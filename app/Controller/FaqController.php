<?php

namespace App\Controller;


use App\Core\Controller;
use App\Core\View;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        // Dummy FAQ data for visual completeness
        $faqs = [
            [
                'category' => 'Ordering & Shipping',
                'question' => 'What is the minimum order quantity (MOQ)?',
                'answer' => 'Our MOQ varies depending on the product. Many premium items have a low MOQ of 10-20 units, while customized promotional items might require 50-100 units. The specific MOQ is always listed on the product detail page.'
            ],
            [
                'category' => 'Ordering & Shipping',
                'question' => 'Do you ship internationally?',
                'answer' => 'Yes, we provide secure shipping globally via our logistics partners. Shipping costs and timelines will be calculated and provided during the quoting process.'
            ],
            [
                'category' => 'Customization',
                'question' => 'What type of branding options do you offer?',
                'answer' => 'We offer a wide range of premium branding options including laser engraving, debossing/embossing, foil stamping, screen printing, and full-color UV printing. Our team will recommend the best method based on the product material and your brand guidelines.'
            ],
            [
                'category' => 'Customization',
                'question' => 'Can I see a sample before placing a bulk order?',
                'answer' => 'Absolutely. We highly recommend reviewing a physical sample for high-value orders. Blank samples can usually be dispatched within 48 hours, while branded pre-production samples typically take 7-10 days.'
            ],
            [
                'category' => 'Payment',
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept bank transfers, major credit cards via Paystack, and can arrange invoice payment terms for established corporate accounts subject to credit approval.'
            ]
        ];

        return View::renderTemplate('pages/public/faq', 'main', [
            'title' => 'Frequently Asked Questions | Marigold Signature',
            'faqs' => $faqs
        ]);
    }
}
