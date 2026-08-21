<?php // app/View/pages/public/categories/souvenirs.php
$cat = [
    'label'    => 'Souvenirs',
    'tagline'  => 'Memories That Keep Your Brand Alive',
    'intro'    => 'Unique, memorable souvenirs that give your audience something to keep — and a reason to remember your brand long after the event is over.',
    'hero_img' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=1920&auto=format&fit=crop',
    'side_img' => 'https://images.unsplash.com/photo-1585771724684-38269d6639fd?q=80&w=1200&auto=format&fit=crop',
    'body'     => [
        'A well-chosen souvenir does more than mark an occasion — it becomes a daily reminder of your brand and the experience you created. Marigold Signature sources distinctive, high-quality souvenirs that stand out from the ordinary.',
        'From stainless steel manicure sets and portable juicers to keychains, picture frames, and novelty items, our souvenir range covers every occasion and budget.',
        'Each item is branded with care, ensuring your organisation\'s mark is present without overpowering the thoughtfulness of the gift itself.',
    ],
    'items'    => [
        ['icon' => 'scissors',   'title' => 'Grooming & Lifestyle Sets',   'desc' => 'Manicure sets, grooming kits, and personal care items for memorable branded gifting.'],
        ['icon' => 'sun',        'title' => 'Home & Kitchen Novelties',    'desc' => 'Portable juicers, food choppers, and handy kitchen tools with your logo applied.'],
        ['icon' => 'key',        'title' => 'Keychains & Accessories',     'desc' => 'Custom metal and leather keychains — compact brand reminders for every recipient.'],
        ['icon' => 'image',      'title' => 'Photo Frames & Memorabilia',  'desc' => 'Branded frames and desk memorabilia that keep your event top of mind.'],
        ['icon' => 'tag',        'title' => 'Custom Novelty Items',        'desc' => 'Bespoke novelty souvenirs designed around your event theme or brand story.'],
        ['icon' => 'package',    'title' => 'Souvenir Gift Sets',          'desc' => 'Curated sets of complementary souvenir items packaged in branded presentation boxes.'],
    ],
];
?>
<?php include __DIR__ . '/_template.php'; ?>
