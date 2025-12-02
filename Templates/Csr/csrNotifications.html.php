<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Notifications | ProPlay Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: '#ec003f', dark: { bg: '#020618', surface: '#0f172a' } }
                }
            }
        }
    </script>
    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .slide-in { animation: slideIn 0.3s ease-out forwards; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .fade-out { animation: fadeOut 0.2s ease-out forwards; }
        @keyframes fadeOut { to { opacity: 0; transform: scale(0.95); } }
    </style>
</head>
<body class="bg-slate-50 dark:bg-dark-bg text-slate-900 dark:text-white min-h-screen pb-20 transition-colors duration-300">

    <!-- Header -->
    <div class="sticky top-0 z-40 bg-white/80 dark:bg-dark-bg/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-md mx-auto px-4 h-14 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button onclick="history.back()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                    <i class="fas fa-arrow-left text-slate-500 dark:text-slate-400"></i>
                </button>
                <h1 class="font-bold text-lg">Notifications</h1>
            </div>
            <div class="flex gap-1">
                <button onclick="app.markAllRead()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 dark:hover:bg-white/10 text-slate-500 dark:text-slate-400 hover:text-brand transition-colors" title="Mark all read">
                    <i class="fas fa-check-double"></i>
                </button>
                <button onclick="app.clearAll()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 dark:hover:bg-white/10 text-slate-500 dark:text-slate-400 hover:text-red-500 transition-colors" title="Clear all">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
        
        <!-- Filter Tabs -->
        <div class="max-w-md mx-auto px-4 pb-2 flex gap-4 overflow-x-auto hide-scrollbar">
            <button onclick="app.setFilter('all')" id="filter-all" class="filter-btn px-4 py-1.5 rounded-full text-xs font-bold bg-brand text-white transition-all whitespace-nowrap">All</button>
            <button onclick="app.setFilter('orders')" id="filter-orders" class="filter-btn px-4 py-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all whitespace-nowrap">Orders</button>
            <button onclick="app.setFilter('messages')" id="filter-messages" class="filter-btn px-4 py-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all whitespace-nowrap">Messages</button>
            <button onclick="app.setFilter('system')" id="filter-system" class="filter-btn px-4 py-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all whitespace-nowrap">System</button>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-md mx-auto px-4 py-4 space-y-6 min-h-[60vh]" id="notification-container">
        <!-- Injected via JS -->
    </main>

    <!-- Empty State Template (Hidden) -->
    <template id="empty-state">
        <div class="flex flex-col items-center justify-center py-20 text-center opacity-0 slide-in" style="animation-delay: 0.1s">
            <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                <i class="far fa-bell-slash text-3xl text-slate-300 dark:text-slate-600"></i>
            </div>
            <h3 class="font-bold text-slate-900 dark:text-white mb-1">No new activities</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 max-w-[200px]">You're all caught up with user interactions.</p>
        </div>
    </template>

    <!-- Invoice Modal -->
    <div id="invoiceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden backdrop-blur-sm transition-all duration-300 opacity-0 pointer-events-none">
        <div class="bg-white dark:bg-dark-surface w-full max-w-md mx-4 rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-all duration-300">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900/50">
                <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fas fa-receipt text-brand"></i> Order Details
                </h3>
                <button onclick="app.closeModal('invoiceModal')" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="invoiceContent" class="p-6 text-sm text-slate-700 dark:text-slate-300 max-h-[60vh] overflow-y-auto">
                <!-- Filled dynamically -->
            </div>
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2 bg-slate-50 dark:bg-slate-900/50">
                <button class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Reject</button>
                <button onclick="app.approveOrder()" class="px-6 py-2 bg-brand text-white font-bold rounded-lg hover:brightness-110 transition-all shadow-lg shadow-brand/20">Approve Order</button>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden backdrop-blur-sm transition-all duration-300 opacity-0 pointer-events-none">
        <div class="bg-white dark:bg-dark-surface w-full max-w-md mx-4 rounded-2xl shadow-2xl overflow-hidden transform scale-95 transition-all duration-300">
            <div class="relative h-32 bg-gradient-to-r from-blue-600 to-cyan-500 flex items-center justify-center">
                <i class="fas fa-info-circle text-5xl text-white/20 absolute"></i>
                <button onclick="app.closeModal('detailsModal')" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-black/20 text-white hover:bg-black/40 transition-colors backdrop-blur-md">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="px-6 py-6 -mt-6 relative">
                <div class="bg-white dark:bg-dark-surface rounded-xl p-4 shadow-sm border border-slate-100 dark:border-slate-800">
                    <h3 id="detailsTitle" class="text-lg font-bold text-slate-900 dark:text-white mb-2">Title</h3>
                    <p id="detailsContent" class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">Description</p>
                </div>
                <div class="mt-6 flex gap-3">
                    <button onclick="app.closeModal('detailsModal')" class="flex-1 py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Close</button>
                    <a id="detailsCta" href="#" class="flex-1 py-3 bg-brand text-white font-bold rounded-xl text-center hover:brightness-110 transition-all shadow-lg shadow-brand/20">View Action</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const app = {
            filter: 'all',
            notifications: [],
            
            // Admin System Alerts
            systemAlerts: [
                {
                    id: 'sys-1',
                    type: 'system',
                    title: 'System Maintenance',
                    message: 'Scheduled maintenance for database optimization at 02:00 AM UTC.',
                    time: '10:00 AM',
                    date: new Date().toISOString(),
                    read: false,
                    icon: 'server',
                    color: 'slate',
                    details: {
                        title: "Maintenance Schedule",
                        description: "The system will undergo routine maintenance. Expected downtime: 30 mins.",
                        cta: null
                    }
                },
                {
                    id: 'sys-2',
                    type: 'system',
                    title: 'High Traffic Warning',
                    message: 'Server load is currently at 85%. Monitor resources closely.',
                    time: 'Yesterday',
                    date: new Date(Date.now() - 86400000).toISOString(),
                    read: true,
                    icon: 'chart-line',
                    color: 'amber',
                    details: {
                        title: "Resource Monitor",
                        description: "Traffic spike detected from region: Asia-Pacific.",
                        cta: null
                    }
                }
            ],

            init() {
                // Theme Check
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                }
                
                this.loadNotifications();
                this.render();
                
                // Listen for storage changes (real-time updates from user actions)
                window.addEventListener('storage', (e) => {
                    if (e.key === 'pp_orders' || e.key === 'proplay_social_posts') {
                        this.loadNotifications();
                        this.render();
                    }
                });
            },

            loadNotifications() {
                this.notifications = [];

                // 1. Load Orders from User Interactions
                const ordersJson = localStorage.getItem('pp_orders');
                if (ordersJson) {
                    try {
                        const orders = JSON.parse(ordersJson);
                        const orderNotifs = orders.map(order => ({
                            id: `notif-${order.id}`,
                            type: 'order',
                            title: `New Order: ${order.id}`,
                            message: `${order.items[0].name} ${order.items.length > 1 ? `+${order.items.length - 1} more` : ''} - ${order.total}`,
                            time: order.date, // Using date string from order
                            date: this.parseDate(order.date), // Convert to Date object for sorting
                            read: false, // In a real app, track read state separately
                            icon: 'shopping-bag',
                            color: 'green',
                            invoice: order // Pass full order object
                        }));
                        this.notifications = [...this.notifications, ...orderNotifs];
                    } catch (e) { console.error("Error parsing orders", e); }
                }

                // 2. Load Social Interactions (Comments/Posts)
                const postsJson = localStorage.getItem('proplay_social_posts');
                if (postsJson) {
                    try {
                        const posts = JSON.parse(postsJson);
                        // Filter for posts that are NOT mine (user posts) or have comments
                        posts.forEach(post => {
                            if (!post.isAdmin) { // User post
                                this.notifications.push({
                                    id: `post-${post.id}`,
                                    type: 'message',
                                    title: `New Post from ${post.user}`,
                                    message: post.content,
                                    time: post.time,
                                    date: new Date().toISOString(), // Mock date
                                    read: false,
                                    icon: 'comment-alt',
                                    color: 'blue',
                                    details: {
                                        title: `Post by ${post.user}`,
                                        description: post.content,
                                        cta: '../Csr/csrSocial.html.php'
                                    }
                                });
                            }
                            // Comments on posts
                            if (post.comments && post.comments.length > 0) {
                                post.comments.forEach((comment, idx) => {
                                    this.notifications.push({
                                        id: `comment-${post.id}-${idx}`,
                                        type: 'message',
                                        title: `New Comment from ${comment.user}`,
                                        message: `Commented on post: "${comment.text}"`,
                                        time: 'Just now',
                                        date: new Date().toISOString(),
                                        read: false,
                                        icon: 'reply',
                                        color: 'purple',
                                        details: {
                                            title: `Comment by ${comment.user}`,
                                            description: comment.text,
                                            cta: '../Csr/csrSocial.html.php'
                                        }
                                    });
                                });
                            }
                        });
                    } catch (e) { console.error("Error parsing posts", e); }
                }

                // 3. Add System Alerts
                this.notifications = [...this.notifications, ...this.systemAlerts];

                // Sort by Date (Newest First)
                this.notifications.sort((a, b) => new Date(b.date) - new Date(a.date));
            },

            parseDate(dateStr) {
                // Helper to parse "Nov 30, 2025" to ISO for sorting
                try {
                    return new Date(dateStr).toISOString();
                } catch (e) {
                    return new Date().toISOString();
                }
            },

            setFilter(filter) {
                this.filter = filter;
                
                // Update UI Tabs
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('bg-brand', 'text-white');
                    btn.classList.add('bg-slate-100', 'dark:bg-slate-800', 'text-slate-500', 'dark:text-slate-400');
                });
                
                const activeBtn = document.getElementById(`filter-${filter}`);
                activeBtn.classList.remove('bg-slate-100', 'dark:bg-slate-800', 'text-slate-500', 'dark:text-slate-400');
                activeBtn.classList.add('bg-brand', 'text-white');
                
                this.render();
            },

            getFilteredNotifications() {
                if (this.filter === 'all') return this.notifications;
                if (this.filter === 'orders') return this.notifications.filter(n => n.type === 'order');
                if (this.filter === 'messages') return this.notifications.filter(n => n.type === 'message');
                if (this.filter === 'system') return this.notifications.filter(n => n.type === 'system');
                return this.notifications;
            },

            render() {
                const container = document.getElementById('notification-container');
                container.innerHTML = '';
                
                const list = this.getFilteredNotifications();
                
                if (list.length === 0) {
                    const emptyTpl = document.getElementById('empty-state');
                    container.appendChild(emptyTpl.content.cloneNode(true));
                    return;
                }

                // Group by Date (Today, Yesterday, Older)
                const groups = { today: [], yesterday: [], older: [] };
                const now = new Date();
                const today = new Date(now.getFullYear(), now.getMonth(), now.getDate()).getTime();
                const yesterday = new Date(today - 86400000).getTime();

                list.forEach(n => {
                    const d = new Date(n.date).getTime();
                    if (d >= today) groups.today.push(n);
                    else if (d >= yesterday) groups.yesterday.push(n);
                    else groups.older.push(n);
                });

                this.renderGroup(container, 'Today', groups.today);
                this.renderGroup(container, 'Yesterday', groups.yesterday);
                this.renderGroup(container, 'Older', groups.older);
            },

            renderGroup(container, title, items) {
                if (items.length === 0) return;

                const groupDiv = document.createElement('div');
                groupDiv.className = 'slide-in';
                
                const titleEl = document.createElement('h3');
                titleEl.className = 'text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 px-1';
                titleEl.innerText = title;
                groupDiv.appendChild(titleEl);

                const listDiv = document.createElement('div');
                listDiv.className = 'space-y-3';

                items.forEach(item => {
                    const el = this.createNotificationElement(item);
                    listDiv.appendChild(el);
                });

                groupDiv.appendChild(listDiv);
                container.appendChild(groupDiv);
            },

            createNotificationElement(item) {
                const div = document.createElement('div');
                div.className = `group relative bg-white dark:bg-dark-surface p-4 rounded-2xl shadow-sm border ${item.read ? 'border-slate-100 dark:border-slate-800' : 'border-brand/30 dark:border-brand/30 bg-brand/5 dark:bg-brand/5'} cursor-pointer hover:shadow-md transition-all active:scale-[0.99]`;
                
                // Icon Logic
                let iconClass = `fas fa-${item.icon}`;
                let colorClass = 'text-slate-500 bg-slate-100 dark:bg-slate-800';
                
                if (item.color === 'green') colorClass = 'text-green-600 bg-green-50 dark:text-green-400 dark:bg-green-900/20';
                if (item.color === 'blue') colorClass = 'text-blue-600 bg-blue-50 dark:text-blue-400 dark:bg-blue-900/20';
                if (item.color === 'purple') colorClass = 'text-purple-600 bg-purple-50 dark:text-purple-400 dark:bg-purple-900/20';
                if (item.color === 'amber') colorClass = 'text-amber-600 bg-amber-50 dark:text-amber-400 dark:bg-amber-900/20';

                // Time Formatting
                let timeDisplay = item.time;
                if (!timeDisplay || timeDisplay === 'Just now') {
                    try {
                        timeDisplay = new Date(item.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    } catch(e) { timeDisplay = 'Just now'; }
                }

                div.innerHTML = `
                    <div class="flex gap-4">
                        <div class="shrink-0 w-12 h-12 rounded-full flex items-center justify-center ${colorClass}">
                            <i class="${iconClass} text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1 pr-6">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate ${!item.read ? 'text-brand dark:text-brand' : ''}">${item.title}</h4>
                                <span class="text-[10px] text-slate-400 whitespace-nowrap mt-0.5">${timeDisplay}</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">${item.message}</p>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <button onclick="event.stopPropagation(); app.deleteNotification('${item.id}')" class="absolute top-2 right-2 w-8 h-8 flex items-center justify-center rounded-full text-slate-300 hover:text-red-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors opacity-0 group-hover:opacity-100">
                        <i class="fas fa-times"></i>
                    </button>
                    ${!item.read ? '<div class="absolute top-1/2 -translate-y-1/2 right-4 w-2 h-2 bg-brand rounded-full"></div>' : ''}
                `;

                div.onclick = () => {
                    this.markAsRead(item.id);
                    if (item.invoice) this.openInvoice(item);
                    else if (item.details) this.openDetails(item.details);
                };

                return div;
            },

            deleteNotification(id) {
                if (!confirm('Delete this notification?')) return;
                // In a real app, you'd delete from DB. 
                // Here we just hide it locally since we are reading from read-only sources (orders/posts)
                // We can store "deleted_notifs" in localStorage
                let deleted = JSON.parse(localStorage.getItem('pp_deleted_notifs') || '[]');
                deleted.push(id);
                localStorage.setItem('pp_deleted_notifs', JSON.stringify(deleted));
                
                this.loadNotifications();
                this.render();
            },

            clearAll() {
                if (!confirm('Clear all notifications?')) return;
                // Mark all current IDs as deleted
                let deleted = JSON.parse(localStorage.getItem('pp_deleted_notifs') || '[]');
                this.notifications.forEach(n => deleted.push(n.id));
                localStorage.setItem('pp_deleted_notifs', JSON.stringify(deleted));
                
                this.loadNotifications();
                this.render();
            },

            markAsRead(id) {
                // In a real app, update DB. Here we just update UI state for session
                const item = this.notifications.find(n => n.id === id);
                if (item) item.read = true;
                this.render();
            },

            markAllRead() {
                this.notifications.forEach(n => n.read = true);
                this.render();
            },

            approveOrder() {
                // Mock approval
                alert('Order Approved! Status updated to Processing.');
                this.closeModal('invoiceModal');
            },

            // --- Modals ---
            openModal(id) {
                const el = document.getElementById(id);
                el.classList.remove('hidden', 'pointer-events-none');
                setTimeout(() => {
                    el.classList.remove('opacity-0');
                    el.querySelector('div').classList.remove('scale-95');
                    el.querySelector('div').classList.add('scale-100');
                }, 10);
            },

            closeModal(id) {
                const el = document.getElementById(id);
                el.classList.add('opacity-0');
                el.querySelector('div').classList.remove('scale-100');
                el.querySelector('div').classList.add('scale-95');
                setTimeout(() => {
                    el.classList.add('hidden', 'pointer-events-none');
                }, 300);
            },

            openInvoice(item) {
                const content = document.getElementById('invoiceContent');
                const invoice = item.invoice;
                
                let html = `
                    <div class="flex justify-between items-end mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <p class="text-xs text-slate-500 uppercase font-bold">Order ID</p>
                            <p class="text-lg font-mono font-bold text-slate-900 dark:text-white">#${invoice.id}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500">${invoice.date}</p>
                            <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded uppercase mt-1">Paid</span>
                        </div>
                    </div>
                    <div class="space-y-3 mb-6">
                `;

                if (invoice.items) {
                    invoice.items.forEach(i => {
                        html += `
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-100 dark:bg-slate-800 rounded flex items-center justify-center text-slate-500">
                                        <span class="text-xs font-bold">x${i.count || 1}</span>
                                    </div>
                                    <span class="font-medium text-slate-700 dark:text-slate-300">${i.name}</span>
                                </div>
                                <span class="font-mono font-bold">${i.price || '-'}</span>
                            </div>
                        `;
                    });
                }

                html += `
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <span class="font-bold text-slate-900 dark:text-white">Total Amount</span>
                        <span class="font-mono font-bold text-xl text-brand">${invoice.total}</span>
                    </div>
                `;

                content.innerHTML = html;
                this.openModal('invoiceModal');
            },

            openDetails(details) {
                document.getElementById('detailsTitle').innerText = details.title;
                document.getElementById('detailsContent').innerText = details.description;
                const cta = document.getElementById('detailsCta');
                if (details.cta) {
                    cta.href = details.cta;
                    cta.classList.remove('hidden');
                } else {
                    cta.classList.add('hidden');
                }
                this.openModal('detailsModal');
            },

            formatCurrency(v) {
                if (typeof v === 'string' && (v.includes('$') || v.includes('VND'))) return v;
                return v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' VND';
            }
        };

        document.addEventListener('DOMContentLoaded', () => app.init());
    </script>
</body>
</html>