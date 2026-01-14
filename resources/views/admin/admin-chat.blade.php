<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Group Chat System - Modern Dashboard</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Pusher -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .message-enter {
            animation: messageSlide 0.3s ease-out;
        }
        
        @keyframes messageSlide {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .typing-indicator {
            display: inline-flex;
            align-items: center;
        }
        
        .typing-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #6b7280;
            margin: 0 1px;
            animation: typingAnimation 1.4s infinite ease-in-out;
        }
        
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        
        @keyframes typingAnimation {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }
        
        .chat-container {
            height: calc(100vh - 120px);
        }
        
        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 transparent;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background-color: #cbd5e0;
            border-radius: 20px;
        }
        
        .message-bubble {
            max-width: 70%;
            word-wrap: break-word;
        }
        
        .sent-message {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 18px 18px 4px 18px;
        }
        
        .received-message {
            background: #f8fafc;
            color: #374151;
            border: 1px solid #e5e7eb;
            border-radius: 18px 18px 18px 4px;
        }
        
        .online-indicator {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            border: 2px solid white;
        }
        
        .notification-badge {
            background: #ef4444;
            color: white;
            border-radius: 50%;
            font-size: 0.75rem;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Voice Recording Styles */
        .recording-indicator {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        /* Media Message Styles */
        .media-message {
            max-width: 300px;
            border-radius: 12px;
            overflow: hidden;
        }

        .media-image {
            max-width: 100%;
            max-height: 300px;
            object-fit: contain;
        }

        .media-video {
            max-width: 100%;
            max-height: 300px;
        }

        .media-audio {
            width: 100%;
            height: 50px;
        }

        .file-info {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px;
            font-size: 0.75rem;
        }

        /* Voice Recording Modal */
        .voice-modal {
            background: rgba(0, 0, 0, 0.8);
        }

        .recording-wave {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
            height: 40px;
        }

        .wave-bar {
            width: 3px;
            background: #ef4444;
            border-radius: 3px;
            animation: wave 1.5s ease-in-out infinite;
        }

        @keyframes wave {
            0%, 100% { height: 5px; }
            50% { height: 20px; }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Main Layout -->
    <div class="flex h-screen">
        <!-- Sidebar Navigation -->
        <aside class="w-20 lg:w-64 bg-white shadow-xl z-50 transition-all duration-300">
            @include("admin.layouts.partials.sidebar")
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden ">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex justify-between items-center py-4 px-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-users text-white text-lg"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Group Chat</h1>
                                <p class="text-sm text-gray-500">Real-time group communication platform</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2 px-4 py-2 bg-green-50 rounded-lg border border-green-200">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium text-green-700" id="connectionStatus">Connected</span>
                        </div>
                        
                        <button onclick="window.groupManager.openNewGroupModal()" 
                                class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl flex items-center space-x-2 shadow-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                            <i class="fas fa-plus"></i>
                            <span class="hidden lg:inline">New Group</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Chat System -->
            <section class="flex-1 flex p-6 gap-6 chat-container">
                <!-- Chat Sidebar -->
                <div class="w-full lg:w-96 bg-white rounded-2xl shadow-lg flex flex-col border border-gray-200/60 glass-effect">
                    <!-- Filter Tabs -->
                    <div class="flex border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
                        <button id="allGroupsTab" class="flex-1 py-4 text-center font-semibold border-b-2 border-blue-500 text-blue-500 transition-all duration-300 hover:bg-gray-50">
                            <i class="fas fa-users mr-2"></i>
                            <span class="hidden lg:inline">All Groups</span>
                        </button>
                        <button id="unreadTab" class="flex-1 py-4 text-center font-semibold text-gray-500 hover:text-gray-700 transition-all duration-300 hover:bg-gray-50 relative">
                            <i class="fas fa-bell mr-2"></i>
                            <span class="hidden lg:inline">Unread</span>
                            <span id="unreadBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                        </button>
                    </div>

                    <!-- Search Bar -->
                    <div class="p-4 border-b border-gray-200 bg-white">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="chatSearch" placeholder="Search groups..." 
                                   class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50">
                        </div>
                    </div>

                    <!-- Group Lists -->
                    <div class="flex-1 overflow-y-auto sidebar">
                        <div id="allGroups" class="chat-list p-3 space-y-2"></div>
                        <div id="unreadGroups" class="chat-list p-3 space-y-2 hidden"></div>
                    </div>

                    <!-- Online Status -->
                    <div class="p-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center space-x-2">
                                <div class="online-indicator"></div>
                                <span class="text-gray-600">Online</span>
                            </div>
                            <span class="text-gray-500" id="onlineCount">0 members online</span>
                        </div>
                    </div>
                </div>

                <!-- Chat Area -->
                <div id="chatArea" class="flex-1 bg-white rounded-2xl shadow-lg flex flex-col hidden border border-gray-200/60 glass-effect">
                    <!-- Chat Header -->
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <img id="chatAvatar" class="h-14 w-14 rounded-2xl object-cover border-2 border-white shadow-lg" 
                                         src="/images/default-group.png" alt="Group Avatar">
                                    <div class="online-indicator absolute -bottom-1 -right-1"></div>
                                </div>
                                <div>
                                    <h3 id="chatName" class="font-bold text-gray-800 text-xl">Select a group</h3>
                                    <p id="chatStatusText" class="text-sm text-gray-500 flex items-center space-x-2">
                                        <span id="typingIndicator" class="hidden text-blue-600 font-medium">
                                            <span class="typing-indicator">
                                                <span class="typing-dot"></span>
                                                <span class="typing-dot"></span>
                                                <span class="typing-dot"></span>
                                            </span>
                                            typing...
                                        </span>
                                        <span id="memberCount" class="text-green-600">0 members</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="p-3 rounded-xl bg-gray-100 hover:bg-gray-200 transition-all duration-300 transform hover:scale-110 hover:shadow-lg" 
                                        onclick="window.chatManager.openChatInfoModal()">
                                    <i class="fas fa-info-circle text-gray-600"></i>
                                </button>
                                <button class="p-3 rounded-xl bg-gray-100 hover:bg-gray-200 transition-all duration-300 transform hover:scale-110 hover:shadow-lg" 
                                        onclick="window.chatManager.toggleChatMenu()">
                                    <i class="fas fa-ellipsis-v text-gray-600"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div id="messageContainer" class="flex-1 overflow-y-auto p-6 bg-gradient-to-b from-gray-50 to-white space-y-4">
                        <div class="text-center text-gray-400 py-12">
                            <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-comments text-3xl text-gray-400"></i>
                            </div>
                            <p class="text-lg font-medium text-gray-500">No messages yet</p>
                            <p class="text-sm text-gray-400 mt-1">Start a conversation by sending a message!</p>
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div class="p-6 border-t border-gray-200 bg-white rounded-b-2xl">
                        <div class="flex items-center space-x-3">
                            <!-- Media Upload Button -->
                            <input type="file" id="mediaUpload" class="hidden" accept="image/*,video/*,audio/*,.pdf,.doc,.docx">
                            <button onclick="document.getElementById('mediaUpload').click()" 
                                    class="p-3 rounded-xl bg-gray-100 hover:bg-gray-200 transition-all duration-300 transform hover:scale-110">
                                <i class="fas fa-paperclip text-gray-600"></i>
                            </button>
                            
                            <!-- Voice Message Button -->
                            <button id="voiceMessageBtn" 
                                    class="p-3 rounded-xl bg-gray-100 hover:bg-gray-200 transition-all duration-300 transform hover:scale-110">
                                <i class="fas fa-microphone text-gray-600"></i>
                            </button>
                            
                            <div class="flex-1 relative">
                                <input type="text" id="messageInput" 
                                       class="w-full border-2 border-gray-200 rounded-xl px-4 py-4 pr-12 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50" 
                                       placeholder="Type your message..." 
                                       onkeypress="if(event.key === 'Enter') window.chatManager.sendMessage()"
                                       oninput="window.chatManager.handleTyping()">
                                <button class="absolute right-3 top-1/2 transform -translate-y-1/2 p-2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="far fa-smile text-lg"></i>
                                </button>
                            </div>
                            <button class="p-4 rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 text-white hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-110 shadow-lg hover:shadow-xl"
                                    onclick="window.chatManager.sendMessage()">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- No Chat Selected Placeholder -->
                <div id="noChatSelected" class="flex-1 flex flex-col justify-center items-center text-gray-400 bg-white rounded-2xl shadow-lg border border-gray-200/60 glass-effect">
                    <div class="text-center max-w-md">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <i class="fas fa-users text-4xl text-blue-500"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-600 mb-4">Welcome to Group Chat</h3>
                        <p class="text-gray-500 leading-relaxed">
                            Select a group from the sidebar to start messaging with your team members. 
                            Create groups for team discussions and collaborate efficiently.
                        </p>
                        <button onclick="window.groupManager.openNewGroupModal()" 
                                class="mt-6 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-3 rounded-xl flex items-center space-x-2 shadow-lg transition-all duration-300 transform hover:scale-105 mx-auto">
                            <i class="fas fa-users"></i>
                            <span>Create Your First Group</span>
                        </button>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- New Group Modal -->
    <div id="newGroupModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 hover:scale-100">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-gray-800">Create New Group</h3>
                    <button class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors" 
                            onclick="window.groupManager.closeNewGroupModal()">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form id="newGroupForm">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Group Name</label>
                            <input type="text" id="groupName" 
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                                   placeholder="Enter group name" required>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Group Description</label>
                            <textarea id="groupDescription" 
                                      class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                                      placeholder="Enter group description" rows="3"></textarea>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Group Profile Image</label>
                            <div class="flex items-center space-x-4">
                                <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center border-2 border-dashed border-gray-300 overflow-hidden group cursor-pointer transition-all duration-300 hover:border-blue-400"
                                     onclick="document.getElementById('groupImage').click()">
                                    <img id="groupImagePreview" src="" class="hidden w-full h-full object-cover">
                                    <div class="text-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-camera text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-xs text-gray-500">Click to upload</p>
                                    </div>
                                </div>
                                <div>
                                    <input type="file" id="groupImage" class="hidden" accept="image/*">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Add Members</label>
                            <div class="border-2 border-gray-200 rounded-xl p-4 max-h-60 overflow-y-auto bg-gray-50/50">
                                <div class="flex items-center mb-4 p-2 bg-white rounded-lg">
                                    <input type="checkbox" id="selectAll" class="rounded text-blue-600 focus:ring-blue-500">
                                    <label for="selectAll" class="ml-2 text-sm font-medium text-gray-700">Select All Admins</label>
                                </div>
                                <div id="membersContainer" class="space-y-2">
                                    <div class="text-center py-8 text-gray-400">
                                        <i class="fas fa-users text-3xl mb-2"></i>
                                        <p>Loading admins...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between pt-6 mt-6 border-t border-gray-200">
                        <button type="button" 
                                class="px-8 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300 transform hover:scale-105 font-medium"
                                onclick="window.groupManager.closeNewGroupModal()">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 font-medium shadow-lg">
                            Create Group
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Chat Info Modal -->
    <div id="chatInfoModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6 relative max-h-[80vh] overflow-y-auto transform transition-all duration-300 scale-95 hover:scale-100">
            <button class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors" 
                    onclick="window.chatManager.closeChatInfoModal()">
                <i class="fas fa-times text-gray-600"></i>
            </button>
            <div id="chatInfoContent">
                <!-- Dynamic content -->
            </div>
        </div>
    </div>

    <!-- Group Edit Modal -->
    <div id="editGroupModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 hover:scale-100">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-gray-800">Edit Group</h3>
                    <button class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors" 
                            onclick="window.groupManager.closeEditGroupModal()">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form id="editGroupForm">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Group Name</label>
                            <input type="text" id="editGroupName" 
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                                   placeholder="Enter group name" required>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Group Description</label>
                            <textarea id="editGroupDescription" 
                                      class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                                      placeholder="Enter group description" rows="3"></textarea>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-semibold mb-2">Group Profile Image</label>
                            <div class="flex items-center space-x-4">
                                <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center border-2 border-dashed border-gray-300 overflow-hidden group cursor-pointer transition-all duration-300 hover:border-blue-400"
                                     onclick="document.getElementById('editGroupImage').click()">
                                    <img id="editGroupImagePreview" src="" class="hidden w-full h-full object-cover">
                                    <div class="text-center group-hover:scale-110 transition-transform duration-300">
                                        <i class="fas fa-camera text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-xs text-gray-500">Click to upload</p>
                                    </div>
                                </div>
                                <div>
                                    <input type="file" id="editGroupImage" class="hidden" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between pt-6 mt-6 border-t border-gray-200">
                        <button type="button" 
                                class="px-8 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300 transform hover:scale-105 font-medium"
                                onclick="window.groupManager.closeEditGroupModal()">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 font-medium shadow-lg">
                            Update Group
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Members Modal -->
    <div id="addMembersModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 hover:scale-100">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-gray-800">Add Members to Group</h3>
                    <button class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors" 
                            onclick="window.chatManager.closeAddMembersModal()">
                        <i class="fas fa-times text-gray-600"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="memberSearch" placeholder="Search admins..." 
                               class="w-full border-2 border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-gray-50">
                    </div>
                    
                    <div class="border-2 border-gray-200 rounded-xl p-4 max-h-60 overflow-y-auto bg-gray-50/50">
                        <div class="flex items-center mb-4 p-2 bg-white rounded-lg">
                            <input type="checkbox" id="selectAllMembers" class="rounded text-blue-600 focus:ring-blue-500">
                            <label for="selectAllMembers" class="ml-2 text-sm font-medium text-gray-700">Select All</label>
                        </div>
                        <div id="availableMembersContainer" class="space-y-2">
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-users text-3xl mb-2"></i>
                                <p>Loading available admins...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between pt-6 mt-6 border-t border-gray-200">
                    <button type="button" 
                            class="px-8 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300 transform hover:scale-105 font-medium"
                            onclick="window.chatManager.closeAddMembersModal()">
                        Cancel
                    </button>
                    <button type="button" 
                            class="px-8 py-3 bg-gradient-to-r from-green-500 to-blue-600 text-white rounded-xl hover:from-green-600 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 font-medium shadow-lg"
                            onclick="window.chatManager.addMembersToGroup()">
                        Add Selected Members
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Voice Recording Modal -->
    <div id="voiceRecordingModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden backdrop-blur-sm voice-modal">
        <div class="bg-white rounded-2xl shadow-2xl w-80 p-6 text-center">
            <div class="mb-4">
                <div class="recording-wave">
                    <div class="wave-bar" style="animation-delay: 0s"></div>
                    <div class="wave-bar" style="animation-delay: 0.1s"></div>
                    <div class="wave-bar" style="animation-delay: 0.2s"></div>
                    <div class="wave-bar" style="animation-delay: 0.3s"></div>
                    <div class="wave-bar" style="animation-delay: 0.4s"></div>
                </div>
            </div>
            <p class="text-red-500 font-semibold mb-2 recording-indicator">Recording...</p>
            <p class="text-gray-500 text-sm mb-4" id="recordingTimer">00:00</p>
            <button class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto shadow-lg hover:bg-red-600 transition-colors"
                    onclick="window.chatManager.stopVoiceRecording()">
                <i class="fas fa-stop text-white text-xl"></i>
            </button>
            <p class="text-xs text-gray-400 mt-3">Click stop to send voice message</p>
        </div>
    </div>

    <script>
        // Global variables
        window.currentAdminId = {{ auth('admin')->check() ? auth('admin')->id() : 'null' }};
        window.pusherInstance = null;
        window.activeChannels = new Map();

        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Initializing Modern Group Chat System...');
            
            // Initialize managers
            window.chatManager = new ChatManager();
            window.groupManager = new GroupManager();
            window.sidebarManager = new SidebarManager();
            
            // Initialize Pusher
            initializePusher();
            
            console.log('✅ Modern Group Chat System initialized successfully');
        });

        function initializePusher() {
            try {
                window.pusherInstance = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
                    cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
                    forceTLS: true,
                    authEndpoint: '/admin/chat/auth',
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }
                });

                // Connection status monitoring
                window.pusherInstance.connection.bind('state_change', function(states) {
                    const statusElement = document.getElementById('connectionStatus');
                    if (statusElement) {
                        if (states.current === 'connected') {
                            statusElement.textContent = 'Connected';
                            statusElement.className = 'text-sm font-medium text-green-700';
                            statusElement.previousElementSibling.className = 'w-2 h-2 bg-green-500 rounded-full animate-pulse';
                        } else {
                            statusElement.textContent = 'Connecting...';
                            statusElement.className = 'text-sm font-medium text-yellow-700';
                            statusElement.previousElementSibling.className = 'w-2 h-2 bg-yellow-500 rounded-full animate-pulse';
                        }
                    }
                });

                console.log('✅ Pusher initialized successfully');
            } catch (error) {
                console.error('❌ Pusher initialization failed:', error);
            }
        }

        // Sidebar Manager Class
        class SidebarManager {
            constructor() {
                this.sidebar = {
                    all: document.getElementById("allGroups"),
                    unread: document.getElementById("unreadGroups")
                };

                this.tabs = {
                    allGroupsTab: "all",
                    unreadTab: "unread"
                };

                this.currentFilter = "all";
                this.allGroups = [];

                this.init();
            }

            init() {
                this.initTabs();
                this.loadGroups("all");
                this.setupSearch();
                this.setupPusher();
                console.log('[SidebarManager] ✅ Initialized');
            }

            initTabs() {
                Object.keys(this.tabs).forEach(tabId => {
                    const tab = document.getElementById(tabId);
                    if (!tab) return;
                    
                    tab.addEventListener("click", () => {
                        this.loadGroups(this.tabs[tabId]);
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
                        this.filterGroups(e.target.value);
                    });
                }
            }

            setupPusher() {
                if (!window.pusherInstance) return;

                // Subscribe to presence channel
                const presenceChannel = window.pusherInstance.subscribe('presence-group-chat');
                
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
                    onlineCountElement.textContent = `${count} member${count !== 1 ? 's' : ''} online`;
                }
            }

            async loadGroups(type) {
                this.currentFilter = type;
                
                try {
                    console.log(`[Sidebar] Loading groups for type: ${type}`);
                    const res = await fetch("/admin/chat/sidebar");
                    const data = await res.json();
                    
                    if (data?.status && data.data) {
                        this.allGroups = data.data[type] || [];
                        this.renderGroupList(this.allGroups, type);
                        this.updateUnreadBadge();
                    } else {
                        console.warn(`[Sidebar] No data found for type: ${type}`);
                        this.renderGroupList([], type);
                    }
                } catch (err) {
                    console.error("[Sidebar] Failed to load groups:", err);
                    this.renderGroupList([], type);
                }
            }

            renderGroupList(list, type) {
                console.log(`[Sidebar] Rendering ${list.length} groups for type: ${type}`);

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

                list.forEach(group => {
                    const item = this.createGroupListItem(group, type);
                    target.appendChild(item);
                });

                target.classList.remove("hidden");
            }

            getEmptyState(type) {
                const messages = {
                    all: {
                        icon: 'fas fa-users',
                        title: 'No groups',
                        description: 'Create a group to start chatting'
                    },
                    unread: {
                        icon: 'fas fa-bell-slash',
                        title: 'No unread messages',
                        description: 'You\'re all caught up!'
                    }
                };

                const msg = messages[type] || messages.all;

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

            createGroupListItem(group, type) {
                const item = document.createElement("div");
                item.className = `group-list-item p-4 rounded-xl mb-2 hover:bg-gray-50 cursor-pointer border border-transparent hover:border-gray-200 transition-all duration-300 ${
                    window.chatManager?.activeChatId === group.id ? 'active bg-blue-50 border-blue-200 shadow-sm' : ''
                }`;

                const imgUrl = group.profile_pic || '/images/default-group.png';
                const lastMessage = group.last_message ? 
                    `<p class="text-sm text-gray-600 truncate">${this.escapeHtml(group.last_message)}</p>` : 
                    '<p class="text-sm text-gray-400 italic">No messages yet</p>';

                const timeAgo = group.last_message_time ? this.getTimeAgo(group.last_message_time) : '';

                item.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <div class="relative flex-shrink-0">
                            <img class="h-12 w-12 rounded-xl object-cover border-2 border-white shadow-sm" 
                                 src="${imgUrl}" alt="${group.name}" 
                                 onerror="this.src='/images/default-group.png'">
                            ${group.unread_count > 0 ? `
                                <span class="notification-badge absolute -top-1 -right-1">
                                    ${group.unread_count > 9 ? '9+' : group.unread_count}
                                </span>
                            ` : ''}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-semibold text-gray-800 truncate">${this.escapeHtml(group.name)}</h4>
                                ${timeAgo ? `<span class="text-xs text-gray-500 flex-shrink-0 ml-2">${timeAgo}</span>` : ''}
                            </div>
                            ${lastMessage}
                            <div class="flex items-center mt-1 text-xs text-gray-500">
                                <i class="fas fa-users mr-1"></i>
                                <span>${group.member_count || 0} members</span>
                            </div>
                        </div>
                    </div>
                `;

                item.addEventListener("click", () => {
                    console.log(`[Sidebar] Opening group:`, group);
                    
                    // Remove active state from all items
                    document.querySelectorAll('.group-list-item').forEach(el => {
                        el.classList.remove('active', 'bg-blue-50', 'border-blue-200', 'shadow-sm');
                    });
                    
                    // Add active state to clicked item
                    item.classList.add('active', 'bg-blue-50', 'border-blue-200', 'shadow-sm');
                    
                    window.chatManager.setActiveChat(group.id, 'group', group.name, group.profile_pic);
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

            filterGroups(searchTerm) {
                if (!searchTerm) {
                    this.renderGroupList(this.allGroups, this.currentFilter);
                    return;
                }

                const filteredGroups = this.allGroups.filter(group => 
                    group.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                    (group.last_message && group.last_message.toLowerCase().includes(searchTerm.toLowerCase()))
                );
                
                this.renderGroupList(filteredGroups, this.currentFilter);
            }

            refreshGroupList() {
                this.loadGroups(this.currentFilter);
            }

            updateUnreadBadge() {
                const unreadBadge = document.getElementById('unreadBadge');
                if (!unreadBadge) return;

                const totalUnread = this.allGroups.reduce((total, group) => total + (group.unread_count || 0), 0);
                
                if (totalUnread > 0) {
                    unreadBadge.textContent = totalUnread > 9 ? '9+' : totalUnread;
                    unreadBadge.classList.remove('hidden');
                } else {
                    unreadBadge.classList.add('hidden');
                }
            }
        }

        // Group Manager Class
        class GroupManager {
            constructor() {
                this.modal = document.getElementById('newGroupModal');
                this.editModal = document.getElementById('editGroupModal');
                this.form = document.getElementById('newGroupForm');
                this.editForm = document.getElementById('editGroupForm');
                this.groupImageInput = document.getElementById('groupImage');
                this.editGroupImageInput = document.getElementById('editGroupImage');
                this.preview = document.getElementById('groupImagePreview');
                this.editPreview = document.getElementById('editGroupImagePreview');
                this.selectAll = document.getElementById('selectAll');
                this.membersContainer = document.getElementById('membersContainer');

                this.init();
            }

            init() {
                this.setupEventListeners();
            }

            setupEventListeners() {
                this.groupImageInput?.addEventListener('change', (e) => this.handleImagePreview(e));
                this.editGroupImageInput?.addEventListener('change', (e) => this.handleEditImagePreview(e));
                this.selectAll?.addEventListener('change', (e) => this.toggleAllMembers(e));
                this.form?.addEventListener('submit', (e) => this.handleFormSubmit(e));
                this.editForm?.addEventListener('submit', (e) => this.handleEditFormSubmit(e));
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
                    if (this.preview) {
                        this.preview.src = '';
                        this.preview.classList.add('hidden');
                    }
                }
            }

            openEditGroupModal(groupId) {
                if (this.editModal) {
                    this.editModal.classList.remove('hidden');
                    this.loadGroupData(groupId);
                }
            }

            closeEditGroupModal() {
                if (this.editModal) {
                    this.editModal.classList.add('hidden');
                    this.editForm.reset();
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

            handleEditImagePreview(event) {
                const file = event.target.files[0];
                if (file && this.editPreview) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.editPreview.src = e.target.result;
                        this.editPreview.classList.remove('hidden');
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

            async loadGroupData(groupId) {
                try {
                    const response = await fetch(`/admin/chat/group/${groupId}`);
                    const data = await response.json();

                    if (data.status) {
                        document.getElementById('editGroupName').value = data.group.name;
                        document.getElementById('editGroupDescription').value = data.group.description || '';
                        
                        if (data.group.image) {
                            this.editPreview.src = data.group.image;
                            this.editPreview.classList.remove('hidden');
                        }
                    }
                } catch (error) {
                    console.error('Failed to load group data:', error);
                    this.showError('Failed to load group data');
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
                             src="${admin.profile_pic}" 
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
                        
                        // Refresh group list and open the new group
                        if (window.sidebarManager) {
                            window.sidebarManager.refreshGroupList();
                        }
                        if (window.chatManager && data.group) {
                            // Auto-open the newly created group
                            setTimeout(() => {
                                window.chatManager.setActiveChat(data.group.id, 'group', data.group.name, data.group.profile_pic);
                            }, 500);
                        }
                    } else {
                        this.showError(data.error || 'Failed to create group');
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

            async handleEditFormSubmit(event) {
                event.preventDefault();

                const submitBtn = event.target.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                try {
                    // Show loading state
                    submitBtn.innerHTML = `
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                        Updating Group...
                    `;
                    submitBtn.disabled = true;

                    const formData = new FormData();
                    formData.append('name', document.getElementById('editGroupName').value.trim());
                    formData.append('description', document.getElementById('editGroupDescription').value.trim());

                    const file = this.editGroupImageInput.files[0];
                    if (file) formData.append('image', file);

                    const response = await fetch(`/admin/chat/group/${window.chatManager.activeChatId}/update`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.status) {
                        this.showSuccess(data.message || 'Group updated successfully!');
                        this.closeEditGroupModal();
                        
                        // Refresh group list and current chat
                        if (window.sidebarManager) {
                            window.sidebarManager.refreshGroupList();
                        }
                        if (window.chatManager) {
                            window.chatManager.updateGroupInfo(data.group);
                        }
                    } else {
                        this.showError(data.error || 'Failed to update group');
                    }
                } catch (error) {
                    console.error('Group update failed:', error);
                    this.showError('Network error: Failed to update group');
                } finally {
                    // Reset button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }

            showError(message) {
                this.showNotification(message, 'error');
            }

            showSuccess(message) {
                this.showNotification(message, 'success');
            }

            showNotification(message, type = 'info') {
                const colors = {
                    error: 'bg-red-500',
                    success: 'bg-green-500',
                    info: 'bg-blue-500'
                };

                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-xl shadow-lg z-50 transform transition-all duration-300`;
                notification.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'}"></i>
                        <span>${message}</span>
                    </div>
                `;
                
                document.body.appendChild(notification);

                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 3000);
            }
        }

        // Chat Manager Class
        class ChatManager {
            constructor() {
                this.activeChatId = null;
                this.activeChatType = null;
                this.channel = null;
                this.typingTimer = null;
                this.isTyping = false;
                this.mediaRecorder = null;
                this.audioChunks = [];
                this.recordingTimer = null;
                this.recordingStartTime = null;
                
                this.elements = {
                    chatArea: document.getElementById('chatArea'),
                    noChat: document.getElementById('noChatSelected'),
                    messageContainer: document.getElementById('messageContainer'),
                    messageInput: document.getElementById('messageInput'),
                    chatName: document.getElementById('chatName'),
                    chatAvatar: document.getElementById('chatAvatar'),
                    chatStatusText: document.getElementById('chatStatusText'),
                    typingIndicator: document.getElementById('typingIndicator'),
                    memberCount: document.getElementById('memberCount')
                };

                this.setupEventListeners();
                this.setupMediaUpload();
                this.setupVoiceRecording();
                console.log('[ChatManager] ✅ Initialized');
                this.subscribedChannels = new Set();

            }

            setupEventListeners() {
                // Enter key to send message
                this.elements.messageInput?.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        this.sendMessage();
                    }
                });

                // Focus management
                this.elements.messageInput?.addEventListener('focus', () => {
                    this.markMessagesAsRead();
                });
            }

            setupMediaUpload() {
                const mediaUpload = document.getElementById('mediaUpload');
                if (mediaUpload) {
                    mediaUpload.addEventListener('change', (e) => {
                        this.handleMediaUpload(e);
                    });
                }
            }

            setupVoiceRecording() {
                const voiceBtn = document.getElementById('voiceMessageBtn');
                if (voiceBtn) {
                    voiceBtn.addEventListener('click', () => {
                        this.startVoiceRecording();
                    });
                }
            }

            setActiveChat(chatId, type, chatName, chatAvatar) {
                console.group('[ChatManager] setActiveChat()');
                console.log('👉 Setting active chat:', { chatId, type });

                // Clear current messages immediately
                this.elements.messageContainer.innerHTML = '';
                this.showLoadingState();

                // Unsubscribe from previous channel
                this.unsubscribeFromChannel();

                this.activeChatId = chatId;
                this.activeChatType = type;

                // Update UI
                this.elements.noChat.classList.add("hidden");
                this.elements.chatArea.classList.remove("hidden");
                this.elements.chatName.textContent = chatName;
                this.elements.chatAvatar.src = chatAvatar || '/images/default-group.png';
                this.elements.typingIndicator.classList.add('hidden');

                if (chatId) {
                    console.log('Group ID exists. Setting up real-time channel and loading history.');
                    
                    // Setup real-time channel
                    this.setupRealtimeChannel();

                    // Load message history
                    this.loadMessages(chatId);

                    // Mark messages as read
                    this.markMessagesAsRead();

                    // Load group info
                    this.loadGroupInfo(chatId);
                }

                console.groupEnd();
            }

            async loadGroupInfo(groupId) {
                try {
                    const response = await fetch(`/admin/chat/group/${groupId}/info`);
                    const data = await response.json();

                    if (data.status) {
                        this.elements.memberCount.textContent = `${data.member_count} members`;
                    }
                } catch (error) {
                    console.error('Failed to load group info:', error);
                }
            }

            updateGroupInfo(groupData) {
                if (groupData.name) {
                    this.elements.chatName.textContent = groupData.name;
                }
                if (groupData.image || groupData.profile_pic) {
                    this.elements.chatAvatar.src = groupData.image || groupData.profile_pic;
                }
            }

            unsubscribeFromChannel() {
                if (this.channel && window.pusherInstance) {
                    console.log('🔌 Unsubscribing from channel:', this.channel.name);
                    window.pusherInstance.unsubscribe(this.channel.name);
                    this.channel = null;
                }
            }

            setupRealtimeChannel() {
    // 1. Safety Checks
    if (!this.activeChatId) {
        console.warn('⚠️ Cannot subscribe: activeChatId is null.');
        return;
    }

    // 2. Define Channel Name
    // Must match routes/channels.php -> 'chat.room.{id}'
    const channelName = `chat.room.${this.activeChatId}`;

    // 3. Leave Previous Channel (Cleanup)
    if (this.currentChannel && this.currentChannel !== channelName) {
        console.log(`🔌 Leaving channel: ${this.currentChannel}`);
        window.Echo.leave(this.currentChannel);
    }

    if (this.currentChannel === channelName) {
        console.log('ℹ️ Already connected to this channel');
        return;
    }

    this.currentChannel = channelName;

    console.log(`🔌 Connecting to: ${channelName}`);
    
    window.Echo.private(channelName)

        .listen('.message.sent', (e) => {
            console.log('⚡ Real-time message received:', e);
            this.handleIncomingMessage(e);
        })

        .listen('.message.read', (e) => {
            console.log('👁️ Messages read:', e);
            this.updateReadStatus(e.ids, e.reader_id);
        })

        .listen('.member.added', (e) => {
            console.log('👤 New member:', e);
            this.addSystemMessage(`${e.user.name} joined the group`);
        });
}
handleIncomingMessage(message) {
    // 1. Scope Check: Is this message for the chat I am currently looking at?
    // If I'm looking at Chat A, and a message comes for Chat B, ignore it here.
    if (parseInt(message.chat_room_id) !== parseInt(this.activeChatId)) {
        console.log('🚫 Background message for another room. Updating sidebar only.');
        if (window.sidebarManager) {
            window.sidebarManager.refreshGroupList(); // Update the unread badge on sidebar
        }
        // Play sound for background notification
        this.notificationSound?.play().catch(e => {});
        return;
    }

    const isFromMe = parseInt(message.sender_id) === parseInt(window.currentAdminId);
    
    if (isFromMe) {
        console.log('✅ Acknowledging my own message arrival');
        return; 
    }

    console.log('📩 Rendering new message from partner');
    this.addMessageToChat(message, false, true); 

    this.notificationSound?.play().catch(e => {});


    this.markMessagesAsRead();
}
            async loadMessages(chatId) {
                console.group(`[ChatManager] Loading messages for group: ${chatId}`);
                
                this.showLoadingState();

                try {
                    const endpoint = `/admin/chat/messages/${chatId}?type=group`;
                    const res = await fetch(endpoint);
                    const data = await res.json();

                    console.log('📨 Messages response:', data);

                    this.elements.messageContainer.innerHTML = '';

                    if (data.status && Array.isArray(data.messages) && data.messages.length > 0) {
                        console.log(`✅ Loaded ${data.messages.length} messages`);
                        data.messages.forEach(msg => {
                            const isMine = parseInt(msg.sender_id) === parseInt(window.currentAdminId);
                            this.addMessageToChat(msg, isMine, false);
                        });
                        this.showEmptyState(false);
                    } else {
                        this.showEmptyState(true);
                    }

                    this.scrollToBottom();
                } catch (error) {
                    console.error('❌ Error loading messages:', error);
                    this.showErrorState('Failed to load messages');
                }

                console.groupEnd();
            }

            showLoadingState() {
                this.elements.messageContainer.innerHTML = `
                    <div class="flex justify-center items-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                        <span class="ml-2 text-gray-500">Loading messages...</span>
                    </div>
                `;
            }

            showEmptyState(show) {
                if (show) {
                    this.elements.messageContainer.innerHTML = `
                        <div class="text-center text-gray-400 py-12">
                            <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-comments text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-lg font-medium text-gray-500">No messages yet</p>
                            <p class="text-sm text-gray-400 mt-1">Start a conversation by sending a message!</p>
                        </div>
                    `;
                }
            }

            showErrorState(message) {
                this.elements.messageContainer.innerHTML = `
                    <div class="text-center text-red-500 py-8">
                        <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                        <p>${message}</p>
                    </div>
                `;
            }

            async sendMessage() {
    const messageText = this.elements.messageInput.value.trim();
    if (!messageText || !this.activeChatId) {
        console.warn('⚠️ Cannot send empty message or no active group');
        return;
    }

    console.group('[ChatManager] Sending message');
    console.log('📝 Message:', messageText, 'Type:', this.activeChatType);

    // Stop typing indicator
    this.stopTyping();

    const payload = {
        message: messageText,
        type: 'group',
        chat_room_id: this.activeChatId,
    };

    console.log('📦 Payload:', payload);

    try {
        // Create optimistic message with unique ID
        const tempId = 'temp-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        
        const optimisticMessage = {
            id: tempId,
            message: messageText,
            sender_id: window.currentAdminId,
            sender_name: 'You',
            sender_pic: '/images/default-avatar.png',
            created_at: new Date().toISOString(),
            is_optimistic: true,
            type: 'text',
            is_read: false
        };

        this.addMessageToChat(optimisticMessage, true);
        this.elements.messageInput.value = '';
        this.scrollToBottom();

        const res = await fetch('/admin/chat/send', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const data = await res.json();
        console.log('📨 Server response:', data);

        if (!data.status) {
            this.showError(data.error || 'Failed to send message');
            this.removeOptimisticMessage(tempId);
        } else {
            console.log('✅ Message sent successfully, replacing optimistic message');
            // The real message will come via Pusher and replace the optimistic one
        }

    } catch (error) {
        console.error('❌ Error sending message:', error);
        this.showError('Failed to send message');
        this.removeOptimisticMessage(tempId);
    }

    console.groupEnd();
}
            async handleMediaUpload(event) {
                const file = event.target.files[0];
                if (!file || !this.activeChatId) return;

                try {
                    // Create optimistic message for media
                    const tempId = 'temp-media-' + Date.now();
                    const messageType = this.getMediaType(file.type);
                    
                    const optimisticMessage = {
                        id: tempId,
                        message: URL.createObjectURL(file), // Use blob URL for instant preview
                        sender_id: window.currentAdminId,
                        sender_name: 'You',
                        sender_pic: '/images/default-avatar.png',
                        created_at: new Date().toISOString(),
                        is_optimistic: true,
                        type: messageType,
                        file_name: file.name,
                        file_size: file.size,
                        is_read: false
                    };

                    // Add optimistic message to chat
                    this.addMessageToChat(optimisticMessage, true);

                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('chat_room_id', this.activeChatId);
                    formData.append('type', 'group');

                    const response = await fetch('/admin/chat/send-media', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.status) {
                        // Replace optimistic message with real one
                        this.replaceOptimisticMessage(tempId, data.message_data);
                        this.showSuccess('Media sent successfully');
                    } else {
                        this.removeOptimisticMessage(tempId);
                        this.showError(data.error || 'Failed to send media');
                    }
                } catch (error) {
                    console.error('Media upload failed:', error);
                    this.removeOptimisticMessage('temp-media-' + Date.now());
                    this.showError('Failed to upload media');
                }

                // Reset file input
                event.target.value = '';
            }

            getMediaType(mimeType) {
                if (mimeType.startsWith('image/')) return 'image';
                if (mimeType.startsWith('video/')) return 'video';
                if (mimeType.startsWith('audio/')) return 'audio';
                return 'file';
            }

            addMessageToChat(message, isMine = false, animate = true) {
                // Clear empty state if present
                if (this.elements.messageContainer.innerHTML.includes('No messages yet')) {
                    this.elements.messageContainer.innerHTML = '';
                }

                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${animate ? 'message-enter' : ''} flex ${isMine ? 'justify-end' : 'justify-start'} mb-4`;
                messageDiv.id = message.is_optimistic ? message.id : `message-${message.id}`;
                
                if (message.is_optimistic) {
                    messageDiv.classList.add('opacity-70');
                }

                const time = message.created_at ? this.formatMessageTime(message.created_at) : 'Just now';
                const readStatus = isMine ? (message.is_read ? '✓✓' : '✓') : '';

                let messageContent = '';
                
                if (message.type === 'image') {
                    messageContent = `
                        <div class="media-message">
                            <img src="${message.message}" class="media-image cursor-pointer" alt="Shared image" 
                                 onclick="window.chatManager.openMediaViewer('${message.message}')">
                            ${message.file_name ? `
                                <div class="file-info">
                                    <div class="flex justify-between items-center">
                                        <span class="truncate">${message.file_name}</span>
                                        ${message.file_size ? `<span>${this.formatFileSize(message.file_size)}</span>` : ''}
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    `;
                } else if (message.type === 'video') {
                    messageContent = `
                        <div class="media-message">
                            <video controls class="media-video">
                                <source src="${message.message}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            ${message.file_name ? `
                                <div class="file-info">
                                    <div class="flex justify-between items-center">
                                        <span class="truncate">${message.file_name}</span>
                                        ${message.file_size ? `<span>${this.formatFileSize(message.file_size)}</span>` : ''}
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    `;
                } else if (message.type === 'audio') {
                    messageContent = `
                        <div class="media-message">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-music text-blue-500 text-xl"></i>
                                    <div class="flex-1">
                                        <audio controls class="w-full">
                                            <source src="${message.message}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                </div>
                                ${message.file_name ? `
                                    <div class="mt-2 text-xs text-gray-500">
                                        <div class="flex justify-between">
                                            <span class="truncate">${message.file_name}</span>
                                            ${message.file_size ? `<span>${this.formatFileSize(message.file_size)}</span>` : ''}
                                        </div>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                } else if (message.type === 'file') {
                    messageContent = `
                        <div class="media-message bg-gray-100 p-4 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-file text-blue-500 text-2xl"></i>
                                <div class="flex-1">
                                    <p class="font-medium text-sm truncate">${message.file_name}</p>
                                    ${message.file_size ? `<p class="text-xs text-gray-500">${this.formatFileSize(message.file_size)}</p>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    messageContent = `<div class="text-sm whitespace-pre-wrap break-words">${this.escapeHtml(message.message)}</div>`;
                }

                messageDiv.innerHTML = `
                    <div class="message-bubble ${isMine ? 'sent-message' : 'received-message'} p-4 shadow-sm">
                        ${!isMine ? `
                            <div class="font-semibold text-xs text-gray-600 mb-1">${message.sender_name}</div>
                        ` : ''}
                        
                        ${messageContent}
                        
                        <div class="flex items-center justify-between mt-2 text-xs ${isMine ? 'text-blue-100' : 'text-gray-500'}">
                            <span>${time}</span>
                            ${isMine ? `<span class="ml-2">${readStatus}</span>` : ''}
                        </div>
                    </div>
                `;

                this.elements.messageContainer.appendChild(messageDiv);
                this.scrollToBottom();
            }

            openMediaViewer(mediaUrl) {
                // Create a simple modal for media viewing
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black/90 flex items-center justify-center z-50 backdrop-blur-sm';
                modal.innerHTML = `
                    <div class="relative max-w-4xl max-h-full">
                        <button class="absolute -top-12 right-0 text-white text-2xl hover:text-gray-300 transition-colors" 
                                onclick="this.parentElement.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                        <img src="${mediaUrl}" class="max-w-full max-h-screen object-contain" alt="Media preview">
                    </div>
                `;
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.remove();
                    }
                });
                document.body.appendChild(modal);
            }


            updateReadStatus(messageIds, readerId) {
    if (parseInt(readerId) === parseInt(window.currentAdminId)) return;

    messageIds.forEach(id => {
        const messageEl = document.getElementById(`message-${id}`);
        if (messageEl) {

            const statusSpan = messageEl.querySelector('.sent-message .text-xs span:last-child');
            
            if (statusSpan && statusSpan.innerText.trim() === '✓') {
                statusSpan.innerText = '✓✓'; // Change to double tick
                statusSpan.classList.add('text-blue-400'); // Make it blue
                statusSpan.classList.remove('text-gray-500');
            }
        }
    });
}
            replaceOptimisticMessage(tempId, realMessage) {
    console.log(`🔄 Replacing optimistic message ${tempId} with real message ${realMessage.id}`);
    
    const tempElement = document.getElementById(tempId);
    if (tempElement) {
        tempElement.remove();
    }
    
    const existingMessage = document.getElementById(`message-${realMessage.id}`);
    if (!existingMessage) {
        this.addMessageToChat(realMessage, true);
    } else {
        console.log('ℹ️ Real message already exists, skipping duplicate');
    }
}

            removeOptimisticMessage(messageId) {
                const optimisticMessage = document.getElementById(messageId);
                if (optimisticMessage) {
                    optimisticMessage.remove();
                }
            }

            formatMessageTime(timestamp) {
                const date = new Date(timestamp);
                const now = new Date();
                const diff = now - date;
                
                if (diff < 60000) { // Less than 1 minute
                    return 'Just now';
                } else if (diff < 3600000) { // Less than 1 hour
                    return Math.floor(diff / 60000) + 'm ago';
                } else if (diff < 86400000) { // Less than 1 day
                    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                } else {
                    return date.toLocaleDateString([], { month: 'short', day: 'numeric' });
                }
            }

            formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            handleTyping() {
                if (!this.isTyping) {
                    this.isTyping = true;
                    this.broadcastTyping(true);
                }

                clearTimeout(this.typingTimer);
                this.typingTimer = setTimeout(() => {
                    this.stopTyping();
                }, 1000);
            }

            stopTyping() {
                if (this.isTyping) {
                    this.isTyping = false;
                    this.broadcastTyping(false);
                }
                clearTimeout(this.typingTimer);
            }

            broadcastTyping(isTyping) {
                if (!this.channel) return;

                const event = isTyping ? 'client-user-typing' : 'client-user-stop-typing';
                this.channel.trigger(event, {
                    user_id: window.currentAdminId,
                    user_name: 'You',
                    chat_id: this.activeChatId
                });
            }

            handleUserTyping(data) {
                if (parseInt(data.user_id) === parseInt(window.currentAdminId)) return;

                this.elements.typingIndicator.classList.remove('hidden');
                this.elements.memberCount.classList.add('hidden');
            }

            handleUserStopTyping(data) {
                if (parseInt(data.user_id) === parseInt(window.currentAdminId)) return;

                this.elements.typingIndicator.classList.add('hidden');
                this.elements.memberCount.classList.remove('hidden');
            }

            scrollToBottom() {
                setTimeout(() => {
                    this.elements.messageContainer.scrollTop = this.elements.messageContainer.scrollHeight;
                }, 100);
            }

            async markMessagesAsRead() {
                if (!this.activeChatId) return;

                try {
                    await fetch(`/admin/chat/mark-read/${this.activeChatId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    // Update UI
                    document.querySelectorAll('.message .text-blue-200').forEach(el => {
                        el.textContent = '✓✓';
                        el.classList.remove('text-blue-200');
                        el.classList.add('text-blue-300');
                    });

                } catch (error) {
                    console.error('Error marking messages as read:', error);
                }
            }

            async markMessageAsRead(messageId) {
                try {
                    await fetch(`/admin/chat/message/${messageId}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                } catch (error) {
                    console.error('Error marking message as read:', error);
                }
            }

            updateMessageReadStatus(data) {
                const messageElement = document.getElementById(`message-${data.message_id}`);
                if (messageElement) {
                    const readIndicator = messageElement.querySelector('.text-blue-200');
                    if (readIndicator) {
                        readIndicator.textContent = '✓✓';
                        readIndicator.classList.remove('text-blue-200');
                        readIndicator.classList.add('text-blue-300');
                    }
                }
            }

            // Voice Recording Methods
            async startVoiceRecording() {
                try {
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        this.showError('Voice recording is not supported in your browser');
                        return;
                    }

                    const stream = await navigator.mediaDevices.getUserMedia({ 
                        audio: {
                            echoCancellation: true,
                            noiseSuppression: true,
                            sampleRate: 44100
                        } 
                    });
                    
                    this.mediaRecorder = new MediaRecorder(stream, {
                        mimeType: 'audio/webm;codecs=opus'
                    });
                    
                    this.audioChunks = [];

                    this.mediaRecorder.ondataavailable = (event) => {
                        if (event.data.size > 0) {
                            this.audioChunks.push(event.data);
                        }
                    };

                    this.mediaRecorder.onstop = async () => {
                        const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                        await this.sendVoiceMessage(audioBlob);
                        stream.getTracks().forEach(track => track.stop());
                    };

                    this.mediaRecorder.start(100); // Collect data every 100ms
                    this.recordingStartTime = Date.now();
                    this.startRecordingTimer();
                    this.showVoiceRecordingModal();

                } catch (error) {
                    console.error('Error starting voice recording:', error);
                    if (error.name === 'NotAllowedError') {
                        this.showError('Microphone access denied. Please allow microphone permissions.');
                    } else {
                        this.showError('Cannot access microphone. Please check your device settings.');
                    }
                }
            }

            stopVoiceRecording() {
                if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
                    this.mediaRecorder.stop();
                    this.stopRecordingTimer();
                    this.hideVoiceRecordingModal();
                }
            }

            startRecordingTimer() {
                this.recordingTimer = setInterval(() => {
                    const elapsed = Math.floor((Date.now() - this.recordingStartTime) / 1000);
                    const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
                    const seconds = (elapsed % 60).toString().padStart(2, '0');
                    document.getElementById('recordingTimer').textContent = `${minutes}:${seconds}`;
                }, 1000);
            }

            stopRecordingTimer() {
                if (this.recordingTimer) {
                    clearInterval(this.recordingTimer);
                    this.recordingTimer = null;
                }
            }

            showVoiceRecordingModal() {
                document.getElementById('voiceRecordingModal').classList.remove('hidden');
            }

            hideVoiceRecordingModal() {
                document.getElementById('voiceRecordingModal').classList.add('hidden');
                document.getElementById('recordingTimer').textContent = '00:00';
            }

            async sendVoiceMessage(audioBlob) {
                if (!this.activeChatId) {
                    this.showError('No active chat');
                    return;
                }

                try {
                    // Create optimistic message for voice
                    const tempId = 'temp-voice-' + Date.now();
                    const audioUrl = URL.createObjectURL(audioBlob);
                    
                    const optimisticMessage = {
                        id: tempId,
                        message: audioUrl,
                        sender_id: window.currentAdminId,
                        sender_name: 'You',
                        sender_pic: '/images/default-avatar.png',
                        created_at: new Date().toISOString(),
                        is_optimistic: true,
                        type: 'audio',
                        file_name: `voice-message-${Date.now()}.webm`,
                        file_size: audioBlob.size,
                        is_read: false
                    };

                    // Add optimistic message to chat
                    this.addMessageToChat(optimisticMessage, true);

                    const formData = new FormData();
                    formData.append('file', audioBlob, `voice-message-${Date.now()}.webm`);
                    formData.append('chat_room_id', this.activeChatId);
                    formData.append('type', 'group');

                    const response = await fetch('/admin/chat/send-media', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.status) {
                        // Replace optimistic message with real one
                        this.replaceOptimisticMessage(tempId, data.message_data);
                        this.showSuccess('Voice message sent successfully');
                    } else {
                        this.removeOptimisticMessage(tempId);
                        this.showError(data.error || 'Failed to send voice message');
                    }
                } catch (error) {
                    console.error('Voice message send failed:', error);
                    this.removeOptimisticMessage('temp-voice-' + Date.now());
                    this.showError('Failed to send voice message');
                }
            }

            // Chat Info Modal Methods
            openChatInfoModal() {
                if (!this.activeChatId) return;

                const chatName = this.elements.chatName.textContent;
                const chatAvatar = this.elements.chatAvatar.src;
                const container = document.getElementById('chatInfoContent');
                
                container.innerHTML = this.getGroupInfoContent(chatName, chatAvatar);
                document.getElementById('chatInfoModal').classList.remove('hidden');

                this.loadGroupMembers(this.activeChatId);
            }

            closeChatInfoModal() {
                document.getElementById('chatInfoModal').classList.add('hidden');
            }

            getGroupInfoContent(chatName, chatAvatar) {
                return `
                    <div class="space-y-6">
                        <div class="text-center">
                            <img src="${chatAvatar}" class="h-24 w-24 rounded-2xl mx-auto mb-4 border-4 border-white shadow-lg">
                            <h3 class="text-xl font-bold text-gray-800">${chatName}</h3>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-users mr-2 text-blue-500"></i>
                                Group Members
                            </h4>
                            <div id="groupMembersList" class="space-y-2 max-h-60 overflow-y-auto border rounded-xl p-3 bg-gray-50">
                                <div class="text-center py-4 text-gray-400">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500 mx-auto mb-2"></div>
                                    <p>Loading members...</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                <i class="fas fa-cog mr-2 text-gray-500"></i>
                                Group Actions
                            </h4>
                            <div class="grid grid-cols-2 gap-3">
                                <button class="p-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors flex items-center justify-center space-x-2" 
                                        onclick="window.groupManager.openEditGroupModal('${this.activeChatId}')">
                                    <i class="fas fa-edit"></i>
                                    <span>Edit Group</span>
                                </button>
                                <button class="p-3 bg-green-500 text-white rounded-xl hover:bg-green-600 transition-colors flex items-center justify-center space-x-2" 
                                        onclick="window.chatManager.openAddMembersModal()">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Add Members</span>
                                </button>
                                <button class="p-3 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-colors flex items-center justify-center space-x-2" 
                                        onclick="window.chatManager.clearChat()">
                                    <i class="fas fa-broom"></i>
                                    <span>Clear Chat</span>
                                </button>
                                <button class="p-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors flex items-center justify-center space-x-2" 
                                        onclick="window.chatManager.deleteGroup()">
                                    <i class="fas fa-trash"></i>
                                    <span>Delete Group</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }

            async loadGroupMembers(groupId) {
                const container = document.getElementById('groupMembersList');
                if (!container) return;

                try {
                    const res = await fetch(`/admin/chat/group/${groupId}/members`);
                    const data = await res.json();
                    
                    container.innerHTML = '';
                    
                    if (data.status && data.data) {
                        const currentUserId = window.currentAdminId;
                        const isGroupCreator = data.data.some(member => 
                            member.id === currentUserId && member.is_creator
                        );

                        data.data.forEach(member => {
                            const memberDiv = document.createElement('div');
                            memberDiv.className = 'flex justify-between items-center p-3 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors';
                            
                            const isCurrentUser = member.id === currentUserId;
                            const isCreator = member.is_creator;
                            
                            memberDiv.innerHTML = `
                                <div class="flex items-center space-x-3">
                                    <img src="${member.profile_pic}" 
                                         class="h-10 w-10 rounded-lg" 
                                         onerror="this.src='/images/default-avatar.png'">
                                    <div>
                                        <span class="font-medium text-gray-800">${member.name}</span>
                                        <div class="flex space-x-1 mt-1">
                                            ${isCurrentUser ? '<span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded">You</span>' : ''}
                                            ${isCreator ? '<span class="text-xs bg-purple-100 text-purple-600 px-2 py-1 rounded">Creator</span>' : ''}
                                        </div>
                                    </div>
                                </div>
                                ${!isCurrentUser && isGroupCreator ? `
                                    <div class="flex space-x-2">
                                        <button class="text-red-500 hover:text-red-700 transition-colors" 
                                                onclick="window.chatManager.kickMember(${member.id})">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </div>
                                ` : ''}
                            `;
                            
                            container.appendChild(memberDiv);
                        });
                    } else {
                        container.innerHTML = '<div class="text-red-500 text-center py-4">Failed to load members</div>';
                    }
                } catch (error) {
                    console.error('Failed to load group members:', error);
                    container.innerHTML = '<div class="text-red-500 text-center py-4">Error loading members</div>';
                }
            }

            // Add Members Modal Methods
            openAddMembersModal() {
                if (!this.activeChatId) return;

                document.getElementById('addMembersModal').classList.remove('hidden');
                this.loadAvailableAdmins();
                this.closeChatInfoModal();
            }

            closeAddMembersModal() {
                document.getElementById('addMembersModal').classList.add('hidden');
            }

            async loadAvailableAdmins() {
                const container = document.getElementById('availableMembersContainer');
                if (!container) return;

                container.innerHTML = `
                    <div class="flex justify-center items-center py-8">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                        <span class="ml-2 text-gray-500">Loading available admins...</span>
                    </div>
                `;

                try {
                    const res = await fetch(`/admin/chat/group/${this.activeChatId}/available-admins`);
                    const data = await res.json();

                    container.innerHTML = '';

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(admin => {
                            const memberElement = this.createAvailableMemberElement(admin);
                            container.appendChild(memberElement);
                        });

                        // Setup select all functionality
                        document.getElementById('selectAllMembers').addEventListener('change', (e) => {
                            const checkboxes = document.querySelectorAll('.available-member-checkbox');
                            checkboxes.forEach(checkbox => {
                                checkbox.checked = e.target.checked;
                            });
                        });
                    } else {
                        container.innerHTML = `
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-users text-3xl mb-2"></i>
                                <p>No available admins to add</p>
                                <p class="text-sm">All admins are already in this group</p>
                            </div>
                        `;
                    }
                } catch (error) {
                    console.error('Failed to load available admins:', error);
                    container.innerHTML = `
                        <div class="text-center py-8 text-red-400">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>Failed to load available admins</p>
                        </div>
                    `;
                }
            }

            createAvailableMemberElement(admin) {
                const div = document.createElement('div');
                div.className = 'flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors';

                div.innerHTML = `
                    <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500 available-member-checkbox" value="${admin.id}">
                    <label class="ml-3 flex items-center cursor-pointer flex-1">
                        <img class="h-10 w-10 rounded-lg object-cover mr-3 border" 
                             src="${admin.profile_pic}" 
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

            async addMembersToGroup() {
                if (!this.activeChatId) return;

                const selectedMembers = [...document.querySelectorAll('.available-member-checkbox:checked')].map(cb => cb.value);
                
                if (selectedMembers.length === 0) {
                    this.showError('Please select at least one admin to add');
                    return;
                }

                try {
                    const response = await fetch(`/admin/chat/group/${this.activeChatId}/add-members`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ members: selectedMembers })
                    });

                    const data = await response.json();

                    if (data.status) {
                        this.showSuccess('Members added successfully');
                        this.closeAddMembersModal();
                        // Refresh group members list
                        this.loadGroupMembers(this.activeChatId);
                        // Refresh group info
                        this.loadGroupInfo(this.activeChatId);
                    } else {
                        this.showError(data.error || 'Failed to add members');
                    }
                } catch (error) {
                    console.error('Error adding members:', error);
                    this.showError('Error adding members');
                }
            }

            async clearChat() {
                if (!this.activeChatId) return;
                
                if (!confirm("Are you sure you want to clear this chat? This action cannot be undone.")) return;

                try {
                    const endpoint = `/admin/chat/clear/${this.activeChatId}`;
                    const res = await fetch(endpoint, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    
                    const data = await res.json();
                    
                    if (data.status) {
                        this.elements.messageContainer.innerHTML = `
                            <div class="text-center text-gray-400 py-12">
                                <i class="fas fa-broom text-4xl mb-2"></i>
                                <p>Chat cleared successfully</p>
                            </div>
                        `;
                        this.showSuccess('Chat cleared successfully');
                        this.closeChatInfoModal();
                    } else {
                        this.showError('Failed to clear chat');
                    }
                } catch (error) {
                    console.error('Error clearing chat:', error);
                    this.showError('Error clearing chat');
                }
            }

            async deleteGroup() {
                if (!this.activeChatId) return;
                
                if (!confirm("Are you sure you want to delete this group? This action cannot be undone and all messages will be lost.")) return;

                try {
                    const res = await fetch(`/admin/chat/group/${this.activeChatId}/delete`, {
                        method: 'DELETE',
                        headers: { 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await res.json();
                    
                    if (data.status) {
                        this.showSuccess('Group deleted successfully');
                        this.closeChatInfoModal();
                        this.elements.chatArea.classList.add('hidden');
                        this.elements.noChat.classList.remove('hidden');
                        
                        // Refresh sidebar
                        if (window.sidebarManager) {
                            window.sidebarManager.refreshGroupList();
                        }
                    } else {
                        this.showError(data.error || 'Failed to delete group');
                    }
                } catch (error) {
                    console.error('Error deleting group:', error);
                    this.showError('Error deleting group');
                }
            }

            async leaveGroup() {
                if (!this.activeChatId) return;
                
                if (!confirm("Are you sure you want to leave this group?")) return;

                try {
                    const res = await fetch(`/admin/chat/group/${this.activeChatId}/leave`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    
                    const data = await res.json();
                    
                    if (data.status) {
                        this.showSuccess('You have left the group');
                        this.closeChatInfoModal();
                        this.elements.chatArea.classList.add('hidden');
                        this.elements.noChat.classList.remove('hidden');
                        
                        // Refresh sidebar
                        if (window.sidebarManager) {
                            window.sidebarManager.refreshGroupList();
                        }
                    } else {
                        this.showError(data.error || 'Failed to leave group');
                    }
                } catch (error) {
                    console.error('Error leaving group:', error);
                    this.showError('Error leaving group');
                }
            }

            async kickMember(memberId) {
                if (!this.activeChatId || !memberId) return;
                
                if (!confirm("Are you sure you want to remove this member from the group?")) return;

                try {
                    const res = await fetch(`/admin/chat/group/${this.activeChatId}/kick/${memberId}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    
                    const data = await res.json();
                    
                    if (data.status) {
                        this.showSuccess('Member removed successfully');
                        this.loadGroupMembers(this.activeChatId);
                        // Refresh group info
                        this.loadGroupInfo(this.activeChatId);
                    } else {
                        this.showError(data.error || 'Failed to remove member');
                    }
                } catch (error) {
                    console.error('Error removing member:', error);
                    this.showError('Error removing member');
                }
            }

            showError(message) {
                this.showNotification(message, 'error');
            }

            showSuccess(message) {
                this.showNotification(message, 'success');
            }

            showNotification(message, type = 'info') {
                const colors = {
                    error: 'bg-red-500',
                    success: 'bg-green-500',
                    info: 'bg-blue-500'
                };

                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-xl shadow-lg z-50 transform transition-all duration-300`;
                notification.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'}"></i>
                        <span>${message}</span>
                    </div>
                `;
                
                document.body.appendChild(notification);

                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 3000);
            }

            toggleChatMenu() {
                this.showSuccess('Chat menu toggled');
            }
        }
    </script>
</body>
</html>