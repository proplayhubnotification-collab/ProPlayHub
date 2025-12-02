<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ProPlay Hub - CSR Admin</title>
    
    <!-- CSS & Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Theme Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: '#ec003f',
                        brandHover: '#c90036',
                        brandDark: '#b0002f',
                        light: { bg: '#f8fafc', text: '#020618' },
                        dark: { bg: '#020618', text: '#f8fafc', surface: '#111827', border: '#1e293b' },
                    },
                    borderRadius: { 'custom': '0.375rem' },
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    boxShadow: {
                        'glow': '0 0 20px rgba(236, 0, 63, 0.3)',
                        'sheet': '0 -5px 25px rgba(0,0,0,0.2)',
                        'nav-glass': '0 -10px 30px rgba(0,0,0,0.1)',
                        'urgent': '0 0 15px rgba(239, 68, 68, 0.4)'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.2s ease-out forwards',
                        'pulse-fast': 'pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } }
                    }
                }
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        body { transition: background-color 0.3s, color 0.3s; user-select: none; -webkit-tap-highlight-color: transparent; }
        
        .nav-item.active i { color: #ec003f; transform: translateY(-2px); }
        .nav-item.active span { color: #ec003f; font-weight: 700; }
        .nav-item { transition: all 0.2s; }
    </style>
</head>
<body class="bg-light-bg text-light-text dark:bg-dark-bg dark:text-dark-text font-sans antialiased h-screen flex justify-center overflow-hidden">

    <div id="app-container" class="w-full max-w-md bg-light-bg dark:bg-dark-bg h-full relative shadow-2xl flex flex-col overflow-hidden">
        
        <main class="flex-1 overflow-y-auto no-scrollbar relative bg-gray-50 dark:bg-black/20 scroll-smooth" id="main-outlet">
            <?php echo $content; ?>
        </main>

        <nav class="fixed bottom-0 left-0 w-full z-50 px-4 pb-[env(safe-area-inset-bottom)] pt-2 border-t border-white/20 bg-white/80 dark:bg-[#0f172a]/85 backdrop-blur-xl shadow-nav-glass transition-all duration-300">
            <div class="relative flex justify-between items-end w-full max-w-lg mx-auto h-[60px]">
                
                <!-- Social (Home for CSR) -->
                <a class="nav-item group flex-1 flex flex-col items-center justify-center h-full pb-2 relative" href="../PHP_CSR/csrSocial.php" data-id="social">
                    <div class="icon-container text-2xl text-slate-400 group-hover:text-brand/70 mb-1">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <span class="nav-label text-[10px] uppercase tracking-wider text-slate-400 opacity-70 group-hover:opacity-100 transition-all duration-300">Social</span>
                </a>

                <!-- Users Management -->
                <a class="nav-item group flex-1 flex flex-col items-center justify-center h-full pb-2 relative" href="../PHP_CSR/csrUserManagement.php" data-id="users">
                    <div class="icon-container text-2xl text-slate-400 group-hover:text-brand/70 mb-1">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <span class="nav-label text-[10px] uppercase tracking-wider text-slate-400 opacity-70 group-hover:opacity-100 transition-all duration-300">Users</span>
                </a>

                <!-- Store Management (Center) -->
                <div class="relative -top-6 w-16 h-16 flex justify-center items-center">
                    <a class="nav-item center-btn absolute w-14 h-14 rounded-full bg-brand text-white shadow-glow center-btn-pulse flex items-center justify-center text-2xl z-10 hover:scale-110 transition-transform duration-300 active:scale-90" href="../PHP_CSR/csrStore.php" data-id="store">
                        <i class="fa-solid fa-shop"></i>
                    </a>
                </div>

                <!-- Orders Management -->
                <a class="nav-item group flex-1 flex flex-col items-center justify-center h-full pb-2 relative" href="../PHP_CSR/csrOrders.php" data-id="orders">
                    <div class="icon-container text-2xl text-slate-400 group-hover:text-brand/70 mb-1">
                       <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                    <span class="nav-label text-[10px] uppercase tracking-wider text-slate-400 opacity-70 group-hover:opacity-100 transition-all duration-300">Orders</span>
                </a>

                <!-- Profile / Settings -->
                <a class="nav-item group flex-1 flex flex-col items-center justify-center h-full pb-2 relative" href="../PHP_CSR/csrProfile.php" data-id="profile">
                    <div class="icon-container text-2xl text-slate-400 group-hover:text-brand/70 mb-1">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <span class="nav-label text-[10px] uppercase tracking-wider text-slate-400 opacity-70 group-hover:opacity-100 transition-all duration-300">Admin</span>
                </a>
            </div>
        </nav>

    </div>

    <script>
        const layoutApp = {
            init() {
                this.loadTheme();
                this.highlightActiveNav();
            },
            loadTheme() {
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
            },
            highlightActiveNav() {
                const currentPath = window.location.pathname;
                const navItems = document.querySelectorAll('.nav-item');
                
                navItems.forEach(item => {
                    if (currentPath.includes(item.getAttribute('href').split('/').pop())) {
                        item.classList.add('active');
                    }
                });
            }
        };
        
        document.addEventListener('DOMContentLoaded', () => layoutApp.init());
    </script>
</body>
</html>
