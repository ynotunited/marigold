<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\View;
use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        // Instead of hitting an empty DB, let's inject dummy content for now to fulfill the premium visual requirement.
        // In a real scenario, this would be: $posts = Post::getAllPublished();
        $posts = [
            [
                'title' => 'Elevating Corporate Gifting: Trends for 2026',
                'slug' => 'elevating-corporate-gifting-trends-2026',
                'excerpt' => 'Discover how top companies are reinventing their corporate gifting strategies to foster deeper connections and brand loyalty in the modern business landscape.',
                'featured_image' => 'https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?q=80&w=800&auto=format&fit=crop',
                'first_name' => 'Sarah',
                'last_name' => 'Jenkins',
                'published_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'title' => 'The Psychology of Premium Merchandise',
                'slug' => 'psychology-premium-merchandise',
                'excerpt' => 'Why does a high-quality pen or a custom leather folio make such a lasting impact? We dive into the psychology behind premium corporate gifts.',
                'featured_image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=800&auto=format&fit=crop',
                'first_name' => 'David',
                'last_name' => 'Okon',
                'published_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
            ],
            [
                'title' => 'Sustainable Gifting: A Necessity, Not a Trend',
                'slug' => 'sustainable-gifting-necessity',
                'excerpt' => 'Eco-friendly corporate merchandise is no longer just nice to have. Learn how to align your brand values with your corporate gifting program.',
                'featured_image' => 'https://images.unsplash.com/photo-1610555356070-d1fb336f1ae8?q=80&w=800&auto=format&fit=crop',
                'first_name' => 'Aisha',
                'last_name' => 'Bello',
                'published_at' => date('Y-m-d H:i:s', strtotime('-12 days'))
            ]
        ];

        return View::renderTemplate('pages/public/blog/index', 'main', [
            'title' => 'Insights & News | Marigold Signature',
            'posts' => $posts
        ]);
    }

    public function show($slug)
    {
        // Dummy data for the detail page
        $post = [
            'title' => 'Elevating Corporate Gifting: Trends for 2026',
            'slug' => 'elevating-corporate-gifting-trends-2026',
            'content' => '<p>Corporate gifting has evolved far beyond the ubiquitous branded pen or standard-issue mug. In 2026, the focus has shifted entirely towards intentionality, personalization, and premium quality.</p><h2>The Shift to Quality over Quantity</h2><p>Companies are realizing that a single, high-quality item leaves a much stronger and longer-lasting impression than a bag full of easily disposable trinkets. Premium merchandise reflects the value you place on the relationship, whether it\'s with a client, an employee, or a partner.</p><h2>Personalization at Scale</h2><p>Advancements in customization technology mean that personalization is no longer restricted to just adding a logo. Engraving individual names, customizing packaging, and tailoring the gift selection to the recipient\'s known preferences are becoming the new standard.</p><blockquote>"The right gift at the right time can solidify a partnership for years to come."</blockquote><p>As we navigate this new landscape, Marigold Signature remains committed to providing our clients with unparalleled options for premium, memorable corporate merchandise.</p>',
            'featured_image' => 'https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?q=80&w=1600&auto=format&fit=crop',
            'first_name' => 'Sarah',
            'last_name' => 'Jenkins',
            'published_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
        ];

        return View::renderTemplate('pages/public/blog/show', 'main', [
            'title' => $post['title'] . ' | Insights | Marigold Signature',
            'post' => $post
        ]);
    }
}
