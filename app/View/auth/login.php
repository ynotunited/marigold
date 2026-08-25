<?php
$errors = \App\Core\Session::get('errors') ?? [];
\App\Core\Session::remove('errors');
?>

<div class="card p-8 sm:p-10" x-data="loginForm()">
    
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-white mb-2">Welcome Back</h2>
        <p class="text-sm text-[var(--text-secondary)]">Enter your credentials to access your account.</p>
    </div>

    <form action="<?= app_url('/login') ?>" method="POST" @submit="submit">
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
                    <a href="<?= app_url('/forgot-password') ?>" class="text-xs font-medium text-[var(--gold)] hover:text-white transition-colors">Forgot password?</a>
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

    <!-- Divider -->
    <div class="flex items-center my-6">
        <div class="flex-1 h-px bg-[var(--border)]"></div>
        <span class="px-4 text-xs text-[var(--text-muted)] uppercase tracking-wider">or</span>
        <div class="flex-1 h-px bg-[var(--border)]"></div>
    </div>

    <!-- Google Sign-In -->
    <a href="<?= app_url('/auth/google') ?>"
       class="flex items-center justify-center w-full h-[52px] bg-white/5 border border-[var(--border)] rounded-[12px] text-white font-medium text-sm hover:bg-white/10 hover:border-[var(--gold)] transition-all duration-200 group">
        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        <span class="relative z-10">Sign in with Google</span>
    </a>
    
    <div class="mt-8 text-center text-sm text-[var(--text-secondary)]">
        Don't have an account? 
        <a href="<?= app_url('/register') ?>" class="font-medium text-[var(--gold)] hover:text-white transition-colors">Apply for an account</a>
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
