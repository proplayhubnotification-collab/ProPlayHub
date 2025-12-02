<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#f8fafc">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#020618">

    <title>Secure Checkout - ProPlay Hub</title>
    
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
                    DEFAULT: '0.375rem',
                },
                boxShadow: { 'glow': '0 0 15px rgba(236, 0, 63, 0.3)' }
            }
        }
      }
    </script>
    
    <style>
        body { -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Smooth transitions */
        .selection-card { 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            border: 1px solid transparent; 
        }
        
        .selection-card:not(.active) { border-color: #e2e8f0; }
        .dark .selection-card:not(.active) { border-color: #1e293b; }

        .selection-card.active { 
            border-color: #ec003f; 
            background-color: rgba(236, 0, 63, 0.04); 
        }
        
        /* Radio Animation */
        .radio-outer { transition: border-color 0.2s; }
        .active .radio-outer { border-color: #ec003f; }
        
        .radio-inner { transform: scale(0); transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .active .radio-inner { transform: scale(1); }

        /* Receipt Animation */
        @keyframes slideUpReceipt { 0% { transform: translateY(100%); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        .receipt-modal { animation: slideUpReceipt 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        /* Confetti Animation */
        .confetti-piece {
            position: fixed;
            pointer-events: none;
            animation: confetti 3s ease-in forwards;
        }
        @keyframes confetti {
            0% { transform: translateY(0) rotateX(0deg) rotateY(0deg); opacity: 1; }
            100% { transform: translateY(-500px) rotateX(720deg) rotateY(360deg); opacity: 0; }
        }
        
        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        .disabled-option { opacity: 0.5; pointer-events: none; }

        /* Realistic Card CSS */
        .credit-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .credit-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            transform: rotate(30deg);
            pointer-events: none;
        }
        .card-chip {
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            border-radius: 6px;
            position: relative;
            overflow: hidden;
        }
        .card-chip::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: 
                linear-gradient(to bottom, transparent 45%, rgba(0,0,0,0.2) 45%, rgba(0,0,0,0.2) 55%, transparent 55%),
                linear-gradient(to right, transparent 45%, rgba(0,0,0,0.2) 45%, rgba(0,0,0,0.2) 55%, transparent 55%);
            background-size: 100% 100%, 100% 100%;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 6px;
        }
        
        /* Card Types */
        .card-visa { background: linear-gradient(135deg, #1a1f71 0%, #2b32b2 100%); }
        .card-mastercard { background: linear-gradient(135deg, #cc2b5e 0%, #753a88 100%); }
        .card-napas { background: linear-gradient(135deg, #0068ff 0%, #004e92 100%); }
        
        .card-logo { height: 30px; object-fit: contain; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 dark:bg-[#020618] dark:text-[#f8fafc] transition-colors duration-300 min-h-screen font-sans pb-32">

    <header class="sticky top-0 z-40 bg-[#f8fafc]/90 dark:bg-[#020618]/90 backdrop-blur-md border-b border-slate-200 dark:border-dark-border px-4 py-4 flex items-center justify-between">
        <button onclick="window.history.back()" class="w-10 h-10 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 flex items-center justify-center transition-colors">
            <i class="fas fa-arrow-left text-lg"></i>
        </button>
        <h1 class="text-lg font-bold uppercase tracking-wider">Checkout</h1>
        <div class="w-10"></div> 
    </header>

    <main class="max-w-md mx-auto px-4 py-6 space-y-6">
        
        <section id="section-address">
            <div class="flex justify-between items-center mb-3">
                <h2 class="font-bold text-lg dark:text-white">Shipping Address</h2>
                <button onclick="toggleAddressEdit()" id="edit-addr-btn" class="text-xs text-brand font-bold uppercase">Change</button>
            </div>
            
            <div id="addr-static" class="bg-white dark:bg-dark-surface p-4 rounded border border-slate-200 dark:border-dark-border flex items-start gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700/50 flex items-center justify-center text-slate-500 shrink-0"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-bold text-sm dark:text-white" id="display-name">Alex Hunter</span>
                        <span class="bg-slate-200 dark:bg-slate-700 text-[10px] px-1.5 py-0.5 rounded text-slate-600 dark:text-slate-300 font-bold">HOME</span>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-tight" id="display-addr">123 Gaming Street, District 1,<br>Ho Chi Minh City</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-mono" id="display-phone">+84 909 888 777</p>
                    <p class="text-xs text-slate-400 mt-1" id="display-country">Vietnam</p>
                </div>
            </div>

            <div id="addr-form" class="hidden bg-white dark:bg-dark-surface p-4 rounded border border-slate-200 dark:border-dark-border space-y-3">
                <input type="text" id="input-name" placeholder="Full Name" value="Alex Hunter" class="w-full bg-slate-50 dark:bg-dark-bg border border-slate-200 dark:border-dark-border rounded px-3 py-2 text-sm outline-none focus:border-brand dark:text-white">
                <input type="text" id="input-addr" placeholder="Address" value="123 Gaming Street, District 1, HCMC" class="w-full bg-slate-50 dark:bg-dark-bg border border-slate-200 dark:border-dark-border rounded px-3 py-2 text-sm outline-none focus:border-brand dark:text-white">
                <div class="flex gap-2">
                    <select id="input-country" class="w-1/2 bg-slate-50 dark:bg-dark-bg border border-slate-200 dark:border-dark-border rounded px-3 py-2 text-sm outline-none focus:border-brand dark:text-white">
                        <option value="UK">United Kingdom</option>
                        <option value="VN">Vietnam</option>
                        <option value="US">United States</option>
                        <option value="OTHER">Other</option>
                    </select>
                    <input type="tel" id="input-phone" placeholder="Phone" value="+84 909 888 777" class="flex-1 bg-slate-50 dark:bg-dark-bg border border-slate-200 dark:border-dark-border rounded px-3 py-2 text-sm outline-none focus:border-brand dark:text-white">
                </div>
                <button onclick="saveAddress()" class="w-full bg-slate-900 dark:bg-white text-white dark:text-black font-bold py-2 rounded text-sm">Save Address</button>
            </div>
        </section>

        <section id="section-delivery">
            <h2 class="font-bold text-lg dark:text-white mb-3">Delivery Method</h2>
            <div class="space-y-3">
                <div onclick="selectShipping('std', 0)" id="ship-std" class="selection-card active bg-white dark:bg-dark-surface p-4 rounded border cursor-pointer">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 rounded-full border border-slate-300 dark:border-slate-600 flex items-center justify-center radio-outer"><div class="radio-inner w-2.5 h-2.5 bg-brand rounded-full"></div></div>
                            <div>
                                <p class="font-bold text-sm dark:text-white">Standard Delivery</p>
                                <p class="text-xs text-slate-500">3-5 Days</p>
                            </div>
                        </div>
                        <span class="font-mono font-bold text-sm text-brand">Free</span>
                    </div>
                </div>
                
                <div onclick="selectShipping('exp', 5.99)" id="ship-exp" class="selection-card bg-white dark:bg-dark-surface p-4 rounded border cursor-pointer">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 rounded-full border border-slate-300 dark:border-slate-600 flex items-center justify-center radio-outer"><div class="radio-inner w-2.5 h-2.5 bg-brand rounded-full"></div></div>
                            <div>
                                <p class="font-bold text-sm dark:text-white">Express Delivery</p>
                                <p class="text-xs text-slate-500">1-2 Days</p>
                            </div>
                        </div>
                        <span class="font-mono font-bold text-sm dark:text-white">$5.99</span>
                    </div>
                </div>

                <div onclick="selectShipping('inst', 12.50)" id="ship-inst" class="selection-card bg-white dark:bg-dark-surface p-4 rounded border cursor-pointer">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 rounded-full border border-slate-300 dark:border-slate-600 flex items-center justify-center radio-outer"><div class="radio-inner w-2.5 h-2.5 bg-brand rounded-full"></div></div>
                            <div>
                                <p class="font-bold text-sm dark:text-white">ProPlay Instant</p>
                                <p class="text-xs text-slate-500">Within 2 Hours</p>
                            </div>
                        </div>
                        <span class="font-mono font-bold text-sm dark:text-white">$12.50</span>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <h2 class="font-bold text-lg dark:text-white mb-3">Payment Method</h2>
            
            <div class="space-y-3">
                
                <div onclick="selectPayment('card')" id="pay-card" class="selection-card group bg-white dark:bg-dark-surface p-4 rounded border cursor-pointer relative overflow-hidden">
                    <div class="flex items-center justify-between relative z-10 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-brand text-lg">
                                <i class="far fa-credit-card"></i>
                            </div>
                            <div>
                                <p class="font-bold text-sm dark:text-white group-hover:text-brand transition-colors">Credit / Debit Card</p>
                                <p class="text-xs text-slate-500">Visa, Mastercard, Napas</p>
                            </div>
                        </div>
                        <div class="w-5 h-5 rounded-full border border-slate-300 dark:border-slate-600 flex items-center justify-center radio-outer">
                            <div class="radio-inner w-2.5 h-2.5 rounded-full bg-brand"></div>
                        </div>
                    </div>

                    <!-- Saved Cards List -->
                    <div id="saved-cards-list" class="hidden space-y-3 animate-fade-in">
                        <div id="cards-container" class="space-y-3"></div>
                        
                        <a href="../PHP_User/userAddCard.php" class="w-full py-3 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-xl text-slate-500 dark:text-slate-400 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-plus"></i> Add New Card
                        </a>
                    </div>
                </div>

                <div onclick="selectPayment('wallet')" id="pay-wallet" class="selection-card group bg-white dark:bg-dark-surface p-4 rounded border cursor-pointer relative">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-brand text-lg">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div>
                                <p class="font-bold text-sm dark:text-white group-hover:text-brand transition-colors">E-Wallet / QR Code</p>
                                <p class="text-xs text-slate-500">MoMo, ZaloPay, Apple Pay</p>
                            </div>
                        </div>
                        <div class="w-5 h-5 rounded-full border border-slate-300 dark:border-slate-600 flex items-center justify-center radio-outer">
                            <div class="radio-inner w-2.5 h-2.5 rounded-full bg-brand"></div>
                        </div>
                    </div>
                    <div id="wallet-hint" class="hidden mt-3 text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-dark-bg p-2 rounded border border-slate-100 dark:border-slate-800 flex items-center gap-2 animate-fade-in">
                        <i class="fas fa-info-circle text-brand"></i>
                        <span>Scan QR Code after clicking Pay Now.</span>
                    </div>
                </div>

                <div onclick="selectPayment('cod')" id="pay-cod" class="selection-card group bg-white dark:bg-dark-surface p-4 rounded border cursor-pointer relative">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-green-600 text-lg">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <p class="font-bold text-sm dark:text-white group-hover:text-brand transition-colors">Cash on Delivery</p>
                                <p class="text-xs text-slate-500">Pay when you receive</p>
                            </div>
                        </div>
                        <div class="w-5 h-5 rounded-full border border-slate-300 dark:border-slate-600 flex items-center justify-center radio-outer">
                            <div class="radio-inner w-2.5 h-2.5 rounded-full bg-brand"></div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <section>
            <h2 class="font-bold text-lg dark:text-white mb-3">Order Summary</h2>
            <div class="bg-white dark:bg-dark-surface p-4 rounded border border-slate-200 dark:border-dark-border">
                <div id="cart-items-container" class="space-y-4 mb-4"></div>

                <div class="space-y-2 text-sm pt-4 border-t border-slate-200 dark:border-dark-border">
                    <div class="flex justify-between text-slate-500">
                        <span>Subtotal</span>
                        <span id="subtotal" class="font-mono font-medium dark:text-slate-300">$0.00</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Shipping</span>
                        <span id="shipping-display" class="font-mono font-medium text-brand">Free</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Tax (8%)</span>
                        <span id="tax" class="font-mono font-medium dark:text-slate-300">$0.00</span>
                    </div>
                    <div id="import-tax-row" class="flex justify-between text-slate-500 hidden">
                        <span>Import Tax (10%)</span>
                        <span id="import-tax" class="font-mono font-medium dark:text-slate-300">$0.00</span>
                    </div>
                    <div id="air-tax-row" class="flex justify-between text-slate-500 hidden">
                        <span>Air Freight</span>
                        <span id="air-tax" class="font-mono font-medium dark:text-slate-300">$0.00</span>
                    </div>
                    <div id="promo-row" class="flex justify-between text-emerald-500 font-bold hidden">
                        <span>Discount</span>
                        <span id="discount-amount" class="font-mono">- $0.00</span>
                    </div>
                    <div id="visa-discount-row" class="flex justify-between text-blue-500 font-bold hidden">
                        <span>Visa Privilege (5%)</span>
                        <span id="visa-discount-amount" class="font-mono">- $0.00</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 flex gap-2">
                <input type="text" id="promo-input" placeholder="Promo Code" class="flex-1 bg-white dark:bg-dark-surface border border-slate-200 dark:border-dark-border rounded px-4 text-sm outline-none focus:border-brand uppercase dark:text-white">
                <button onclick="applyPromo()" class="bg-slate-900 dark:bg-white text-white dark:text-black font-bold px-4 py-3 rounded text-sm">Apply</button>
            </div>
            <div class="mt-3">
                <p class="text-sm text-slate-500 mb-2">Available Vouchers (choose one):</p>
                <div id="voucher-list" class="flex gap-2"></div>
            </div>
        </section>

        <section class="bg-white dark:bg-dark-surface p-6 rounded border border-slate-200 dark:border-dark-border shadow-lg mt-6">
            <div class="flex justify-between items-center mb-4">
                <p class="text-sm text-slate-500 uppercase font-bold">Total Payment</p>
                <p id="final-total" class="text-3xl font-mono font-bold text-brand">$0.00</p>
            </div>
            
            <div id="rental-warning" class="hidden mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 rounded text-red-600 dark:text-red-400 text-xs font-bold flex items-center gap-2">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Rental items are only available in the UK. You are in an unsupported region.</span>
            </div>

            <button onclick="processOrder()" id="pay-btn" class="w-full bg-brand text-white font-bold py-4 rounded shadow-glow hover:bg-red-600 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <span>PAY SECURELY NOW</span>
                <i class="fas fa-arrow-right"></i>
            </button>
            
            <p class="text-center text-[10px] text-slate-400 mt-3">
                <i class="fas fa-lock"></i> All transactions are secure and encrypted.
            </p>
        </section>

    </main>

    <div id="invoice-overlay" class="fixed inset-0 z-50 bg-slate-900/90 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 pointer-events-none opacity-30 bg-[url('https://api.dicebear.com/7.x/shapes/svg?seed=Celebrate')]"></div>
        
        <div class="bg-white w-full max-w-sm relative z-10 receipt-modal shadow-2xl overflow-hidden rounded">
            <div class="h-2 bg-brand w-full"></div>
            
            <div class="p-6 text-center border-b border-dashed border-slate-300">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg text-white text-2xl">
                    <i class="fas fa-check"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-1">Payment Successful</h2>
                <p class="text-slate-500 text-sm">Your order is on the way!</p>
            </div>

            <div class="p-6 bg-slate-50 space-y-3 font-mono text-sm text-slate-700">
                <div class="flex justify-between"><span>Date</span><span id="inv-date">--</span></div>
                <div class="flex justify-between"><span>Trans. ID</span><span id="inv-id">--</span></div>
                <div class="flex justify-between"><span>Method</span><span id="inv-method" class="uppercase font-bold">--</span></div>
                <hr class="border-slate-300 border-dashed my-2">
                <div class="flex justify-between"><span>Subtotal</span><span id="inv-sub">--</span></div>
                <div class="flex justify-between"><span>Shipping</span><span id="inv-ship">--</span></div>
                <div class="flex justify-between"><span>Tax</span><span id="inv-tax">--</span></div>
                <hr class="border-slate-300 border-dashed my-2">
                <div class="flex justify-between text-lg font-bold text-slate-900">
                    <span>TOTAL</span>
                    <span id="inv-total">--</span>
                </div>
            </div>
            
            <div class="relative h-4 bg-slate-50">
                <div class="absolute bottom-0 w-full h-4 bg-slate-900" style="mask-image: radial-gradient(circle, transparent 50%, black 50%); mask-size: 20px 20px; -webkit-mask-image: radial-gradient(circle, transparent 50%, black 50%); -webkit-mask-size: 20px 20px;"></div>
            </div>

            <div class="p-4 bg-white">
                <button onclick="window.location.href='../PHP_User/userStore.php'" class="w-full py-3 bg-slate-900 text-white font-bold rounded text-sm">Continue Shopping</button>
            </div>
        </div>
    </div>

    <div id="qr-overlay" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-md hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-sm relative z-10 rounded overflow-hidden">
            <div class="p-4 text-center">
                <h3 class="font-bold text-lg">Scan to Pay</h3>
                <p class="text-sm text-slate-500">Scan this QR with your banking app to transfer the amount.</p>
                <div class="my-4 flex justify-center">
                    <img id="qr-img" src="" alt="QR Code" class="w-40 h-40 bg-slate-100 p-2 rounded" />
                </div>
                <div class="flex justify-center gap-3 text-sm mb-2">
                    <div>Tx: <span id="qr-tx" class="font-mono">--</span></div>
                    <div>Time left: <span id="qr-countdown" class="font-mono">01:30</span></div>
                </div>
                <div id="qr-status" class="text-xs text-slate-500 mb-3">Waiting for scan...</div>
                <div class="flex gap-2 px-6">
                    <button id="qr-pay-btn" onclick="verifyWalletPayment()" class="flex-1 py-2 bg-emerald-600 text-white rounded font-bold">I Have Paid</button>
                    <button onclick="closeQRModal()" class="flex-1 py-2 bg-slate-200 rounded">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- CONFIG & STATE ---
        const themeQuery = window.matchMedia('(prefers-color-scheme: dark)');
        function handleThemeChange(e) { if(e.matches) document.documentElement.classList.add('dark'); else document.documentElement.classList.remove('dark'); }
        themeQuery.addListener(handleThemeChange); handleThemeChange(themeQuery);
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) document.documentElement.classList.add('dark');

        let state = {
            cart: [],
            shippingCost: 0,
            discount: 0,
            subtotal: 0,
            tax: 0,
            total: 0,
            paymentMethod: null,
            // default to Vietnam to match initial static address in template
            addressCountry: 'VN',
            walletVerified: false,
            savedCards: [],
            selectedCardIndex: 0
        };

        // Predefined vouchers
        const vouchers = [
            { code: 'WELCOME10', label: 'WELCOME10 — $10 off', type: 'amount', value: 10 },
            { code: 'SHIPFREE', label: 'SHIPFREE — Free shipping', type: 'shipping', value: 0 },
            { code: 'PROPLAY20', label: 'PROPLAY20 — 20% off', type: 'percent', value: 20 }
        ];

        // --- INIT ---
        document.addEventListener('DOMContentLoaded', () => {
            const stored = sessionStorage.getItem('cart');
            if (!stored) {
                state.cart = [
                    { name: 'DualSense Edge', price: 199.00, type: 'gear' },
                    { name: 'Steam Wallet $20', price: 20.00, type: 'card' }
                ];
            } else {
                state.cart = JSON.parse(stored);
            }
            
            // Load Saved Cards
            try {
                const saved = localStorage.getItem('pp_saved_cards');
                if(saved) {
                    state.savedCards = JSON.parse(saved);
                } else {
                    // Default card if none exist
                    state.savedCards = [{ number: '4242 4242 4242 4242', name: 'ALEX HUNTER', exp: '12/28' }];
                    localStorage.setItem('pp_saved_cards', JSON.stringify(state.savedCards));
                }
            } catch(e) {
                state.savedCards = [];
            }

            renderCart();
            updateSummary();
            selectShipping('std', 0); 
            selectPayment('card');
            // init country display
            const countrySelect = document.getElementById('input-country');
            if (countrySelect) {
                countrySelect.value = state.addressCountry;
            }
            document.getElementById('display-country').innerText = getCountryLabel(state.addressCountry);
            updateShippingAvailability();
            renderVouchers();
            validateRentalRestrictions();
            checkDigitalOnly();
            renderSavedCards(); // Initial render
        });

        function checkDigitalOnly() {
            // Check if cart has ONLY digital items (type 'card')
            const isDigitalOnly = state.cart.length > 0 && state.cart.every(item => item.type === 'card');
            
            const addrSec = document.getElementById('section-address');
            const delSec = document.getElementById('section-delivery');
            
            if (isDigitalOnly) {
                // Hide Address & Delivery
                if(addrSec) addrSec.classList.add('hidden');
                if(delSec) delSec.classList.add('hidden');
                
                // Force free shipping
                state.shippingCost = 0;
                state.freeShippingApplied = true; // effectively free
                updateSummary();
            } else {
                // Show Address & Delivery
                if(addrSec) addrSec.classList.remove('hidden');
                if(delSec) delSec.classList.remove('hidden');
                
                // Reset shipping if it was forced to 0 (unless user selected free shipping manually)
                // Default back to standard if needed, or keep current selection
                if (state.shippingCost === 0 && !state.freeShippingApplied) {
                    selectShipping('std', 0);
                }
            }
        }

        function validateRentalRestrictions() {
            const hasRental = state.cart.some(i => i.type === 'rental');
            const isInternational = state.addressCountry !== 'UK';
            const warningEl = document.getElementById('rental-warning');
            const payBtn = document.getElementById('pay-btn');

            if (hasRental && isInternational) {
                if(warningEl) warningEl.classList.remove('hidden');
                if(payBtn) {
                    payBtn.disabled = true;
                    payBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    payBtn.innerHTML = '<span>Region Not Supported</span>';
                }
            } else {
                if(warningEl) warningEl.classList.add('hidden');
                if(payBtn) {
                    payBtn.disabled = false;
                    payBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    payBtn.innerHTML = '<span>PAY SECURELY NOW</span><i class="fas fa-arrow-right"></i>';
                }
            }
        }

        function getCountryLabel(code) {
            switch((code||'').toUpperCase()) {
                case 'UK': return 'United Kingdom';
                case 'US': return 'United States';
                case 'VN': return 'Vietnam';
                case 'OTHER': return 'Other';
                default: return code;
            }
        }

        // --- RENDER CART ---
        function renderCart() {
            const container = document.getElementById('cart-items-container');
            if(state.cart.length === 0) {
                container.innerHTML = '<div class="text-center py-8 text-slate-500">No items in cart</div>';
                return;
            }
            container.innerHTML = state.cart.map(item => `
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-bold text-sm dark:text-white">${item.name}</p>
                        <p class="text-xs text-slate-500 uppercase">${item.type}</p>
                    </div>
                    <p class="font-mono font-bold text-sm dark:text-white">$${item.price.toFixed(2)}</p>
                </div>
            `).join('');
        }

        // --- ADDRESS LOGIC ---
        function toggleAddressEdit() {
            const staticView = document.getElementById('addr-static');
            const formView = document.getElementById('addr-form');
            const btn = document.getElementById('edit-addr-btn');
            
            if (formView.classList.contains('hidden')) {
                // populate form inputs from displayed values
                const name = document.getElementById('display-name').innerText;
                const addr = document.getElementById('display-addr').innerHTML.replace(/<br\s*\/?/gi, ', ');
                const phone = document.getElementById('display-phone').innerText;
                document.getElementById('input-name').value = name;
                document.getElementById('input-addr').value = addr;
                document.getElementById('input-phone').value = phone;
                document.getElementById('input-country').value = state.addressCountry || 'UK';

                staticView.classList.add('hidden');
                formView.classList.remove('hidden');
                btn.innerText = 'Cancel';
            } else {
                staticView.classList.remove('hidden');
                formView.classList.add('hidden');
                btn.innerText = 'Change';
            }
        }

        function saveAddress() {
            const name = document.getElementById('input-name').value;
            const addr = document.getElementById('input-addr').value;
            const phone = document.getElementById('input-phone').value;
            const country = document.getElementById('input-country').value;

            document.getElementById('display-name').innerText = name;
            document.getElementById('display-addr').innerHTML = addr.replace(',', ',<br>');
            document.getElementById('display-phone').innerText = phone;
            document.getElementById('display-country').innerText = (country === 'UK') ? 'United Kingdom' : document.getElementById('input-country').options[document.getElementById('input-country').selectedIndex].text;
            state.addressCountry = country;
            updateShippingAvailability();
            validateRentalRestrictions();
            updateSummary();
            
            toggleAddressEdit(); 
        }

        function updateShippingAvailability() {
            const notUK = state.addressCountry !== 'UK';
            const exp = document.getElementById('ship-exp');
            const inst = document.getElementById('ship-inst');
            if (exp && inst) {
                if (notUK) {
                    exp.classList.add('disabled-option');
                    inst.classList.add('disabled-option');
                } else {
                    exp.classList.remove('disabled-option');
                    inst.classList.remove('disabled-option');
                }
            }
            // ensure selected shipping is allowed
            const active = document.querySelector('.selection-card.active');
            if (active && (active.id === 'ship-exp' || active.id === 'ship-inst') && notUK) {
                selectShipping('std', 0);
            }
        }

        // --- SHIPPING LOGIC ---
        function selectShipping(type, cost) {
            // prevent selecting express/instant when not UK
            if ((type === 'exp' || type === 'inst') && state.addressCountry !== 'UK') {
                alert('Express and instant delivery only available within the United Kingdom.');
                return;
            }
            state.shippingCost = cost;
            
            document.querySelectorAll('#ship-std, #ship-exp, #ship-inst').forEach(el => {
                el.classList.remove('active');
            });

            document.getElementById(`ship-${type}`).classList.add('active');
            updateSummary();
        }

        // --- CARD LOGIC ---
        function renderSavedCards() {
            const container = document.getElementById('saved-cards-list');
            if(!container) return;
            container.innerHTML = '';

            state.savedCards.forEach((card, index) => {
                const isSelected = (index === state.selectedCardIndex);
                const style = getCardTypeStyle(card.number);
                
                const div = document.createElement('div');
                div.className = `relative p-4 rounded-xl border-2 cursor-pointer transition-all duration-300 overflow-hidden group ${isSelected ? 'border-brand bg-brand/5 dark:bg-brand/10 shadow-md' : 'border-slate-200 dark:border-dark-border hover:border-brand/50'}`;
                div.onclick = (e) => selectSavedCard(index, e);
                
                div.innerHTML = `
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-slate-800 dark:text-white font-bold tracking-widest font-mono text-lg">
                            •••• ${card.number.slice(-4)}
                        </div>
                        <div class="text-brand">
                            ${style.icon}
                        </div>
                    </div>
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Card Holder</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase truncate max-w-[120px]">${card.name}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">Expires</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 font-mono">${card.exp}</p>
                        </div>
                    </div>
                    ${isSelected ? '<div class="absolute top-2 right-2 w-3 h-3 bg-brand rounded-full shadow-glow-sm"></div>' : ''}
                `;
                container.appendChild(div);
            });

            // Add New Card Button (Link)
            const addBtn = document.createElement('a');
            addBtn.href = '../PHP_User/userAddCard.php';
            addBtn.className = 'flex flex-col items-center justify-center p-4 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-600 text-slate-400 hover:text-brand hover:border-brand hover:bg-brand/5 transition-all duration-300 gap-2 group min-h-[100px]';
            addBtn.innerHTML = `
                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-plus text-lg"></i>
                </div>
                <span class="text-xs font-bold uppercase tracking-wide">Add New Card</span>
            `;
            container.appendChild(addBtn);
        }

        function selectSavedCard(index, e) {
            if(e) e.stopPropagation();
            state.selectedCardIndex = index;
            renderSavedCards();
            updateSummary();
        }

        function getCardTypeStyle(number) {
            const cleanNum = number.replace(/\s/g, '');
            if(cleanNum.startsWith('4')) return { class: 'card-visa', icon: '<i class="fab fa-cc-visa text-3xl opacity-90"></i>' };
            if(cleanNum.startsWith('5')) return { class: 'card-mastercard', icon: '<i class="fab fa-cc-mastercard text-3xl opacity-90"></i>' };
            if(cleanNum.startsWith('9704')) return { class: 'card-napas', icon: '<span class="font-bold italic text-xl">NAPAS</span>' };
            return { class: 'bg-slate-700', icon: '<i class="fas fa-credit-card text-2xl"></i>' };
        }

        // --- PAYMENT LOGIC ---
        function selectPayment(method) {
            state.paymentMethod = method;
            const methods = ['card', 'wallet', 'cod'];
            
            methods.forEach(m => {
                const el = document.getElementById(`pay-${m}`);
                if(m === method) {
                    el.classList.add('active');
                    if(m === 'card') {
                        // Show saved list by default
                        document.getElementById('saved-cards-list').classList.remove('hidden');
                    }
                    if(m === 'wallet') document.getElementById('wallet-hint').classList.remove('hidden');
                } else {
                    el.classList.remove('active');
                    if(m === 'card') {
                        document.getElementById('saved-cards-list').classList.add('hidden');
                    }
                    if(m === 'wallet') document.getElementById('wallet-hint').classList.add('hidden');
                }
            });
            updateSummary();
        }

        // --- CALCULATIONS ---
        function updateSummary() {
            state.subtotal = state.cart.reduce((sum, item) => sum + parseFloat(item.price), 0);
            state.tax = state.subtotal * 0.08;
            const effectiveShipping = state.freeShippingApplied ? 0 : state.shippingCost;
            
            // Visa Discount Logic
            let visaDiscount = 0;
            if (state.paymentMethod === 'card' && state.savedCards.length > 0) {
                const card = state.savedCards[state.selectedCardIndex];
                if (card && card.number.replace(/\D/g, '').startsWith('4')) {
                    visaDiscount = state.subtotal * 0.05;
                }
            }

            // International Taxes
            let importTax = 0;
            let airFreight = 0;
            if (state.addressCountry !== 'UK') {
                importTax = state.subtotal * 0.10;
                airFreight = 20.00;
            }

            state.total = state.subtotal + state.tax + effectiveShipping + importTax + airFreight - state.discount - visaDiscount;

            document.getElementById('subtotal').innerText = `$${state.subtotal.toFixed(2)}`;
            document.getElementById('tax').innerText = `$${state.tax.toFixed(2)}`;
            
            // Update International Tax Rows
            const importRow = document.getElementById('import-tax-row');
            const airRow = document.getElementById('air-tax-row');
            
            if (importRow) {
                if (importTax > 0) {
                    importRow.classList.remove('hidden');
                    document.getElementById('import-tax').innerText = `$${importTax.toFixed(2)}`;
                } else {
                    importRow.classList.add('hidden');
                }
            }
            
            if (airRow) {
                if (airFreight > 0) {
                    airRow.classList.remove('hidden');
                    document.getElementById('air-tax').innerText = `$${airFreight.toFixed(2)}`;
                } else {
                    airRow.classList.add('hidden');
                }
            }

            const shipDisplay = (state.freeShippingApplied || state.shippingCost === 0) ? 'Free' : `$${state.shippingCost.toFixed(2)}`;
            document.getElementById('shipping-display').innerText = shipDisplay;
            
            // show/hide promo row
            const promoRow = document.getElementById('promo-row');
            if (state.discount > 0) {
                promoRow.classList.remove('hidden');
                document.getElementById('discount-amount').innerText = `- $${state.discount.toFixed(2)}`;
            } else if (state.freeShippingApplied) {
                promoRow.classList.remove('hidden');
                document.getElementById('discount-amount').innerText = `- Free Shipping`;
            } else {
                promoRow.classList.add('hidden');
            }

            // Visa Discount Row
            const visaRow = document.getElementById('visa-discount-row');
            if (visaRow) {
                if (visaDiscount > 0) {
                    visaRow.classList.remove('hidden');
                    document.getElementById('visa-discount-amount').innerText = `- $${visaDiscount.toFixed(2)}`;
                } else {
                    visaRow.classList.add('hidden');
                }
            }

            document.getElementById('final-total').innerText = `$${state.total.toFixed(2)}`;
        }

        function applyPromo() {
            const input = document.getElementById('promo-input');
            const row = document.getElementById('promo-row');
            const code = (input.value || '').toUpperCase().trim();
            if (!code) { alert('Please enter a promo code or select a voucher.'); return; }

            // check predefined vouchers first
            const v = vouchers.find(x => x.code === code);
            if (v) {
                applyVoucher(v.code);
                alert('Voucher applied: ' + v.label);
                return;
            }

            // legacy code 'PROPLAY' remains as a 20$ discount
            if (code === 'PROPLAY') {
                if (!state.appliedVoucher || state.appliedVoucher !== 'PROPLAY') {
                    clearVoucher();
                    state.discount = 20;
                    state.appliedVoucher = 'PROPLAY';
                    state.freeShippingApplied = false;
                    updateSummary();
                    alert('Promo code applied!');
                    return;
                }
            }

            alert('Invalid Code');
        }

        function renderVouchers() {
            const container = document.getElementById('voucher-list');
            if (!container) return;
            container.innerHTML = vouchers.map(v => `
                <button onclick="applyVoucher('${v.code}')" id="v-${v.code}" class="px-3 py-2 rounded border selection-card text-sm">${v.label}</button>
            `).join('');
        }

        function applyVoucher(code) {
            const v = vouchers.find(x => x.code === code);
            if (!v) return;
            // clear any previous voucher effects
            clearVoucher();
            state.appliedVoucher = v.code;
            document.getElementById('promo-input').value = v.code;
            if (v.type === 'amount') {
                state.discount = v.value;
                state.freeShippingApplied = false;
            } else if (v.type === 'shipping') {
                state.discount = 0;
                state.freeShippingApplied = true;
            } else if (v.type === 'percent') {
                state.discount = (state.subtotal * (v.value/100));
                state.freeShippingApplied = false;
            }
            // mark UI
            const btn = document.getElementById('v-' + v.code);
            if (btn) btn.classList.add('active');
            updateSummary();
        }

        function clearVoucher() {
            // remove active from all voucher buttons
            vouchers.forEach(v => {
                const b = document.getElementById('v-' + v.code);
                if (b) b.classList.remove('active');
            });
            state.discount = 0;
            state.freeShippingApplied = false;
            state.appliedVoucher = null;
            document.getElementById('promo-input').value = '';
            updateSummary();
        }

        // --- PROCESS ORDER ---
        function processOrder() {
            const btn = document.getElementById('pay-btn');
            // If using wallet (QR), open QR modal and require verification
            if (state.paymentMethod === 'wallet') {
                openQRModal();
                return;
            }

            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Processing...';
            btn.disabled = true;
            btn.classList.add('opacity-75');

            setTimeout(() => {
                showInvoice();
                btn.innerHTML = '<span>PAY SECURELY NOW</span><i class="fas fa-arrow-right"></i>';
                btn.disabled = false;
                btn.classList.remove('opacity-75');
            }, 1200);
        }

        function showInvoice() {
            const overlay = document.getElementById('invoice-overlay');
            const now = new Date();
            
            document.getElementById('inv-date').innerText = now.toLocaleDateString();
            document.getElementById('inv-id').innerText = '#PP-' + Math.floor(Math.random()*100000);
            document.getElementById('inv-method').innerText = state.paymentMethod;
            
            document.getElementById('inv-sub').innerText = `$${state.subtotal.toFixed(2)}`;
            document.getElementById('inv-ship').innerText = `$${state.shippingCost.toFixed(2)}`;
            document.getElementById('inv-tax').innerText = `$${state.tax.toFixed(2)}`;
            document.getElementById('inv-total').innerText = `$${state.total.toFixed(2)}`;

            // Build invoice data object
            const invoiceData = {
                id: document.getElementById('inv-id').innerText,
                date: document.getElementById('inv-date').innerText + ' ' + now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}),
                method: document.getElementById('inv-method').innerText,
                details: {
                    sub: document.getElementById('inv-sub').innerText,
                    ship: document.getElementById('inv-ship').innerText,
                    tax: document.getElementById('inv-tax').innerText
                },
                total: document.getElementById('inv-total').innerText,
                items: state.cart.map(i => ({ 
                    name: i.name, 
                    price: `$${parseFloat(i.price).toFixed(2)}`,
                    img: i.img || i.image || 'https://api.dicebear.com/7.x/shapes/svg?seed=' + i.name,
                    count: i.qty || 1,
                    type: i.type,
                    subType: i.subType
                })),
                status: 'processing',
                type: 'physical'
            };

            // Simple logic to determine type
            if (invoiceData.items.some(i => i.name.toLowerCase().includes('wallet') || i.name.toLowerCase().includes('card') || i.name.toLowerCase().includes('digital'))) {
                invoiceData.type = 'digital';
                invoiceData.digitalCode = 'CODE-' + Math.random().toString(36).substring(2, 14).toUpperCase();
                invoiceData.status = 'completed';
            } else if (invoiceData.items.some(i => i.name.toLowerCase().includes('rental'))) {
                invoiceData.type = 'rental';
                invoiceData.status = 'active';
            }

            // push a notification so it appears in the user's notification center
            try { pushOrderNotification(invoiceData); } catch(e) { console.warn('pushOrderNotification failed', e); }
            
            // Save to History
            try { saveOrderToHistory(invoiceData); } catch(e) { console.warn('saveOrderToHistory failed', e); }

            overlay.classList.remove('hidden');
            createConfetti();
        }

        function saveOrderToHistory(order) {
            const key = 'pp_orders';
            let list = [];
            try { list = JSON.parse(localStorage.getItem(key) || '[]'); } catch(e) { list = []; }
            list.unshift(order);
            localStorage.setItem(key, JSON.stringify(list));
        }

        // Persist a notification to localStorage so notification page can show it.
        function pushOrderNotification(invoice) {
            const key = 'pp_notifications';
            let list = [];
            try { list = JSON.parse(localStorage.getItem(key) || '[]'); } catch(e) { list = []; }

            const id = invoice.id || ('PP-' + Date.now());
            const now = new Date();
            const notif = {
                id: id,
                title: 'Order Successful',
                message: `Order ${id} completed. Total ${invoice.total}`,
                date: now.toISOString(),
                invoice: invoice
            };

            // prepend newest
            list.unshift(notif);
            // keep a reasonable limit
            if (list.length > 50) list = list.slice(0,50);
            localStorage.setItem(key, JSON.stringify(list));
        }

        function createConfetti() {
            const colors = ['#ec003f', '#00d4ff', '#00ff88', '#ffaa00'];
            const confettiCount = 30;

            for (let i = 0; i < confettiCount; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti-piece';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.top = '-10px';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.width = (Math.random() * 10 + 5) + 'px';
                    confetti.style.height = (Math.random() * 10 + 5) + 'px';
                    confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                    document.body.appendChild(confetti);
                    setTimeout(() => confetti.remove(), 3000);
                }, i * 30);
            }
        }

        // --- QR Wallet Flow ---
        function openQRModal() {
            const overlay = document.getElementById('qr-overlay');
            const qrImg = document.getElementById('qr-img');
            const txId = 'QR-' + Math.floor(Math.random()*1000000);
            const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' + encodeURIComponent('ProPlayHub|'+txId+'|'+(state.total.toFixed(2)));
            qrImg.src = qrUrl;
            document.getElementById('qr-tx').innerText = txId;
            overlay.classList.remove('hidden');
            startQRCountdown(90);
        }

        function closeQRModal() {
            const overlay = document.getElementById('qr-overlay');
            overlay.classList.add('hidden');
            // stop countdown if any
            clearInterval(window._qrCountdownInterval);
        }

        function startQRCountdown(seconds) {
            const el = document.getElementById('qr-countdown');
            let remaining = seconds;
            el.innerText = formatTime(remaining);
            clearInterval(window._qrCountdownInterval);
            window._qrCountdownInterval = setInterval(() => {
                remaining -= 1;
                el.innerText = formatTime(remaining);
                if (remaining <= 0) {
                    clearInterval(window._qrCountdownInterval);
                    document.getElementById('qr-status').innerText = 'Code expired. Please retry.';
                    document.getElementById('qr-pay-btn').disabled = true;
                }
            }, 1000);
        }

        function formatTime(sec) {
            const m = Math.floor(sec/60).toString().padStart(2,'0');
            const s = (sec%60).toString().padStart(2,'0');
            return m + ':' + s;
        }

        function verifyWalletPayment() {
            const status = document.getElementById('qr-status');
            const btn = document.getElementById('qr-pay-btn');
            // show loading state
            btn.disabled = true;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Verifying...';
            btn.classList.add('opacity-80');
            status.innerText = 'Verifying payment...';

            // simulate server-side verification (would normally poll backend)
            setTimeout(() => {
                // success
                state.walletVerified = true;
                status.innerText = 'Payment confirmed! Completing order...';
                // show success state on button
                btn.innerHTML = '<i class="fas fa-check mr-2"></i>Confirmed';
                btn.classList.remove('opacity-80');
                btn.classList.add('bg-emerald-600');

                // small delay to let user see success, then close modal and show invoice
                setTimeout(() => {
                    closeQRModal();
                    // restore button for next time
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('bg-emerald-600');
                    btn.disabled = false;
                    setTimeout(() => showInvoice(), 400);
                }, 800);
            }, 1600);
        }
    </script>
</body>
</html>