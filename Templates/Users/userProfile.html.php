<!-- Page Specific Resources -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">

    <div id="share-simulation-overlay" class="fixed inset-0 z-[150] bg-black/60 hidden flex items-center justify-center backdrop-blur-md p-4">
        <!-- Card Container -->
        <div id="share-card" class="w-full max-w-sm rounded-2xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 overflow-hidden relative">
            
            <!-- Dynamic Background/Theme -->
            <div id="share-card-bg" class="absolute inset-0 bg-white dark:bg-dark-bg transition-colors duration-300"></div>

            <!-- Content -->
            <div class="relative z-10 p-6 flex flex-col min-h-[300px]">
                
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div id="share-platform-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-xl shadow-sm transition-colors"></div>
                        <div>
                            <h3 id="share-platform-name" class="font-bold text-lg leading-none transition-colors">Platform</h3>
                            <p id="share-account-name" class="text-xs opacity-70 transition-colors">@AlexHunter</p>
                        </div>
                    </div>
                    <div id="share-connection-status" class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                </div>

                <!-- Text Area Simulation -->
                <div class="flex-1 bg-black/5 dark:bg-white/5 rounded-xl p-4 mb-6 relative overflow-hidden border border-black/5 dark:border-white/5">
                    <p id="share-preview-text" class="text-sm font-medium leading-relaxed opacity-90 font-sans whitespace-pre-wrap transition-colors"></p>
                    <div id="typing-cursor" class="inline-block w-0.5 h-4 bg-current ml-1 animate-blink align-middle"></div>
                </div>

                <!-- Footer / Actions -->
                <div class="mt-auto">
                    <!-- Progress Bar (Hidden by default) -->
                    <div id="share-progress-bar" class="w-full h-1 bg-black/10 dark:bg-white/10 rounded-full overflow-hidden mb-3 opacity-0 transition-opacity">
                        <div id="share-progress-fill" class="h-full bg-current w-0 transition-all duration-1000 ease-out"></div>
                    </div>

                    <button id="share-action-btn" class="w-full py-3 rounded-xl font-bold text-sm shadow-lg transform active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span id="share-btn-text">Post</span>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
      tailwind.config = {
        darkMode: 'class', // We will toggle this class based on system listener
        theme: {
            extend: {
                fontFamily: {
                    sans: ['"Inter"', 'sans-serif'],
                    mono: ['"Rajdhani"', 'monospace'],
                },
                colors: {
                    brand: '#ff0055', /* Cyberpunk Red */
                    brandDark: '#cc0044',
                    light: {
                        bg: '#f8fafc',
                        text: '#020618'
                    },
                    dark: {
                        bg: '#020618',
                        text: '#f8fafc',
                        surface: '#1e293b',
                        border: '#334155'
                    }
                },
                boxShadow: {
                    'glow': '0 0 20px rgba(255, 0, 85, 0.3)',
                    'float': '0 10px 40px -10px rgba(0,0,0,0.2)'
                }
            }
        }
      }
    </script>
    
    <style>
        /* Base Reset */
        body { -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Smooth Transitions */
        .smooth-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        /* Animations */
        @keyframes slideUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes scaleIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        
        .modal-enter { animation: slideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .fade-in { animation: fadeIn 0.2s ease-out forwards; }
        .pop-in { animation: scaleIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        /* Custom Styles */
        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Tier Card Backgrounds - Metal Effect */
        .metal-card {
            position: relative;
            overflow: hidden;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
        }
        .metal-card::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-25deg);
            animation: shine 6s infinite;
        }
        @keyframes shine {
            0% { left: -100%; }
            20% { left: 200%; }
            100% { left: 200%; }
        }

        .tier-member { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; }
        .tier-silver { 
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 50%, #94a3b8 100%); 
            color: #1e293b; 
        }
        .tier-gold { 
            background: linear-gradient(135deg, #fef08a 0%, #facc15 40%, #a16207 100%); 
            color: #422006; 
        }
        .tier-platinum { 
            background: linear-gradient(135deg, #e2e8f0 0%, #f8fafc 50%, #64748b 100%); 
            color: #0f172a; 
            border: 1px solid rgba(255,255,255,0.5);
        }
        .tier-diamond { 
            background: linear-gradient(135deg, #bae6fd 0%, #e0f2fe 50%, #0284c7 100%); 
            color: #0c4a6e; 
        }

        /* Tier Card Background */
        .tier-card-bg {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            position: relative; overflow: hidden;
        }
        .tier-card-bg::before {
            content: ''; position: absolute; top: 0; right: 0; width: 150px; height: 150px;
            background: radial-gradient(circle, rgba(255,0,85,0.2) 0%, transparent 70%);
            pointer-events: none;
        }
        .tier-card-bg::after {
            content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='3'/%3E%3Ccircle cx='13' cy='13' r='3'/%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        /* Typing Cursor */
        @keyframes blink { 50% { opacity: 0; } }
        .animate-blink { animation: blink 1s step-end infinite; }
    </style>

    <div class="bg-light-bg text-light-text dark:bg-dark-bg dark:text-dark-text transition-colors duration-300 min-h-full font-sans overflow-x-hidden selection:bg-brand selection:text-white pb-24">

    <div id="toast" class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] transition-all duration-300 transform -translate-y-24 opacity-0 px-5 py-3 rounded-full bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-glow flex items-center gap-3 min-w-[220px] max-w-[90vw]">
        <div class="w-6 h-6 rounded-full bg-green-100 dark:bg-green-900/30 text-green-500 flex items-center justify-center shrink-0">
            <i class="fas fa-check text-xs"></i>
        </div>
        <span id="toast-msg" class="text-sm font-semibold truncate">Notification</span>
    </div>

    <main class="max-w-md mx-auto min-h-screen relative pb-32">
        
        <header class="px-6 pt-8 pb-4 flex justify-between items-center sticky top-0 z-40 bg-light-bg/90 dark:bg-dark-bg/90 backdrop-blur-md">
            <div class="flex items-center gap-3.5">
                <div class="relative w-11 h-11 rounded-full p-[2px] bg-gradient-to-tr from-brand to-purple-600 shadow-lg cursor-pointer" onclick="openModal('profile')">
                    <img id="header-avatar" src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix&backgroundColor=1e293b" class="w-full h-full rounded-full bg-light-text object-cover" alt="Avatar">
                    <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white dark:border-dark-bg rounded-full"></div>
                </div>
                <div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest leading-none mb-1">Welcome Back</p>
                    <h1 class="font-bold text-lg leading-none tracking-tight">Alex Hunter</h1>
                </div>
            </div>
            

        </header>

        <div class="px-6 space-y-6">
            
            <!-- Tier Header -->
            <div class="flex justify-between items-end px-1">
                <div>
                    <h3 class="font-bold text-base dark:text-white">Membership Status</h3>
                    <p class="text-xs text-slate-500">Tap card to view benefits</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-slate-400 uppercase font-bold">Lifetime Spend</p>
                    <p class="text-lg font-mono font-bold text-brand" id="spent-amount">$0.00</p>
                </div>
            </div>

            <!-- Current Tier Card (Single) -->
            <div id="current-tier-display" onclick="openModal('tiers')" class="w-full cursor-pointer active:scale-[0.98] transition-transform">
                <!-- Injected via JS -->
            </div>

            <div class="grid grid-cols-4 gap-3">
                <button onclick="openModal('library')" class="flex flex-col items-center gap-2 group">
                    <div class="w-14 h-14 rounded-2xl bg-white dark:bg-dark-surface border border-slate-100 dark:border-dark-border text-blue-500 dark:text-blue-400 flex items-center justify-center text-xl shadow-sm transition-all group-active:scale-90 group-active:bg-blue-50 dark:group-active:bg-blue-900/20">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400">Library</span>
                </button>

                <button onclick="openModal('rentals')" class="flex flex-col items-center gap-2 group relative">
                     <div class="absolute top-0 right-2 w-3 h-3 bg-brand rounded-full border-2 border-slate-50 dark:border-dark-bg z-10 animate-bounce"></div>
                    <div class="w-14 h-14 rounded-2xl bg-white dark:bg-dark-surface border border-slate-100 dark:border-dark-border text-emerald-500 dark:text-emerald-400 flex items-center justify-center text-xl shadow-sm transition-all group-active:scale-90 group-active:bg-emerald-50 dark:group-active:bg-emerald-900/20">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400">Rentals</span>
                </button>

                <button onclick="window.location.href='/ProPlayHub/PHP_User/userStore.php'" class="flex flex-col items-center gap-2 group">
                    <div class="w-14 h-14 rounded-2xl bg-white dark:bg-dark-surface border border-slate-100 dark:border-dark-border text-purple-500 dark:text-purple-400 flex items-center justify-center text-xl shadow-sm transition-all group-active:scale-90 group-active:bg-purple-50 dark:group-active:bg-purple-900/20">
                        <i class="fas fa-store"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400">Shop</span>
                </button>

                <button onclick="window.location.href='/ProPlayHub/PHP_User/userSocialPage.php'" class="flex flex-col items-center gap-2 group">
                    <div class="w-14 h-14 rounded-2xl bg-white dark:bg-dark-surface border border-slate-100 dark:border-dark-border text-pink-500 dark:text-pink-400 flex items-center justify-center text-xl shadow-sm transition-all group-active:scale-90 group-active:bg-pink-50 dark:group-active:bg-pink-900/20">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400">Social</span>
                </button>
            </div>

            <div>
                <div class="flex justify-between items-center mb-4 px-1">
                    <h3 class="font-bold text-base dark:text-white">Gaming Activity</h3>
                    <button onclick="randomizeStats()" class="text-xs text-brand font-bold uppercase tracking-wide hover:underline"><i class="fas fa-sync-alt mr-1"></i> Sync</button>
                </div>
                
                <div id="activity-container">
                    <div class="p-8 text-center text-slate-400">
                        <i class="fas fa-circle-notch fa-spin text-2xl mb-2"></i>
                        <p class="text-xs font-bold uppercase tracking-widest">Loading Activity...</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-dark-surface rounded-2xl overflow-hidden border border-slate-100 dark:border-dark-border">
                <button onclick="window.location.href='/ProPlayHub/PHP_User/userHistory.php'" class="w-full p-4 flex items-center justify-between border-b border-slate-50 dark:border-dark-border hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-500 flex items-center justify-center"><i class="fas fa-receipt"></i></div>
                        <span class="font-medium text-sm dark:text-slate-200">Transaction History</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                </button>
                <button onclick="window.location.href='/ProPlayHub/PHP_User/userNotification.php'" class="w-full p-4 flex items-center justify-between border-b border-slate-50 dark:border-dark-border hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-orange-50 dark:bg-orange-500/10 text-orange-500 flex items-center justify-center"><i class="fas fa-bell"></i></div>
                        <span class="font-medium text-sm dark:text-slate-200">Notifications</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                </button>
                <button onclick="openModal('profile')" class="w-full p-4 flex items-center justify-between border-b border-slate-50 dark:border-dark-border hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-pink-50 dark:bg-pink-500/10 text-pink-500 flex items-center justify-center"><i class="fas fa-user-cog"></i></div>
                        <span class="font-medium text-sm dark:text-slate-200">Account Settings</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                </button>
                <button onclick="openModal('redeem')" class="w-full p-4 flex items-center justify-between border-b border-slate-50 dark:border-dark-border hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 flex items-center justify-center"><i class="fas fa-ticket-alt"></i></div>
                        <span class="font-medium text-sm dark:text-slate-200">Redeem Code</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                </button>
                <button onclick="openModal('support')" class="w-full p-4 flex items-center justify-between border-b border-slate-50 dark:border-dark-border hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-cyan-50 dark:bg-cyan-500/10 text-cyan-500 flex items-center justify-center"><i class="fas fa-headset"></i></div>
                        <span class="font-medium text-sm dark:text-slate-200">Help & Support</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs text-slate-400"></i>
                </button>
                <button onclick="logout()" class="w-full p-4 flex items-center justify-between hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center group-hover:bg-red-100 dark:group-hover:bg-red-500/20 transition-colors"><i class="fas fa-sign-out-alt"></i></div>
                        <span class="font-medium text-sm text-red-500 dark:text-red-400">Log Out</span>
                    </div>
                </button>
            </div>

        </div>
    </main>

    <div id="modal-overlay" class="fixed inset-0 z-50 bg-dark-bg/60 backdrop-blur-sm hidden flex items-end justify-center fade-in touch-none" onclick="closeModal(event)">
        <div id="modal-content" class="w-full max-w-md bg-white dark:bg-[#0b1121] rounded-t-[2rem] h-[85vh] flex flex-col shadow-2xl transition-transform translate-y-full border-t border-white/20 dark:border-white/5 relative">
            
            <div class="w-full pt-4 pb-2 flex justify-center shrink-0 cursor-pointer" onclick="closeModal(null, true)">
                <div class="w-12 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full"></div>
            </div>
            
            <div class="px-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center shrink-0">
                <h2 id="modal-title" class="text-xl font-bold dark:text-white">--</h2>
                <button onclick="closeModal(null, true)" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="modal-body" class="p-6 overflow-y-auto no-scrollbar flex-1 pb-24">
                </div>

            <div id="modal-footer" class="p-6 border-t border-slate-100 dark:border-slate-800 bg-white dark:bg-[#0b1121] hidden">
                </div>
        </div>
    </div>

    <script>
        // --- 1. THEME ENGINE ---
        function initTheme() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.getItem('theme')) {
                if (e.matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        });
        
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
        
        initTheme();

        // --- 2. DATA & STATE ---
        const db = {
            user: { 
                name: 'Alex Hunter', 
                email: 'alex@proplay.gg', 
                phone: '+1 888 000 2211',
                preferences: ['FPS', 'RPG', 'Strategy'],
                avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Felix&backgroundColor=1e293b'
            },
            allowedAvatars: [
                'https://api.dicebear.com/7.x/avataaars/svg?seed=Felix&backgroundColor=1e293b',
                'https://api.dicebear.com/7.x/avataaars/svg?seed=Aneka&backgroundColor=ff0055',
                'https://api.dicebear.com/7.x/avataaars/svg?seed=Zack&backgroundColor=0f172a',
                'https://api.dicebear.com/7.x/avataaars/svg?seed=Ginger&backgroundColor=4f46e5',
                'https://api.dicebear.com/7.x/avataaars/svg?seed=Trouble&backgroundColor=059669',
                'https://api.dicebear.com/7.x/avataaars/svg?seed=Bandit&backgroundColor=d97706',
                'https://api.dicebear.com/7.x/avataaars/svg?seed=Baby&backgroundColor=db2777',
                'https://api.dicebear.com/7.x/avataaars/svg?seed=Leo&backgroundColor=2563eb',
                'https://api.dicebear.com/7.x/avataaars/svg?seed=Lola&backgroundColor=7c3aed',
                'https://api.dicebear.com/7.x/avataaars/svg?seed=Miki&backgroundColor=dc2626'
            ],
            preferenceOptions: ['FPS', 'RPG', 'Strategy', 'Adventure', 'Sports', 'Puzzle', 'Horror', 'Indie'],
            tiers: [
                { 
                    name: 'MEMBER', 
                    min: 0, 
                    benefits: ['Access to Shop', 'Standard Support'],
                    description: "The starting point for every gamer. Enjoy full access to our curated shop and community events."
                },
                { 
                    name: 'SILVER', 
                    min: 250, 
                    benefits: ['5% Shop Discount', 'Extended Rentals (+24h)'],
                    description: "Step up your game. Silver members get automatic discounts on all purchases and extra time to return rentals."
                },
                { 
                    name: 'GOLD', 
                    min: 1000, 
                    benefits: ['10% Shop Discount', 'Free Shipping', 'Priority Support'],
                    description: "Gold standard. Enjoy double the discounts, free shipping on all hardware, and skip the queue for support."
                },
                { 
                    name: 'PLATINUM', 
                    min: 2500, 
                    benefits: ['15% Shop Discount', 'Same-day Delivery', 'Exclusive Events'],
                    description: "Elite status. Get gear delivered same-day, massive discounts, and invites to pro-player meetups."
                },
                { 
                    name: 'DIAMOND', 
                    min: 5000, 
                    benefits: ['20% Shop Discount', 'Personal Concierge', 'All Access'],
                    description: "The ultimate level. A dedicated account manager, maximum discounts, and access to everything."
                }
            ],
            library: [
                { id: 1, title: 'Elden Ring: Shadow of the Erdtree', platform: 'STEAM', key: 'ER24-XXXX-YYYY-ZZZZ' },
                { id: 2, title: 'Call of Duty: Black Ops 6', platform: 'BATTLE.NET', key: 'COD6-AAAA-BBBB-CCCC' },
                { id: 3, title: 'Final Fantasy VII Rebirth', platform: 'PSN', key: 'FF07-1111-2222-3333' }
            ],
            rentals: [
                { id: 101, item: 'PlayStation 5 Slim (Disc)', due: '2025-12-10', img: 'fab fa-playstation' },
                { id: 102, item: 'Nintendo Switch OLED', due: '2025-12-05', img: 'fas fa-gamepad' }
            ],
            shop: [
                { id: 201, name: 'PS5 DualSense Edge', price: 199.00, cat: 'Gear', icon: 'fas fa-gamepad' },
                { id: 202, name: 'Meta Quest 3 (128GB)', price: 499.00, cat: 'VR', icon: 'fas fa-vr-cardboard' },
                { id: 203, name: 'Xbox Series X', price: 499.00, cat: 'Console', icon: 'fab fa-xbox' },
                { id: 204, name: 'Steam Wallet $50', price: 50.00, cat: 'Digital', icon: 'fab fa-steam' },
                { id: 205, name: 'Razer BlackWidow V4', price: 169.00, cat: 'Gear', icon: 'fas fa-keyboard' },
                { id: 206, name: 'Logitech G Pro X Superlight', price: 149.00, cat: 'Gear', icon: 'fas fa-mouse' }
            ],
            history: [
                { id: 'TX-9921', date: 'Nov 28, 2025', desc: 'Rental: PS5 Slim (7 Days)', amount: -35.00, type: 'rent' },
                { id: 'TX-9920', date: 'Nov 15, 2025', desc: 'Purchase: Elden Ring DLC', amount: -39.99, type: 'buy' }
            ],
            activity: {
                totalHours: 2453,
                rank: 18,
                lastAchievement: { title: 'God of War', game: 'God of War Ragnarök', icon: 'fas fa-khanda', rarity: 'Platinum' },
                missions: [
                    { title: 'Daily: 50 Kills', progress: 35, total: 50, completed: false },
                    { title: 'Weekly: Complete Raid', progress: 1, total: 1, completed: true },
                    { title: 'Event: Winter Fest', progress: 800, total: 1000, completed: false }
                ],
                playtimeHistory: [
                    { day: 'Mon', hours: 3.5 },
                    { day: 'Tue', hours: 2.0 },
                    { day: 'Wed', hours: 5.5 },
                    { day: 'Thu', hours: 1.5 },
                    { day: 'Fri', hours: 6.0 },
                    { day: 'Sat', hours: 8.5 },
                    { day: 'Sun', hours: 7.2 }
                ]
            }
        };

        const state = {
            spent: 350.00, // Starting amount
            cart: []
        };

        // --- 3. CORE LOGIC ---

        // --- PERSISTENCE ---
        const STORAGE_KEYS = {
            USER: 'proplay_user_v1',
            STATE: 'proplay_state_v1',
            HISTORY: 'proplay_history_v1',
            ACTIVITY: 'proplay_activity_v1'
        };

        function saveSystemData() {
            localStorage.setItem(STORAGE_KEYS.USER, JSON.stringify(db.user));
            localStorage.setItem(STORAGE_KEYS.STATE, JSON.stringify(state));
            localStorage.setItem(STORAGE_KEYS.HISTORY, JSON.stringify(db.history));
            localStorage.setItem(STORAGE_KEYS.ACTIVITY, JSON.stringify(db.activity));
        }

        function loadSystemData() {
            try {
                const user = localStorage.getItem(STORAGE_KEYS.USER);
                const st = localStorage.getItem(STORAGE_KEYS.STATE);
                const hist = localStorage.getItem(STORAGE_KEYS.HISTORY);
                const act = localStorage.getItem(STORAGE_KEYS.ACTIVITY);

                if (user) {
                    db.user = JSON.parse(user);
                    // Update Header immediately
                    const headerName = document.querySelector('header h1');
                    const headerAvatar = document.getElementById('header-avatar');
                    if(headerName) headerName.innerText = db.user.name;
                    if(headerAvatar) headerAvatar.src = db.user.avatar;
                }
                if (st) {
                    const parsedState = JSON.parse(st);
                    state.spent = parsedState.spent || 0;
                    state.cart = parsedState.cart || [];
                }
                if (hist) db.history = JSON.parse(hist);
                if (act) db.activity = JSON.parse(act);
            } catch (e) {
                console.error('Error loading system data', e);
            }
        }

        function loadRealData() {
            // Load Rentals from Order History (Hardware/Consoles)
            const storedOrders = localStorage.getItem('proplayOrders_v2');
            if (storedOrders) {
                const orders = JSON.parse(storedOrders);
                // Filter for hardware/consoles which we treat as "Rentals" for this demo context
                // or items that explicitly have 'Rental' in name (if any)
                // For now, we'll assume any 'hardware' or 'console' type is a rental item in this view
                const rentalItems = orders.filter(o => 
                    (o.type === 'hardware' || o.subType === 'console' || o.subType === 'gear') && 
                    o.status !== 'cancelled'
                ).map(o => {
                    // Calculate due date (random 7-30 days from order date for demo)
                    const orderDate = new Date(o.date);
                    const dueDate = new Date(orderDate);
                    dueDate.setDate(dueDate.getDate() + 7); 
                    
                    let icon = 'fas fa-box';
                    if (o.name.toLowerCase().includes('playstation') || o.name.toLowerCase().includes('ps5')) icon = 'fab fa-playstation';
                    else if (o.name.toLowerCase().includes('xbox')) icon = 'fab fa-xbox';
                    else if (o.name.toLowerCase().includes('switch') || o.name.toLowerCase().includes('nintendo')) icon = 'fas fa-gamepad';
                    else if (o.name.toLowerCase().includes('quest') || o.name.toLowerCase().includes('vr')) icon = 'fas fa-vr-cardboard';

                    return {
                        id: o.id,
                        item: o.name,
                        due: dueDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
                        img: icon
                    };
                });

                if (rentalItems.length > 0) {
                    db.rentals = rentalItems;
                }
            }
            
            // Load Library from Order History (Digital Keys)
            if (storedOrders) {
                const orders = JSON.parse(storedOrders);
                const libraryItems = orders.filter(o => 
                    (o.type === 'digital' || o.type === 'key' || o.subType === 'steam' || o.subType === 'riot') && 
                    o.status !== 'cancelled'
                ).map(o => ({
                    id: o.id,
                    title: o.name,
                    platform: o.subType ? o.subType.toUpperCase() : 'DIGITAL',
                    key: 'XXXX-XXXX-XXXX-' + Math.floor(1000 + Math.random() * 9000) // Mock key generation
                }));

                if (libraryItems.length > 0) {
                    db.library = libraryItems;
                }
            }
        }

        function formatMoney(amount) {
            return '$' + amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function getTierInfo() {
            let current = db.tiers[0];
            let next = db.tiers[1];
            
            for(let i = 0; i < db.tiers.length; i++) {
                if(state.spent >= db.tiers[i].min) {
                    current = db.tiers[i];
                    next = db.tiers[i+1] || null;
                }
            }
            return { current, next };
        }

        function renderCurrentTier() {
            const container = document.getElementById('current-tier-display');
            if (!container) return;

            const { current, next } = getTierInfo();
            
            // Determine Style Class
            let tierClass = 'tier-member';
            if (current.name === 'SILVER') tierClass = 'tier-silver';
            if (current.name === 'GOLD') tierClass = 'tier-gold';
            if (current.name === 'PLATINUM') tierClass = 'tier-platinum';
            if (current.name === 'DIAMOND') tierClass = 'tier-diamond';

            // Progress Logic
            let progressHtml = '';
            if (next) {
                const percent = ((state.spent - current.min) / (next.min - current.min)) * 100;
                const needed = next.min - state.spent;
                // Adjust progress bar color based on background brightness
                const barBg = (current.name === 'MEMBER') ? 'bg-slate-700' : 'bg-black/10';
                
                progressHtml = `
                    <div class="mt-6">
                        <div class="flex justify-between text-[10px] font-bold uppercase opacity-70 mb-1">
                            <span>Next: ${next.name}</span>
                            <span>${formatMoney(needed)} to go</span>
                        </div>
                        <div class="w-full h-1.5 ${barBg} rounded-full overflow-hidden">
                            <div class="h-full bg-current opacity-80" style="width: ${Math.max(5, percent)}%"></div>
                        </div>
                    </div>
                `;
            } else {
                progressHtml = `<div class="mt-6 text-xs font-bold uppercase tracking-widest opacity-80">Max Level Achieved</div>`;
            }

            container.innerHTML = `
                <div class="metal-card ${tierClass} rounded-2xl p-6 shadow-lg relative overflow-hidden">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-1 opacity-80">
                                <i class="fas fa-shield-alt"></i>
                                <span class="text-xs font-bold uppercase tracking-widest">Current Status</span>
                            </div>
                            <h2 class="text-4xl font-mono font-bold tracking-tight">${current.name}</h2>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                             <i class="fas fa-crown text-xl"></i>
                        </div>
                    </div>
                    
                    ${progressHtml}
                    
                    <div class="absolute bottom-0 right-0 opacity-10 pointer-events-none">
                        <i class="fas fa-crown text-9xl transform translate-x-4 translate-y-4"></i>
                    </div>
                </div>
            `;
        }

        function renderAllTiersModal() {
            const { current } = getTierInfo();
            
            return `
                <div id="tier-scroll-container" class="flex overflow-x-auto gap-4 pb-6 -mx-6 px-6 no-scrollbar snap-x pt-2 items-center h-[320px]">
                    ${db.tiers.map((tier, index) => {
                        const isCurrent = tier.name === current.name;
                        const isUnlocked = state.spent >= tier.min;
                        
                        // Style Class
                        let tierClass = 'tier-member';
                        if (tier.name === 'SILVER') tierClass = 'tier-silver';
                        if (tier.name === 'GOLD') tierClass = 'tier-gold';
                        if (tier.name === 'PLATINUM') tierClass = 'tier-platinum';
                        if (tier.name === 'DIAMOND') tierClass = 'tier-diamond';

                        return `
                            <div data-index="${index}" class="tier-card-snap min-w-[260px] h-[280px] snap-center rounded-2xl p-5 metal-card ${tierClass} flex flex-col relative shadow-lg transition-all duration-300 opacity-50 scale-90">
                                ${isCurrent ? '<div class="absolute top-3 right-3 bg-white/90 text-black text-[10px] font-bold px-2 py-1 rounded shadow-sm">CURRENT</div>' : ''}
                                
                                <div class="mb-4">
                                    <h3 class="font-mono font-bold text-2xl mb-1">${tier.name}</h3>
                                    <p class="text-[10px] font-bold uppercase opacity-70">
                                        ${tier.min === 0 ? 'Entry Level' : `Spend ${formatMoney(tier.min)}+`}
                                    </p>
                                </div>

                                <div class="flex-1 flex items-center justify-center opacity-20">
                                     <i class="fas fa-crown text-6xl"></i>
                                </div>

                                ${!isUnlocked ? `
                                    <div class="mt-auto pt-3 border-t border-black/10">
                                        <p class="text-[10px] font-bold opacity-70"><i class="fas fa-lock mr-1"></i> Locked</p>
                                    </div>
                                ` : `
                                    <div class="mt-auto pt-3 border-t border-black/10">
                                        <p class="text-[10px] font-bold opacity-70"><i class="fas fa-check mr-1"></i> Unlocked</p>
                                    </div>
                                `}
                            </div>
                        `;
                    }).join('')}
                </div>
                
                <div id="tier-details-panel" class="transition-opacity duration-300 min-h-[200px]">
                    <!-- Populated by JS -->
                </div>
            `;
        }

        let tierObserver = null;

        function initTierObserver() {
            const container = document.getElementById('tier-scroll-container');
            if(!container) return;
            
            const cards = container.querySelectorAll('.tier-card-snap');
            
            if (tierObserver) tierObserver.disconnect();

            tierObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const index = entry.target.getAttribute('data-index');
                        updateTierDetails(index);
                        
                        // Highlight active card
                        cards.forEach(c => {
                            c.classList.remove('scale-100', 'opacity-100', 'shadow-2xl');
                            c.classList.add('opacity-50', 'scale-90');
                        });
                        entry.target.classList.remove('opacity-50', 'scale-90');
                        entry.target.classList.add('scale-100', 'opacity-100', 'shadow-2xl');
                    }
                });
            }, {
                root: container,
                threshold: 0.6,
                rootMargin: "0px -20% 0px -20%" // Focus on center
            });

            cards.forEach(card => tierObserver.observe(card));
            
            // Scroll to current tier initially
            const { current } = getTierInfo();
            const currentIndex = db.tiers.findIndex(t => t.name === current.name);
            if(currentIndex >= 0 && cards[currentIndex]) {
                cards[currentIndex].scrollIntoView({ behavior: 'auto', block: 'center', inline: 'center' });
            }
        }

        function updateTierDetails(index) {
            const tier = db.tiers[index];
            const panel = document.getElementById('tier-details-panel');
            if(!panel || !tier) return;

            panel.style.opacity = '0';
            setTimeout(() => {
                panel.innerHTML = `
                    <div class="text-center px-2">
                        <h3 class="font-bold text-xl mb-2 dark:text-white">${tier.name} Benefits</h3>
                        <p class="text-sm text-slate-500 mb-6 px-4">${tier.description}</p>
                        <div class="grid grid-cols-1 gap-3 text-left">
                            ${tier.benefits.map(b => `
                                <div class="flex items-center gap-4 p-3 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/5">
                                    <div class="w-8 h-8 rounded-full bg-brand/10 flex items-center justify-center shrink-0">
                                        <i class="fas fa-check text-brand text-sm"></i>
                                    </div>
                                    <span class="text-sm font-bold dark:text-slate-200">${b}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
                panel.style.opacity = '1';
            }, 150);
        }

        function renderActivity() {
            try {
                const container = document.getElementById('activity-container');
                if (!container) {
                    console.error('Activity container not found');
                    return;
                }

                const act = db.activity;
                if (!act || !act.playtimeHistory) {
                    console.error('Activity data missing');
                    return;
                }
                
                // Find max hours for scaling
                const maxHours = Math.max(...act.playtimeHistory.map(d => d.hours)) || 10; // Fallback to 10 if 0

                container.innerHTML = `
                    <div class="bg-white dark:bg-dark-surface rounded-2xl p-5 border border-slate-100 dark:border-dark-border shadow-sm">
                        <!-- Stats Row -->
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <p class="text-[10px] font-bold uppercase text-slate-400 tracking-widest">Total Playtime</p>
                                <h4 class="text-2xl font-mono font-bold dark:text-white">${act.totalHours} <span class="text-sm text-slate-500">hrs</span></h4>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold uppercase text-slate-400 tracking-widest">Weekly Rank</p>
                                <h4 class="text-2xl font-mono font-bold text-brand">#${act.rank}</h4>
                            </div>
                        </div>

                        <!-- Playtime Chart -->
                        <div class="mb-6">
                            <p class="text-[10px] font-bold uppercase text-slate-400 tracking-widest mb-3">Last 7 Days</p>
                            <div class="flex items-end justify-between h-24 gap-2">
                                ${act.playtimeHistory.map(d => `
                                    <div class="flex flex-col items-center gap-1 flex-1 group cursor-pointer">
                                        <div class="relative w-full bg-slate-100 dark:bg-slate-700 rounded-t-sm overflow-hidden flex items-end h-full">
                                            <div class="w-full bg-brand opacity-80 group-hover:opacity-100 transition-all duration-500" style="height: ${(d.hours / maxHours) * 100}%"></div>
                                            <!-- Tooltip -->
                                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">
                                                ${d.hours} hrs
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">${d.day}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>

                        <!-- Recent Achievement -->
                        <div class="flex items-center gap-4 mb-6 p-3 bg-slate-50 dark:bg-white/5 rounded-xl border border-slate-100 dark:border-white/5">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white shadow-lg shrink-0">
                                <i class="${act.lastAchievement.icon} text-lg"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-sm dark:text-white">${act.lastAchievement.title}</h5>
                                <p class="text-xs text-slate-500">${act.lastAchievement.game} • ${act.lastAchievement.rarity}</p>
                            </div>
                        </div>

                        <!-- Missions -->
                        <div class="space-y-4 mb-6">
                            ${act.missions.map(m => `
                            <div>
                                <div class="flex justify-between text-[10px] font-bold uppercase text-slate-500 mb-1">
                                    <span>${m.title}</span>
                                    <span>${m.progress}/${m.total}</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full ${m.completed ? 'bg-green-500' : 'bg-brand'} rounded-full transition-all duration-1000" style="width: ${(m.progress/m.total)*100}%"></div>
                                </div>
                            </div>
                            `).join('')}
                        </div>

                        <!-- Share Actions -->
                        <div>
                            <p class="text-[10px] font-bold uppercase text-slate-400 tracking-widest mb-3 text-center">Share Stats</p>
                            <div class="grid grid-cols-3 gap-2">
                                <button onclick="shareActivity('internal')" class="flex flex-col items-center justify-center p-3 rounded-xl bg-slate-50 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors gap-1 group">
                                    <i class="fas fa-share-alt text-brand group-hover:scale-110 transition-transform"></i>
                                    <span class="text-[10px] font-bold dark:text-slate-300">Feed</span>
                                </button>
                                <button onclick="shareActivity('twitter')" class="flex flex-col items-center justify-center p-3 rounded-xl bg-slate-50 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors gap-1 group">
                                    <i class="fab fa-x-twitter text-black dark:text-white group-hover:scale-110 transition-transform"></i>
                                    <span class="text-[10px] font-bold dark:text-slate-300">Post</span>
                                </button>
                                <button onclick="shareActivity('discord')" class="flex flex-col items-center justify-center p-3 rounded-xl bg-slate-50 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors gap-1 group">
                                    <i class="fab fa-discord text-[#5865F2] group-hover:scale-110 transition-transform"></i>
                                    <span class="text-[10px] font-bold dark:text-slate-300">Discord</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            } catch (e) {
                console.error('Error rendering activity:', e);
            }
        }

        function shareActivity(platform) {
            const act = db.activity;
            const msg = `I just hit ${act.totalHours} hours of playtime on ProPlay Hub! 🎮\nRank: #${act.rank}\nLast Achievement: ${act.lastAchievement.title}`;
            
            if (platform === 'internal') {
                // Create Post Object for Social Page
                const newPost = {
                    id: Date.now(),
                    user: db.user.name,
                    avatar: db.user.avatar, // Use current avatar seed
                    time: "Just now",
                    content: `Just checked my stats! 🚀\n\nTotal Playtime: ${act.totalHours} Hours\nWeekly Rank: #${act.rank}\nLatest Achievement: ${act.lastAchievement.title} in ${act.lastAchievement.game} 🏆`,
                    type: 'text',
                    mediaUrl: null,
                    poll: null,
                    likes: 0,
                    comments: [],
                    isLiked: false,
                    isMine: true
                };

                // Save to LocalStorage for Social Page to pick up
                try {
                    const storedPosts = localStorage.getItem('proplay_social_posts');
                    let posts = [];
                    if (storedPosts) {
                        posts = JSON.parse(storedPosts);
                    }
                    // Prepend new post
                    posts.unshift(newPost);
                    localStorage.setItem('proplay_social_posts', JSON.stringify(posts));
                    
                    showToast('Shared to your Social Feed!');
                } catch (e) {
                    console.error('Error sharing to feed:', e);
                    showToast('Error sharing to feed');
                }
            } else {
                simulateExternalShare(platform, msg);
            }
        }

        function simulateExternalShare(platform, content) {
            const overlay = document.getElementById('share-simulation-overlay');
            const card = document.getElementById('share-card');
            const cardBg = document.getElementById('share-card-bg');
            
            const iconContainer = document.getElementById('share-platform-icon');
            const platformName = document.getElementById('share-platform-name');
            const accountName = document.getElementById('share-account-name');
            
            const previewText = document.getElementById('share-preview-text');
            const cursor = document.getElementById('typing-cursor');
            
            const progressBar = document.getElementById('share-progress-bar');
            const progressFill = document.getElementById('share-progress-fill');
            const actionBtn = document.getElementById('share-action-btn');
            const btnText = document.getElementById('share-btn-text');

            // Config
            let config = {};
            if(platform === 'twitter') {
                config = { 
                    name: 'X', 
                    account: '@AlexHunter',
                    icon: '<i class="fab fa-x-twitter"></i>', 
                    bgClass: 'bg-black', 
                    textClass: 'text-white',
                    btnClass: 'bg-white text-black hover:bg-gray-200',
                    iconBg: 'bg-white/10 text-white'
                };
            } else {
                config = { 
                    name: 'Discord', 
                    account: '#general',
                    icon: '<i class="fab fa-discord"></i>', 
                    bgClass: 'bg-[#313338]', 
                    textClass: 'text-gray-100',
                    btnClass: 'bg-[#5865F2] text-white hover:bg-[#4752C4]',
                    iconBg: 'bg-[#5865F2] text-white'
                };
            }

            // Apply Theme
            cardBg.className = `absolute inset-0 transition-colors duration-300 ${config.bgClass}`;
            
            iconContainer.className = `w-10 h-10 rounded-full flex items-center justify-center text-xl shadow-sm transition-colors ${config.iconBg}`;
            iconContainer.innerHTML = config.icon;
            
            platformName.innerText = config.name;
            platformName.className = `font-bold text-lg leading-none transition-colors ${config.textClass}`;
            
            accountName.innerText = config.account;
            accountName.className = `text-xs opacity-70 transition-colors ${config.textClass}`;
            
            previewText.className = `text-sm font-medium leading-relaxed opacity-90 font-sans whitespace-pre-wrap transition-colors ${config.textClass}`;
            previewText.innerText = ''; // Clear
            
            cursor.className = `inline-block w-0.5 h-4 ml-1 animate-blink align-middle ${config.textClass === 'text-white' ? 'bg-white' : 'bg-black'}`;
            cursor.style.display = 'inline-block';

            actionBtn.className = `w-full py-3 rounded-xl font-bold text-sm shadow-lg transform active:scale-95 transition-all flex items-center justify-center gap-2 ${config.btnClass}`;
            btnText.innerText = platform === 'twitter' ? 'Post' : 'Send Message';
            actionBtn.disabled = true; // Disable initially
            actionBtn.style.opacity = '0.7';

            progressBar.style.opacity = '0';
            progressFill.style.width = '0%';
            progressFill.className = `h-full w-0 transition-all duration-1000 ease-out ${config.textClass === 'text-white' ? 'bg-white' : 'bg-black'}`;

            // Open Animation
            overlay.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);

            // Typing Sequence
            setTimeout(() => {
                let i = 0;
                const typeInterval = setInterval(() => {
                    previewText.innerText += content.charAt(i);
                    i++;
                    if (i >= content.length) {
                        clearInterval(typeInterval);
                        cursor.style.display = 'none';
                        
                        // Enable Button
                        actionBtn.disabled = false;
                        actionBtn.style.opacity = '1';
                        
                        // Auto Click after delay
                        setTimeout(() => {
                            // Simulate Click
                            actionBtn.classList.add('scale-95');
                            setTimeout(() => actionBtn.classList.remove('scale-95'), 150);
                            
                            btnText.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Sending...';
                            progressBar.style.opacity = '1';
                            
                            // Progress Fill
                            setTimeout(() => {
                                progressFill.style.width = '100%';
                            }, 100);

                            // Success
                            setTimeout(() => {
                                btnText.innerHTML = '<i class="fas fa-check"></i> Sent!';
                                actionBtn.className = `w-full py-3 rounded-xl font-bold text-sm shadow-lg flex items-center justify-center gap-2 bg-green-500 text-white`;
                                
                                // Close
                                setTimeout(() => {
                                    card.classList.remove('scale-100', 'opacity-100');
                                    card.classList.add('scale-95', 'opacity-0');
                                    setTimeout(() => {
                                        overlay.classList.add('hidden');
                                    }, 300);
                                }, 1500);
                            }, 1200);

                        }, 800);
                    }
                }, 15); // Typing speed
            }, 800);
        }

        function generateRandomPersona() {
            const personas = [
                {
                    name: "The FPS Pro",
                    totalHours: 3420,
                    rank: 3,
                    playtimeHistory: [
                        { day: 'Mon', hours: 4.5 }, { day: 'Tue', hours: 5.0 }, { day: 'Wed', hours: 6.5 },
                        { day: 'Thu', hours: 4.0 }, { day: 'Fri', hours: 8.0 }, { day: 'Sat', hours: 12.0 }, { day: 'Sun', hours: 10.5 }
                    ],
                    lastAchievement: { title: 'Apex Predator', game: 'Apex Legends', icon: 'fas fa-crosshairs', rarity: 'Diamond' },
                    missions: [
                        { title: 'Weekly: 50 Headshots', progress: 42, total: 50, completed: false },
                        { title: 'Daily: Win 3 Matches', progress: 3, total: 3, completed: true },
                        { title: 'Season: Reach Diamond', progress: 2800, total: 3000, completed: false }
                    ]
                },
                {
                    name: "The RPG Completionist",
                    totalHours: 5100,
                    rank: 45,
                    playtimeHistory: [
                        { day: 'Mon', hours: 2.0 }, { day: 'Tue', hours: 1.5 }, { day: 'Wed', hours: 3.0 },
                        { day: 'Thu', hours: 2.0 }, { day: 'Fri', hours: 5.0 }, { day: 'Sat', hours: 9.0 }, { day: 'Sun', hours: 8.0 }
                    ],
                    lastAchievement: { title: 'Elden Lord', game: 'Elden Ring', icon: 'fas fa-trophy', rarity: 'Ultra Rare' },
                    missions: [
                        { title: 'Main Quest: The Capital', progress: 1, total: 1, completed: true },
                        { title: 'Collect: All Relics', progress: 8, total: 10, completed: false },
                        { title: 'Side Quest: Ranni', progress: 50, total: 100, completed: false }
                    ]
                },
                {
                    name: "The Speedrunner",
                    totalHours: 1200,
                    rank: 8,
                    playtimeHistory: [
                        { day: 'Mon', hours: 6.0 }, { day: 'Tue', hours: 6.5 }, { day: 'Wed', hours: 7.0 },
                        { day: 'Thu', hours: 5.5 }, { day: 'Fri', hours: 4.0 }, { day: 'Sat', hours: 2.0 }, { day: 'Sun', hours: 3.0 }
                    ],
                    lastAchievement: { title: 'Sub 1-Hour Run', game: 'Celeste', icon: 'fas fa-stopwatch', rarity: 'Gold' },
                    missions: [
                        { title: 'Practice: Any%', progress: 15, total: 20, completed: false },
                        { title: 'Daily: No Death Run', progress: 0, total: 1, completed: false },
                        { title: 'Community: Beat WR', progress: 98, total: 100, completed: false }
                    ]
                },
                {
                    name: "The Casual Gamer",
                    totalHours: 450,
                    rank: 82,
                    playtimeHistory: [
                        { day: 'Mon', hours: 0.5 }, { day: 'Tue', hours: 0 }, { day: 'Wed', hours: 1.0 },
                        { day: 'Thu', hours: 0 }, { day: 'Fri', hours: 2.5 }, { day: 'Sat', hours: 4.0 }, { day: 'Sun', hours: 3.5 }
                    ],
                    lastAchievement: { title: 'First Victory', game: 'Fall Guys', icon: 'fas fa-crown', rarity: 'Common' },
                    missions: [
                        { title: 'Weekly: Play 5 Games', progress: 3, total: 5, completed: false },
                        { title: 'Daily: Login', progress: 1, total: 1, completed: true },
                        { title: 'Event: Summer Fun', progress: 120, total: 500, completed: false }
                    ]
                }
            ];
            return personas[Math.floor(Math.random() * personas.length)];
        }

        function randomizeStats() {
            showToast('Syncing latest stats...');
            
            // Simulate network delay
            const btn = document.querySelector('button[onclick="randomizeStats()"] i');
            if(btn) btn.classList.add('fa-spin');
            
            setTimeout(() => {
                const randomPersona = generateRandomPersona();
                db.activity = { ...db.activity, ...randomPersona };

                saveSystemData();
                renderActivity();
                if(btn) btn.classList.remove('fa-spin');
                showToast(`Loaded Profile: ${randomPersona.name}`);
            }, 800);
        }

        function updateDashboard() {
            // Update Money
            const spentEl = document.getElementById('spent-amount');
            if(spentEl) spentEl.innerText = formatMoney(state.spent);
            
            // Render Current Tier Card
            renderCurrentTier();

            // Update Cart Badge
            const badge = document.getElementById('cart-badge');
            if (state.cart.length > 0) {
                badge.innerText = state.cart.length;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }

        // --- 4. MODAL SYSTEM ---

        function openModal(id) {
            const overlay = document.getElementById('modal-overlay');
            const content = document.getElementById('modal-content');
            const title = document.getElementById('modal-title');
            const body = document.getElementById('modal-body');
            const footer = document.getElementById('modal-footer');
            
            // Reset Layout
            footer.classList.add('hidden');
            footer.innerHTML = '';
            
            // Generate Content
            switch(id) {
                case 'library':
                    title.innerText = 'My Library';
                    body.innerHTML = renderLibrary();
                    break;
                case 'rentals':
                    title.innerText = 'Active Rentals';
                    body.innerHTML = renderRentals();
                    break;
                case 'cart':
                    title.innerText = 'Your Cart';
                    body.innerHTML = renderCart();
                    if(state.cart.length > 0) {
                        footer.classList.remove('hidden');
                        const total = state.cart.reduce((a, b) => a + b.price, 0);
                        footer.innerHTML = `
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-slate-500 dark:text-slate-400 font-medium">Total</span>
                                <span class="text-2xl font-mono font-bold dark:text-white">${formatMoney(total)}</span>
                            </div>
                            <button onclick="processCheckout()" class="w-full bg-brand text-white font-bold py-4 rounded-xl shadow-glow active:scale-95 transition-transform uppercase tracking-wider">Pay Now</button>
                        `;
                    }
                    break;
                case 'history':
                    title.innerText = 'Transaction History';
                    body.innerHTML = renderHistory();
                    break;
                case 'support':
                    title.innerText = 'Support Center';
                    body.innerHTML = renderSupport();
                    break;
                case 'profile':
                    title.innerText = 'Account Settings';
                    body.innerHTML = renderProfile();
                    break;
                case 'redeem':
                    title.innerText = 'Redeem Code';
                    body.innerHTML = renderRedeem();
                    break;
                case 'benefits':
                    title.innerText = 'Tier Benefits';
                    body.innerHTML = renderBenefits();
                    break;
                case 'tiers':
                    title.innerText = 'Membership Tiers';
                    body.innerHTML = renderAllTiersModal();
                    setTimeout(initTierObserver, 100);
                    break;
            }

            // Show Modal
            overlay.classList.remove('hidden');
            requestAnimationFrame(() => {
                content.classList.remove('translate-y-full');
                content.classList.add('modal-enter');
            });
        }

        function closeModal(e, force) {
            if (force || e.target.id === 'modal-overlay') {
                const overlay = document.getElementById('modal-overlay');
                const content = document.getElementById('modal-content');
                content.classList.add('translate-y-full');
                content.classList.remove('modal-enter');
                setTimeout(() => {
                    overlay.classList.add('hidden');
                    content.classList.remove('translate-y-full'); 
                }, 300);
            }
        }

        // --- 5. RENDER FUNCTIONS (DETAILED CONTENT) ---

        function renderLibrary() {
            if(db.library.length === 0) return '<p class="text-center text-slate-500 mt-10">No games found.</p>';
            return `
                <div class="space-y-4">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex gap-3 text-sm text-blue-700 dark:text-blue-300">
                        <i class="fas fa-info-circle mt-0.5"></i>
                        <p>Click "Reveal" to view your activation key. Do not share this with anyone.</p>
                    </div>
                    ${db.library.map(game => `
                    <div class="bg-slate-50 dark:bg-dark-surface p-4 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-bold text-light-text dark:text-white leading-tight mb-1">${game.title}</h3>
                                <span class="inline-block px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide rounded bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300">${game.platform}</span>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-400">
                                <i class="fas fa-download"></i>
                            </div>
                        </div>
                        <div class="h-12 relative bg-slate-200 dark:bg-slate-800 rounded-lg overflow-hidden group cursor-pointer select-none" onclick="revealKey(this, '${game.key}')">
                            <div class="absolute inset-0 flex items-center justify-center z-10 transition-opacity duration-300" id="mask">
                                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest"><i class="fas fa-eye mr-2"></i>Tap to Reveal</span>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center bg-white dark:bg-dark-bg opacity-0 group-hover:opacity-10 transition-opacity"></div>
                            <input type="text" value="" class="w-full h-full bg-transparent text-center font-mono font-bold text-brand text-lg outline-none opacity-0" readonly>
                        </div>
                        <button onclick="navigator.clipboard.writeText('${game.key}'); showToast('Key copied to clipboard');" class="w-full mt-2 text-xs font-bold text-slate-400 hover:text-brand py-2 uppercase">Copy Code</button>
                    </div>
                    `).join('')}
                </div>`;
        }

        function renderRentals() {
            return `
                <div class="space-y-4">
                    ${db.rentals.map(item => `
                    <div class="bg-slate-50 dark:bg-dark-surface p-4 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm flex gap-4">
                        <div class="w-16 h-16 rounded-xl bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-2xl text-slate-400 shrink-0">
                            <i class="${item.img}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-light-text dark:text-white truncate">${item.item}</h3>
                            <p class="text-xs text-slate-500 mb-3">Due: <span class="font-mono text-slate-700 dark:text-slate-300">${item.due}</span></p>
                            <div class="flex gap-2">
                                <button onclick="showToast('Extension granted (+24h). Fee: $2.50');" class="flex-1 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-white text-xs font-bold py-2 rounded-lg hover:bg-slate-50">Extend</button>
                                <button onclick="showToast('Return label sent to email.');" class="flex-1 bg-brand/10 dark:bg-brand/20 text-brand text-xs font-bold py-2 rounded-lg hover:bg-brand/20">Return</button>
                            </div>
                        </div>
                    </div>
                    `).join('')}
                    <div class="p-4 rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 flex flex-col items-center justify-center text-slate-400 gap-2 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors" onclick="window.location.href='/ProPlayHub/PHP_User/userStore.php';">
                        <i class="fas fa-plus-circle text-2xl"></i>
                        <span class="text-sm font-bold">Rent New Device</span>
                    </div>
                </div>`;
        }

        function renderShop() {
            return `
                <div class="grid grid-cols-2 gap-3">
                    ${db.shop.map(item => `
                    <div class="bg-slate-50 dark:bg-dark-surface p-3 rounded-2xl border border-slate-100 dark:border-dark-border flex flex-col h-full">
                        <div class="aspect-square bg-white dark:bg-black/20 rounded-xl mb-3 flex items-center justify-center text-4xl text-slate-300 dark:text-slate-600">
                            <i class="${item.icon}"></i>
                        </div>
                        <div class="mb-1">
                            <span class="text-[10px] uppercase font-bold text-slate-400">${item.cat}</span>
                            <h3 class="font-bold text-sm text-light-text dark:text-white leading-tight line-clamp-2 min-h-[2.5em]">${item.name}</h3>
                        </div>
                        <div class="mt-auto pt-2 flex justify-between items-center">
                            <span class="font-mono font-bold text-light-text dark:text-white">${formatMoney(item.price)}</span>
                            <button onclick="addToCart('${item.name}', ${item.price})" class="w-8 h-8 rounded-lg bg-light-text dark:bg-white text-white dark:text-black flex items-center justify-center hover:opacity-80 active:scale-90 transition-transform">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>
                    `).join('')}
                </div>`;
        }

        function renderCart() {
            if(state.cart.length === 0) return `
                <div class="flex flex-col items-center justify-center h-64 text-slate-400">
                    <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-shopping-basket text-3xl opacity-50"></i>
                    </div>
                    <h3 class="font-bold text-lg dark:text-white">Your cart is empty</h3>
                    <p class="text-sm">Start shopping to upgrade your tier!</p>
                    <button onclick="window.location.href='/ProPlayHub/PHP_User/userStore.php'" class="mt-6 px-6 py-2 bg-light-text dark:bg-white text-white dark:text-black font-bold rounded-lg text-sm">Browse Shop</button>
                </div>`;
            
            return `
                <div class="space-y-4">
                    ${state.cart.map((item, index) => `
                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <h4 class="font-bold text-sm dark:text-white">${item.name}</h4>
                            <button onclick="removeFromCart(${index})" class="text-xs text-red-500 font-medium hover:underline">Remove</button>
                        </div>
                        <span class="font-mono font-bold dark:text-slate-200">${formatMoney(item.price)}</span>
                    </div>
                    `).join('')}
                </div>`;
        }

        function renderHistory() {
            return `
                <div class="space-y-4 relative pl-4 border-l-2 border-slate-200 dark:border-slate-700">
                    ${db.history.map(tx => `
                    <div class="relative mb-6 last:mb-0">
                        <div class="absolute -left-[21px] top-1 w-3 h-3 rounded-full ${tx.type === 'buy' ? 'bg-brand' : 'bg-blue-500'} ring-4 ring-white dark:ring-dark-bg"></div>
                        <div class="bg-slate-50 dark:bg-dark-surface p-3 rounded-xl border border-slate-100 dark:border-dark-border">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-[10px] font-bold uppercase text-slate-400">${tx.date}</span>
                                <span class="font-mono font-bold ${tx.amount < 0 ? 'text-light-text dark:text-white' : 'text-green-500'}">${formatMoney(tx.amount)}</span>
                            </div>
                            <h4 class="font-bold text-sm dark:text-slate-200">${tx.desc}</h4>
                            <p class="text-xs text-slate-500 mt-1">ID: ${tx.id}</p>
                        </div>
                    </div>
                    `).join('')}
                </div>`;
        }

        function renderSupport() {
            return `
                <div class="flex flex-col h-full">
                    <div class="bg-slate-50 dark:bg-dark-surface p-4 rounded-2xl border border-slate-100 dark:border-dark-border mb-6">
                        <h3 class="font-bold dark:text-white mb-2">Frequently Asked Questions</h3>
                        <div class="space-y-3">
                            <details class="text-sm text-slate-600 dark:text-slate-400 cursor-pointer">
                                <summary class="font-semibold hover:text-brand">How do rentals work?</summary>
                                <p class="mt-2 pl-4 border-l-2 border-slate-200">Select a device, choose duration, and pay. We ship within 2 hours.</p>
                            </details>
                            <details class="text-sm text-slate-600 dark:text-slate-400 cursor-pointer">
                                <summary class="font-semibold hover:text-brand">How do I upgrade tier?</summary>
                                <p class="mt-2 pl-4 border-l-2 border-slate-200">Every $1 spent earns you status. Reach $250 for Silver.</p>
                            </details>
                        </div>
                    </div>
                    <div class="mt-auto text-center">
                        <p class="text-sm text-slate-500 mb-4">Still need help?</p>
                        <button onclick="showToast('Connecting to Live Agent...'); setTimeout(() => closeModal(null, true), 1500);" class="w-full bg-light-text dark:bg-white text-white dark:text-black font-bold py-3 rounded-xl shadow-lg">Chat with Support</button>
                    </div>
                </div>`;
        }

        function renderProfile() {
            return `
                <div class="space-y-4">
                    <div class="flex justify-center mb-6">
                        <div class="w-24 h-24 rounded-full p-1 bg-gradient-to-tr from-brand to-purple-600 relative">
                             <img id="preview-avatar" src="${db.user.avatar}" class="w-full h-full rounded-full bg-light-text object-cover" alt="Avatar">
                             <button onclick="document.getElementById('avatar-picker').scrollIntoView({behavior: 'smooth'})" class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-white dark:bg-slate-700 shadow flex items-center justify-center text-xs cursor-pointer hover:bg-slate-100"><i class="fas fa-camera"></i></button>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Choose Avatar</label>
                        <div id="avatar-picker" class="mt-3 grid grid-cols-5 gap-3 pb-4">
                            ${db.allowedAvatars.map((avatar, idx) => `
                                <button type="button" onclick="selectAvatar('${avatar}')" class="relative w-full aspect-square rounded-lg overflow-hidden border-2 transition-all ${db.user.avatar === avatar ? 'border-brand shadow-glow' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300'}">
                                    <img src="${avatar}" class="w-full h-full object-cover" alt="Avatar ${idx + 1}">
                                    ${db.user.avatar === avatar ? '<div class="absolute inset-0 flex items-center justify-center bg-brand/20"><i class="fas fa-check text-white text-xl"></i></div>' : ''}
                                </button>
                            `).join('')}
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Full Name</label>
                        <input type="text" id="input-name" value="${db.user.name}" class="w-full mt-1 p-3 bg-slate-50 dark:bg-dark-surface rounded-xl border border-slate-200 dark:border-slate-700 outline-none focus:border-brand font-medium dark:text-white">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Email</label>
                        <input type="email" id="input-email" value="${db.user.email}" class="w-full mt-1 p-3 bg-slate-50 dark:bg-dark-surface rounded-xl border border-slate-200 dark:border-slate-700 outline-none focus:border-brand font-medium dark:text-white">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Phone</label>
                        <input type="tel" id="input-phone" value="${db.user.phone}" class="w-full mt-1 p-3 bg-slate-50 dark:bg-dark-surface rounded-xl border border-slate-200 dark:border-slate-700 outline-none focus:border-brand font-medium dark:text-white">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Game Preferences</label>
                        <div id="preferences-container" class="mt-2 flex flex-wrap gap-2">
                            ${db.preferenceOptions.map(pref => `
                                <button type="button" onclick="togglePreference('${pref}')" class="px-3 py-2 rounded-lg font-medium text-sm transition-all ${db.user.preferences.includes(pref) ? 'bg-brand text-white shadow-glow' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600'}">
                                    ${pref}
                                </button>
                            `).join('')}
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">App Settings</label>
                        <div class="mt-2 flex items-center justify-between p-3 bg-slate-50 dark:bg-dark-surface rounded-xl border border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-300">
                                    <i class="fas fa-moon"></i>
                                </div>
                                <span class="text-sm font-bold dark:text-white">Dark Mode</span>
                            </div>
                            <button onclick="toggleTheme(); this.classList.toggle('bg-brand'); this.classList.toggle('bg-slate-300'); this.querySelector('div').classList.toggle('translate-x-6');" class="w-12 h-6 rounded-full ${document.documentElement.classList.contains('dark') ? 'bg-brand' : 'bg-slate-300'} relative transition-colors">
                                <div class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white shadow-sm transform transition-transform ${document.documentElement.classList.contains('dark') ? 'translate-x-6' : ''}"></div>
                            </button>
                        </div>
                    </div>
                    <button onclick="saveProfile()" class="w-full bg-brand text-white font-bold py-3 rounded-xl shadow-glow mt-4">Save Changes</button>
                    <button onclick="if(confirm('Are you sure you want to log out?')) location.reload();" class="w-full text-red-500 font-bold py-3 rounded-xl text-sm mt-2">Log Out</button>
                </div>`;
        }

        function renderBenefits() {
            const { current } = getTierInfo();
            return `
                <div class="text-center mb-8">
                     <i class="fas fa-crown text-5xl text-brand mb-4 filter drop-shadow-[0_0_15px_rgba(255,0,85,0.5)]"></i>
                     <h2 class="text-3xl font-mono font-bold dark:text-white uppercase">${current.name} STATUS</h2>
                     <p class="text-slate-500">You are a valued customer.</p>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-dark-surface rounded-xl border border-slate-100 dark:border-dark-border">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        <div>
                            <h4 class="font-bold text-sm dark:text-white">5% Shop Discount</h4>
                            <p class="text-xs text-slate-500">Applied automatically at checkout</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-dark-surface rounded-xl border border-slate-100 dark:border-dark-border">
                         <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        <div>
                            <h4 class="font-bold text-sm dark:text-white">Extended Rentals</h4>
                            <p class="text-xs text-slate-500">+24h grace period on returns</p>
                        </div>
                    </div>
                    ${state.spent < 2500 ? `
                    <div class="flex items-center gap-3 p-3 opacity-50 border border-dashed border-slate-300 rounded-xl">
                        <i class="fas fa-lock text-slate-400 text-xl"></i>
                        <div>
                            <h4 class="font-bold text-sm dark:text-white">Priority Shipping</h4>
                            <p class="text-xs text-slate-500">Unlocks at PLATINUM</p>
                        </div>
                    </div>` : `
                    <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-dark-surface rounded-xl border border-slate-100 dark:border-dark-border">
                         <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        <div>
                            <h4 class="font-bold text-sm dark:text-white">Priority Shipping</h4>
                            <p class="text-xs text-slate-500">Free same-day delivery</p>
                        </div>
                    </div>`}
                </div>
            `;
        }

        function renderRedeem() {
            return `
                <div class="text-center space-y-6 pt-4">
                    <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto text-emerald-500 text-3xl shadow-sm">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg dark:text-white">Redeem Code</h3>
                        <p class="text-sm text-slate-500">Enter your promo code or gift card below.</p>
                    </div>
                    <div class="relative max-w-xs mx-auto">
                        <input type="text" id="redeem-input" placeholder="ENTER CODE" class="w-full bg-slate-50 dark:bg-dark-surface border border-slate-200 dark:border-dark-border rounded-xl px-4 py-3 text-center font-mono font-bold uppercase tracking-widest focus:outline-none focus:border-brand transition-colors dark:text-white">
                    </div>
                    <button onclick="handleRedeem()" class="w-full max-w-xs mx-auto bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-500/20 transition-all active:scale-95 block">
                        Redeem Now
                    </button>
                </div>
            `;
        }

        // --- 6. ACTIONS ---

        function addToCart(name, price) {
            state.cart.push({ name, price });
            saveSystemData();
            updateDashboard();
            showToast(`${name} added to cart`);
        }

        function removeFromCart(index) {
            state.cart.splice(index, 1);
            saveSystemData();
            openModal('cart'); // Re-render
            updateDashboard();
        }

        function processCheckout() {
            const btn = document.querySelector('#modal-footer button');
            const originalText = btn.innerText;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Processing...';
            btn.disabled = true;

            setTimeout(() => {
                const total = state.cart.reduce((a,b)=>a+b.price, 0);
                
                // Add to History
                db.history.unshift({
                    id: 'TX-' + Math.floor(Math.random() * 9000 + 1000),
                    date: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
                    desc: `Purchase: ${state.cart.length} Item(s)`,
                    amount: -total,
                    type: 'buy'
                });

                // Update State
                state.spent += total;
                state.cart = [];
                
                saveSystemData();
                
                // UI Updates
                updateDashboard();
                closeModal(null, true);
                showToast(`Payment of ${formatMoney(total)} Successful!`);
                
                // Confetti or visual cue for Rank Up?
                const {current} = getTierInfo();
                if(total > 0) {
                     // Simple check if tier possibly changed (simplified)
                     setTimeout(() => showToast(`Rank Updated: ${current.name}`), 1500);
                }

            }, 1500);
        }

        function handleRedeem() {
            const input = document.getElementById('redeem-input');
            if(!input) return;
            const code = input.value.trim().toUpperCase();
            
            if (!code) {
                showToast('⚠️ Please enter a code');
                return;
            }
            
            // Mock Codes
            const codes = {
                'WELCOME2025': 50,
                'PROPLAY100': 100,
                'VIPMEMBER': 200
            };
            
            if (codes[code]) {
                const amount = codes[code];
                state.spent += amount;
                
                // Add to history
                db.history.unshift({
                    id: 'RD-' + Math.floor(Math.random() * 10000),
                    date: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
                    desc: `Redeemed Code: ${code}`,
                    amount: amount, 
                    type: 'credit' 
                });
                
                saveSystemData();
                updateDashboard();
                closeModal(null, true);
                showToast(`✓ Redeemed ${formatMoney(amount)} value!`);
            } else {
                showToast('❌ Invalid or Expired Code');
            }
        }

        function logout() {
            if(confirm('Are you sure you want to log out?')) {
                window.location.href = '/ProPlayHub/index.html'; 
            }
        }

        function revealKey(el, key) {
            const input = el.querySelector('input');
            const mask = el.querySelector('#mask');
            
            mask.style.opacity = '0';
            mask.style.pointerEvents = 'none';
            input.value = key;
            input.style.opacity = '1';
            
            // Haptic
            if(navigator.vibrate) navigator.vibrate(50);
        }

        function showToast(msg) {
            const toast = document.getElementById('toast');
            const text = document.getElementById('toast-msg');
            text.innerText = msg;
            
            toast.classList.remove('opacity-0', '-translate-y-24');
            toast.classList.add('translate-y-0', 'opacity-100');
            
            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('opacity-0', '-translate-y-24');
            }, 3000);
        }

        function saveProfile() {
            const name = document.getElementById('input-name').value.trim();
            const email = document.getElementById('input-email').value.trim();
            const phone = document.getElementById('input-phone').value.trim();
            
            // Validation
            if (!name) {
                showToast('⚠️ Please enter your name');
                return;
            }
            
            if (!email || !email.includes('@')) {
                showToast('⚠️ Invalid email address');
                return;
            }
            
            if (!phone) {
                showToast('⚠️ Please enter your phone number');
                return;
            }

            if (db.user.preferences.length === 0) {
                showToast('⚠️ Please select at least one game preference');
                return;
            }
            
            // Update data
            db.user.name = name;
            db.user.email = email;
            db.user.phone = phone;
            
            // Update header
            document.querySelector('header h1').innerText = name;
            document.getElementById('header-avatar').src = db.user.avatar;
            
            saveSystemData();
            
            closeModal(null, true);
            showToast('✓ Profile updated successfully!');
        }

        function selectAvatar(avatar) {
            db.user.avatar = avatar;
            saveSystemData();
            openModal('profile'); // Re-render to show selection
        }

        function togglePreference(pref) {
            const index = db.user.preferences.indexOf(pref);
            if (index > -1) {
                db.user.preferences.splice(index, 1);
            } else {
                db.user.preferences.push(pref);
            }
            saveSystemData();
            openModal('profile'); // Re-render
        }

        // Init
        function initProfilePage() {
            console.log('Initializing Profile Page...');
            loadSystemData();
            loadRealData();
            
            // Load random persona only if not present
            if (!localStorage.getItem(STORAGE_KEYS.ACTIVITY)) {
                const initialPersona = generateRandomPersona();
                db.activity = { ...db.activity, ...initialPersona };
                saveSystemData();
            }
            
            updateDashboard();
            renderActivity();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initProfilePage);
        } else {
            initProfilePage();
        }

    </script>
    </div> <!-- End Wrapper -->