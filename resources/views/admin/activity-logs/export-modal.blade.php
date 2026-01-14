<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" id="exportModal">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <!-- Modal header -->
            <div class="flex justify-between items-center pb-3 mb-3 border-b">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-file-export mr-2"></i> Export Activity Logs
                </h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal body -->
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
                               max="{{ min($maxExport, $totalCount) }}"
                               value="{{ min($totalCount, 1000) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Maximum: {{ min($maxExport, $totalCount) }} records available
                        ({{ $totalCount }} total with current filters)
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
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
                            Estimated: <span id="estimatedPages">{{ $estimatedPages }}</span> pages in PDF
                        </span>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" onclick="closeModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                    Cancel
                </button>
                <button type="button" onclick="submitExport()" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Export Now
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function closeModal() {
    document.getElementById('exportModal').remove();
}

function submitExport() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.activity-logs.export") }}';
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    // Add limit
    const limitInput = document.createElement('input');
    limitInput.type = 'hidden';
    limitInput.name = 'limit';
    limitInput.value = document.getElementById('exportLimit').value;
    form.appendChild(limitInput);
    
    // Add format
    const formatInput = document.createElement('input');
    formatInput.type = 'hidden';
    formatInput.name = 'format';
    formatInput.value = document.querySelector('input[name="format"]:checked').value;
    form.appendChild(formatInput);
    
    // Add options
    const includeChanges = document.createElement('input');
    includeChanges.type = 'hidden';
    includeChanges.name = 'include_changes';
    includeChanges.value = document.querySelector('input[name="include_changes"]').checked ? '1' : '0';
    form.appendChild(includeChanges);
    
    const includeIp = document.createElement('input');
    includeIp.type = 'hidden';
    includeIp.name = 'include_ip';
    includeIp.value = document.querySelector('input[name="include_ip"]').checked ? '1' : '0';
    form.appendChild(includeIp);
    
    // Add current filters
    @foreach($filters as $key => $value)
        @if(!empty($value))
            const input{{ $key }} = document.createElement('input');
            input{{ $key }}.type = 'hidden';
            input{{ $key }}.name = '{{ $key }}';
            input{{ $key }}.value = '{{ $value }}';
            form.appendChild(input{{ $key }});
        @endif
    @endforeach
    
    document.body.appendChild(form);
    form.submit();
}

// Update estimated pages when limit changes
document.getElementById('exportLimit').addEventListener('input', function() {
    const limit = parseInt(this.value) || 0;
    const estimated = Math.ceil(limit / 100);
    document.getElementById('estimatedPages').textContent = estimated;
});
</script>