<div class="bg-gray-100 min-h-screen p-4 sm:p-6">

    {{-- 1. STATISTICS SECTION --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Total Stats</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalStats ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                    <i class="fas fa-chart-bar text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Active STATISTICS</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $activeStats ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-green-100 rounded-full text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-gray-400">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Inactive Stats</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $inactiveStats ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-gray-100 rounded-full text-gray-500">
                    <i class="fas fa-eye-slash text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. MAIN CONTENT AREA --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Company Statistics</h3>
                <p class="text-sm text-gray-500">Manage the counters shown on the About page (e.g. 100+ Clients).</p>
            </div>
            <button onclick="openStatModal('add')" class="w-full sm:w-auto bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg shadow transition flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Add Statistic
            </button>
        </div>

        <div class="p-0">
            
            {{-- DESKTOP TABLE VIEW --}}
            <div class=" md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-4">Order</th>
                            <th class="px-6 py-4">Number Value</th>
                            <th class="px-6 py-4">Title & Description</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($stats ?? [] as $stat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">
                                #{{ $stat->order }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-lg font-bold text-purple-600">{{ $stat->number_value }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-800">{{ $stat->title }}</p>
                                <p class="text-sm text-gray-500 truncate w-64">{{ $stat->description }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($stat->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button onclick="openStatModal('edit', {{ json_encode($stat) }})" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.about-stats.delete', $stat->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                No statistics found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- MOBILE CARD VIEW --}}
            <div class="md:hidden grid grid-cols-1 gap-4 p-4">
                @forelse($stats ?? [] as $stat)
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm relative">
                    <div class="flex justify-between items-start mb-3">
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold">Order: {{ $stat->order }}</span>
                        @if($stat->is_active)
                            <span class="text-green-600 text-xs font-bold"><i class="fas fa-check-circle mr-1"></i> Active</span>
                        @else
                            <span class="text-gray-400 text-xs font-bold"><i class="fas fa-ban mr-1"></i> Inactive</span>
                        @endif
                    </div>
                    
                    <h4 class="text-2xl font-bold text-purple-600 mb-1">{{ $stat->number_value }}</h4>
                    <p class="font-bold text-gray-800">{{ $stat->title }}</p>
                    <p class="text-sm text-gray-500 mb-4">{{ $stat->description }}</p>
                    
                    <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                        <button onclick="openStatModal('edit', {{ json_encode($stat) }})" class="flex-1 bg-gray-50 text-gray-700 py-2 rounded-lg text-sm font-medium hover:bg-gray-100">Edit</button>
                        <form action="{{ route('admin.about-stats.delete', $stat->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-50 text-red-600 py-2 rounded-lg text-sm font-medium hover:bg-red-100">Delete</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center p-6 text-gray-500">No statistics found.</div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- 3. UNIFIED MODAL (ADD & EDIT) --}}
    <div id="statModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" onclick="closeStatModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl">
                
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold leading-6 text-gray-900" id="statModalTitle">Add Statistic</h3>
                        <button onclick="closeStatModal()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <form id="statForm" method="POST">
                    @csrf
                    {{-- Hidden Method Field for PUT --}}
                    <div id="statMethodField"></div>

                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Number Value</label>
                                <input type="text" name="number_value" id="stat_number_value" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-purple-500 outline-none" required placeholder="e.g. 500+">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                                <input type="number" name="order" id="stat_order" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-purple-500 outline-none" placeholder="e.g. 1">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title / Label</label>
                            <input type="text" name="title" id="stat_title" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-purple-500 outline-none" required placeholder="e.g. Satisfied Clients">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="stat_description" rows="3" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-purple-500 outline-none" placeholder="Brief explanation..."></textarea>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" id="stat_is_active" value="1" class="sr-only peer" checked>
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-700">Display this statistic</span>
                            </label>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-purple-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-500 sm:ml-3 sm:w-auto">
                            Save Statistic
                        </button>
                        <button type="button" onclick="closeStatModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 4. JAVASCRIPT LOGIC --}}
    <script>
    const statModal = document.getElementById('statModal');
    const statForm = document.getElementById('statForm');
    const statModalTitle = document.getElementById('statModalTitle');
    const statMethodField = document.getElementById('statMethodField');

    // ROUTES
    const statStoreRoute = "{{ route('admin.about-stats.store') }}";
    const statUpdateRouteBase = "{{ url('admin/about-stats') }}/"; 

    function openStatModal(mode, data = null) {
        statModal.classList.remove('hidden');

        if (mode === 'add') {
            // --- ADD MODE ---
            statForm.reset();
            statForm.action = statStoreRoute;
            statModalTitle.textContent = "Add Statistic";
            statMethodField.innerHTML = ''; // no _method for POST
            document.getElementById('stat_is_active').checked = true;

        } else if (mode === 'edit' && data) {
            // --- EDIT MODE ---
            statForm.action = statUpdateRouteBase + data.id; // append the ID
            statModalTitle.textContent = "Edit Statistic";

            // Inject PUT method hidden input
            statMethodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // Populate fields
            document.getElementById('stat_number_value').value = data.number_value || '';
            document.getElementById('stat_order').value = data.order || '';
            document.getElementById('stat_title').value = data.title || '';
            document.getElementById('stat_description').value = data.description || '';
            document.getElementById('stat_is_active').checked = data.is_active == 1;
        }
    }

    function closeStatModal() {
        statModal.classList.add('hidden');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") closeStatModal();
    });
</script>

</div>