<title>Newsletter Manager | Admin Panel</title>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    [x-cloak] {
        display: none !important;
    }

    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .status-draft {
        @apply bg-gray-100 text-gray-800;
    }

    .status-scheduled {
        @apply bg-blue-100 text-blue-800;
    }

    .status-sending {
        @apply bg-yellow-100 text-yellow-800;
    }

    .status-sent {
        @apply bg-green-100 text-green-800;
    }

    .status-failed {
        @apply bg-red-100 text-red-800;
    }

    .type-product {
        @apply bg-purple-100 text-purple-800;
    }

    .type-sale {
        @apply bg-orange-100 text-orange-800;
    }

    .type-offer {
        @apply bg-pink-100 text-pink-800;
    }

    .type-custom {
        @apply bg-gray-100 text-gray-800;
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.3s;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }

    .slide-enter-active,
    .slide-leave-active {
        transition: all 0.3s;
    }

    .slide-enter,
    .slide-leave-to {
        transform: translateX(20px);
        opacity: 0;
    }
</style>

<div class="h-screen flex flex-col bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm flex-shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Newsletter Manager</h1>
                    <p class="text-sm text-gray-500">Manage campaigns and subscribers</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button @click="showCreateModal = true" class="gradient-bg text-white px-4 py-2 rounded-lg flex items-center space-x-2 hover:opacity-90 transition">
                        <i class="fas fa-plus"></i>
                        <span>Create Campaign</span>
                    </button>
                </div>
            </div>
        </div>
    </header>
    @php
    use App\Models\NewsletterSubscriber;
    @endphp


    <main class="flex-1 overflow-y-auto p-6">
        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Active Subscribers</p>
                        <p class="text-3xl font-bold">{{ $stats['activeSubscribers'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-xs text-green-500">
                        <i class="fas fa-arrow-up"></i> 5.2% from last month
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Average Open Rate</p>
                        <p class="text-3xl font-bold">{{ $stats['avgOpenRate'] }}%</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-envelope-open text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-xs text-green-500">
                        <i class="fas fa-arrow-up"></i> 2.4% from last month
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Emails in Queue</p>
                        <p class="text-3xl font-bold">{{ $stats['emailsQueued'] }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-inbox text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-xs text-gray-500">Processing in background</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Campaigns Today</p>
                        <p class="text-3xl font-bold">{{ $stats['campaignsToday'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-paper-plane text-yellow-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-xs text-green-500">
                        <i class="fas fa-check"></i> All on schedule
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow mb-8">
        <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Performance Overview (Last 30 Days)</h2>
            </div>
            <div class="p-6">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Campaigns Table -->
        <div class="bg-white rounded-xl shadow">
        <div class="px-6 py-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Campaigns</h2>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" x-model="search" @input="filterCampaigns" placeholder="Search campaigns..."
                            class="pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <select x-model="statusFilter" @change="filterCampaigns" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="sending">Sending</option>
                        <option value="sent">Sent</option>
                        <option value="failed">Failed</option>
                    </select>
                    <select x-model="typeFilter" @change="filterCampaigns" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        <option value="product">Product</option>
                        <option value="sale">Sale</option>
                        <option value="offer">Offer</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px]">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaign</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipients</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($campaigns as $campaign)
                        <tr x-show="matchesFilter('{{ $campaign->status }}', '{{ $campaign->type }}', '{{ $campaign->name }}')"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full status-{{ $campaign->status }}">
                                    {{ strtoupper($campaign->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $campaign->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $campaign->subject }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full type-{{ $campaign->type }}">
                                    {{ ucfirst($campaign->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $campaign->sent_count }} / {{ $campaign->total_recipients }}</div>
                                @if($campaign->status == 'sending')
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-blue-600 h-2 rounded-full"
                                        :style="`width: ${Math.round(({{ $campaign->sent_count }} / {{ $campaign->total_recipients }}) * 100)}%`"></div>
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($campaign->status == 'sent')
                                <div class="flex items-center space-x-4">
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-blue-600">{{ $campaign->open_rate }}%</div>
                                        <div class="text-xs text-gray-500">Open Rate</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-green-600">{{ $campaign->click_rate }}%</div>
                                        <div class="text-xs text-gray-500">Click Rate</div>
                                    </div>
                                </div>
                                @elseif($campaign->status == 'scheduled')
                                <div class="text-sm text-gray-500">
                                    Scheduled for {{ $campaign->scheduled_at->format('M j, g:i A') }}
                                </div>
                                @else
                                <div class="text-sm text-gray-500">-</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    @if($campaign->status == 'draft')
                                    <button @click="editCampaign({{ $campaign->id }})"
                                        class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.newsletter.campaigns.send', $campaign->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </form>
                                    @elseif($campaign->status == 'scheduled')
                                    <form action="{{ route('admin.newsletter.campaigns.cancel', $campaign->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if($campaign->status == 'sent')
                                    <a href="{{ route('admin.newsletter.campaigns.report', $campaign->id) }}"
                                        class="text-purple-600 hover:text-purple-900">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>
                                    @endif

                                    <form action="{{ route('admin.newsletter.campaigns.duplicate', $campaign->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-gray-600 hover:text-gray-900">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </form>

                                    @if($campaign->status != 'sending')
                                    <form action="{{ route('admin.newsletter.campaigns.destroy', $campaign->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this campaign?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t">
                {{ $campaigns->links() }}
            </div>

            <!-- Subscribers Section -->
            <div class="bg-white rounded-xl shadow mt-8" x-show="showSubscribers" x-transition>
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Subscribers</h2>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search subscribers..."
                                class="pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <select class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Status</option>
                            <option value="subscribed">Subscribed</option>
                            <option value="unsubscribed">Unsubscribed</option>
                        </select>
                    </div>
                </div>

                <div class="">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscribed</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(NewsletterSubscriber::take(10)->get() as $subscriber)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $subscriber->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $subscriber->first_name }} {{ $subscriber->last_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($subscriber->is_unsubscribed)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Unsubscribed
                                    </span>
                                    @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Subscribed
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $subscriber->subscribed_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form action="{{ route('admin.newsletter.subscribers.toggle-unsubscribe', $subscriber->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900">
                                            @if($subscriber->is_unsubscribed)
                                            <i class="fas fa-user-plus"></i> Resubscribe
                                            @else
                                            <i class="fas fa-user-minus"></i> Unsubscribe
                                            @endif
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
<!-- Create/Edit Campaign Modal -->
<div x-show="showCreateModal || showEditModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            @click="showCreateModal = false; showEditModal = false"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
            @click.stop>
            <form :action="showEditModal ? '/admin/newsletter/campaigns/' + editId : '{{ route('admin.newsletter.campaigns.store') }}'"
                method="POST">
                @csrf
                <template x-if="showEditModal">
                    @method('PUT')
                </template>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4"
                        x-text="showEditModal ? 'Edit Campaign' : 'Create New Campaign'">
                    </h3>

                    <div class="space-y-6">
                        <!-- Campaign Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Campaign Name</label>
                            <input type="text" name="name" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Subject Line -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Subject Line</label>
                            <input type="text" name="subject" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Campaign Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Campaign Type</label>
                            <select name="type" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="custom">Custom Campaign</option>
                                <option value="product">Product Announcement</option>
                                <option value="sale">Sale Promotion</option>
                                <option value="offer">Special Offer</option>
                            </select>
                        </div>

                        <!-- Preview Text -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Preview Text</label>
                            <textarea name="preview_text" rows="2"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Brief text shown in email preview..."></textarea>
                        </div>

                        <!-- Email Content (WYSIWYG) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Content</label>
                            <div class="border border-gray-300 rounded-lg overflow-hidden">
                                <!-- Toolbar -->
                                <div class="bg-gray-50 px-4 py-2 border-b flex flex-wrap gap-2">
                                    <button type="button" @click="execCommand('bold')" class="p-2 hover:bg-gray-200 rounded">
                                        <i class="fas fa-bold"></i>
                                    </button>
                                    <button type="button" @click="execCommand('italic')" class="p-2 hover:bg-gray-200 rounded">
                                        <i class="fas fa-italic"></i>
                                    </button>
                                    <button type="button" @click="execCommand('underline')" class="p-2 hover:bg-gray-200 rounded">
                                        <i class="fas fa-underline"></i>
                                    </button>
                                    <button type="button" @click="execCommand('formatBlock', '<h1>')" class="p-2 hover:bg-gray-200 rounded">
                                        H1
                                    </button>
                                    <button type="button" @click="execCommand('formatBlock', '<h2>')" class="p-2 hover:bg-gray-200 rounded">
                                        H2
                                    </button>
                                    <button type="button" @click="execCommand('insertUnorderedList')" class="p-2 hover:bg-gray-200 rounded">
                                        <i class="fas fa-list-ul"></i>
                                    </button>
                                    <button type="button" @click="execCommand('insertOrderedList')" class="p-2 hover:bg-gray-200 rounded">
                                        <i class="fas fa-list-ol"></i>
                                    </button>
                                    <button type="button" @click="execCommand('createLink')" class="p-2 hover:bg-gray-200 rounded">
                                        <i class="fas fa-link"></i>
                                    </button>
                                    <button type="button" @click="execCommand('insertImage')" class="p-2 hover:bg-gray-200 rounded">
                                        <i class="fas fa-image"></i>
                                    </button>
                                </div>

                                <!-- Editor -->
                                <div id="emailEditor"
                                    contenteditable="true"
                                    class="min-h-[300px] p-4 focus:outline-none"
                                    @input="updateContent">
                                    <p>Start writing your email content here...</p>
                                </div>
                                <textarea name="content" x-model="emailContent" class="hidden"></textarea>
                            </div>
                        </div>

                        <!-- Scheduling -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Schedule</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="radio" id="sendNow" name="send_option" value="now" checked
                                        @change="showScheduleOptions = false"
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="sendNow" class="ml-2 text-sm text-gray-700">
                                        Send Immediately
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="sendLater" name="send_option" value="later"
                                        @change="showScheduleOptions = true"
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="sendLater" class="ml-2 text-sm text-gray-700">
                                        Schedule for Later
                                    </label>
                                </div>

                                <div x-show="showScheduleOptions" class="ml-6 mt-2 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Schedule Date & Time</label>
                                        <div class="flex space-x-4 mt-1">
                                            <input type="date" name="scheduled_date"
                                                class="border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <input type="time" name="scheduled_time"
                                                class="border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <span x-text="showEditModal ? 'Update Campaign' : 'Create Campaign'"></span>
                    </button>
                    <button type="button"
                        @click="showCreateModal = false; showEditModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Quick Actions Panel -->
<div class="fixed bottom-6 right-6 flex flex-col space-y-3">
    <button @click="showCreateModal = true"
        class="gradient-bg text-white p-4 rounded-full shadow-lg hover:shadow-xl transition">
        <i class="fas fa-plus text-xl"></i>
    </button>
    <button @click="showSubscribers = !showSubscribers"
        class="bg-green-500 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition">
        <i class="fas fa-users text-xl"></i>
    </button>
    <button @click="refreshData"
        class="bg-blue-500 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition">
        <i class="fas fa-sync-alt text-xl"></i>
    </button>
</div>


<script>
    // Initialize Chart
    const ctx = document.getElementById('performanceChart').getContext('2d');
    const performanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json(array_column($performanceData, 'date')),
            datasets: [{
                    label: 'Sent',
                    data: @json(array_column($performanceData, 'sent')),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Opens',
                    data: @json(array_column($performanceData, 'opens')),
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Clicks',
                    data: @json(array_column($performanceData, 'clicks')),
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Alpine.js Application
    function newsletterApp() {
        return {
            showCreateModal: false,
            showEditModal: false,
            showSubscribers: false,
            showScheduleOptions: false,
            search: '',
            statusFilter: '',
            typeFilter: '',
            editId: null,
            emailContent: '',
            campaigns: @json($campaigns -> items()),

            init() {
                // Initialize any required setup
                console.log('Newsletter app initialized');
            },

            matchesFilter(status, type, name) {
                if (this.statusFilter && status !== this.statusFilter) return false;
                if (this.typeFilter && type !== this.typeFilter) return false;
                if (this.search && !name.toLowerCase().includes(this.search.toLowerCase())) return false;
                return true;
            },

            filterCampaigns() {
                // Filtering is handled by x-show in template
            },

            async editCampaign(id) {
                try {
                    const response = await fetch(`/admin/newsletter/campaigns/${id}/edit`);
                    const campaign = await response.json();

                    // Populate form fields
                    document.querySelector('[name="name"]').value = campaign.name;
                    document.querySelector('[name="subject"]').value = campaign.subject;
                    document.querySelector('[name="type"]').value = campaign.type;
                    document.querySelector('[name="preview_text"]').value = campaign.preview_text;
                    this.emailContent = campaign.content;

                    const editor = document.getElementById('emailEditor');
                    editor.innerHTML = campaign.content;

                    this.editId = id;
                    this.showEditModal = true;
                } catch (error) {
                    console.error('Error fetching campaign:', error);
                }
            },

            execCommand(command, value = null) {
                document.execCommand(command, false, value);
                this.updateContent();
            },

            updateContent() {
                const editor = document.getElementById('emailEditor');
                this.emailContent = editor.innerHTML;
            },

            async refreshData() {
                try {
                    const response = await fetch('/admin/newsletter/refresh');
                    const data = await response.json();

                    // Update stats in real-time
                    // You would need to implement this endpoint
                    console.log('Data refreshed:', data);

                    // Show success notification
                    this.showNotification('Data refreshed successfully', 'success');
                } catch (error) {
                    console.error('Error refreshing data:', error);
                    this.showNotification('Error refreshing data', 'error');
                }
            },

            showNotification(message, type = 'info') {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${
                        type === 'success' ? 'bg-green-500' : 
                        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
                    }`;
                notification.textContent = message;

                document.body.appendChild(notification);

                // Remove after 3 seconds
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            },

            // Auto-refresh for sending campaigns
            startAutoRefresh() {
                setInterval(() => {
                    const sendingCampaigns = document.querySelectorAll('.status-sending');
                    if (sendingCampaigns.length > 0) {
                        // Refresh the page or fetch updated data
                        window.location.reload();
                    }
                }, 10000); // Refresh every 10 seconds
            }
        };
    }

    // Start auto-refresh if there are sending campaigns
    document.addEventListener('DOMContentLoaded', function() {
        const hasSendingCampaigns = document.querySelector('.status-sending');
        if (hasSendingCampaigns) {
            setTimeout(() => {
                window.location.reload();
            }, 10000);
        }
    });

    // Toast Notification System
    window.showToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-4 py-3 rounded shadow-lg text-white ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' :
                type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
            }`;
        toast.textContent = message;
        toast.style.zIndex = '9999';

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    };

    // Handle success/error messages from Laravel
    @if(session('success'))
    showToast("{{ session('success') }}", 'success');
    @endif

    @if(session('error'))
    showToast("{{ session('error') }}", 'error');
    @endif
</script>
