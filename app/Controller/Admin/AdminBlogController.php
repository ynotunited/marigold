<?php
namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\CSRF;
use App\Core\Session;
use App\Core\View;

class AdminBlogController extends Controller
{
    private function getMockPosts(): array
    {
        return [
            ['id' => 1, 'title' => 'Top 10 Corporate Gift Ideas for 2026', 'author' => 'Sarah Jenkins', 'category' => 'Gift Guides', 'status' => 'Published', 'views' => 1240, 'date' => '2026-06-15', 'featured' => true],
            ['id' => 2, 'title' => 'The Power of Branded Merchandise', 'author' => 'David Okon', 'category' => 'Marketing', 'status' => 'Published', 'views' => 890, 'date' => '2026-05-22', 'featured' => false],
            ['id' => 3, 'title' => 'Eco-Friendly Gifting: Sustainable Choices', 'author' => 'Sarah Jenkins', 'category' => 'Sustainability', 'status' => 'Draft', 'views' => 0, 'date' => '2026-07-01', 'featured' => false],
            ['id' => 4, 'title' => 'How to Plan Your End of Year Rewards', 'author' => 'David Okon', 'category' => 'Planning', 'status' => 'Scheduled', 'views' => 0, 'date' => '2026-08-15', 'featured' => false],
        ];
    }

    public function index()
    {
        return View::renderTemplate('pages/admin/blog/index', 'admin', [
            'title' => 'Blog Posts | Admin',
            'posts' => $this->getMockPosts(),
        ]);
    }

    public function create()
    {
        return View::renderTemplate('pages/admin/blog/form', 'admin', [
            'title' => 'New Post | Admin',
            'post' => null,
            'mode' => 'create'
        ]);
    }

    public function edit($id)
    {
        $posts = $this->getMockPosts();
        $post = $posts[0]; // just grab first for mock
        $post['excerpt'] = 'Discover the most impactful corporate gifts for the upcoming year to wow your clients and staff.';
        
        return View::renderTemplate('pages/admin/blog/form', 'admin', [
            'title' => 'Edit Post | Admin',
            'post' => $post,
            'mode' => 'edit'
        ]);
    }

    public function store()
    {
        return $this->save(null);
    }

    public function update($id)
    {
        return $this->save($id);
    }

    private function save($id = null)
    {
        if (!CSRF::verify($_POST['csrf_token'] ?? '')) {
            die('Invalid CSRF token');
        }

        Session::set('success', $id ? 'Blog post updated successfully.' : 'Blog post created successfully.');
        $this->redirect('/admin/blog');
    }
}
