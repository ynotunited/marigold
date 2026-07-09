<?php // app/View/pages/public/categories/drinkware.php
$cat = [
    'label'    => 'Drinkware',
    'tagline'  => 'Stay Hydrated, Stay Branded',
    'intro'    => 'From sleek vacuum flasks to custom sports bottles and branded mugs, our drinkware collection keeps your organisation\'s identity front and centre — on desks, at events, and on the move.',
    'hero_img' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?q=80&w=1920&auto=format&fit=crop',
    'side_img' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=1200&auto=format&fit=crop',
    'body'     => [
        'Marigold Signature offers a premium range of branded drinkware solutions tailored for corporate gifting, events, and everyday brand promotion. Every item is customisable with your logo, brand colours, and messaging.',
        'Whether you need thermal flasks for a conference, branded water bottles for a marathon sponsorship, or elegant mugs for employee gifts, we source and deliver with precision.',
        'Our drinkware products meet global quality standards — food-grade materials, BPA-free construction, and leak-proof designs — so your brand is always associated with quality.',
    ],
    'items'    => [
        ['icon' => 'coffee',     'title' => 'Vacuum Flasks & Thermal Bottles',   'desc' => 'Double-wall stainless steel construction keeps beverages hot or cold for hours.'],
        ['icon' => 'droplets',   'title' => 'Sports Water Bottles',             'desc' => 'BPA-free collapsible and rigid sports bottles for active brand ambassadors.'],
        ['icon' => 'cup-soda',   'title' => 'Branded Mugs & Cups',              'desc' => 'Ceramic, enamel, and travel mugs for office desks and on-the-go gifting.'],
        ['icon' => 'wine',       'title' => 'Tumblers & Travel Cups',           'desc' => 'Spill-proof tumblers perfect for commuters and conference delegates.'],
        ['icon' => 'package',    'title' => 'Drinkware Gift Sets',              'desc' => 'Curated sets bundling flasks, cups, and accessories in premium branded packaging.'],
        ['icon' => 'star',       'title' => 'Custom Glassware',                'desc' => 'Engraved or printed glass sets ideal for executive gifts and award ceremonies.'],
    ],
];
?>
<?php include __DIR__ . '/_template.php'; ?>
