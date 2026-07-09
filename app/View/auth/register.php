<?php
$errors = \App\Core\Session::get('errors') ?? [];
\App\Core\Session::remove('errors');
?>

<div class="card p-8 sm:p-10" x-data="registerForm()">
    
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-white mb-2">Apply for an Account</h2>
        <p class="text-sm text-[var(--text-secondary)]">Create an account to access our premium catalog.</p>
    </div>

    <form action="/register" method="POST" @submit="submit">
        <?= \App\Core\CSRF::field() ?>

        <div class="space-y-5">
            <!-- Name (First / Last) -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">First Name <span class="text-[var(--gold)]">*</span></label>
                    <input type="text" id="first_name" name="first_name" x-model="firstName" @input="touched.firstName = true"
                           class="w-full h-[52px] bg-black/40 border rounded-[12px] px-4 text-white focus:outline-none transition-colors"
                           :class="getInputClass('firstName', <?= isset($errors['first_name']) ? 'true' : 'false' ?>)"
                           placeholder="John" required>
                    <?php if (isset($errors['first_name'])): ?>
                        <p class="text-xs text-[var(--danger)] mt-2"><?= $errors['first_name'][0] ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Last Name <span class="text-[var(--gold)]">*</span></label>
                    <input type="text" id="last_name" name="last_name" x-model="lastName" @input="touched.lastName = true"
                           class="w-full h-[52px] bg-black/40 border rounded-[12px] px-4 text-white focus:outline-none transition-colors"
                           :class="getInputClass('lastName', <?= isset($errors['last_name']) ? 'true' : 'false' ?>)"
                           placeholder="Doe" required>
                    <?php if (isset($errors['last_name'])): ?>
                        <p class="text-xs text-[var(--danger)] mt-2"><?= $errors['last_name'][0] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Email Address <span class="text-[var(--gold)]">*</span></label>
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="h-5 w-5 text-[var(--text-muted)]" :class="{'text-[var(--gold)]': email.length > 0 && validateEmail()}"></i>
                    </div>
                    <input type="email" id="email" name="email" x-model="email" @input="touched.email = true"
                           class="w-full h-[52px] bg-black/40 border rounded-[12px] pl-12 pr-4 text-white focus:outline-none transition-colors"
                           :class="getInputClass('email', <?= isset($errors['email']) ? 'true' : 'false' ?>)"
                           placeholder="name@company.com" required>
                </div>
                <p x-show="touched.email && !validateEmail()" class="text-xs text-[var(--danger)] mt-2 flex items-center"><i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i> Please enter a valid email.</p>
                <?php if (isset($errors['email'])): ?>
                    <p class="text-xs text-[var(--danger)] mt-2"><?= $errors['email'][0] ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Phone (Optional) -->
            <div>
                <label for="phone" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Phone Number</label>
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="phone" class="h-5 w-5 text-[var(--text-muted)]" :class="{'text-[var(--gold)]': phone.length > 0}"></i>
                    </div>
                    <input type="tel" id="phone" name="phone" x-model="phone"
                           class="w-full h-[52px] bg-black/40 border border-[var(--border)] focus:border-[var(--gold)] rounded-[12px] pl-12 pr-4 text-white focus:outline-none transition-colors"
                           placeholder="+234 800 000 0000">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Password <span class="text-[var(--gold)]">*</span></label>
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="h-5 w-5 text-[var(--text-muted)]" :class="{'text-[var(--gold)]': password.length >= 8}"></i>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" id="password" name="password" x-model="password" @input="touched.password = true"
                           class="w-full h-[52px] bg-black/40 border rounded-[12px] pl-12 pr-12 text-white focus:outline-none transition-colors"
                           :class="getInputClass('password', <?= isset($errors['password']) ? 'true' : 'false' ?>)"
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
            
            <!-- Submit -->
            <div class="pt-4">
                <button type="submit" 
                        class="w-full btn btn-primary h-[52px] relative overflow-hidden group"
                        :class="{'opacity-75 cursor-not-allowed': !isValid()}"
                        :disabled="!isValid()">
                    <span class="relative z-10 font-bold tracking-wide">Submit Application</span>
                    <div class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:animate-[shine_1.5s_ease-in-out_infinite]"></div>
                </button>
            </div>
        </div>
    </form>
    
    <div class="mt-8 text-center text-sm text-[var(--text-secondary)]">
        Already have an account? 
        <a href="/login" class="font-medium text-[var(--gold)] hover:text-white transition-colors">Sign in here</a>
    </div>
</div>

<script>
    function registerForm() {
        return {
            firstName: '',
            lastName: '',
            email: '',
            phone: '',
            password: '',
            showPassword: false,
            touched: {
                firstName: false,
                lastName: false,
                email: false,
                password: false
            },
            validateEmail() {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(this.email);
            },
            validateField(field) {
                if (field === 'email') return this.validateEmail();
                if (field === 'password') return this.password.length >= 8;
                return this[field].trim().length > 0;
            },
            getInputClass(field, hasServerError) {
                if (hasServerError) return 'border-[var(--danger)] focus:border-[var(--danger)]';
                if (!this.touched[field]) return 'border-[var(--border)] focus:border-[var(--gold)]';
                return this.validateField(field) 
                    ? 'border-[var(--gold)] focus:border-[var(--gold)]' 
                    : 'border-[var(--danger)] focus:border-[var(--danger)]';
            },
            isValid() {
                return this.firstName.trim().length > 0 && 
                       this.lastName.trim().length > 0 && 
                       this.validateEmail() && 
                       this.password.length >= 8;
            },
            submit(e) {
                if (!this.isValid()) {
                    e.preventDefault();
                    Object.keys(this.touched).forEach(k => this.touched[k] = true);
                }
            }
        }
    }
</script>
