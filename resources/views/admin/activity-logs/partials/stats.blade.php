<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8" id="statsContainer">
    <!-- Total Logs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-slate-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mr-4">
                <i class="fas fa-list-alt text-xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm text-slate-600 dark:text-gray-300 font-medium">Total Logs</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white" data-stat-total>{{ number_format($stats['total']) }}</h3>
            </div>
            <div class="text-xs text-slate-500 dark:text-gray-400">
                @if($stats['oldest_log'])
                Since {{ \Carbon\Carbon::parse($stats['oldest_log'])->format('M d, Y') }}
                @endif
            </div>
        </div>
    </div>
    
    <!-- Creations -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-slate-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 mr-4">
                <i class="fas fa-plus text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-slate-600 dark:text-gray-300 font-medium">Creations</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($stats['creations']) }}</h3>
            </div>
        </div>
        @if($stats['total'] > 0)
        <div class="mt-2">
            <div class="h-2 bg-slate-200 dark:bg-gray-700 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ ($stats['creations'] / $stats['total']) * 100 }}%"></div>
            </div>
            <div class="text-xs text-slate-500 dark:text-gray-400 mt-1 text-right">
                {{ round(($stats['creations'] / $stats['total']) * 100, 1) }}%
            </div>
        </div>
        @endif
    </div>
    
    <!-- Updates -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-slate-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 mr-4">
                <i class="fas fa-edit text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-slate-600 dark:text-gray-300 font-medium">Updates</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($stats['updates']) }}</h3>
            </div>
        </div>
        @if($stats['total'] > 0)
        <div class="mt-2">
            <div class="h-2 bg-slate-200 dark:bg-gray-700 rounded-full overflow-hidden">
                <div class="h-full bg-amber-500 rounded-full" style="width: {{ ($stats['updates'] / $stats['total']) * 100 }}%"></div>
            </div>
            <div class="text-xs text-slate-500 dark:text-gray-400 mt-1 text-right">
                {{ round(($stats['updates'] / $stats['total']) * 100, 1) }}%
            </div>
        </div>
        @endif
    </div>
    
    <!-- Deletions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-slate-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 mr-4">
                <i class="fas fa-trash text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-slate-600 dark:text-gray-300 font-medium">Deletions</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($stats['deletions']) }}</h3>
            </div>
        </div>
        @if($stats['total'] > 0)
        <div class="mt-2">
            <div class="h-2 bg-slate-200 dark:bg-gray-700 rounded-full overflow-hidden">
                <div class="h-full bg-rose-500 rounded-full" style="width: {{ ($stats['deletions'] / $stats['total']) * 100 }}%"></div>
            </div>
            <div class="text-xs text-slate-500 dark:text-gray-400 mt-1 text-right">
                {{ round(($stats['deletions'] / $stats['total']) * 100, 1) }}%
            </div>
        </div>
        @endif
    </div>
    
    <!-- Restorations -->
    <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-slate-200 dark:border-gray-700 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mr-4">
                <i class="fas fa-history text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-slate-600 dark:text-gray-300 font-medium">Restorations</p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($stats['restorations']) }}</h3>
            </div>
        </div>
        @if($stats['total'] > 0)
        <div class="mt-2">
            <div class="h-2 bg-slate-200 dark:bg-gray-700 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 rounded-full" style="width: {{ ($stats['restorations'] / $stats['total']) * 100 }}%"></div>
            </div>
            <div class="text-xs text-slate-500 dark:text-gray-400 mt-1 text-right">
                {{ round(($stats['restorations'] / $stats['total']) * 100, 1) }}%
            </div>
        </div>
        @endif
    </div>
</div>