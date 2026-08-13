<?php // app/View/pages/public/categories/seasonal-gifts.php
$cat = [
    'label'    => 'Seasonal Gifts',
    'tagline'  => 'The Right Gift for Every Season',
    'intro'    => 'Mark every milestone — Christmas, New Year, Eid, Easter, and beyond — with beautifully curated seasonal gifts that reinforce your brand through the moments that matter most.',
    'hero_img' => 'https://images.unsplash.com/photo-1512909006721-3d6018887383?q=80&w=1920&auto=format&fit=crop',
    'side_img' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?q=80&w=1200&auto=format&fit=crop',
    'body'     => [
        'Seasonal gifting is one of the most effective ways for organisations to stay connected with clients, partners, and employees throughout the year. At Marigold Signature, we plan and deliver seasonal gift programmes that are timely, thoughtful, and on-brand.',
        'From festive hampers and New Year gift sets to Ramadan gifting packs and end-of-year appreciation gifts, we handle the full cycle — concept, sourcing, branding, packaging, and delivery.',
        'We recommend planning seasonal gift programmes well in advance to ensure availability and on-time delivery, especially during peak periods. Our team is available to guide you through the process.',
    ],
    'items'    => [
        ['icon' => 'gift',       'title' => 'Christmas & Festive Hampers',  'desc' => 'Beautifully packed hampers with branded items and seasonal treats for clients and staff.'],
        ['icon' => 'star',       'title' => 'New Year Gift Sets',           'desc' => 'Curated branded sets to start the new year with a lasting impression on key relationships.'],
        ['icon' => 'moon',       'title' => 'Eid & Ramadan Gifting',        'desc' => 'Premium gift packs tailored for Eid Al-Fitr and Ramadan corporate appreciation.'],
        ['icon' => 'sun',        'title' => 'Mid-Year Appreciation Gifts',  'desc' => 'Practical branded gifts for staff and clients at mid-year milestones.'],
        ['icon' => 'package',    'title' => 'End-of-Year Gift Programmes',  'desc' => 'Bulk seasonal gift programmes planned and delivered seamlessly for large organisations.'],
        ['icon' => 'sparkles',   'title' => 'Custom Seasonal Merchandise',  'desc' => 'Bespoke items designed around a specific season, theme, or brand campaign.'],
    ],
];
?>
<?php include __DIR__ . '/_template.php'; ?>
