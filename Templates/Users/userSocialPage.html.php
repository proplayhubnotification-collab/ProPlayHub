<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proplay Hub - Social</title>
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
                        brandHover: '#c90036',
                        brandDark: '#b0002f',
                        light: { bg: '#f8fafc', text: '#020618' },
                        dark: { bg: '#020618', text: '#f8fafc', surface: '#111827', border: '#1e293b' },
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
        
        .like-anim { animation: heartBeat 0.3s ease-in-out; }
        @keyframes heartBeat {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }
        
        .fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
        .slide-up { animation: slideUp 0.3s ease-out forwards; }

        /* TikTok Style Snap Scrolling */
        .snap-y-mandatory {
            scroll-snap-type: y mandatory;
        }
        .snap-start {
            scroll-snap-align: start;
        }
        
        /* Rotating Disc Animation */
        @keyframes spin { 100% { transform: rotate(360deg); } }
        .animate-spin-slow { animation: spin 4s linear infinite; }
    </style>
</head>
<body class="bg-light-bg text-light-text dark:bg-dark-bg dark:text-dark-text font-sans antialiased min-h-screen flex justify-center transition-colors duration-300">

        <!-- Header (Adjusts based on tab) -->
    <div id="main-header" class="px-4 py-3 flex items-center justify-between bg-white/90 dark:bg-dark-surface/90 shadow-sm z-30 fixed top-0 w-full max-w-md transition-all duration-300 backdrop-blur-md border-b border-slate-100 dark:border-white/5">
        <div class="flex gap-4 absolute left-1/2 -translate-x-1/2">
            <button onclick="app.switchTab('following')" id="tab-following" class="font-bold text-brand border-b-2 border-brand pb-1 transition-colors text-shadow">Following</button>
            <button onclick="app.switchTab('trending')" id="tab-trending" class="font-bold text-slate-400 dark:text-slate-500 pb-1 hover:text-slate-600 dark:hover:text-slate-300 transition-colors border-b-2 border-transparent text-shadow">Trending</button>
        </div>
        
        <!-- Notification Bell -->
        <div class="relative z-40">
            <button onclick="window.location.href='../PHP_User/userNotification.php'" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 dark:bg-white/5 text-brand relative hover:bg-slate-200 dark:hover:bg-white/10 transition-colors" id="notif-btn">
                <i class="fas fa-bell"></i>
                <span id="notif-badge" class="absolute top-2 right-2 w-2.5 h-2.5 bg-brand border-2 border-white dark:border-dark-surface rounded-full"></span>
            </button>
        </div>
    </div>

        <!-- ================= FOLLOWING VIEW (Standard Feed) ================= -->
        <div id="following-view" class="flex-1 overflow-y-auto no-scrollbar pt-16 pb-20">
            <!-- Create Post Input -->
            <div class="p-4 bg-white dark:bg-dark-surface mb-2 shadow-sm border-b border-slate-100 dark:border-white/5">
                <div class="flex gap-3">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800">
                    <div class="flex-1">
                        <textarea id="new-post-content" rows="2" placeholder="What's on your mind, gamer?" class="w-full bg-transparent border-none focus:ring-0 text-sm resize-none pt-2 placeholder-slate-400 dark:text-white"></textarea>
                        
                        <!-- Preview Attached Media -->
                        <div id="attachment-preview" class="hidden mt-2 relative w-full h-32 rounded-lg overflow-hidden bg-black">
                            <!-- Image or Video will be injected here -->
                            <button onclick="app.removeAttachment()" class="absolute top-1 right-1 bg-black/50 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-500 z-10"><i class="fas fa-times text-xs"></i></button>
                        </div>

                        <!-- Preview Poll -->
                        <div id="poll-preview" class="hidden mt-2 p-3 border border-slate-200 dark:border-white/10 rounded-lg bg-slate-50 dark:bg-white/5">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-bold uppercase text-brand">Poll</span>
                                <button onclick="app.removePoll()" class="text-slate-400 hover:text-red-500"><i class="fas fa-times"></i></button>
                            </div>
                            <p id="poll-preview-question" class="font-bold text-sm mb-2 dark:text-white">Question?</p>
                            <ul id="poll-preview-options" class="space-y-1 text-xs text-slate-500 dark:text-slate-400 ml-4 list-disc"></ul>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-2 pt-2 border-t border-slate-100 dark:border-white/5">
                    <div class="flex gap-4 text-slate-400">
                        <button onclick="app.openMediaGallery('image')" class="hover:text-brand transition-colors" title="Add Image"><i class="fas fa-image text-lg"></i></button>
                        <button onclick="app.openMediaGallery('video')" class="hover:text-brand transition-colors" title="Add Video"><i class="fas fa-video text-lg"></i></button>
                        <button onclick="app.openPollCreator()" class="hover:text-brand transition-colors" title="Create Poll"><i class="fas fa-chart-bar text-lg"></i></button>
                    </div>
                    <button onclick="app.createPost()" class="bg-brand text-white text-xs font-bold px-4 py-2 rounded-full hover:brightness-110 transition-all shadow-lg shadow-brand/20">
                        Post
                    </button>
                </div>
            </div>

            <!-- Feed Container -->
            <div id="feed-container" class="space-y-2 bg-slate-50 dark:bg-transparent pb-4">
                <!-- Posts will be injected here -->
            </div>
            
            <div class="py-8 text-center text-slate-400 text-xs">
                <p>You're all caught up!</p>
                <i class="fas fa-check-circle mt-2 text-brand"></i>
            </div>
        </div>


        <!-- ================= TRENDING VIEW (TikTok Style) ================= -->
        <div id="trending-view" class="hidden h-screen w-full bg-black overflow-y-scroll snap-y-mandatory no-scrollbar relative">
            <!-- Videos will be injected here -->
        </div>


        <!-- === OVERLAYS === -->
        
        <!-- Media Gallery Modal -->
        <div id="modal-media-gallery" class="hidden fixed inset-0 z-[80] bg-black/50 flex flex-col justify-end">
            <div class="bg-white dark:bg-dark-surface rounded-t-2xl h-[70vh] flex flex-col slide-up shadow-2xl">
                <div class="p-4 border-b border-slate-100 dark:border-white/5 flex justify-between items-center">
                    <h3 class="font-bold text-lg dark:text-white">Select Media</h3>
                    <button onclick="app.closeMediaGallery()" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="flex p-2 gap-2">
                    <button id="tab-gallery-photos" onclick="app.switchGalleryTab('image')" class="flex-1 py-2 rounded-lg bg-brand text-white text-sm font-bold">Photos</button>
                    <button id="tab-gallery-videos" onclick="app.switchGalleryTab('video')" class="flex-1 py-2 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-500 text-sm font-bold">Videos</button>
                </div>
                <div id="gallery-grid" class="flex-1 overflow-y-auto p-2 grid grid-cols-3 gap-1">
                    <!-- Images/Videos injected here -->
                </div>
            </div>
        </div>

        <!-- Poll Creator Modal -->
        <div id="modal-poll-creator" class="hidden fixed inset-0 z-[80] bg-black/50 flex flex-col justify-end">
            <div class="bg-white dark:bg-slate-900 rounded-t-2xl p-6 slide-up shadow-2xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg">Create Poll</h3>
                    <button onclick="app.closePollCreator()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Question</label>
                        <input type="text" id="poll-question" class="w-full mt-1 p-3 bg-gray-50 dark:bg-slate-800 rounded-custom border border-gray-200 dark:border-gray-700 focus:border-brand focus:outline-none" placeholder="Ask something...">
                    </div>
                    <div id="poll-options-container" class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase">Options</label>
                        <input type="text" class="poll-option-input w-full p-3 bg-gray-50 dark:bg-slate-800 rounded-custom border border-gray-200 dark:border-gray-700 focus:border-brand focus:outline-none" placeholder="Option 1">
                        <input type="text" class="poll-option-input w-full p-3 bg-gray-50 dark:bg-slate-800 rounded-custom border border-gray-200 dark:border-gray-700 focus:border-brand focus:outline-none" placeholder="Option 2">
                    </div>
                    <button onclick="app.addPollOptionInput()" class="text-brand text-sm font-bold hover:underline">+ Add Option</button>
                    
                    <button onclick="app.savePoll()" class="w-full mt-4 py-3 bg-brand text-white font-bold rounded-custom">Attach Poll</button>
                </div>
            </div>
        </div>

        <!-- Story Viewer Overlay -->
        <div id="story-viewer" class="hidden fixed inset-0 z-[60] bg-black flex flex-col">
            <!-- Progress Bar -->
            <div class="flex gap-1 p-2 pt-4">
                <div class="h-1 bg-gray-600 rounded-full flex-1 overflow-hidden">
                    <div id="story-progress" class="h-full bg-white w-0"></div>
                </div>
            </div>
            
            <!-- Header -->
            <div class="flex justify-between items-center px-4 py-2 text-white">
                <div class="flex items-center gap-3">
                    <img id="story-avatar" src="" class="w-8 h-8 rounded-full border border-white">
                    <span id="story-username" class="font-bold text-sm">Username</span>
                    <span class="text-xs text-gray-300">10m</span>
                </div>
                <button onclick="app.closeStory()" class="text-white p-2"><i class="fas fa-times text-xl"></i></button>
            </div>

            <!-- Content -->
            <div class="flex-1 flex items-center justify-center bg-gray-900 relative">
                <img id="story-image" src="" class="max-h-full max-w-full object-contain">
                <!-- Tap areas -->
                <div onclick="app.showToast('Previous Story')" class="absolute left-0 top-0 bottom-0 w-1/3"></div>
                <div onclick="app.closeStory()" class="absolute right-0 top-0 bottom-0 w-1/3"></div> 
            </div>

            <!-- Reply -->
            <div class="p-4 pb-8 flex gap-3">
                <input type="text" placeholder="Send message..." class="flex-1 bg-transparent border border-white/50 rounded-full px-4 py-2 text-white placeholder-gray-400 focus:border-white focus:outline-none">
                <button class="text-white text-2xl"><i class="far fa-heart"></i></button>
                <button class="text-white text-2xl"><i class="far fa-paper-plane"></i></button>
            </div>
        </div>
        
        <!-- Post Options Modal -->
        <div id="post-options-modal" class="hidden fixed inset-0 z-[70] bg-black/50 flex flex-col justify-end" onclick="app.closePostOptions()">
            <div class="bg-white dark:bg-slate-900 rounded-t-2xl p-4 animate-[slideUp_0.2s_ease-out]" onclick="event.stopPropagation()">
                <div class="w-12 h-1 bg-gray-300 rounded-full mx-auto mb-4"></div>
                <div class="space-y-1">
                    <button id="opt-delete" class="w-full text-left p-3 text-red-500 font-bold hover:bg-gray-50 dark:hover:bg-slate-800 rounded-lg flex items-center gap-3">
                        <i class="fas fa-trash-alt"></i> Delete Post
                    </button>
                    <button id="opt-report" class="w-full text-left p-3 text-gray-700 dark:text-gray-200 font-medium hover:bg-gray-50 dark:hover:bg-slate-800 rounded-lg flex items-center gap-3">
                        <i class="fas fa-flag"></i> Report
                    </button>
                    <button class="w-full text-left p-3 text-gray-700 dark:text-gray-200 font-medium hover:bg-gray-50 dark:hover:bg-slate-800 rounded-lg flex items-center gap-3" onclick="app.showToast('Link copied'); app.closePostOptions()">
                        <i class="fas fa-link"></i> Copy Link
                    </button>
                </div>
                <button onclick="app.closePostOptions()" class="w-full mt-2 p-3 text-center text-gray-500 font-medium">Cancel</button>
            </div>
        </div>

        <!-- Toast -->
        <div id="toast" class="fixed top-20 left-1/2 -translate-x-1/2 bg-gray-900 dark:bg-white text-white dark:text-black px-5 py-3 rounded-full shadow-xl opacity-0 pointer-events-none transition-opacity duration-300 z-[100] text-sm font-medium flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-check-circle text-brand"></i>
            <span id="toast-message">Notification</span>
        </div>

    </div>

    <script>
        const app = {
            currentUser: {
                name: "Hugo Wishpax",
                avatar: "Felix",
                rank: "Diamond"
            },
            currentTab: 'following',
            
            // Attachment State
            attachment: { type: null, url: null }, // type: 'image' | 'video'
            pollData: null, // { question: string, options: string[] }
            
            activePostId: null,

            // Mock Gallery Data
            mockImages: [
                "https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=400",
                "https://images.unsplash.com/photo-1612815154858-60aa4c4603e1?auto=format&fit=crop&q=80&w=400",
                "https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&q=80&w=400",
                "https://images.unsplash.com/photo-1552820728-8b83bb6b773f?auto=format&fit=crop&q=80&w=400",
                "https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&q=80&w=400",
                "https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&q=80&w=400"
            ],
            mockVideos: [
                "https://interactive-examples.mdn.mozilla.net/media/cc0-videos/flower.webm",
                "https://www.w3schools.com/html/mov_bbb.mp4"
            ],

            posts: [
                // Leaderboard Ad (Sponsored)
                {
                    id: 999,
                    user: "ProPlay Shop",
                    avatar: "Shop",
                    time: "Sponsored",
                    content: "🏆 The champions of December are here! Check out who's dominating the Valorant ladder this month. Gear up like a pro at our shop!",
                    type: "leaderboard",
                    leaderboard: {
                        game: "Valorant",
                        month: "December",
                        top3: [
                            { name: "Demon1", rank: "Radiant #1", score: "1250 RR", avatar: "Felix" },
                            { name: "TenZ", rank: "Radiant #2", score: "1210 RR", avatar: "Zack" },
                            { name: "Aspas", rank: "Radiant #3", score: "1190 RR", avatar: "Christopher" }
                        ]
                    },
                    likes: 1542,
                    comments: [],
                    isLiked: false,
                    isMine: false
                },
                // Existing Posts
                {
                    id: 1,
                    user: "Zack_Gamer",
                    avatar: "Zack",
                    time: "2h ago",
                    content: "Finally reached Ascendant in Valorant! 🎮 Need a duo partner.",
                    type: "image",
                    mediaUrl: "https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=1000",
                    likes: 124,
                    comments: [{ user: "Molly.FPS", text: "Congrats!" }],
                    isLiked: false,
                    isMine: false
                },
                {
                    id: 2,
                    user: "Hugo Wishpax",
                    avatar: "Felix",
                    time: "3h ago",
                    content: "Just bought the new skin bundle! Looks amazing.",
                    type: "text",
                    mediaUrl: null,
                    likes: 5,
                    comments: [],
                    isLiked: true,
                    isMine: true
                },
                {
                    id: 3,
                    user: "Community_Poll",
                    avatar: "Christopher",
                    time: "4h ago",
                    content: "Which game should I stream tonight?",
                    type: "poll",
                    poll: {
                        question: "Which game should I stream tonight?",
                        options: [
                            { text: "Valorant", votes: 45 },
                            { text: "CS2", votes: 30 },
                            { text: "League of Legends", votes: 15 }
                        ],
                        totalVotes: 90,
                        votedIndex: -1
                    },
                    likes: 10,
                    comments: [],
                    isLiked: false,
                    isMine: false
                },
                // New Images (3)
                {
                    id: 4,
                    user: "Sarah_Sniper",
                    avatar: "Sarah",
                    time: "5h ago",
                    content: "New setup finally complete! 🖥️✨ Ready for the grind.",
                    type: "image",
                    mediaUrl: "data:image/jpeg;base64,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",
                    likes: 89,
                    comments: [],
                    isLiked: true,
                    isMine: false
                },
                {
                    id: 5,
                    user: "Pro_Lee",
                    avatar: "Lee",
                    time: "6h ago",
                    content: "Victory royale! 🏆 That last shot was insane.",
                    type: "image",
                    mediaUrl: "https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=1000",
                    likes: 230,
                    comments: [],
                    isLiked: false,
                    isMine: false
                },
                {
                    id: 6,
                    user: "GamerDad",
                    avatar: "Dad",
                    time: "8h ago",
                    content: "Retro gaming night with the kids. Teaching them the classics.",
                    type: "image",
                    mediaUrl: "https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=1000",
                    likes: 45,
                    comments: [],
                    isLiked: false,
                    isMine: false
                },
                // New Text Posts (5)
                {
                    id: 7,
                    user: "NoobMaster69",
                    avatar: "Noob",
                    time: "1h ago",
                    content: "Why is the new patch so buggy? 😭 I keep crashing in ranked.",
                    type: "text",
                    mediaUrl: null,
                    likes: 12,
                    comments: [],
                    isLiked: false,
                    isMine: false
                },
                {
                    id: 8,
                    user: "Jinx_Main",
                    avatar: "Jinx",
                    time: "2h ago",
                    content: "Looking for a clash team for this weekend. Tier 2 support/adc main. DM me!",
                    type: "text",
                    mediaUrl: null,
                    likes: 5,
                    comments: [],
                    isLiked: false,
                    isMine: false
                },
                {
                    id: 9,
                    user: "TechReviewer",
                    avatar: "Tech",
                    time: "3h ago",
                    content: "The RTX 5090 rumors are getting wild. Do we really need that much power?",
                    type: "text",
                    mediaUrl: null,
                    likes: 67,
                    comments: [],
                    isLiked: true,
                    isMine: false
                },
                {
                    id: 10,
                    user: "Support_Life",
                    avatar: "Mercy",
                    time: "4h ago",
                    content: "Please ward your lanes people... I can't be everywhere at once! 😤",
                    type: "text",
                    mediaUrl: null,
                    likes: 156,
                    comments: [],
                    isLiked: false,
                    isMine: false
                },
                {
                    id: 11,
                    user: "Speedy",
                    avatar: "Sonic",
                    time: "7h ago",
                    content: "Just beat my personal best in Mario Kart! 1:20 on Rainbow Road!",
                    type: "text",
                    mediaUrl: null,
                    likes: 34,
                    comments: [],
                    isLiked: false,
                    isMine: false
                },
                // New Polls (2)
                {
                    id: 12,
                    user: "Esports_News",
                    avatar: "News",
                    time: "10h ago",
                    content: "Who do you think will win Worlds 2025?",
                    type: "poll",
                    poll: {
                        question: "Who wins Worlds 2025?",
                        options: [
                            { text: "T1", votes: 1200 },
                            { text: "Gen.G", votes: 800 },
                            { text: "JDG", votes: 600 },
                            { text: "G2", votes: 200 }
                        ],
                        totalVotes: 2800,
                        votedIndex: -1
                    },
                    likes: 400,
                    comments: [],
                    isLiked: false,
                    isMine: false
                },
                {
                    id: 13,
                    user: "RPG_Fan",
                    avatar: "Geralt",
                    time: "12h ago",
                    content: "Best RPG of all time? Settle the debate.",
                    type: "poll",
                    poll: {
                        question: "Best RPG?",
                        options: [
                            { text: "Witcher 3", votes: 450 },
                            { text: "Skyrim", votes: 400 },
                            { text: "Elden Ring", votes: 500 },
                            { text: "FF7", votes: 300 }
                        ],
                        totalVotes: 1650,
                        votedIndex: -1
                    },
                    likes: 210,
                    comments: [],
                    isLiked: true,
                    isMine: false
                }
            ],

            // Mock Data for Trending (Videos)
            trendingVideos: [
                {
                    id: 101,
                    user: "ProPlayer_One",
                    avatar: "Christopher",
                    desc: "Insane clutch in CS2! 🔥 #cs2 #clutch #gaming",
                    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4",
                    likes: "12K",
                    isLiked: false
                },
                {
                    id: 102,
                    user: "SpeedRun_Guy",
                    avatar: "Timmy",
                    desc: "World record attempt! So close... 😱 #speedrun #minecraft",
                    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4",
                    likes: "45K",
                    isLiked: true
                },
                {
                    id: 103,
                    user: "Valorant_Clips",
                    avatar: "Jett",
                    desc: "Jett knives are broken 🔪 #valorant #ace",
                    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
                    likes: "8.5K",
                    isLiked: false
                },
                {
                    id: 104,
                    user: "League_Pro",
                    avatar: "Faker",
                    desc: "Outplaying the gank like a boss 😎 #leagueoflegends #toplane",
                    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4",
                    likes: "22K",
                    isLiked: false
                },
                {
                    id: 105,
                    user: "Retro_Gamer",
                    avatar: "Mario",
                    desc: "Nostalgia hitting hard today 🕹️ #retro #nintendo",
                    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoy.mp4",
                    likes: "5K",
                    isLiked: true
                },
                {
                    id: 106,
                    user: "Stream_Highlights",
                    avatar: "PogChamp",
                    desc: "Funniest moment from yesterday's stream 😂 #twitch #fails",
                    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4",
                    likes: "150K",
                    isLiked: false
                },
                {
                    id: 107,
                    user: "Tech_Tips",
                    avatar: "Linus",
                    desc: "Don't do this to your PC! ⚠️ #pcbuild #tech",
                    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4",
                    likes: "30K",
                    isLiked: false
                },
                {
                    id: 108,
                    user: "Cosplay_Queen",
                    avatar: "Ahri",
                    desc: "My Ahri cosplay is finally done! 🦊 #cosplay #lol",
                    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4",
                    likes: "89K",
                    isLiked: true
                },
                {
                    id: 109,
                    user: "Mobile_Gamer",
                    avatar: "Phone",
                    desc: "PUBG Mobile is intense! 📱 #pubgm #winner",
                    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/SubaruOutbackOnStreetAndDirt.mp4",
                    likes: "12K",
                    isLiked: false
                },
                {
                    id: 110,
                    user: "Indie_Dev",
                    avatar: "Dev",
                    desc: "Working on my dream game 🎮 #gamedev #indie",
                    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4",
                    likes: "4K",
                    isLiked: false
                }
            ],

            init() {
                this.loadTheme();
                
                // Load User Data from Profile
                const storedUser = localStorage.getItem('proplay_user_v1');
                if (storedUser) {
                    try {
                        const user = JSON.parse(storedUser);
                        this.currentUser.name = user.name;
                        
                        // Extract seed from URL if possible, otherwise use name
                        // URL format: ...?seed=Felix&...
                        const seedMatch = user.avatar.match(/seed=([^&]+)/);
                        if (seedMatch) {
                            this.currentUser.avatar = seedMatch[1];
                        } else {
                            this.currentUser.avatar = user.name;
                        }
                    } catch (e) {
                        console.error("Error loading user data", e);
                    }
                }

                // Load Posts from LocalStorage
                const storedPosts = localStorage.getItem('proplay_social_posts');
                if (storedPosts) {
                    try {
                        const parsedPosts = JSON.parse(storedPosts);
                        if (parsedPosts.length > 0) {
                            this.posts = parsedPosts;
                        } else {
                            // Add isAdmin flag to default posts if missing
                            this.posts = this.posts.map(p => ({...p, isAdmin: p.isAdmin || false}));
                            this.savePosts();
                        }
                    } catch (e) {
                        console.error("Error loading posts", e);
                        this.posts = this.posts.map(p => ({...p, isAdmin: p.isAdmin || false}));
                        this.savePosts();
                    }
                } else {
                    // Save default posts to storage for first time
                    this.posts = this.posts.map(p => ({...p, isAdmin: p.isAdmin || false}));
                    this.savePosts();
                }

                this.renderFeed();
                this.renderTrendingFeed();
                
                // Listen for storage changes from other tabs/windows (e.g., CSR admin posting)
                window.addEventListener('storage', (e) => {
                    if (e.key === 'proplay_social_posts') {
                        try {
                            this.posts = JSON.parse(e.newValue);
                            this.renderFeed();
                        } catch (err) {
                            console.error("Error syncing posts", err);
                        }
                    }
                });
                
                // Dropdown close logic
                document.addEventListener('click', (e) => {
                    const dropdown = document.getElementById('notif-dropdown');
                    const btn = e.target.closest('button');
                    if (!dropdown.classList.contains('hidden') && (!btn || btn.id !== 'notif-btn')) {
                        dropdown.classList.add('hidden');
                    }
                });
            },

            loadTheme() {
                const applyTheme = () => {
                    const storedData = localStorage.getItem('proplayUser_v2');
                    let theme = 'light';
                    
                    if (storedData) {
                        try {
                            const parsed = JSON.parse(storedData);
                            if (parsed.theme) theme = parsed.theme;
                            else if (window.matchMedia('(prefers-color-scheme: dark)').matches) theme = 'dark';
                        } catch(e) {
                            if (window.matchMedia('(prefers-color-scheme: dark)').matches) theme = 'dark';
                        }
                    } else {
                        if (window.matchMedia('(prefers-color-scheme: dark)').matches) theme = 'dark';
                    }

                    if (theme === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                };

                applyTheme();

                // Listen for changes
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', applyTheme);
                window.addEventListener('storage', (e) => {
                    if (e.key === 'proplayUser_v2') applyTheme();
                });
            },

            switchTab(tab) {
                this.currentTab = tab;
                const btnFollowing = document.getElementById('tab-following');
                const btnTrending = document.getElementById('tab-trending');
                const viewFollowing = document.getElementById('following-view');
                const viewTrending = document.getElementById('trending-view');
                const header = document.getElementById('main-header');
                const notifBtn = document.getElementById('notif-btn');

                if (tab === 'following') {
                    // Styles for Following Tab
                    btnFollowing.classList.add('text-brand', 'border-brand');
                    btnFollowing.classList.remove('text-slate-400', 'dark:text-slate-500', 'border-transparent', 'text-white', 'text-white/60');
                    
                    btnTrending.classList.remove('text-brand', 'border-brand', 'text-white', 'border-white');
                    btnTrending.classList.add('text-slate-400', 'dark:text-slate-500', 'border-transparent');
                    
                    // Show Following View
                    viewFollowing.classList.remove('hidden');
                    viewTrending.classList.add('hidden');

                    // Reset Header Style (White/Dark)
                    header.classList.remove('bg-transparent', 'text-white', 'border-transparent');
                    header.classList.add('bg-white/90', 'dark:bg-dark-surface/90', 'shadow-sm', 'border-b', 'border-slate-100', 'dark:border-white/5');
                    
                    notifBtn.classList.remove('bg-white/20', 'text-white', 'hover:bg-white/30');
                    notifBtn.classList.add('bg-slate-100', 'dark:bg-white/5', 'text-brand');

                    // Pause all trending videos
                    document.querySelectorAll('#trending-view video').forEach(v => v.pause());

                } else {
                    // Styles for Trending Tab (TikTok mode)
                    btnTrending.classList.add('text-white', 'border-white');
                    btnTrending.classList.remove('text-slate-400', 'dark:text-slate-500', 'border-transparent');
                    
                    btnFollowing.classList.remove('text-brand', 'border-brand');
                    btnFollowing.classList.add('text-white/60', 'border-transparent');

                    // Show Trending View
                    viewTrending.classList.remove('hidden');
                    viewFollowing.classList.add('hidden');

                    // Transparent Overlay Header
                    header.classList.remove('bg-white/90', 'dark:bg-dark-surface/90', 'shadow-sm', 'border-b', 'border-slate-100', 'dark:border-white/5');
                    header.classList.add('bg-transparent', 'text-white', 'border-transparent');
                    
                    // Update Notifications Button for dark overlay
                    notifBtn.classList.remove('bg-slate-100', 'dark:bg-white/5', 'text-brand');
                    notifBtn.classList.add('bg-white/20', 'text-white', 'hover:bg-white/30');

                    this.playVisibleVideo();
                }
            },

            savePosts() {
                localStorage.setItem('proplay_social_posts', JSON.stringify(this.posts));
            },

            // --- MEDIA GALLERY LOGIC ---
            openMediaGallery(type) {
                this.galleryType = type; // 'image' or 'video'
                document.getElementById('modal-media-gallery').classList.remove('hidden');
                this.switchGalleryTab(type);
            },
            
            closeMediaGallery() {
                document.getElementById('modal-media-gallery').classList.add('hidden');
            },

            switchGalleryTab(type) {
                this.galleryType = type;
                const tabP = document.getElementById('tab-gallery-photos');
                const tabV = document.getElementById('tab-gallery-videos');
                const grid = document.getElementById('gallery-grid');
                grid.innerHTML = '';

                if (type === 'image') {
                    tabP.classList.replace('bg-gray-100', 'bg-brand');
                    tabP.classList.replace('text-gray-500', 'text-white');
                    tabP.classList.remove('dark:bg-slate-800');
                    
                    tabV.classList.replace('bg-brand', 'bg-gray-100');
                    tabV.classList.replace('text-white', 'text-gray-500');
                    tabV.classList.add('dark:bg-slate-800');

                    this.mockImages.forEach(src => {
                        const img = document.createElement('img');
                        img.src = src;
                        img.className = "w-full aspect-square object-cover rounded-lg cursor-pointer hover:opacity-80 border-2 border-transparent hover:border-brand";
                        img.onclick = () => this.selectMedia(src, 'image');
                        grid.appendChild(img);
                    });
                } else {
                    tabV.classList.replace('bg-gray-100', 'bg-brand');
                    tabV.classList.replace('text-gray-500', 'text-white');
                    tabV.classList.remove('dark:bg-slate-800');
                    
                    tabP.classList.replace('bg-brand', 'bg-gray-100');
                    tabP.classList.replace('text-white', 'text-gray-500');
                    tabP.classList.add('dark:bg-slate-800');

                    this.mockVideos.forEach(src => {
                        const div = document.createElement('div');
                        div.className = "w-full aspect-square bg-black rounded-lg cursor-pointer hover:border-brand border-2 border-transparent relative overflow-hidden group";
                        div.innerHTML = `
                            <video src="${src}" class="w-full h-full object-cover"></video>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-transparent transition-all">
                                <i class="fas fa-play text-white text-2xl"></i>
                            </div>
                        `;
                        div.onclick = () => this.selectMedia(src, 'video');
                        grid.appendChild(div);
                    });
                }
            },

            selectMedia(url, type) {
                this.attachment = { type: type, url: url };
                this.closeMediaGallery();
                
                const preview = document.getElementById('attachment-preview');
                preview.innerHTML = ''; // Clear prev
                preview.classList.remove('hidden');

                const btnRemove = document.createElement('button');
                btnRemove.className = "absolute top-1 right-1 bg-black/50 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-500 z-10";
                btnRemove.innerHTML = '<i class="fas fa-times text-xs"></i>';
                btnRemove.onclick = () => this.removeAttachment();
                preview.appendChild(btnRemove);

                if (type === 'image') {
                    const img = document.createElement('img');
                    img.src = url;
                    img.className = "w-full h-full object-cover";
                    preview.appendChild(img);
                } else {
                    const vid = document.createElement('video');
                    vid.src = url;
                    vid.className = "w-full h-full object-cover";
                    vid.controls = true;
                    preview.appendChild(vid);
                }
                
                // Clear Poll if exists (mutually exclusive)
                this.removePoll();
            },

            removeAttachment() {
                this.attachment = { type: null, url: null };
                document.getElementById('attachment-preview').classList.add('hidden');
                document.getElementById('attachment-preview').innerHTML = '';
            },

            // --- POLL LOGIC ---
            openPollCreator() {
                document.getElementById('modal-poll-creator').classList.remove('hidden');
                // Reset fields
                document.getElementById('poll-question').value = '';
                const container = document.getElementById('poll-options-container');
                // Reset to 2 options
                const inputs = container.querySelectorAll('input');
                if (inputs.length > 2) {
                    for(let i=2; i<inputs.length; i++) inputs[i].remove();
                }
                inputs[0].value = '';
                inputs[1].value = '';
            },

            closePollCreator() {
                document.getElementById('modal-poll-creator').classList.add('hidden');
            },

            addPollOptionInput() {
                const container = document.getElementById('poll-options-container');
                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'poll-option-input w-full p-3 bg-gray-50 dark:bg-slate-800 rounded-custom border border-gray-200 dark:border-gray-700 focus:border-brand focus:outline-none mt-2';
                input.placeholder = `Option ${container.children.length}`; // Simple count logic (label + inputs)
                container.appendChild(input);
            },

            savePoll() {
                const question = document.getElementById('poll-question').value.trim();
                const inputs = document.querySelectorAll('.poll-option-input');
                const options = [];
                inputs.forEach(input => {
                    if (input.value.trim()) options.push(input.value.trim());
                });

                if (!question || options.length < 2) {
                    this.showToast("Need a question and at least 2 options!");
                    return;
                }

                // Format poll data
                this.pollData = {
                    question: question,
                    options: options.map(opt => ({ text: opt, votes: 0 })),
                    totalVotes: 0,
                    votedIndex: -1
                };

                // Show Preview
                document.getElementById('poll-preview').classList.remove('hidden');
                document.getElementById('poll-preview-question').innerText = question;
                const list = document.getElementById('poll-preview-options');
                list.innerHTML = '';
                options.forEach(opt => {
                    const li = document.createElement('li');
                    li.innerText = opt;
                    list.appendChild(li);
                });

                // Clear Media (Mutually exclusive)
                this.removeAttachment();
                this.closePollCreator();
            },

            removePoll() {
                this.pollData = null;
                document.getElementById('poll-preview').classList.add('hidden');
            },

            // --- POST CREATION ---
            createPost() {
                const input = document.getElementById('new-post-content');
                const content = input.value.trim();
                
                if (!content && !this.attachment.url && !this.pollData) {
                    this.showToast("Create some content first!");
                    return;
                }

                let type = 'text';
                let mediaUrl = null;
                let poll = null;

                if (this.pollData) {
                    type = 'poll';
                    poll = JSON.parse(JSON.stringify(this.pollData)); // Deep copy
                } else if (this.attachment.url) {
                    type = this.attachment.type;
                    mediaUrl = this.attachment.url;
                }

                const newPost = {
                    id: Date.now(),
                    user: this.currentUser.name,
                    avatar: this.currentUser.avatar,
                    time: "Just now",
                    content: content,
                    type: type,
                    mediaUrl: mediaUrl,
                    poll: poll,
                    likes: 0,
                    comments: [],
                    isLiked: false,
                    isMine: true,
                    isAdmin: false // User posts are not admin
                };

                this.posts.unshift(newPost);
                this.savePosts();
                this.renderFeed();
                
                // Reset form
                input.value = "";
                this.removeAttachment();
                this.removePoll();
                
                this.showToast("Post published successfully!");
                document.getElementById('following-view').scrollTop = 0;
            },

            // --- FEED RENDERING ---
            renderFeed() {
                const container = document.getElementById('feed-container');
                container.innerHTML = '';

                this.posts.forEach(post => {
                    const postEl = document.createElement('div');
                    postEl.className = "bg-white dark:bg-slate-900 p-4 fade-in-up border-b border-gray-100 dark:border-slate-800 mb-2 shadow-sm";
                    
                    // Comments
                    let commentsHtml = '';
                    post.comments.forEach(c => {
                        commentsHtml += `<div class="flex gap-2 text-sm mb-1"><span class="font-bold text-xs self-center cursor-pointer hover:underline text-brand">${c.user}</span><span class="text-gray-600 dark:text-gray-300 flex-1">${c.text}</span></div>`;
                    });

                    // Media Content Generation
                    let mediaContent = '';
                    if (post.type === 'image' && post.mediaUrl) {
                        mediaContent = `<div class="mt-3 rounded-custom overflow-hidden max-h-80 border border-gray-100 dark:border-slate-800 bg-gray-50 dark:bg-slate-800"><img src="${post.mediaUrl}" class="w-full h-full object-cover"></div>`;
                    } else if (post.type === 'video' && post.mediaUrl) {
                        mediaContent = `<div class="mt-3 rounded-custom overflow-hidden max-h-80 border border-gray-100 dark:border-slate-800 bg-black"><video src="${post.mediaUrl}" class="w-full h-full object-contain" controls></video></div>`;
                    } else if (post.type === 'leaderboard' && post.leaderboard) {
                        // Leaderboard Rendering (Ad Style)
                        let rows = '';
                        post.leaderboard.top3.forEach((p, i) => {
                            let medal = '';
                            if(i===0) medal = '🥇';
                            if(i===1) medal = '🥈';
                            if(i===2) medal = '🥉';
                            rows += `
                                <div class="flex items-center justify-between p-3 ${i!==2?'border-b border-gray-100 dark:border-slate-700':''}">
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl">${medal}</span>
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=${p.avatar}" class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 dark:border-slate-600">
                                        <div>
                                            <p class="font-bold text-sm text-gray-800 dark:text-gray-200">${p.name}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">${p.rank}</p>
                                        </div>
                                    </div>
                                    <span class="font-bold text-brand text-sm">${p.score}</span>
                                </div>
                            `;
                        });

                        mediaContent = `
                            <div class="mt-3 border border-brand/30 rounded-custom overflow-hidden bg-gradient-to-br from-white to-blue-50 dark:from-slate-900 dark:to-slate-800 shadow-md relative group">
                                <div class="bg-brand text-white text-[10px] font-bold px-2 py-1 absolute top-0 right-0 rounded-bl-lg z-10 shadow-sm">SPONSORED</div>
                                <div class="p-4 border-b border-brand/10 relative overflow-hidden">
                                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-brand/10 rounded-full blur-xl"></div>
                                    <h4 class="font-bold text-brand uppercase text-xs tracking-wider mb-1 flex items-center gap-2"><i class="fas fa-trophy"></i> Monthly Leaderboard</h4>
                                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">${post.leaderboard.game} - ${post.leaderboard.month}</h3>
                                </div>
                                <div class="p-2 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm">
                                    ${rows}
                                </div>
                                <div class="p-3 bg-brand/5 text-center border-t border-brand/10">
                                    <button class="text-brand text-xs font-bold hover:underline flex items-center justify-center gap-1 w-full py-1">
                                        View Full Rankings & Shop Skins <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    } else if (post.type === 'poll' && post.poll) {
                        // Poll Rendering
                        let optionsHtml = '';
                        post.poll.options.forEach((opt, idx) => {
                            const isVoted = post.poll.votedIndex === idx;
                            const percentage = post.poll.totalVotes > 0 ? Math.round((opt.votes / post.poll.totalVotes) * 100) : 0;
                            const barColor = isVoted ? 'bg-brand' : 'bg-gray-200 dark:bg-slate-700';
                            
                            optionsHtml += `
                                <div class="relative mb-2 cursor-pointer group" onclick="app.votePoll(${post.id}, ${idx})">
                                    <div class="h-10 w-full bg-gray-50 dark:bg-slate-800 rounded-custom overflow-hidden border border-gray-100 dark:border-slate-700 relative">
                                        <div class="absolute top-0 left-0 h-full ${barColor} opacity-20 transition-all duration-500" style="width: ${percentage}%"></div>
                                        <div class="absolute inset-0 flex justify-between items-center px-4">
                                            <span class="text-sm font-medium ${isVoted ? 'text-brand' : ''}">${opt.text}</span>
                                            <span class="text-xs font-bold">${percentage}%</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        
                        mediaContent = `
                            <div class="mt-3 p-4 border border-gray-100 dark:border-slate-800 rounded-custom bg-white dark:bg-slate-900 shadow-sm">
                                <p class="font-bold mb-3 text-sm">${post.poll.question}</p>
                                <div>${optionsHtml}</div>
                                <p class="text-xs text-gray-400 mt-2 text-right">${post.poll.totalVotes} votes</p>
                            </div>
                        `;
                    }

                    const likeClass = post.isLiked ? 'text-brand' : 'text-gray-400 hover:text-brand';
                    const likeIcon = post.isLiked ? 'fas' : 'far';
                    
                    // Admin badge
                    const adminBadge = post.isAdmin ? '<span class="ml-2 text-[9px] bg-brand text-white px-1.5 py-0.5 rounded font-bold uppercase">Admin</span>' : '';
                    
                    postEl.innerHTML = `
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex gap-3">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=${post.avatar}" class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 dark:border-slate-700">
                                <div>
                                    <div class="flex items-center">
                                        <h3 class="font-bold text-sm hover:underline cursor-pointer">${post.user}</h3>
                                        ${adminBadge}
                                    </div>
                                    <p class="text-xs text-gray-400">${post.time}</p>
                                </div>
                            </div>
                            <button onclick="app.openPostOptions(${post.id}, ${post.isMine})" class="text-gray-400 hover:text-gray-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors"><i class="fas fa-ellipsis-h"></i></button>
                        </div>
                        <p class="text-sm leading-relaxed whitespace-pre-line">${post.content}</p>
                        ${mediaContent}
                        <div class="flex justify-between items-center mt-4 pt-3 border-t border-gray-100 dark:border-slate-800">
                            <div class="flex gap-6">
                                <button onclick="app.toggleLike(${post.id})" class="flex items-center gap-2 group transition-colors ${likeClass}"><i class="${likeIcon} fa-heart text-xl group-active:scale-125 transition-transform duration-200"></i><span class="text-xs font-bold" id="likes-count-${post.id}">${post.likes}</span></button>
                                <button onclick="app.toggleCommentSection(${post.id})" class="flex items-center gap-2 text-gray-400 hover:text-blue-500 transition-colors"><i class="far fa-comment-alt text-xl"></i><span class="text-xs font-bold">${post.comments.length}</span></button>
                            </div>
                        </div>
                        <div id="comments-section-${post.id}" class="mt-3 pt-2 ${post.comments.length === 0 ? 'hidden' : ''}">
                            <div class="bg-gray-50 dark:bg-slate-800/50 rounded-custom p-3">
                                <div id="comments-list-${post.id}" class="space-y-2 mb-2">${commentsHtml}</div>
                                <div class="flex gap-2 items-center mt-2">
                                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=${this.currentUser.avatar}" class="w-6 h-6 rounded-full">
                                    <input type="text" id="comment-input-${post.id}" placeholder="Add a comment..." class="flex-1 bg-transparent text-xs border-b border-gray-300 dark:border-gray-600 focus:border-brand focus:outline-none py-1" onkeypress="app.handleCommentKey(event, ${post.id})">
                                    <button onclick="app.addComment(${post.id})" class="text-brand text-xs font-bold uppercase">Post</button>
                                </div>
                            </div>
                        </div>`;
                    container.appendChild(postEl);
                });
            },
            
            votePoll(postId, optionIdx) {
                const post = this.posts.find(p => p.id === postId);
                if (post && post.type === 'poll') {
                    if (post.poll.votedIndex === optionIdx) return; // Already voted this
                    
                    // Simple switch vote logic
                    if (post.poll.votedIndex !== -1) {
                         post.poll.options[post.poll.votedIndex].votes--;
                    } else {
                        post.poll.totalVotes++;
                    }
                    
                    post.poll.votedIndex = optionIdx;
                    post.poll.options[optionIdx].votes++;
                    this.savePosts();
                    this.renderFeed();
                    this.showToast("Vote recorded!");
                }
            },

            renderTrendingFeed() {
                const container = document.getElementById('trending-view');
                container.innerHTML = '';

                // Setup Intersection Observer for Auto-play
                if (this.trendingObserver) this.trendingObserver.disconnect();
                
                this.trendingObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        const video = entry.target.querySelector('video');
                        if (entry.isIntersecting) {
                            video.play().catch(() => {}); // Ignore play errors
                        } else {
                            video.pause();
                            video.currentTime = 0;
                        }
                    });
                }, { root: container, threshold: 0.6 });

                this.trendingVideos.forEach(video => {
                    const vidEl = document.createElement('div');
                    vidEl.className = "w-full h-full snap-start relative bg-black flex items-center justify-center overflow-hidden border-b border-gray-800";
                    
                    const likeColor = video.isLiked ? 'text-red-500' : 'text-white';
                    const likeIcon = video.isLiked ? 'fas' : 'fas'; 

                    vidEl.innerHTML = `
                        <!-- Video -->
                        <video src="${video.video}" class="w-full h-full object-cover opacity-90 cursor-pointer" loop muted playsinline></video>
                        
                        <!-- Overlay Gradient -->
                        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/80 pointer-events-none"></div>

                        <!-- Right Sidebar Actions -->
                        <div class="absolute right-4 bottom-20 flex flex-col items-center gap-6 z-10 text-white">
                            <div class="flex flex-col items-center gap-1 group cursor-pointer" onclick="app.toggleVideoLike(${video.id})">
                                <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center group-active:scale-90 transition-transform">
                                    <i class="${likeIcon} fa-heart text-2xl ${likeColor} drop-shadow-md"></i>
                                </div>
                                <span class="text-xs font-bold drop-shadow-md">${video.likes}</span>
                            </div>
                            
                            <!-- Rotating Disc -->
                            <div class="w-12 h-12 bg-gray-800 rounded-full border-4 border-gray-900 overflow-hidden animate-spin-slow mt-4">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=${video.avatar}" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <!-- Bottom Info -->
                        <div class="absolute left-4 bottom-24 right-16 z-10 text-white text-left">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-10 h-10 rounded-full border border-white overflow-hidden">
                                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=${video.avatar}" class="w-full h-full object-cover">
                                </div>
                                <span class="font-bold text-sm drop-shadow-md">@${video.user}</span>
                            </div>
                            <p class="text-sm leading-snug drop-shadow-md line-clamp-2 pr-2">${video.desc}</p>
                            <div class="flex items-center gap-2 mt-2 opacity-80">
                                <i class="fas fa-music text-xs"></i>
                                <span class="text-xs overflow-hidden whitespace-nowrap w-40">Original Sound - ${video.user}</span>
                            </div>
                        </div>
                    `;
                    container.appendChild(vidEl);
                    this.trendingObserver.observe(vidEl);
                });
            },

            playVisibleVideo() {
                // Simplified autoplay logic
            },
            
            toggleVideoLike(id) {
                const video = this.trendingVideos.find(v => v.id === id);
                if(video) {
                    video.isLiked = !video.isLiked;
                    this.renderTrendingFeed(); 
                    if(video.isLiked) this.showToast('Liked Video!');
                }
            },

            toggleLike(id) { const post = this.posts.find(p => p.id === id); if (post) { post.isLiked = !post.isLiked; post.likes += post.isLiked ? 1 : -1; this.savePosts(); this.renderFeed(); } },
            toggleCommentSection(id) { const s = document.getElementById(`comments-section-${id}`); s.classList.toggle('hidden'); if(!s.classList.contains('hidden')) setTimeout(()=>document.getElementById(`comment-input-${id}`).focus(),100); },
            handleCommentKey(e, id) { if(e.key === 'Enter') this.addComment(id); },
            addComment(id) { const i = document.getElementById(`comment-input-${id}`); if(i.value.trim()) { const p = this.posts.find(x=>x.id===id); p.comments.push({user:this.currentUser.name, text:i.value.trim()}); this.savePosts(); this.renderFeed(); this.showToast("Commented"); } },
            toggleNotifications() { const d = document.getElementById('notif-dropdown'); d.classList.toggle('hidden'); if(!d.classList.contains('hidden')) document.getElementById('notif-badge').classList.add('hidden'); },
            viewStory(u, i) { const v = document.getElementById('story-viewer'); document.getElementById('story-username').innerText=u; document.getElementById('story-avatar').src=`https://api.dicebear.com/7.x/avataaars/svg?seed=${u}`; document.getElementById('story-image').src=i; v.classList.remove('hidden'); const b = document.getElementById('story-progress'); b.classList.remove('story-progress-bar'); void b.offsetWidth; b.classList.add('story-progress-bar'); this.storyTimeout = setTimeout(()=>this.closeStory(),3000); },
            addStory() { if(confirm("Post story?")) { this.showToast("Posting..."); setTimeout(()=>this.showToast("Story Live!"),1000); } },
            closeStory() { document.getElementById('story-viewer').classList.add('hidden'); clearTimeout(this.storyTimeout); },
            openPostOptions(id, m) { this.activePostId = id; const d = document.getElementById('opt-delete'); const r = document.getElementById('opt-report'); if(m) { d.classList.remove('hidden'); r.classList.add('hidden'); d.onclick=()=>{ if(confirm('Delete?')) { this.posts=this.posts.filter(x=>x.id!==id); this.savePosts(); this.renderFeed(); } this.closePostOptions(); } } else { d.classList.add('hidden'); r.classList.remove('hidden'); r.onclick=()=>{ this.showToast('Reported'); this.closePostOptions(); } } document.getElementById('post-options-modal').classList.remove('hidden'); },
            closePostOptions() { document.getElementById('post-options-modal').classList.add('hidden'); },
            showToast(m) { const t = document.getElementById('toast'); document.getElementById('toast-message').innerText = m; t.classList.remove('opacity-0'); clearTimeout(this.toastTimeout); this.toastTimeout = setTimeout(()=>t.classList.add('opacity-0'),2000); }
        };

        document.addEventListener('DOMContentLoaded', () => app.init());
    </script>
</body>
</html>