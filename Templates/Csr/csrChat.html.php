<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSR Messenger</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
    
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
                    light: { bg: '#f8fafc', text: '#020618' },
                    dark: {
                        bg: '#020618',
                        text: '#f8fafc',
                        surface: '#1e293b',
                        border: '#334155'
                    }
                }
            }
        }
      }
    </script>
    <style>
        body { -webkit-tap-highlight-color: transparent; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-light-bg text-light-text dark:bg-dark-bg dark:text-dark-text transition-colors duration-300 min-h-screen font-sans">

    <main class="max-w-md mx-auto min-h-screen relative bg-white dark:bg-dark-bg shadow-2xl overflow-hidden flex flex-col">
        
        <!-- Header -->
        <header class="px-6 pt-8 pb-4 flex items-center gap-4 sticky top-0 z-40 bg-white/90 dark:bg-dark-bg/90 backdrop-blur-md border-b border-slate-100 dark:border-dark-border shrink-0">
            <button onclick="window.location.href='csrProfile.php'" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 flex items-center justify-center hover:text-brand transition-colors active:scale-95">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div>
                <h1 class="text-lg font-bold dark:text-white leading-tight">Support Center</h1>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest leading-none">Inbox</p>
            </div>
        </header>

        <!-- Chat List (Visible by default) -->
        <div id="chat-list" class="flex-1 overflow-y-auto no-scrollbar p-4 space-y-2 pb-24">
            <!-- Injected by JS -->
        </div>

        <!-- Chat Window (Hidden by default) -->
        <div id="chat-window" class="absolute inset-0 z-50 bg-white dark:bg-dark-bg flex flex-col translate-x-full transition-transform duration-300">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-slate-100 dark:border-dark-border flex items-center gap-3 bg-white/90 dark:bg-dark-bg/90 backdrop-blur-md pt-8">
                <button onclick="closeChat()" class="w-8 h-8 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition-colors">
                    <i class="fas fa-arrow-left text-slate-500"></i>
                </button>
                <img id="chat-avatar" src="" class="w-8 h-8 rounded-full bg-slate-200">
                <div class="flex-1">
                    <h4 id="chat-name" class="font-bold text-sm dark:text-white leading-none">User Name</h4>
                    <span class="text-[10px] text-green-500 font-bold">Online</span>
                </div>
                <button onclick="startCall()" class="text-slate-400 hover:text-brand"><i class="fas fa-phone"></i></button>
            </div>

            <!-- Messages -->
            <div id="messages-area" class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50 dark:bg-dark-surface/50">
                <!-- Injected -->
            </div>

            <!-- Input -->
            <div class="p-4 bg-white dark:bg-dark-surface border-t border-slate-100 dark:border-dark-border flex gap-2 pb-8">
                <input type="text" id="msg-input" class="flex-1 bg-slate-100 dark:bg-slate-800 rounded-full px-4 text-sm focus:outline-none dark:text-white" placeholder="Type a message...">
                <button onclick="sendMsg()" class="w-10 h-10 rounded-full bg-brand text-white flex items-center justify-center shrink-0 shadow-lg shadow-brand/30">
                    <i class="fas fa-paper-plane text-xs"></i>
                </button>
            </div>
        </div>

    

        <!-- Call Overlay -->
        <div id="call-overlay" class="fixed inset-0 z-[60] bg-slate-900/95 backdrop-blur-xl flex flex-col items-center justify-center hidden opacity-0 transition-opacity duration-500">
            <div class="relative mb-8">
                <div id="call-ring-1" class="absolute inset-0 bg-brand/30 rounded-full animate-ping"></div>
                <div id="call-ring-2" class="absolute inset-0 bg-brand/20 rounded-full animate-ping" style="animation-delay: 0.5s"></div>
                <img id="call-avatar" src="" class="w-32 h-32 rounded-full border-4 border-white/10 relative z-10 object-cover shadow-2xl">
            </div>
            <h2 id="call-name" class="text-2xl font-bold text-white mb-2">User Name</h2>
            <p id="call-status" class="text-slate-400 animate-pulse mb-12">Calling...</p>
            
            <div class="flex gap-8">
                <button class="w-16 h-16 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center hover:bg-slate-700 transition-colors">
                    <i class="fas fa-microphone-slash text-xl"></i>
                </button>
                <button onclick="endCall()" class="w-16 h-16 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg shadow-red-500/30 transform hover:scale-110">
                    <i class="fas fa-phone-slash text-xl"></i>
                </button>
                <button class="w-16 h-16 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center hover:bg-slate-700 transition-colors">
                    <i class="fas fa-volume-up text-xl"></i>
                </button>
            </div>
        </div>
    </main>

    <script>
        // Theme Logic
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        const applyTheme = (e) => {
            if (e.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        };
        
        applyTheme(mediaQuery);
        mediaQuery.addEventListener('change', applyTheme);

        // Mock Data
        const chats = [
            { id: 1, name: 'John Doe', avatar: 'https://ui-avatars.com/api/?name=John+Doe', lastMsg: 'When will my order arrive?', time: '2m', unread: 2 },
            { id: 2, name: 'Jane Smith', avatar: 'https://ui-avatars.com/api/?name=Jane+Smith', lastMsg: 'Thanks for the help!', time: '1h', unread: 0 },
            { id: 3, name: 'Mike Ross', avatar: 'https://ui-avatars.com/api/?name=Mike+Ross', lastMsg: 'Is this item in stock?', time: '3h', unread: 1 },
        ];

        const messages = [
            { id: 1, sender: 'user', text: 'Hi, I have a question about my order.', time: '10:00 AM' },
            { id: 2, sender: 'me', text: 'Hello! Sure, what is your order ID?', time: '10:01 AM' },
            { id: 3, sender: 'user', text: 'It is ORD-001.', time: '10:02 AM' },
            { id: 4, sender: 'user', text: 'When will my order arrive?', time: '10:02 AM' },
        ];

        function renderChatList() {
            const container = document.getElementById('chat-list');
            container.innerHTML = '';
            chats.forEach(chat => {
                container.innerHTML += `
                    <div onclick="openChat(${chat.id})" class="flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer">
                        <div class="relative">
                            <img src="${chat.avatar}" class="w-12 h-12 rounded-full object-cover bg-slate-200">
                            ${chat.unread > 0 ? `<div class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-brand text-white text-[10px] font-bold flex items-center justify-center border-2 border-white dark:border-dark-bg">${chat.unread}</div>` : ''}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline mb-1">
                                <h4 class="font-bold text-sm dark:text-white truncate">${chat.name}</h4>
                                <span class="text-[10px] text-slate-400">${chat.time}</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate ${chat.unread > 0 ? 'font-bold text-slate-800 dark:text-slate-200' : ''}">${chat.lastMsg}</p>
                        </div>
                    </div>
                `;
            });
        }

        function openChat(id) {
            const chat = chats.find(c => c.id === id);
            document.getElementById('chat-name').innerText = chat.name;
            document.getElementById('chat-avatar').src = chat.avatar;
            
            renderMessages();
            document.getElementById('chat-window').classList.remove('translate-x-full');
        }

        function closeChat() {
            document.getElementById('chat-window').classList.add('translate-x-full');
        }

        function renderMessages() {
            const container = document.getElementById('messages-area');
            container.innerHTML = '';
            messages.forEach(msg => {
                const isMe = msg.sender === 'me';
                container.innerHTML += `
                    <div class="flex ${isMe ? 'justify-end' : 'justify-start'}">
                        <div class="max-w-[75%] ${isMe ? 'bg-brand text-white rounded-tr-none' : 'bg-white dark:bg-dark-surface text-slate-800 dark:text-slate-200 rounded-tl-none'} p-3 rounded-2xl shadow-sm text-sm">
                            <p>${msg.text}</p>
                            <p class="text-[10px] ${isMe ? 'text-white/70' : 'text-slate-400'} mt-1 text-right">${msg.time}</p>
                        </div>
                    </div>
                `;
            });
            container.scrollTop = container.scrollHeight;
        }

        function sendMsg() {
            const input = document.getElementById('msg-input');
            const text = input.value;
            if(!text) return;

            messages.push({
                id: Date.now(),
                sender: 'me',
                text: text,
                time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
            });
            
            renderMessages();
            input.value = '';
        }

        let callTimer;
        let connectTimeout;

        function startCall() {
            const name = document.getElementById('chat-name').innerText;
            const avatar = document.getElementById('chat-avatar').src;
            
            document.getElementById('call-name').innerText = name;
            document.getElementById('call-avatar').src = avatar;
            
            // Reset UI State
            const statusEl = document.getElementById('call-status');
            statusEl.innerText = 'Calling...';
            statusEl.className = 'text-slate-400 animate-pulse mb-12';
            document.getElementById('call-ring-1').classList.remove('hidden');
            document.getElementById('call-ring-2').classList.remove('hidden');

            const overlay = document.getElementById('call-overlay');
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);

            // Simulate Answer after 3s
            connectTimeout = setTimeout(() => {
                statusEl.className = 'text-white font-mono text-3xl mb-12 font-bold tracking-widest';
                statusEl.innerText = '00:00';
                
                // Stop ringing animation
                document.getElementById('call-ring-1').classList.add('hidden');
                document.getElementById('call-ring-2').classList.add('hidden');

                // Start Timer
                let seconds = 0;
                callTimer = setInterval(() => {
                    seconds++;
                    const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
                    const secs = (seconds % 60).toString().padStart(2, '0');
                    statusEl.innerText = `${mins}:${secs}`;
                }, 1000);
            }, 3000);
        }

        function endCall() {
            clearTimeout(connectTimeout);
            clearInterval(callTimer);
            
            const overlay = document.getElementById('call-overlay');
            overlay.classList.add('opacity-0');
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 500);
        }

        renderChatList();
    </script>
</body>
</html>
