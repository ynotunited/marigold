<?php
/**
 * Input Component
 * 
 * @param string $name
 * @param string $label
 * @param string $type text|email|password|number
 * @param string $value
 * @param string $placeholder
 * @param string $error Error message if validation failed
 * @param bool $required
 */
$type = $type ?? 'text';
$value = $value ?? '';
$placeholder = $placeholder ?? '';
$requiredAttr = ($required ?? false) ? 'required' : '';
$errorClass = isset($error) ? 'border-[var(--danger)] focus:border-[var(--danger)]' : '';
?>
<div class="flex flex-col space-y-2 mb-4">
    <?php if (isset($label)): ?>
        <label for="<?= $name ?>" class="text-sm font-medium text-[var(--text-secondary)]">
            <?= $label ?> <?= ($required ?? false) ? '<span class="text-[var(--gold)]">*</span>' : '' ?>
        </label>
    <?php endif; ?>
    
    <input 
        type="<?= $type ?>" 
        id="<?= $name ?>" 
        name="<?= $name ?>" 
        value="<?= htmlspecialchars($value) ?>"
        placeholder="<?= $placeholder ?>"
        class="input-field <?= $errorClass ?>"
        <?= $requiredAttr ?>
    >
    
    <?php if (isset($error)): ?>
        <span class="text-xs text-[var(--danger)] flex items-center">
            <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i> <?= $error ?>
        </span>
    <?php endif; ?>
</div>
