<?php
$errors = \App\Core\Session::get('errors') ?? [];
\App\Core\Session::remove('errors');
// The controller should pass a $token variable to this view
$token = $token ?? ''; 
?>

<div class="card p-8 sm:p-10" x-data="resetForm()">
    
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-white mb-2">Create New Password</h2>
        <p class="text-sm text-[var(--text-secondary)]">Your new password must be different from previous used passwords.</p>
    </div>

    <form action="/reset-password" method="POST" @submit="submit">
        <?= \App\Core\CSRF::field() ?>
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <div class="space-y-5">
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">New Password <span class="text-[var(--gold)]">*</span></label>
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="h-5 w-5 text-[var(--text-muted)]" :class="{'text-[var(--gold)]': password.length >= 8}"></i>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" id="password" name="password" x-model="password" @input="touched.password = true"
                           class="w-full h-[52px] bg-black/40 border rounded-[12px] pl-12 pr-12 text-white focus:outline-none transition-colors"
                           :class="getInputClass('password')"
                           placeholder="••••••••" required minlength="8">
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-[var(--text-muted)] hover:text-white transition-colors focus:outline-none">
                        <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="h-5 w-5"></i>
                    </button>
                </div>
                <p x-show="touched.password && password.length < 8" class="text-xs text-[var(--danger)] mt-2 flex items-center"><i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i> Must be at least 8 characters.</p>
                <?php if (isset($errors['password'])): ?>
                    <p class="text-xs text-[var(--danger)] mt-2"><?= $errors['password'][0] ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Confirm Password <span class="text-[var(--gold)]">*</span></label>
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="h-5 w-5 text-[var(--text-muted)]" :class="{'text-[var(--gold)]': passwordConfirmation.length >= 8 && passwordsMatch()}"></i>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" x-model="passwordConfirmation" @input="touched.passwordConfirmation = true"
                           class="w-full h-[52px] bg-black/40 border rounded-[12px] pl-12 pr-4 text-white focus:outline-none transition-colors"
                           :class="getInputClass('passwordConfirmation')"
                           placeholder="••••••••" required minlength="8">
                </div>
                <p x-show="touched.passwordConfirmation && !passwordsMatch()" class="text-xs text-[var(--danger)] mt-2 flex items-center"><i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i> Passwords do not match.</p>
            </div>
            
            <!-- Submit -->
            <div class="pt-4">
                <button type="submit" 
                        class="w-full btn btn-primary h-[52px] relative overflow-hidden group"
                        :class="{'opacity-75 cursor-not-allowed': !isValid()}"
                        :disabled="!isValid()">
                    <span class="relative z-10 font-bold tracking-wide">Reset Password</span>
                    <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:animate-[shine_1.5s_ease-in-out_infinite]"></div>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function resetForm() {
        return {
            password: '',
            passwordConfirmation: '',
            showPassword: false,
            touched: {
                password: false,
                passwordConfirmation: false
            },
            passwordsMatch() {
                return this.password === this.passwordConfirmation && this.password.length > 0;
            },
            validateField(field) {
                if (field === 'password') return this.password.length >= 8;
                if (field === 'passwordConfirmation') return this.passwordsMatch();
                return false;
            },
            getInputClass(field) {
                if (!this.touched[field]) return 'border-[var(--border)] focus:border-[var(--gold)]';
                return this.validateField(field) 
                    ? 'border-[var(--gold)] focus:border-[var(--gold)]' 
                    : 'border-[var(--danger)] focus:border-[var(--danger)]';
            },
            isValid() {
                return this.password.length >= 8 && this.passwordsMatch();
            },
            submit(e) {
                if (!this.isValid()) {
                    e.preventDefault();
                    this.touched.password = true;
                    this.touched.passwordConfirmation = true;
                }
            }
        }
    }
</script>
