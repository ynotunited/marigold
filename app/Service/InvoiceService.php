<?php

namespace App\Service;

use App\Core\Model;
use App\Core\Logger;

class InvoiceService
{
    public static function generateNumber(): string
    {
        $db = Model::getDB();
        $year = date('Y');
        $stmt = $db->query("SELECT COUNT(*) FROM invoices WHERE YEAR(created_at) = {$year}");
        $count = (int) $stmt->fetchColumn() + 1;
        return 'MS-INV-' . $year . '-' . str_pad((string) $count, 5, '0', STR_PAD_LEFT);
    }

    public static function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public static function recalculate(int $invoiceId): void
    {
        $db = Model::getDB();

        $stmt = $db->prepare("SELECT COALESCE(SUM(total), 0) AS subtotal FROM invoice_items WHERE invoice_id = :id");
        $stmt->execute(['id' => $invoiceId]);
        $subtotal = (float) $stmt->fetchColumn();

        $stmt = $db->prepare("SELECT tax_rate, discount_amount FROM invoices WHERE id = :id");
        $stmt->execute(['id' => $invoiceId]);
        $inv = $stmt->fetch();
        $taxRate = (float) ($inv['tax_rate'] ?? 7.5);
        $discount = (float) ($inv['discount_amount'] ?? 0);

        $taxAmount = round($subtotal * $taxRate / 100, 2);
        $total = max(0, $subtotal + $taxAmount - $discount);

        $upd = $db->prepare("UPDATE invoices SET subtotal = :sub, tax_amount = :tax, total = :total WHERE id = :id");
        $upd->execute([
            'sub'   => $subtotal,
            'tax'   => $taxAmount,
            'total' => $total,
            'id'    => $invoiceId,
        ]);
    }

    public static function addItem(int $invoiceId, array $data): int
    {
        $db = Model::getDB();
        $qty = max(1, (int) ($data['quantity'] ?? 1));
        $price = (float) ($data['unit_price'] ?? 0);
        $total = round($qty * $price, 2);

        $stmt = $db->prepare(
            "INSERT INTO invoice_items (invoice_id, product_id, name, description, quantity, unit_price, total, is_custom)
             VALUES (:inv, :pid, :name, :desc, :qty, :price, :total, :custom)"
        );
        $stmt->execute([
            'inv'    => $invoiceId,
            'pid'    => $data['product_id'] ?? null,
            'name'   => $data['name'] ?? '',
            'desc'   => $data['description'] ?? null,
            'qty'    => $qty,
            'price'  => $price,
            'total'  => $total,
            'custom' => !empty($data['is_custom']) ? 1 : 0,
        ]);
        $itemId = (int) $db->lastInsertId();

        self::recalculate($invoiceId);
        return $itemId;
    }

    public static function removeItem(int $invoiceId, int $itemId): void
    {
        $db = Model::getDB();
        $stmt = $db->prepare("DELETE FROM invoice_items WHERE id = :id AND invoice_id = :inv");
        $stmt->execute(['id' => $itemId, 'inv' => $invoiceId]);
        self::recalculate($invoiceId);
    }

    public static function getByToken(string $token): ?array
    {
        $db = Model::getDB();
        $stmt = $db->prepare("SELECT * FROM invoices WHERE token = :token LIMIT 1");
        $stmt->execute(['token' => $token]);
        $inv = $stmt->fetch();
        if (!$inv) return null;

        $itemStmt = $db->prepare("SELECT * FROM invoice_items WHERE invoice_id = :id ORDER BY id ASC");
        $itemStmt->execute(['id' => $inv['id']]);
        $inv['items'] = $itemStmt->fetchAll();

        return $inv;
    }

    public static function markViewed(int $id): void
    {
        $db = Model::getDB();
        $db->prepare("UPDATE invoices SET status = 'viewed' WHERE id = :id AND status IN ('sent','draft')")->execute(['id' => $id]);
    }

    public static function markPaid(int $id, string $gateway, string $reference): void
    {
        $db = Model::getDB();
        $db->prepare(
            "UPDATE invoices SET status = 'paid', payment_gateway = :gw, payment_reference = :ref, paid_at = NOW() WHERE id = :id"
        )->execute(['gw' => $gateway, 'ref' => $reference, 'id' => $id]);
    }

    public static function recordPayment(int $invoiceId, string $gateway, string $reference, float $amount, string $currency = 'NGN', string $status = 'pending', ?string $responseJson = null): int
    {
        $db = Model::getDB();
        $stmt = $db->prepare(
            "INSERT INTO invoice_payments (invoice_id, reference, gateway, amount, currency, status, response_json, paid_at)
             VALUES (:inv, :ref, :gw, :amt, :cur, :st, :resp, " . ($status === 'success' ? 'NOW()' : 'NULL') . ")"
        );
        $stmt->execute([
            'inv'   => $invoiceId,
            'ref'   => $reference,
            'gw'    => $gateway,
            'amt'   => $amount,
            'cur'   => $currency,
            'st'    => $status,
            'resp'  => $responseJson,
        ]);
        return (int) $db->lastInsertId();
    }

    public static function findByReference(string $reference): ?array
    {
        $db = Model::getDB();
        $stmt = $db->prepare("SELECT * FROM invoice_payments WHERE reference = :ref LIMIT 1");
        $stmt->execute(['ref' => $reference]);
        return $stmt->fetch() ?: null;
    }
}
