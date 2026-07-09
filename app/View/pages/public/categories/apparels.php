<?php // app/View/pages/public/categories/apparels.php
$cat = [
    'label'    => 'Apparels',
    'tagline'  => 'Wear Your Brand with Pride',
    'intro'    => 'From premium polo shirts and hoodies to branded caps and uniforms, our apparel range turns every employee and event attendee into a walking brand ambassador.',
    'hero_img' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?q=80&w=1920&auto=format&fit=crop',
    'side_img' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?q=80&w=1200&auto=format&fit=crop',
    'body'     => [
        'Branded apparel is one of the most powerful tools for building team identity and increasing brand visibility. At Marigold Signature, we supply a wide range of high-quality garments customised with your logo, brand colours, and messaging.',
        'Whether you need staff uniforms, event T-shirts, executive polo shirts, or branded hoodies for a corporate retreat, we source and deliver premium garments that make your team look cohesive and professional.',
        'Each item is produced to specification — the right fit, the right fabric, and the right finish — so your brand is always represented at its best.',
    ],
    'items'    => [
        ['icon' => 'shirt',      'title' => 'Polo Shirts',                 'desc' => 'Premium cotton and polyester polo shirts for corporate teams, events, and uniforms.'],
        ['icon' => 'shirt',      'title' => 'Hoodies & Sweatshirts',       'desc' => 'Pullover and zip-up hoodies for retreats, team building, and casual branded gifting.'],
        ['icon' => 'user',       'title' => 'T-Shirts & Casual Wear',      'desc' => 'Event tees, promotional shirts, and casual wear printed or embroidered to your spec.'],
        ['icon' => 'circle',     'title' => 'Branded Caps & Headwear',     'desc' => 'Structured and unstructured caps with embroidered logos — a classic brand staple.'],
        ['icon' => 'layers',     'title' => 'Corporate Uniforms',          'desc' => 'Tailored uniform sets for front-line staff, receptionists, and event crew.'],
        ['icon' => 'wind',       'title' => 'Jackets & Outerwear',         'desc' => 'Branded windbreakers and fleece jackets for outdoor events and senior staff gifts.'],
    ],
];
?>
<?php include __DIR__ . '/_template.php'; ?>
