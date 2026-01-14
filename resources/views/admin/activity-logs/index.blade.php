@extends('admin.layouts.master-layouts.plain')

@section('content')
<style>
    /* Custom animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideIn {
        from { transform: translateX(-20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .fade-in { animation: fadeIn 0.3s ease-out; }
    .slide-in { animation: slideIn 0.2s ease-out; }
    
    /* Loading overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        display: none;
    }
    
    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Responsive table */
    @media (max-width: 768px) {
        .responsive-table {
            display: block;
        }
        .responsive-table thead {
            display: none;
        }
        .responsive-table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
        }
        .responsive-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border: none;
            border-bottom: 1px solid #f3f4f6;
        }
        .responsive-table tbody td:last-child {
            border-bottom: none;
        }
        .responsive-table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #6b7280;
            min-width: 120px;
        }
    }
    
    /* Custom scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<!-- Export Modal -->
<div id="exportModal" class="export-modal hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Export Activity Logs</h3>
                <button type="button" onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="exportForm" onsubmit="handleExport(event)">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Number of records to export
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="limit" 
                                   id="exportLimit" 
                                   min="1" 
                                   max="5000"
                                   value="{{ min($totalLogs, 1000) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Maximum: 5000 records
                                (<span id="availableLogs">{{ $totalLogs }}</span> available with current filters)
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Export Format
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="inline-flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                <input type="radio" name="format" value="pdf" checked class="form-radio text-blue-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-file-pdf text-red-500 mr-1"></i> PDF
                                </span>
                            </label>
                            <label class="inline-flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                <input type="radio" name="format" value="csv" class="form-radio text-blue-600">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-file-csv text-green-500 mr-1"></i> CSV
                                </span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="include_changes" checked class="form-checkbox text-blue-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Include changes details</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="include_ip" checked class="form-checkbox text-blue-600">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Include IP addresses</span>
                        </label>
                    </div>
                    
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <span class="text-sm text-blue-700 dark:text-blue-300">
                                Estimated file size: <span id="estimatedSize">~1.5 MB</span>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="closeExportModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm flex items-center">
                        <i class="fas fa-download mr-2"></i>
                        Export Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div id="bulkDeleteModal" class="export-modal hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Confirm Delete</h3>
                <button type="button" onclick="closeBulkDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Are you sure you want to delete <span id="selectedCountText">0</span> selected logs? This action cannot be undone.
            </p>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeBulkDeleteModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    Cancel
                </button>
                <button type="button" onclick="performBulkDelete()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md shadow-sm flex items-center">
                    <i class="fas fa-trash mr-2"></i>
                    Delete Selected
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    initTooltips();
    
    // Initialize advanced filters toggle
    const advancedToggle = document.getElementById('advancedToggle');
    const advancedFilters = document.getElementById('advancedFilters');
    
    if (advancedToggle && advancedFilters) {
        advancedToggle.addEventListener('click', function() {
            advancedFilters.classList.toggle('hidden');
            const icon = this.querySelector('i.fa-chevron-down, i.fa-chevron-up');
            if (icon) {
                if (icon.classList.contains('fa-chevron-down')) {
                    icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                } else {
                    icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                }
            }
        });
    }
    
    // Quick filter buttons - UPDATED for AJAX
    document.querySelectorAll('[data-filter-action]').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.filterAction;
            const model = this.dataset.filterModel;
            const days = this.dataset.filterDays;
            
            let params = {
                action: action || '',
                model: model || '',
                ajax: 1
            };
            
            if (days) {
                const date = new Date();
                date.setDate(date.getDate() - parseInt(days));
                params.date_from = date.toISOString().split('T')[0];
            }
            
            // Remove empty params
            Object.keys(params).forEach(key => {
                if (!params[key]) delete params[key];
            });
            
            loadLogs(params);
        });
    });
    
    // Live search with debounce
    const searchInput = document.getElementById('liveSearch');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const form = document.getElementById('advancedFilters');
                const formData = new FormData(form);
                const params = Object.fromEntries(formData.entries());
                params.ajax = 1;
                
                loadLogs(params);
            }, 500);
        });
    }
    
    // Form submit handler
    const filterForm = document.getElementById('advancedFilters');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const params = Object.fromEntries(formData.entries());
            params.ajax = 1;
            
            loadLogs(params);
        });
    }
    
    // Export limit calculation
    const exportLimit = document.getElementById('exportLimit');
    if (exportLimit) {
        exportLimit.addEventListener('input', function() {
            calculateEstimatedSize(this.value);
        });
        calculateEstimatedSize(exportLimit.value);
    }
    
    // Bulk selection
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('log-checkbox')) {
            updateBulkActions();
            updateSelectAllCheckbox();
        }
    });
});

function initTooltips() {
    // Initialize tooltips using Tippy.js or similar
    if (typeof tippy !== 'undefined') {
        tippy('[data-tippy-content]', {
            arrow: true,
            animation: 'shift-away',
            theme: 'light-border'
        });
    }
}

function showLoading() {
    document.getElementById('loadingOverlay').style.display = 'flex';
}

function hideLoading() {
    document.getElementById('loadingOverlay').style.display = 'none';
}

// AJAX function to load logs
function loadLogs(params = {}) {
    showLoading();
    
    const url = new URL('{{ route("admin.activity-logs.index") }}');
    Object.keys(params).forEach(key => {
        if (params[key]) {
            url.searchParams.set(key, params[key]);
        }
    });
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update table
            document.getElementById('tableContainer').innerHTML = data.html;
            
            // Update stats
            document.getElementById('statsContainer').innerHTML = data.statsHtml;
            
            // Update pagination info
            const paginationInfo = document.querySelector('.pagination-info');
            if (paginationInfo) {
                paginationInfo.innerHTML = `Showing ${data.firstItem} to ${data.lastItem} of ${data.total} logs`;
            }
            
            // Update available logs for export
            const availableLogs = document.getElementById('availableLogs');
            if (availableLogs) {
                availableLogs.textContent = data.total;
            }
            
            // Update URL without reloading page
            const newUrl = new URL(window.location.href);
            Object.keys(params).forEach(key => {
                if (params[key] && key !== 'ajax') {
                    newUrl.searchParams.set(key, params[key]);
                } else {
                    newUrl.searchParams.delete(key);
                }
            });
            newUrl.searchParams.delete('page');
            window.history.pushState({}, '', newUrl);
            
            showToast('Logs updated successfully', 'success');
        } else {
            showToast(data.message || 'Failed to load logs', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while loading logs', 'error');
    })
    .finally(() => {
        hideLoading();
    });
}

// Handle pagination links
document.addEventListener('click', function(e) {
    if (e.target.closest('.pagination a')) {
        e.preventDefault();
        const url = e.target.closest('a').href;
        const urlObj = new URL(url);
        const params = Object.fromEntries(urlObj.searchParams.entries());
        params.ajax = 1;
        
        loadLogs(params);
    }
});

function updateSelectAllCheckbox() {
    const checkboxes = document.querySelectorAll('.log-checkbox');
    const selectAll = document.getElementById('selectAll');
    if (!selectAll) return;
    
    const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    
    if (checkedCount === 0) {
        selectAll.checked = false;
        selectAll.indeterminate = false;
    } else if (checkedCount === checkboxes.length) {
        selectAll.checked = true;
        selectAll.indeterminate = false;
    } else {
        selectAll.checked = false;
        selectAll.indeterminate = true;
    }
}

function updateBulkActions() {
    const checkedCount = document.querySelectorAll('.log-checkbox:checked').length;
    const bulkActions = document.getElementById('bulkActions');
    
    if (!bulkActions) return;
    
    if (checkedCount > 0) {
        bulkActions.classList.remove('hidden');
        document.getElementById('selectedCount').textContent = checkedCount;
    } else {
        bulkActions.classList.add('hidden');
    }
}

function showExportModal() {
    const modal = document.getElementById('exportModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeExportModal() {
    const modal = document.getElementById('exportModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

async function handleExport(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Add current filter parameters
    const currentParams = new URLSearchParams(window.location.search);
    currentParams.forEach((value, key) => {
        if (key !== 'page' && key !== 'ajax' && value) {
            formData.append(key, value);
        }
    });
    
    showLoading();
    
    try {
        const response = await fetch('{{ route("admin.activity-logs.export") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            
            // Get filename from response headers
            const contentDisposition = response.headers.get('content-disposition');
            let filename = 'activity-logs-export';
            if (contentDisposition) {
                const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                if (filenameMatch && filenameMatch[1]) {
                    filename = filenameMatch[1];
                }
            }
            
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            showToast('Export completed successfully', 'success');
            closeExportModal();
        } else {
            const data = await response.json();
            showToast(data.message || 'Export failed', 'error');
        }
    } catch (error) {
        console.error('Export error:', error);
        showToast('Export failed: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
}

function showBulkDeleteModal() {
    const checkedCount = document.querySelectorAll('.log-checkbox:checked').length;
    if (checkedCount === 0) {
        showToast('Please select at least one log to delete.', 'warning');
        return;
    }
    
    document.getElementById('selectedCountText').textContent = checkedCount;
    const modal = document.getElementById('bulkDeleteModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeBulkDeleteModal() {
    const modal = document.getElementById('bulkDeleteModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

async function performBulkDelete() {
    const selectedIds = Array.from(document.querySelectorAll('.log-checkbox:checked'))
        .map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        showToast('No logs selected.', 'warning');
        return;
    }
    
    showLoading();
    
    try {
        const response = await fetch('{{ route("admin.activity-logs.bulk-delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ids: selectedIds })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            // Reload logs
            const currentParams = new URLSearchParams(window.location.search);
            const params = Object.fromEntries(currentParams.entries());
            params.ajax = 1;
            loadLogs(params);
        } else {
            showToast(data.message || 'Failed to delete logs.', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred while deleting logs.', 'error');
    } finally {
        closeBulkDeleteModal();
        hideLoading();
    }
}

function confirmDelete(logId) {
    if (confirm('Are you sure you want to delete this log?')) {
        showLoading();
        
        fetch(`/admin/activity-logs/${logId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Log deleted successfully.', 'success');
                // Reload logs
                const currentParams = new URLSearchParams(window.location.search);
                const params = Object.fromEntries(currentParams.entries());
                params.ajax = 1;
                loadLogs(params);
            } else {
                showToast(data.message || 'Failed to delete log.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while deleting the log.', 'error');
        })
        .finally(() => {
            hideLoading();
        });
    }
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white z-50 transform transition-transform duration-300 ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        type === 'warning' ? 'bg-yellow-500' :
        'bg-blue-500'
    }`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${
                type === 'success' ? 'fa-check-circle' :
                type === 'error' ? 'fa-exclamation-circle' :
                type === 'warning' ? 'fa-exclamation-triangle' :
                'fa-info-circle'
            } mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function calculateEstimatedSize(recordCount) {
    const format = document.querySelector('input[name="format"]:checked')?.value || 'pdf';
    const sizePerRecord = format === 'pdf' ? 2 : 1;
    const estimatedKB = Math.round((recordCount * sizePerRecord) / 1024 * 10) / 10;
    const estimatedMB = Math.round((estimatedKB / 1024) * 10) / 10;
    
    const sizeElement = document.getElementById('estimatedSize');
    if (estimatedMB >= 1) {
        sizeElement.textContent = `~${estimatedMB} MB`;
    } else {
        sizeElement.textContent = `~${estimatedKB} KB`;
    }
}

function viewLogDetails(logId) {
    window.location.href = `/admin/activity-logs/${logId}`;
}

function refreshData() {
    const currentParams = new URLSearchParams(window.location.search);
    const params = Object.fromEntries(currentParams.entries());
    params.ajax = 1;
    params.refresh = Date.now();
    
    loadLogs(params);
}

function clearAllFilters() {
    loadLogs({ ajax: 1 });
}

// Close modals on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeExportModal();
        closeBulkDeleteModal();
    }
});

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    const exportModal = document.getElementById('exportModal');
    const bulkDeleteModal = document.getElementById('bulkDeleteModal');
    
    if (exportModal && !exportModal.classList.contains('hidden') && 
        !exportModal.contains(e.target) && !e.target.closest('[onclick="showExportModal()"]')) {
        closeExportModal();
    }
    
    if (bulkDeleteModal && !bulkDeleteModal.classList.contains('hidden') && 
        !bulkDeleteModal.contains(e.target) && !e.target.closest('[onclick="showBulkDeleteModal()"]')) {
        closeBulkDeleteModal();
    }
});
</script>

<!-- Page Header -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between mb-8 gap-4">
    <div class="space-y-2">
        <h1 class="text-3xl font-bold text-slate-800 dark:text-white">Activity Logs</h1>
        <p class="text-slate-600 dark:text-gray-300">Monitor system activities and administrator actions in real-time</p>
        
        @if($hasFilters)
        <div class="flex flex-wrap items-center gap-2 text-sm">
            <span class="text-slate-500 dark:text-gray-400">Active filters:</span>
            @foreach($filters as $key => $value)
                @if(!empty($value) && !in_array($key, ['per_page', 'page', '_token', 'ajax']))
                <span class="inline-flex items-center gap-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 px-2 py-1 rounded-full text-xs">
                    {{ str_replace('_', ' ', $key) }}: {{ $value }}
                    <button onclick="removeFilter('{{ $key }}')" class="hover:text-blue-600 dark:hover:text-blue-200 ml-1">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </span>
                @endif
            @endforeach
            <button onclick="clearAllFilters()" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm">
                <i class="fas fa-times mr-1"></i> Clear all
            </button>
        </div>
        @endif
    </div>
    
    <!-- Action Buttons -->
    <div class="flex flex-wrap items-center gap-3">
        <!-- Export Button -->
        <button type="button" onclick="showExportModal()" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
            <i class="fas fa-file-export"></i>
            Export
        </button>
        
        <!-- Refresh Button -->
        <button onclick="refreshData()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
            <i class="fas fa-sync-alt"></i>
            Refresh
        </button>
        
        <!-- Bulk Actions -->
        <div id="bulkActions" class="hidden">
            <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <span class="text-blue-700 dark:text-blue-300 font-medium">
                    <span id="selectedCount">0</span> logs selected
                </span>
                <button onclick="showBulkDeleteModal()" class="text-sm bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded flex items-center">
                    <i class="fas fa-trash mr-1"></i> Delete Selected
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards Container -->
<div id="statsContainer">
    @include('admin.activity-logs.partials.stats')
</div>

<!-- Quick Filters -->
<div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 rounded-xl border border-blue-200 dark:border-gray-700">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <span class="text-sm font-medium text-slate-700 dark:text-gray-300">Quick Filters:</span>
        <div class="flex flex-wrap gap-2">
            <button data-filter-action="created" data-filter-days="0" class="px-3 py-1.5 bg-emerald-100 hover:bg-emerald-200 dark:bg-emerald-900/30 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-1"></i> Today's Creations
            </button>
            <button data-filter-action="deleted" data-filter-days="7" class="px-3 py-1.5 bg-rose-100 hover:bg-rose-200 dark:bg-rose-900/30 dark:hover:bg-rose-900/50 text-rose-700 dark:text-rose-300 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-trash mr-1"></i> Recent Deletions
            </button>
            <button data-filter-days="7" class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-calendar-week mr-1"></i> Last 7 Days
            </button>
            <button data-filter-model="User" class="px-3 py-1.5 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900/30 dark:hover:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-users mr-1"></i> User Actions
            </button>
            <button data-filter-model="Order" class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 dark:bg-amber-900/30 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-300 rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-shopping-cart mr-1"></i> Order Actions
            </button>
        </div>
        @if($hasFilters)
        <button onclick="clearAllFilters()" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-slate-700 dark:text-gray-300 rounded-lg text-sm font-medium transition-colors">
            <i class="fas fa-times mr-1"></i> Clear All
        </button>
        @endif
    </div>
</div>

<!-- Advanced Filters -->
<button type="button" id="advancedToggle" class="w-full mb-4 p-3 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-lg hover:bg-slate-50 dark:hover:bg-gray-700 transition-colors flex items-center justify-between">
    <div class="flex items-center gap-2">
        <i class="fas fa-sliders-h text-blue-600 dark:text-blue-400"></i>
        <span class="font-medium text-slate-700 dark:text-gray-300">Advanced Filters</span>
        @if($hasFilters)
        <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 text-xs px-2 py-1 rounded-full">
            Active
        </span>
        @endif
    </div>
    <i class="fas fa-chevron-down text-slate-400"></i>
</button>

<form method="GET" action="{{ route('admin.activity-logs.index') }}" 
      id="advancedFilters" 
      class="{{ $hasFilters ? '' : 'hidden' }} mb-8 bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-gray-700">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Model Filter -->
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">
                <i class="fas fa-cube mr-1"></i> Model Type
            </label>
            <select name="model" class="w-full bg-white dark:bg-gray-700 border border-slate-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200">
                <option value="">All Models</option>
                @foreach($models as $model)
                    <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                        {{ $model }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Action Type -->
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">
                <i class="fas fa-bolt mr-1"></i> Action Type
            </label>
            <select name="action" class="w-full bg-white dark:bg-gray-700 border border-slate-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200">
                <option value="">All Actions</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                        {{ ucfirst($action) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Admin Filter -->
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">
                <i class="fas fa-user-shield mr-1"></i> Admin User
            </label>
            <select name="user_id" class="w-full bg-white dark:bg-gray-700 border border-slate-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200">
                <option value="">All Admins</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin['id'] }}" {{ request('user_id') == $admin['id'] ? 'selected' : '' }}>
                        {{ $admin['name'] }} ({{ $admin['activity_count'] }} actions)
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Per Page -->
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">
                <i class="fas fa-list-ol mr-1"></i> Items Per Page
            </label>
            <select name="per_page" class="w-full bg-white dark:bg-gray-700 border border-slate-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200">
                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 per page</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200 per page</option>
            </select>
        </div>

        <!-- Date Range -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">
                <i class="fas fa-calendar-alt mr-1"></i> Date Range
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full bg-white dark:bg-gray-700 border border-slate-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200"
                           placeholder="From Date">
                </div>
                <div>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full bg-white dark:bg-gray-700 border border-slate-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200"
                           placeholder="To Date">
                </div>
            </div>
        </div>

        <!-- IP Address Filter -->
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">
                <i class="fas fa-network-wired mr-1"></i> IP Address
            </label>
            <input type="text" name="ip" value="{{ request('ip') }}" 
                   placeholder="Enter IP address"
                   class="w-full bg-white dark:bg-gray-700 border border-slate-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200">
        </div>

        <!-- Model ID Filter -->
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">
                <i class="fas fa-hashtag mr-1"></i> Model ID
            </label>
            <input type="number" name="subject_id" value="{{ request('subject_id') }}" 
                   placeholder="Enter Model ID"
                   class="w-full bg-white dark:bg-gray-700 border border-slate-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200">
        </div>

        <!-- Live Search -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">
                <i class="fas fa-search mr-1"></i> Live Search
            </label>
            <div class="relative">
                <input type="text" name="search" id="liveSearch" value="{{ request('search') }}" 
                       placeholder="Search logs by admin, action, IP, or changes..." 
                       class="w-full bg-white dark:bg-gray-700 border border-slate-300 dark:border-gray-600 rounded-lg px-4 py-2.5 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-gray-200">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex flex-wrap justify-end gap-3 mt-6 pt-6 border-t border-slate-200 dark:border-gray-700">
        <button type="reset" class="px-5 py-2.5 border border-slate-300 dark:border-gray-600 text-slate-700 dark:text-gray-300 rounded-lg font-medium hover:bg-slate-50 dark:hover:bg-gray-700 transition-colors">
            <i class="fas fa-redo mr-2"></i> Reset
        </button>
        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-2">
            <i class="fas fa-filter"></i>
            Apply Filters
        </button>
    </div>
</form>

<!-- Logs Table Container -->
<div id="tableContainer">
    @include('admin.activity-logs.partials.table')
</div>

<!-- Help Section -->
<div class="mt-8 p-6 bg-gradient-to-r from-slate-50 to-blue-50 dark:from-gray-800 dark:to-gray-900 rounded-xl border border-slate-200 dark:border-gray-700">
    <div class="flex flex-col md:flex-row items-start justify-between gap-4">
        <div class="space-y-2 flex-1">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-2"></i>
                About Activity Logs
            </h3>
            <p class="text-sm text-slate-600 dark:text-gray-400">
                Activity logs track all administrative actions in the system. You can filter by model, action type, date range, or specific admin users.
            </p>
            <ul class="text-xs text-slate-500 dark:text-gray-500 space-y-1 mt-2">
                <li><i class="fas fa-circle text-emerald-500 mr-1"></i> <strong>Created</strong>: New records added to the system</li>
                <li><i class="fas fa-circle text-amber-500 mr-1"></i> <strong>Updated</strong>: Existing records modified</li>
                <li><i class="fas fa-circle text-rose-500 mr-1"></i> <strong>Deleted</strong>: Records removed (soft delete)</li>
                <li><i class="fas fa-circle text-blue-500 mr-1"></i> <strong>Restored</strong>: Soft-deleted records recovered</li>
            </ul>
        </div>
        <div class="text-right space-y-2">
            <div class="text-sm text-slate-600 dark:text-gray-400">
                <i class="fas fa-clock mr-1"></i> Logs are retained for {{ config('activitylog.delete_records_older_than_days', 90) }} days
            </div>
            <div class="flex gap-2">
                <button onclick="clearOldLogs()" class="text-sm text-amber-600 dark:text-amber-400 hover:underline">
                    <i class="fas fa-broom mr-1"></i> Clear Old Logs
                </button>
                <span class="text-slate-400">|</span>
                <button onclick="showExportModal()" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                    <i class="fas fa-download mr-1"></i> Export All
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function removeFilter(key) {
    const currentParams = new URLSearchParams(window.location.search);
    currentParams.delete(key);
    const params = Object.fromEntries(currentParams.entries());
    params.ajax = 1;
    loadLogs(params);
}

async function clearOldLogs() {
    const days = prompt('Enter number of days (logs older than this will be deleted):', '30');
    if (!days || isNaN(days) || days < 1 || days > 365) {
        showToast('Please enter a valid number between 1 and 365', 'warning');
        return;
    }
    
    if (!confirm(`Are you sure you want to delete logs older than ${days} days? This action cannot be undone.`)) {
        return;
    }
    
    showLoading();
    
    try {
        const response = await fetch('{{ route("admin.activity-logs.clear-old") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ days: parseInt(days) })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message, 'success');
            // Reload logs
            const currentParams = new URLSearchParams(window.location.search);
            const params = Object.fromEntries(currentParams.entries());
            params.ajax = 1;
            loadLogs(params);
        } else {
            showToast(data.message || 'Failed to clear old logs.', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('An error occurred while clearing old logs.', 'error');
    } finally {
        hideLoading();
    }
}
</script>
@endsection