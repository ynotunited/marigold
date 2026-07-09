<?php
$errors = \App\Core\Session::get('errors') ?? [];
\App\Core\Session::remove('errors');
?>

<div class="card p-8 sm:p-10" x-data="loginForm()">
    
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-white mb-2">Welcome Back</h2>
        <p class="text-sm text-[var(--text-secondary)]">Enter your credentials to access your account.</p>
    </div>

    <form action="/login" method="POST" @submit="submit">
        <?= \App\Core\CSRF::field() ?>

        <div class="space-y-5">
            <!-- Email -->
            <div class="relative">
                <label for="email" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Email Address <span class="text-[var(--gold)]">*</span></label>
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="h-5 w-5 text-[var(--text-muted)]" :class="{'text-[var(--gold)]': email.length > 0 && validateEmail()}"></i>
                    </div>
                    <input type="email" id="email" name="email" x-model="email" @input="touched.email = true"
                           class="w-full h-[52px] bg-black/40 border rounded-[12px] pl-12 pr-4 text-white focus:outline-none transition-colors"
                           :class="{
                               'border-[var(--danger)] focus:border-[var(--danger)]': (touched.email && !validateEmail()) || <?= isset($errors['email']) ? 'true' : 'false' ?>,
                               'border-[var(--gold)] focus:border-[var(--gold)]': touched.email && validateEmail(),
                               'border-[var(--border)] focus:border-[var(--gold)]': !touched.email && !<?= isset($errors['email']) ? 'true' : 'false' ?>
                           }"
                           placeholder="name@company.com" required>
                </div>
                <!-- Validation Message -->
                <p x-show="touched.email && !validateEmail()" class="text-xs text-[var(--danger)] mt-2 flex items-center"><i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i> Please enter a valid email address.</p>
                <?php if (isset($errors['email'])): ?>
                    <p class="text-xs text-[var(--danger)] mt-2"><?= $errors['email'][0] ?></p>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="relative">
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-sm font-medium text-[var(--text-secondary)]">Password <span class="text-[var(--gold)]">*</span></label>
                    <a href="/forgot-password" class="text-xs font-medium text-[var(--gold)] hover:text-white transition-colors">Forgot password?</a>
                </div>
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="h-5 w-5 text-[var(--text-muted)]" :class="{'text-[var(--gold)]': password.length > 0}"></i>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" id="password" name="password" x-model="password" @input="touched.password = true"
                           class="w-full h-[52px] bg-black/40 border rounded-[12px] pl-12 pr-12 text-white focus:outline-none transition-colors"
                           :class="{
                               'border-[var(--danger)] focus:border-[var(--danger)]': <?= isset($errors['password']) ? 'true' : 'false' ?>,
                               'border-[var(--border)] focus:border-[var(--gold)]': !<?= isset($errors['password']) ? 'true' : 'false' ?>
                           }"
                           placeholder="••••••••" required>
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-[var(--text-muted)] hover:text-white transition-colors focus:outline-none">
                        <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="h-5 w-5"></i>
                    </button>
                </div>
                <?php if (isset($errors['password'])): ?>
                    <p class="text-xs text-[var(--danger)] mt-2"><?= $errors['password'][0] ?></p>
                <?php endif; ?>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded bg-black/40 border-[var(--border)] text-[var(--gold)] focus:ring-[var(--gold)] focus:ring-offset-black">
                <label for="remember" class="ml-2 block text-sm text-[var(--text-secondary)]">
                    Keep me signed in
                </label>
            </div>
            
            <!-- Submit -->
            <div class="pt-2">
                <button type="submit" 
                        class="w-full btn btn-primary h-[52px] relative overflow-hidden group"
                        :class="{'opacity-75 cursor-not-allowed': !isValid()}"
                        :disabled="!isValid()">
                    <span class="relative z-10 font-bold tracking-wide">Sign In</span>
                    <!-- Shine Effect -->
                    <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:animate-[shine_1.5s_ease-in-out_infinite]"></div>
                </button>
            </div>
        </div>
    </form>
    
    <div class="mt-8 text-center text-sm text-[var(--text-secondary)]">
        Don't have an account? 
        <a href="/register" class="font-medium text-[var(--gold)] hover:text-white transition-colors">Apply for an account</a>
    </div>
</div>

<script>
    function loginForm() {
        return {
            email: '',
            password: '',
            showPassword: false,
            touched: {
                email: false,
                password: false
            },
            validateEmail() {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(this.email);
            },
            isValid() {
                return this.validateEmail() && this.password.length > 0;
            },
            submit(e) {
                if (!this.isValid()) {
                    e.preventDefault();
                    this.touched.email = true;
                    this.touched.password = true;
                }
            }
        }
    }
</script>

<style>
@keyframes shine {
  100% {
    transform: translateX(100%);
  }
}
</style>
