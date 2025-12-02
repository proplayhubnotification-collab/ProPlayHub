<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#f8fafc">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#020618">

    <title>Add New Card - ProPlay Hub</title>
    
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
        
        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 dark:bg-[#020618] dark:text-[#f8fafc] transition-colors duration-300 min-h-screen font-sans">

    <header class="sticky top-0 z-40 bg-[#f8fafc]/90 dark:bg-[#020618]/90 backdrop-blur-md border-b border-slate-200 dark:border-dark-border px-4 py-4 flex items-center justify-between">
        <button onclick="window.history.back()" class="w-10 h-10 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 flex items-center justify-center transition-colors">
            <i class="fas fa-arrow-left text-lg"></i>
        </button>
        <h1 class="text-lg font-bold uppercase tracking-wider">Add New Card</h1>
        <div class="w-10"></div> 
    </header>

    <main class="max-w-md mx-auto px-4 py-6">
        
        <!-- Live Preview -->
        <div id="card-preview" class="credit-card bg-slate-800 p-5 text-white relative mb-8 transition-colors duration-500 h-56 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="w-12 h-9 card-chip"></div>
                <div id="preview-logo" class="text-3xl opacity-90"><i class="fas fa-credit-card"></i></div>
            </div>
            
            <div id="preview-number" class="font-mono text-xl tracking-widest drop-shadow-md text-center">
                **** **** **** ****
            </div>
            
            <div class="flex justify-between items-end">
                <div>
                    <p class="text-[10px] uppercase opacity-70 mb-1">Card Holder</p>
                    <p id="preview-name" class="font-bold text-sm tracking-wide uppercase truncate max-w-[150px]">YOUR NAME</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] uppercase opacity-70 mb-1">Expires</p>
                    <p id="preview-expiry" class="font-bold text-sm tracking-wide">MM/YY</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-surface p-6 rounded-2xl border border-slate-200 dark:border-dark-border shadow-lg space-y-5 animate-fade-in">
            <div class="relative">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Card Number</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-slate-400"><i class="fas fa-hashtag text-sm"></i></span>
                    <input type="text" id="input-card-num" oninput="updateCardPreview()" maxlength="19" placeholder="0000 0000 0000 0000" class="w-full bg-slate-50 dark:bg-dark-bg border border-slate-200 dark:border-dark-border rounded-xl px-4 py-3 pl-10 text-base outline-none focus:border-brand focus:ring-1 focus:ring-brand/20 font-mono transition-all dark:text-white">
                </div>
            </div>

            <div class="relative">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Card Holder Name</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-slate-400"><i class="fas fa-user text-sm"></i></span>
                    <input type="text" id="input-card-name" oninput="updateCardPreview()" placeholder="ALEX HUNTER" class="w-full bg-slate-50 dark:bg-dark-bg border border-slate-200 dark:border-dark-border rounded-xl px-4 py-3 pl-10 text-base outline-none focus:border-brand focus:ring-1 focus:ring-brand/20 uppercase transition-all dark:text-white">
                </div>
            </div>

            <div class="flex gap-4">
                <div class="relative flex-1">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Expiry Date</label>
                    <input type="text" id="input-card-exp" oninput="updateCardPreview()" maxlength="5" placeholder="MM/YY" class="w-full bg-slate-50 dark:bg-dark-bg border border-slate-200 dark:border-dark-border rounded-xl px-4 py-3 text-center text-base outline-none focus:border-brand focus:ring-1 focus:ring-brand/20 font-mono transition-all dark:text-white">
                </div>
                <div class="relative flex-1">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">CVC / CVV</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-slate-400"><i class="fas fa-lock text-sm"></i></span>
                        <input type="text" maxlength="3" placeholder="123" class="w-full bg-slate-50 dark:bg-dark-bg border border-slate-200 dark:border-dark-border rounded-xl px-4 py-3 pl-10 text-base outline-none focus:border-brand focus:ring-1 focus:ring-brand/20 font-mono transition-all dark:text-white">
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button onclick="saveCard()" class="w-full py-4 bg-brand text-white font-bold rounded-xl shadow-glow hover:bg-red-600 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Save Card</span>
                </button>
            </div>
        </div>

        <div class="mt-6 text-center">
            <p class="text-xs text-slate-400">
                <i class="fas fa-shield-alt mr-1"></i> Your card information is encrypted and secure.
            </p>
        </div>

    </main>

    <script>
        // --- THEME SYNC ---
        const themeQuery = window.matchMedia('(prefers-color-scheme: dark)');
        function handleThemeChange(e) { if(e.matches) document.documentElement.classList.add('dark'); else document.documentElement.classList.remove('dark'); }
        themeQuery.addListener(handleThemeChange); handleThemeChange(themeQuery);
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) document.documentElement.classList.add('dark');

        function updateCardPreview() {
            const numInput = document.getElementById('input-card-num');
            const nameInput = document.getElementById('input-card-name');
            const expInput = document.getElementById('input-card-exp');
            
            const previewNum = document.getElementById('preview-number');
            const previewName = document.getElementById('preview-name');
            const previewExp = document.getElementById('preview-expiry');
            const previewLogo = document.getElementById('preview-logo');
            const previewCard = document.getElementById('card-preview');

            // Format Number
            let val = numInput.value.replace(/\D/g, '');
            let formatted = '';
            for(let i = 0; i < val.length; i++) {
                if(i > 0 && i % 4 === 0) formatted += ' ';
                formatted += val[i];
            }
            numInput.value = formatted;
            previewNum.innerText = formatted || '**** **** **** ****';

            // Detect Type
            previewCard.className = 'credit-card p-5 text-white relative mb-8 transition-colors duration-500 h-56 flex flex-col justify-between';
            if(val.startsWith('4')) {
                previewCard.classList.add('card-visa');
                previewLogo.innerHTML = '<i class="fab fa-cc-visa text-4xl"></i>';
            } else if(val.startsWith('5')) {
                previewCard.classList.add('card-mastercard');
                previewLogo.innerHTML = '<i class="fab fa-cc-mastercard text-4xl"></i>';
            } else if(val.startsWith('9704')) {
                previewCard.classList.add('card-napas');
                previewLogo.innerHTML = '<span class="font-bold italic text-2xl">NAPAS</span>';
            } else {
                previewCard.style.background = 'linear-gradient(135deg, #1e293b 0%, #0f172a 100%)';
                previewLogo.innerHTML = '<i class="fas fa-credit-card text-3xl"></i>';
            }

            // Update Name & Exp
            previewName.innerText = nameInput.value || 'YOUR NAME';
            
            // Format Exp
            let expVal = expInput.value.replace(/\D/g, '');
            if(expVal.length >= 2) {
                expVal = expVal.substring(0,2) + '/' + expVal.substring(2,4);
            }
            expInput.value = expVal;
            previewExp.innerText = expVal || 'MM/YY';
        }

        function saveCard() {
            const num = document.getElementById('input-card-num').value;
            const name = document.getElementById('input-card-name').value;
            const exp = document.getElementById('input-card-exp').value;
            
            if(num.length < 16 || !name || !exp) {
                alert('Please fill in all card details');
                return;
            }

            // Get existing cards
            let savedCards = [];
            try {
                savedCards = JSON.parse(localStorage.getItem('pp_saved_cards') || '[]');
            } catch(e) { savedCards = []; }

            // Add new card
            savedCards.push({
                number: num,
                name: name.toUpperCase(),
                exp: exp
            });

            // Save back
            localStorage.setItem('pp_saved_cards', JSON.stringify(savedCards));
            
            // Redirect back
            window.history.back();
        }
    </script>
</body>
</html>