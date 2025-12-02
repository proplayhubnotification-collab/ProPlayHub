<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProPlay Hub - Choose Your Role</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: '#ec003f',
                        brandHover: '#c90036',
                        light: { bg: '#f8fafc', text: '#020618' },
                        dark: { bg: '#020618', text: '#f8fafc' }
                    },
                    borderRadius: { 'custom': '0.375rem' },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { transition: background-color 0.3s, color 0.3s; }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .dark .glass-panel {
            background: rgba(30, 41, 59, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .role-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }
        
        .role-card:hover {
            transform: translateY(-8px);
            border-color: #ec003f;
            box-shadow: 0 20px 40px rgba(236, 0, 63, 0.2);
        }
        
        .role-card.selected {
            border-color: #ec003f;
            background: rgba(236, 0, 63, 0.05);
        }
        
        .dark .role-card.selected {
            background: rgba(236, 0, 63, 0.1);
        }
        
        .icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ec003f 0%, #c90036 100%);
            transition: transform 0.3s;
        }
        
        .role-card:hover .icon-wrapper {
            transform: scale(1.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ec003f 0%, #c90036 100%);
            transition: all 0.3s;
        }
        
        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(236, 0, 63, 0.3);
        }
        
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .feature-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(236, 0, 63, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ec003f;
            font-size: 12px;
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-light-bg text-light-text dark:bg-dark-bg dark:text-dark-text font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-6xl">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-3 mb-6">
                <div class="w-14 h-14 bg-brand rounded-custom flex items-center justify-center text-white font-bold text-xl">
                    P
                </div>
                <span class="text-3xl font-bold text-brand">ProPlay Hub</span>
            </div>
            <h1 class="text-4xl font-bold mb-3">Choose Your Role</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">Select how you want to use ProPlay Hub</p>
        </div>

        <!-- Role Selection Cards -->
        <div class="grid md:grid-cols-2 gap-8 mb-10">
            
            <!-- Member Card -->
            <div class="role-card glass-panel rounded-lg p-8 fade-in" style="animation-delay: 0.1s;" data-role="member">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Game Enthusiast</h2>
                        <p class="text-gray-600 dark:text-gray-400">Member Account</p>
                    </div>
                    <div class="icon-wrapper text-white text-3xl">
                        <i class="fas fa-gamepad"></i>
                    </div>
                </div>

                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    Join our gaming community and enjoy premium gaming services, subscriptions, and exclusive rewards.
                </p>

                <!-- Features List -->
                <div class="space-y-3 mb-8">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm">Browse & subscribe to gaming plans</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm">Rent gaming accessories and peripherals</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm">Purchase gaming items & merchandise</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm">Share achievements with community</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm">Live chat support with experts</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm">Access exclusive promotions</span>
                    </div>
                </div>

                <button class="select-role-btn w-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 font-semibold py-3 rounded-custom hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Continue as Member
                </button>
            </div>

            <!-- Admin Card -->
            <div class="role-card glass-panel rounded-lg p-8 fade-in" style="animation-delay: 0.2s;" data-role="admin">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Business Partner</h2>
                        <p class="text-gray-600 dark:text-gray-400">Admin Account</p>
                    </div>
                    <div class="icon-wrapper text-white text-3xl">
                        <i class="fas fa-crown"></i>
                    </div>
                </div>

                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    Manage your gaming products, subscriptions, and connect with thousands of gamers.
                </p>

                <!-- Features List -->
                <div class="space-y-3 mb-8">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm">Create & manage subscription plans</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm">List gaming items for rent & sale</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm">Manage orders & inventory</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm">Analytics & sales dashboard</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm">Customer support management</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <span class="text-sm">Marketing & promotion tools</span>
                    </div>
                </div>

                <button class="select-role-btn w-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 font-semibold py-3 rounded-custom hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    Continue as Admin
                </button>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <button id="continueBtn" class="btn-primary text-white font-semibold py-3 px-8 rounded-custom flex-1 disabled:opacity-50" disabled>
                <span>Continue to Registration</span>
            </button>
            <button id="loginBtn" class="border-2 border-brand text-brand dark:border-brand dark:text-brand font-semibold py-3 px-8 rounded-custom flex-1 hover:bg-brand/5 transition-colors">
                <span>Already have an account? Login</span>
            </button>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 pt-8 border-t border-gray-300 dark:border-gray-700">
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                By continuing, you agree to our <a href="#" class="text-brand hover:underline">Terms of Service</a> and <a href="#" class="text-brand hover:underline">Privacy Policy</a>
            </p>
            <div class="flex justify-center gap-6 text-gray-600 dark:text-gray-400">
                <a href="#" class="hover:text-brand transition-colors"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-brand transition-colors"><i class="fab fa-discord"></i></a>
                <a href="#" class="hover:text-brand transition-colors"><i class="fab fa-facebook"></i></a>
            </div>
        </div>
    </div>

    <script>
        let selectedRole = null;

        // Role Card Selection
        document.querySelectorAll('.role-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                selectedRole = card.dataset.role;
                
                // Update button styles
                document.querySelectorAll('.select-role-btn').forEach(btn => {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-800', 'dark:text-gray-100');
                });
                
                const selectedBtn = card.querySelector('.select-role-btn');
                selectedBtn.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-800', 'dark:text-gray-100');
                selectedBtn.classList.add('btn-primary', 'text-white');
                
                // Enable continue button
                document.getElementById('continueBtn').disabled = false;
            });
        });

        // Continue Button
        document.getElementById('continueBtn').addEventListener('click', () => {
            if (selectedRole) {
                // Store role in session/localStorage
                sessionStorage.setItem('userRole', selectedRole);
                // Redirect to registration
                window.location.href = `/PHP_User/register${selectedRole.charAt(0).toUpperCase() + selectedRole.slice(1)}.php`;
            }
        });

        // Login Button
        document.getElementById('loginBtn').addEventListener('click', () => {
            window.location.href = '/PHP_User/login.php';
        });

        // Auto Dark Mode (System Preference)
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        const applyTheme = (e) => {
            if (e.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        };
        
        applyTheme(mediaQuery);
        mediaQuery.addEventListener('change', applyTheme);
    </script>
</body>
</html>
