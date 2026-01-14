@extends('admin.layouts.master-layouts.plain')

@section('content')
<style>
    .diff-added {
        background-color: #dcfce7;
        color: #166534;
        padding: 0 2px;
        border-radius: 2px;
    }
    
    .diff-removed {
        background-color: #fee2e2;
        color: #991b1b;
        padding: 0 2px;
        border-radius: 2px;
        text-decoration: line-through;
    }
    
    .json-view {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 12px;
        line-height: 1.4;
    }
</style>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <i class="fas fa-home mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400"></i>
                        <a href="{{ route('admin.activity-logs.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            Activity Logs
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">Log Details</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Activity Log Details</h1>
                <p class="text-gray-600 dark:text-gray-400">Log ID: {{ $log->id }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Basic Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Log Summary Card -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Log Summary</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Basic information about this activity</p>
                        </div>
                        @php
                            $badgeClass = match($log->description) {
                                'created' => 'bg-emerald-100 text-emerald-800',
                                'updated' => 'bg-amber-100 text-amber-800',
                                'deleted' => 'bg-red-100 text-red-800',
                                'restored' => 'bg-blue-100 text-blue-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $badgeClass }}">
                            <i class="fas fa-{{ $log->description == 'created' ? 'plus' : ($log->description == 'updated' ? 'edit' : ($log->description == 'deleted' ? 'trash' : 'history')) }} mr-1"></i>
                            {{ ucfirst($log->description) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Timestamp</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white font-medium">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $log->created_at->diffForHumans() }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Model Type</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white font-medium">
                                {{ class_basename($log->subject_type) }}
                            </p>
                            @if($log->subject_id)
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                ID: {{ $log->subject_id }}
                            </p>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">IP Address</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                                {{ $log->ip ?? 'N/A' }}
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">User Agent</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white truncate" title="{{ $log->user_agent }}">
                                {{ Str::limit($log->user_agent, 50) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Changes Card -->
         <!-- Changes Card -->
<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Changes Made</h2>
    
    @php
        $properties = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
    @endphp
    
    @if(!empty($properties) && isset($properties['attributes']))
        <div class="space-y-4">
            @if(isset($properties['old']))
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Field</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Old Value</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">New Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($properties['attributes'] as $key => $newValue)
                                @php
                                    $oldValue = $properties['old'][$key] ?? null;
                                    $hasChanged = $oldValue != $newValue;
                                @endphp
                                @if($hasChanged)
                                <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-gray-900/50' : '' }}">
                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ Str::title(str_replace('_', ' ', $key)) }}
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <span class="diff-removed">{{ formatValueForDisplay($oldValue) }}</span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <span class="diff-added">{{ formatValueForDisplay($newValue) }}</span>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <!-- Show just the attributes if no old values -->
                <div class="space-y-2">
                    @foreach($properties['attributes'] as $key => $value)
                    <div class="flex items-start">
                        <span class="font-medium text-gray-700 dark:text-gray-300 w-1/3">{{ Str::title(str_replace('_', ' ', $key)) }}:</span>
                        <span class="text-gray-600 dark:text-gray-400 flex-1">{{ formatValueForDisplay($value) }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-8">
            <i class="fas fa-info-circle text-gray-400 text-3xl mb-3"></i>
            <p class="text-gray-500 dark:text-gray-400">No changes recorded for this action</p>
        </div>
    @endif
    
    <!-- Raw Properties -->
    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
        <details>
            <summary class="cursor-pointer text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                <i class="fas fa-code mr-1"></i> View Raw Data
            </summary>
            <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-900 rounded-md overflow-x-auto">
                <pre class="json-view text-xs">{{ json_encode($properties, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </details>
    </div>
</div>

@php
function formatValueForDisplay($value) {
    if (is_null($value)) return 'NULL';
    if (is_bool($value)) return $value ? 'Yes' : 'No';
    if (is_array($value)) return json_encode($value);
    if (is_object($value)) return 'Object';
    if (strlen($value) > 100) return Str::limit($value, 100) . '...';
    return $value;
}
@endphp
            </div>

            <!-- Right Column: Side Info -->
            <div class="space-y-6">
                <!-- Actor Card -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Actor Information</h2>
                    
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-medium text-lg">
                                {{ substr($log->causer->name ?? 'S', 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $log->causer->name ?? 'System' }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $log->causer->email ?? 'System Action' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Type: {{ $log->causer_type ? class_basename($log->causer_type) : 'System' }}
                            </p>
                        </div>
                    </div>
                    
                    @if($log->causer)
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="" class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                            <i class="fas fa-external-link-alt mr-1"></i> View Admin Profile
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Related Actions Card -->
                @if($relatedLogs->count() > 0)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Related Actions</h2>
                    
                    <div class="space-y-3">
                        @foreach($relatedLogs as $relatedLog)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-900">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ ucfirst($relatedLog->description) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $relatedLog->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('admin.activity-logs.show', $relatedLog->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Actions Card -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Actions</h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.activity-logs.index', ['subject_id' => $log->subject_id, 'model' => class_basename($log->subject_type)]) }}" 
                           class="flex items-center justify-between w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-800 rounded-lg">
                            <span>View all logs for this record</span>
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        
                        <button onclick="confirmDelete('{{ $log->id }}')" 
                                class="flex items-center justify-between w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg">
                            <span>Delete this log</span>
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(logId) {
    if (confirm('Are you sure you want to delete this log? This action cannot be undone.')) {
        fetch(`/admin/activity-logs/${logId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("admin.activity-logs.index") }}';
            } else {
                alert('Failed to delete log: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the log.');
        });
    }
}
</script>

@php

if (!function_exists('formatValueForDisplay')) {
    function formatValueForDisplay($value) {
        if (is_null($value)) return 'NULL';
        if (is_bool($value)) return $value ? 'Yes' : 'No';
        if (is_array($value)) return json_encode($value);
        if (is_object($value)) return 'Object';
        if (is_string($value) && strlen($value) > 100) {
            return \Illuminate\Support\Str::limit($value, 100);
        }
        return $value;
    }
}
@endphp

@endsection