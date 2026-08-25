<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;
use App\Core\CSRF;
use App\Service\InvoiceService;
use App\Service\AuditService;

class InvoiceController extends Controller
{
    public function index()
    {
        $db = Model::getDB();
        $status = $_GET['status'] ?? '';

        $where = '';
        $params = [];
        if ($status && in_array($status, ['draft','sent','viewed','paid','cancelled','expired'], true)) {
            $where = 'WHERE i.status = :status';
            $params['status'] = $status;
        }

        $invoices = $db->prepare("
            SELECT i.id, i.invoice_number, i.customer_name, i.customer_email, i.status,
                   i.total, i.currency, i.created_at, i.paid_at
            FROM invoices i
            {$where}
            ORDER BY i.created_at DESC
            LIMIT 200
        ");
        $invoices->execute($params);
        $rows = $invoices->fetchAll();

        return View::renderTemplate('pages/admin/invoices/index', 'admin', [
            'title'   => 'Invoices | Admin',
            'invoices' => $rows,
            'current' => $status,
        ]);
    }

    public function create()
    {
        $db = Model::getDB();
        $products = $db->query("SELECT id, name, price, sale_price FROM products WHERE deleted_at IS NULL ORDER BY name ASC")->fetchAll();

        return View::renderTemplate('pages/admin/invoices/create', 'admin', [
            'title'    => 'Create Invoice | Admin',
            'products' => $products,
        ]);
    }

    public function store()
    {
        CSRF::verify($_POST['csrf_token'] ?? '');

        $db = Model::getDB();
        $data = $_POST;

        $customerName  = trim($data['customer_name'] ?? '');
        $customerEmail = trim($data['customer_email'] ?? '');
        if ($customerName === '' || $customerEmail === '') {
            \App\Core\Session::error('Customer name and email are required.');
            $this->redirect('/admin/invoices/create');
        }

        $invoiceNumber = InvoiceService::generateNumber();
        $token = InvoiceService::generateToken();
        $taxRate = (float) ($data['tax_rate'] ?? 7.5);
        $discount = (float) ($data['discount'] ?? 0);
        $notes = trim($data['notes'] ?? '');
        $dueDate = $data['due_date'] ?: null;

        $stmt = $db->prepare(
            "INSERT INTO invoices (invoice_number, token, customer_name, customer_email, customer_phone,
                status, tax_rate, discount_amount, notes, due_date, created_by)
             VALUES (:num, :tok, :name, :email, :phone, 'draft', :tax, :disc, :notes, :due, :uid)"
        );
        $stmt->execute([
            'num'  => $invoiceNumber,
            'tok'  => $token,
            'name' => $customerName,
            'email'=> $customerEmail,
            'phone'=> $data['customer_phone'] ?? null,
            'tax'  => $taxRate,
            'disc' => $discount,
            'notes'=> $notes ?: null,
            'due'  => $dueDate,
            'uid'  => $_SESSION['user_id'] ?? null,
        ]);
        $invoiceId = (int) $db->lastInsertId();

        // Add line items from the form
        $names  = $data['item_name'] ?? [];
        $descs  = $data['item_desc'] ?? [];
        $qtys   = $data['item_qty'] ?? [];
        $prices = $data['item_price'] ?? [];
        $pids   = $data['item_product_id'] ?? [];
        $customs= $data['item_custom'] ?? [];

        for ($i = 0; $i < count($names); $i++) {
            $n = trim($names[$i] ?? '');
            if ($n === '') continue;
            InvoiceService::addItem($invoiceId, [
                'name'       => $n,
                'description'=> $descs[$i] ?? null,
                'quantity'   => max(1, (int) ($qtys[$i] ?? 1)),
                'unit_price' => (float) ($prices[$i] ?? 0),
                'product_id' => $pids[$i] ?: null,
                'is_custom'  => !empty($customs[$i]),
            ]);
        }

        AuditService::act('invoice.created', 'invoices', $invoiceId, null, ['number' => $invoiceNumber]);
        \App\Core\Session::success("Invoice {$invoiceNumber} created.");
        $this->redirect("/admin/invoices/{$invoiceId}");
    }

    public function show($id)
    {
        $db = Model::getDB();
        $stmt = $db->prepare("SELECT * FROM invoices WHERE id = :id OR invoice_number = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $inv = $stmt->fetch();
        if (!$inv) {
            http_response_code(404);
            return View::renderTemplate('pages/public/errors/404', 'admin', ['title' => 'Invoice not found']);
        }

        $itemStmt = $db->prepare("SELECT * FROM invoice_items WHERE invoice_id = :id ORDER BY id ASC");
        $itemStmt->execute(['id' => $inv['id']]);
        $inv['items'] = $itemStmt->fetchAll();

        $payStmt = $db->prepare("SELECT * FROM invoice_payments WHERE invoice_id = :id ORDER BY created_at DESC");
        $payStmt->execute(['id' => $inv['id']]);
        $inv['payments'] = $payStmt->fetchAll();

        $baseUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
        $invoiceUrl = $baseUrl . '/invoice/' . $inv['token'];

        return View::renderTemplate('pages/admin/invoices/show', 'admin', [
            'title'       => "Invoice {$inv['invoice_number']} | Admin",
            'invoice'     => $inv,
            'invoice_url' => $invoiceUrl,
        ]);
    }

    public function addItem($id)
    {
        CSRF::verify($_POST['csrf_token'] ?? '');
        $db = Model::getDB();
        $stmt = $db->prepare("SELECT id FROM invoices WHERE id = :id");
        $stmt->execute(['id' => $id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            return;
        }

        InvoiceService::addItem((int) $id, [
            'name'       => trim($_POST['name'] ?? ''),
            'description'=> trim($_POST['description'] ?? null),
            'quantity'   => max(1, (int) ($_POST['quantity'] ?? 1)),
            'unit_price' => (float) ($_POST['unit_price'] ?? 0),
            'product_id' => $_POST['product_id'] ?: null,
            'is_custom'  => !empty($_POST['is_custom']),
        ]);

        \App\Core\Session::success('Item added.');
        $this->redirect("/admin/invoices/{$id}");
    }

    public function removeItem($id, $itemId)
    {
        CSRF::verify($_POST['csrf_token'] ?? '');
        InvoiceService::removeItem((int) $id, (int) $itemId);
        \App\Core\Session::success('Item removed.');
        $this->redirect("/admin/invoices/{$id}");
    }

    public function update($id)
    {
        CSRF::verify($_POST['csrf_token'] ?? '');
        $db = Model::getDB();
        $data = $_POST;

        $stmt = $db->prepare("SELECT id FROM invoices WHERE id = :id");
        $stmt->execute(['id' => $id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            return;
        }

        $fields = [
            'customer_name'  => trim($data['customer_name'] ?? ''),
            'customer_email' => trim($data['customer_email'] ?? ''),
            'customer_phone' => $data['customer_phone'] ?? null,
            'tax_rate'       => (float) ($data['tax_rate'] ?? 7.5),
            'discount_amount'=> (float) ($data['discount'] ?? 0),
            'notes'          => trim($data['notes'] ?? '') ?: null,
            'due_date'       => $data['due_date'] ?: null,
        ];

        $sql = "UPDATE invoices SET customer_name = :name, customer_email = :email, customer_phone = :phone,
                tax_rate = :tax, discount_amount = :disc, notes = :notes, due_date = :due WHERE id = :id";
        $upd = $db->prepare($sql);
        $upd->execute([
            'name' => $fields['customer_name'],
            'email'=> $fields['customer_email'],
            'phone'=> $fields['customer_phone'],
            'tax'  => $fields['tax_rate'],
            'disc' => $fields['discount_amount'],
            'notes'=> $fields['notes'],
            'due'  => $fields['due_date'],
            'id'   => $id,
        ]);

        InvoiceService::recalculate((int) $id);
        \App\Core\Session::success('Invoice updated.');
        $this->redirect("/admin/invoices/{$id}");
    }

    public function send($id)
    {
        CSRF::verify($_POST['csrf_token'] ?? '');
        $db = Model::getDB();
        $db->prepare("UPDATE invoices SET status = 'sent' WHERE id = :id AND status IN ('draft','sent','viewed')")->execute(['id' => $id]);
        AuditService::act('invoice.sent', 'invoices', $id);
        \App\Core\Session::success('Invoice marked as sent.');
        $this->redirect("/admin/invoices/{$id}");
    }

    public function cancel($id)
    {
        CSRF::verify($_POST['csrf_token'] ?? '');
        $db = Model::getDB();
        $db->prepare("UPDATE invoices SET status = 'cancelled' WHERE id = :id AND status NOT IN ('paid','cancelled')")->execute(['id' => $id]);
        AuditService::act('invoice.cancelled', 'invoices', $id);
        \App\Core\Session::success('Invoice cancelled.');
        $this->redirect("/admin/invoices/{$id}");
    }

    public function destroy($id)
    {
        CSRF::verify($_POST['csrf_token'] ?? '');
        $db = Model::getDB();
        $stmt = $db->prepare("SELECT id, invoice_number FROM invoices WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $inv = $stmt->fetch();
        if ($inv) {
            $db->prepare("DELETE FROM invoices WHERE id = :id")->execute(['id' => $id]);
            AuditService::act('invoice.deleted', 'invoices', $id, null, ['number' => $inv['invoice_number']]);
        }
        \App\Core\Session::success('Invoice deleted.');
        $this->redirect('/admin/invoices');
    }
}
