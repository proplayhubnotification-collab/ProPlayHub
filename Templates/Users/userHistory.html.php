<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#f8fafc">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#020618">

    <title>Order History - ProPlay Hub</title>
    
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
                }
            }
        }
      }
    </script>
    
    <style>
        body { -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Tab Animation */
        .tab-item { transition: all 0.3s; border-bottom: 2px solid transparent; }
        .tab-item.active { border-color: #ec003f; color: #ec003f; font-weight: 600; }
        
        /* Receipt Modal Animation */
        @keyframes slideUpReceipt { 0% { transform: translateY(100%); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        .receipt-modal { animation: slideUpReceipt 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        /* Digital Card Gradients */
        .card-steam { background: linear-gradient(135deg, #1b2838 0%, #2a475e 100%); }
        .card-psn { background: linear-gradient(135deg, #00439c 0%, #0070d1 100%); }
        .card-xbox { background: linear-gradient(135deg, #107c10 0%, #3a9a3a 100%); }
        .card-riot { background: linear-gradient(135deg, #d13639 0%, #eb0029 100%); }
        .card-generic { background: linear-gradient(135deg, #334155 0%, #475569 100%); }

        /* Call Animation */
        @keyframes phoneShake {
            0% { transform: rotate(0deg); }
            25% { transform: rotate(5deg); }
            50% { transform: rotate(0deg); }
            75% { transform: rotate(-5deg); }
            100% { transform: rotate(0deg); }
        }
        .animate-phone-shake { animation: phoneShake 0.5s ease-in-out infinite; }
        
        @keyframes ripple {
            0% { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(2.5); opacity: 0; }
        }
        .animate-ripple { animation: ripple 1.5s linear infinite; }
    </style>
</head>
<body class="bg-[#f8fafc] text-[#020618] dark:bg-[#020618] dark:text-[#f8fafc] transition-colors duration-300 min-h-screen font-sans pb-24">

    <header class="sticky top-0 z-40 bg-[#f8fafc]/90 dark:bg-[#020618]/90 backdrop-blur-md border-b border-slate-200 dark:border-dark-border px-4 pt-4 pb-0">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-lg font-bold uppercase tracking-wider">My Orders</h1>
            <div class="w-10"></div> 
        </div>

        <div class="flex overflow-x-auto no-scrollbar gap-6 text-sm text-slate-500 dark:text-slate-400">
            <button onclick="filterOrders('all')" id="tab-all" class="tab-item active pb-3 px-1 whitespace-nowrap">All</button>
            <button onclick="filterOrders('processing')" id="tab-processing" class="tab-item pb-3 px-1 whitespace-nowrap">Processing</button>
            <button onclick="filterOrders('completed')" id="tab-completed" class="tab-item pb-3 px-1 whitespace-nowrap">Completed</button>
            <button onclick="filterOrders('cancelled')" id="tab-cancelled" class="tab-item pb-3 px-1 whitespace-nowrap">Cancelled</button>
        </div>
    </header>

    <main class="max-w-md mx-auto px-4 py-6 pb-32">
        <div id="order-list" class="space-y-4">
            <!-- Filled via JS -->
        </div>
        
        <div id="empty-state" class="hidden flex flex-col items-center justify-center py-20 text-slate-400">
            <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4 text-3xl">
                <i class="fas fa-box-open"></i>
            </div>
            <p class="text-sm font-medium">No orders found</p>
        </div>
    </main>

    <!-- PHYSICAL ORDER TRACKING MODAL -->
    <div id="tracking-modal" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-dark-surface w-full max-w-md relative z-10 receipt-modal shadow-2xl overflow-hidden rounded-t-2xl sm:rounded-2xl h-[85vh] sm:h-auto flex flex-col">
            <div class="p-4 border-b border-slate-100 dark:border-dark-border flex justify-between items-center">
                <h2 class="text-lg font-bold">Order Tracking</h2>
                <button onclick="closeModal('tracking-modal')" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto flex-1">
                <!-- Product Info -->
                <div class="flex gap-4 mb-8">
                    <div id="track-img-container" class="w-16 h-16 rounded-lg bg-slate-100 dark:bg-slate-800 overflow-hidden shrink-0">
                        <img id="track-img" src="" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 id="track-name" class="font-bold text-sm mb-1">Product Name</h3>
                        <p id="track-id" class="text-xs text-slate-500 font-mono">#ID</p>
                        <span class="inline-block mt-2 px-2 py-0.5 bg-blue-100 text-blue-600 text-[10px] font-bold rounded-full">In Transit</span>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="relative pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-8">
                    <!-- Step 1 -->
                    <div class="relative">
                        <div class="absolute -left-[21px] top-0 w-4 h-4 rounded-full bg-green-500 border-2 border-white dark:border-dark-surface"></div>
                        <p class="text-xs text-slate-400 mb-0.5">Today, 09:41 AM</p>
                        <h4 class="text-sm font-bold">Out for Delivery</h4>
                        <p class="text-xs text-slate-500 mt-1">Shipper is on the way to your location.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="relative">
                        <div class="absolute -left-[21px] top-0 w-4 h-4 rounded-full bg-green-500 border-2 border-white dark:border-dark-surface"></div>
                        <p class="text-xs text-slate-400 mb-0.5">Yesterday, 14:20 PM</p>
                        <h4 class="text-sm font-bold">Arrived at Sorting Center</h4>
                        <p class="text-xs text-slate-500 mt-1">District 7 Hub</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="relative">
                        <div class="absolute -left-[21px] top-0 w-4 h-4 rounded-full bg-green-500 border-2 border-white dark:border-dark-surface"></div>
                        <p class="text-xs text-slate-400 mb-0.5">Nov 28, 10:00 AM</p>
                        <h4 class="text-sm font-bold">Order Shipped</h4>
                        <p class="text-xs text-slate-500 mt-1">Handed over to delivery partner.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="relative">
                        <div class="absolute -left-[21px] top-0 w-4 h-4 rounded-full bg-green-500 border-2 border-white dark:border-dark-surface"></div>
                        <p class="text-xs text-slate-400 mb-0.5">Nov 28, 08:30 AM</p>
                        <h4 class="text-sm font-bold">Order Placed</h4>
                        <p class="text-xs text-slate-500 mt-1">We have received your order.</p>
                    </div>
                </div>
            </div>
            <div class="p-4 pb-24 border-t border-slate-100 dark:border-dark-border bg-white dark:bg-dark-surface z-20 relative">
                <button onclick="openChat()" class="w-full py-3 bg-brand text-white font-bold rounded-lg shadow-glow flex items-center justify-center gap-2">
                    <i class="fas fa-comment-dots"></i>
                    <span>Contact Shipper</span>
                </button>
            </div>
        </div>
    </div>

    <!-- DIGITAL PRODUCT MODAL -->
    <div id="digital-modal" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-dark-surface w-full max-w-sm relative z-10 receipt-modal shadow-2xl overflow-hidden rounded-2xl">
            <div class="h-32 bg-gradient-to-br from-purple-600 to-blue-600 relative flex items-center justify-center">
                <i class="fas fa-gamepad text-5xl text-white/20 absolute"></i>
                <div class="text-center relative z-10">
                    <div id="digital-img-container" class="w-16 h-16 mx-auto bg-white rounded-xl shadow-lg p-1 mb-2">
                        <img id="digital-img" src="" class="w-full h-full object-cover rounded-lg">
                    </div>
                    <h3 id="digital-name" class="text-white font-bold text-lg shadow-black drop-shadow-md">Game Card</h3>
                </div>
                <button onclick="closeModal('digital-modal')" class="absolute top-4 right-4 text-white/80 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 text-center">
                <div class="mb-6">
                    <span id="digital-status-badge" class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-600">Active & Unused</span>
                </div>
                
                <p class="text-xs text-slate-500 mb-2 uppercase tracking-wider">Activation Code</p>
                <div class="bg-slate-100 dark:bg-slate-800 p-4 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 relative group cursor-pointer" onclick="copyCode()">
                    <p id="digital-code" class="font-mono text-xl font-bold tracking-widest text-slate-800 dark:text-white blur-sm transition-all group-hover:blur-0">XXXX-XXXX-XXXX</p>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="bg-black/70 text-white text-xs px-2 py-1 rounded">Click to Copy</span>
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 mt-3">
                    *This code is valid for 12 months. Please redeem it on the respective platform.
                </p>
            </div>
            <div class="p-4 border-t border-slate-100 dark:border-dark-border">
                <button onclick="window.open('https://store.steampowered.com/account/redeemwalletcode')" class="w-full py-3 bg-slate-900 dark:bg-white dark:text-slate-900 text-white font-bold rounded-lg">Redeem Now</button>
            </div>
        </div>
    </div>

    <!-- RENTAL RETURN MODAL -->
    <div id="rental-modal" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-dark-surface w-full max-w-md relative z-10 receipt-modal shadow-2xl overflow-hidden rounded-t-2xl sm:rounded-2xl">
            <div class="p-4 border-b border-slate-100 dark:border-dark-border flex justify-between items-center">
                <h2 class="text-lg font-bold">Rental Management</h2>
                <button onclick="closeModal('rental-modal')" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="flex gap-4 mb-6">
                    <div id="rental-img-container" class="w-20 h-20 rounded-lg bg-slate-100 dark:bg-slate-800 overflow-hidden shrink-0">
                        <img id="rental-img" src="" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 id="rental-name" class="font-bold text-lg mb-1">PS5 Console</h3>
                        <p class="text-sm text-slate-500">Rental Period: 7 Days</p>
                        <div class="mt-2 flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                            <span class="text-xs font-bold text-green-600">Currently Active</span>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between text-xs text-slate-500 mb-2">
                        <span>Start: Nov 28</span>
                        <span>Due: Dec 05</span>
                    </div>
                    <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-brand w-[60%] rounded-full"></div>
                    </div>
                    <p class="text-center text-xs text-brand font-bold mt-2">3 Days Remaining</p>
                </div>

                <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-lg border border-amber-100 dark:border-amber-800/50 mb-6">
                    <h4 class="text-sm font-bold text-amber-700 dark:text-amber-400 mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Return Policy</h4>
                    <p class="text-xs text-amber-600 dark:text-amber-500">Please return the device in its original condition. Late returns may incur additional fees.</p>
                </div>

                <button onclick="openReturnSchedule()" class="w-full py-3 bg-slate-900 dark:bg-white dark:text-slate-900 text-white font-bold rounded-lg">Return Device Now</button>
            </div>
        </div>
    </div>

    <!-- SCHEDULE RETURN DATE MODAL -->
    <div id="schedule-modal" class="fixed inset-0 z-[60] bg-slate-900/90 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-dark-surface w-full max-w-xs p-6 rounded-2xl shadow-2xl text-center">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-calendar-alt text-xl"></i>
            </div>
            <h3 class="text-lg font-bold mb-2">Schedule Return</h3>
            <p class="text-sm text-slate-500 mb-4">Select a date for our courier to pick up the device.</p>
            
            <input type="date" class="w-full p-3 border border-slate-200 dark:border-dark-border rounded-lg mb-4 bg-slate-50 dark:bg-slate-800 dark:text-white outline-none focus:border-brand">
            
            <div class="flex gap-2">
                <button onclick="closeModal('schedule-modal')" class="flex-1 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg font-bold text-sm">Cancel</button>
                <button onclick="confirmReturn()" class="flex-1 py-2 bg-brand text-white rounded-lg font-bold text-sm">Confirm</button>
            </div>
        </div>
    </div>

    <!-- CHAT MODAL -->
    <div id="chat-modal" class="fixed inset-0 z-[60] bg-slate-900/90 backdrop-blur-sm hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-dark-surface w-full max-w-md relative z-10 receipt-modal shadow-2xl overflow-hidden rounded-t-2xl sm:rounded-2xl h-[85vh] flex flex-col">
            <!-- Header -->
            <div class="p-4 border-b border-slate-100 dark:border-dark-border flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                            <img src="" id="shipper-avatar" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-dark-surface rounded-full"></div>
                    </div>
                    <div>
                        <h3 id="shipper-name" class="font-bold text-sm dark:text-white">Shipper Name</h3>
                        <p class="text-xs text-slate-500">ProPlay Logistics • <span class="text-green-600">Online</span></p>
                    </div>
                </div>
                <button onclick="closeModal('chat-modal')" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Chat Area -->
            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50 dark:bg-[#0b1121]">
                <!-- Messages will be injected here -->
            </div>

            <!-- Quick Actions -->
            <div class="p-4 pb-8 bg-white dark:bg-dark-surface border-t border-slate-100 dark:border-dark-border">
                <p class="text-xs text-slate-400 mb-3 font-bold uppercase">Quick Questions</p>
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-2">
                    <button onclick="sendQuickMessage('Where is my order?')" class="whitespace-nowrap px-4 py-2 rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-brand hover:text-white transition-colors">Where is my order?</button>
                    <button onclick="sendQuickMessage('Call me when close')" class="whitespace-nowrap px-4 py-2 rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-brand hover:text-white transition-colors">Call me when close</button>
                    <button onclick="sendQuickMessage('Leave at door')" class="whitespace-nowrap px-4 py-2 rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-brand hover:text-white transition-colors">Leave at door</button>
                </div>
                
                <div class="mt-3 flex gap-2">
                    <input type="text" id="chat-input" placeholder="Type a message..." class="flex-1 bg-slate-100 dark:bg-slate-800 border-none rounded-full px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand/50 dark:text-white">
                    <button onclick="sendMessage()" class="w-11 h-11 rounded-full bg-brand text-white flex items-center justify-center shadow-glow hover:scale-105 transition-transform">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- CANCEL REASON MODAL -->
    <div id="cancel-modal" class="fixed inset-0 z-[60] bg-slate-900/90 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-dark-surface w-full max-w-sm p-6 rounded-2xl shadow-2xl">
            <h3 class="text-lg font-bold mb-2 dark:text-white">Cancel Order</h3>
            <p class="text-sm text-slate-500 mb-4">Please tell us why you want to cancel.</p>
            
            <div class="space-y-2 mb-4">
                <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-slate-700 rounded-lg cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800">
                    <input type="radio" name="cancel_reason" value="Changed mind" class="accent-brand">
                    <span class="text-sm dark:text-slate-300">Changed my mind</span>
                </label>
                <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-slate-700 rounded-lg cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800">
                    <input type="radio" name="cancel_reason" value="Found cheaper" class="accent-brand">
                    <span class="text-sm dark:text-slate-300">Found cheaper elsewhere</span>
                </label>
                <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-slate-700 rounded-lg cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800">
                    <input type="radio" name="cancel_reason" value="Other" class="accent-brand">
                    <span class="text-sm dark:text-slate-300">Other reason</span>
                </label>
            </div>
            
            <textarea id="cancel-note" placeholder="Additional details (optional)..." class="w-full p-3 border border-slate-200 dark:border-dark-border rounded-lg mb-4 bg-slate-50 dark:bg-slate-800 dark:text-white outline-none focus:border-brand text-sm h-20"></textarea>
            
            <div class="flex gap-2">
                <button onclick="closeModal('cancel-modal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg font-bold text-sm">Back</button>
                <button onclick="confirmCancel()" class="flex-1 py-2.5 bg-red-600 text-white rounded-lg font-bold text-sm shadow-lg shadow-red-600/30">Confirm Cancel</button>
            </div>
        </div>
    </div>

    <!-- RETURN REQUEST MODAL -->
    <div id="return-modal" class="fixed inset-0 z-[60] bg-slate-900/90 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-dark-surface w-full max-w-sm p-6 rounded-2xl shadow-2xl relative overflow-hidden">
            <h3 class="text-lg font-bold mb-1 dark:text-white">Request Return & Refund</h3>
            <p class="text-xs text-slate-500 mb-4">Eligible within 7 days of receipt.</p>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Reason for Return</label>
                <select id="return-reason" class="w-full p-3 border border-slate-200 dark:border-dark-border rounded-lg bg-slate-50 dark:bg-slate-800 dark:text-white outline-none focus:border-brand text-sm">
                    <option value="damaged">Product Damaged / Broken</option>
                    <option value="defective">Defective / Not Working</option>
                    <option value="wrong_item">Received Wrong Item</option>
                    <option value="missing_parts">Missing Parts / Accessories</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Description of Issue</label>
                <textarea id="return-desc" placeholder="Please describe the issue in detail..." class="w-full p-3 border border-slate-200 dark:border-dark-border rounded-lg bg-slate-50 dark:bg-slate-800 dark:text-white outline-none focus:border-brand text-sm h-24"></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Evidence (Optional)</label>
                <div class="border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-lg p-4 text-center cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <i class="fas fa-camera text-2xl text-slate-400 mb-2"></i>
                    <p class="text-xs text-slate-500">Tap to upload photos/video</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <button onclick="closeModal('return-modal')" class="flex-1 py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl font-bold text-sm">Cancel</button>
                <button onclick="submitReturnRequest()" class="flex-1 py-3 bg-brand text-white rounded-xl font-bold text-sm shadow-glow">Submit Request</button>
            </div>
        </div>
    </div>

    <!-- INCOMING CALL OVERLAY -->
    <div id="call-overlay" class="fixed inset-0 z-[100] bg-slate-900 hidden flex flex-col items-center justify-between py-12 px-6">
        <!-- Background Blur Effect -->
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1556742049-0cfed4f7a07d?q=80&w=1000&auto=format&fit=crop')] bg-cover bg-center opacity-20 blur-xl"></div>
        
        <div class="relative z-10 flex flex-col items-center mt-10">
            <div class="w-32 h-32 rounded-full border-4 border-white/20 p-1 mb-6 relative">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=SupportAgent" class="w-full h-full rounded-full object-cover bg-slate-800">
                <div class="absolute inset-0 rounded-full border-4 border-white/50 animate-ripple"></div>
                <div class="absolute inset-0 rounded-full border-4 border-white/30 animate-ripple" style="animation-delay: 0.5s"></div>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">ProPlay Support</h2>
            <p class="text-white/70 animate-pulse">Incoming Video Call...</p>
            <p class="text-sm text-white/50 mt-2">Verifying Return Request</p>
        </div>

        <div class="relative z-10 w-full max-w-xs grid grid-cols-2 gap-8 mb-8">
            <button onclick="closeModal('call-overlay')" class="flex flex-col items-center gap-2 group">
                <div class="w-16 h-16 rounded-full bg-red-500 flex items-center justify-center text-2xl text-white shadow-lg shadow-red-500/40 transition-transform group-active:scale-95">
                    <i class="fas fa-phone-slash"></i>
                </div>
                <span class="text-white text-sm font-medium">Decline</span>
            </button>
            <button onclick="answerCall()" class="flex flex-col items-center gap-2 group">
                <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center text-2xl text-white shadow-lg shadow-green-500/40 animate-phone-shake transition-transform group-active:scale-95">
                    <i class="fas fa-phone"></i>
                </div>
                <span class="text-white text-sm font-medium">Accept</span>
            </button>
        </div>
    </div>

    <!-- RETURN PROGRESS MODAL -->
    <div id="return-progress-modal" class="fixed inset-0 z-[70] bg-slate-900/90 backdrop-blur-sm hidden flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div class="bg-white dark:bg-dark-surface w-full max-w-md relative z-10 receipt-modal shadow-2xl overflow-hidden rounded-t-2xl sm:rounded-2xl">
            <div class="p-4 border-b border-slate-100 dark:border-dark-border flex justify-between items-center">
                <h2 class="text-lg font-bold dark:text-white">Return Status</h2>
                <button onclick="closeModal('return-progress-modal')" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4 mb-8 bg-green-50 dark:bg-green-900/20 p-4 rounded-xl border border-green-100 dark:border-green-900/50">
                    <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-800 flex items-center justify-center text-green-600 dark:text-green-300 shrink-0">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-green-700 dark:text-green-400 text-sm">Request Verified</h3>
                        <p class="text-xs text-green-600 dark:text-green-500">Support agent approved your return.</p>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="relative pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-8">
                    <!-- Step 1 -->
                    <div class="relative">
                        <div class="absolute -left-[21px] top-0 w-4 h-4 rounded-full bg-slate-300 dark:bg-slate-600 border-2 border-white dark:border-dark-surface"></div>
                        <h4 class="text-sm font-bold text-slate-400">Refund Processed</h4>
                        <p class="text-xs text-slate-500 mt-1">Estimated: 3-5 business days</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="relative">
                        <div class="absolute -left-[21px] top-0 w-4 h-4 rounded-full bg-slate-300 dark:bg-slate-600 border-2 border-white dark:border-dark-surface"></div>
                        <h4 class="text-sm font-bold text-slate-400">Item Received</h4>
                        <p class="text-xs text-slate-500 mt-1">Warehouse inspection pending</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="relative">
                        <div class="absolute -left-[21px] top-0 w-4 h-4 rounded-full bg-brand border-2 border-white dark:border-dark-surface animate-pulse"></div>
                        <h4 class="text-sm font-bold dark:text-white">Pickup Scheduled</h4>
                        <p class="text-xs text-slate-500 mt-1">Courier will contact you shortly.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="relative">
                        <div class="absolute -left-[21px] top-0 w-4 h-4 rounded-full bg-green-500 border-2 border-white dark:border-dark-surface"></div>
                        <h4 class="text-sm font-bold dark:text-white">Request Approved</h4>
                        <p class="text-xs text-slate-500 mt-1">Verified by Support Agent</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- THEME SYNC ---
        const themeQuery = window.matchMedia('(prefers-color-scheme: dark)');
        function handleThemeChange(e) { 
            if(e.matches) document.documentElement.classList.add('dark'); 
            else document.documentElement.classList.remove('dark'); 
        }
        themeQuery.addListener(handleThemeChange); 
        handleThemeChange(themeQuery);

        // --- DATA SYNC ---
        let allOrders = [];
        
        function initData() {
            try {
                const stored = localStorage.getItem('pp_orders');
                if (stored) {
                    allOrders = JSON.parse(stored);
                }
            } catch(e) {
                console.error("Error loading orders:", e);
            }

            // Auto-generate if empty (Simulation Mode)
            if (!allOrders || allOrders.length === 0) {
                generateSampleOrders();
            }
            
            render();
        }

        function generateSampleOrders() {
            allOrders = [
                {
                    id: 'ORD-8821-RTX',
                    date: 'Dec 01, 2025',
                    status: 'processing',
                    type: 'physical',
                    total: '$1,299.00',
                    items: [{ name: 'ASUS ROG Strix GeForce RTX 4090', count: 1, img: 'https://dlcdnwebimgs.asus.com/gain/4B634366-98F0-4633-9853-23C7D607D639/w750/h470' }]
                },
                {
                    id: 'ORD-9921-LOG',
                    date: 'Nov 30, 2025',
                    status: 'shipping',
                    type: 'physical',
                    total: '$149.99',
                    items: [{ name: 'Logitech G Pro X Superlight 2', count: 1, img: 'https://resource.logitechg.com/w_692,c_lpad,ar_4:3,q_auto:best,f_auto,dpr_auto/content/dam/gaming/en/products/pro-x-superlight/pro-x-superlight-black-gallery-1.png' }]
                },
                {
                    id: 'ORD-7723-PSN',
                    date: 'Nov 28, 2025',
                    status: 'completed',
                    type: 'digital',
                    total: '$50.00',
                    digitalCode: 'PSN-9928-3321-XXXX',
                    items: [{ name: 'PlayStation Store Gift Card $50', count: 1, type: 'card', subType: 'psn' }]
                },
                {
                    id: 'ORD-4412-STM',
                    date: 'Nov 25, 2025',
                    status: 'completed',
                    type: 'digital',
                    total: '$20.00',
                    digitalCode: 'STM-1122-3344-XXXX',
                    items: [{ name: 'Steam Wallet Code $20', count: 1, type: 'card', subType: 'steam' }]
                },
                {
                    id: 'ORD-1102-PS5',
                    date: 'Nov 20, 2025',
                    status: 'active',
                    type: 'rental',
                    total: '$25.00 / week',
                    items: [{ name: 'PlayStation 5 Console (Rental)', count: 1, img: 'https://gmedia.playstation.com/is/image/SIEPDC/ps5-product-thumbnail-01-en-14sep21?$facebook$' }]
                }
            ];
            localStorage.setItem('pp_orders', JSON.stringify(allOrders));
        }

        // --- RENDER LOGIC ---
        let currentFilter = 'all';

        function filterOrders(filter) {
            currentFilter = filter;
            document.querySelectorAll('.tab-item').forEach(btn => btn.classList.remove('active'));
            document.getElementById(`tab-${filter}`).classList.add('active');
            render();
        }

        function getIcon(subType) {
            if(subType === 'steam') return 'fab fa-steam';
            if(subType === 'psn') return 'fab fa-playstation';
            if(subType === 'xbox') return 'fab fa-xbox';
            if(subType === 'riot') return 'fas fa-gamepad'; // Generic for riot
            return 'fas fa-gift';
        }

        function render() {
            const container = document.getElementById('order-list');
            const empty = document.getElementById('empty-state');
            container.innerHTML = '';

            const filtered = currentFilter === 'all' 
                ? allOrders 
                : allOrders.filter(o => {
                    if(currentFilter === 'processing') return o.status === 'processing' || o.status === 'shipping' || o.status === 'active';
                    return o.status === currentFilter;
                });

            if (filtered.length === 0) {
                empty.classList.remove('hidden');
                return;
            }
            empty.classList.add('hidden');

            filtered.forEach(order => {
                const statusConfig = getStatusConfig(order.status);
                const typeIcon = getTypeIcon(order.type);
                
                const card = document.createElement('div');
                card.className = "bg-white dark:bg-dark-surface p-4 rounded-xl border border-slate-200 dark:border-dark-border shadow-sm transition-all hover:shadow-md";
                
                // Construct Items HTML
                const itemsHtml = order.items.map(item => {
                    // Robust check for card type (handles old data/missing types)
                    let isCard = item.type === 'card';
                    const nameLower = (item.name || '').toLowerCase();
                    
                    if (!isCard && (nameLower.includes('wallet') || nameLower.includes('points') || nameLower.includes('psn') || nameLower.includes('eshop') || nameLower.includes('game pass') || nameLower.includes('card'))) {
                        isCard = true;
                    }

                    let imgHtml = `<img src="${item.img}" class="w-full h-full object-cover" onerror="this.style.display='none'">`;
                    
                    if (isCard) {
                        let subType = item.subType || 'generic';
                        // Infer subtype if missing
                        if (subType === 'generic') {
                            if (nameLower.includes('steam')) subType = 'steam';
                            else if (nameLower.includes('psn') || nameLower.includes('playstation')) subType = 'psn';
                            else if (nameLower.includes('xbox') || nameLower.includes('game pass')) subType = 'xbox';
                            else if (nameLower.includes('riot') || nameLower.includes('valorant') || nameLower.includes('league')) subType = 'riot';
                        }
                        imgHtml = `<div class="w-full h-full card-${subType} flex items-center justify-center text-white"><i class="${getIcon(subType)} text-2xl"></i></div>`;
                    }

                    return `
                    <div class="flex gap-3 mt-3">
                        <div class="w-14 h-14 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0 overflow-hidden border border-slate-100 dark:border-slate-700">
                            ${imgHtml}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold dark:text-white truncate">${item.name}</p>
                            <p class="text-xs text-slate-500 mt-0.5">Qty: ${item.count}</p>
                        </div>
                    </div>
                `}).join('');

                // Action Button Logic
                let actionBtns = [];
                
                // Primary Actions
                if(order.type === 'physical') {
                    actionBtns.push(`<button onclick="openTracking('${order.id}')" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold shadow-md hover:bg-blue-700">Track Order</button>`);
                    
                    // Confirm Receipt (Only if shipping)
                    if(order.status === 'shipping') {
                        actionBtns.push(`<button onclick="confirmReceived('${order.id}')" class="ml-2 px-4 py-2 bg-green-600 text-white rounded-lg text-xs font-bold shadow-md hover:bg-green-700"><i class="fas fa-check mr-1"></i>Received</button>`);
                    }
                    
                    // Return/Refund (Only if completed & within 7 days)
                    if(order.status === 'completed') {
                        // Check 7 days logic
                        const orderDate = new Date(order.date);
                        const now = new Date();
                        const diffTime = Math.abs(now - orderDate);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                        
                        // For demo purposes, we assume all completed orders are eligible if date is invalid or recent
                        if(diffDays <= 7 || isNaN(diffDays)) {
                            actionBtns.push(`<button onclick="openReturnModal('${order.id}')" class="ml-2 px-4 py-2 bg-slate-800 text-white rounded-lg text-xs font-bold shadow-md hover:bg-slate-900 border border-slate-600">Return/Refund</button>`);
                        }
                    }
                }
                else if(order.type === 'digital') {
                    actionBtns.push(`<button onclick="openDigital('${order.id}')" class="px-4 py-2 bg-purple-600 text-white rounded-lg text-xs font-bold shadow-md hover:bg-purple-700">View Code</button>`);
                }
                else if(order.type === 'rental') {
                    actionBtns.push(`<button onclick="openRental('${order.id}')" class="px-4 py-2 bg-amber-500 text-white rounded-lg text-xs font-bold shadow-md hover:bg-amber-600">Manage Rental</button>`);
                }

                // Cancel Option (Only if processing)
                if(order.status === 'processing') {
                    actionBtns.push(`<button onclick="openCancelModal('${order.id}')" class="ml-2 px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-lg text-xs font-bold hover:bg-red-100">Cancel</button>`);
                }
                
                // Return Status
                if(order.status === 'returning') {
                     actionBtns.push(`<button onclick="openReturnProgress('${order.id}')" class="ml-2 px-4 py-2 bg-amber-100 text-amber-700 border border-amber-200 rounded-lg text-xs font-bold hover:bg-amber-200"><i class="fas fa-sync-alt fa-spin mr-1"></i>Return Active</button>`);
                }

                const actionBtnHtml = actionBtns.join('');

                card.innerHTML = `
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 text-xs">
                                ${typeIcon}
                            </div>
                            <div>
                                <p class="text-xs font-mono text-slate-500">#${order.id}</p>
                                <p class="text-[10px] text-slate-400">${order.date}</p>
                            </div>
                        </div>
                        <span class="text-[10px] px-2 py-1 rounded-full font-bold uppercase tracking-wide ${statusConfig.class}">
                            ${order.status}
                        </span>
                    </div>
                    
                    <div class="border-t border-dashed border-slate-200 dark:border-slate-700 my-3"></div>
                    
                    ${itemsHtml}

                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase font-bold">Total</p>
                            <p class="font-mono font-bold text-base dark:text-white">${order.total}</p>
                        </div>
                        <div>
                            ${actionBtnHtml}
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function getStatusConfig(status) {
            switch(status) {
                case 'processing': return { class: 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' };
                case 'shipping': return { class: 'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400' };
                case 'completed': return { class: 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' };
                case 'cancelled': return { class: 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' };
                case 'active': return { class: 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' };
                default: return { class: 'bg-slate-100 text-slate-500' };
            }
        }

        function getTypeIcon(type) {
            if(type === 'physical') return '<i class="fas fa-box"></i>';
            if(type === 'digital') return '<i class="fas fa-wifi"></i>';
            if(type === 'rental') return '<i class="fas fa-clock"></i>';
            return '<i class="fas fa-shopping-bag"></i>';
        }

        function renderItemImage(container, item) {
            // Robust check for card type
            let isCard = item.type === 'card';
            const nameLower = (item.name || '').toLowerCase();
            
            if (!isCard && (nameLower.includes('wallet') || nameLower.includes('points') || nameLower.includes('psn') || nameLower.includes('eshop') || nameLower.includes('game pass') || nameLower.includes('card'))) {
                isCard = true;
            }

            if (isCard) {
                let subType = item.subType || 'generic';
                if (subType === 'generic') {
                    if (nameLower.includes('steam')) subType = 'steam';
                    else if (nameLower.includes('psn') || nameLower.includes('playstation')) subType = 'psn';
                    else if (nameLower.includes('xbox') || nameLower.includes('game pass')) subType = 'xbox';
                    else if (nameLower.includes('riot') || nameLower.includes('valorant') || nameLower.includes('league')) subType = 'riot';
                }
                container.innerHTML = `<div class="w-full h-full card-${subType} flex items-center justify-center text-white"><i class="${getIcon(subType)} text-2xl"></i></div>`;
            } else {
                container.innerHTML = `<img src="${item.img}" class="w-full h-full object-cover" onerror="this.style.display='none'">`;
            }
        }

        // --- MODAL ACTIONS ---
        
        function openTracking(id) {
            const order = allOrders.find(o => o.id === id);
            if(!order) return;
            
            renderItemImage(document.getElementById('track-img-container'), order.items[0]);
            
            document.getElementById('track-name').textContent = order.items[0].name;
            document.getElementById('track-id').textContent = '#' + order.id;
            
            // Generate Timeline
            const timelineContainer = document.querySelector('#tracking-modal .relative.pl-4');
            timelineContainer.innerHTML = generateTimelineHTML(order.date);

            document.getElementById('tracking-modal').classList.remove('hidden');
        }

        function generateTimelineHTML(dateString) {
            let startTime = new Date(dateString);
            // Fallback for invalid dates
            if(isNaN(startTime.getTime())) {
                startTime = new Date(); 
            }

            const now = new Date();
            const steps = [
                { label: 'Order Placed', desc: 'We have received your order.', delay: 0 },
                { label: 'Order Shipped', desc: 'Handed over to delivery partner.', delay: 15 },
                { label: 'Arrived at Sorting Center', desc: 'District 7 Hub', delay: 30 },
                { label: 'Out for Delivery', desc: 'Shipper is on the way to your location.', delay: 45 }
            ];

            // Reverse to show newest on top
            const reversedSteps = [...steps].reverse();
            let html = '';
            let hasActive = false;

            reversedSteps.forEach((step) => {
                const stepTime = new Date(startTime.getTime() + step.delay * 60000);
                
                // Only show if time has passed
                if (now >= stepTime) {
                    const dateStr = stepTime.toLocaleDateString() === now.toLocaleDateString() 
                        ? `Today, ${stepTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`
                        : `${stepTime.toLocaleDateString()}, ${stepTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`;
                    
                    // First item rendered is the "latest" status
                    const isLatest = !hasActive;
                    if(isLatest) hasActive = true;

                    const dotClass = isLatest ? 'bg-green-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600';
                    const textClass = isLatest ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400';

                    html += `
                        <div class="relative fade-in-up">
                            <div class="absolute -left-[21px] top-0 w-4 h-4 rounded-full ${dotClass} border-2 border-white dark:border-dark-surface"></div>
                            <p class="text-xs text-slate-400 mb-0.5">${dateStr}</p>
                            <h4 class="text-sm font-bold ${textClass}">${step.label}</h4>
                            <p class="text-xs text-slate-500 mt-1">${step.desc}</p>
                        </div>
                    `;
                }
            });
            
            return html;
        }

        function openDigital(id) {
            const order = allOrders.find(o => o.id === id);
            if(!order) return;
            
            renderItemImage(document.getElementById('digital-img-container'), order.items[0]);
            
            document.getElementById('digital-name').textContent = order.items[0].name;
            document.getElementById('digital-code').textContent = order.digitalCode || 'XXXX-XXXX-XXXX';
            document.getElementById('digital-modal').classList.remove('hidden');
        }

        function openRental(id) {
            const order = allOrders.find(o => o.id === id);
            if(!order) return;
            
            renderItemImage(document.getElementById('rental-img-container'), order.items[0]);
            
            document.getElementById('rental-name').textContent = order.items[0].name;
            document.getElementById('rental-modal').classList.remove('hidden');
        }

        function openReturnSchedule() {
            document.getElementById('schedule-modal').classList.remove('hidden');
        }

        function confirmReturn() {
            alert('Return scheduled successfully! Our courier will contact you shortly.');
            closeModal('schedule-modal');
            closeModal('rental-modal');
        }

        function copyCode() {
            const code = document.getElementById('digital-code').textContent;
            navigator.clipboard.writeText(code);
            alert('Code copied to clipboard!');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // --- CHAT LOGIC ---
        const shipperNames = ['David', 'Sarah', 'Michael', 'Jessica', 'Robert', 'Emily', 'John', 'Linda'];
        let currentShipper = '';

        function openChat() {
            // Generate random shipper
            const name = shipperNames[Math.floor(Math.random() * shipperNames.length)];
            currentShipper = name;
            
            document.getElementById('shipper-name').textContent = name;
            document.getElementById('shipper-avatar').src = `https://api.dicebear.com/7.x/avataaars/svg?seed=${name}`;
            
            // Reset chat
            const chatBox = document.getElementById('chat-messages');
            chatBox.innerHTML = `
                <div class="flex justify-center my-4">
                    <span class="text-[10px] text-slate-400 bg-slate-200 dark:bg-slate-800 px-2 py-1 rounded-full">Today</span>
                </div>
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden shrink-0">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=${name}" class="w-full h-full object-cover">
                    </div>
                    <div class="bg-white dark:bg-dark-surface border border-slate-100 dark:border-slate-700 p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[80%]">
                        <p class="text-sm dark:text-slate-200">Hello! I'm ${name}, your delivery partner. How can I help you today?</p>
                        <span class="text-[10px] text-slate-400 mt-1 block text-right">Just now</span>
                    </div>
                </div>
            `;
            
            document.getElementById('chat-modal').classList.remove('hidden');
        }

        function sendQuickMessage(msg) {
            addMessage(msg, 'user');
            setTimeout(() => {
                let reply = "I'll check on that for you.";
                if(msg.includes('Where')) reply = "I'm currently about 15 minutes away from your location.";
                if(msg.includes('Call')) reply = "Sure, I will give you a call when I arrive.";
                if(msg.includes('door')) reply = "Understood. I'll leave the package at your door and take a photo.";
                addMessage(reply, 'shipper');
            }, 1000);
        }

        function sendMessage() {
            const input = document.getElementById('chat-input');
            const msg = input.value.trim();
            if(!msg) return;
            
            addMessage(msg, 'user');
            input.value = '';
            
            setTimeout(() => {
                addMessage("Thanks for the message. I'm driving right now but will reply shortly!", 'shipper');
            }, 1500);
        }

        function addMessage(text, sender) {
            const chatBox = document.getElementById('chat-messages');
            const div = document.createElement('div');
            
            if(sender === 'user') {
                div.className = "flex justify-end";
                div.innerHTML = `
                    <div class="bg-brand text-white p-3 rounded-2xl rounded-tr-none shadow-glow max-w-[80%]">
                        <p class="text-sm">${text}</p>
                    </div>
                `;
            } else {
                div.className = "flex gap-3";
                div.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden shrink-0">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=${currentShipper}" class="w-full h-full object-cover">
                    </div>
                    <div class="bg-white dark:bg-dark-surface border border-slate-100 dark:border-slate-700 p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[80%]">
                        <p class="text-sm dark:text-slate-200">${text}</p>
                    </div>
                `;
            }
            
            chatBox.appendChild(div);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        let activeOrderId = null;

        function openCancelModal(id) {
            activeOrderId = id;
            document.getElementById('cancel-modal').classList.remove('hidden');
        }

        function confirmCancel() {
            const reason = document.querySelector('input[name="cancel_reason"]:checked')?.value;
            const note = document.getElementById('cancel-note').value;
            
            if(!reason) {
                alert('Please select a reason for cancellation.');
                return;
            }

            const index = allOrders.findIndex(o => o.id === activeOrderId);
            if(index !== -1) {
                allOrders[index].status = 'cancelled';
                allOrders[index].cancelReason = reason;
                allOrders[index].cancelNote = note;
                localStorage.setItem('pp_orders', JSON.stringify(allOrders));
                render();
                closeModal('cancel-modal');
                alert('Order cancelled successfully.');
            }
        }

        function openReturnModal(id) {
            activeOrderId = id;
            document.getElementById('return-modal').classList.remove('hidden');
        }

        function submitReturnRequest() {
            const reason = document.getElementById('return-reason').value;
            const desc = document.getElementById('return-desc').value;
            
            if(!desc) {
                alert('Please describe the issue.');
                return;
            }

            // Close modal and start simulation
            closeModal('return-modal');
            
            // Show loading or toast
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-6 py-3 rounded-full shadow-xl z-[100] flex items-center gap-3 animate-bounce';
            toast.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Connecting to Support Agent...</span>';
            document.body.appendChild(toast);

            // 5 Seconds Delay
            setTimeout(() => {
                toast.remove();
                document.getElementById('call-overlay').classList.remove('hidden');
                // Optional: Play sound here if allowed
            }, 5000);
        }

        function answerCall() {
            const overlay = document.getElementById('call-overlay');
            const statusText = overlay.querySelector('p.animate-pulse');
            const buttons = overlay.querySelector('.grid');
            
            // Change UI to "Connected"
            statusText.innerText = "00:01";
            statusText.classList.remove('animate-pulse');
            buttons.innerHTML = `
                <div class="col-span-2 flex justify-center">
                    <button onclick="endCall()" class="w-16 h-16 rounded-full bg-red-500 flex items-center justify-center text-2xl text-white shadow-lg">
                        <i class="fas fa-phone-slash"></i>
                    </button>
                </div>
            `;

            // Simulate verification conversation
            setTimeout(() => {
                statusText.innerText = "00:03 • Verifying details...";
            }, 2000);

            setTimeout(() => {
                statusText.innerText = "00:05 • Request Approved";
                statusText.classList.add('text-green-400');
            }, 4000);

            setTimeout(() => {
                endCall();
            }, 6000);
        }

        function endCall() {
            document.getElementById('call-overlay').classList.add('hidden');
            
            // Update Order Status
            const index = allOrders.findIndex(o => o.id === activeOrderId);
            if(index !== -1) {
                allOrders[index].status = 'returning';
                localStorage.setItem('pp_orders', JSON.stringify(allOrders));
                render();
                
                // Show Progress Modal immediately
                setTimeout(() => {
                    openReturnProgress(activeOrderId);
                }, 500);
            }
        }

        function openReturnProgress(id) {
            document.getElementById('return-progress-modal').classList.remove('hidden');
        }

        // Init
        document.addEventListener('DOMContentLoaded', initData);
    </script>
</body>
</html>