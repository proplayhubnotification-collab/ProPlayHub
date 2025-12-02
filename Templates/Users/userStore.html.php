<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="description" content="ProPlay Store - Gear, Cards & Rentals">
    
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#f8fafc">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#0f172a">

    <title>ProPlay Store</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">

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
                    brand: '#ff0055', 
                    dark: { bg: '#0f172a', surface: '#1e293b', border: '#334155' }
                },
                boxShadow: {
                    'glow': '0 0 15px rgba(255, 0, 85, 0.25)',
                    'float': '0 10px 30px -10px rgba(0,0,0,0.3)'
                }
            }
        }
      }
    </script>
    
    <style>
        body { -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Digital Card Gradients */
        .card-steam { background: linear-gradient(135deg, #1b2838 0%, #2a475e 100%); }
        .card-psn { background: linear-gradient(135deg, #00439c 0%, #0070d1 100%); }
        .card-xbox { background: linear-gradient(135deg, #107c10 0%, #3a9a3a 100%); }
        .card-riot { background: linear-gradient(135deg, #d13639 0%, #eb0029 100%); }

        .modal-panel { transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-dark-bg dark:text-slate-50 transition-colors duration-300 min-h-screen font-sans overflow-x-hidden pb-48">

    <div id="toast" class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] transition-all duration-300 transform -translate-y-24 opacity-0 px-5 py-3 rounded-full bg-slate-900 dark:bg-white text-white dark:text-black shadow-glow flex items-center gap-3 min-w-[200px] pointer-events-none">
        <i class="fas fa-check-circle text-brand"></i>
        <span id="toast-msg" class="text-sm font-bold">Action Successful</span>
    </div>

    <header class="sticky top-0 z-40 bg-slate-50/90 dark:bg-dark-bg/90 backdrop-blur-md border-b border-slate-200 dark:border-dark-border px-4 py-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-lg font-bold uppercase tracking-wider">Store</h1>
            <button onclick="openCart()" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-dark-surface border border-slate-200 dark:border-dark-border flex items-center justify-center relative">
                <i class="fas fa-shopping-cart text-slate-600 dark:text-slate-300"></i>
                <span id="cart-badge" class="absolute -top-1 -right-1 w-5 h-5 bg-brand text-white text-[10px] font-bold flex items-center justify-center rounded-full hidden border-2 border-white dark:border-dark-bg">0</span>
            </button>
        </div>
        
        <div class="relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" id="search-input" oninput="handleSearch()" placeholder="Search gear, games, cards..." class="w-full bg-white dark:bg-dark-surface pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-dark-border outline-none focus:border-brand transition-colors text-sm font-medium dark:text-white placeholder:text-slate-400">
        </div>
    </header>

    <div class="px-4 mt-4">
        <div class="w-full h-40 rounded-2xl bg-gradient-to-r from-purple-900 to-indigo-900 relative overflow-hidden flex items-center px-6 shadow-lg">
            <div class="absolute right-0 top-0 h-full w-1/2 bg-[url('https://api.dicebear.com/7.x/shapes/svg?seed=Gaming')] opacity-10"></div>
            <div class="relative z-10 max-w-[60%]">
                <span class="text-[10px] font-bold bg-brand text-white px-2 py-1 rounded mb-2 inline-block">HOT DEAL</span>
                <h2 class="text-2xl font-bold text-white leading-tight mb-1">Winter Sale</h2>
                <p class="text-indigo-200 text-xs mb-3">Up to 50% off on Razer Gear</p>
                <button class="bg-white text-indigo-900 text-xs font-bold px-4 py-2 rounded-lg hover:bg-indigo-50">Shop Now</button>
            </div>
            <i class="fas fa-headset text-8xl text-white opacity-10 absolute -right-4 -bottom-4 rotate-12"></i>
        </div>
    </div>

    <div class="sticky top-[135px] z-30 bg-slate-50/95 dark:bg-dark-bg/95 backdrop-blur py-2 pl-4 mt-4 border-b border-transparent dark:border-dark-border">
        <div class="flex gap-3 overflow-x-auto no-scrollbar pr-4" id="category-container">
            </div>
    </div>

    <div id="product-grid" class="p-4 pb-32 grid grid-cols-2 gap-4">
        </div>

    <div id="product-modal" class="fixed inset-0 z-50 bg-slate-900/70 backdrop-blur-sm hidden flex items-end justify-center touch-none" onclick="closeModal(event, 'product-modal')">
        <div class="w-full bg-white dark:bg-[#0b1121] rounded-t-[2rem] max-h-[90vh] overflow-y-auto no-scrollbar shadow-2xl modal-panel translate-y-full relative">
            
            <button onclick="closeModal(null, 'product-modal', true)" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-black/10 dark:bg-white/10 text-slate-500 dark:text-white flex items-center justify-center backdrop-blur">
                <i class="fas fa-times"></i>
            </button>

            <div id="modal-image-container" class="w-full aspect-[4/3] bg-slate-100 dark:bg-slate-800 flex items-center justify-center relative">
                </div>

            <div class="p-6">
                <div class="flex justify-between items-start mb-2">
                    <span id="modal-category" class="text-[10px] font-bold uppercase tracking-wider text-brand bg-brand/10 px-2 py-1 rounded">--</span>
                    <div class="flex items-center gap-1 text-yellow-500 text-sm">
                        <i class="fas fa-star"></i>
                        <span id="modal-rating" class="font-bold text-slate-900 dark:text-white">4.8</span>
                    </div>
                </div>
                
                <h2 id="modal-title" class="text-2xl font-bold dark:text-white mb-2 leading-tight">--</h2>
                <h3 id="modal-price" class="text-2xl font-mono font-bold text-slate-900 dark:text-white mb-4">--</h3>
                
                <p id="modal-desc" class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed mb-6">
                    Description loading...
                </p>

                <div id="rental-options" class="hidden mb-6 p-4 bg-slate-50 dark:bg-dark-surface rounded-xl border border-slate-200 dark:border-dark-border">
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-2">Rent Duration</label>
                    <div class="flex gap-2">
                        <button onclick="setRentalDays(1)" class="rental-btn flex-1 py-2 rounded-lg border border-slate-300 dark:border-slate-600 text-sm font-bold dark:text-white hover:border-brand hover:text-brand transition-colors selected">1 Day</button>
                        <button onclick="setRentalDays(3)" class="rental-btn flex-1 py-2 rounded-lg border border-slate-300 dark:border-slate-600 text-sm font-bold dark:text-white hover:border-brand hover:text-brand transition-colors">3 Days</button>
                        <button onclick="setRentalDays(7)" class="rental-btn flex-1 py-2 rounded-lg border border-slate-300 dark:border-slate-600 text-sm font-bold dark:text-white hover:border-brand hover:text-brand transition-colors">7 Days</button>
                    </div>
                </div>

                <div class="mb-6 p-4 bg-slate-50 dark:bg-dark-surface rounded-xl border border-slate-200 dark:border-dark-border">
                    <label class="text-xs font-bold text-slate-500 uppercase block mb-3">Quantity</label>
                    <div class="flex items-center gap-4">
                        <button onclick="decreaseQty()" class="w-10 h-10 rounded-lg border-2 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white font-bold text-lg hover:border-brand hover:text-brand transition-colors active:scale-90">
                            <i class="fas fa-minus"></i>
                        </button>
                        <div class="flex-1 text-center">
                            <input type="number" id="qty-input" value="1" min="1" max="99" class="w-full text-center text-2xl font-bold dark:bg-dark-surface dark:text-white bg-white border-2 border-slate-200 dark:border-slate-600 rounded-lg outline-none focus:border-brand transition-colors py-2" readonly>
                        </div>
                        <button onclick="increaseQty()" class="w-10 h-10 rounded-lg border-2 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white font-bold text-lg hover:border-brand hover:text-brand transition-colors active:scale-90">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <button id="modal-action-btn" class="w-full bg-slate-900 dark:bg-white text-white dark:text-black font-bold py-4 rounded-xl shadow-lg active:scale-95 transition-transform flex items-center justify-center gap-2 mb-24">
                    <span>Add to Cart</span>
                </button>
            </div>
        </div>
    </div>

    <div id="cart-modal" class="fixed inset-0 z-50 bg-slate-900/70 backdrop-blur-sm hidden flex items-end justify-center touch-none" onclick="closeModal(event, 'cart-modal')">
        <div class="w-full bg-white dark:bg-[#0b1121] rounded-t-[2rem] h-[80vh] flex flex-col shadow-2xl modal-panel translate-y-full">
            <div class="w-full pt-4 pb-2 flex justify-center shrink-0" onclick="closeModal(null, 'cart-modal', true)"><div class="w-12 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full"></div></div>
            <div class="px-6 pb-4 border-b border-slate-100 dark:border-dark-border flex justify-between items-center">
                <h2 class="text-xl font-bold dark:text-white">Your Cart</h2>
                <button onclick="clearCart()" class="text-xs text-red-500 font-bold uppercase hover:underline">Clear</button>
            </div>
            <div id="cart-items" class="p-6 overflow-y-auto no-scrollbar flex-1 space-y-4">
                </div>
            <div class="p-6 pb-24 border-t border-slate-100 dark:border-dark-border bg-white dark:bg-[#0b1121]">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-slate-500 font-bold">Total</span>
                    <span id="cart-total" class="text-2xl font-mono font-bold dark:text-white">$0.00</span>
                </div>
                <button onclick="checkout()" class="w-full bg-brand text-white font-bold py-4 rounded-xl shadow-glow active:scale-95 transition-transform">CHECKOUT NOW</button>
            </div>
        </div>
    </div>

    <script>
        // --- DATA & CONFIG ---
        const themeQuery = window.matchMedia('(prefers-color-scheme: dark)');
        function handleThemeChange(e) { if(e.matches) document.documentElement.classList.add('dark'); else document.documentElement.classList.remove('dark'); }
        themeQuery.addListener(handleThemeChange); handleThemeChange(themeQuery);

        // DATABASE
        let products = [];

        function getDefaultProducts() {
            return [
                // --- GAMING GEAR ---
                { id: '1', name: 'DualSense Edge', category: 'Controllers', type: 'gear', price: 199.00, rating: 4.9, img: 'https://product.hstatic.net/200000637319/product/1_3cc4246584c04e7db0c45706954295e9_master.jpg', desc: 'The high-performance PS5 controller with customizable controls and swappable stick caps. Pro-level precision and haptic feedback.' },
                { id: '2', name: 'Logitech G Pro X2', category: 'Headsets', type: 'gear', price: 129.00, rating: 4.7, img: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', desc: 'Professional gaming headset with Blue VO!CE microphone and DTS Headphone:X 2.0 surround sound. Wireless & wired modes.' },
                { id: '3', name: 'Razer Huntsman Mini', category: 'Keyboards', type: 'gear', price: 119.00, rating: 4.6, img: 'https://npcshop.vn/media/product/2137-55390_ba_n_phi_m_co_razer_huntsman_mini_mercury_rz03_03390300_r3m1_0000_1.jpg', desc: '60% compact gaming keyboard with Razer Optical Switches. RGB lighting and programmable keys.' },
                { id: '4', name: 'Logitech G Pro X Superlight', category: 'Mice', type: 'gear', price: 149.00, rating: 4.8, img: 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=500&h=500&fit=crop', desc: 'Ultra-lightweight wireless gaming mouse. 16,000 DPI sensor, 70-hour battery life.' },
                { id: '5', name: 'ASUS ROG Monitor 27"', category: 'Monitors', type: 'gear', price: 349.00, rating: 4.9, img: 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=500&h=500&fit=crop', desc: '1440p IPS panel, 240Hz refresh rate, 1ms response time. HDR400 certified gaming monitor.' },
                { id: '6', name: 'SteelSeries Mousepad QCK', category: 'Mousepads', type: 'gear', price: 49.99, rating: 4.5, img: 'https://product.hstatic.net/200000637319/product/h2_46ac467003e8498db76291ef4dcfcb1b_master.jpg', desc: 'Large premium cloth mousepad. Non-slip rubber base, 450x400mm size for ample mouse space.' },

                // --- DIGITAL CARDS ---
                { id: '7', name: 'Steam Wallet $20', category: 'Steam', type: 'card', subType: 'steam', price: 20.00, rating: 5.0, img: 'https://cdn.cloudflare.steamstatic.com/steam/apps/5/header.jpg?t=1666823513', desc: 'Instant digital code for Steam. Valid for all games and DLC on the Steam platform.' },
                { id: '8', name: 'Steam Wallet $50', category: 'Steam', type: 'card', subType: 'steam', price: 50.00, rating: 5.0, img: 'https://cdn.cloudflare.steamstatic.com/steam/apps/5/header.jpg?t=1666823513', desc: 'Instant digital code for Steam. Purchase thousands of games, DLC, and in-game items.' },
                { id: '9', name: 'PSN Card $25', category: 'PlayStation', type: 'card', subType: 'psn', price: 25.00, rating: 5.0, img: 'https://gmedia.playstation.com/is/image/SIEPDC/psn-card-image-01-en-14sep21?$native$', desc: 'PlayStation Network Store credit. Buy games, DLC, and subscription services.' },
                { id: '10', name: 'Xbox Game Pass', category: 'Xbox', type: 'card', subType: 'xbox', price: 17.99, rating: 4.9, img: 'https://compass-ssl.xbox.com/assets/d4/6e/d46edbf5-c0e8-4e8a-9a77-48dc2e2be999.jpg?n=Game-Pass_Content-Placement-0_Hero-1084_740x417.jpg', desc: 'Monthly subscription for Xbox Game Pass. Access 100+ games instantly.' },
                { id: '11', name: 'Riot Points 2500', category: 'Riot Games', type: 'card', subType: 'riot', price: 25.00, rating: 4.9, img: 'https://www.riotgames.com/darkroom/original/8c19b2c3fa7b12e6ba560cdbc7e48c91:21f3b3574e0d8b34d6df04867a2e23ec/riot-games-logo-v.png', desc: 'Points for League of Legends or Valorant. Purchase skins, champions, and cosmetics.' },
                { id: '12', name: 'Nintendo eShop $35', category: 'Nintendo', type: 'card', subType: 'nintendo', price: 35.00, rating: 4.8, img: 'https://assets.nintendo.com/image/upload/f_auto/q_auto/dpr_2.0/c_scale,w_600/ncom/en_US/merchandising/gift-cards/eshop/nintendo-eshop-cards', desc: 'Nintendo Switch eShop credit. Buy games and DLC for Nintendo Switch.' },

                // --- CONSOLES & VR RENTALS ---
                { id: '13', name: 'PlayStation 5 Pro', type: 'rental', category: 'Console Rental', price: 25.00, rating: 4.8, img: 'https://m.media-amazon.com/images/I/51gq-sy5CiL.jpg', desc: 'Rent the latest PS5 Pro console. Includes 1 DualSense controller and 3 AAA games.' },
                { id: '14', name: 'Meta Quest 3 Pro', type: 'rental', category: 'VR Rental', price: 35.00, rating: 4.9, img: 'https://d28jzcg6y4v9j1.cloudfront.net/media/core/products/2023/10/2/meta-quest-3-05-thinkpro-epiczone.jpg', desc: 'Experience advanced mixed reality. Top-tier VR gaming and immersive experiences.' },
                { id: '15', name: 'Nintendo Switch OLED', type: 'rental', category: 'Console Rental', price: 15.00, rating: 4.7, img: 'https://www.droidshop.vn/wp-content/uploads/2022/01/May-choi-game-Nintendo-Switch-OLED-model-with-White-Joy%E2%80%91Con.jpg', desc: 'Portable gaming console with vibrant OLED display. Perfect for gaming on the go.' },
                { id: '16', name: 'Xbox Series X', type: 'rental', category: 'Console Rental', price: 20.00, rating: 4.8, img: 'https://www.droidshop.vn/wp-content/uploads/2023/05/May-choi-game-Xbox-Series-X.jpg', desc: 'Next-gen gaming power. 4K gaming at 120fps with Game Pass included.' },
                { id: '17', name: 'HTC Vive XR Elite', type: 'rental', category: 'VR Rental', price: 40.00, rating: 4.6, img: 'https://www.droidshop.vn/wp-content/uploads/2023/02/Kinh-thuc-te-ao-HTC-Vive-XR-Elite.jpg', desc: 'Premium standalone VR headset. High-resolution display and advanced controllers.' },
                { id: '18', name: 'Alienware Gaming Laptop', type: 'rental', category: 'Gaming PC', price: 50.00, rating: 4.9, img: 'https://laptopgiasi.vn/wp-content/uploads/2023/03/Mua-Ban-Laptop-Alienware-17-R3-Moi-Cu-Gia-Re-Nhu-Ban-Si-Core-i7-6820-HK-The-he-6-Gaming-Doc-Dao-Pin-sieu-lau-2.jpg', desc: 'High-performance gaming laptop. RTX 4090, Intel i9, 32GB RAM. Perfect for AAA titles.' },
            ];
        }

        function loadProducts() {
            const stored = localStorage.getItem('proplay_store_products');
            if (stored) {
                try {
                    products = JSON.parse(stored);
                    if (!products || products.length === 0) {
                        products = getDefaultProducts();
                        localStorage.setItem('proplay_store_products', JSON.stringify(products));
                    }
                } catch (e) {
                    products = getDefaultProducts();
                    localStorage.setItem('proplay_store_products', JSON.stringify(products));
                }
            } else {
                products = getDefaultProducts();
                localStorage.setItem('proplay_store_products', JSON.stringify(products));
            }
        }

        // Listen for storage changes from CSR Admin
        window.addEventListener('storage', (e) => {
            if (e.key === 'proplay_store_products') {
                try {
                    products = JSON.parse(e.newValue);
                    renderGrid();
                } catch (err) {}
            }
        });

        let state = {
            category: 'all',
            cart: [],
            selectedProduct: null,
            rentalDays: 1,
            quantity: 1,
            searchQuery: ''
        };

        // --- RENDER LOGIC ---

        function init() {
            loadProducts();
            renderTabs();
            renderGrid();
        }

        function handleSearch() {
            state.searchQuery = document.getElementById('search-input').value.toLowerCase().trim();
            renderGrid();
        }

        function renderTabs() {
            const cats = ['all', 'gear', 'card', 'rental'];
            const container = document.getElementById('category-container');
            container.innerHTML = cats.map(c => `
                <button onclick="setCategory('${c}')" class="px-5 py-2 rounded-full text-sm font-bold border whitespace-nowrap transition-all ${state.category === c ? 'bg-slate-900 dark:bg-white text-white dark:text-black border-transparent' : 'bg-white dark:bg-dark-surface text-slate-500 border-slate-200 dark:border-dark-border'}">
                    ${c === 'card' ? 'Digital Cards' : c.charAt(0).toUpperCase() + c.slice(1)}
                </button>
            `).join('');
        }

        function renderGrid() {
            const grid = document.getElementById('product-grid');
            let filtered = state.category === 'all' ? products : products.filter(p => p.type === state.category);
            
            if (state.searchQuery) {
                filtered = filtered.filter(p => 
                    p.name.toLowerCase().includes(state.searchQuery) || 
                    p.category.toLowerCase().includes(state.searchQuery)
                );
            }

            if (filtered.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-2 flex flex-col items-center justify-center py-12 text-slate-400">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-3 text-2xl">
                            <i class="fas fa-search"></i>
                        </div>
                        <p class="font-medium">No results found</p>
                        <p class="text-xs mt-1">Try searching for something else</p>
                    </div>
                `;
                return;
            }

            grid.innerHTML = filtered.map((p, idx) => {
                const delay = idx * 50;
                // Template logic based on type
                if (p.type === 'card') {
                    return `
                    <div onclick="openProduct(${p.id})" class="fade-in-up cursor-pointer group rounded-xl overflow-hidden shadow-sm relative aspect-[1.6/1] card-${p.subType} p-4 flex flex-col justify-between text-white border border-white/10" style="animation-delay: ${delay}ms">
                         <div class="flex justify-between items-start">
                            <i class="${getIcon(p.subType)} text-2xl opacity-80"></i>
                            <span class="font-mono font-bold text-lg">$${p.price}</span>
                         </div>
                         <div>
                            <p class="font-bold text-sm opacity-90">${p.name}</p>
                            <p class="text-[10px] opacity-70">Digital Code</p>
                         </div>
                    </div>`;
                } else {
                    return `
                    <div onclick="openProduct(${p.id})" class="fade-in-up bg-white dark:bg-dark-surface p-3 rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm flex flex-col cursor-pointer group active:scale-[0.98] transition-transform" style="animation-delay: ${delay}ms">
                        <div class="aspect-square bg-slate-50 dark:bg-black/20 rounded-xl mb-3 overflow-hidden flex items-center justify-center text-4xl text-slate-300 dark:text-slate-600 group-hover:scale-105 transition-transform">
                            <img src="${p.img}" alt="${p.name}" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML='<i class=&quot;fas fa-image text-2xl&quot;></i>'">
                        </div>
                        <div class="mb-1">
                            <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">${p.category}</span>
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white leading-tight truncate">${p.name}</h3>
                        </div>
                        <div class="mt-auto pt-2 flex justify-between items-center">
                            <span class="font-mono font-bold text-slate-900 dark:text-white">$${p.price}${p.type === 'rental' ? '<span class="text-xs font-sans font-normal text-slate-400">/day</span>' : ''}</span>
                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white flex items-center justify-center group-hover:bg-brand group-hover:text-white transition-colors">
                                <i class="fas fa-plus text-xs"></i>
                            </div>
                        </div>
                    </div>`;
                }
            }).join('');
        }

        function getIcon(subType) {
            if(subType === 'steam') return 'fab fa-steam';
            if(subType === 'psn') return 'fab fa-playstation';
            if(subType === 'xbox') return 'fab fa-xbox';
            if(subType === 'riot') return 'fas fa-gamepad'; // Generic for riot
            return 'fas fa-gift';
        }

        function setCategory(cat) {
            state.category = cat;
            renderTabs();
            renderGrid();
        }

        // --- MODAL & CART LOGIC ---

        function openProduct(id) {
            const p = products.find(x => x.id === id);
            state.selectedProduct = p;
            state.rentalDays = 1; // Reset rental days
            state.quantity = 1; // Reset quantity

            // UI Population
            document.getElementById('modal-title').innerText = p.name;
            document.getElementById('modal-category').innerText = p.type;
            document.getElementById('modal-rating').innerText = p.rating;
            document.getElementById('modal-desc').innerText = p.desc;
            
            // Image Area
            const imgContainer = document.getElementById('modal-image-container');
            if(p.type === 'card') {
                imgContainer.innerHTML = `<div class="w-3/4 aspect-[1.6/1] rounded-xl card-${p.subType} flex items-center justify-center shadow-lg"><i class="${getIcon(p.subType)} text-5xl text-white"></i></div>`;
            } else {
                imgContainer.innerHTML = `<img src="${p.img}" class="w-full h-full object-cover">`;
            }

            // Rental Logic
            const rentalOptions = document.getElementById('rental-options');
            const priceEl = document.getElementById('modal-price');
            const btn = document.getElementById('modal-action-btn');

            if(p.type === 'rental') {
                rentalOptions.classList.remove('hidden');
                priceEl.innerHTML = `$${p.price} <span class="text-sm font-sans font-medium text-slate-400">/ day</span>`;
                btn.innerHTML = `<span>Rent for $${p.price.toFixed(2)}</span>`;
                updateRentalBtnUI();
            } else {
                rentalOptions.classList.add('hidden');
                priceEl.innerText = `$${p.price.toFixed(2)}`;
                btn.innerHTML = `<span>Add to Cart</span> <i class="fas fa-shopping-bag"></i>`;
            }

            // Open Modal
            const modal = document.getElementById('product-modal');
            const panel = modal.querySelector('.modal-panel');
            modal.classList.remove('hidden');
            requestAnimationFrame(() => { panel.classList.remove('translate-y-full'); });
        }

        function increaseQty() {
            const input = document.getElementById('qty-input');
            const current = parseInt(input.value);
            if(current < 99) input.value = current + 1;
            state.quantity = parseInt(input.value);
        }

        function decreaseQty() {
            const input = document.getElementById('qty-input');
            const current = parseInt(input.value);
            if(current > 1) input.value = current - 1;
            state.quantity = parseInt(input.value);
        }

        function setRentalDays(days) {
            state.rentalDays = days;
            updateRentalBtnUI();
        }

        function updateRentalBtnUI() {
            const p = state.selectedProduct;
            const total = p.price * state.rentalDays;
            
            // Update button text
            document.getElementById('modal-action-btn').innerHTML = `<span>Rent for $${total.toFixed(2)}</span> <span class="text-xs opacity-70">(${state.rentalDays} Days)</span>`;
            
            // Highlight selected button
            const btns = document.querySelectorAll('.rental-btn');
            btns.forEach(b => {
                if(b.innerText.includes(state.rentalDays)) {
                    b.classList.add('border-brand', 'text-brand');
                    b.classList.remove('border-slate-300', 'dark:border-slate-600', 'dark:text-white');
                } else {
                    b.classList.remove('border-brand', 'text-brand');
                    b.classList.add('border-slate-300', 'dark:border-slate-600', 'dark:text-white');
                }
            });
        }

        document.getElementById('modal-action-btn').onclick = function() {
            const p = state.selectedProduct;
            const qty = state.quantity;
            let finalPrice = p.price;
            let name = p.name;
            
            if(p.type === 'rental') {
                finalPrice = p.price * state.rentalDays;
                name = `${p.name} (${state.rentalDays} Days)`;
            }

            // Add multiple items based on quantity
            for(let i = 0; i < qty; i++) {
                addToCart({
                    id: p.id,
                    name: name,
                    price: finalPrice,
                    type: p.type,
                    subType: p.subType,
                    img: p.img // Pass image to cart
                });
            }
            closeModal(null, 'product-modal', true);
        };

        function addToCart(item) {
            state.cart.push(item);
            updateCartUI();
            showToast('Added to Cart');
        }

        function updateCartUI() {
            const badge = document.getElementById('cart-badge');
            if(state.cart.length > 0) {
                badge.innerText = state.cart.length;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }

        function openCart() {
            const list = document.getElementById('cart-items');
            const totalEl = document.getElementById('cart-total');
            
            if(state.cart.length === 0) {
                list.innerHTML = `<div class="flex flex-col items-center justify-center h-40 text-slate-400"><i class="fas fa-shopping-basket text-3xl mb-2"></i><p>Cart is empty</p></div>`;
                totalEl.innerText = '$0.00';
            } else {
                let total = 0;
                list.innerHTML = state.cart.map((item, i) => {
                    total += item.price;
                    return `
                    <div class="flex justify-between items-center bg-slate-50 dark:bg-dark-surface p-3 rounded-xl border border-slate-100 dark:border-dark-border">
                        <div>
                            <p class="font-bold text-sm dark:text-white">${item.name}</p>
                            <p class="text-[10px] uppercase font-bold text-slate-400">${item.type}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-mono font-bold dark:text-white">$${item.price.toFixed(2)}</p>
                            <button onclick="removeItem(${i})" class="text-xs text-red-500 font-bold hover:underline">Remove</button>
                        </div>
                    </div>`;
                }).join('');
                totalEl.innerText = `$${total.toFixed(2)}`;
            }

            const modal = document.getElementById('cart-modal');
            const panel = modal.querySelector('.modal-panel');
            modal.classList.remove('hidden');
            requestAnimationFrame(() => { panel.classList.remove('translate-y-full'); });
        }

        function removeItem(index) {
            state.cart.splice(index, 1);
            openCart(); // Re-render
            updateCartUI();
        }

        function clearCart() {
            state.cart = [];
            openCart();
            updateCartUI();
        }

        function checkout() {
            if(state.cart.length === 0) return;
            
            // Save cart to sessionStorage
            sessionStorage.setItem('cart', JSON.stringify(state.cart));
            
            const btn = document.querySelector('#cart-modal button.w-full'); // Checkout btn
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Processing...';
            
            // Redirect to checkout page
            setTimeout(() => {
                window.location.href = '/ProPlayHub/PHP_User/userCheckout.php';
            }, 500);
        }

        function closeModal(e, modalId, force) {
            if (force || e.target.id === modalId) {
                const modal = document.getElementById(modalId);
                const panel = modal.querySelector('.modal-panel');
                panel.classList.add('translate-y-full');
                setTimeout(() => { modal.classList.add('hidden'); }, 300);
            }
        }

        function showToast(msg) {
            const t = document.getElementById('toast');
            document.getElementById('toast-msg').innerText = msg;
            t.classList.remove('opacity-0', '-translate-y-24');
            t.classList.add('translate-y-0', 'opacity-100');
            setTimeout(() => {
                t.classList.remove('translate-y-0', 'opacity-100');
                t.classList.add('opacity-0', '-translate-y-24');
            }, 2500);
        }

        // Init
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>