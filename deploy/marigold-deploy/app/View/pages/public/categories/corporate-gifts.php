<?php // app/View/pages/public/categories/corporate-gifts.php
$cat = [
    'label'    => 'Corporate Gifts',
    'tagline'  => 'Gifts That Strengthen Relationships',
    'intro'    => 'Thoughtfully curated corporate gifts that reinforce relationships, express appreciation, and leave a lasting impression on clients, partners, and employees.',
    'hero_img' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?q=80&w=1920&auto=format&fit=crop',
    'side_img' => 'https://images.unsplash.com/photo-1607344645866-009c320b63e0?q=80&w=1200&auto=format&fit=crop',
    'body'     => [
        'Corporate gifts are a strategic investment in your most important relationships. At Marigold Signature, we design and deliver bespoke gift solutions that align with your brand identity and communicate the right message to the right audience.',
        'From AI-powered smart notebooks for senior executives to elegant branded stationery sets for client appreciation, every gift is chosen with purpose and presented with precision.',
        'We handle everything — sourcing, branding, packaging, and delivery — so you can focus on the relationship while we ensure the gift does its job perfectly.',
    ],
    'items'    => [
        ['icon' => 'book-open',  'title' => 'Executive Stationery Sets',   'desc' => 'Premium notebooks, pens, and desk accessories presented in luxury gift boxes.'],
        ['icon' => 'cpu',        'title' => 'Smart Tech Gift Sets',         'desc' => 'AI notebooks, power banks, and wireless chargers curated for senior professionals.'],
        ['icon' => 'package',    'title' => 'Branded Gift Boxes',          'desc' => 'Custom-assembled gift boxes with your logo, ribbon, and personalised message cards.'],
        ['icon' => 'award',      'title' => 'Awards & Plaques',            'desc' => 'Crystal, acrylic, and metal awards for staff recognition and client appreciation events.'],
        ['icon' => 'gift',       'title' => 'Appreciation Gift Sets',      'desc' => 'Multi-item sets combining drinkware, stationery, and accessories for impactful gifting.'],
        ['icon' => 'star',       'title' => 'Luxury Hampers',             'desc' => 'Premium hampers filled with curated branded and lifestyle items for VIP recipients.'],
    ],
];
?>
<?php include __DIR__ . '/_template.php'; ?>
