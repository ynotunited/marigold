<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Model;
use App\Core\View;

class AdminContentController extends Controller
{
    public function testimonials()
    {
        $db = Model::getDB();

        $rows = $db->query("
            SELECT id, name, company, rating, featured, status, created_at
            FROM testimonials
            ORDER BY created_at DESC
        ")->fetchAll();

        $testimonials = [];
        foreach ($rows as $t) {
            $testimonials[] = [
                'id'       => $t['id'],
                'name'     => $t['name'],
                'company'  => $t['company'] ?: '—',
                'rating'   => (int)$t['rating'],
                'featured' => (bool)$t['featured'],
                'status'   => ucfirst($t['status']),
                'date'     => date('Y-m-d', strtotime($t['created_at'])),
            ];
        }

        return View::renderTemplate('pages/admin/content/testimonials', 'admin', [
            'title' => 'Testimonials | Admin',
            'testimonials' => $testimonials,
        ]);
    }

    public function faqs()
    {
        $db = Model::getDB();

        $rows = $db->query("
            SELECT id, category, question, sort_order, status
            FROM faqs
            ORDER BY sort_order ASC, id ASC
        ")->fetchAll();

        $faqs = [];
        foreach ($rows as $f) {
            $faqs[] = [
                'id'       => $f['id'],
                'category' => $f['category'] ?: 'General',
                'question' => $f['question'],
                'sort'     => (int)$f['sort_order'],
                'status'   => ucfirst($f['status']),
            ];
        }

        return View::renderTemplate('pages/admin/content/faqs', 'admin', [
            'title' => 'FAQs | Admin',
            'faqs' => $faqs,
        ]);
    }

    public function announcements()
    {
        $db = Model::getDB();

        $rows = $db->query("
            SELECT id, message, priority, schedule_start, schedule_end, status
            FROM announcements
            ORDER BY priority DESC, created_at DESC
        ")->fetchAll();

        $announcements = [];
        foreach ($rows as $a) {
            $announcements[] = [
                'id'       => $a['id'],
                'message'  => $a['message'],
                'priority' => $this->priorityLabel((int)$a['priority']),
                'status'   => ucfirst($a['status']),
                'schedule' => $a['schedule_start'] && $a['schedule_end']
                    ? date('M j', strtotime($a['schedule_start'])) . ' - ' . date('M j', strtotime($a['schedule_end']))
                    : 'Always',
            ];
        }

        return View::renderTemplate('pages/admin/content/announcements', 'admin', [
            'title' => 'Announcements | Admin',
            'announcements' => $announcements,
        ]);
    }

    public function popups()
    {
        // No popups table in the schema — return empty list until feature exists.
        $popups = [];

        return View::renderTemplate('pages/admin/content/popups', 'admin', [
            'title' => 'Popups | Admin',
            'popups' => $popups,
        ]);
    }

    private function priorityLabel(int $priority): string
    {
        if ($priority >= 10) return 'Urgent';
        if ($priority >= 5) return 'High';
        if ($priority >= 2) return 'Medium';
        return 'Low';
    }
}
