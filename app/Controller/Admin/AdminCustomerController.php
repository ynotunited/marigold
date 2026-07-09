<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\View;

class AdminCustomerController extends Controller
{
    private function getMockCustomers(): array
    {
        return [
            ['id' => 1042, 'name' => 'David Okon', 'email' => 'david@techsol.ng', 'company' => 'TechSolutions Inc', 'type' => 'Corporate', 'status' => 'Active', 'orders' => 12, 'spent' => 2450000, 'registered' => '2024-03-12'],
            ['id' => 1045, 'name' => 'Adaeze Williams', 'email' => 'adaeze@company.ng', 'company' => 'Apex Group', 'type' => 'Corporate', 'status' => 'Active', 'orders' => 4, 'spent' => 820000, 'registered' => '2024-11-05'],
            ['id' => 1089, 'name' => 'Seun Adeyemi', 'email' => 'seun@adeyemi.ng', 'company' => null, 'type' => 'Individual', 'status' => 'Active', 'orders' => 1, 'spent' => 1200000, 'registered' => '2025-01-20'],
            ['id' => 1102, 'name' => 'Blessing Nwosu', 'email' => 'blessing@nw.ng', 'company' => null, 'type' => 'Individual', 'status' => 'Inactive', 'orders' => 0, 'spent' => 0, 'registered' => '2025-05-14'],
            ['id' => 1120, 'name' => 'TechCorp Nigeria', 'email' => 'procurement@techcorp.ng', 'company' => 'TechCorp', 'type' => 'Corporate', 'status' => 'Active', 'orders' => 25, 'spent' => 15400000, 'registered' => '2023-08-01'],
        ];
    }

    public function index()
    {
        return View::renderTemplate('pages/admin/customers/index', 'admin', [
            'title' => 'Customers | Admin',
            'customers' => $this->getMockCustomers(),
        ]);
    }

    public function show($id)
    {
        $customer = [
            'id' => $id, 'name' => 'David Okon', 'email' => 'david@techsol.ng', 'phone' => '+234 801 234 5678', 'company' => 'TechSolutions Inc',
            'type' => 'Corporate', 'status' => 'Active', 'registered' => 'Mar 12, 2024', 'last_login' => '2 hours ago',
            'lifetime_value' => 2450000, 'total_orders' => 12, 'avg_order_value' => 204166,
            'addresses' => [
                ['label' => 'HQ (Default)', 'street' => '14 Adeola Odeku St', 'city' => 'Victoria Island', 'state' => 'Lagos'],
                ['label' => 'Branch Office', 'street' => '22 Allen Ave', 'city' => 'Ikeja', 'state' => 'Lagos'],
            ],
            'recent_orders' => [
                ['id' => 'ORD-9823', 'date' => '2 days ago', 'total' => 450000, 'status' => 'Processing'],
                ['id' => 'ORD-9810', 'date' => '15 days ago', 'total' => 1200000, 'status' => 'Completed'],
            ],
            'recent_quotes' => [
                ['id' => 'QT-1045', 'date' => '1 day ago', 'items' => 2, 'status' => 'Pending'],
                ['id' => 'QT-1022', 'date' => '1 month ago', 'items' => 20, 'status' => 'Converted'],
            ],
            'internal_notes' => 'Key account. Always requires custom branding on moleskine notebooks. Prefers delivery on Thursdays.',
        ];
        return View::renderTemplate('pages/admin/customers/show', 'admin', ['title' => "Customer $id | Admin", 'customer' => $customer]);
    }
}
