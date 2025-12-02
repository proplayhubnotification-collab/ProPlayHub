<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proplay Hub - History</title>
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
                        light: { bg: '#f8fafc', text: '#020618' },
                        dark: { bg: '#020618', text: '#f8fafc' },
                        success: '#22c55e' 
                    },
                    borderRadius: { 'custom': '0.375rem' },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        body { transition: background-color 0.3s, color 0.3s; }
        
        /* Tab Animation */
        .tab-indicator {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .fade-in { animation: fadeIn 0.3s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-light-bg text-light-text dark:bg-dark-bg dark:text-dark-text font-sans antialiased min-h-screen flex justify-center bg-gray-100">

    <!-- Mobile Container -->
    <div id="app-container" class="w-full max-w-md bg-light-bg dark:bg-dark-bg min-h-screen relative shadow-2xl flex flex-col">
        
        <!-- Header -->
        <div class="px-4 py-4 flex items-center justify-between bg-white dark:bg-slate-900 shadow-sm z-20 sticky top-0">
            <button onclick="window.history.back()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            <h1 class="text-lg font-bold ml-2">History</h1>
            
            <div class="flex items-center gap-1">
                <button onclick="app.confirmClearHistory()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-red-50 hover:text-red-500 dark:hover:bg-slate-800 transition-colors" title="Clear History">
                    <i class="fas fa-trash-alt text-lg"></i>
                </button>
                <button onclick="app.toggleFilter()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors relative">
                    <i class="fas fa-filter text-lg"></i>
                    <span id="filter-badge" class="hidden absolute top-2 right-2 w-2 h-2 bg-brand rounded-full"></span>
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="px-4 pt-2 bg-white dark:bg-slate-900 pb-0 shadow-sm z-10">
            <div class="flex relative">
                <button onclick="app.switchTab('purchases')" class="flex-1 pb-3 text-center font-semibold text-sm transition-colors text-brand" id="tab-purchases">
                    Purchases
                </button>
                <button onclick="app.switchTab('points')" class="flex-1 pb-3 text-center font-semibold text-sm transition-colors text-gray-400 dark:text-gray-500" id="tab-points">
                    Points
                </button>
                
                <!-- Sliding Indicator -->
                <div id="tab-indicator" class="absolute bottom-0 left-0 w-1/2 h-0.5 bg-brand tab-indicator rounded-t-full"></div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto no-scrollbar bg-gray-50 dark:bg-black/20 p-4 space-y-4" id="history-list">
            <!-- Items will be injected here via JS -->
        </div>

        <!-- Filter Modal (Overlay) -->
        <div id="filter-modal" class="hidden fixed inset-0 z-50 bg-black/50 flex flex-col justify-end">
            <div class="bg-white dark:bg-slate-900 rounded-t-2xl p-6 animate-[slideUp_0.3s_ease-out]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg">Filter History</h3>
                    <button onclick="app.toggleFilter()" class="p-2"><i class="fas fa-times"></i></button>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase mb-3">Time Range</p>
                        <div class="flex gap-3">
                            <button class="px-4 py-2 rounded-custom bg-brand text-white text-sm font-medium">All Time</button>
                            <button class="px-4 py-2 rounded-custom bg-gray-100 dark:bg-slate-800 text-sm font-medium">This Month</button>
                            <button class="px-4 py-2 rounded-custom bg-gray-100 dark:bg-slate-800 text-sm font-medium">Last Month</button>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase mb-3">Status</p>
                        <div class="flex gap-3">
                            <button class="px-4 py-2 rounded-custom bg-green-100 text-green-700 text-sm font-medium border border-green-200">Success</button>
                            <button class="px-4 py-2 rounded-custom bg-gray-100 dark:bg-slate-800 text-sm font-medium">Pending</button>
                            <button class="px-4 py-2 rounded-custom bg-gray-100 dark:bg-slate-800 text-sm font-medium">Failed</button>
                        </div>
                    </div>

                    <button onclick="app.applyFilter()" class="w-full py-3 bg-brand text-white font-bold rounded-custom shadow-lg hover:brightness-110">Apply Filters</button>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Details Modal -->
    <div id="detail-modal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-2xl p-6 shadow-2xl scale-95 opacity-0 transition-all duration-300" id="detail-card">
            <div class="text-center mb-6">
                <!-- Icon changes based on status via JS -->
                <div id="detail-icon-bg" class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 text-green-500 text-3xl">
                    <i id="detail-icon" class="fas fa-check"></i>
                </div>
                <h3 class="font-bold text-xl mb-1" id="detail-status-text">Transaction Successful!</h3>
                <p class="text-gray-500 text-sm" id="detail-date">10:53 - 28/11/2025</p>
            </div>
            
            <div class="space-y-4 mb-6">
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-gray-500 text-sm">Item</span>
                    <span class="font-bold text-sm text-right max-w-[60%]" id="detail-title">Item Name</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-gray-500 text-sm">Amount</span>
                    <span class="font-bold text-lg" id="detail-amount">71,000 VND</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-gray-500 text-sm">Payment Method</span>
                    <span class="font-medium text-sm text-right" id="detail-method">Visa **** 4242</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-gray-500 text-sm">Order ID</span>
                    <span class="font-mono text-xs text-gray-400">#DIGITAL-88293</span>
                </div>
            </div>

            <button onclick="app.closeDetail()" class="w-full py-3 bg-gray-100 dark:bg-slate-800 font-bold rounded-custom hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors">Close</button>
        </div>
    </div>

    <script>
        const app = {
            activeTab: 'purchases',
            
            // Updated Mock Data for 100% Online Game Shop
            purchases: [
                {
                    id: 1,
                    title: "Elden Ring (Steam Key)",
                    amount: "899,000 VND",
                    date: "10:53 - 28/11/2025",
                    method: "Via Visa **** 4242",
                    status: "success"
                },
                {
                    id: 2,
                    title: "Valorant Points (2050 VP)",
                    amount: "250,000 VND",
                    date: "15:47 - 27/11/2025",
                    method: "Via MoMo Wallet",
                    status: "success"
                },
                {
                    id: 3,
                    title: "Genshin Impact: Welkin Moon",
                    amount: "109,000 VND",
                    date: "09:30 - 25/11/2025",
                    method: "Via Apple Pay",
                    status: "success"
                },
                 {
                    id: 4,
                    title: "Minecraft: Java & Bedrock",
                    amount: "399,000 VND",
                    date: "14:15 - 20/11/2025",
                    method: "Via Proplay Wallet",
                    status: "success"
                },
                {
                    id: 5,
                    title: "FIFA 24 Ultimate Team Points",
                    amount: "500,000 VND",
                    date: "08:20 - 15/11/2025",
                    method: "Via Banking QR",
                    status: "success"
                }
            ],
            
            points: [
                {
                    id: 101,
                    title: "Redeemed: Rare AK-47 Skin",
                    amount: "-200 pts",
                    date: "11:20 - 28/11/2025",
                    method: "Inventory Added",
                    type: "minus"
                },
                {
                    id: 102,
                    title: "Daily Login Bonus",
                    amount: "+10 pts",
                    date: "08:00 - 28/11/2025",
                    method: "System Reward",
                    type: "plus"
                },
                {
                    id: 103,
                    title: "Purchase Bonus: Elden Ring",
                    amount: "+450 pts",
                    date: "10:53 - 28/11/2025",
                    method: "Loyalty Program",
                    type: "plus"
                },
                {
                    id: 104,
                    title: "Review Reward",
                    amount: "+50 pts",
                    date: "18:00 - 27/11/2025",
                    method: "Community Activity",
                    type: "plus"
                }
            ],

            init() {
                this.loadTheme();
                this.renderList();
            },

            loadTheme() {
                // Sync theme
                const storedData = localStorage.getItem('proplayUser_v2');
                if (storedData) {
                    const parsed = JSON.parse(storedData);
                    if (parsed.theme === 'dark') {
                        document.documentElement.classList.add('dark');
                    }
                }
            },

            switchTab(tab) {
                this.activeTab = tab;
                
                // Update UI Indicators
                const indicator = document.getElementById('tab-indicator');
                const btnPurchases = document.getElementById('tab-purchases');
                const btnPoints = document.getElementById('tab-points');

                if (tab === 'purchases') {
                    indicator.style.transform = 'translateX(0)';
                    btnPurchases.classList.add('text-brand');
                    btnPurchases.classList.remove('text-gray-400', 'dark:text-gray-500');
                    btnPoints.classList.remove('text-brand');
                    btnPoints.classList.add('text-gray-400', 'dark:text-gray-500');
                } else {
                    indicator.style.transform = 'translateX(100%)';
                    btnPoints.classList.add('text-brand');
                    btnPoints.classList.remove('text-gray-400', 'dark:text-gray-500');
                    btnPurchases.classList.remove('text-brand');
                    btnPurchases.classList.add('text-gray-400', 'dark:text-gray-500');
                }

                this.renderList();
            },

            renderList() {
                const container = document.getElementById('history-list');
                const data = this.activeTab === 'purchases' ? this.purchases : this.points;
                
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = `
                        <div class="flex flex-col items-center justify-center pt-20 text-gray-400 fade-in">
                            <i class="fas fa-box-open text-4xl mb-3 opacity-50"></i>
                            <p class="text-sm">No history available</p>
                        </div>
                    `;
                    return;
                }

                data.forEach((item, index) => {
                    const isPoints = this.activeTab === 'points';
                    let amountColor = 'text-green-600 dark:text-green-400';
                    if (isPoints && item.amount.includes('-')) {
                        amountColor = 'text-gray-500 dark:text-gray-400';
                    }

                    const card = document.createElement('div');
                    card.className = "bg-white dark:bg-slate-900 p-4 rounded-custom shadow-sm border border-transparent dark:border-slate-800 fade-in cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-800/80 transition-colors";
                    card.style.animationDelay = `${index * 0.05}s`;
                    card.onclick = () => app.viewDetail(item.id);
                    
                    // Added generic icon for item type
                    const icon = isPoints ? 'fa-star' : 'fa-gamepad';
                    
                    card.innerHTML = `
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-slate-800 flex items-center justify-center shrink-0 text-brand text-xs">
                                    <i class="fas ${icon}"></i>
                                </div>
                                <h3 class="font-bold text-sm leading-tight truncate">${item.title}</h3>
                            </div>
                            <span class="font-bold text-sm ${amountColor} whitespace-nowrap pl-2">${item.amount}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 ml-11">
                            <span>${item.date}</span>
                            <span class="truncate max-w-[50%] text-right opacity-80">${item.method}</span>
                        </div>
                    `;
                    container.appendChild(card);
                });
            },

            viewDetail(id) {
                const modal = document.getElementById('detail-modal');
                const card = document.getElementById('detail-card');
                const list = this.activeTab === 'purchases' ? this.purchases : this.points;
                const item = list.find(i => i.id === id);

                if(item) {
                    document.getElementById('detail-title').innerText = item.title;
                    document.getElementById('detail-amount').innerText = item.amount;
                    document.getElementById('detail-date').innerText = item.date;
                    document.getElementById('detail-method').innerText = item.method;
                    
                    // Dynamic Status Text
                    const statusText = document.getElementById('detail-status-text');
                    const iconBg = document.getElementById('detail-icon-bg');
                    
                    if(this.activeTab === 'points' && item.type === 'minus') {
                         statusText.innerText = "Redemption Successful";
                         iconBg.className = "w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-500 text-3xl";
                    } else {
                         statusText.innerText = "Transaction Successful";
                         iconBg.className = "w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 text-green-500 text-3xl";
                    }

                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        card.classList.remove('scale-95', 'opacity-0');
                        card.classList.add('scale-100', 'opacity-100');
                    }, 10);
                }
            },

            closeDetail() {
                const modal = document.getElementById('detail-modal');
                const card = document.getElementById('detail-card');
                
                card.classList.remove('scale-100', 'opacity-100');
                card.classList.add('scale-95', 'opacity-0');
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            },

            confirmClearHistory() {
                const tabName = this.activeTab === 'purchases' ? 'Purchase' : 'Points';
                if(confirm(`Are you sure you want to clear your ${tabName} history? This cannot be undone.`)) {
                    if (this.activeTab === 'purchases') {
                        this.purchases = [];
                    } else {
                        this.points = [];
                    }
                    this.renderList();
                }
            },

            toggleFilter() {
                const modal = document.getElementById('filter-modal');
                modal.classList.toggle('hidden');
            },

            applyFilter() {
                this.toggleFilter();
                // Simulation only
                document.getElementById('filter-badge').classList.remove('hidden');
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            app.init();
        });
    </script>
</body>
</html>