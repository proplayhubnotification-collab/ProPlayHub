<style>
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
    
    .story-progress-bar { animation: progressBar 3s linear forwards; }
    @keyframes progressBar { from { width: 0%; } to { width: 100%; } }
</style>

<!-- Header (Adjusts based on tab) -->
<div id="main-header" class="px-4 py-3 flex items-center justify-between bg-white/90 dark:bg-dark-surface/90 shadow-sm z-30 fixed top-0 w-full max-w-md transition-all duration-300 backdrop-blur-md border-b border-slate-100 dark:border-white/5">
    <div class="flex gap-4 absolute left-1/2 -translate-x-1/2">
        <button onclick="app.switchTab('following')" id="tab-following" class="font-bold text-brand border-b-2 border-brand pb-1 transition-colors text-shadow">Following</button>
        <button onclick="app.switchTab('trending')" id="tab-trending" class="font-bold text-slate-400 dark:text-slate-500 pb-1 hover:text-slate-600 dark:hover:text-slate-300 transition-colors border-b-2 border-transparent text-shadow">Trending</button>
    </div>
    
    <!-- Notification Bell -->
    <div class="relative z-40">
        <button onclick="window.location.href='../PHP_CSR/csrNotifications.php'" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 dark:bg-white/5 text-brand relative hover:bg-slate-200 dark:hover:bg-white/10 transition-colors" id="notif-btn">
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
            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=AdminCSR" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800">
            <div class="flex-1">
                <textarea id="new-post-content" rows="2" placeholder="Post as Admin..." class="w-full bg-transparent border-none focus:ring-0 text-sm resize-none pt-2 placeholder-slate-400 dark:text-white"></textarea>
                
                <!-- Preview Attached Media -->
                <div id="attachment-preview" class="hidden mt-2 relative w-full h-32 rounded-lg overflow-hidden bg-black">
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
    <div class="bg-white dark:bg-slate-900 rounded-t-2xl h-[70vh] flex flex-col slide-up shadow-2xl">
        <div class="p-4 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center">
            <h3 class="font-bold text-lg">Select Media</h3>
            <button onclick="app.closeMediaGallery()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <div class="flex p-2 gap-2">
            <button id="tab-gallery-photos" onclick="app.switchGalleryTab('image')" class="flex-1 py-2 rounded-lg bg-brand text-white text-sm font-bold">Photos</button>
            <button id="tab-gallery-videos" onclick="app.switchGalleryTab('video')" class="flex-1 py-2 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-500 text-sm font-bold">Videos</button>
        </div>
        <div id="gallery-grid" class="flex-1 overflow-y-auto p-2 grid grid-cols-3 gap-1"></div>
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
    <div class="flex gap-1 p-2 pt-4">
        <div class="h-1 bg-gray-600 rounded-full flex-1 overflow-hidden">
            <div id="story-progress" class="h-full bg-white w-0"></div>
        </div>
    </div>
    
    <div class="flex justify-between items-center px-4 py-2 text-white">
        <div class="flex items-center gap-3">
            <img id="story-avatar" src="" class="w-8 h-8 rounded-full border border-white">
            <span id="story-username" class="font-bold text-sm">Username</span>
            <span class="text-xs text-gray-300">10m</span>
        </div>
        <button onclick="app.closeStory()" class="text-white p-2"><i class="fas fa-times text-xl"></i></button>
    </div>

    <div class="flex-1 flex items-center justify-center bg-gray-900 relative">
        <img id="story-image" src="" class="max-h-full max-w-full object-contain">
        <div onclick="app.showToast('Previous Story')" class="absolute left-0 top-0 bottom-0 w-1/3"></div>
        <div onclick="app.closeStory()" class="absolute right-0 top-0 bottom-0 w-1/3"></div> 
    </div>

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

<!-- Comments Modal -->
<div id="modal-comments" class="hidden fixed inset-0 z-[90] bg-black/50 flex flex-col justify-end" onclick="app.closeComments()">
    <div class="bg-white dark:bg-slate-900 rounded-t-2xl h-[70vh] flex flex-col slide-up shadow-2xl" onclick="event.stopPropagation()">
        <div class="p-4 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center">
            <h3 class="font-bold text-lg">Comments</h3>
            <button onclick="app.closeComments()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
        </div>
        <div id="comments-list" class="flex-1 overflow-y-auto p-4 space-y-4">
            <!-- Comments injected here -->
        </div>
        <div class="p-4 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 pb-8">
            <div class="flex gap-2 items-center">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=AdminCSR" class="w-8 h-8 rounded-full bg-gray-100">
                <div class="flex-1 relative">
                    <input type="text" id="comment-input" placeholder="Add a comment..." class="w-full bg-gray-100 dark:bg-slate-800 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-brand" onkeypress="if(event.key === 'Enter') app.addComment()">
                    <button onclick="app.addComment()" class="absolute right-2 top-1/2 -translate-y-1/2 text-brand font-bold text-sm hover:text-brand-dark px-2">Post</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const app = {
    currentUser: {
        name: "CSR Admin",
        avatar: "AdminCSR",
        rank: "Admin"
    },
    currentTab: 'following',
    attachment: { type: null, url: null },
    pollData: null,
    activePostId: null,

    mockImages: [
        "https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=400",
        "https://images.unsplash.com/photo-1612815154858-60aa4c4603e1?auto=format&fit=crop&q=80&w=400",
        "https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&q=80&w=400"
    ],
    mockVideos: [
        "https://interactive-examples.mdn.mozilla.net/media/cc0-videos/flower.webm",
        "https://www.w3schools.com/html/mov_bbb.mp4"
    ],

    posts: [],

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
            user: "ValorantQueen",
            avatar: "Sarah",
            desc: "ACE with Sheriff only! No armor challenge 😱 #valorant #sheriff #ace",
            video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4",
            likes: "8.5K",
            isLiked: false
        },
        {
            id: 103,
            user: "Apex_Legend_Jay",
            avatar: "Jay",
            desc: "NEW META loadout for Season 19! You need to try this 🎯 #apexlegends #meta",
            video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4",
            likes: "15K",
            isLiked: false
        },
        {
            id: 104,
            user: "MinecraftMaster",
            avatar: "Steve",
            desc: "Built an ENTIRE city in Survival Mode! 200 hours of work 🏙️ #minecraft #build",
            video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
            likes: "22K",
            isLiked: true
        },
        {
            id: 105,
            user: "LeagueOfLeo",
            avatar: "Leo",
            desc: "Pentakill as Yasuo in ranked! Wind wall saved my life 🌪️ #lol #yasuo #pentakill",
            video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4",
            likes: "9.2K",
            isLiked: false
        },
        {
            id: 106,
            user: "FortniteFrenzy",
            avatar: "Alex",
            desc: "Zero Build Victory Royale with ONLY Pistols! 🏆 #fortnite #zerobuild #victoryroyale",
            video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4",
            likes: "11K",
            isLiked: false
        },
        {
            id: 107,
            user: "OverwatchOG",
            avatar: "Mercy",
            desc: "5-man Resurrection clutch! My team was screaming 😂 #overwatch #support #clutch",
            video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4",
            likes: "18K",
            isLiked: false
        },
        {
            id: 108,
            user: "GenshinGamer",
            avatar: "Keqing",
            desc: "Pulled C6 in 10 wishes! My luck is INSANE 💎 #genshinimpact #gacha #lucky",
            video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4",
            likes: "25K",
            isLiked: true
        },
        {
            id: 109,
            user: "RocketLeaguePro",
            avatar: "Octane",
            desc: "Ceiling shot into double flip reset! Hours of practice paid off 🚗 #rocketleague #freestyle",
            video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/SubaruOutbackOnStreetAndDirt.mp4",
            likes: "13K",
            isLiked: false
        },
        {
            id: 110,
            user: "DotaLegend",
            avatar: "Invoker",
            desc: "10-spell combo team wipe! This is why I main Invoker 🔮 #dota2 #invoker #combo",
            video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4",
            likes: "16K",
            isLiked: false
        }
    ],

    getDefaultPosts() {
        return [
            {
                id: 999,
                user: "ProPlay Shop",
                avatar: "Shop",
                time: "Sponsored",
                content: "🏆 The champions of December are here! Check out who's dominating the Valorant ladder this month. Gear up like a pro at our shop!",
                type: "text",
                mediaUrl: null,
                poll: null,
                likes: 1542,
                comments: [],
                isLiked: false,
                isMine: false,
                isAdmin: false
            },
            {
                id: 1,
                user: "Zack_Gamer",
                avatar: "Zack",
                time: "2h ago",
                content: "Finally reached Ascendant in Valorant! 🎮 Need a duo partner.",
                type: "image",
                mediaUrl: "https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=1000",
                poll: null,
                likes: 124,
                comments: [{ user: "Molly.FPS", text: "Congrats!" }],
                isLiked: false,
                isMine: false,
                isAdmin: false
            }
        ];
    },

    init() {
        const storedPosts = localStorage.getItem('proplay_social_posts');
        if (storedPosts) {
            try {
                this.posts = JSON.parse(storedPosts);
            } catch (e) {
                this.posts = this.getDefaultPosts();
                this.savePosts();
            }
        } else {
            this.posts = this.getDefaultPosts();
            this.savePosts();
        }
        
        this.renderFeed();
        this.renderTrendingFeed();
        
        // Listen for storage changes from other tabs/windows
        window.addEventListener('storage', (e) => {
            if (e.key === 'proplay_social_posts') {
                try {
                    this.posts = JSON.parse(e.newValue);
                    this.renderFeed();
                } catch (err) {}
            }
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
            // Active: Following (Brand)
            btnFollowing.classList.add('text-brand', 'border-brand');
            btnFollowing.classList.remove('text-gray-400', 'dark:text-gray-600', 'border-transparent', 'text-white', 'text-white/60');
            
            // Inactive: Trending (Gray)
            btnTrending.classList.remove('text-brand', 'border-brand', 'text-white', 'drop-shadow-md');
            btnTrending.classList.add('text-gray-400', 'dark:text-gray-600', 'border-transparent');
            
            viewFollowing.classList.remove('hidden');
            viewTrending.classList.add('hidden');
            header.classList.remove('bg-transparent', 'text-white');
            header.classList.add('bg-white', 'dark:bg-slate-900', 'shadow-sm');
            notifBtn.classList.remove('bg-white/20', 'text-white', 'hover:bg-white/30');
            notifBtn.classList.add('bg-gray-100', 'dark:bg-slate-800', 'text-brand');
            document.querySelectorAll('#trending-view video').forEach(v => v.pause());
        } else {
            // Active: Trending (Brand - Unified)
            btnTrending.classList.add('text-brand', 'border-brand', 'drop-shadow-md');
            btnTrending.classList.remove('text-gray-400', 'dark:text-gray-600', 'border-transparent', 'text-white');
            
            // Inactive: Following (White/60)
            btnFollowing.classList.remove('text-brand', 'border-brand');
            btnFollowing.classList.add('text-white/60', 'border-transparent');
            
            viewTrending.classList.remove('hidden');
            viewFollowing.classList.add('hidden');
            header.classList.remove('bg-white', 'dark:bg-slate-900', 'shadow-sm');
            header.classList.add('bg-transparent', 'text-white');
            notifBtn.classList.remove('bg-gray-100', 'dark:bg-slate-800', 'text-brand');
            notifBtn.classList.add('bg-white/20', 'text-white', 'hover:bg-white/30');
        }
    },

    savePosts() {
        localStorage.setItem('proplay_social_posts', JSON.stringify(this.posts));
    },

    openMediaGallery(type) {
        this.galleryType = type;
        document.getElementById('modal-media-gallery').classList.remove('hidden');
        this.switchGalleryTab(type);
    },
    
    closeMediaGallery() {
        document.getElementById('modal-media-gallery').classList.add('hidden');
    },

    switchGalleryTab(type) {
        const grid = document.getElementById('gallery-grid');
        grid.innerHTML = '';
        const sources = type === 'image' ? this.mockImages : this.mockVideos;
        sources.forEach(src => {
            if (type === 'image') {
                const img = document.createElement('img');
                img.src = src;
                img.className = "w-full aspect-square object-cover rounded-lg cursor-pointer hover:opacity-80 transition-all duration-200";
                img.onclick = (e) => this.selectMedia(src, 'image', e.target);
                grid.appendChild(img);
            } else {
                // Video Thumbnail (Mock)
                const div = document.createElement('div');
                div.className = "w-full aspect-square bg-gray-800 rounded-lg cursor-pointer flex items-center justify-center hover:bg-gray-700 transition-all duration-200 relative overflow-hidden";
                div.innerHTML = `<video src="${src}" class="w-full h-full object-cover opacity-60"></video><i class="fas fa-play text-white text-2xl absolute z-10"></i>`;
                div.onclick = (e) => this.selectMedia(src, 'video', e.currentTarget);
                grid.appendChild(div);
            }
        });
    },

    selectMedia(url, type, element) {
        // Visual Feedback
        if (element) {
            element.classList.add('ring-4', 'ring-brand', 'scale-95');
            setTimeout(() => element.classList.remove('scale-95'), 150);
        }

        // Delay closing for smooth UX
        setTimeout(() => {
            this.attachment = { type, url };
            this.closeMediaGallery();
            
            const preview = document.getElementById('attachment-preview');
            let content = '';
            
            if (type === 'image') {
                content = `<img src="${url}" class="w-full h-full object-cover">`;
            } else {
                content = `<video src="${url}" class="w-full h-full object-cover" controls></video>`;
            }
            
            preview.innerHTML = `${content}<button onclick="app.removeAttachment()" class="absolute top-1 right-1 bg-black/50 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-500 z-10"><i class="fas fa-times text-xs"></i></button>`;
            
            preview.classList.remove('hidden');
            preview.classList.add('fade-in-up'); // Animate preview
            this.removePoll();
        }, 300);
    },

    removeAttachment() {
        this.attachment = { type: null, url: null };
        document.getElementById('attachment-preview').classList.add('hidden');
    },

    openPollCreator() {
        document.getElementById('modal-poll-creator').classList.remove('hidden');
    },

    closePollCreator() {
        document.getElementById('modal-poll-creator').classList.add('hidden');
    },

    addPollOptionInput() {
        const container = document.getElementById('poll-options-container');
        const input = document.createElement('input');
        input.className = 'poll-option-input w-full p-3 bg-gray-50 dark:bg-slate-800 rounded-custom border border-gray-200 dark:border-gray-700 mt-2';
        container.appendChild(input);
    },

    savePoll() {
        const question = document.getElementById('poll-question').value.trim();
        const inputs = document.querySelectorAll('.poll-option-input');
        const options = [];
        inputs.forEach(input => { if (input.value.trim()) options.push(input.value.trim()); });
        if (!question || options.length < 2) return this.showToast("Need question and 2+ options!");
        this.pollData = { question, options: options.map(opt => ({ text: opt, votes: 0 })), totalVotes: 0, votedIndex: -1 };
        document.getElementById('poll-preview').classList.remove('hidden');
        this.removeAttachment();
        this.closePollCreator();
    },

    removePoll() {
        this.pollData = null;
        document.getElementById('poll-preview').classList.add('hidden');
    },

    createPost() {
        const content = document.getElementById('new-post-content').value.trim();
        if (!content && !this.attachment.url && !this.pollData) return this.showToast("Create content first!");
        
        const newPost = {
            id: Date.now(),
            user: this.currentUser.name,
            avatar: this.currentUser.avatar,
            time: "Just now",
            content,
            type: this.pollData ? 'poll' : (this.attachment.url ? this.attachment.type : 'text'),
            mediaUrl: this.attachment.url,
            poll: this.pollData,
            likes: 0,
            comments: [],
            isLiked: false,
            isMine: true,
            isAdmin: true
        };
        
        this.posts.unshift(newPost);
        this.savePosts();
        
        // Animate new post insertion
        const container = document.getElementById('feed-container');
        const postEl = this.createPostElement(newPost);
        postEl.classList.add('fade-in-up'); // Add animation class
        
        // Remove "No posts" message if it exists
        if (this.posts.length === 1) container.innerHTML = '';
        
        container.prepend(postEl);
        
        // Reset Inputs
        document.getElementById('new-post-content').value = "";
        this.removeAttachment();
        this.removePoll();
        this.showToast("Posted!");
    },

    createPostElement(post) {
        const postEl = document.createElement('div');
        postEl.className = "bg-white dark:bg-slate-900 p-4 border-b border-gray-100 dark:border-slate-800 mb-2";
        
        let mediaContent = '';
        if (post.type === 'image' && post.mediaUrl) {
            mediaContent = `<div class="mt-3 rounded-custom overflow-hidden max-h-80"><img src="${post.mediaUrl}" class="w-full h-full object-cover"></div>`;
        } else if (post.type === 'video' && post.mediaUrl) {
            mediaContent = `<div class="mt-3 rounded-custom overflow-hidden max-h-80"><video src="${post.mediaUrl}" class="w-full h-full object-cover" controls></video></div>`;
        } else if (post.type === 'poll' && post.poll) {
            // Poll Rendering Logic (Simplified for now)
            mediaContent = `<div class="mt-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-800">
                <p class="font-bold text-sm mb-2">${post.poll.question}</p>
                <div class="space-y-2">
                    ${post.poll.options.map(opt => `
                        <div class="w-full p-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-700 rounded text-xs font-medium text-center cursor-pointer hover:border-brand transition-colors">
                            ${opt.text}
                        </div>
                    `).join('')}
                </div>
                <p class="text-xs text-gray-400 mt-2 text-right">0 votes</p>
            </div>`;
        }
        
        const adminBadge = post.isAdmin ? '<span class="ml-2 text-[9px] bg-brand text-white px-1.5 py-0.5 rounded font-bold uppercase">Admin</span>' : '';
        const deleteBtn = `<button onclick="app.deletePost(${post.id})" class="text-gray-400 hover:text-red-500 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors"><i class="fas fa-trash-alt text-xs"></i></button>`;
        
        postEl.innerHTML = `
            <div class="flex justify-between items-start mb-3">
                <div class="flex gap-3">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=${post.avatar}" class="w-10 h-10 rounded-full">
                    <div>
                        <div class="flex items-center">
                            <h3 class="font-bold text-sm">${post.user}</h3>
                            ${adminBadge}
                        </div>
                        <p class="text-xs text-gray-400">${post.time}</p>
                    </div>
                </div>
                ${deleteBtn}
            </div>
            <p class="text-sm">${post.content}</p>
            ${mediaContent}
            <div class="flex gap-6 mt-4 pt-3 border-t border-gray-100 dark:border-slate-800">
                <button onclick="app.toggleLike(${post.id})" class="flex items-center gap-2 text-gray-400"><i class="${post.isLiked ? 'fas' : 'far'} fa-heart ${post.isLiked ? 'text-brand' : ''}"></i><span class="text-xs">${post.likes}</span></button>
                <button onclick="app.openComments(${post.id})" class="flex items-center gap-2 text-gray-400 hover:text-brand transition-colors"><i class="far fa-comment-alt"></i><span class="text-xs">${post.comments ? post.comments.length : 0}</span></button>
            </div>
        `;
        return postEl;
    },

    renderFeed() {
        const container = document.getElementById('feed-container');
        container.innerHTML = this.posts.length === 0 ? '<p class="text-center text-gray-400 py-8">No posts yet</p>' : '';
        this.posts.forEach(post => {
            container.appendChild(this.createPostElement(post));
        });
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
    
    toggleLike(id) {
        const post = this.posts.find(p => p.id === id);
        if (post) {
            post.isLiked = !post.isLiked;
            post.likes += post.isLiked ? 1 : -1;
            this.savePosts();
            this.renderFeed();
        }
    },

    deletePost(id) {
        if (confirm('Delete this post? This action cannot be undone.')) {
            this.posts = this.posts.filter(p => p.id !== id);
            this.savePosts();
            this.renderFeed();
            this.showToast('Post deleted');
        }
    },

    closeStory() {
        document.getElementById('story-viewer').classList.add('hidden');
    },

    openPostOptions(id, isMine) {},
    closePostOptions() {
        document.getElementById('post-options-modal').classList.add('hidden');
    },

    // Comments System
    openComments(id) {
        this.activePostId = id;
        const post = this.posts.find(p => p.id === id);
        if (!post) return;
        
        document.getElementById('modal-comments').classList.remove('hidden');
        this.renderCommentsList(post);
    },

    closeComments() {
        document.getElementById('modal-comments').classList.add('hidden');
        this.activePostId = null;
    },

    renderCommentsList(post) {
        const list = document.getElementById('comments-list');
        list.innerHTML = '';
        
        if (!post.comments || post.comments.length === 0) {
            list.innerHTML = '<div class="text-center text-gray-400 py-8">No comments yet. Be the first!</div>';
            return;
        }

        post.comments.forEach(comment => {
            const el = document.createElement('div');
            el.className = 'flex gap-3';
            el.innerHTML = `
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=${comment.user}" class="w-8 h-8 rounded-full bg-gray-100">
                <div class="flex-1">
                    <div class="bg-gray-100 dark:bg-slate-800 rounded-2xl px-4 py-2 rounded-tl-none">
                        <span class="font-bold text-sm block text-brand">${comment.user}</span>
                        <p class="text-sm text-gray-700 dark:text-gray-300">${comment.text}</p>
                    </div>
                    <div class="flex gap-4 mt-1 ml-2 text-xs text-gray-400">
                        <button class="hover:text-brand">Like</button>
                        <button class="hover:text-brand">Reply</button>
                        <span>Just now</span>
                    </div>
                </div>
            `;
            list.appendChild(el);
        });
        
        // Scroll to bottom
        list.scrollTop = list.scrollHeight;
    },

    addComment() {
        if (!this.activePostId) return;
        const input = document.getElementById('comment-input');
        const text = input.value.trim();
        if (!text) return;

        const post = this.posts.find(p => p.id === this.activePostId);
        if (post) {
            if (!post.comments) post.comments = [];
            post.comments.push({
                user: this.currentUser.name,
                text: text,
                time: new Date().toISOString()
            });
            
            this.savePosts();
            this.renderFeed(); // Update comment count in feed
            this.renderCommentsList(post); // Update modal
            input.value = '';
        }
    },

    showToast(m) {
        const t = document.getElementById('toast');
        document.getElementById('toast-message').innerText = m;
        t.classList.remove('opacity-0');
        setTimeout(() => t.classList.add('opacity-0'), 2000);
    }
};

document.addEventListener('DOMContentLoaded', () => app.init());
</script>
