<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#f8fafc">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#020618">

    <title>Notifications Category</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">

    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                fontFamily: { sans: ['"Inter"', 'sans-serif'], mono: ['"Rajdhani"', 'monospace'] },
                colors: { 
                    brand: '#ec003f', 
                    dark: { 
                        bg: '#020618',      
                        surface: '#0f172a', 
                        border: '#1e293b' 
                    } 
                },
                borderRadius: {
                    DEFAULT: '0.375rem', // 6px
                },
                boxShadow: {
                    'card': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                }
            }
        }
      }
    </script>
    <style>
        body { -webkit-tap-highlight-color: transparent; }
        /* Custom style to match the reference image's large icon style */
        .category-icon-box {
            background: linear-gradient(145deg, #1e293b, #0f172a);
        }
        .dark .category-icon-box {
             background: linear-gradient(145deg, #334155, #1e293b);
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 dark:bg-[#020618] dark:text-[#f8fafc] transition-colors duration-300 min-h-screen font-sans">

    <header class="sticky top-0 z-40 bg-[#f8fafc]/90 dark:bg-[#020618]/90 backdrop-blur-md border-b border-slate-200 dark:border-dark-border px-4 py-4 flex items-center justify-between">
        <h1 class="text-lg font-bold uppercase tracking-wider">Notifications</h1>
        <div class="flex gap-2">
            <button onclick="clearAllNotifications()" class="w-10 h-10 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors" title="Clear All">
                <i class="fas fa-trash-alt text-lg"></i>
            </button>
            <button onclick="markAllRead()" class="w-10 h-10 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 flex items-center justify-center text-slate-400 hover:text-brand dark:hover:text-brand transition-colors" title="Mark all as read">
                <i class="fas fa-check-circle text-xl"></i>
            </button>
        </div>
    </header>

    <main class="max-w-lg mx-auto px-4 py-6 space-y-6">

        <!-- Section: Today -->
        <div>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 px-1">Today</h3>
            <div id="today-list" class="space-y-3">
                
                <!-- Dynamic notifications will be inserted here -->

                <!-- Promo: Game Cards -->
                <div onclick="openDetailsModal(this);" class="group relative bg-white dark:bg-dark-surface p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-dark-border cursor-pointer hover:shadow-md transition-all active:scale-[0.99]" data-details='{"title":"Game Card Flash Sale","description":"Get 20% off on all game cards today! Use code FLASH20 at checkout. Valid until midnight.","cta":"userStore.php"}'>
                    <button onclick="deleteStaticNotification(this, event)" class="absolute top-2 right-2 w-6 h-6 flex items-center justify-center rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-red-500 transition-colors z-20" title="Delete">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    <div class="flex gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <i class="fas fa-bolt text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1 pr-8">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate">Flash Sale: 20% Off</h4>
                                <span class="text-[10px] text-slate-400 whitespace-nowrap mt-0.5">08:15 AM</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
                                Limited time offer! Get 20% discount on all game card purchases.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Section: Yesterday -->
        <div>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 px-1">Yesterday</h3>
            <div class="space-y-3">
                
                <!-- Promo: Accessories -->
                <div onclick="openDetailsModal(this);" class="group relative bg-white dark:bg-dark-surface p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-dark-border cursor-pointer hover:shadow-md transition-all active:scale-[0.99]" data-details='{"title":"New Gear Arrived","description":"Check out our latest collection of mechanical keyboards and gaming mice. Upgrade your setup today!","cta":"userStore.php"}'>
                    <button onclick="deleteStaticNotification(this, event)" class="absolute top-2 right-2 w-6 h-6 flex items-center justify-center rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-red-500 transition-colors z-20" title="Delete">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    <div class="flex gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <i class="fas fa-gamepad text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1 pr-8">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate">New Gaming Gear</h4>
                                <span class="text-[10px] text-slate-400 whitespace-nowrap mt-0.5">Yesterday</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
                                Upgrade your setup with our new arrivals. Free shipping on orders over $50.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </main>

    <!-- Invoice Modal -->
    <div id="invoiceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden">
        <div class="bg-white dark:bg-dark-surface w-full max-w-xl rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-dark-border flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Order Invoice</h3>
                <button onclick="closeInvoiceModal();" class="text-slate-500 hover:text-slate-800 dark:hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="invoiceContent" class="p-6 text-sm text-slate-700 dark:text-slate-200">
                <!-- Filled dynamically -->
            </div>
            <div class="px-6 py-4 border-t border-slate-200 dark:border-dark-border flex justify-end gap-2">
                <button onclick="closeInvoiceModal();" class="px-4 py-2 bg-brand text-white rounded">Close</button>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden">
        <div class="bg-white dark:bg-dark-surface w-full max-w-md rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-dark-border flex items-center justify-between">
                <h3 id="detailsTitle" class="text-lg font-bold text-slate-900 dark:text-white">Details</h3>
                <button onclick="closeDetailsModal();" class="text-slate-500 hover:text-slate-800 dark:hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="detailsContent" class="p-6 text-sm text-slate-700 dark:text-slate-200">
                <!-- Filled dynamically -->
            </div>
            <div class="px-6 py-4 border-t border-slate-200 dark:border-dark-border flex justify-end gap-2">
                <a id="detailsCta" href="#" class="px-4 py-2 bg-brand text-white rounded">View Deal</a>
                <button onclick="closeDetailsModal();" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded">Close</button>
            </div>
        </div>
    </div>

    <script>
        // --- THEME SETUP (Keep this for consistency) ---
        const themeQuery = window.matchMedia('(prefers-color-scheme: dark)');
        function handleThemeChange(e) { if(e.matches) document.documentElement.classList.add('dark'); else document.documentElement.classList.remove('dark'); }
        themeQuery.addListener(handleThemeChange); handleThemeChange(themeQuery);
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) document.documentElement.classList.add('dark');

        // --- NOTIFICATION LOADER ---
        document.addEventListener('DOMContentLoaded', () => {
            loadNotifications();
        });

        function loadNotifications() {
            const key = 'pp_notifications';
            let list = [];
            try { list = JSON.parse(localStorage.getItem(key) || '[]'); } catch(e) { list = []; }

            const container = document.getElementById('today-list');
            if (!container) return;

            // Remove existing dynamic notifications to prevent duplicates if called multiple times
            const existing = container.querySelectorAll('.dynamic-notif');
            existing.forEach(e => e.remove());
            
            list.forEach(notif => {
                // Validate data
                if (!notif || !notif.id) return;
                const title = notif.title || 'Notification';
                const message = notif.message || 'No details available.';
                
                let timeStr = 'Just now';
                try {
                    const date = new Date(notif.date);
                    if (!isNaN(date.getTime())) {
                        timeStr = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    }
                } catch(e) {}

                // Create element
                const div = document.createElement('div');
                div.className = "dynamic-notif group relative bg-white dark:bg-dark-surface p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-dark-border cursor-pointer hover:shadow-md transition-all active:scale-[0.99] mb-3";
                
                // Click to open invoice AND mark as read
                div.onclick = function() { 
                    markAsRead(notif.id, this);
                    openInvoiceModal(this); 
                };
                
                // Store invoice data safely
                if (notif.invoice) {
                    div.setAttribute('data-invoice', JSON.stringify(notif.invoice));
                }

                // Check read status
                const isRead = notif.read === true;
                const dotHtml = isRead ? '' : '<div class="unread-dot absolute top-4 right-12 w-2.5 h-2.5 bg-brand rounded-full ring-2 ring-white dark:ring-dark-surface"></div>';

                div.innerHTML = `
                    <button onclick="deleteNotification('${notif.id}', this, event)" class="absolute top-2 right-2 w-6 h-6 flex items-center justify-center rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-red-500 transition-colors z-20" title="Delete">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    
                    ${dotHtml}

                    <div class="flex gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400">
                            <i class="fas fa-receipt text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col mb-1">
                                <div class="flex justify-between items-start pr-8">
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate max-w-[85%]">${title}</h4>
                                    <span class="text-[10px] text-slate-400 whitespace-nowrap mt-0.5">${timeStr}</span>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
                                ${message}
                            </p>
                        </div>
                    </div>
                `;
                
                // Insert at top
                container.prepend(div);
            });
        }

        function deleteNotification(id, el, event) {
            event.stopPropagation();
            // Fade out effect
            el.closest('.group').style.opacity = '0';
            el.closest('.group').style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                el.closest('.group').remove();
                // Remove from LocalStorage
                const key = 'pp_notifications';
                let list = [];
                try { list = JSON.parse(localStorage.getItem(key) || '[]'); } catch(e) { list = []; }
                list = list.filter(n => n.id !== id);
                localStorage.setItem(key, JSON.stringify(list));
            }, 200);
        }

        function deleteStaticNotification(el, event) {
            event.stopPropagation();
            el.closest('.group').style.opacity = '0';
            setTimeout(() => el.closest('.group').remove(), 200);
        }

        function clearAllNotifications() {
            if(!confirm('Clear all notifications?')) return;
            localStorage.removeItem('pp_notifications');
            loadNotifications(); // Reloads list (which will be empty)
            // Also remove static ones if desired, or just reload page
            // For now, let's just reload the dynamic part
        }

        function markAsRead(id, el) {
            // Remove dot visually
            const dot = el.querySelector('.unread-dot');
            if(dot) dot.remove();

            // Update storage
            const key = 'pp_notifications';
            let list = [];
            try { list = JSON.parse(localStorage.getItem(key) || '[]'); } catch(e) { list = []; }
            
            const index = list.findIndex(n => n.id === id);
            if(index !== -1) {
                list[index].read = true;
                localStorage.setItem(key, JSON.stringify(list));
            }
        }

        function markAllRead() {
            // Update UI
            document.querySelectorAll('.unread-dot').forEach(dot => dot.remove());
            
            // Update LocalStorage
            const key = 'pp_notifications';
            let list = [];
            try { list = JSON.parse(localStorage.getItem(key) || '[]'); } catch(e) { list = []; }
            
            list = list.map(n => ({ ...n, read: true }));
            localStorage.setItem(key, JSON.stringify(list));
        }

        // --- INVOICE / MODAL HANDLERS ---
        function formatCurrency(v){ 
            if(typeof v === 'string' && (v.includes('$') || v.includes('VND'))) return v;
            return v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' VND'; 
        }

        function openInvoiceModal(el){
            const modal = document.getElementById('invoiceModal');
            const content = document.getElementById('invoiceContent');
            const data = el.getAttribute('data-invoice');
            try{
                const invoice = JSON.parse(data);
                renderInvoice(invoice, content);
            }catch(err){
                console.error(err);
                content.innerHTML = '<p class="text-red-500">Cannot load invoice.</p>';
            }
            modal.classList.remove('hidden');
        }

        function closeInvoiceModal(){
            document.getElementById('invoiceModal').classList.add('hidden');
        }

        function renderInvoice(invoice, container){
            let html = '';
            html += '<div class="mb-4">';
            html += '<div class="flex justify-between items-start">';
            html += '<div><div class="text-sm text-slate-500">Order ID</div><div class="font-bold text-lg">'+invoice.id+'</div></div>';
            html += '<div class="text-sm text-slate-500">'+invoice.date+'</div>';
            html += '</div></div>';

            html += '<div class="mb-4">';
            html += '<table class="w-full text-sm">';
            html += '<thead><tr class="text-left text-slate-500"><th>Item</th><th class="text-right">Qty</th><th class="text-right">Price</th></tr></thead>';
            html += '<tbody class="divide-y divide-slate-100 dark:divide-slate-700">';
            
            if(invoice.items && Array.isArray(invoice.items)){
                invoice.items.forEach(item => {
                    const qty = item.qty || 1;
                    const price = formatCurrency(item.price);
                    html += '<tr class="py-2"><td class="py-2">'+item.name+'</td><td class="py-2 text-right">'+qty+'</td><td class="py-2 text-right">'+price+'</td></tr>';
                });
            }
            
            html += '</tbody>';
            html += '</table>';
            html += '</div>';

            html += '<div class="flex justify-end items-center gap-4">';
            html += '<div class="text-slate-500">Total</div><div class="text-lg font-bold">'+formatCurrency(invoice.total)+'</div>';
            html += '</div>';

            container.innerHTML = html;
        }

        // --- DETAILS MODAL HANDLERS ---
        function openDetailsModal(el){
            const modal = document.getElementById('detailsModal');
            const titleEl = document.getElementById('detailsTitle');
            const contentEl = document.getElementById('detailsContent');
            const ctaEl = document.getElementById('detailsCta');
            
            const data = el.getAttribute('data-details');
            try {
                const details = JSON.parse(data);
                titleEl.textContent = details.title;
                contentEl.textContent = details.description;
                if(details.cta) {
                    ctaEl.href = details.cta;
                    ctaEl.classList.remove('hidden');
                } else {
                    ctaEl.classList.add('hidden');
                }
            } catch(e) {
                contentEl.innerHTML = '<p class="text-red-500">Cannot load details.</p>';
            }
            modal.classList.remove('hidden');
        }

        function closeDetailsModal(){
            document.getElementById('detailsModal').classList.add('hidden');
        }

    </script>
</body>
</html>