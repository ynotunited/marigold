<?php
/**
 * Badge Component
 * 
 * @param string $type new|sale|preorder|instock|out|quote
 * @param string $label
 */
$type = $type ?? 'new';
$class = "badge badge-$type";
?>
<span class="<?= $class ?>">
    <?= htmlspecialchars($label) ?>
</span>
