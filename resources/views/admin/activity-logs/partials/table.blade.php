<!-- Table Content -->
<div class="overflow-x-auto custom-scrollbar">
    <table class="min-w-full divide-y divide-slate-200 dark:divide-gray-700 responsive-table">
        <thead class="bg-gradient-to-r from-blue-600 to-blue-700">
            <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider w-12">
                    <!-- Checkbox column -->
                </th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Admin</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Action</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Model / ID</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Changes</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">IP / Device</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Date & Time</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider w-24">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
            @forelse($logs as $log)
            <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/50 transition-colors duration-150 group fade-in" data-log-id="{{ $log->id }}">
                <!-- Checkbox -->
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox" value="{{ $log->id }}" class="log-checkbox rounded border-slate-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                </td>

                <!-- Admin -->
                <td class="px-6 py-4 whitespace-nowrap" data-label="Admin">
                    <div class="flex items-center">
                        <div class="h-9 w-9 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-medium text-sm mr-3 shadow">
                            {{ substr($log->causer->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $log->causer->name ?? 'System' }}</div>
                            <div class="text-xs text-slate-500 dark:text-gray-400">{{ $log->causer->email ?? 'System Action' }}</div>
                        </div>
                    </div>
                </td>

                <!-- Action -->
                <td class="px-6 py-4 whitespace-nowrap" data-label="Action">
                    @php
                    $actionClass = match($log->description) {
                    'created' => 'bg-emerald-100 text-emerald-800 border border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800',
                    'updated' => 'bg-amber-100 text-amber-800 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
                    'deleted' => 'bg-rose-100 text-rose-800 border border-rose-200 dark:bg-rose-900/30 dark:text-rose-300 dark:border-rose-800',
                    'restored' => 'bg-blue-100 text-blue-800 border border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                    default => 'bg-slate-100 text-slate-800 border border-slate-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600'
                    };
                    @endphp
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium capitalize {{ $actionClass }}">
                        <i class="fas fa-{{ $log->description == 'created' ? 'plus' : ($log->description == 'updated' ? 'edit' : ($log->description == 'deleted' ? 'trash' : 'history')) }} text-xs"></i>
                        {{ $log->description }}
                    </span>
                </td>

                <!-- Model -->
                <td class="px-6 py-4 whitespace-nowrap" data-label="Model / ID">
                    <div class="space-y-1">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-gray-700 text-slate-800 dark:text-gray-300 border border-slate-200 dark:border-gray-600">
                            {{ $log->subject_type ? class_basename($log->subject_type) : '-' }}
                        </span>
                        @if($log->subject_id)
                        <div class="text-xs text-slate-500 dark:text-gray-400 font-mono">
                            ID: {{ $log->subject_id }}
                        </div>
                        @endif
                    </div>
                </td>

                <!-- Changes -->
                <td class="px-6 py-4 text-sm text-slate-700 dark:text-gray-300 max-w-xs" data-label="Changes">
                    @php
                    $properties = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
                    @endphp
                    @if(!empty($properties) && isset($properties['attributes']) && count($properties['attributes']))
                    <div class="space-y-1">
                        @php
                        $attributes = $properties['attributes'];
                        $changes = collect($attributes)->take(2);
                        @endphp
                        @foreach($changes as $key => $value)
                        <div class="flex items-start gap-2">
                            <span class="font-medium text-slate-600 dark:text-gray-400 text-xs bg-slate-100 dark:bg-gray-700 px-2 py-1 rounded min-w-[80px]">{{ Str::title(str_replace('_', ' ', $key)) }}</span>
                            <span class="text-slate-700 dark:text-gray-300 flex-1 truncate" title="{{ is_array($value) ? json_encode($value) : $value }}">
                                @if(is_array($value))
                                {{ json_encode($value) }}
                                @elseif(is_bool($value))
                                {{ $value ? 'Yes' : 'No' }}
                                @else
                                {{ Str::limit($value, 30) }}
                                @endif
                            </span>
                        </div>
                        @endforeach
                        @if(count($attributes) > 2)
                        <button type="button" onclick="viewLogDetails({{ $log->id }})" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                            +{{ count($attributes) - 2 }} more changes
                        </button>
                        @endif
                    </div>
                    @else
                    <span class="text-slate-400 dark:text-gray-500 italic">No changes recorded</span>
                    @endif
                </td>

                <!-- IP & Device -->
                <td class="px-6 py-4 whitespace-nowrap" data-label="IP / Device">
                    <div class="space-y-1">
                        <div class="text-sm text-slate-600 dark:text-gray-400 font-mono font-medium">
                            {{ $log->ip ?? '-' }}
                        </div>
                        @if($log->user_agent)
                        <div class="text-xs text-slate-500 dark:text-gray-500 truncate max-w-[150px]" title="{{ $log->user_agent }}">
                            <i class="fas fa-laptop mr-1"></i>
                            {{ parseUserAgent($log->user_agent) }}
                        </div>
                        @endif
                    </div>
                </td>

                <!-- Date & Time -->
                <td class="px-6 py-4 whitespace-nowrap" data-label="Date & Time">
                    <div class="space-y-1">
                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                            {{ $log->created_at->format('d M Y') }}
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-gray-400">
                            <i class="far fa-clock"></i>
                            {{ $log->created_at->format('H:i:s') }}
                        </div>
                        <div class="text-xs text-slate-400 dark:text-gray-500">
                            {{ $log->created_at->diffForHumans() }}
                        </div>
                    </div>
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 whitespace-nowrap text-right" data-label="Actions">
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="viewLogDetails({{ $log->id }})" class="p-1.5 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" onclick="confirmDelete({{ $log->id }})" class="p-1.5 text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center text-slate-400 dark:text-gray-500">
                        <div class="p-4 rounded-full bg-slate-100 dark:bg-gray-800 mb-3">
                            <i class="fas fa-inbox text-3xl"></i>
                        </div>
                        <p class="text-lg font-medium text-slate-500 dark:text-gray-400">No activity logs found</p>
                        <p class="text-sm text-slate-400 dark:text-gray-500 mt-1">
                            @if($hasFilters)
                            Try adjusting your filters or <a href="{{ route('admin.activity-logs.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">clear all filters</a>
                            @else
                            No activities have been recorded yet
                            @endif
                        </p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>



</div>


<div style="padding: 16px; border-top: 1px solid #ccc; background-color: #f9f9f9;">
    <div style="display: flex; flex-direction: column; gap: 8px;">
        <!-- Showing X to Y of Z -->
        <div style="font-size: 14px; color: #555;">
            Showing <strong>{{ $logs->firstItem() }}</strong> to 
            <strong>{{ $logs->lastItem() }}</strong> of 
            <strong>{{ $logs->total() }}</strong> results
        </div>

        <!-- Pagination links -->
        @if ($logs->hasPages())
        <div style="margin-top: 8px;">
            <ul style="list-style: none; padding: 0; display: flex; gap: 4px; flex-wrap: wrap;">
                {{-- Previous Page Link --}}
                @if ($logs->onFirstPage())
                    <li style="padding: 6px 12px; color: #999; border: 1px solid #ccc;">&laquo; Previous</li>
                @else
                    <li style="padding: 6px 12px; border: 1px solid #ccc;">
                        <a href="{{ $logs->previousPageUrl() }}" style="text-decoration: none; color: #007bff;">&laquo; Previous</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($logs->links()->elements[0] ?? [] as $page => $url)
                    @if ($page == $logs->currentPage())
                        <li style="padding: 6px 12px; background-color: #007bff; color: #fff; border: 1px solid #ccc;">
                            {{ $page }}
                        </li>
                    @else
                        <li style="padding: 6px 12px; border: 1px solid #ccc;">
                            <a href="{{ $url }}" style="text-decoration: none; color: #007bff;">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($logs->hasMorePages())
                    <li style="padding: 6px 12px; border: 1px solid #ccc;">
                        <a href="{{ $logs->nextPageUrl() }}" style="text-decoration: none; color: #007bff;">Next &raquo;</a>
                    </li>
                @else
                    <li style="padding: 6px 12px; color: #999; border: 1px solid #ccc;">Next &raquo;</li>
                @endif
            </ul>
        </div>
        @endif
    </div>
</div>



    @php


    function parseUserAgent($userAgent) {
    if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
    if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
    if (strpos($userAgent, 'Safari') !== false) return 'Safari';
    if (strpos($userAgent, 'Edge') !== false) return 'Edge';
    if (strpos($userAgent, 'Opera') !== false) return 'Opera';
    if (strpos($userAgent, 'Postman') !== false) return 'API Client';
    if (strpos($userAgent, 'Mobile') !== false) return 'Mobile';
    if (strpos($userAgent, 'Android') !== false) return 'Android';
    if (strpos($userAgent, 'iPhone') !== false) return 'iPhone';
    return 'Unknown Browser';
    }
    @endphp