<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Live Support</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                fontFamily: { sans: ['"Inter"', 'sans-serif'] },
                colors: { 
                    brand: '#ec003f', 
                    dark: { 
                        bg: '#020618',      
                        surface: '#0f172a', 
                        border: '#1e293b' 
                    } 
                }
            }
        }
      }
    </script>
    <style>
        body { -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Typing Animation */
        .typing-dot {
            animation: typing 1.4s infinite ease-in-out both;
        }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        
        @keyframes typing {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-[#020618] dark:bg-[#020618] dark:text-[#f8fafc] transition-colors duration-300 h-screen flex flex-col font-sans">

    <!-- Header -->
    <header class="sticky top-0 z-40 bg-[#f8fafc]/90 dark:bg-[#020618]/90 backdrop-blur-md border-b border-slate-200 dark:border-dark-border px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-brand/10 flex items-center justify-center text-brand relative">
                <i id="header-icon" class="fas fa-robot text-xl"></i>
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white dark:border-dark-bg rounded-full"></span>
            </div>
            <div>
                <h1 id="header-title" class="text-lg font-bold">ProPlay Bot</h1>
                <div class="flex items-center gap-1.5">
                    <span class="text-xs text-slate-500 dark:text-slate-400" id="header-status">Automated Support</span>
                </div>
            </div>
        </div>
        <button class="w-10 h-10 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 flex items-center justify-center text-slate-400 transition-colors">
            <i class="fas fa-ellipsis-v"></i>
        </button>
    </header>

    <!-- Chat Area -->
    <main id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4 no-scrollbar pb-24">
        <!-- Messages will be inserted here dynamically -->
    </main>

    <!-- Input Area -->
    <div class="fixed bottom-[70px] left-0 w-full px-4 pb-4 pt-2 bg-gradient-to-t from-[#f8fafc] via-[#f8fafc] to-transparent dark:from-[#020618] dark:via-[#020618] z-30">
        <!-- Quick Options Container -->
        <div id="quick-options" class="max-w-lg mx-auto flex gap-2 overflow-x-auto no-scrollbar mb-2 pb-1">
            <!-- Options injected via JS -->
        </div>

        <div class="max-w-lg mx-auto relative flex items-end gap-2 bg-white dark:bg-dark-surface p-2 rounded-3xl shadow-lg border border-slate-100 dark:border-dark-border">
            <button class="w-10 h-10 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 transition-colors flex items-center justify-center shrink-0">
                <i class="fas fa-plus"></i>
            </button>
            <textarea id="chat-input" rows="1" class="flex-1 bg-transparent border-0 focus:ring-0 text-sm py-3 max-h-24 resize-none text-slate-900 dark:text-white placeholder-slate-400" placeholder="Type a message..."></textarea>
            <button onclick="sendMessage()" class="w-10 h-10 rounded-full bg-brand hover:bg-brand/90 text-white shadow-md transition-colors flex items-center justify-center shrink-0 mb-0.5">
                <i class="fas fa-paper-plane text-sm translate-x-[-1px] translate-y-[1px]"></i>
            </button>
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

        // State
        let isAgentActive = false;
        const container = document.getElementById('chat-container');
        const input = document.getElementById('chat-input');
        const optionsContainer = document.getElementById('quick-options');

        // Auto-resize textarea
        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Initialize Chat
        document.addEventListener('DOMContentLoaded', () => {
            addSystemMessage();
            setTimeout(() => {
                addBotMessage("Hello! I'm the ProPlay Bot. How can I help you today?");
                showOptions([
                    { text: "Check Order Status", action: "order" },
                    { text: "Refund Policy", action: "refund" },
                    { text: "Technical Issue", action: "tech" },
                    { text: "Chat with Support Agent", action: "agent", highlight: true }
                ]);
            }, 500);
        });

        function addSystemMessage() {
            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const html = `
                <div class="flex justify-center animate-fade-in">
                    <span class="text-[10px] font-medium text-slate-400 bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-full">Today, ${time}</span>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
        }

        function addBotMessage(text) {
            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const html = `
                <div class="flex gap-3 max-w-[85%] animate-fade-in">
                    <div class="w-8 h-8 rounded-full bg-brand flex items-center justify-center text-white shrink-0 mt-1">
                        <i class="fas fa-robot text-xs"></i>
                    </div>
                    <div class="space-y-1">
                        <div class="bg-white dark:bg-dark-surface border border-slate-100 dark:border-dark-border p-3 rounded-2xl rounded-tl-none shadow-sm">
                            <p class="text-sm leading-relaxed">${text}</p>
                        </div>
                        <span class="text-[10px] text-slate-400 ml-1">${time}</span>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            scrollToBottom();
        }

        function addAgentMessage(text) {
            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const html = `
                <div class="flex gap-3 max-w-[85%] animate-fade-in">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white shrink-0 mt-1">
                        <i class="fas fa-headset text-xs"></i>
                    </div>
                    <div class="space-y-1">
                        <div class="bg-white dark:bg-dark-surface border border-slate-100 dark:border-dark-border p-3 rounded-2xl rounded-tl-none shadow-sm">
                            <p class="text-sm leading-relaxed">${text}</p>
                        </div>
                        <span class="text-[10px] text-slate-400 ml-1">Sarah • ${time}</span>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            scrollToBottom();
        }

        function addUserMessage(text) {
            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const html = `
                <div class="flex gap-3 max-w-[85%] ml-auto flex-row-reverse animate-fade-in">
                    <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-300 shrink-0 mt-1 overflow-hidden">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix&backgroundColor=1e293b" class="w-full h-full object-cover" alt="User">
                    </div>
                    <div class="space-y-1">
                        <div class="bg-brand text-white p-3 rounded-2xl rounded-tr-none shadow-sm">
                            <p class="text-sm leading-relaxed">${text}</p>
                        </div>
                        <span class="text-[10px] text-slate-400 mr-1 text-right block">${time}</span>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            scrollToBottom();
        }

        function showOptions(opts) {
            optionsContainer.innerHTML = '';
            opts.forEach(opt => {
                const btn = document.createElement('button');
                btn.className = `whitespace-nowrap px-4 py-2 rounded-full text-xs font-medium transition-all active:scale-95 ${
                    opt.highlight 
                    ? 'bg-brand text-white shadow-md hover:bg-brandHover' 
                    : 'bg-white dark:bg-dark-surface border border-slate-200 dark:border-dark-border text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5'
                }`;
                btn.textContent = opt.text;
                btn.onclick = () => handleOptionClick(opt);
                optionsContainer.appendChild(btn);
            });
        }

        function showTyping() {
            const html = `
                <div id="typing-indicator" class="flex gap-3 max-w-[85%] animate-fade-in">
                    <div class="w-8 h-8 rounded-full bg-brand flex items-center justify-center text-white shrink-0 mt-1">
                        <i class="fas fa-robot text-xs"></i>
                    </div>
                    <div class="bg-white dark:bg-dark-surface border border-slate-100 dark:border-dark-border p-4 rounded-2xl rounded-tl-none shadow-sm flex items-center gap-1">
                        <div class="w-1.5 h-1.5 bg-slate-400 rounded-full typing-dot"></div>
                        <div class="w-1.5 h-1.5 bg-slate-400 rounded-full typing-dot"></div>
                        <div class="w-1.5 h-1.5 bg-slate-400 rounded-full typing-dot"></div>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            scrollToBottom();
        }

        function hideTyping() {
            const el = document.getElementById('typing-indicator');
            if(el) el.remove();
        }

        function addOrderCard() {
            const html = `
                <div class="flex gap-3 max-w-[85%] animate-fade-in">
                    <div class="w-8 h-8 rounded-full bg-brand flex items-center justify-center text-white shrink-0 mt-1">
                        <i class="fas fa-robot text-xs"></i>
                    </div>
                    <div class="space-y-2 w-full">
                        <div class="bg-white dark:bg-dark-surface border border-slate-100 dark:border-dark-border p-3 rounded-2xl rounded-tl-none shadow-sm">
                            <p class="text-sm leading-relaxed">I found a recent order in progress. Is this the one you are asking about?</p>
                        </div>
                        
                        <!-- Order Card -->
                        <div class="bg-white dark:bg-dark-surface border border-slate-100 dark:border-dark-border rounded-xl p-3 shadow-sm hover:shadow-md transition-shadow cursor-pointer">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-xs font-bold text-slate-500 uppercase">Order #82910</p>
                                    <h4 class="font-bold text-sm">Razer DeathAdder V3</h4>
                                </div>
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-600 text-[10px] font-bold rounded-full">Shipping</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-700 h-1.5 rounded-full mb-2 overflow-hidden">
                                <div class="bg-brand h-full w-3/4 rounded-full"></div>
                            </div>
                            <div class="flex justify-between text-[10px] text-slate-400">
                                <span>Shipped</span>
                                <span>Arriving Tomorrow</span>
                            </div>
                        </div>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            scrollToBottom();
        }

        function handleOptionClick(opt) {
            addUserMessage(opt.text);
            optionsContainer.innerHTML = ''; // Clear options

            if (opt.action === 'agent') {
                switchToAgentMode();
            } else {
                showTyping();
                // Simulate bot reply
                setTimeout(() => {
                    hideTyping();
                    
                    if(opt.action === 'order') {
                        addOrderCard();
                    } else {
                        let reply = "I can help with that. Please check our FAQ section for more details.";
                        if(opt.action === 'refund') reply = "Refunds are processed within 5-7 business days. Do you want to start a request?";
                        if(opt.action === 'tech') reply = "Please describe the issue you are facing with your device.";
                        addBotMessage(reply);
                    }
                    
                    // Show options again
                    setTimeout(() => {
                        showOptions([
                            { text: "Yes, correct", action: "yes" },
                            { text: "No, other order", action: "no" },
                            { text: "Chat with Support Agent", action: "agent", highlight: true }
                        ]);
                    }, 1000);
                }, 1500);
            }
        }

        function switchToAgentMode() {
            setTimeout(() => {
                addBotMessage("Connecting you to a support agent... Please wait a moment.");
                
                setTimeout(() => {
                    // Change Header
                    document.getElementById('header-title').textContent = "Sarah (Support)";
                    document.getElementById('header-status').textContent = "Typing...";
                    document.getElementById('header-icon').className = "fas fa-headset text-xl";
                    document.querySelector('.bg-brand\\/10').classList.replace('bg-brand/10', 'bg-blue-500/10');
                    document.querySelector('.text-brand').classList.replace('text-brand', 'text-blue-600');

                    isAgentActive = true;

                    setTimeout(() => {
                        document.getElementById('header-status').textContent = "Online";
                        addAgentMessage("Hello! I am Sarah, your support agent. I'm very happy to assist you today. How can I help?");
                    }, 1500);

                }, 2000);
            }, 500);
        }

        function sendMessage() {
            const text = input.value.trim();
            if(!text) return;

            addUserMessage(text);
            input.value = '';
            input.style.height = 'auto';

            if (isAgentActive) {
                // Simulate Agent Reply
                setTimeout(() => {
                    document.getElementById('header-status').textContent = "Typing...";
                    setTimeout(() => {
                        document.getElementById('header-status').textContent = "Online";
                        addAgentMessage("Thank you for the details. Let me check that for you right away.");
                    }, 2000);
                }, 1000);
            } else {
                // Bot Fallback
                setTimeout(() => {
                    addBotMessage("I'm not sure I understand. Would you like to speak to a human agent?");
                    showOptions([{ text: "Chat with Support Agent", action: "agent", highlight: true }]);
                }, 1000);
            }
        }

        function scrollToBottom() {
            container.scrollTop = container.scrollHeight;
        }
    </script>
</body>
</html>