<?php
namespace App\Controller;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;
use App\Service\InvoiceService;
use App\Service\AuditService;
use App\Core\PaymentGatewayFactory;
use App\Core\Logger;

class InvoicePublicController extends Controller
{
    public function show($token)
    {
        $inv = InvoiceService::getByToken($token);
        if (!$inv) {
            http_response_code(404);
            return View::renderTemplate('pages/public/errors/404', 'main', ['title' => 'Invoice not found']);
        }

        if (in_array($inv['status'], ['draft','sent'], true)) {
            InvoiceService::markViewed((int) $inv['id']);
            $inv['status'] = 'viewed';
        }

        if ($inv['status'] === 'cancelled') {
            return View::renderTemplate('pages/public/invoice', 'main', [
                'title' => 'Invoice Cancelled',
                'invoice' => $inv,
                'cancelled' => true,
            ]);
        }

        if ($inv['status'] === 'paid') {
            return View::renderTemplate('pages/public/invoice-success', 'main', [
                'title' => 'Invoice Paid',
                'invoice' => $inv,
            ]);
        }

        return View::renderTemplate('pages/public/invoice', 'main', [
            'title' => "Invoice {$inv['invoice_number']}",
            'invoice' => $inv,
            'cancelled' => false,
        ]);
    }

    public function pay($token)
    {
        $inv = InvoiceService::getByToken($token);
        if (!$inv) {
            http_response_code(404);
            $this->json(['error' => 'Invoice not found'], 404);
            return;
        }

        if ($inv['status'] === 'paid') {
            $this->json(['error' => 'Invoice is already paid'], 400);
            return;
        }

        if ($inv['status'] === 'cancelled') {
            $this->json(['error' => 'Invoice has been cancelled'], 400);
            return;
        }

        $gatewayName = $_POST['gateway'] ?? 'paystack';
        try {
            $gateway = PaymentGatewayFactory::make(PaymentGatewayFactory::normalize($gatewayName));
        } catch (\Throwable $e) {
            $this->json(['error' => 'Invalid payment gateway'], 400);
            return;
        }

        $amount = (float) $inv['total'];
        if ($amount <= 0) {
            $this->json(['error' => 'Invoice total must be greater than zero'], 400);
            return;
        }

        $reference = 'INV_' . strtoupper(bin2hex(random_bytes(8))) . time();
        $amountKobo = (int) round($amount * 100);
        $currency = $inv['currency'] ?: 'NGN';
        $email = $inv['customer_email'];
        $baseUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
        $callbackUrl = $baseUrl . '/invoice/' . $token . '/callback';

        $initParams = [
            'email'        => $email,
            'amount_kobo'  => $amountKobo,
            'currency'     => $currency,
            'reference'    => $reference,
            'callback_url' => $callbackUrl,
            'redirect_url' => $callbackUrl,
            'metadata'     => ['invoice_id' => $inv['id'], 'invoice_number' => $inv['invoice_number']],
        ];

        if ($gatewayName === 'flutterwave') {
            $initParams['customer_name'] = $inv['customer_name'];
            $initParams['customer_phone'] = $inv['customer_phone'] ?? '';
            $initParams['redirect_url'] = $callbackUrl;
        }

        try {
            $result = $gateway->initialize($initParams);
        } catch (\Throwable $e) {
            Logger::error("Invoice payment init failed: " . $e->getMessage(), 'invoice');
            $this->json(['error' => 'Could not initialize payment. Please try again.'], 502);
            return;
        }

        InvoiceService::recordPayment(
            (int) $inv['id'],
            $gatewayName,
            $reference,
            $amount,
            $currency,
            'pending',
            json_encode($result['raw'] ?? [])
        );

        if (!empty($result['authorization_url'])) {
            $this->json(['url' => $result['authorization_url'], 'reference' => $reference]);
        } else {
            $this->json([
                'reference' => $reference,
                'simulated' => true,
                'redirect'  => $baseUrl . '/invoice/' . $token . '/callback?reference=' . $reference . '&status=success',
            ]);
        }
    }

    public function callback($token)
    {
        $inv = InvoiceService::getByToken($token);
        if (!$inv) {
            http_response_code(404);
            return View::renderTemplate('pages/public/errors/404', 'main', ['title' => 'Invoice not found']);
        }

        $reference = $_GET['reference'] ?? ($_POST['reference'] ?? '');
        $gatewayName = $inv['payment_gateway'] ?: 'paystack';

        $payment = InvoiceService::findByReference($reference);
        if (!$payment) {
            $this->redirect('/invoice/' . $token);
            return;
        }

        $gateway = PaymentGatewayFactory::make(PaymentGatewayFactory::normalize($payment['gateway']));

        try {
            $result = $gateway->verify($reference);
        } catch (\Throwable $e) {
            Logger::error("Invoice payment verify failed: " . $e->getMessage(), 'invoice');
            $this->redirect('/invoice/' . $token);
            return;
        }

        if ($result['status'] === 'success') {
            $db = Model::getDB();
            $db->prepare("UPDATE invoice_payments SET status = 'success', paid_at = NOW(), response_json = :resp WHERE reference = :ref")
               ->execute(['resp' => json_encode($result['raw'] ?? []), 'ref' => $reference]);

            InvoiceService::markPaid((int) $inv['id'], $payment['gateway'], $reference);
            AuditService::act('invoice.paid', 'invoices', $inv['id'], null, [
                'gateway'   => $payment['gateway'],
                'reference' => $reference,
                'amount'    => $inv['total'],
            ]);
        } else {
            $db = Model::getDB();
            $db->prepare("UPDATE invoice_payments SET status = 'failed', response_json = :resp WHERE reference = :ref")
               ->execute(['resp' => json_encode($result['raw'] ?? []), 'ref' => $reference]);
        }

        $this->redirect('/invoice/' . $token);
    }
}
