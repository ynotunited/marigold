<?php // app/View/pages/public/categories/technology-accessories.php
$cat = [
    'label'    => 'Technology & Accessories',
    'tagline'  => 'Smart Gifts for the Modern Professional',
    'intro'    => 'Power banks, wireless chargers, smart notebooks, USB hubs and more — our tech accessories range delivers practical branded gifts that stay relevant long after the event.',
    'hero_img' => 'https://images.unsplash.com/photo-1612815292673-ab2ad8a5a95b?q=80&w=1920&auto=format&fit=crop',
    'side_img' => 'https://images.unsplash.com/photo-1600377695841-1f6dff5c76da?q=80&w=1200&auto=format&fit=crop',
    'body'     => [
        'Technology accessories are among the most appreciated corporate gifts because they serve a daily purpose. At Marigold Signature, we source premium tech products that combine functionality with your brand identity.',
        'From MagSafe power banks and wireless charging pads to multifunctional desktop gadgets and branded USB drives, every item is chosen for quality and everyday usability.',
        'We customise each product with your logo or brand name, ensuring your organisation stays visible every time a recipient charges their phone, plugs in a USB, or uses their smart notebook.',
    ],
    'items'    => [
        ['icon' => 'battery-charging', 'title' => 'Power Banks',                    'desc' => 'MagSafe and standard power banks from 5,000mAh to 20,000mAh, branded with your logo.'],
        ['icon' => 'wifi',             'title' => 'Wireless Charging Pads',         'desc' => 'Sleek Qi-compatible chargers for desks, meeting rooms, and executive gift sets.'],
        ['icon' => 'book-open',        'title' => 'Smart Notebooks',               'desc' => 'AI-powered notebooks with voice control — cutting-edge gifts for modern executives.'],
        ['icon' => 'monitor',          'title' => 'USB Hubs & Accessories',         'desc' => 'Multi-port hubs, USB drives, and cable organisers for the productive professional.'],
        ['icon' => 'wind',             'title' => 'Desktop Gadgets & Fans',         'desc' => 'Colourful desktop fans and organisers that keep your brand on every work surface.'],
        ['icon' => 'headphones',       'title' => 'Audio & Peripherals',            'desc' => 'Branded earbuds, speakers, and computer peripherals for premium gift packs.'],
    ],
];
?>
<?php include __DIR__ . '/_template.php'; ?>
