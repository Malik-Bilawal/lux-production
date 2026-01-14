@extends("admin.layouts.master-layouts.plain")
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Contact Management | Luxorix | Admin Panel')

@push("script")
<script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        dark: '#1F2937',
                        light: '#F9FAFB'
                    }
                }
            }
        }
    </script>
@endpush


@push("style")
<style>
        .message-row {
            transition: background-color 0.2s ease;
        }
        .message-row:hover {
            background-color: #F9FAFB;
        }
        .message-row.unread {
            background-color: #EFF6FF;
            font-weight: 500;
        }
        .message-row.unread:hover {
            background-color: #E1EFFE;
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
        }
        .action-btn {
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }
        .message-content {
            max-height: 300px;
            overflow-y: auto;
        }
        .reply-area {
            transition: all 0.3s ease;
        }
        .reply-area:focus-within {
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
@endpush


@section("content")
<header class="bg-white shadow-sm">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center py-4 px-6 gap-4">
        
        <div class="w-full sm:w-auto">
            <h2 class="text-xl font-semibold text-gray-800">Message Management</h2>
            <p class="text-sm text-gray-500">View and respond to customer inquiries</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            <form method="GET" action="{{ route('admin.contact.index') }}" class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-auto">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Search messages..." 
                        class="border rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64"
                    >
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <button 
                    type="submit" 
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center justify-center action-btn w-full sm:w-auto"
                >
                    <i class="fas fa-filter mr-2"></i>
                    Search
                </button>
            </form>
            
            @include("admin.components.dark-mode.dark-toggle")
        </div>
    </div>
</header>

<section class="px-6 py-4 mt-1">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center card-hover">
            <div class="rounded-full bg-blue-100 p-3 mr-4">
                <i class="fas fa-envelope text-blue-500 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Messages</p>
                <p class="text-xl font-semibold">{{ $totalMessages }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center card-hover">
            <div class="rounded-full bg-yellow-100 p-3 mr-4">
                <i class="fas fa-clock text-yellow-500 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Pending</p>
                <p class="text-xl font-semibold">{{ $pendingMessages }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center card-hover">
            <div class="rounded-full bg-green-100 p-3 mr-4">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Replied</p>
                <p class="text-xl font-semibold">{{ $repliedMessages }}</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center card-hover">
            <div class="rounded-full bg-purple-100 p-3 mr-4">
                <i class="fas fa-history text-purple-500 text-xl"></i>
            </div>
            <div id="avgResponseTime">
                <p class="text-gray-500 text-sm">Avg. Response Time</p>
                <p class="text-xl font-semibold" id="avgTimeValue">Loading...</p>
            </div>
            
            <script>
            // This script is fine, no changes needed
            fetch("{{ route('admin.contact.index.avg-response-time') }}")
                .then(res => res.json())
                .then(data => {
                    document.getElementById('avgTimeValue').innerText = data.avg_response_time ?? "N/A";
                });
            </script>
        </div>
    </div>
</section>

<section class="px-6 py-4 bg-white shadow-sm mt-1">
    <form method="GET" action="{{ route('admin.contact.index') }}">
        <div class="flex flex-col sm:flex-row sm:flex-wrap items-end gap-4">

            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="border rounded-md px-3 py-2 text-sm w-full sm:w-40">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                    <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <select name="date_range" class="border rounded-md px-3 py-2 text-sm w-full sm:w-48">
                    <option value="">All Time</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select name="sort" class="border rounded-md px-3 py-2 text-sm w-full sm:w-40">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>A to Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Z to A</Ooption>
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm flex items-center justify-center action-btn w-full sm:w-auto">
                    <i class="fas fa-filter mr-2"></i>
                    Apply Filters
                </button>
            </div>
            
            <div class="w-full sm:w-auto">
                <a href="{{ route('admin.contact.contact-info') }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm flex items-center justify-center transition duration-300 shadow-md w-full sm:w-auto">
                    <i class="fas fa-plus mr-2"></i>
                    Add Info
                </a>
            </div>
            
        </div>
    </form>
</section>

<<section class="p-6 flex-1">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">

        <div class="overflow-x-auto hidden lg:block">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" class="rounded text-blue-500 focus:ring-blue-400">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($messages as $msg)
                    <tr class="message-row {{ $msg->status == 'pending' ? 'unread font-bold' : '' }}">
                        <td class="px-6 py-4 w-8">
                            <input type="checkbox" class="rounded text-blue-500 focus:ring-blue-400">
                        </td>
                        <td class="px-6 py-4 w-48">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center mr-3">
                                    <span class="font-medium text-white">{{ strtoupper(substr($msg->name,0,2)) }}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $msg->name }}</div>
                                    <div class="text-gray-500 text-sm">{{ $msg->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 w-60">
                            <div class="font-medium">{{ $msg->subject ?? 'No Subject' }}</div>
                            <div class="text-gray-500 text-sm truncate max-w-xs">{{ Str::limit($msg->message, 40) }}</div>
                        </td>
                        <td class="px-6 py-4 w-40">
                            <div class="text-sm text-gray-900">{{ $msg->created_at->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $msg->created_at->timezone('Asia/Karachi')->format('h:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 w-32">
                            @php
                            $badgeColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'read' => 'bg-blue-100 text-blue-800',
                            'replied' => 'bg-green-100 text-green-800'
                            ];
                            @endphp
                            <span class="status-badge {{ $badgeColors[$msg->status] }}">{{ ucfirst($msg->status) }}</span>
                        </td>
                        <td class="px-6 py-4 w-36">
                            <div class="flex space-x-2">
                                <button class="text-blue-500 hover:text-blue-700 action-btn" onclick="openViewMessageModal(this, '{{ $msg->id }}')"
                                    data-name="{{ $msg->name }}" data-email="{{ $msg->email }}" data-subject="{{ $msg->subject ?? 'No Subject' }}"
                                    data-message="{{ $msg->message }}" data-date="{{ $msg->created_at->timezone('Asia/Karachi')->format('M d, Y h:i A') }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-green-500 hover:text-green-700 action-btn" data-id="{{ $msg->id }}"
                                    data-name="{{ $msg->name }}" data-email="{{ $msg->email }}" data-message="{{ $msg->message }}"
                                    data-subject="{{ $msg->subject ?? 'No Subject' }}" onclick="openReplyModal(this)">
                                    <i class="fas fa-reply"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.contact.index.destroy', $msg->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 action-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="block lg:hidden">
            <div class="p-4 space-y-4">
                @foreach($messages as $msg)
                <div class="border rounded-lg overflow-hidden {{ $msg->status == 'pending' ? 'border-blue-400 border-2' : 'border-gray-200' }}">

                    <div class="flex items-center justify-between p-4 bg-gray-50 border-b">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center mr-3">
                                <span class="font-medium text-white">{{ strtoupper(substr($msg->name,0,2)) }}</span>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 {{ $msg->status == 'pending' ? 'font-bold' : '' }}">{{ $msg->name }}</div>
                                <div class="text-gray-500 text-sm">{{ $msg->email }}</div>
                            </div>
                        </div>
                        <input type="checkbox" class="rounded text-blue-500 focus:ring-blue-400 ml-4">
                    </div>

                    <div class="p-4 space-y-3">
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-500 w-1/3">Subject</span>
                            <span class="text-sm text-gray-900 w-2/3 text-right {{ $msg->status == 'pending' ? 'font-bold' : '' }}">{{ $msg->subject ?? 'No Subject' }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-500 w-1/3">Message</span>
                            <span class="text-sm text-gray-500 w-2/3 text-right truncate">{{ Str::limit($msg->message, 40) }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-500 w-1/3">Date</span>
                            <div class="text-sm text-gray-900 text-right">
                                <div>{{ $msg->created_at->format('M d, Y') }}</div>
                                <div class="text-gray-500">{{ $msg->created_at->timezone('Asia/Karachi')->format('h:i A') }}</div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Status</span>
                            @php
                            $badgeColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'read' => 'bg-blue-100 text-blue-800',
                            'replied' => 'bg-green-100 text-green-800'
                            ];
                            @endphp
                            <span class="status-badge {{ $badgeColors[$msg->status] }}">{{ ucfirst($msg->status) }}</span>
                        </div>
                    </div>

                    <div class="flex justify-around items-center p-2 bg-gray-50 border-t">
                        <button class="text-blue-500 hover:text-blue-700 p-2 action-btn flex flex-col items-center"
                            onclick="openViewMessageModal(this, '{{ $msg->id }}')" data-name="{{ $msg->name }}" data-email="{{ $msg->email }}"
                            data-subject="{{ $msg->subject ?? 'No Subject' }}" data-message="{{ $msg->message }}"
                            data-date="{{ $msg->created_at->timezone('Asia/Karachi')->format('M d, Y h:i A') }}">
                            <i class="fas fa-eye text-lg"></i>
                            <span class="text-xs font-medium mt-1">View</span>
                        </button>

                        <button class="text-green-500 hover:text-green-700 p-2 action-btn flex flex-col items-center"
                            data-id="{{ $msg->id }}" data-name="{{ $msg->name }}" data-email="{{ $msg->email }}"
                            data-message="{{ $msg->message }}" data-subject="{{ $msg->subject ?? 'No Subject' }}" onclick="openReplyModal(this)">
                            <i class="fas fa-reply text-lg"></i>
                            <span class="text-xs font-medium mt-1">Reply</span>
                        </button>

                        <form method="POST" action="{{ route('admin.contact.index.destroy', $msg->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-2 action-btn flex flex-col items-center">
                                <i class="fas fa-trash text-lg"></i>
                                <span class="text-xs font-medium mt-1">Delete</span>
                            </button>
                        </form>
                    </div>

                </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-between px-6 py-4 border-t bg-white">
            <div class="text-sm text-gray-700">
                Showing <span class="font-semibold">{{ $messages->firstItem() }}</span>
                to <span class="font-semibold">{{ $messages->lastItem() }}</span>
                of <span class="font-semibold">{{ $messages->total() }}</span> messages
            </div>

            <div class="flex space-x-2">
                @if ($messages->onFirstPage())
                <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">Previous</span>
                @else
                <a href="{{ $messages->previousPageUrl() }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                    Previous
                </a>
                @endif

                @if ($messages->hasMorePages())
                <a href="{{ $messages->nextPageUrl() }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                    Next
                </a>
                @else
                <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>

    </div>
</section>

    <!-- View Message Modal -->
    <div id="viewMessageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Message Details</h3>
                <button onclick="closeViewMessageModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                            <span class="font-medium text-blue-800 text-xl" id="senderInitials">JS</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg" id="senderName">John Smith</h4>
                            <p class="text-gray-500" id="senderEmail">john.smith@example.com</p>
                            <p class="text-sm text-gray-500 mt-1" id="messageDate">Today at 10:24 AM</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="font-medium text-gray-700 mb-2">Subject</h4>
                        <p id="messageSubject">Product inquiry about Smart Watch Pro</p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">Message</h4>
                        <div class="message-content bg-gray-50 p-4 rounded-lg" id="messageContent">
                            <p>Hello, I'm interested in your Smart Watch Pro but would like to know if it's waterproof and what the battery life is like with continuous heart rate monitoring enabled.</p>
                            <p class="mt-3">Also, could you tell me if it's compatible with iOS devices? I have an iPhone 12 and want to make sure all features will work properly.</p>
                            <p class="mt-3">Thank you for your help!</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors" onclick="closeViewMessageModal()">
                        Close
                    </button>

<!-- Modal Reply Button -->
<button class="text-green-500 hover:text-green-700 action-btn" id="modalReplyBtn"
onclick="openReplyModalFromView(this) ">
    <i class="fas fa-reply"></i>
</button>





                </div>
            </div>
        </div>
    </div>

    <!-- Reply Modal -->
    <div id="replyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Reply to Message</h3>
                <button onclick="closeReplyModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <form id="replyForm">
                <input type="hidden" id="replyEmail" name="email">
                <input type="hidden" id="replyName" name="name">
                <input type="hidden" id="replyId" name="id">
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-start mb-2">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <span class="font-medium text-blue-800" id="replySenderInitials">JS</span>
                            </div>
                            <div>
                                <h4 class="font-medium" id="replySenderName">John Smith</h4>
                                <p class="text-sm text-gray-500" id="replySenderEmail">john.smith@example.com</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 mt-2" id="originalMessagePreview">Original message: Hello, I'm interested in your Smart Watch Pro but would like to know if it's waterproof...</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="replySubject">Subject</label>
                        <input type="text" id="replySubject" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="Re: Product inquiry about Smart Watch Pro">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="replyMessage">Your Response</label>
                        <div class="reply-area border rounded-lg focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-200">
                            <textarea id="replyMessage" rows="6" class="w-full px-4 py-2 rounded-lg focus:outline-none" placeholder="Type your response here..."></textarea>
                            <div class="px-4 py-2 bg-gray-50 border-t flex justify-between items-center">
                                <div class="flex space-x-2">
                                    <button type="button" class="text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-paperclip"></i>
                                    </button>
                                    <button type="button" class="text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-bold"></i>
                                    </button>
                                    <button type="button" class="text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-italic"></i>
                                    </button>
                                </div>
                                <div class="text-sm text-gray-500" id="charCount">0 characters</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" class="rounded text-blue-500 focus:ring-blue-400" checked>
                            <span class="ml-2 text-sm text-gray-700">Send a copy to my email</span>
                        </label>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors" onclick="closeReplyModal()">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i>Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection


@push("script")
    <script>
function openViewMessageModal(btn, id) {
    const name = btn.dataset.name;
    const email = btn.dataset.email;
    const subject = btn.dataset.subject;
    const message = btn.dataset.message;
    const date = btn.dataset.date;

    // Fill modal fields
    document.getElementById('senderInitials').textContent = name.substr(0,2).toUpperCase();
    document.getElementById('senderName').textContent = name;
    document.getElementById('senderEmail').textContent = email;
    document.getElementById('messageSubject').textContent = subject;
    document.getElementById('messageContent').innerHTML = `<p>${message.replace(/\n/g,'</p><p>')}</p>`;
    document.getElementById('messageDate').textContent = date;

    // Pass data to the modal reply button dynamically
    const modalReplyBtn = document.getElementById('modalReplyBtn');
    modalReplyBtn.dataset.id = id;
    modalReplyBtn.dataset.name = name;
    modalReplyBtn.dataset.email = email;
    modalReplyBtn.dataset.subject = subject;
    modalReplyBtn.dataset.message = message;

    // Show modal
    document.getElementById('viewMessageModal').classList.remove('hidden');

    // Mark as read
    fetch(`/admin/messages/${id}/mark-read`, {
    method: 'PATCH',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
})

    .then(res => {
        if (!res.ok) throw new Error("Failed to mark as read");
        return res.json();
    })
    .then(data => {
        console.log("Marked as read:", data);
        const row = document.querySelector(`#message-row-${id}`);
        if (row) row.classList.remove("unread", "font-bold", "bg-gray-100");
    })
    .catch(err => console.error("Error:", err));
}

function closeViewMessageModal() {
    const modal = document.getElementById('viewMessageModal');
    modal.classList.add('hidden');
}
function openReplyModal(button) {
    const id = button.dataset.id;
    const name = button.dataset.name;
    const email = button.dataset.email;
    const message = button.dataset.message;
    const subject = button.dataset.subject;

    // Fill modal values dynamically
    document.getElementById('replyId').value = id;
    document.getElementById('replyName').value = name;
    document.getElementById('replyEmail').value = email;
    document.getElementById('replySenderName').textContent = name;
    document.getElementById('replySenderEmail').textContent = email;
    document.getElementById('replySenderInitials').textContent = name.slice(0,2).toUpperCase();
    document.getElementById('originalMessagePreview').textContent = "Original message: " + message;
    document.getElementById('replySubject').value = "Re: " + subject;

    // Show modal
    document.getElementById('replyModal').classList.remove('hidden');

    // Destroy previous editor if exists
    if (tinymce.get('replyMessage')) {
        tinymce.get('replyMessage').remove();
    }

    // Init TinyMCE with full toolbar
    setTimeout(() => {
        tinymce.init({
            selector: '#replyMessage',
            height: 400,
            menubar: 'file edit view insert format tools table help',
            plugins: 'advlist autolink lists link image charmap preview anchor ' +
                     'searchreplace visualblocks code fullscreen ' +
                     'insertdatetime media table help wordcount ' +
                     'emoticons hr pagebreak nonbreaking directionality visualchars codesample template autosave',
            toolbar: 'undo redo | styles | fontfamily fontsize lineheight | ' +
                     'bold italic underline strikethrough subscript superscript removeformat | forecolor backcolor | ' +
                     'alignleft aligncenter alignright alignjustify | outdent indent | bullist numlist checklist | ' +
                     'blockquote hr pagebreak nonbreaking | link image media emoticons charmap anchor | ' +
                     'ltr rtl | visualchars visualblocks | codesample template | ' +
                     'table | insertdatetime | preview code fullscreen help',
            branding: false,
            promotion: false
        });
    }, 200);
}

        
        function openReplyModalFromView(button) {
    const id = button.dataset.id;
    const name = button.dataset.name;
    const email = button.dataset.email;
    const message = button.dataset.message;
    const subject = button.dataset.subject;

    document.getElementById('replyId').value = id;
    document.getElementById('replyName').value = name;
    document.getElementById('replyEmail').value = email;
    document.getElementById('replySenderName').textContent = name;
    document.getElementById('replySenderEmail').textContent = email;
    document.getElementById('replySenderInitials').textContent = name.slice(0,2).toUpperCase();
    document.getElementById('originalMessagePreview').textContent = "Original message: " + message;
    document.getElementById('replySubject').value = "Re: " + subject;

    document.getElementById('replyModal').classList.remove('hidden');
}
        function closeReplyModal() {
            document.getElementById('replyModal').classList.add('hidden');
        }

        window.onclick = function(event) {
            const viewModal = document.getElementById('viewMessageModal');
            const replyModal = document.getElementById('replyModal');
            
            if (event.target === viewModal) {
                closeViewMessageModal();
            }
            
            if (event.target === replyModal) {
                closeReplyModal();
            }
        }
        
        document.getElementById('replyMessage').addEventListener('input', function(e) {
            const length = e.target.value.length;
            document.getElementById('charCount').textContent = `${length} characters`;
        });
        document.addEventListener('DOMContentLoaded', function() {
    const replyForm = document.getElementById('replyForm');
    if (!replyForm) return console.error('replyForm not found');

    replyForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = {
            id: document.getElementById('replyId').value,   
            email: document.getElementById('replyEmail').value,
            name: document.getElementById('replyName').value,
            subject: document.getElementById('replySubject').value,
            message: tinymce.get('replyMessage') ? tinymce.get('replyMessage').getContent() : document.getElementById('replyMessage').value
        };

        console.log('Sending data:', formData);

        modalReplyBtn.textContent = "sending...";
        try {
            const res = await fetch('{{ route("admin.contact.index.reply") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            });

            if (!res.ok) {
                const text = await res.text();
                console.error('HTTP error', res.status, text);
                alert(`Error ${res.status}: Check console`);
                return;
            }

            const json = await res.json();
            console.log('Server JSON:', json);

            if (json.success) {
                alert(json.message);
                document.getElementById('replyModal').classList.add('hidden');
                modalReplyBtn.textContent = "send repply"
            } else {
                alert(json.message || 'Server returned an error');
            }

        } catch(err) {
            console.error('Fetch failed:', err);
            alert('Fetch failed. Check console.');
        }
    });
});




function closeReplyModal() {
    document.getElementById('replyModal').classList.add('hidden');
    if (tinymce.get('replyMessage')) {
        tinymce.get('replyMessage').remove();
    }
}



        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeViewMessageModal();
                closeReplyModal();
            }
        });
    </script>
@endpush