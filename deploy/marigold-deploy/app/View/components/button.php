<?php
/**
 * Button Component
 * 
 * @param string $type primary|secondary|ghost|icon
 * @param string $label Button text
 * @param string|null $icon Lucide icon name
 * @param string|null $href If provided, renders as an <a> tag
 * @param string $class Additional CSS classes
 * @param string $attrs Additional HTML attributes
 */
$type = $type ?? 'primary';
$class = $class ?? '';
$attrs = $attrs ?? '';
$isLink = isset($href);
$tag = $isLink ? 'a' : 'button';
$hrefAttr = $isLink ? "href=\"$href\"" : '';

$baseClass = "btn btn-$type $class";
?>
<<?= $tag ?> <?= $hrefAttr ?> class="<?= $baseClass ?>" <?= $attrs ?>>
    <?php if (isset($icon)): ?>
        <i data-lucide="<?= $icon ?>" class="<?= isset($label) ? 'mr-2' : '' ?> w-5 h-5"></i>
    <?php endif; ?>
    <?php if (isset($label)): ?>
        <span><?= $label ?></span>
    <?php endif; ?>
</<?= $tag ?>>
