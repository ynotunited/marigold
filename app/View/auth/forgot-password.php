<?php
$errors = \App\Core\Session::get('errors') ?? [];
\App\Core\Session::remove('errors');
?>

<div class="card p-8 sm:p-10" x-data="forgotForm()">
    
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-white mb-2">Reset Password</h2>
        <p class="text-sm text-[var(--text-secondary)]">Enter your email address and we'll send you a link to reset your password.</p>
    </div>

    <form action="/forgot-password" method="POST" @submit="submit">
        <?= \App\Core\CSRF::field() ?>

        <div class="space-y-5">
            <!-- Email -->
            <div class="relative">
                <label for="email" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Email Address <span class="text-[var(--gold)]">*</span></label>
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="h-5 w-5 text-[var(--text-muted)]" :class="{'text-[var(--gold)]': email.length > 0 && validateEmail()}"></i>
                    </div>
                    <input type="email" id="email" name="email" x-model="email" @input="touched = true"
                           class="w-full h-[52px] bg-black/40 border rounded-[12px] pl-12 pr-4 text-white focus:outline-none transition-colors"
                           :class="{
                               'border-[var(--danger)] focus:border-[var(--danger)]': (touched && !validateEmail()) || <?= isset($errors['email']) ? 'true' : 'false' ?>,
                               'border-[var(--gold)] focus:border-[var(--gold)]': touched && validateEmail(),
                               'border-[var(--border)] focus:border-[var(--gold)]': !touched && !<?= isset($errors['email']) ? 'true' : 'false' ?>
                           }"
                           placeholder="name@company.com" required>
                </div>
                <!-- Validation Message -->
                <p x-show="touched && !validateEmail()" class="text-xs text-[var(--danger)] mt-2 flex items-center"><i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i> Please enter a valid email address.</p>
                <?php if (isset($errors['email'])): ?>
                    <p class="text-xs text-[var(--danger)] mt-2"><?= $errors['email'][0] ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Submit -->
            <div class="pt-2">
                <button type="submit" 
                        class="w-full btn btn-primary h-[52px] relative overflow-hidden group"
                        :class="{'opacity-75 cursor-not-allowed': !validateEmail()}"
                        :disabled="!validateEmail()">
                    <span class="relative z-10 font-bold tracking-wide">Send Reset Link</span>
                    <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:animate-[shine_1.5s_ease-in-out_infinite]"></div>
                </button>
            </div>
        </div>
    </form>
    
    <div class="mt-8 text-center text-sm text-[var(--text-secondary)]">
        <a href="/login" class="inline-flex items-center font-medium text-[var(--gold)] hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Back to login
        </a>
    </div>
</div>

<script>
    function forgotForm() {
        return {
            email: '',
            touched: false,
            validateEmail() {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(this.email);
            },
            submit(e) {
                if (!this.validateEmail()) {
                    e.preventDefault();
                    this.touched = true;
                }
            }
        }
    }
</script>
