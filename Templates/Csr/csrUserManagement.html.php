    <style>
        /* Sheet Animation */
        .sheet-enter { transform: translateY(100%); }
        .sheet-active { transform: translateY(0); }
        .transition-sheet { transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    </style>

    <div id="toast-container" class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] w-full max-w-xs px-4 pointer-events-none flex flex-col gap-2"></div>

    <header class="px-6 pt-8 pb-4 sticky top-0 z-40 bg-light-bg/95 dark:bg-dark-bg/95 backdrop-blur-md border-b border-slate-100 dark:border-white/5">
        <div class="flex items-center gap-4 mb-4">
            <button onclick="history.back()" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 flex items-center justify-center hover:text-brand transition-colors active:scale-95">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1 class="text-xl font-bold flex-1">User Management</h1>
            <div class="text-xs font-bold bg-brand/10 text-brand px-2 py-1 rounded" id="total-users-badge">0 Users</div>
        </div>

        <div class="space-y-3">
            <div class="relative group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand transition-colors"></i>
                <input type="text" id="search-input" placeholder="Search name, email or ID..." class="w-full pl-10 pr-4 py-3 bg-white dark:bg-dark-surface border border-slate-200 dark:border-dark-border rounded-xl text-sm font-medium focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand dark:text-white transition-all shadow-sm">
            </div>
            
            <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1" id="filter-tabs">
                <button onclick="setFilter('all', this)" class="filter-btn active px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-brand text-white border-brand transition-all shadow-sm">All</button>
                <button onclick="setFilter('active', this)" class="filter-btn px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-white dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 transition-all">Active</button>
                <button onclick="setFilter('suspended', this)" class="filter-btn px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-white dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 transition-all">Suspended</button>
                <button onclick="setFilter('vip', this)" class="filter-btn px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-white dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 transition-all">VIP Only</button>
            </div>
        </div>
    </header>

    <main class="h-[calc(100vh-185px)] overflow-y-auto px-6 pb-32 pt-2" id="user-list-container">
        </main>

    <div id="action-sheet-overlay" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden transition-opacity opacity-0" onclick="closeSheet()"></div>
    
    <div id="action-sheet" class="fixed bottom-0 left-0 w-full z-50 bg-white dark:bg-dark-surface rounded-t-[2rem] shadow-sheet sheet-enter transition-sheet max-h-[90vh] flex flex-col border-t border-white/20 dark:border-white/5">
        <div class="w-full pt-4 pb-2 flex justify-center shrink-0 cursor-pointer" onclick="closeSheet()">
            <div class="w-12 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full"></div>
        </div>

        <div id="sheet-content" class="p-6 overflow-y-auto pb-32 safe-pb">
            </div>
    </div>

    <script>
        // --- DATA SIMULATION ---
        let users = [
            { id: 'U-8821', name: 'Alex Hunter', email: 'alex.hunter@gmail.com', avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Felix', role: 'VIP', status: 'active', spent: 540.00, orders: 12 },
            { id: 'U-8822', name: 'Brian Smith', email: 'brian.s@yahoo.com', avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Jack', role: 'Member', status: 'suspended', spent: 0.00, orders: 0 },
            { id: 'U-8823', name: 'Sarah Connor', email: 'sarah.c@gmail.com', avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Sarah', role: 'Platinum', status: 'active', spent: 1250.50, orders: 45 },
            { id: 'U-8824', name: 'John Doe', email: 'johndoe@test.com', avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=John', role: 'Member', status: 'active', spent: 25.00, orders: 1 },
            { id: 'U-8825', name: 'Spambot 9000', email: 'spam@bot.net', avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Spam', role: 'Member', status: 'suspended', spent: 0.00, orders: 0 },
            { id: 'U-8826', name: 'Emily Rose', email: 'emily.rose@outlook.com', avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Emily', role: 'VIP', status: 'active', spent: 340.00, orders: 8 },
        ];

        let currentFilter = 'all';
        let currentUserID = null;

        // --- THEME AUTO-DETECT ---
        function updateTheme(e) {
            if (e.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        updateTheme(darkModeMediaQuery); // Run once on load
        darkModeMediaQuery.addEventListener('change', updateTheme); // Listen for system changes

        // --- RENDER LOGIC ---
        const listContainer = document.getElementById('user-list-container');
        const searchInput = document.getElementById('search-input');
        const badgeCount = document.getElementById('total-users-badge');

        function renderList() {
            const searchTerm = searchInput.value.toLowerCase();
            
            // Filter logic
            const filtered = users.filter(u => {
                const matchSearch = u.name.toLowerCase().includes(searchTerm) || u.email.toLowerCase().includes(searchTerm) || u.id.toLowerCase().includes(searchTerm);
                let matchFilter = true;
                
                if (currentFilter === 'active') matchFilter = u.status === 'active';
                else if (currentFilter === 'suspended') matchFilter = u.status === 'suspended';
                else if (currentFilter === 'vip') matchFilter = ['VIP', 'Platinum'].includes(u.role);

                return matchSearch && matchFilter;
            });

            badgeCount.innerText = `${filtered.length} Users`;

            if (filtered.length === 0) {
                listContainer.innerHTML = `
                    <div class="flex flex-col items-center justify-center mt-20 opacity-50">
                        <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center mb-3">
                            <i class="fas fa-search text-2xl dark:text-white"></i>
                        </div>
                        <p class="text-sm font-medium dark:text-white">No users found.</p>
                    </div>
                `;
                return;
            }

            listContainer.innerHTML = filtered.map(u => `
                <div onclick="openSheet('${u.id}')" class="group flex items-center justify-between p-4 mb-3 bg-white dark:bg-dark-surface rounded-2xl border ${u.status === 'suspended' ? 'border-red-200 dark:border-red-900/30 bg-red-50/30' : 'border-slate-100 dark:border-dark-border'} shadow-sm active:scale-[0.98] transition-all cursor-pointer relative overflow-hidden">
                    
                    ${u.status === 'suspended' ? `<div class="absolute -right-3 top-3 bg-red-500 text-white text-[9px] px-4 py-0.5 rotate-45 font-bold shadow-sm">BANNED</div>` : ''}

                    <div class="flex items-center gap-3.5">
                        <div class="relative">
                            <img src="${u.avatar}" class="w-12 h-12 rounded-full bg-slate-100 object-cover border border-slate-100 dark:border-white/10">
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-white dark:bg-dark-surface rounded-full flex items-center justify-center border border-slate-50 dark:border-dark-surface">
                                ${u.status === 'active' 
                                    ? '<i class="fas fa-check-circle text-green-500 text-sm"></i>' 
                                    : '<i class="fas fa-ban text-red-500 text-sm"></i>'}
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-0.5">
                                <h4 class="font-bold text-sm dark:text-white leading-none ${u.status === 'suspended' ? 'line-through decoration-red-500 opacity-70' : ''}">${u.name}</h4>
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded ${u.role === 'Platinum' ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300'} uppercase tracking-wide">${u.role}</span>
                            </div>
                            <p class="text-xs text-slate-400 font-mono">${u.email}</p>
                        </div>
                    </div>
                    <div class="text-right pr-2">
                         <div class="text-sm font-mono font-bold dark:text-white">$${u.spent.toFixed(2)}</div>
                         <div class="text-[10px] text-slate-400">${u.orders} orders</div>
                    </div>
                </div>
            `).join('');
        }

        // --- FILTER TABS ---
        function setFilter(type, btn) {
            currentFilter = type;
            // Update UI
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.className = 'filter-btn px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-white dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 transition-all';
            });
            btn.className = 'filter-btn active px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-brand text-white border-brand transition-all shadow-glow';
            renderList();
        }

        searchInput.addEventListener('input', renderList);

        // --- BOTTOM SHEET LOGIC ---
        const overlay = document.getElementById('action-sheet-overlay');
        const sheet = document.getElementById('action-sheet');
        const sheetContent = document.getElementById('sheet-content');

        function openSheet(id) {
            currentUserID = id;
            const u = users.find(user => user.id === id);
            
            // Build Detail View
            sheetContent.innerHTML = `
                <div class="flex flex-col items-center mb-6">
                    <div class="relative">
                        <img src="${u.avatar}" class="w-20 h-20 rounded-full bg-slate-100 mb-3 border-4 border-slate-50 dark:border-white/5 object-cover">
                        ${u.status === 'suspended' ? '<div class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center backdrop-blur-[2px]"><i class="fas fa-lock text-white text-2xl"></i></div>' : ''}
                    </div>
                    
                    <h2 class="text-xl font-bold dark:text-white flex items-center gap-2">
                        ${u.name} 
                        <i class="fas fa-copy text-slate-300 text-xs cursor-pointer active:text-brand" onclick="showToast('ID Copied: ${u.id}')"></i>
                    </h2>
                    <p class="text-sm text-slate-500 font-mono">${u.email}</p>
                    
                    <div class="flex gap-4 mt-4 w-full">
                        <div class="flex-1 bg-slate-50 dark:bg-white/5 p-3 rounded-xl text-center border border-slate-100 dark:border-white/5">
                            <p class="text-[10px] text-slate-400 uppercase font-bold">Total Spent</p>
                            <p class="text-lg font-mono font-bold text-brand">$${u.spent.toFixed(2)}</p>
                        </div>
                        <div class="flex-1 bg-slate-50 dark:bg-white/5 p-3 rounded-xl text-center border border-slate-100 dark:border-white/5">
                            <p class="text-[10px] text-slate-400 uppercase font-bold">Status</p>
                            <p class="text-lg font-bold ${u.status === 'active' ? 'text-green-500' : 'text-red-500'} capitalize">${u.status}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="font-bold text-xs text-slate-400 uppercase tracking-widest mb-2">Actions</h3>
                    
                    <button onclick="showToast('Reset Password Email Sent')" class="w-full flex items-center gap-4 p-4 rounded-xl bg-white dark:bg-dark-surface border border-slate-100 dark:border-white/5 active:scale-[0.98] transition-transform">
                        <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center"><i class="fas fa-key"></i></div>
                        <div class="text-left flex-1">
                            <h4 class="font-bold text-sm dark:text-white">Reset Password</h4>
                            <p class="text-[10px] text-slate-400">Send recovery email to user</p>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-slate-300"></i>
                    </button>

                    <button onclick="showToast('Opening Order History...')" class="w-full flex items-center gap-4 p-4 rounded-xl bg-white dark:bg-dark-surface border border-slate-100 dark:border-white/5 active:scale-[0.98] transition-transform">
                        <div class="w-10 h-10 rounded-full bg-purple-50 dark:bg-purple-900/20 text-purple-500 flex items-center justify-center"><i class="fas fa-receipt"></i></div>
                        <div class="text-left flex-1">
                            <h4 class="font-bold text-sm dark:text-white">View Orders</h4>
                            <p class="text-[10px] text-slate-400">Manage transactions & refunds</p>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-slate-300"></i>
                    </button>

                    <div class="h-px bg-slate-100 dark:bg-white/10 my-4"></div>

                    ${u.status === 'active' ? `
                        <button onclick="toggleUserStatus('${u.id}', 'suspend')" class="w-full py-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-bold text-sm border border-red-100 dark:border-red-900/30 hover:bg-red-100 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-ban"></i> Suspend Account
                        </button>
                    ` : `
                        <button onclick="toggleUserStatus('${u.id}', 'activate')" class="w-full py-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 font-bold text-sm border border-green-100 dark:border-green-900/30 hover:bg-green-100 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-unlock"></i> Reactivate Account
                        </button>
                    `}
                </div>
            `;

            // Animate In
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
            
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 300);
        }

        function toggleUserStatus(id, action) {
            const user = users.find(u => u.id === id);
            
            if (action === 'suspend') {
                if(confirm(`Are you sure you want to suspend ${user.name}? They will lose access immediately.`)) {
                    user.status = 'suspended';
                    showToast('User has been Suspended', 'error');
                } else return;
            } else {
                user.status = 'active';
                showToast('User Reactivated Successfully', 'success');
            }

            // Refresh UI
            renderList();
            closeSheet();
        }

        // --- TOAST SYSTEM ---
        function showToast(msg, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            let icon = type === 'success' ? 'fa-check' : (type === 'error' ? 'fa-ban' : 'fa-info');
            let color = type === 'success' ? 'bg-green-500' : (type === 'error' ? 'bg-red-500' : 'bg-blue-500');

            toast.className = `flex items-center gap-3 p-3 rounded-full bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-glow pointer-events-auto transform transition-all duration-300 translate-y-10 opacity-0`;
            toast.innerHTML = `
                <div class="w-8 h-8 rounded-full ${color} text-white flex items-center justify-center shrink-0 shadow-md">
                    <i class="fas ${icon} text-xs"></i>
                </div>
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate pr-2">${msg}</span>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => toast.classList.remove('translate-y-10', 'opacity-0'));
            setTimeout(() => {
                toast.classList.add('opacity-0', '-translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Init
        document.addEventListener('DOMContentLoaded', renderList);

    </script>
