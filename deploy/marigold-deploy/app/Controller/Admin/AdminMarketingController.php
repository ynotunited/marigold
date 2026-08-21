<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\Money;
use App\Core\View;

class AdminMarketingController extends Controller
{
    public function coupons()
    {
        $db = Model::getDB();

        $rows = $db->query("
            SELECT id, code, type, value, minimum_spend, expiry, usage_limit, status, created_at
            FROM coupons
            ORDER BY created_at DESC
        ")->fetchAll();

        $coupons = [];
        foreach ($rows as $c) {
            $now = time();
            $rawStatus = $c['status'];
            if ($rawStatus === 'active' && $c['expiry'] && strtotime($c['expiry']) < $now) {
                $status = 'Expired';
            } elseif ($rawStatus === 'active') {
                $status = 'Active';
            } else {
                $status = ucfirst($rawStatus);
            }

            $coupons[] = [
                'id'        => $c['id'],
                'code'      => $c['code'],
                'type'      => ucfirst($c['type']),
                'value'     => $c['type'] === 'percentage' ? ($c['value'] . '%') : Money::format((float)$c['value']),
                'min_spend' => $c['minimum_spend'] ? Money::format((float)$c['minimum_spend']) : Money::format(0),
                'usage'     => ($c['usage_limit'] ?: '∞'),
                'expiry'    => $c['expiry'] ? date('Y-m-d', strtotime($c['expiry'])) : 'Never',
                'status'    => $status,
            ];
        }

        return View::renderTemplate('pages/admin/marketing/coupons', 'admin', ['title' => 'Coupons | Admin', 'coupons' => $coupons]);
    }

    public function reviews()
    {
        $db = Model::getDB();

        $rows = $db->query("
            SELECT
                r.id,
                r.rating,
                r.review,
                r.status,
                r.created_at,
                p.name AS product_name,
                u.first_name,
                u.last_name,
                u.email
            FROM reviews r
            LEFT JOIN products p ON p.id = r.product_id
            LEFT JOIN users u ON u.id = r.customer_id
            ORDER BY r.created_at DESC
        ")->fetchAll();

        $reviews = [];
        foreach ($rows as $r) {
            $reviews[] = [
                'id'       => $r['id'],
                'product'  => $r['product_name'] ?: 'Product',
                'customer' => trim(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? '')) ?: ($r['email'] ?: 'Customer'),
                'rating'   => (int)$r['rating'],
                'comment'  => $r['review'] ?: '',
                'date'     => date('M j, Y', strtotime($r['created_at'])),
                'status'   => ucfirst($r['status']),
            ];
        }

        return View::renderTemplate('pages/admin/marketing/reviews', 'admin', ['title' => 'Product Reviews | Admin', 'reviews' => $reviews]);
    }
}
