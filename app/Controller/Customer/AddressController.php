<?php

namespace App\Controller\Customer;


use App\Core\Controller;
use App\Core\View;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = [
            [
                'id' => 1,
                'label' => 'Head Office',
                'name' => 'David Okon',
                'company' => 'TechSolutions Inc',
                'street' => '14 Adeola Odeku St',
                'city' => 'Victoria Island',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'phone' => '+234 801 234 5678',
                'type' => 'Both',
                'is_default' => true
            ],
            [
                'id' => 2,
                'label' => 'Warehouse / Delivery',
                'name' => 'David Okon',
                'company' => 'TechSolutions Inc',
                'street' => '7 Industrial Estate Rd',
                'city' => 'Apapa',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'phone' => '+234 801 234 5678',
                'type' => 'Shipping',
                'is_default' => false
            ]
        ];

        return View::renderTemplate('pages/customer/address/index', 'customer', [
            'title' => 'Address Book | Marigold Signature',
            'addresses' => $addresses
        ]);
    }
}
