<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ProPlay - Order Command</title>
    
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
                    'glow': '0 0 20px rgba(236, 0, 63, 0.3)',
                    'urgent': '0 0 15px rgba(239, 68, 68, 0.4)',
                    'sheet': '0 -5px 25px rgba(0,0,0,0.3)'
                },
                animation: {
                    'pulse-fast': 'pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
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
            <h1 class="text-xl font-bold flex-1">Order Command</h1>
            <div class="flex gap-2">
                 <button onclick="filterUrgent()" class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/10 text-red-500 flex items-center justify-center hover:bg-red-100 transition-colors active:scale-95 border border-red-100 dark:border-red-900/20">
                    <i class="fas fa-fire"></i>
                </button>
            </div>
        </div>

        <div class="space-y-3">
            <div class="relative group">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-brand transition-colors"></i>
                <input type="text" id="search-input" placeholder="Search Order ID, Customer..." class="w-full pl-10 pr-4 py-3 bg-white dark:bg-dark-surface border border-slate-200 dark:border-dark-border rounded-xl text-sm font-medium focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand dark:text-white transition-all shadow-sm">
            </div>
            
            <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1" id="filter-tabs">
                <button onclick="setFilter('all', this)" class="filter-btn active px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-brand text-white border-brand transition-all shadow-glow">All</button>
                <button onclick="setFilter('pending', this)" class="filter-btn px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-white dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 transition-all">Pending</button>
                <button onclick="setFilter('processing', this)" class="filter-btn px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-white dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 transition-all">Processing</button>
                <button onclick="setFilter('shipped', this)" class="filter-btn px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-white dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 transition-all">Shipped</button>
            </div>
        </div>
    </header>

    <main class="h-[calc(100vh-185px)] overflow-y-auto px-6 pb-32 pt-2" id="order-list-container">
        </main>

    <div id="action-sheet-overlay" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden transition-opacity opacity-0" onclick="closeSheet()"></div>
    
    <div id="action-sheet" class="fixed bottom-0 left-0 w-full z-50 bg-white dark:bg-[#0b1121] rounded-t-[2rem] shadow-sheet sheet-enter transition-sheet max-h-[92vh] flex flex-col border-t border-white/20 dark:border-white/5">
        <div class="w-full pt-4 pb-2 flex justify-center shrink-0 cursor-pointer" onclick="closeSheet()">
            <div class="w-12 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full"></div>
        </div>

        <div id="sheet-content" class="p-6 overflow-y-auto pb-12 safe-pb">
            </div>
    </div>

    <script>
        // --- MOCK DATA ---
        const staffDb = {
            'EMP-001': { name: 'Sarah J.', avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Sarah' },
            'EMP-088': { name: 'Mike Ross', avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Mike' },
            'EMP-102': { name: 'Davina C.', avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Davina' }
        };

        const productDb = [
            { name: 'Elden Ring: DLC', price: 39.99 },
            { name: 'PS5 Pro Console', price: 699.00 },
            { name: 'Xbox Series X', price: 499.00 },
            { name: 'Nintendo Switch OLED', price: 349.99 },
            { name: 'FIFA 24', price: 59.99 },
            { name: 'Call of Duty: MW3', price: 69.99 },
            { name: 'DualSense Controller', price: 69.99 },
            { name: 'Gaming Headset', price: 99.99 }
        ];

        const customerDb = [
            { name: 'Alex Hunter', seed: 'Felix' },
            { name: 'Sarah Connor', seed: 'Sarah' },
            { name: 'John Doe', seed: 'John' },
            { name: 'Emily Rose', seed: 'Emily' },
            { name: 'Michael Scott', seed: 'Michael' },
            { name: 'Dwight Schrute', seed: 'Dwight' },
            { name: 'Jim Halpert', seed: 'Jim' },
            { name: 'Pam Beesly', seed: 'Pam' }
        ];

        let orders = [
            { 
                id: 'ORD-9921', 
                customer: 'Alex Hunter', 
                avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Felix', 
                items: ['Elden Ring: DLC'], 
                total: 39.99, 
                status: 'processing', 
                date: '20 mins ago', 
                priority: false,
                shipping: 'Standard',
                note: '',
                timeline: [
                    { step: 'Order Placed', time: '10:00 AM', done: true, staff: null }, 
                    { step: 'Payment Verified', time: '10:05 AM', done: true, staff: { id: 'EMP-001' } }, 
                    { step: 'Packing', time: 'In Progress', done: false, active: true, staff: { id: 'EMP-088' } }, 
                    { step: 'Handover to Carrier', time: '', done: false, staff: null },
                    { step: 'Delivered', time: '', done: false, staff: null }
                ]
            },
            { 
                id: 'ORD-9922', 
                customer: 'Sarah Connor', 
                avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=Sarah', 
                items: ['PS5 Pro Console'], 
                total: 699.00, 
                status: 'pending', 
                date: '5 mins ago', 
                priority: true, // ALREADY URGENT
                shipping: 'Express',
                note: 'Gift for son birthday',
                timeline: [
                    { step: 'Order Placed', time: '11:50 AM', done: true, staff: null },
                    { step: 'Payment Verification', time: 'Pending', done: false, active: true, staff: { id: 'EMP-001' } },
                    { step: 'Packing', time: '', done: false, staff: { id: 'EMP-088' } },
                    { step: 'Delivered', time: '', done: false, staff: null }
                ]
            }
        ];

        let currentFilter = 'all';

        // --- HELPER FUNCTIONS ---
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) document.documentElement.classList.add('dark');

        // Auto-detect theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (e.matches) document.documentElement.classList.add('dark');
            else document.documentElement.classList.remove('dark');
        });

        const getStatusConfig = (status) => {
            const configs = {
                pending: { color: 'text-yellow-500', bg: 'bg-yellow-50 dark:bg-yellow-900/20', icon: 'fa-clock' },
                processing: { color: 'text-blue-500', bg: 'bg-blue-50 dark:bg-blue-900/20', icon: 'fa-cog fa-spin' },
                shipped: { color: 'text-purple-500', bg: 'bg-purple-50 dark:bg-purple-900/20', icon: 'fa-shipping-fast' },
                delivered: { color: 'text-green-500', bg: 'bg-green-50 dark:bg-green-900/20', icon: 'fa-check-circle' }
            };
            return configs[status] || configs['pending'];
        };

        // --- REAL-TIME SIMULATION LOGIC ---
        
        // 1. Progress existing orders
        function progressOrders() {
            let updatedCount = 0;
            orders.forEach(order => {
                // Find current active step
                const activeIndex = order.timeline.findIndex(step => step.active);
                
                // If there is an active step and it's not the last one
                if (activeIndex !== -1 && activeIndex < order.timeline.length - 1) {
                    // Complete current step
                    order.timeline[activeIndex].done = true;
                    order.timeline[activeIndex].active = false;
                    order.timeline[activeIndex].time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    
                    // Activate next step
                    const nextIndex = activeIndex + 1;
                    order.timeline[nextIndex].active = true;
                    order.timeline[nextIndex].time = 'In Progress';
                    
                    // Update Order Status based on step
                    const stepName = order.timeline[nextIndex].step;
                    if (stepName === 'Payment Verification') order.status = 'pending';
                    else if (stepName === 'Packing') order.status = 'processing';
                    else if (stepName === 'Handover to Carrier') order.status = 'shipped';
                    else if (stepName === 'Delivered') {
                        order.status = 'delivered';
                        order.timeline[nextIndex].done = true; // Auto complete delivery for sim
                        order.timeline[nextIndex].active = false;
                        order.timeline[nextIndex].time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    }
                    
                    updatedCount++;
                }
            });
            
            if (updatedCount > 0) {
                showToast(`${updatedCount} orders progressed to next stage`, 'info');
                renderList();
                // If sheet is open, refresh it
                const sheet = document.getElementById('action-sheet');
                if (sheet.classList.contains('sheet-active')) {
                    // Find ID from title
                    const title = document.querySelector('#sheet-content h2');
                    if (title) {
                        const id = title.innerText.replace('#', '');
                        openSheet(id);
                    }
                }
            }
        }

        // 2. Generate new orders
        function generateNewOrders() {
            const newOrdersCount = 5;
            for (let i = 0; i < newOrdersCount; i++) {
                const randomCustomer = customerDb[Math.floor(Math.random() * customerDb.length)];
                const randomProduct = productDb[Math.floor(Math.random() * productDb.length)];
                const orderId = 'ORD-' + (Math.floor(Math.random() * 9000) + 1000);
                
                const newOrder = {
                    id: orderId,
                    customer: randomCustomer.name,
                    avatar: `https://api.dicebear.com/7.x/avataaars/svg?seed=${randomCustomer.seed}`,
                    items: [randomProduct.name],
                    total: randomProduct.price,
                    status: 'pending',
                    date: 'Just now',
                    priority: Math.random() < 0.2, // 20% chance of urgent
                    shipping: Math.random() < 0.3 ? 'Express' : 'Standard',
                    note: '',
                    timeline: [
                        { step: 'Order Placed', time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}), done: true, staff: null },
                        { step: 'Payment Verification', time: 'In Progress', done: false, active: true, staff: { id: 'EMP-001' } },
                        { step: 'Packing', time: '', done: false, staff: { id: 'EMP-088' } },
                        { step: 'Handover to Carrier', time: '', done: false, staff: null },
                        { step: 'Delivered', time: '', done: false, staff: null }
                    ]
                };
                
                orders.unshift(newOrder); // Add to top
            }
            
            showToast(`${newOrdersCount} new orders received!`, 'success');
            renderList();
        }

        // 3. Simulation Loop (Every 5 minutes)
        const SIMULATION_INTERVAL = 5 * 60 * 1000; 
        
        setInterval(() => {
            progressOrders();
            generateNewOrders();
        }, SIMULATION_INTERVAL);

        // --- RENDER LIST ---
        const listContainer = document.getElementById('order-list-container');
        const searchInput = document.getElementById('search-input');

        function renderList() {
            const searchTerm = searchInput.value.toLowerCase();
            const filtered = orders.filter(o => {
                const matchSearch = o.id.toLowerCase().includes(searchTerm) || o.customer.toLowerCase().includes(searchTerm);
                const matchFilter = currentFilter === 'all' ? true : (currentFilter === 'urgent' ? o.priority : o.status === currentFilter);
                return matchSearch && matchFilter;
            });

            if (filtered.length === 0) {
                listContainer.innerHTML = `<div class="flex flex-col items-center justify-center mt-20 opacity-50"><i class="fas fa-search text-3xl mb-2 dark:text-white"></i><p class="text-sm dark:text-white">No orders found.</p></div>`;
                return;
            }

            listContainer.innerHTML = filtered.map(o => {
                const style = getStatusConfig(o.status);
                // Urgent Styles
                const borderClass = o.priority ? 'border-red-500 shadow-urgent' : 'border-slate-100 dark:border-dark-border shadow-sm';
                const bgClass = o.priority ? 'bg-red-50/10 dark:bg-red-900/10' : 'bg-white dark:bg-dark-surface';
                
                return `
                <div onclick="openSheet('${o.id}')" class="group relative p-4 mb-3 rounded-2xl border ${borderClass} ${bgClass} active:scale-[0.98] transition-all cursor-pointer overflow-hidden">
                    
                    ${o.priority ? `<div class="absolute right-0 top-0 bg-red-500 text-white text-[9px] px-2 py-1 rounded-bl-xl font-bold z-10 animate-pulse"><i class="fas fa-fire"></i> URGENT</div>` : ''}

                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3">
                            <img src="${o.avatar}" class="w-10 h-10 rounded-full bg-slate-100 object-cover">
                            <div>
                                <h4 class="font-bold text-sm dark:text-white leading-none mb-1">${o.customer}</h4>
                                <p class="text-[10px] text-slate-400 font-mono">${o.id}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-full ${style.bg} ${style.color} uppercase tracking-wide flex items-center gap-1 mt-1">
                            <i class="fas ${style.icon}"></i> ${o.status}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center border-t border-dashed border-slate-200 dark:border-white/10 pt-2">
                         <div class="flex flex-col">
                            <span class="text-[10px] text-slate-400">Shipping</span>
                            <span class="text-xs font-bold ${o.shipping === 'Express' ? 'text-orange-500' : 'dark:text-white'}">${o.shipping}</span>
                         </div>
                         <span class="text-sm font-mono font-bold dark:text-white">$${o.total.toFixed(2)}</span>
                    </div>
                </div>
            `}).join('');
        }

        function setFilter(type, btn) {
            currentFilter = type;
            document.querySelectorAll('.filter-btn').forEach(b => b.className = 'filter-btn px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-white dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400 transition-all');
            if(btn) btn.className = 'filter-btn active px-4 py-1.5 rounded-full text-xs font-bold border whitespace-nowrap bg-brand text-white border-brand transition-all shadow-glow';
            renderList();
        }

        function filterUrgent() {
            setFilter('urgent', null);
        }

        searchInput.addEventListener('input', renderList);

        // --- BOTTOM SHEET & ADMIN ACTIONS ---
        const overlay = document.getElementById('action-sheet-overlay');
        const sheet = document.getElementById('action-sheet');
        const sheetContent = document.getElementById('sheet-content');

        function openSheet(id) {
            const o = orders.find(ord => ord.id === id);
            
            // Build Timeline with "Nudge" actions
            const timelineHtml = o.timeline.map((step, index) => {
                const isLast = index === o.timeline.length - 1;
                const staffInfo = step.staff ? staffDb[step.staff.id] : null;
                const isActive = step.active;
                
                const dotColor = step.done ? 'bg-brand' : (isActive ? 'bg-yellow-400 animate-pulse' : 'bg-slate-200 dark:bg-slate-700');
                const textColor = step.done || isActive ? 'text-slate-800 dark:text-white' : 'text-slate-400';
                
                return `
                <div class="relative pl-8 pb-8 ${isLast ? '' : 'border-l-2 border-slate-100 dark:border-white/5 ml-1.5'}">
                    <div class="absolute -left-[5px] top-0 w-3.5 h-3.5 rounded-full ${dotColor} border-2 border-white dark:border-dark-surface z-10"></div>
                    
                    <div class="flex justify-between items-start -mt-1.5">
                        <div>
                            <h4 class="text-sm font-bold ${textColor}">${step.step}</h4>
                            <p class="text-[10px] text-slate-400">${step.time}</p>
                        </div>
                    </div>

                    ${staffInfo ? `
                        <div class="mt-2 flex items-center justify-between bg-slate-50 dark:bg-white/5 p-2 rounded-xl border border-slate-100 dark:border-white/5">
                            <div class="flex items-center gap-2">
                                <img src="${staffInfo.avatar}" class="w-6 h-6 rounded-full bg-white">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold dark:text-slate-200">${staffInfo.name}</span>
                                    <span class="text-[8px] text-slate-400 font-mono">ID: ${step.staff.id}</span>
                                </div>
                            </div>
                            ${isActive ? `
                                <button onclick="notifyStaff('${step.staff.id}')" class="text-[10px] bg-red-100 dark:bg-red-900/20 text-red-600 px-2 py-1 rounded-lg font-bold border border-red-200 dark:border-red-900/30 active:scale-95 transition-transform">
                                    <i class="fas fa-bell"></i> Nudge
                                </button>
                            ` : ''}
                        </div>
                    ` : ''}
                </div>
                `;
            }).join('');

            sheetContent.innerHTML = `
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="flex items-center gap-2">
                             <h2 class="text-xl font-bold dark:text-white">#${o.id}</h2>
                             ${o.priority ? '<span class="text-[9px] bg-red-500 text-white px-1.5 py-0.5 rounded font-bold uppercase"><i class="fas fa-fire"></i> Urgent</span>' : ''}
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Placed: ${o.date}</p>
                    </div>
                    <div class="text-right">
                         <button onclick="togglePriority('${o.id}')" class="text-xs font-bold px-3 py-1.5 rounded-lg border transition-colors ${o.priority ? 'bg-red-50 text-red-500 border-red-200' : 'bg-slate-100 text-slate-500 border-slate-200'}">
                            ${o.priority ? 'Remove Priority' : '🔥 Mark Urgent'}
                        </button>
                    </div>
                </div>

                <div class="mb-6 bg-gradient-to-r from-slate-900 to-slate-800 rounded-xl p-4 text-white shadow-lg relative overflow-hidden">
                    <div class="absolute right-0 top-0 opacity-10 p-2"><i class="fas fa-tachometer-alt text-6xl"></i></div>
                    <h3 class="text-xs font-bold text-slate-300 uppercase tracking-widest mb-3">Admin Speed Controls</h3>
                    
                    <div class="grid grid-cols-2 gap-3 relative z-10">
                        <div class="bg-white/10 rounded-lg p-2 backdrop-blur-sm border border-white/10">
                            <label class="text-[9px] text-slate-300 block mb-1">Shipping Method</label>
                            <select onchange="changeShipping('${o.id}', this.value)" class="w-full bg-black/30 border border-white/10 rounded text-xs font-bold text-white p-1 focus:outline-none focus:border-brand">
                                <option value="Standard" ${o.shipping === 'Standard' ? 'selected' : ''}>Standard (3-5d)</option>
                                <option value="Express" ${o.shipping === 'Express' ? 'selected' : ''}>Express (1-2d)</option>
                                <option value="SameDay" ${o.shipping === 'SameDay' ? 'selected' : ''}>🚀 Same Day</option>
                            </select>
                        </div>
                        
                        <button onclick="addNote('${o.id}')" class="bg-white/10 rounded-lg p-2 backdrop-blur-sm border border-white/10 text-left hover:bg-white/20 transition-colors">
                            <label class="text-[9px] text-slate-300 block mb-1">Internal Note</label>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold truncate">${o.note || 'Add instruction...'}</span>
                                <i class="fas fa-pen text-[10px]"></i>
                            </div>
                        </button>
                    </div>
                </div>

                <div class="mb-2">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 pl-1">Execution Status</h3>
                    ${timelineHtml}
                </div>

                 <button onclick="closeSheet()" class="w-full py-4 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 font-bold text-sm">Close</button>
            `;

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

        // --- ACTIONS ---
        function togglePriority(id) {
            const order = orders.find(o => o.id === id);
            order.priority = !order.priority;
            
            if(order.priority) {
                showToast('Order marked as URGENT! 🔥', 'error');
            } else {
                showToast('Priority removed', 'success');
            }
            renderList();
            openSheet(id); // Re-render sheet to show updated state
        }

        function notifyStaff(staffId) {
            showToast(`Notification sent to ${staffId} 🔔`, 'info');
            // Mock API call to push notification system
        }

        function changeShipping(id, method) {
            const order = orders.find(o => o.id === id);
            order.shipping = method;
            showToast(`Logistics updated to ${method} ✈️`, 'success');
            renderList(); // Update list view text
        }

        function addNote(id) {
            const order = orders.find(o => o.id === id);
            const newNote = prompt("Add instruction for warehouse/staff:", order.note);
            if(newNote !== null) {
                order.note = newNote;
                showToast('Instruction saved 📝');
                openSheet(id);
            }
        }

        function showToast(msg, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            let color = 'bg-brand';
            let icon = 'fa-check';
            if(type === 'error') { color = 'bg-red-500'; icon = 'fa-fire'; }
            if(type === 'info') { color = 'bg-blue-500'; icon = 'fa-bell'; }

            toast.className = `flex items-center gap-3 p-3 rounded-full bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-glow pointer-events-auto transform transition-all duration-300 translate-y-10 opacity-0`;
            toast.innerHTML = `<div class="w-8 h-8 rounded-full ${color} text-white flex items-center justify-center shrink-0 shadow-md"><i class="fas ${icon} text-xs"></i></div><span class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate pr-2">${msg}</span>`;
            container.appendChild(toast);
            requestAnimationFrame(() => toast.classList.remove('translate-y-10', 'opacity-0'));
            setTimeout(() => { toast.classList.add('opacity-0', '-translate-y-2'); setTimeout(() => toast.remove(), 300); }, 3000);
        }

        document.addEventListener('DOMContentLoaded', renderList);
    </script>
</body>
</html>