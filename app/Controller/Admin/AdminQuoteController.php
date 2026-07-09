<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\View;

class AdminQuoteController extends Controller
{
    private function getMockQuotes(): array
    {
        return [
            ['id' => 'QT-1046', 'customer' => 'TechCorp Nigeria',   'email' => 'procurement@techcorp.ng', 'items' => 3,  'status' => 'Pending',   'date' => date('Y-m-d', strtotime('-22 min'))],
            ['id' => 'QT-1045', 'customer' => 'David Okon',         'email' => 'david@techsol.ng',        'items' => 2,  'status' => 'Pending',   'date' => date('Y-m-d', strtotime('-1 day'))],
            ['id' => 'QT-1040', 'customer' => 'Seun Adeyemi',       'email' => 'seun@adeyemi.ng',         'items' => 8,  'status' => 'Approved',  'date' => date('Y-m-d', strtotime('-5 days'))],
            ['id' => 'QT-1035', 'customer' => 'Apex Industries Ltd', 'email' => 'buy@apex.ng',             'items' => 5,  'status' => 'Rejected',  'date' => date('Y-m-d', strtotime('-10 days'))],
            ['id' => 'QT-1022', 'customer' => 'David Okon',         'email' => 'david@techsol.ng',        'items' => 20, 'status' => 'Converted', 'date' => date('Y-m-d', strtotime('-30 days'))],
            ['id' => 'QT-0998', 'customer' => 'NovaTech Ltd',       'email' => 'info@novatech.ng',        'items' => 4,  'status' => 'Expired',   'date' => date('Y-m-d', strtotime('-45 days'))],
        ];
    }

    public function index()
    {
        return View::renderTemplate('pages/admin/quotes/index', 'admin', [
            'title' => 'Quotes | Admin',
            'quotes' => $this->getMockQuotes(),
        ]);
    }

    public function show($id)
    {
        $quote = [
            'id' => $id, 'date' => date('Y-m-d', strtotime('-1 day')), 'status' => 'Pending',
            'valid_until' => date('Y-m-d', strtotime('+13 days')),
            'customer' => ['name' => 'David Okon', 'email' => 'david@techsol.ng', 'phone' => '+234 801 234 5678', 'company' => 'TechSolutions Inc'],
            'items' => [
                ['name' => 'Custom Moleskine Notebook (Branded)', 'qty' => 500, 'unit_price' => null, 'total' => null, 'notes' => 'Gold foil stamping on cover'],
                ['name' => 'Premium Metal Pen Set',               'qty' => 500, 'unit_price' => null, 'total' => null, 'notes' => 'Laser engraving on barrel'],
            ],
            'files' => [
                ['name' => 'company_logo.ai', 'size' => '2.4 MB', 'icon' => 'file'],
                ['name' => 'brand_guidelines.pdf', 'size' => '8.1 MB', 'icon' => 'file-text'],
            ],
            'customer_notes' => 'Need delivery to 3 locations by end of December. Would like to see proofs before production begins.',
            'messages' => [
                ['sender' => 'David Okon', 'is_customer' => true, 'message' => 'Hello Sarah, I submitted this quote request. We need these delivered by the 15th of next month. Is that possible?', 'time' => date('M j, Y h:i A', strtotime('-24 hours'))],
                ['sender' => 'Sarah Jenkins', 'is_customer' => false, 'message' => 'Hi David, thanks for reaching out. Yes, we can definitely meet that timeline for the notebooks, but the metal pens might require expedited shipping. Let me crunch the numbers and I will update this quote with pricing shortly.', 'time' => date('M j, Y h:i A', strtotime('-18 hours'))],
            ],
        ];
        return View::renderTemplate('pages/admin/quotes/show', 'admin', ['title' => "Quote $id | Admin", 'quote' => $quote]);
    }
}
