<!DOCTYPE html>
<html lang="en" x-data="reportApp()" x-cloak>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Report: {{ $campaign->name }} | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@3.0.0/build/global/luxon.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-green {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }

        .gradient-blue {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
        }

        .gradient-purple {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
        }

        .gradient-orange {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        }

        .shadow-soft {
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.08);
        }

        .animate-pulse-soft {
            animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse-soft {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #374151;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px;
            position: absolute;
            z-index: 100;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        .status-badge {
            @apply px-2 py-1 text-xs font-semibold rounded-full;
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
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.newsletter.index') }}"
                            class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $campaign->name }}</h1>
                            <div class="flex items-center space-x-3 mt-1">
                                <span class="status-badge status-{{ $campaign->status }}">
                                    {{ strtoupper($campaign->status) }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ $campaign->sent_date }}
                                </span>
                                @if($campaign->type)
                                <span class="text-sm text-gray-500">
                                    • {{ ucfirst($campaign->type) }} Campaign
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button @click="exportReport"
                        class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center space-x-2 hover:bg-gray-50 transition">
                        <i class="fas fa-download"></i>
                        <span>Export Report</span>
                    </button>
                    <button @click="printReport"
                        class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center space-x-2 hover:bg-gray-50 transition">
                        <i class="fas fa-print"></i>
                        <span>Print</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Campaign Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Left Column: Key Metrics -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Performance Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Delivery Rate -->
                    <div class="bg-white rounded-xl shadow-soft p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Delivery Rate</p>
                                <p class="text-3xl font-bold mt-1">
                                    {{ $campaign->total_recipients > 0 ? 
                                       round(($campaign->sent_count / $campaign->total_recipients) * 100, 1) : 0 }}%
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-50">
                                <i class="fas fa-paper-plane text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Sent</span>
                                <span>{{ $campaign->sent_count }}/{{ $campaign->total_recipients }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full"
                                    :style="`width: ${Math.min(100, Math.round(({{ $campaign->sent_count }} / {{ $campaign->total_recipients }}) * 100))}%`"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Open Rate -->
                    <div class="bg-white rounded-xl shadow-soft p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Open Rate</p>
                                <p class="text-3xl font-bold mt-1">{{ $campaign->open_rate }}%</p>
                            </div>
                            <div class="p-3 rounded-full bg-green-50">
                                <i class="fas fa-envelope-open text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Opened</span>
                                <span>{{ $campaign->opens }} opens</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full"
                                    :style="`width: {{ $campaign->open_rate }}%`"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Click Rate -->
                    <div class="bg-white rounded-xl shadow-soft p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Click Rate</p>
                                <p class="text-3xl font-bold mt-1">{{ $campaign->click_rate }}%</p>
                            </div>
                            <div class="p-3 rounded-full bg-purple-50">
                                <i class="fas fa-mouse-pointer text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Clicked</span>
                                <span>{{ $campaign->clicks }} clicks</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full"
                                    :style="`width: {{ $campaign->click_rate }}%`"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Engagement Timeline -->
                <div class="bg-white rounded-xl shadow-soft p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-semibold text-gray-800">Engagement Timeline</h2>
                        <div class="flex items-center space-x-2">
                            <button @click="timelineRange = 'day'"
                                :class="timelineRange === 'day' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                                class="px-3 py-1 rounded-lg text-sm transition">
                                Day
                            </button>
                            <button @click="timelineRange = 'week'"
                                :class="timelineRange === 'week' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                                class="px-3 py-1 rounded-lg text-sm transition">
                                Week
                            </button>
                            <button @click="timelineRange = 'month'"
                                :class="timelineRange === 'month' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                                class="px-3 py-1 rounded-lg text-sm transition">
                                Month
                            </button>
                        </div>
                    </div>
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="engagementChart"></canvas>
                    </div>
                </div>

                <!-- Top Performing Links -->
                <div class="bg-white rounded-xl shadow-soft p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Top Performing Links</h2>
                    <div class="space-y-4">
                        @forelse($engagementData['top_links'] as $link)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $link->clicked_url }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $link->clicks }} clicks •
                                    {{ $campaign->sent_count > 0 ? round(($link->clicks / $campaign->sent_count) * 100, 1) : 0 }}% click rate
                                </p>
                            </div>
                            <div class="ml-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $link->clicks }} clicks
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-link text-3xl mb-2"></i>
                            <p>No clicks recorded yet</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Details & Devices -->
            <div class="space-y-6">
                <!-- Campaign Details -->
                <div class="bg-white rounded-xl shadow-soft p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Campaign Details</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Subject:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $campaign->subject }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Preview Text:</span>
                            <span class="text-sm text-gray-900">{{ $campaign->preview_text ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Created:</span>
                            <span class="text-sm text-gray-900">{{ $campaign->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        @if($campaign->scheduled_at)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Scheduled:</span>
                            <span class="text-sm text-gray-900">{{ $campaign->scheduled_at->format('M d, Y H:i') }}</span>
                        </div>
                        @endif
                        @if($campaign->started_at)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Started:</span>
                            <span class="text-sm text-gray-900">{{ $campaign->started_at->format('M d, Y H:i') }}</span>
                        </div>
                        @endif
                        @if($campaign->completed_at)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Completed:</span>
                            <span class="text-sm text-gray-900">{{ $campaign->completed_at->format('M d, Y H:i') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Total Recipients:</span>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($campaign->total_recipients) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Unique Opens:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $campaign->stats['unique_opens'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Unique Clicks:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $campaign->stats['unique_clicks'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Device Breakdown -->
                <div class="bg-white rounded-xl shadow-soft p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Device Breakdown</h2>
                    <div class="space-y-4">
                        @php
                        $totalDevices = array_sum($engagementData['devices']);
                        $deviceColors = [
                        'desktop' => '#3B82F6',
                        'mobile' => '#10B981',
                        'tablet' => '#8B5CF6',
                        'unknown' => '#9CA3AF'
                        ];
                        @endphp

                        @foreach($engagementData['devices'] as $device => $count)
                        @if($count > 0)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 capitalize">{{ $device }}</span>
                                <span class="text-gray-500">{{ $count }} ({{ $totalDevices > 0 ? round(($count / $totalDevices) * 100, 1) : 0 }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full"
                                    :style="`width: {{ $totalDevices > 0 ? ($count / $totalDevices) * 100 : 0 }}%; background-color: {{ $deviceColors[$device] ?? '#9CA3AF' }};`"></div>
                            </div>
                        </div>
                        @endif
                        @endforeach

                        @if(empty($engagementData['devices']) || $totalDevices == 0)
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-mobile-alt text-3xl mb-2"></i>
                            <p>No device data available</p>
                        </div>
                        @endif
                    </div>
                </div>

            <!-- Performance Score -->
<div class="gradient-blue rounded-xl shadow-soft p-6 text-white">
    <h2 class="text-lg font-semibold mb-2">Campaign Score</h2>
    <div class="text-center py-4">
        <div class="text-5xl font-bold mb-2">
            {{ $campaignScore }}/100
        </div>
        <div class="w-24 h-24 mx-auto relative">
            <svg class="w-full h-full" viewBox="0 0 100 100">
                <!-- Background circle -->
                <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="8" />
                
                <!-- Progress circle -->
                @php $score = $campaignScore; @endphp
                <circle cx="50" cy="50" r="45" fill="none" stroke="white" stroke-width="8"
                        stroke-dasharray="{{ (2 * pi() * 45) }}"
                        stroke-dashoffset="{{ (2 * pi() * 45) * (1 - $score / 100) }}"
                        stroke-linecap="round" transform="rotate(-90 50 50)" />
            </svg>
        </div>
        <p class="text-sm opacity-90 mt-4">
            Based on delivery, open rate, click rate, and engagement
        </p>
    </div>
</div>

            </div>
        </div>

        <!-- Hourly Engagement -->
        <div class="bg-white rounded-xl shadow-soft p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">Hourly Engagement Pattern</h2>
            <div class="chart-container" style="height: 200px;">
                <canvas id="hourlyChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity Logs -->
        <div class="bg-white rounded-xl shadow-soft">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Recent Activity</h2>
                <div class="flex items-center space-x-2">
                    <button @click="activityFilter = 'all'"
                        :class="activityFilter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                        class="px-3 py-1 rounded-lg text-sm transition">
                        All
                    </button>
                    <button @click="activityFilter = 'opens'"
                        :class="activityFilter === 'opens' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700'"
                        class="px-3 py-1 rounded-lg text-sm transition">
                        Opens
                    </button>
                    <button @click="activityFilter = 'clicks'"
                        :class="activityFilter === 'clicks' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700'"
                        class="px-3 py-1 rounded-lg text-sm transition">
                        Clicks
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscriber</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($campaign->logs as $log)
                        <tr x-show="matchesActivityFilter('{{ $log->opened_at ? 'opens' : ($log->clicked_at ? 'clicks' : 'sent') }}')"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ substr($log->subscriber?->email ?? "unknown", 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $log->subscriber?->email ?? 'unknown' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $log->subscriber->first_name ?? 'Subscriber' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->opened_at)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-envelope-open mr-1"></i> Opened
                                </span>
                                @elseif($log->clicked_at)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    <i class="fas fa-mouse-pointer mr-1"></i> Clicked
                                </span>
                                @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-paper-plane mr-1"></i> Sent
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($log->device_type == 'desktop')
                                    <i class="fas fa-desktop text-gray-400 mr-2"></i>
                                    <span class="text-sm text-gray-900">Desktop</span>
                                    @elseif($log->device_type == 'mobile')
                                    <i class="fas fa-mobile-alt text-gray-400 mr-2"></i>
                                    <span class="text-sm text-gray-900">Mobile</span>
                                    @elseif($log->device_type == 'tablet')
                                    <i class="fas fa-tablet-alt text-gray-400 mr-2"></i>
                                    <span class="text-sm text-gray-900">Tablet</span>
                                    @else
                                    <i class="fas fa-question-circle text-gray-400 mr-2"></i>
                                    <span class="text-sm text-gray-500">Unknown</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($log->clicked_at)
                                {{ $log->clicked_at->diffForHumans() }}
                                @elseif($log->opened_at)
                                {{ $log->opened_at->diffForHumans() }}
                                @else
                                {{ $log->sent_at->diffForHumans() }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($log->clicked_url)
                                <div class="tooltip">
                                    <span class="text-purple-600 hover:text-purple-800 cursor-pointer">
                                        <i class="fas fa-external-link-alt"></i> Link
                                    </span>
                                    <div class="tooltip-text">
                                        Clicked: {{ $log->clicked_url }}
                                    </div>
                                </div>
                                @elseif($log->opened_at)
                                <div class="tooltip">
                                    <span class="text-green-600 hover:text-green-800 cursor-pointer">
                                        <i class="fas fa-info-circle"></i> Details
                                    </span>
                                    <div class="tooltip-text">
                                        Opened from: {{ $log->ip_address ?? 'Unknown IP' }}
                                    </div>
                                </div>
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>No activity recorded yet</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Export Modal -->
    <div x-show="showExportModal"
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
                @click="showExportModal = false"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full"
                @click.stop>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Export Report
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button @click="exportFormat = 'pdf'"
                                    :class="exportFormat === 'pdf' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                                    class="px-4 py-2 rounded-lg text-sm transition">
                                    <i class="fas fa-file-pdf mr-2"></i> PDF
                                </button>
                                <button @click="exportFormat = 'excel'"
                                    :class="exportFormat === 'excel' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700'"
                                    class="px-4 py-2 rounded-lg text-sm transition">
                                    <i class="fas fa-file-excel mr-2"></i> Excel
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Include Data</label>
                            <div class="space-y-2">
                                @foreach(['overview', 'metrics', 'charts', 'links', 'activity'] as $section)
                                <label class="flex items-center">
                                    <input type="checkbox" x-model="exportSections.{{ $section }}"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700 capitalize">{{ $section }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="doExport"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Export Report
                    </button>
                    <button @click="showExportModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Helper function to calculate campaign score
        function calculateCampaignScore(campaign) {
            const deliveryRate = campaign.total_recipients > 0 ?
                (campaign.sent_count / campaign.total_recipients) * 100 : 0;
            const openRate = campaign.open_rate || 0;
            const clickRate = campaign.click_rate || 0;

            // Weighted scoring: 30% delivery, 40% open rate, 30% click rate
            return Math.round(
                (deliveryRate * 0.3) +
                (openRate * 0.4) +
                (clickRate * 0.3)
            );
        }

        // Initialize Hourly Engagement Chart
        const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
        const hourlyChart = new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: Array.from({
                    length: 24
                }, (_, i) => {
                    const hour = i % 12 || 12;
                    const ampm = i < 12 ? 'AM' : 'PM';
                    return `${hour} ${ampm}`;
                }),
                datasets: [{
                    label: 'Opens',
                    data: @json($engagementData['hours']),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: '#3B82F6',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Initialize Engagement Timeline Chart
        const engagementCtx = document.getElementById('engagementChart').getContext('2d');
        let engagementChart = null;

        function initEngagementChart(range = 'week') {
            if (engagementChart) {
                engagementChart.destroy();
            }

            const DateTime = luxon.DateTime;
            let labels = [];
            let opens = [];
            let clicks = [];

            // Generate data based on range
            if (range === 'day') {
                // Last 24 hours
                for (let i = 23; i >= 0; i--) {
                    const hour = DateTime.now().minus({
                        hours: i
                    });
                    labels.push(hour.toFormat('ha'));
                    // In real app, fetch data for each hour
                    opens.push(Math.floor(Math.random() * 20)); // Mock data
                    clicks.push(Math.floor(Math.random() * 10)); // Mock data
                }
            } else if (range === 'week') {
                // Last 7 days
                for (let i = 6; i >= 0; i--) {
                    const day = DateTime.now().minus({
                        days: i
                    });
                    labels.push(day.toFormat('EEE'));
                    opens.push(Math.floor(Math.random() * 100)); // Mock data
                    clicks.push(Math.floor(Math.random() * 50)); // Mock data
                }
            } else {
                // Last 30 days
                for (let i = 29; i >= 0; i--) {
                    const day = DateTime.now().minus({
                        days: i
                    });
                    labels.push(day.toFormat('MMM d'));
                    opens.push(Math.floor(Math.random() * 200)); // Mock data
                    clicks.push(Math.floor(Math.random() * 100)); // Mock data
                }
            }

            engagementChart = new Chart(engagementCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Opens',
                            data: opens,
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Clicks',
                            data: clicks,
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
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        // Alpine.js Application
        function reportApp() {
            return {
                timelineRange: 'week',
                activityFilter: 'all',
                showExportModal: false,
                exportFormat: 'pdf',
                exportSections: {
                    overview: true,
                    metrics: true,
                    charts: true,
                    links: true,
                    activity: true
                },

                init() {
                    // Initialize charts
                    initEngagementChart(this.timelineRange);

                    // Auto-refresh if campaign is still sending
                    @if($campaign -> status === 'sending')
                    this.startAutoRefresh();
                    @endif
                },

                matchesActivityFilter(action) {
                    if (this.activityFilter === 'all') return true;
                    return this.activityFilter === action;
                },

                exportReport() {
                    this.showExportModal = true;
                },

                async doExport() {
                    try {
                        // In a real app, this would make an API call to generate the report
                        const response = await fetch('/admin/newsletter/campaigns/{{ $campaign->id }}/export', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                format: this.exportFormat,
                                sections: this.exportSections
                            })
                        });

                        if (response.ok) {
                            const blob = await response.blob();
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `campaign-report-{{ $campaign->name }}-${new Date().toISOString().split('T')[0]}.${this.exportFormat}`;
                            document.body.appendChild(a);
                            a.click();
                            a.remove();

                            this.showExportModal = false;
                            this.showToast('Report exported successfully', 'success');
                        }
                    } catch (error) {
                        console.error('Export failed:', error);
                        this.showToast('Export failed', 'error');
                    }
                },

                printReport() {
                    window.print();
                },

                showToast(message, type = 'success') {
                    const toast = document.createElement('div');
                    toast.className = `fixed bottom-4 right-4 px-4 py-3 rounded shadow-lg text-white ${
                        type === 'success' ? 'bg-green-500' : 
                        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
                    }`;
                    toast.textContent = message;
                    toast.style.zIndex = '9999';

                    document.body.appendChild(toast);

                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                },

                startAutoRefresh() {
                    setInterval(() => {
                        window.location.reload();
                    }, 30000); // Refresh every 30 seconds for sending campaigns
                }
            };
        }

        // Initialize on page load
        document.addEventListener('alpine:init', () => {
            Alpine.data('reportApp', reportApp);
        });

        // Watch for timeline range changes
        document.addEventListener('alpine:initialized', () => {
            const app = Alpine.$data(document.querySelector('[x-data="reportApp()"]'));

            // Watch timelineRange for changes
            Alpine.effect(() => {
                if (app.timelineRange) {
                    initEngagementChart(app.timelineRange);
                }
            });
        });

        // Print styles
        @media print {
            header,
            .no - print {
                display: none!important;
            }

            body {
                background: white;
            }

            main {
                max - width: none;
                padding: 0;
            }

            .shadow - soft,
            .shadow {
                box - shadow: none!important;
                border: 1 px solid #e5e7eb;
            }

            .bg - gradient - to - br {
                background: #3b82f6 !important;
            }
            
            .text-white {
                color: # 111827!important;
            }
        }
    </script>
</body>

</html>