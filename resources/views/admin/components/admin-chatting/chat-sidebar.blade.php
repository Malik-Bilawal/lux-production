<div class="w-96 bg-white rounded-2xl shadow-lg flex flex-col border border-gray-200">
    <!-- Filter Tabs -->
    <div class="flex border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
        <button id="allChatsTab" class="flex-1 py-4 text-center font-semibold border-b-2 border-blue-500 text-blue-500 transition-all duration-300">
            <i class="fas fa-comments mr-2"></i>All Chats
        </button>
        <button id="groupsTab" class="flex-1 py-4 text-center font-semibold text-gray-500 hover:text-gray-700 transition-all duration-300">
            <i class="fas fa-users mr-2"></i>Groups
        </button>
        <button id="unreadTab" class="flex-1 py-4 text-center font-semibold text-gray-500 hover:text-gray-700 transition-all duration-300 relative">
            <i class="fas fa-bell mr-2"></i>Unread
            <span id="unreadBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden notification-badge">0</span>
        </button>
    </div>

    <!-- Search Bar -->
    <div class="p-4 border-b border-gray-200 bg-white">
        <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="chatSearch" placeholder="Search chats..." 
                   class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
        </div>
    </div>

    <!-- Chat Lists -->
    <div class="flex-1 overflow-y-auto">
        <div id="individualChats" class="chat-list p-2"></div>
        <div id="groupChats" class="chat-list p-2 hidden"></div>
        <div id="unreadChats" class="chat-list p-2 hidden"></div>
    </div>

    <!-- Online Status -->
    <div class="p-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
        <div class="flex items-center justify-between text-sm">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-gray-600">Online</span>
            </div>
            <span class="text-gray-500" id="onlineCount">0 admins online</span>
        </div>
    </div>
</div>

<script>
class SidebarManager {
    constructor() {
        this.sidebar = {
            private: document.getElementById("individualChats"),
            group: document.getElementById("groupChats"),
            unread: document.getElementById("unreadChats")
        };

        this.tabs = {
            allChatsTab: "private",
            groupsTab: "group", 
            unreadTab: "unread"
        };

        this.currentFilter = "private";
        this.allChats = [];

        this.init();
    }

    init() {
        this.initTabs();
        this.loadChats("private");
        this.setupSearch();
        this.setupPusher();
        console.log('[SidebarManager] ✅ Initialized');
    }

    initTabs() {
        Object.keys(this.tabs).forEach(tabId => {
            const tab = document.getElementById(tabId);
            if (!tab) return;
            
            tab.addEventListener("click", () => {
                this.loadChats(this.tabs[tabId]);
                this.updateTabStyles(tabId);
            });
        });
    }

    updateTabStyles(activeId) {
        Object.keys(this.tabs).forEach(id => {
            const el = document.getElementById(id);
            el.classList.remove("border-blue-500", "text-blue-500");
            el.classList.add("text-gray-500");
        });
        
        const active = document.getElementById(activeId);
        active.classList.remove("text-gray-500");
        active.classList.add("border-blue-500", "text-blue-500");
    }

    setupSearch() {
        const searchInput = document.getElementById('chatSearch');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.filterChats(e.target.value);
            });
        }
    }

    setupPusher() {
    if (!window.pusherInstance) return;

    const presenceChannel = window.pusherInstance.subscribe('presence-admin-chat');
    
    presenceChannel.bind('pusher:subscription_succeeded', (members) => {
        this.updateOnlineCount(members.count);
    });

    presenceChannel.bind('pusher:member_added', (member) => {
        this.updateOnlineCount(presenceChannel.members.count);
    });

    presenceChannel.bind('pusher:member_removed', (member) => {
        this.updateOnlineCount(presenceChannel.members.count);
    });
}

    updateOnlineCount(count) {
        const onlineCountElement = document.getElementById('onlineCount');
        if (onlineCountElement) {
            onlineCountElement.textContent = `${count} admin${count !== 1 ? 's' : ''} online`;
        }
    }

    async loadChats(type) {
        this.currentFilter = type;
        
        try {
            console.log(`[Sidebar] Loading chats for type: ${type}`);
            const res = await fetch("/admin/chat/sidebar");
            const data = await res.json();
            
            if (data?.data) {
                this.allChats = data.data[type] || [];
                this.renderChatList(this.allChats, type);
                this.updateUnreadBadge();
            } else {
                console.warn(`[Sidebar] No data found for type: ${type}`);
                this.renderChatList([], type);
            }
        } catch (err) {
            console.error("[Sidebar] Failed to load chats:", err);
            this.renderChatList([], type);
        }
    }

    renderChatList(list, type) {
        console.log(`[Sidebar] Rendering ${list.length} chats for type: ${type}`);

        // Hide all sidebar sections
        Object.values(this.sidebar).forEach(div => div.classList.add("hidden"));
        
        const target = this.sidebar[type];
        if (!target) {
            console.error(`[Sidebar] No target found for type: ${type}`);
            return;
        }

        target.innerHTML = "";

        if (!list.length) {
            target.innerHTML = this.getEmptyState(type);
            target.classList.remove("hidden");
            return;
        }

        list.forEach(chat => {
            const item = this.createChatListItem(chat, type);
            target.appendChild(item);
        });

        target.classList.remove("hidden");
    }

    getEmptyState(type) {
        const messages = {
            private: {
                icon: 'fas fa-user-friends',
                title: 'No personal chats',
                description: 'Start a conversation with another admin'
            },
            group: {
                icon: 'fas fa-users',
                title: 'No group chats',
                description: 'Create a group to start chatting'
            },
            unread: {
                icon: 'fas fa-bell-slash',
                title: 'No unread messages',
                description: 'You\'re all caught up!'
            }
        };

        const msg = messages[type] || messages.private;

        return `
            <div class="text-center text-gray-400 py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="${msg.icon} text-2xl text-gray-400"></i>
                </div>
                <p class="font-medium text-gray-500">${msg.title}</p>
                <p class="text-sm text-gray-400 mt-1">${msg.description}</p>
            </div>
        `;
    }

    createChatListItem(chat, type) {
        const item = document.createElement("div");
        item.className = `chat-list-item p-3 rounded-xl mb-2 hover:bg-gray-50 cursor-pointer border border-transparent hover:border-gray-200 transition-all duration-300 ${
            window.chatManager?.activeChatId === chat.id ? 'active bg-blue-50 border-blue-200' : ''
        }`;

        const imgUrl = chat.profile_pic || '/images/default-avatar.png';
        const lastMessage = chat.last_message ? 
            `<p class="text-sm text-gray-600 truncate">${this.escapeHtml(chat.last_message)}</p>` : 
            '<p class="text-sm text-gray-400 italic">No messages yet</p>';

        const timeAgo = chat.last_message_time ? this.getTimeAgo(chat.last_message_time) : '';

        item.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="relative flex-shrink-0">
                    <img class="h-12 w-12 rounded-xl object-cover border-2 border-white shadow-sm" 
                         src="${imgUrl}" alt="${chat.name}" 
                         onerror="this.src='/images/default-avatar.png'">
                    ${chat.unread_count > 0 ? `
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center notification-badge">
                            ${chat.unread_count > 9 ? '9+' : chat.unread_count}
                        </span>
                    ` : ''}
                    ${chat.is_online ? `
                        <span class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                    ` : ''}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="font-semibold text-gray-800 truncate">${this.escapeHtml(chat.name)}</h4>
                        ${timeAgo ? `<span class="text-xs text-gray-500 flex-shrink-0 ml-2">${timeAgo}</span>` : ''}
                    </div>
                    ${lastMessage}
                </div>
            </div>
        `;

        item.addEventListener("click", () => {
            console.log(`[Sidebar] Opening chat:`, chat);
            
            // Remove active state from all items
            document.querySelectorAll('.chat-list-item').forEach(el => {
                el.classList.remove('active', 'bg-blue-50', 'border-blue-200');
            });
            
            // Add active state to clicked item
            item.classList.add('active', 'bg-blue-50', 'border-blue-200');
            
            if (chat.type === 'group') {
                window.chatManager.setActiveChat(chat.id, 'group', chat.name, chat.profile_pic);
            } else {
                window.chatManager.setActiveChat(chat.id, 'personal', chat.name, chat.profile_pic, chat.receiver_id || chat.id);
            }
        });

        return item;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    getTimeAgo(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) { // Less than 1 minute
            return 'now';
        } else if (diff < 3600000) { // Less than 1 hour
            return Math.floor(diff / 60000) + 'm';
        } else if (diff < 86400000) { // Less than 1 day
            return Math.floor(diff / 3600000) + 'h';
        } else if (diff < 604800000) { // Less than 1 week
            return Math.floor(diff / 86400000) + 'd';
        } else {
            return date.toLocaleDateString([], { month: 'short', day: 'numeric' });
        }
    }

    filterChats(searchTerm) {
        if (!searchTerm) {
            this.renderChatList(this.allChats, this.currentFilter);
            return;
        }

        const filteredChats = this.allChats.filter(chat => 
            chat.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (chat.last_message && chat.last_message.toLowerCase().includes(searchTerm.toLowerCase()))
        );
        
        this.renderChatList(filteredChats, this.currentFilter);
    }

    refreshChatList() {
        this.loadChats(this.currentFilter);
    }

    updateUnreadBadge() {
        const unreadBadge = document.getElementById('unreadBadge');
        if (!unreadBadge) return;

        const totalUnread = this.allChats.reduce((total, chat) => total + (chat.unread_count || 0), 0);
        
        if (totalUnread > 0) {
            unreadBadge.textContent = totalUnread > 9 ? '9+' : totalUnread;
            unreadBadge.classList.remove('hidden');
        } else {
            unreadBadge.classList.add('hidden');
        }
    }

    updateChatItem(chatId, updates) {
        // Find and update specific chat item
        const chatItems = document.querySelectorAll('.chat-list-item');
        chatItems.forEach(item => {
            const nameElement = item.querySelector('h4');
            if (nameElement && nameElement.textContent.includes(chatId)) {
                // Update the item with new data
                // This is a simplified implementation
                console.log('Updating chat item:', chatId, updates);
            }
        });
    }
}

class GroupManager {
    constructor() {
        this.modal = document.getElementById('newGroupModal');
        this.form = document.getElementById('newGroupForm');
        this.groupImageInput = document.getElementById('groupImage');
        this.preview = document.getElementById('groupImagePreview');
        this.selectAll = document.getElementById('selectAll');
        this.membersContainer = document.getElementById('membersContainer');

        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        this.groupImageInput?.addEventListener('change', (e) => this.handleImagePreview(e));
        this.selectAll?.addEventListener('change', (e) => this.toggleAllMembers(e));
        this.form?.addEventListener('submit', (e) => this.handleFormSubmit(e));
    }

    openNewGroupModal() {
        if (this.modal) {
            this.modal.classList.remove('hidden');
            this.loadAdminMembers();
        }
    }

    closeNewGroupModal() {
        if (this.modal) {
            this.modal.classList.add('hidden');
            this.form.reset();
            this.preview.src = '';
            this.preview.classList.add('hidden');
        }
    }

    handleImagePreview(event) {
        const file = event.target.files[0];
        if (file && this.preview) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.preview.src = e.target.result;
                this.preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    toggleAllMembers(event) {
        const checkboxes = document.querySelectorAll('.member-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = event.target.checked;
        });
    }

    async loadAdminMembers() {
        if (!this.membersContainer) return;

        this.membersContainer.innerHTML = `
            <div class="flex justify-center items-center py-8">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                <span class="ml-2 text-gray-500">Loading admins...</span>
            </div>
        `;

        try {
            const response = await fetch('/admin/chat/members');
            const data = await response.json();

            if (data && Array.isArray(data)) {
                this.renderAdminMembers(data);
            } else {
                this.membersContainer.innerHTML = `
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-users text-3xl mb-2"></i>
                        <p>No admins found</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Failed to load members:', error);
            this.membersContainer.innerHTML = `
                <div class="text-center py-8 text-red-400">
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Failed to load admins</p>
                </div>
            `;
        }
    }

    renderAdminMembers(admins) {
        this.membersContainer.innerHTML = '';

        admins.forEach(admin => {
            const memberElement = this.createMemberElement(admin);
            this.membersContainer.appendChild(memberElement);
        });
    }

    createMemberElement(admin) {
        const div = document.createElement('div');
        div.className = 'flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors';

        div.innerHTML = `
            <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 member-checkbox" value="${admin.id}">
            <label class="ml-3 flex items-center cursor-pointer flex-1">
                <img class="h-10 w-10 rounded-lg object-cover mr-3 border" 
                     src="${admin.profile_pic || '/images/default-avatar.png'}" 
                     alt="${admin.name}" 
                     onerror="this.src='/images/default-avatar.png'">
                <div>
                    <span class="font-medium text-gray-800 block">${admin.name}</span>
                    <span class="text-xs text-gray-500">${admin.role || 'Admin'}</span>
                </div>
            </label>
        `;

        return div;
    }

    async handleFormSubmit(event) {
        event.preventDefault();

        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        try {
            // Show loading state
            submitBtn.innerHTML = `
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                Creating Group...
            `;
            submitBtn.disabled = true;

            const formData = new FormData();
            formData.append('name', document.getElementById('groupName').value.trim());
            formData.append('description', document.getElementById('groupDescription').value.trim());

            const file = this.groupImageInput.files[0];
            if (file) formData.append('image', file);

            const members = [...document.querySelectorAll('.member-checkbox:checked')].map(cb => cb.value);
            if (members.length === 0) {
                this.showError('Please select at least one admin member!');
                return;
            }

            members.forEach(id => formData.append('members[]', id));

            const response = await fetch('/admin/chat/create-group', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const data = await response.json();

            if (data.status) {
                this.showSuccess(data.message || 'Group created successfully!');
                this.closeNewGroupModal();
                
                // Refresh chat list
                if (window.sidebarManager) {
                    window.sidebarManager.refreshChatList();
                }
            } else {
                this.showError(data.message || 'Failed to create group');
            }
        } catch (error) {
            console.error('Group creation failed:', error);
            this.showError('Network error: Failed to create group');
        } finally {
            // Reset button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    showError(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 transform transition-all duration-300';
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas fa-exclamation-triangle"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    showSuccess(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 transform transition-all duration-300';
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
}
</script><div class="w-96 bg-white rounded-2xl shadow-lg flex flex-col border border-gray-200">
    <!-- Filter Tabs -->
    <div class="flex border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
        <button id="allChatsTab" class="flex-1 py-4 text-center font-semibold border-b-2 border-blue-500 text-blue-500 transition-all duration-300">
            <i class="fas fa-comments mr-2"></i>All Chats
        </button>
        <button id="groupsTab" class="flex-1 py-4 text-center font-semibold text-gray-500 hover:text-gray-700 transition-all duration-300">
            <i class="fas fa-users mr-2"></i>Groups
        </button>
        <button id="unreadTab" class="flex-1 py-4 text-center font-semibold text-gray-500 hover:text-gray-700 transition-all duration-300 relative">
            <i class="fas fa-bell mr-2"></i>Unread
            <span id="unreadBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden notification-badge">0</span>
        </button>
    </div>

    <!-- Search Bar -->
    <div class="p-4 border-b border-gray-200 bg-white">
        <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="chatSearch" placeholder="Search chats..." 
                   class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
        </div>
    </div>

    <!-- Chat Lists -->
    <div class="flex-1 overflow-y-auto">
        <div id="individualChats" class="chat-list p-2"></div>
        <div id="groupChats" class="chat-list p-2 hidden"></div>
        <div id="unreadChats" class="chat-list p-2 hidden"></div>
    </div>

    <!-- Online Status -->
    <div class="p-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
        <div class="flex items-center justify-between text-sm">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-gray-600">Online</span>
            </div>
            <span class="text-gray-500" id="onlineCount">0 admins online</span>
        </div>
    </div>
</div>

<script>
class SidebarManager {
    constructor() {
        this.sidebar = {
            private: document.getElementById("individualChats"),
            group: document.getElementById("groupChats"),
            unread: document.getElementById("unreadChats")
        };

        this.tabs = {
            allChatsTab: "private",
            groupsTab: "group", 
            unreadTab: "unread"
        };

        this.currentFilter = "private";
        this.allChats = [];

        this.init();
    }

    init() {
        this.initTabs();
        this.loadChats("private");
        this.setupSearch();
        this.setupPusher();
        console.log('[SidebarManager] ✅ Initialized');
    }

    initTabs() {
        Object.keys(this.tabs).forEach(tabId => {
            const tab = document.getElementById(tabId);
            if (!tab) return;
            
            tab.addEventListener("click", () => {
                this.loadChats(this.tabs[tabId]);
                this.updateTabStyles(tabId);
            });
        });
    }

    updateTabStyles(activeId) {
        Object.keys(this.tabs).forEach(id => {
            const el = document.getElementById(id);
            el.classList.remove("border-blue-500", "text-blue-500");
            el.classList.add("text-gray-500");
        });
        
        const active = document.getElementById(activeId);
        active.classList.remove("text-gray-500");
        active.classList.add("border-blue-500", "text-blue-500");
    }

    setupSearch() {
        const searchInput = document.getElementById('chatSearch');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.filterChats(e.target.value);
            });
        }
    }

    setupPusher() {
        if (!window.pusherInstance) return;

        // Subscribe to presence channel for online status
        const presenceChannel = window.pusherInstance.subscribe('presence-admin-chat');
        
        presenceChannel.bind('pusher:subscription_succeeded', (members) => {
            this.updateOnlineCount(members.count);
        });

        presenceChannel.bind('pusher:member_added', (member) => {
            this.updateOnlineCount(document.querySelectorAll('.member').length + 1);
        });

        presenceChannel.bind('pusher:member_removed', (member) => {
            this.updateOnlineCount(Math.max(0, document.querySelectorAll('.member').length - 1));
        });
    }

    updateOnlineCount(count) {
        const onlineCountElement = document.getElementById('onlineCount');
        if (onlineCountElement) {
            onlineCountElement.textContent = `${count} admin${count !== 1 ? 's' : ''} online`;
        }
    }

    async loadChats(type) {
        this.currentFilter = type;
        
        try {
            console.log(`[Sidebar] Loading chats for type: ${type}`);
            const res = await fetch("/admin/chat/sidebar");
            const data = await res.json();
            
            if (data?.data) {
                this.allChats = data.data[type] || [];
                this.renderChatList(this.allChats, type);
                this.updateUnreadBadge();
            } else {
                console.warn(`[Sidebar] No data found for type: ${type}`);
                this.renderChatList([], type);
            }
        } catch (err) {
            console.error("[Sidebar] Failed to load chats:", err);
            this.renderChatList([], type);
        }
    }

    renderChatList(list, type) {
        console.log(`[Sidebar] Rendering ${list.length} chats for type: ${type}`);

        // Hide all sidebar sections
        Object.values(this.sidebar).forEach(div => div.classList.add("hidden"));
        
        const target = this.sidebar[type];
        if (!target) {
            console.error(`[Sidebar] No target found for type: ${type}`);
            return;
        }

        target.innerHTML = "";

        if (!list.length) {
            target.innerHTML = this.getEmptyState(type);
            target.classList.remove("hidden");
            return;
        }

        list.forEach(chat => {
            const item = this.createChatListItem(chat, type);
            target.appendChild(item);
        });

        target.classList.remove("hidden");
    }

    getEmptyState(type) {
        const messages = {
            private: {
                icon: 'fas fa-user-friends',
                title: 'No personal chats',
                description: 'Start a conversation with another admin'
            },
            group: {
                icon: 'fas fa-users',
                title: 'No group chats',
                description: 'Create a group to start chatting'
            },
            unread: {
                icon: 'fas fa-bell-slash',
                title: 'No unread messages',
                description: 'You\'re all caught up!'
            }
        };

        const msg = messages[type] || messages.private;

        return `
            <div class="text-center text-gray-400 py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="${msg.icon} text-2xl text-gray-400"></i>
                </div>
                <p class="font-medium text-gray-500">${msg.title}</p>
                <p class="text-sm text-gray-400 mt-1">${msg.description}</p>
            </div>
        `;
    }

    createChatListItem(chat, type) {
        const item = document.createElement("div");
        item.className = `chat-list-item p-3 rounded-xl mb-2 hover:bg-gray-50 cursor-pointer border border-transparent hover:border-gray-200 transition-all duration-300 ${
            window.chatManager?.activeChatId === chat.id ? 'active bg-blue-50 border-blue-200' : ''
        }`;

        const imgUrl = chat.profile_pic || '/images/default-avatar.png';
        const lastMessage = chat.last_message ? 
            `<p class="text-sm text-gray-600 truncate">${this.escapeHtml(chat.last_message)}</p>` : 
            '<p class="text-sm text-gray-400 italic">No messages yet</p>';

        const timeAgo = chat.last_message_time ? this.getTimeAgo(chat.last_message_time) : '';

        item.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="relative flex-shrink-0">
                    <img class="h-12 w-12 rounded-xl object-cover border-2 border-white shadow-sm" 
                         src="${imgUrl}" alt="${chat.name}" 
                         onerror="this.src='/images/default-avatar.png'">
                    ${chat.unread_count > 0 ? `
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center notification-badge">
                            ${chat.unread_count > 9 ? '9+' : chat.unread_count}
                        </span>
                    ` : ''}
                    ${chat.is_online ? `
                        <span class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                    ` : ''}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="font-semibold text-gray-800 truncate">${this.escapeHtml(chat.name)}</h4>
                        ${timeAgo ? `<span class="text-xs text-gray-500 flex-shrink-0 ml-2">${timeAgo}</span>` : ''}
                    </div>
                    ${lastMessage}
                </div>
            </div>
        `;

        item.addEventListener("click", () => {
            console.log(`[Sidebar] Opening chat:`, chat);
            
            // Remove active state from all items
            document.querySelectorAll('.chat-list-item').forEach(el => {
                el.classList.remove('active', 'bg-blue-50', 'border-blue-200');
            });
            
            // Add active state to clicked item
            item.classList.add('active', 'bg-blue-50', 'border-blue-200');
            
            if (chat.type === 'group') {
                window.chatManager.setActiveChat(chat.id, 'group', chat.name, chat.profile_pic);
            } else {
                window.chatManager.setActiveChat(chat.id, 'personal', chat.name, chat.profile_pic, chat.receiver_id || chat.id);
            }
        });

        return item;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    getTimeAgo(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) { // Less than 1 minute
            return 'now';
        } else if (diff < 3600000) { // Less than 1 hour
            return Math.floor(diff / 60000) + 'm';
        } else if (diff < 86400000) { // Less than 1 day
            return Math.floor(diff / 3600000) + 'h';
        } else if (diff < 604800000) { // Less than 1 week
            return Math.floor(diff / 86400000) + 'd';
        } else {
            return date.toLocaleDateString([], { month: 'short', day: 'numeric' });
        }
    }

    filterChats(searchTerm) {
        if (!searchTerm) {
            this.renderChatList(this.allChats, this.currentFilter);
            return;
        }

        const filteredChats = this.allChats.filter(chat => 
            chat.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (chat.last_message && chat.last_message.toLowerCase().includes(searchTerm.toLowerCase()))
        );
        
        this.renderChatList(filteredChats, this.currentFilter);
    }

    refreshChatList() {
        this.loadChats(this.currentFilter);
    }

    updateUnreadBadge() {
        const unreadBadge = document.getElementById('unreadBadge');
        if (!unreadBadge) return;

        const totalUnread = this.allChats.reduce((total, chat) => total + (chat.unread_count || 0), 0);
        
        if (totalUnread > 0) {
            unreadBadge.textContent = totalUnread > 9 ? '9+' : totalUnread;
            unreadBadge.classList.remove('hidden');
        } else {
            unreadBadge.classList.add('hidden');
        }
    }

    updateChatItem(chatId, updates) {
        // Find and update specific chat item
        const chatItems = document.querySelectorAll('.chat-list-item');
        chatItems.forEach(item => {
            const nameElement = item.querySelector('h4');
            if (nameElement && nameElement.textContent.includes(chatId)) {
                // Update the item with new data
                // This is a simplified implementation
                console.log('Updating chat item:', chatId, updates);
            }
        });
    }
}

class GroupManager {
    constructor() {
        this.modal = document.getElementById('newGroupModal');
        this.form = document.getElementById('newGroupForm');
        this.groupImageInput = document.getElementById('groupImage');
        this.preview = document.getElementById('groupImagePreview');
        this.selectAll = document.getElementById('selectAll');
        this.membersContainer = document.getElementById('membersContainer');

        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        this.groupImageInput?.addEventListener('change', (e) => this.handleImagePreview(e));
        this.selectAll?.addEventListener('change', (e) => this.toggleAllMembers(e));
        this.form?.addEventListener('submit', (e) => this.handleFormSubmit(e));
    }

    openNewGroupModal() {
        if (this.modal) {
            this.modal.classList.remove('hidden');
            this.loadAdminMembers();
        }
    }

    closeNewGroupModal() {
        if (this.modal) {
            this.modal.classList.add('hidden');
            this.form.reset();
            this.preview.src = '';
            this.preview.classList.add('hidden');
        }
    }

    handleImagePreview(event) {
        const file = event.target.files[0];
        if (file && this.preview) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.preview.src = e.target.result;
                this.preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    toggleAllMembers(event) {
        const checkboxes = document.querySelectorAll('.member-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = event.target.checked;
        });
    }

    async loadAdminMembers() {
        if (!this.membersContainer) return;

        this.membersContainer.innerHTML = `
            <div class="flex justify-center items-center py-8">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                <span class="ml-2 text-gray-500">Loading admins...</span>
            </div>
        `;

        try {
            const response = await fetch('/admin/chat/members');
            const data = await response.json();

            if (data && Array.isArray(data)) {
                this.renderAdminMembers(data);
            } else {
                this.membersContainer.innerHTML = `
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-users text-3xl mb-2"></i>
                        <p>No admins found</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Failed to load members:', error);
            this.membersContainer.innerHTML = `
                <div class="text-center py-8 text-red-400">
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Failed to load admins</p>
                </div>
            `;
        }
    }

    renderAdminMembers(admins) {
        this.membersContainer.innerHTML = '';

        admins.forEach(admin => {
            const memberElement = this.createMemberElement(admin);
            this.membersContainer.appendChild(memberElement);
        });
    }

    createMemberElement(admin) {
        const div = document.createElement('div');
        div.className = 'flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors';

        div.innerHTML = `
            <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 member-checkbox" value="${admin.id}">
            <label class="ml-3 flex items-center cursor-pointer flex-1">
                <img class="h-10 w-10 rounded-lg object-cover mr-3 border" 
                     src="${admin.profile_pic || '/images/default-avatar.png'}" 
                     alt="${admin.name}" 
                     onerror="this.src='/images/default-avatar.png'">
                <div>
                    <span class="font-medium text-gray-800 block">${admin.name}</span>
                    <span class="text-xs text-gray-500">${admin.role || 'Admin'}</span>
                </div>
            </label>
        `;

        return div;
    }

    async handleFormSubmit(event) {
        event.preventDefault();

        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        try {
            // Show loading state
            submitBtn.innerHTML = `
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                Creating Group...
            `;
            submitBtn.disabled = true;

            const formData = new FormData();
            formData.append('name', document.getElementById('groupName').value.trim());
            formData.append('description', document.getElementById('groupDescription').value.trim());

            const file = this.groupImageInput.files[0];
            if (file) formData.append('image', file);

            const members = [...document.querySelectorAll('.member-checkbox:checked')].map(cb => cb.value);
            if (members.length === 0) {
                this.showError('Please select at least one admin member!');
                return;
            }

            members.forEach(id => formData.append('members[]', id));

            const response = await fetch('/admin/chat/create-group', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const data = await response.json();

            if (data.status) {
                this.showSuccess(data.message || 'Group created successfully!');
                this.closeNewGroupModal();
                
                // Refresh chat list
                if (window.sidebarManager) {
                    window.sidebarManager.refreshChatList();
                }
            } else {
                this.showError(data.message || 'Failed to create group');
            }
        } catch (error) {
            console.error('Group creation failed:', error);
            this.showError('Network error: Failed to create group');
        } finally {
            // Reset button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    showError(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 transform transition-all duration-300';
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas fa-exclamation-triangle"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    showSuccess(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 transform transition-all duration-300';
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
}


</script>