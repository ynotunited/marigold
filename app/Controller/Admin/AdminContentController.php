<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\View;

class AdminContentController extends Controller
{
    public function testimonials()
    {
        $testimonials = [
            ['id' => 1, 'name' => 'John Doe', 'company' => 'Acme Corp', 'rating' => 5, 'featured' => true, 'status' => 'Active', 'date' => '2026-05-12'],
            ['id' => 2, 'name' => 'Jane Smith', 'company' => 'TechFlow', 'rating' => 4, 'featured' => false, 'status' => 'Active', 'date' => '2026-06-01'],
            ['id' => 3, 'name' => 'Michael Brown', 'company' => 'Globex', 'rating' => 5, 'featured' => true, 'status' => 'Pending', 'date' => '2026-06-20'],
        ];
        return View::renderTemplate('pages/admin/content/testimonials', 'admin', ['title' => 'Testimonials | Admin', 'testimonials' => $testimonials]);
    }

    public function faqs()
    {
        $faqs = [
            ['id' => 1, 'category' => 'Shipping', 'question' => 'How long does delivery take?', 'sort' => 1, 'status' => 'Active'],
            ['id' => 2, 'category' => 'Shipping', 'question' => 'Do you ship internationally?', 'sort' => 2, 'status' => 'Active'],
            ['id' => 3, 'category' => 'Corporate', 'question' => 'What is the minimum order quantity for custom branding?', 'sort' => 1, 'status' => 'Active'],
            ['id' => 4, 'category' => 'Corporate', 'question' => 'Can I get a sample before ordering?', 'sort' => 2, 'status' => 'Draft'],
        ];
        return View::renderTemplate('pages/admin/content/faqs', 'admin', ['title' => 'FAQs | Admin', 'faqs' => $faqs]);
    }

    public function announcements()
    {
        $announcements = [
            ['id' => 1, 'message' => 'Free shipping on all corporate orders over ₦500,000!', 'priority' => 'High', 'status' => 'Active', 'schedule' => 'Always'],
            ['id' => 2, 'message' => 'Holiday discount: Use code XMAS26 for 10% off.', 'priority' => 'Medium', 'status' => 'Scheduled', 'schedule' => 'Dec 1 - Dec 25'],
            ['id' => 3, 'message' => 'We are experiencing slight delivery delays due to weather.', 'priority' => 'Urgent', 'status' => 'Draft', 'schedule' => 'Manual'],
        ];
        return View::renderTemplate('pages/admin/content/announcements', 'admin', ['title' => 'Announcements | Admin', 'announcements' => $announcements]);
    }

    public function popups()
    {
        $popups = [
            ['id' => 1, 'title' => 'Join our Newsletter', 'trigger' => 'Exit Intent', 'devices' => 'All', 'status' => 'Active', 'views' => 4500, 'conversions' => 120],
            ['id' => 2, 'title' => 'New Corporate Gifting Guide', 'trigger' => 'Time Delay (5s)', 'devices' => 'Desktop', 'status' => 'Draft', 'views' => 0, 'conversions' => 0],
        ];
        return View::renderTemplate('pages/admin/content/popups', 'admin', ['title' => 'Popups | Admin', 'popups' => $popups]);
    }
}
