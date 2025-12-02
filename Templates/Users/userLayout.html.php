<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ProPlay Hub - Master Layout</title>
    
    <!-- Thư viện CSS & Icon -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Cấu hình Theme -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: '#ec003f',
                        brandHover: '#c90036',
                        light: { bg: '#f8fafc', text: '#020618' },
                        dark: { bg: '#020618', text: '#f8fafc' },
                    },
                    borderRadius: { 'custom': '0.375rem' },
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    animation: {
                        'fade-in': 'fadeIn 0.2s ease-out forwards',
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
                <a class="nav-item group flex-1 flex flex-col items-center justify-center h-full pb-2 relative" href="../PHP_User/userSocialPage.php" data-href="SocialPage.php" data-id="home">
                    <div class="icon-container text-2xl text-slate-400 group-hover:text-brand/70 mb-1">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <span class="nav-label text-[10px] uppercase tracking-wider text-slate-400 opacity-70 group-hover:opacity-100 transition-all duration-300">Home</span>
                </a>

                <a class="nav-item group flex-1 flex flex-col items-center justify-center h-full pb-2 relative" href="../PHP_User/userLiveChat.php" data-href="LiveChat.php" data-id="support">
                    <div class="icon-container text-2xl text-slate-400 group-hover:text-brand/70 mb-1">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <span class="nav-label text-[10px] uppercase tracking-wider text-slate-400 opacity-70 group-hover:opacity-100 transition-all duration-300">Support</span>
                </a>

                <div class="relative -top-6 w-16 h-16 flex justify-center items-center">
                    <a class="nav-item center-btn absolute w-14 h-14 rounded-full bg-brand text-white shadow-glow center-btn-pulse flex items-center justify-center text-2xl z-10 hover:scale-110 transition-transform duration-300 active:scale-90" href="../PHP_User/userStore.php" data-href="Store.php" data-id="store">
                        <i class="fa-solid fa-store"></i>
                    </a>
                    <div class="absolute -bottom-6 w-20 h-10 bg-white dark:bg-[#0f172a] opacity-0 md:opacity-0 rounded-t-full"></div>
                </div>

                <a class="nav-item group flex-1 flex flex-col items-center justify-center h-full pb-2 relative" href="../PHP_User/userHistory.php" data-href="OrderHistory.php" data-id="history">
                    <div class="icon-container text-2xl text-slate-400 group-hover:text-brand/70 mb-1">
                       <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <span class="nav-label text-[10px] uppercase tracking-wider text-slate-400 opacity-70 group-hover:opacity-100 transition-all duration-300">History</span>
                </a>

                <a class="nav-item group flex-1 flex flex-col items-center justify-center h-full pb-2 relative" href="../PHP_User/userProfile.php" data-href="ProfileUser.php" data-id="me">
                    <div class="icon-container text-2xl text-slate-400 group-hover:text-brand/70 mb-1">
                        <i class="fa-solid fa-user-astronaut"></i>
                        <span class="absolute top-0 right-[30%] w-2.5 h-2.5 bg-brand border-2 border-white dark:border-[#0f172a] rounded-full hidden group-[.has-noti]:block"></span>
                    </div>
                    <span class="nav-label text-[10px] uppercase tracking-wider text-slate-400 opacity-70 group-hover:opacity-100 transition-all duration-300">Me</span>
                </a>
            </div>
        </nav>

    </div>

    <script>
        const layoutApp = {
            init() {
                this.loadTheme();
                this.highlightCurrentPage();
                this.attachNavHandlers();
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

            highlightCurrentPage() {
                const url = window.location.pathname;
                const navs = document.querySelectorAll('.nav-item');
                navs.forEach(n => n.classList.remove('active'));

                let matched = false;
                navs.forEach(n => {
                    // prefer href attribute (anchors), fallback to data-href
                    const hrefAttr = n.getAttribute('href') || n.dataset.href || '';
                    const basename = hrefAttr.split('/').pop();
                    if (basename && url.includes(basename)) {
                        n.classList.add('active');
                        matched = true;
                    }
                });

                if (!matched && navs.length) navs[0].classList.add('active');
            },

            // getNavPath removed: anchors now handle navigation directly

            attachNavHandlers() {
                // anchors handle navigation naturally; here we only toggle UI bits like notification dot
                try {
                    const notifs = JSON.parse(localStorage.getItem('pp_notifications') || '[]');
                    if (Array.isArray(notifs) && notifs.length > 0) {
                        document.querySelectorAll('.nav-item').forEach(n => {
                            if (n.dataset.id === 'me') n.classList.add('has-noti');
                        });
                    }
                } catch(e) {}
            },

            toggleStoreMode(mode) {
                const bg = document.getElementById('store-toggle-bg');
                const btnBuy = document.getElementById('btn-store-buy');
                const btnRent = document.getElementById('btn-store-rent');

                if (mode === 'buy') {
                    bg.style.left = '4px';
                    btnBuy.classList.add('text-brand');
                    btnBuy.classList.remove('text-gray-500');
                    btnRent.classList.remove('text-brand');
                    btnRent.classList.add('text-gray-500');
                } else {
                    bg.style.left = '50%';
                    btnRent.classList.add('text-brand');
                    btnRent.classList.remove('text-gray-500');
                    btnBuy.classList.remove('text-brand');
                    btnBuy.classList.add('text-gray-500');
                }
            }
        };

        document.addEventListener('DOMContentLoaded', () => layoutApp.init());
    </script>
</body>
</html>
