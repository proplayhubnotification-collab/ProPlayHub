<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ProPlay - Store Management</title>
    
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
                boxShadow: {
                    'glow': '0 0 20px rgba(236, 0, 63, 0.4)',
                    'sheet': '0 -5px 25px rgba(0,0,0,0.3)',
                    'float': '0 10px 25px -5px rgba(236, 0, 63, 0.5)'
                }
            }
        }
      }
    </script>
    <style>
        body { -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .safe-pb { padding-bottom: env(safe-area-inset-bottom); }
        
        /* Sheet Animation */
        .sheet-enter { transform: translateY(100%); }
        .sheet-active { transform: translateY(0); }
        .transition-sheet { transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    </style>
</head>
<body class="bg-light-bg text-light-text dark:bg-dark-bg dark:text-dark-text transition-colors duration-300 min-h-screen font-sans overflow-hidden safe-pb">

    <div id="toast-container" class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] w-full max-w-xs px-4 pointer-events-none flex flex-col gap-2"></div>

    <header class="px-6 pt-8 pb-4 sticky top-0 z-40 bg-light-bg/95 dark:bg-dark-bg/95 backdrop-blur-md border-b border-slate-100 dark:border-white/5">
        <div class="flex items-center gap-4 mb-4">
            <h1 class="text-xl font-bold flex-1">Store Inventory</h1>
            <div class="flex items-center gap-3">
                <button onclick="resetData()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-white/5 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors flex items-center justify-center" title="Reset Data">
                    <i class="fas fa-sync-alt text-xs"></i>
                </button>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] text-slate-500 uppercase font-bold">Total Items</span>
                    <span class="text-base font-mono font-bold text-brand" id="total-products">0</span>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <div class="relative group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand transition-colors"></i>
                <input type="text" id="search-input" placeholder="Search product name, SKU..." class="w-full pl-10 pr-4 py-3 bg-white dark:bg-dark-surface border border-slate-200 dark:border-dark-border rounded-xl text-sm font-medium focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand dark:text-white transition-all shadow-sm">
            </div>
            
            <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1" id="filter-tabs">
                <button onclick="setFilter('all', this)" class="filter-btn active px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-brand text-white border-brand transition-all shadow-glow">All</button>
                <button onclick="setFilter('gear', this)" class="filter-btn px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-white dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 transition-all">Gear</button>
                <button onclick="setFilter('card', this)" class="filter-btn px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-white dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 transition-all">Digital Cards</button>
                <button onclick="setFilter('rental', this)" class="filter-btn px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-white dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 transition-all">Rental</button>
            </div>
        </div>
    </header>

    <main class="h-[calc(100vh-185px)] overflow-y-auto px-6 pb-32 pt-2" id="product-list-container">
        </main>

    <button onclick="openSheet(null)" class="fixed bottom-24 right-6 w-14 h-14 bg-brand text-white rounded-full shadow-float flex items-center justify-center text-xl z-40 active:scale-90 transition-transform hover:bg-brandDark">
        <i class="fas fa-plus"></i>
    </button>

    <div id="action-sheet-overlay" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden transition-opacity opacity-0" onclick="closeSheet()"></div>
    
    <div id="action-sheet" class="fixed bottom-0 left-0 w-full z-50 bg-white dark:bg-[#0b1121] rounded-t-[2rem] shadow-sheet sheet-enter transition-sheet max-h-[90vh] flex flex-col border-t border-white/20 dark:border-white/5">
        <div class="w-full pt-4 pb-2 flex justify-center shrink-0 cursor-pointer" onclick="closeSheet()">
            <div class="w-12 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full"></div>
        </div>

        <div class="p-6 overflow-y-auto pb-32 safe-pb">
            <div class="flex justify-between items-center mb-6">
                <h2 id="sheet-title" class="text-xl font-bold dark:text-white">Add Product</h2>
                <button id="delete-btn" class="hidden text-red-500 text-xs font-bold px-3 py-1.5 bg-red-50 dark:bg-red-900/10 rounded-lg hover:bg-red-100">
                    <i class="fas fa-trash-alt mr-1"></i> Delete
                </button>
            </div>

            <form id="product-form" onsubmit="handleFormSubmit(event)" class="space-y-4">
                <input type="hidden" id="p-id">
                
                <div class="flex flex-col items-center justify-center">
                    <div class="relative group cursor-pointer" onclick="document.getElementById('file-upload').click()">
                        <div id="image-preview" class="w-28 h-28 rounded-2xl bg-slate-100 dark:bg-white/5 border-2 border-dashed border-slate-300 dark:border-white/20 flex items-center justify-center overflow-hidden">
                            <img id="current-img" src="" class="w-full h-full object-cover hidden">
                            <div id="card-preview" class="w-full h-full hidden"></div>
                            <div id="upload-placeholder" class="text-center text-slate-400">
                                <i class="fas fa-camera text-xl mb-1"></i>
                                <p class="text-[10px]">Tap to Upload</p>
                            </div>
                        </div>
                        <div class="absolute bottom-0 right-0 w-8 h-8 bg-brand text-white rounded-full flex items-center justify-center shadow-lg transform translate-x-2 translate-y-2">
                            <i class="fas fa-pen text-xs"></i>
                        </div>
                    </div>
                    <input type="file" id="file-upload" class="hidden" accept="image/*" onchange="previewImage(this)">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 block mb-1">Product Name</label>
                    <input type="text" id="p-name" required placeholder="e.g. PS5 Console" class="w-full p-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl dark:text-white focus:border-brand focus:outline-none transition-colors">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 block mb-1">Description</label>
                    <textarea id="p-desc" rows="2" placeholder="Product description..." class="w-full p-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl dark:text-white focus:border-brand focus:outline-none transition-colors resize-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-1">Type</label>
                        <select id="p-category" class="w-full p-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl dark:text-white focus:border-brand focus:outline-none transition-colors appearance-none">
                            <option value="gear">Gear</option>
                            <option value="card">Digital Card</option>
                            <option value="rental">Rental</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-1">Sub-Category</label>
                        <select id="p-style" class="w-full p-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl dark:text-white focus:border-brand focus:outline-none transition-colors appearance-none">
                            <option value="Controllers">Controllers</option>
                            <option value="Headsets">Headsets</option>
                            <option value="Keyboards">Keyboards</option>
                            <option value="Mice">Mice</option>
                            <option value="Monitors">Monitors</option>
                            <option value="Mousepads">Mousepads</option>
                            <option value="Steam">Steam</option>
                            <option value="PlayStation">PlayStation</option>
                            <option value="Xbox">Xbox</option>
                            <option value="Riot Games">Riot Games</option>
                            <option value="Nintendo">Nintendo</option>
                            <option value="Console Rental">Console Rental</option>
                            <option value="VR Rental">VR Rental</option>
                            <option value="Gaming PC">Gaming PC</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-1">Price ($)</label>
                        <input type="number" id="p-price" step="0.01" required placeholder="0.00" class="w-full p-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl dark:text-white focus:border-brand focus:outline-none transition-colors font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-1">Stock</label>
                        <input type="number" id="p-stock" required placeholder="0" class="w-full p-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl dark:text-white focus:border-brand focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 block mb-1">SKU</label>
                        <input type="text" id="p-sku" placeholder="AUTO-GEN" readonly class="w-full p-3 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-slate-500 font-mono text-xs">
                    </div>
                </div>

                <button type="submit" id="submit-btn" class="w-full py-4 rounded-xl bg-brand text-white font-bold text-sm shadow-glow mt-4 active:scale-[0.98] transition-transform">
                    Add Product
                </button>
            </form>
        </div>
    </div>

    <script>
        // --- SHARED PRODUCT DATA ---
        let products = [];

        function getDefaultProducts() {
            return [
                // --- GAMING GEAR ---
                { id: '1', sku: 'CTRL-DS-EDGE', name: 'DualSense Edge', category: 'Controllers', style: 'Controllers', price: 199.00, stock: 8, image: 'https://product.hstatic.net/200000637319/product/1_3cc4246584c04e7db0c45706954295e9_master.jpg', img: 'https://product.hstatic.net/200000637319/product/1_3cc4246584c04e7db0c45706954295e9_master.jpg', type: 'gear', rating: 4.9, desc: 'The high-performance PS5 controller with customizable controls and swappable stick caps. Pro-level precision and haptic feedback.' },
                { id: '2', sku: 'HEAD-G-PRO-X2', name: 'Logitech G Pro X2', category: 'Headsets', style: 'Headsets', price: 129.00, stock: 15, image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', img: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop', type: 'gear', rating: 4.7, desc: 'Professional gaming headset with Blue VO!CE microphone and DTS Headphone:X 2.0 surround sound. Wireless & wired modes.' },
                { id: '3', sku: 'KB-RAZER-MINI', name: 'Razer Huntsman Mini', category: 'Keyboards', style: 'Keyboards', price: 119.00, stock: 22, image: 'https://npcshop.vn/media/product/2137-55390_ba_n_phi_m_co_razer_huntsman_mini_mercury_rz03_03390300_r3m1_0000_1.jpg', img: 'https://npcshop.vn/media/product/2137-55390_ba_n_phi_m_co_razer_huntsman_mini_mercury_rz03_03390300_r3m1_0000_1.jpg', type: 'gear', rating: 4.6, desc: '60% compact gaming keyboard with Razer Optical Switches. RGB lighting and programmable keys.' },
                { id: '4', sku: 'MOUSE-G-SUPERLIGHT', name: 'Logitech G Pro X Superlight', category: 'Mice', style: 'Mice', price: 149.00, stock: 12, image: 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=500&h=500&fit=crop', img: 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=500&h=500&fit=crop', type: 'gear', rating: 4.8, desc: 'Ultra-lightweight wireless gaming mouse. 16,000 DPI sensor, 70-hour battery life.' },
                { id: '5', sku: 'MON-ROG-27', name: 'ASUS ROG Monitor 27"', category: 'Monitors', style: 'Monitors', price: 349.00, stock: 5, image: 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=500&h=500&fit=crop', img: 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=500&h=500&fit=crop', type: 'gear', rating: 4.9, desc: '1440p IPS panel, 240Hz refresh rate, 1ms response time. HDR400 certified gaming monitor.' },
                { id: '6', sku: 'PAD-STEEL-QCK', name: 'SteelSeries Mousepad QCK', category: 'Mousepads', style: 'Mousepads', price: 49.99, stock: 35, image: 'https://product.hstatic.net/200000637319/product/h2_46ac467003e8498db76291ef4dcfcb1b_master.jpg', img: 'https://product.hstatic.net/200000637319/product/h2_46ac467003e8498db76291ef4dcfcb1b_master.jpg', type: 'gear', rating: 4.5, desc: 'Large premium cloth mousepad. Non-slip rubber base, 450x400mm size for ample mouse space.' },
                
                // --- DIGITAL CARDS ---
                { id: '7', sku: 'CARD-STEAM-20', name: 'Steam Wallet $20', category: 'Steam', style: 'Steam', price: 20.00, stock: 100, icon: 'fab fa-steam', gradient: 'linear-gradient(135deg, #1b2838 0%, #2a475e 100%)', type: 'card', subType: 'steam', rating: 5.0, desc: 'Instant digital code for Steam. Valid for all games and DLC on the Steam platform.' },
                { id: '8', sku: 'CARD-STEAM-50', name: 'Steam Wallet $50', category: 'Steam', style: 'Steam', price: 50.00, stock: 100, icon: 'fab fa-steam', gradient: 'linear-gradient(135deg, #1b2838 0%, #2a475e 100%)', type: 'card', subType: 'steam', rating: 5.0, desc: 'Instant digital code for Steam. Purchase thousands of games, DLC, and in-game items.' },
                { id: '9', sku: 'CARD-PSN-25', name: 'PSN Card $25', category: 'PlayStation', style: 'PlayStation', price: 25.00, stock: 100, icon: 'fab fa-playstation', gradient: 'linear-gradient(135deg, #00439c 0%, #0070d1 100%)', type: 'card', subType: 'psn', rating: 5.0, desc: 'PlayStation Network Store credit. Buy games, DLC, and subscription services.' },
                { id: '10', sku: 'CARD-XBOX-GP', name: 'Xbox Game Pass', category: 'Xbox', style: 'Xbox', price: 17.99, stock: 100, icon: 'fab fa-xbox', gradient: 'linear-gradient(135deg, #107c10 0%, #3a9a3a 100%)', type: 'card', subType: 'xbox', rating: 4.9, desc: 'Monthly subscription for Xbox Game Pass. Access 100+ games instantly.' },
                { id: '11', sku: 'CARD-RIOT-2500', name: 'Riot Points 2500', category: 'Riot Games', style: 'Riot Games', price: 25.00, stock: 100, icon: 'fas fa-gamepad', gradient: 'linear-gradient(135deg, #d13639 0%, #eb0029 100%)', type: 'card', subType: 'riot', rating: 4.9, desc: 'Points for League of Legends or Valorant. Purchase skins, champions, and cosmetics.' },
                { id: '12', sku: 'CARD-NINTENDO-35', name: 'Nintendo eShop $35', category: 'Nintendo', style: 'Nintendo', price: 35.00, stock: 100, icon: 'fas fa-gamepad', gradient: 'linear-gradient(135deg, #e60012 0%, #ff0000 100%)', type: 'card', subType: 'nintendo', rating: 4.8, desc: 'Nintendo Switch eShop credit. Buy games and DLC for Nintendo Switch.' },
                
                // --- CONSOLES & VR RENTALS ---
                { id: '13', sku: 'RENT-PS5-PRO', name: 'PlayStation 5 Pro', category: 'Console Rental', style: 'Console Rental', price: 25.00, stock: 3, image: 'https://m.media-amazon.com/images/I/51gq-sy5CiL.jpg', img: 'https://m.media-amazon.com/images/I/51gq-sy5CiL.jpg', type: 'rental', rating: 4.8, desc: 'Rent the latest PS5 Pro console. Includes 1 DualSense controller and 3 AAA games.' },
                { id: '14', sku: 'RENT-QUEST-3', name: 'Meta Quest 3 Pro', category: 'VR Rental', style: 'VR Rental', price: 35.00, stock: 4, image: 'https://d28jzcg6y4v9j1.cloudfront.net/media/core/products/2023/10/2/meta-quest-3-05-thinkpro-epiczone.jpg', img: 'https://d28jzcg6y4v9j1.cloudfront.net/media/core/products/2023/10/2/meta-quest-3-05-thinkpro-epiczone.jpg', type: 'rental', rating: 4.9, desc: 'Experience advanced mixed reality. Top-tier VR gaming and immersive experiences.' },
                { id: '15', sku: 'RENT-SWITCH-OLED', name: 'Nintendo Switch OLED', category: 'Console Rental', style: 'Console Rental', price: 15.00, stock: 7, image: 'https://www.droidshop.vn/wp-content/uploads/2022/01/May-choi-game-Nintendo-Switch-OLED-model-with-White-Joy%E2%80%91Con.jpg', img: 'https://www.droidshop.vn/wp-content/uploads/2022/01/May-choi-game-Nintendo-Switch-OLED-model-with-White-Joy%E2%80%91Con.jpg', type: 'rental', rating: 4.7, desc: 'Portable gaming console with vibrant OLED display. Perfect for gaming on the go.' },
                { id: '16', sku: 'RENT-XBOX-SX', name: 'Xbox Series X', category: 'Console Rental', style: 'Console Rental', price: 20.00, stock: 6, image: 'https://www.droidshop.vn/wp-content/uploads/2023/05/May-choi-game-Xbox-Series-X.jpg', img: 'https://www.droidshop.vn/wp-content/uploads/2023/05/May-choi-game-Xbox-Series-X.jpg', type: 'rental', rating: 4.8, desc: 'Next-gen gaming power. 4K gaming at 120fps with Game Pass included.' },
                { id: '17', sku: 'RENT-VIVE-XR', name: 'HTC Vive XR Elite', category: 'VR Rental', style: 'VR Rental', price: 40.00, stock: 2, image: 'https://www.droidshop.vn/wp-content/uploads/2023/02/Kinh-thuc-te-ao-HTC-Vive-XR-Elite.jpg', img: 'https://www.droidshop.vn/wp-content/uploads/2023/02/Kinh-thuc-te-ao-HTC-Vive-XR-Elite.jpg', type: 'rental', rating: 4.6, desc: 'Premium standalone VR headset. High-resolution display and advanced controllers.' },
                { id: '18', sku: 'RENT-ALIENWARE', name: 'Alienware Gaming Laptop', category: 'Gaming PC', style: 'Gaming PC', price: 50.00, stock: 1, image: 'https://laptopgiasi.vn/wp-content/uploads/2023/03/Mua-Ban-Laptop-Alienware-17-R3-Moi-Cu-Gia-Re-Nhu-Ban-Si-Core-i7-6820-HK-The-he-6-Gaming-Doc-Dao-Pin-sieu-lau-2.jpg', img: 'https://laptopgiasi.vn/wp-content/uploads/2023/03/Mua-Ban-Laptop-Alienware-17-R3-Moi-Cu-Gia-Re-Nhu-Ban-Si-Core-i7-6820-HK-The-he-6-Gaming-Doc-Dao-Pin-sieu-lau-2.jpg', type: 'rental', rating: 4.9, desc: 'High-performance gaming laptop. RTX 4090, Intel i9, 32GB RAM. Perfect for AAA titles.' },
            ];
        }

        function loadProducts() {
            const stored = localStorage.getItem('proplay_store_products');
            if (stored) {
                try {
                    products = JSON.parse(stored);
                    // Nếu dữ liệu không có hoặc rỗng, load default
                    if (!products || products.length === 0) {
                        products = getDefaultProducts();
                        saveProducts();
                    }
                } catch (e) {
                    products = getDefaultProducts();
                    saveProducts();
                }
            } else {
                products = getDefaultProducts();
                saveProducts();
            }
            console.log('Loaded products:', products.length); // Debug
        }

        function saveProducts() {
            localStorage.setItem('proplay_store_products', JSON.stringify(products));
        }

        let currentFilter = 'all';

        // --- THEME ---
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) document.documentElement.classList.add('dark');

        // Listen for storage changes from other tabs/windows
        window.addEventListener('storage', (e) => {
            if (e.key === 'proplay_store_products') {
                try {
                    products = JSON.parse(e.newValue);
                    renderList();
                } catch (err) {}
            }
        });

        // --- HELPER FUNCTIONS ---
        function getCardIcon(p) {
            if (p.icon) return p.icon;
            const c = (p.category || '').toLowerCase();
            if (c.includes('steam')) return 'fab fa-steam';
            if (c.includes('playstation') || c.includes('psn')) return 'fab fa-playstation';
            if (c.includes('xbox')) return 'fab fa-xbox';
            if (c.includes('riot')) return 'fas fa-gamepad';
            if (c.includes('nintendo')) return 'fas fa-gamepad';
            return 'fas fa-gift';
        }

        function getCardGradient(p) {
             if (p.gradient) return p.gradient;
             const c = (p.category || '').toLowerCase();
             if (c.includes('steam')) return 'linear-gradient(135deg, #1b2838 0%, #2a475e 100%)';
             if (c.includes('playstation') || c.includes('psn')) return 'linear-gradient(135deg, #00439c 0%, #0070d1 100%)';
             if (c.includes('xbox')) return 'linear-gradient(135deg, #107c10 0%, #3a9a3a 100%)';
             if (c.includes('riot')) return 'linear-gradient(135deg, #d13639 0%, #eb0029 100%)';
             if (c.includes('nintendo')) return 'linear-gradient(135deg, #e60012 0%, #ff0000 100%)';
             return 'linear-gradient(135deg, #333 0%, #555 100%)';
        }

        // --- RENDER LIST ---
        const listContainer = document.getElementById('product-list-container');
        const searchInput = document.getElementById('search-input');
        const totalCount = document.getElementById('total-products');

        function renderList() {
            const searchTerm = searchInput.value.toLowerCase();
            
            // Debug: Log first product to see its structure
            if (products.length > 0) {
                console.log('Sample product:', products[0]);
                console.log('Product types:', products.map(p => p.type));
            }
            
            const filtered = products.filter(p => {
                const matchSearch = p.name.toLowerCase().includes(searchTerm) || p.sku.toLowerCase().includes(searchTerm);
                const matchFilter = currentFilter === 'all' ? true : p.type === currentFilter;
                return matchSearch && matchFilter;
            });

            console.log('Current filter:', currentFilter); // Debug
            console.log('Total products:', products.length); // Debug
            console.log('Filtered products:', filtered.length); // Debug

            totalCount.innerText = filtered.length;

            if (filtered.length === 0) {
                listContainer.innerHTML = `<div class="flex flex-col items-center justify-center mt-20 opacity-50"><i class="fas fa-box-open text-3xl mb-2 dark:text-white"></i><p class="text-sm dark:text-white">No products found.</p></div>`;
                return;
            }

            listContainer.innerHTML = filtered.map(p => {
                const isLowStock = p.stock <= 5;
                const isOutOfStock = p.stock === 0;
                
                return `
                <div onclick="openSheet('${p.id}')" class="group relative flex gap-4 p-4 mb-3 bg-white dark:bg-dark-surface rounded-2xl border border-slate-100 dark:border-dark-border shadow-sm active:scale-[0.98] transition-all cursor-pointer">
                    <div class="w-20 h-20 rounded-xl bg-slate-100 dark:bg-white/5 shrink-0 overflow-hidden relative border border-slate-100 dark:border-white/10">
                        ${p.type === 'card' ? 
                            `<div class="w-full h-full flex items-center justify-center" style="background: ${getCardGradient(p)}; color: white">
                                <i class="${getCardIcon(p)} text-4xl"></i>
                            </div>` 
                            : 
                            `<img src="${p.image}" class="w-full h-full object-cover">`
                        }
                        ${isOutOfStock ? `<div class="absolute inset-0 bg-black/60 flex items-center justify-center text-[10px] text-white font-bold uppercase backdrop-blur-sm">Sold Out</div>` : ''}
                    </div>

                    <div class="flex-1 flex flex-col justify-between py-0.5">
                        <div>
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-sm dark:text-white leading-tight line-clamp-2">${p.name}</h4>
                                <span class="text-xs font-mono font-bold text-brand">$${p.price.toFixed(2)}</span>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1">${p.category} • ${p.sku}</p>
                        </div>
                        
                        <div class="flex justify-between items-end mt-2">
                             <div class="flex items-center gap-1.5">
                                <span class="text-[10px] font-bold ${isLowStock ? 'text-red-500' : 'text-green-500'}">
                                    <i class="fas ${isOutOfStock ? 'fa-times-circle' : 'fa-cubes'}"></i> ${p.stock} in stock
                                </span>
                                ${isLowStock && !isOutOfStock ? `<span class="animate-pulse w-2 h-2 rounded-full bg-red-500"></span>` : ''}
                             </div>
                             <div class="w-8 h-8 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400 group-hover:text-brand group-hover:bg-brand/10 transition-colors">
                                <i class="fas fa-pen text-xs"></i>
                             </div>
                        </div>
                    </div>
                </div>
            `}).join('');
        }

        // --- FILTER ---
        function setFilter(type, btn) {
            currentFilter = type;
            document.querySelectorAll('.filter-btn').forEach(b => b.className = 'filter-btn px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-white dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 transition-all');
            if(btn) btn.className = 'filter-btn active px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-brand text-white border-brand transition-all shadow-glow';
            renderList();
        }

        searchInput.addEventListener('input', renderList);

        // --- RESET DATA ---
        function resetData() {
            if(confirm('Reset all inventory data to default?')) {
                localStorage.removeItem('proplay_store_products');
                products = getDefaultProducts();
                saveProducts();
                renderList();
                showToast('Data reset successfully');
            }
        }

        // --- SMART SHEET LOGIC (ADD / EDIT) ---
        const overlay = document.getElementById('action-sheet-overlay');
        const sheet = document.getElementById('action-sheet');
        const form = document.getElementById('product-form');
        const deleteBtn = document.getElementById('delete-btn');

        // Form Fields
        const fId = document.getElementById('p-id');
        const fName = document.getElementById('p-name');
        const fDesc = document.getElementById('p-desc');
        const fCat = document.getElementById('p-category');
        const fStyle = document.getElementById('p-style');
        const fPrice = document.getElementById('p-price');
        const fStock = document.getElementById('p-stock');
        const fSku = document.getElementById('p-sku');
        const fImg = document.getElementById('current-img');
        const fCardPreview = document.getElementById('card-preview');
        const fPlaceholder = document.getElementById('upload-placeholder');
        const btnSubmit = document.getElementById('submit-btn');
        const title = document.getElementById('sheet-title');

        let tempImage = '';

        function openSheet(id) {
            if (id) {
                // --- EDIT MODE ---
                const p = products.find(i => i.id === id);
                title.innerText = "Edit Product";
                btnSubmit.innerText = "Update Product";
                deleteBtn.classList.remove('hidden');
                deleteBtn.onclick = () => deleteProduct(id);

                fId.value = p.id;
                fName.value = p.name;
                fDesc.value = p.desc || '';
                fCat.value = p.type;
                fStyle.value = p.style || p.category;
                fPrice.value = p.price;
                fStock.value = p.stock;
                fSku.value = p.sku;
                
                // Image Handling
                if (p.type === 'card') {
                    fImg.classList.add('hidden');
                    fPlaceholder.classList.add('hidden');
                    fCardPreview.classList.remove('hidden');
                    fCardPreview.innerHTML = `<div class="w-full h-full flex items-center justify-center" style="background: ${getCardGradient(p)}; color: white"><i class="${getCardIcon(p)} text-4xl"></i></div>`;
                } else {
                    tempImage = p.image;
                    fImg.src = p.image;
                    fImg.classList.remove('hidden');
                    fPlaceholder.classList.add('hidden');
                    fCardPreview.classList.add('hidden');
                }

            } else {
                // --- ADD MODE ---
                title.innerText = "Add New Product";
                btnSubmit.innerText = "Create Product";
                deleteBtn.classList.add('hidden');
                form.reset();
                fId.value = '';
                fCat.value = 'gear'; // Default to Gear
                fStyle.value = 'Controllers'; // Default sub-category
                fSku.value = 'AUTO-GEN'; // Placeholder logic
                
                // Image Reset
                tempImage = 'https://via.placeholder.com/150'; // Default placeholder
                fImg.src = '';
                fImg.classList.add('hidden');
                fCardPreview.classList.add('hidden');
                fPlaceholder.classList.remove('hidden');
            }

            // Animate Open
            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                sheet.classList.remove('sheet-enter');
                sheet.classList.add('sheet-active');
            }, 10);
        }

        function closeSheet() {
            sheet.classList.remove('sheet-active');
            sheet.classList.add('sheet-enter');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }

        // --- SYNC CATEGORY TO STYLE ---
        function syncCategoryToStyle() {
            // Not needed anymore - Type is simplified
        }

        // --- MOCK IMAGE UPLOAD ---
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    fImg.src = e.target.result;
                    fImg.classList.remove('hidden');
                    fCardPreview.classList.add('hidden');
                    fPlaceholder.classList.add('hidden');
                    tempImage = e.target.result; // Store for submit
                    showToast('Image uploaded successfully', 'success');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // --- FORM SUBMIT HANDLER ---
        function handleFormSubmit(e) {
            e.preventDefault();
            const id = fId.value;

            if (id) {
                // UPDATE EXISTING
                const index = products.findIndex(p => p.id === id);
                if (index !== -1) {
                    products[index] = {
                        ...products[index],
                        name: fName.value,
                        desc: fDesc.value,
                        category: fStyle.value,
                        style: fStyle.value,
                        type: fCat.value,
                        price: parseFloat(fPrice.value),
                        stock: parseInt(fStock.value),
                        image: tempImage,
                        img: tempImage
                    };
                    saveProducts();
                    showToast('Product updated successfully');
                }
            } else {
                // CREATE NEW
                const newProduct = {
                    id: Date.now().toString(),
                    sku: 'PRO-' + Math.floor(Math.random() * 10000),
                    name: fName.value,
                    desc: fDesc.value,
                    category: fStyle.value,
                    style: fStyle.value,
                    type: fCat.value,
                    price: parseFloat(fPrice.value),
                    stock: parseInt(fStock.value),
                    image: tempImage,
                    img: tempImage,
                    rating: 4.5
                };
                products.unshift(newProduct); // Add to top
                saveProducts();
                showToast('New product added to inventory');
            }

            renderList();
            closeSheet();
        }

        function deleteProduct(id) {
            if(confirm('Are you sure you want to delete this product?')) {
                products = products.filter(p => p.id !== id);
                saveProducts();
                showToast('Product deleted', 'error');
                renderList();
                closeSheet();
            }
        }

        // --- TOAST ---
        function showToast(msg, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            let color = 'bg-brand';
            let icon = 'fa-check';
            if(type === 'error') { color = 'bg-red-500'; icon = 'fa-trash-alt'; }

            toast.className = `flex items-center gap-3 p-3 rounded-full bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-glow pointer-events-auto transform transition-all duration-300 translate-y-10 opacity-0`;
            toast.innerHTML = `<div class="w-8 h-8 rounded-full ${color} text-white flex items-center justify-center shrink-0 shadow-md"><i class="fas ${icon} text-xs"></i></div><span class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate pr-2">${msg}</span>`;
            container.appendChild(toast);
            requestAnimationFrame(() => toast.classList.remove('translate-y-10', 'opacity-0'));
            setTimeout(() => { toast.classList.add('opacity-0', '-translate-y-2'); setTimeout(() => toast.remove(), 300); }, 3000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadProducts();
            renderList();
        });
    </script>
</body>
</html>