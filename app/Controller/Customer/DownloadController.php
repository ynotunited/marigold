<?php

namespace App\Controller\Customer;


use App\Core\Controller;
use App\Core\View;

class DownloadController extends Controller
{
    public function index()
    {
        $downloads = [
            [
                'name' => 'Invoice — ORD-9823',
                'type' => 'Invoice',
                'size' => '124 KB',
                'date' => date('Y-m-d', strtotime('-2 days')),
                'icon' => 'file-text',
                'url' => '#'
            ],
            [
                'name' => 'Invoice — ORD-9810',
                'type' => 'Invoice',
                'size' => '89 KB',
                'date' => date('Y-m-d', strtotime('-15 days')),
                'icon' => 'file-text',
                'url' => '#'
            ],
            [
                'name' => 'Marigold Signature — Product Catalogue 2026',
                'type' => 'Catalogue',
                'size' => '8.4 MB',
                'date' => date('Y-m-d', strtotime('-30 days')),
                'icon' => 'book-open',
                'url' => '#'
            ],
            [
                'name' => 'Executive Leather Folio — Care Manual',
                'type' => 'Manual',
                'size' => '340 KB',
                'date' => date('Y-m-d', strtotime('-45 days')),
                'icon' => 'file',
                'url' => '#'
            ]
        ];

        return View::renderTemplate('pages/customer/downloads', 'customer', [
            'title' => 'Downloads | Marigold Signature',
            'downloads' => $downloads
        ]);
    }
}
