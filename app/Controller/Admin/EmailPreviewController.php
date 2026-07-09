<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\View;

class EmailPreviewController extends Controller
{
    public function index()
    {
        $templates = [
            'welcome' => 'Welcome Email',
            'order_confirmation' => 'Order Confirmation',
            'payment_received' => 'Payment Received',
            'invoice' => 'Order Invoice',
            'quote_request' => 'Quote Request Confirmation',
            'quote_sent' => 'Quote Sent (Proposal)',
            'password_reset' => 'Password Reset',
            'admin_new_order' => 'Admin: New Order Alert',
            'admin_new_quote' => 'Admin: New Quote Request',
        ];

        return View::renderTemplate('pages/admin/emails/index', 'admin', [
            'title' => 'Email Previews | Admin',
            'templates' => $templates
        ]);
    }

    public function preview($template)
    {
        // Mock data
        $data = [
            'subject' => 'Preview: ' . ucwords(str_replace('_', ' ', $template)),
            'customer_name' => 'David Okon',
            'order_id' => 'ORD-90210',
            'quote_id' => 'QT-1045',
            'amount' => '₦450,000',
            'date' => date('M j, Y'),
            'reset_link' => 'https://marigoldsignature.com/reset-password?token=mock_token_123',
            'action_link' => 'https://marigoldsignature.com/account/orders/ORD-90210'
        ];

        // Render the inner content
        ob_start();
        extract($data);
        include __DIR__ . '/../../../View/emails/templates/' . $template . '.php';
        $content = ob_get_clean();

        // Render the wrapper layout
        ob_start();
        include __DIR__ . '/../../../View/emails/layout.php';
        $html = ob_get_clean();

        // Output raw HTML so it looks like an email in the browser
        header('Content-Type: text/html');
        echo $html;
        exit;
    }
}
