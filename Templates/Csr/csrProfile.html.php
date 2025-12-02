<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ProPlay Admin Portal</title>
    
    <meta name="application-name" content="ProPlay Admin">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#f8fafc" id="theme-color-meta">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                fontFamily: {
                    sans: ['"Inter"', 'sans-serif'],
                    mono: ['"Rajdhani"', 'monospace'],
                },
                colors: {
                    brand: '#ec003f',
                    brandDark: '#b0002f',
                    light: { bg: '#f8fafc', text: '#020618' },
                    dark: { bg: '#020618', text: '#f8fafc', surface: '#111827', border: '#1e293b' }
                },
                animation: {
                    'slide-in': 'slideInRight 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    'slide-out': 'slideOutRight 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                },
                keyframes: {
                    slideInRight: { '0%': { transform: 'translateX(100%)' }, '100%': { transform: 'translateX(0)' } },
                    slideOutRight: { '0%': { transform: 'translateX(0)' }, '100%': { transform: 'translateX(100%)' } }
                }
            }
        }
      }
    </script>
    <style>
        body { -webkit-tap-highlight-color: transparent; overscroll-behavior-y: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .safe-pb { padding-bottom: env(safe-area-inset-bottom); }
        
        /* View Transition Containers */
        #app-container { position: relative; width: 100%; height: 100vh; overflow: hidden; background-color: #000; }
        
        .view-screen {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            background-color: var(--bg-color, #f8fafc);
            will-change: transform;
        }
        .dark .view-screen { background-color: #020618; }

        #screen-dashboard {
            z-index: 10;
            transition: transform 0.5s cubic-bezier(0.32, 0.72, 0, 1), border-radius 0.5s ease, opacity 0.5s ease, filter 0.5s ease;
            transform-origin: center center;
        }

        #screen-subview {
            z-index: 20;
            transform: translateY(100%);
            transition: transform 0.5s cubic-bezier(0.32, 0.72, 0, 1);
            box-shadow: 0 -25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Active State applied to container */
        .has-subview #screen-dashboard {
            transform: scale(0.92);
            border-radius: 20px;
            opacity: 0.5;
            filter: blur(1px);
            pointer-events: none;
        }

        .has-subview #screen-subview {
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-light-bg text-light-text dark:bg-dark-bg dark:text-dark-text transition-colors duration-300 min-h-screen font-sans overflow-hidden safe-pb select-none">

    <div id="toast-container" class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] flex flex-col gap-2 pointer-events-none w-full max-w-xs px-4"></div>

    <div id="app-container">
        
        <div id="screen-dashboard" class="view-screen overflow-y-auto pb-20">
            
            <header class="px-6 pt-8 pb-2 sticky top-0 z-40 bg-light-bg/95 dark:bg-dark-bg/95 backdrop-blur-md flex justify-between items-center">
                <h1 class="text-lg font-bold">Admin Profile</h1>
                <div class="w-10"></div> </header>

            <main class="max-w-md mx-auto px-6 mt-4 space-y-6">
                <div class="relative group">
                    <div class="bg-white dark:bg-dark-surface border border-slate-100 dark:border-dark-border rounded-2xl p-6 shadow-sm flex flex-col items-center text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] dark:opacity-[0.05]"></div>
                        <div class="relative mb-3 z-10">
                            <div class="w-24 h-24 rounded-full p-1 border-2 border-brand relative">
                                <img id="user-avatar" src="https://api.dicebear.com/7.x/avataaars/svg?seed=AdminSarah&backgroundColor=020618" class="w-full h-full rounded-full bg-slate-800 object-cover">
                                <label class="absolute bottom-0 right-0 w-8 h-8 bg-brand text-white rounded-full border-4 border-white dark:border-dark-surface flex items-center justify-center shadow-sm cursor-pointer active:scale-90 transition-transform">
                                    <i class="fas fa-camera text-[10px]"></i>
                                    <input type="file" class="hidden" onchange="handleAvatarUpload(this)">
                                </label>
                            </div>
                        </div>
                        <h2 id="user-name-display" class="text-xl font-bold dark:text-white z-10">Sarah Jenkins</h2>
                        <p id="user-role-display" class="text-sm text-brand font-mono font-medium mb-4 z-10">Super Admin (Lv.5)</p>
                        <div class="flex items-center gap-2 bg-slate-50 dark:bg-white/5 px-4 py-2 rounded-xl border border-slate-100 dark:border-white/5 z-10">
                            <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span>
                            <span class="text-xs font-mono font-bold text-slate-600 dark:text-slate-300">SYSTEM: ONLINE</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-xs mb-3 text-slate-400 uppercase tracking-widest pl-1">Operations</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Social Feed -->
                        <button onclick="navigateTo('social')" class="bg-white dark:bg-dark-surface p-4 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm active:scale-[0.98] transition-transform text-left relative overflow-hidden group">
                            <div class="flex justify-between items-start mb-2">
                                <div class="w-10 h-10 rounded-xl bg-pink-50 dark:bg-pink-900/20 text-pink-500 flex items-center justify-center text-lg"><i class="fas fa-globe-asia"></i></div>
                                <span class="bg-red-100 text-red-600 text-[9px] font-bold px-2 py-0.5 rounded-full animate-pulse">LIVE</span>
                            </div>
                            <p class="font-bold text-sm dark:text-white group-hover:text-brand transition-colors">Social Feed</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">12 New Posts</span>
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                <span class="text-[10px] text-slate-400">3 Reports</span>
                            </div>
                        </button>

                        <!-- User Management -->
                        <button onclick="navigateTo('users')" class="bg-white dark:bg-dark-surface p-4 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm active:scale-[0.98] transition-transform text-left relative overflow-hidden group">
                            <div class="flex justify-between items-start mb-2">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/20 text-orange-500 flex items-center justify-center text-lg"><i class="fas fa-users-cog"></i></div>
                                <span class="bg-green-100 text-green-600 text-[9px] font-bold px-2 py-0.5 rounded-full">+12%</span>
                            </div>
                            <p class="font-bold text-sm dark:text-white group-hover:text-brand transition-colors">User Mgmt</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">8.2k Users</span>
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                <span class="text-[10px] text-slate-400">Active</span>
                            </div>
                        </button>

                        <!-- Inventory -->
                        <button onclick="navigateTo('store')" class="bg-white dark:bg-dark-surface p-4 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm active:scale-[0.98] transition-transform text-left relative overflow-hidden group">
                            <div class="flex justify-between items-start mb-2">
                                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/20 text-purple-500 flex items-center justify-center text-lg"><i class="fas fa-boxes"></i></div>
                                <span class="bg-amber-100 text-amber-600 text-[9px] font-bold px-2 py-0.5 rounded-full">Alert</span>
                            </div>
                            <p class="font-bold text-sm dark:text-white group-hover:text-brand transition-colors">Inventory</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">450 Items</span>
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                <span class="text-[10px] text-amber-500 font-bold">2 Low</span>
                            </div>
                        </button>

                        <!-- Orders -->
                        <button onclick="navigateTo('orders')" class="bg-white dark:bg-dark-surface p-4 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm active:scale-[0.98] transition-transform text-left relative overflow-hidden group">
                            <div class="flex justify-between items-start mb-2">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 flex items-center justify-center text-lg"><i class="fas fa-clipboard-list"></i></div>
                                <span class="bg-brand text-white text-[9px] font-bold px-2 py-0.5 rounded-full">3 New</span>
                            </div>
                            <p class="font-bold text-sm dark:text-white group-hover:text-brand transition-colors">Orders</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Processing</span>
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                <span class="text-[10px] text-slate-400">$1.2k</span>
                            </div>
                        </button>

                        <!-- Support Center (Highlighted) -->
                        <button onclick="navigateTo('support')" class="col-span-2 bg-gradient-to-r from-blue-500 to-blue-600 p-4 rounded-2xl shadow-lg shadow-blue-500/20 active:scale-[0.98] transition-transform text-left relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-3 opacity-10">
                                <i class="fas fa-headset text-6xl text-white"></i>
                            </div>
                            <div class="flex items-center gap-4 relative z-10">
                                <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm text-white flex items-center justify-center text-xl shrink-0 border border-white/10">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <p class="font-bold text-sm text-white">Support Center</p>
                                        <span class="bg-white/20 text-white text-[9px] font-bold px-2 py-0.5 rounded-full backdrop-blur-sm">High Priority</span>
                                    </div>
                                    <p class="text-[11px] text-blue-100">5 Tickets Waiting • Avg Response: 2m</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-white text-blue-600 flex items-center justify-center shadow-sm">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-xs mb-3 text-slate-400 uppercase tracking-widest pl-1">Settings</h3>
                    <div class="bg-white dark:bg-dark-surface rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm overflow-hidden divide-y divide-slate-50 dark:divide-white/5">
                        
                        <!-- Notifications -->
                        <button onclick="navigateTo('notifications')" class="w-full p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-900/20 text-red-500 flex items-center justify-center transition-transform group-hover:scale-110">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-bold dark:text-white">Notifications</p>
                                    <p class="text-[10px] text-slate-400">Manage alerts & sounds</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-[10px] font-bold">On</span>
                                <i class="fas fa-chevron-right text-slate-300 text-xs group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </button>

                        <!-- Edit Profile -->
                        <button onclick="navigateTo('profile-edit')" class="w-full p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-purple-50 dark:bg-purple-900/20 text-purple-500 flex items-center justify-center transition-transform group-hover:scale-110">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-bold dark:text-white">Edit Profile</p>
                                    <p class="text-[10px] text-slate-400">Account details</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-slate-300 text-xs group-hover:translate-x-1 transition-transform"></i>
                        </button>

                        <!-- Application -->
                        <button onclick="navigateTo('app-settings')" class="w-full p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center transition-transform group-hover:scale-110">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-bold dark:text-white">Application</p>
                                    <p class="text-[10px] text-slate-400">Version 2.5.1</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-slate-300 text-xs group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>

                <button onclick="handleLogout()" class="w-full py-4 rounded-2xl bg-slate-100 dark:bg-white/5 text-slate-500 font-bold text-sm hover:text-red-500 transition-colors flex items-center justify-center gap-2 active:scale-95">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </main>
        </div>

        <div id="screen-subview" class="view-screen bg-light-bg dark:bg-dark-bg z-50 overflow-y-auto">
            <header class="px-6 pt-8 pb-4 sticky top-0 z-40 bg-light-bg/95 dark:bg-dark-bg/95 backdrop-blur-md border-b border-slate-100 dark:border-white/5 flex items-center gap-4">
                <button onclick="closeSubView()" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 flex items-center justify-center active:scale-95">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h2 id="subview-title" class="text-lg font-bold truncate">Detail Page</h2>
            </header>
            
            <div id="subview-content" class="p-6 pb-24 space-y-4">
                </div>
        </div>

    </div>

    <script>
        // --- 1. SYSTEM CONFIG & AUTO THEME ---
        const darkMeta = '#020618';
        const lightMeta = '#f8fafc';
        const metaTag = document.getElementById('theme-color-meta');

        function updateTheme(e) {
            const isDark = e.matches;
            if (isDark) {
                document.documentElement.classList.add('dark');
                metaTag.setAttribute('content', darkMeta);
            } else {
                document.documentElement.classList.remove('dark');
                metaTag.setAttribute('content', lightMeta);
            }
        }

        // Initialize & Listen
        const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        updateTheme(darkModeMediaQuery); // Run once on load
        darkModeMediaQuery.addEventListener('change', updateTheme); // Listen for system changes

        // --- 2. NAVIGATION SYSTEM (VIEW MANAGER) ---
        const appContainer = document.getElementById('app-container');
        const dashboard = document.getElementById('screen-dashboard');
        const subview = document.getElementById('screen-subview');
        const subTitle = document.getElementById('subview-title');
        const subContent = document.getElementById('subview-content');

        // --- DATA MANAGEMENT ---
        const defaultProfile = {
            name: 'Sarah Jenkins',
            email: 'sarah.admin@proplay.gg',
            phone: '+1 234 567 890',
            department: 'Management',
            bio: 'Senior Administrator with 5 years of experience in customer support and team management.',
            avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=AdminSarah&backgroundColor=020618'
        };

        const defaultAppSettings = {
            volume: 80,
            sound: true,
            haptics: true
        };

        function loadData() {
            const profile = JSON.parse(localStorage.getItem('csr_profile')) || defaultProfile;
            const settings = JSON.parse(localStorage.getItem('csr_settings')) || defaultAppSettings;
            return { profile, settings };
        }

        function saveData(key, data) {
            localStorage.setItem(key, JSON.stringify(data));
        }

        // Initialize View
        function initProfile() {
            const { profile } = loadData();
            // Update Dashboard Header
            const nameDisplay = document.getElementById('user-name-display');
            const roleDisplay = document.getElementById('user-role-display');
            const avatarDisplay = document.getElementById('user-avatar');

            if(nameDisplay) nameDisplay.innerText = profile.name;
            if(roleDisplay) roleDisplay.innerText = profile.department;
            if(avatarDisplay) avatarDisplay.src = profile.avatar;
        }
        
        // Run on load
        initProfile();

        // Avatar List
        const avatars = [
            'https://api.dicebear.com/7.x/avataaars/svg?seed=AdminSarah&backgroundColor=020618',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Felix',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Aneka',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Zack',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Trouble',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Bear',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Annie',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Man',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Woman',
            'https://api.dicebear.com/7.x/avataaars/svg?seed=Human'
        ];

        function renderEditProfile() {
            const { profile } = loadData();
            return `
                <div class="space-y-6">
                    <!-- Avatar Selection -->
                    <div class="bg-white dark:bg-dark-surface p-4 rounded-xl border border-slate-100 dark:border-dark-border">
                        <label class="text-xs font-bold text-slate-400 block mb-3 uppercase tracking-wider">Choose Avatar</label>
                        <div class="grid grid-cols-5 gap-3">
                            ${avatars.map((url) => `
                                <div onclick="selectAvatar('${url}', this)" class="aspect-square rounded-full overflow-hidden border-2 ${url === profile.avatar ? 'border-brand' : 'border-transparent'} cursor-pointer hover:scale-110 transition-transform relative group">
                                    <img src="${url}" class="w-full h-full object-cover bg-slate-100 dark:bg-slate-800">
                                    ${url === profile.avatar ? '<div class="absolute inset-0 bg-brand/20 flex items-center justify-center"><i class="fas fa-check text-white text-xs"></i></div>' : ''}
                                </div>
                            `).join('')}
                        </div>
                    </div>

                    <!-- Personal Info -->
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-slate-400 block mb-1.5 ml-1">Full Name</label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" id="input-name" value="${profile.name}" class="w-full pl-10 pr-4 py-3 rounded-xl bg-white dark:bg-dark-surface border border-slate-200 dark:border-dark-border dark:text-white focus:border-brand focus:ring-1 focus:ring-brand outline-none transition-all">
                            </div>
                        </div>
                        
                        <div>
                            <label class="text-xs font-bold text-slate-400 block mb-1.5 ml-1">Email Address</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="email" id="input-email" value="${profile.email}" class="w-full pl-10 pr-4 py-3 rounded-xl bg-white dark:bg-dark-surface border border-slate-200 dark:border-dark-border dark:text-white focus:border-brand focus:ring-1 focus:ring-brand outline-none transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-1.5 ml-1">Phone</label>
                                <input type="tel" id="input-phone" value="${profile.phone}" class="w-full px-4 py-3 rounded-xl bg-white dark:bg-dark-surface border border-slate-200 dark:border-dark-border dark:text-white focus:border-brand focus:ring-1 focus:ring-brand outline-none transition-all">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 block mb-1.5 ml-1">Department</label>
                                <select id="input-dept" class="w-full px-4 py-3 rounded-xl bg-white dark:bg-dark-surface border border-slate-200 dark:border-dark-border dark:text-white focus:border-brand focus:ring-1 focus:ring-brand outline-none transition-all appearance-none">
                                    <option ${profile.department === 'Support' ? 'selected' : ''}>Support</option>
                                    <option ${profile.department === 'Management' ? 'selected' : ''}>Management</option>
                                    <option ${profile.department === 'Sales' ? 'selected' : ''}>Sales</option>
                                    <option ${profile.department === 'Technical' ? 'selected' : ''}>Technical</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-400 block mb-1.5 ml-1">Bio</label>
                            <textarea id="input-bio" rows="3" class="w-full px-4 py-3 rounded-xl bg-white dark:bg-dark-surface border border-slate-200 dark:border-dark-border dark:text-white focus:border-brand focus:ring-1 focus:ring-brand outline-none transition-all resize-none">${profile.bio}</textarea>
                        </div>
                    </div>

                    <button class="w-full bg-brand text-white py-4 rounded-xl font-bold shadow-lg shadow-brand/30 active:scale-[0.98] transition-transform flex items-center justify-center gap-2" onclick="saveProfileChanges()">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            `;
        }

        function renderAppSettings() {
            const { settings } = loadData();
            return `
                <div class="space-y-6">
                    <!-- Audio & Haptics -->
                    <div class="bg-white dark:bg-dark-surface rounded-xl border border-slate-100 dark:border-dark-border p-5 space-y-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Audio & Haptics</h3>
                        
                        <!-- Volume Slider -->
                        <div>
                            <div class="flex justify-between mb-3">
                                <span class="text-sm font-bold dark:text-white flex items-center gap-2"><i class="fas fa-volume-up text-slate-400"></i> System Volume</span>
                                <span class="text-xs font-mono font-bold text-brand" id="vol-display">${settings.volume}%</span>
                            </div>
                            <input type="range" min="0" max="100" value="${settings.volume}" class="w-full h-2 bg-slate-100 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-brand" 
                                oninput="document.getElementById('vol-display').innerText = this.value + '%'; saveAppSettings('volume', this.value)">
                        </div>

                        <!-- Toggles -->
                        <div class="flex justify-between items-center pt-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center"><i class="fas fa-music text-xs"></i></div>
                                <span class="text-sm font-medium dark:text-white">Sound Effects</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" ${settings.sound ? 'checked' : ''} class="sr-only peer" onchange="saveAppSettings('sound', this.checked)">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                            </label>
                        </div>

                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-purple-50 dark:bg-purple-900/20 text-purple-500 flex items-center justify-center"><i class="fas fa-mobile-alt text-xs"></i></div>
                                <span class="text-sm font-medium dark:text-white">Haptic Feedback</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" ${settings.haptics ? 'checked' : ''} class="sr-only peer" onchange="saveAppSettings('haptics', this.checked)">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                            </label>
                        </div>
                    </div>

                    <!-- System Info -->
                    <div class="bg-white dark:bg-dark-surface rounded-xl border border-slate-100 dark:border-dark-border p-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 pl-1">System</h3>
                        <div class="flex justify-between py-3 border-b border-slate-50 dark:border-white/5">
                            <span class="text-sm dark:text-white">Language</span>
                            <span class="text-sm font-bold text-brand flex items-center gap-2">English <i class="fas fa-chevron-right text-xs text-slate-300"></i></span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-slate-50 dark:border-white/5">
                            <span class="text-sm dark:text-white">App Version</span>
                            <span class="text-sm font-bold text-slate-400 font-mono">v2.5.1 (Build 2025)</span>
                        </div>
                        <div class="flex justify-between py-3 pt-4">
                            <button onclick="localStorage.clear(); location.reload();" class="text-red-500 text-sm font-bold w-full text-left flex items-center gap-2 hover:text-red-600 transition-colors">
                                <i class="fas fa-trash-alt"></i> Reset All Data
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function selectAvatar(url, el) {
            const parent = el.parentElement;
            Array.from(parent.children).forEach(child => {
                child.classList.remove('border-brand');
                child.classList.add('border-transparent');
                const check = child.querySelector('.absolute');
                if(check) check.remove();
            });
            
            el.classList.remove('border-transparent');
            el.classList.add('border-brand');
            el.innerHTML += '<div class="absolute inset-0 bg-brand/20 flex items-center justify-center"><i class="fas fa-check text-white text-xs"></i></div>';
        }

        function saveProfileChanges() {
            const { profile } = loadData();
            
            // Gather Data
            const newProfile = {
                ...profile,
                name: document.getElementById('input-name').value,
                email: document.getElementById('input-email').value,
                phone: document.getElementById('input-phone').value,
                department: document.getElementById('input-dept').value,
                bio: document.getElementById('input-bio').value,
                avatar: document.querySelector('.border-brand img')?.src || profile.avatar
            };

            // Save
            saveData('csr_profile', newProfile);
            
            // Update UI
            initProfile();
            showToast('Profile updated successfully!');
            setTimeout(() => closeSubView(), 1000);
        }

        function saveAppSettings(key, value) {
            const { settings } = loadData();
            settings[key] = value;
            saveData('csr_settings', settings);
            // Optional: Provide feedback like a small vibration if haptics enabled
        }

        function navigateTo(key) {
            const routes = {
                'social': 'csrSocial.php',
                'users': 'csrUserManagement.php',
                'store': 'csrStore.php',
                'orders': 'csrOrders.php',
                'notifications': 'csrNotifications.php',
                'support': 'csrChat.php'
            };

            if (routes[key]) {
                window.location.href = routes[key];
                return;
            }

            let title = '';
            let html = '';

            if (key === 'profile-edit') {
                title = 'Edit Profile';
                html = renderEditProfile();
            } else if (key === 'app-settings') {
                title = 'App Settings';
                html = renderAppSettings();
            } else {
                return; // Unknown route
            }

            // 1. Populate Data
            subTitle.innerText = title;
            subContent.innerHTML = html;

            // 2. Animate View
            appContainer.classList.add('has-subview');
            
            // Push history state to allow back button of browser to work
            window.history.pushState({ view: 'sub' }, '', `#${key}`);
        }

        function closeSubView() {
            appContainer.classList.remove('has-subview');
            setTimeout(() => {
                subContent.innerHTML = ''; // Clean up
            }, 500);
            
            // Handle browser history URL
            if(window.location.hash) {
                history.back(); 
            }
        }

        // Browser Back Button Support
        window.addEventListener('popstate', (event) => {
            // If we are essentially "back" at root, hide the subview
            if (!window.location.hash) {
                 appContainer.classList.remove('has-subview');
            }
        });

        // --- 3. OTHER INTERACTION LOGIC ---
        function handleAvatarUpload(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('user-avatar').src = e.target.result;
                    showToast('Profile picture updated!');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function handleLogout() {
            if(confirm('Logout from this device?')) {
                document.body.innerHTML = `<div class="flex items-center justify-center h-screen bg-brand text-white font-bold text-xl flex-col gap-4"><i class="fas fa-circle-notch animate-spin text-4xl"></i>Logging out...</div>`;
                setTimeout(() => window.location.href = '../index.html', 1500);
            }
        }

        function showToast(msg) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `flex items-center gap-3 p-3 rounded-full bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-glow pointer-events-auto transform transition-all duration-300 translate-y-10 opacity-0`;
            toast.innerHTML = `<div class="w-8 h-8 rounded-full bg-brand text-white flex items-center justify-center shrink-0"><i class="fas fa-check text-xs"></i></div><span class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate pr-2">${msg}</span>`;
            container.appendChild(toast);
            requestAnimationFrame(() => toast.classList.remove('translate-y-10', 'opacity-0'));
            setTimeout(() => {
                toast.classList.add('opacity-0', '-translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // PWA Install Logic (Simplified)
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            showToast('Add app to home screen?');
        });
    </script>
</body>
</html>