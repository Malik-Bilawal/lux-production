<div class="bg-gray-100 min-h-screen p-4 sm:p-6">
    
    {{-- 1. STATISTICS SECTION --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Total Pages</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $total ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                    <i class="fas fa-layer-group text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Active Headers</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $active ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-green-100 rounded-full text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-gray-400">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Inactive</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $inactive ?? 0 }}</h3>
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
            <h3 class="text-lg font-bold text-gray-800">Page Heroes / Headers</h3>
            <p class="text-sm text-gray-500">Manage the hero section for specific site pages.</p>
        </div>
        <button onclick="openModal('add')" 
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow transition flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i> Add New Header
        </button>
    </div>

    <div class="p-0">

        {{-- DESKTOP TABLE VIEW --}}
        <div class="sm-hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">Page Type</th>
                        <th class="px-6 py-4">Heading Info</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($headers ?? [] as $header)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4">
                            <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                                {{ str_replace('_', ' ', $header->page_type) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-xs text-blue-600 font-bold mb-1">{{ $header->eyebrow_text }}</p>
                            <p class="text-gray-800 font-semibold">
                                {{ $header->main_heading }} 
                                <span class="text-blue-500">{{ $header->highlight_text }}</span>
                            </p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-500 truncate w-48" title="{{ $header->description }}">
                                {{ Str::limit($header->description, 50) }}
                            </p>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($header->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right space-x-2">

                            {{-- FIXED JSON --}}
                            <button onclick='openModal("edit", @json($header))' 
                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition">
                                <i class="fas fa-edit"></i>
                            </button>

                            {{-- DELETE --}}
                            <form action="{{ route('admin.page-hero.delete', $header->id) }}" method="POST" 
                                  class="inline-block" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-layer-group text-4xl text-gray-300 mb-3"></i>
                                <p>No page headers found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARD VIEW --}}
        <div class="md:hidden grid grid-cols-1 gap-4 p-4">
            @forelse($headers ?? [] as $header)
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm relative">

                <div class="flex justify-between items-start mb-3">
                    <span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-xs font-bold uppercase">
                        {{ str_replace('_', ' ', $header->page_type) }}
                    </span>

                    @if($header->is_active)
                        <span class="text-green-600 text-xs font-bold"><i class="fas fa-check-circle mr-1"></i> Active</span>
                    @else
                        <span class="text-gray-400 text-xs font-bold"><i class="fas fa-ban mr-1"></i> Inactive</span>
                    @endif
                </div>

                <h4 class="font-bold text-gray-800 mb-1">{{ $header->main_heading }}</h4>
                <p class="text-xs text-blue-500 mb-2 font-medium">{{ $header->eyebrow_text }}</p>

                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">

                    {{-- FIXED JSON --}}
                    <button onclick='openModal("edit", @json($header))' 
                            class="flex-1 bg-gray-50 text-gray-700 py-2 rounded-lg text-sm font-medium hover:bg-gray-100">
                        Edit
                    </button>

                    <form action="{{ route('admin.page-hero.delete', $header->id) }}" method="POST" 
                          class="flex-1" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-50 text-red-600 py-2 rounded-lg text-sm font-medium hover:bg-red-100">
                            Delete
                        </button>
                    </form>

                </div>

            </div>
            @empty
            <div class="text-center p-6 text-gray-500">No headers found.</div>
            @endforelse
        </div>

    </div>
</div>


   {{-- 3. UNIFIED MODAL (ADD & EDIT) --}}
   <div id="headerModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold leading-6 text-gray-900" id="modalTitle">Add Page Header</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <form id="headerForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- 
                       NOTE: We do not need a hidden input for PUT/PATCH because 
                       your route is defined as Route::post('/page-hero/update/{id}') 
                    --}}
                    
                    <div class="px-4 py-5 sm:p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Page Type</label>
                                <select name="page_type" id="page_type" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" required>
                                    <option value="" disabled selected>Select a Page</option>
                                    <option value="home">Home</option>
                                    <option value="watches">Watches</option>
                                    <option value="objects">Objects</option>
                                    <option value="about_us">About Us</option>
                                    <option value="contact">Contact</option>
                                    <option value="privacy_policy">Privacy Policy</option>
                                    <option value="shipping_policy">Shipping Policy</option>
                                    <option value="warranty_info">Warranty Info</option>
                                    <option value="terms_service">Terms & Service</option>
                                </select>
                            </div>
                            <div class="flex items-end mb-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" class="sr-only peer" checked>
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-700">Set as Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Eyebrow Text</label>
                                <input type="text" name="eyebrow_text" id="eyebrow_text" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="e.g. New Collection">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Main Heading</label>
                                <input type="text" name="main_heading" id="main_heading" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required placeholder="e.g. Timeless Elegance">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Highlight Text (Colored)</label>
                            <input type="text" name="highlight_text" id="highlight_text" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="e.g. For Him">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="3" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Short description for the hero section..."></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">CTA Button Text</label>
                                <input type="text" name="cta_text" id="cta_text" class="w-full rounded bg-white border-gray-300 border px-3 py-1.5 text-sm focus:ring-blue-500 outline-none" placeholder="e.g. Shop Now">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">CTA Link / URL</label>
                                <input type="text" name="cta_link" id="cta_link" class="w-full rounded bg-white border-gray-300 border px-3 py-1.5 text-sm focus:ring-blue-500 outline-none" placeholder="e.g. /products/watches">
                            </div>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                            Save Changes
                        </button>
                        <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 4. JAVASCRIPT LOGIC --}}
    <script>
    const modal = document.getElementById('headerModal');
    const form = document.getElementById('headerForm');
    const modalTitle = document.getElementById('modalTitle');

    // --- 1. DEFINE ROUTES CORRECTLY FROM BLADE ---
    const storeRoute = "{{ route('admin.page-hero.store') }}"; 
    const updateRouteBase = "{{ url('admin/page-hero/update') }}"; // Base URL for update

    function openModal(mode, data = null) {
        modal.classList.remove('hidden');

        if (mode === 'add') {
            // --- SETUP FOR ADD ---
            form.reset();
            form.action = storeRoute; // Set Action to Store
            modalTitle.textContent = "Add New Page Header";
            document.getElementById('is_active').checked = true;

            // Enable Page Type selection
            document.getElementById('page_type').disabled = false;

        } else if (mode === 'edit' && data) {
            // --- SETUP FOR EDIT ---
            form.action = updateRouteBase + '/' + data.id; // Dynamic Update URL

            modalTitle.textContent = `Edit Header: ${data.page_type.replace('_', ' ').toUpperCase()}`;

            // Populate Fields
            document.getElementById('page_type').value = data.page_type;
            document.getElementById('eyebrow_text').value = data.eyebrow_text || '';
            document.getElementById('main_heading').value = data.main_heading || '';
            document.getElementById('highlight_text').value = data.highlight_text || '';
            document.getElementById('description').value = data.description || '';
            document.getElementById('cta_text').value = data.cta_text || '';
            document.getElementById('cta_link').value = data.cta_link || '';
            document.getElementById('is_active').checked = data.is_active == 1;

            // Disable Page Type selection to prevent changing it
        }
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeModal();
        }
    });
</script>
