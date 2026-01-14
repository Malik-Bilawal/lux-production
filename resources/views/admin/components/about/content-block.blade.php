<div class="bg-gray-100 min-h-screen p-4 sm:p-6">

    {{-- 1. STATISTICS SECTION --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-indigo-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Total Blocks</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $total ?? 0 }}</h3>
                </div>
                <div class="p-3 bg-indigo-100 rounded-full text-indigo-600">
                    <i class="fas fa-cubes text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Active Blocks</p>
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
                <h3 class="text-lg font-bold text-gray-800">Content Blocks</h3>
                <p class="text-sm text-gray-500">Manage blocks, signatures, and figures.</p>
            </div>
            <button onclick="openModal('add')" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow transition flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Add New Block
            </button>
        </div>

        <div class="p-0">
            
            {{-- DESKTOP TABLE VIEW --}}
            <div class=" md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-6 py-4">Block / Order</th>
                            <th class="px-6 py-4">Image / Fig</th>
                            <th class="px-6 py-4">Title & Content</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($blocks ?? [] as $block)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-500 uppercase">Block #{{ $block->block_number }}</span>
                                    <span class="text-xs text-gray-400">Order: {{ $block->order }}</span>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                @if($block->image_url)
                                    <div class="flex flex-col gap-1">
                                        <img src="{{ asset('storage/' . $block->image_url) }}" class="h-12 w-12 object-cover rounded-md border border-gray-200" alt="Block Image">
                                        <span class="text-[10px] text-gray-400 truncate w-20">{{ $block->fig_label }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">No Image</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-800">{{ $block->title }}</p>
                                <p class="text-xs text-indigo-500 font-medium mb-1">{{ $block->subtitle }}</p>
                                <p class="text-sm text-gray-500 truncate w-48">{{ Str::limit($block->content, 60) }}</p>
                                @if($block->signature_text)
                                    <p class="text-xs text-gray-400 mt-1 italic font-serif">"{{ $block->signature_text }}"</p>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($block->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right space-x-2">
                                <button onclick="openModal('edit', {{ json_encode($block) }})" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.about-block.delete', $block->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this block?');">
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
                                No blocks found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

{{-- 3. UNIFIED MODAL (ADD & EDIT) --}}
<div id="blockModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold leading-6 text-gray-900" id="modalTitle">Manage Content Block</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <form id="blockForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- IMPORTANT: Placeholder for PUT method injection via JS --}}
                    <div id="methodField"></div>
                    
                    <div class="px-4 py-5 sm:p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Block Text</label>
                                <input type="text" name="block_text" id="block_text" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 outline-none" required placeholder="e.g. Block Description">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                                <input type="number" name="order" id="order" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 outline-none" placeholder="e.g. 10">
                            </div>
                             <div class="flex items-end mb-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" class="sr-only peer" checked>
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" name="title" id="title" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 outline-none" placeholder="Main Title">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                                <input type="text" name="subtitle" id="subtitle" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 outline-none" placeholder="Small Subtitle">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Content / Paragraph</label>
                            <textarea name="content" id="content" rows="4" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 outline-none" placeholder="Detailed content text..."></textarea>
                        </div>

                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Signature Text</label>
                            <input type="text" name="signature_text" id="signature_text" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 outline-none font-serif italic" placeholder="e.g. John Doe, CEO">
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300">
                             <label class="block text-sm font-medium text-gray-700 mb-2">Block Image</label>
                             <div class="flex items-center gap-4">
                                <div id="currentImageContainer" class="hidden">
                                    <img id="currentImagePreview" src="" class="h-16 w-16 object-cover rounded border border-gray-300">
                                    <p class="text-[10px] text-gray-500 mt-1 text-center">Current</p>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="image" id="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                                    <p class="text-xs text-gray-400 mt-1">Leave empty to keep current image.</p>
                                </div>
                             </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Figure Label (Image Caption)</label>
                            <input type="text" name="fig_label" id="fig_label" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 outline-none" placeholder="e.g. Fig 1.1 - The Watch">
                        </div>

                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto">
                            Save Block
                        </button>
                        <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
    const modal = document.getElementById('blockModal');
    const form = document.getElementById('blockForm');
    const modalTitle = document.getElementById('modalTitle');
    const imagePreview = document.getElementById('currentImagePreview');
    const imageContainer = document.getElementById('currentImageContainer');
    const methodField = document.getElementById('methodField'); // For PUT method spoofing

    // Base URLs
    const storeRoute = "{{ route('admin.about-block.store') }}"; 
    const updateRouteBase = "{{ url('admin/about-block') }}/"; // will append ID dynamically

    function openModal(mode, data = null) {
        modal.classList.remove('hidden');

        if (mode === 'add') {
            // --- ADD MODE ---
            form.reset();
            form.action = storeRoute;
            modalTitle.textContent = "Add New Block";
            methodField.innerHTML = ''; // no _method for POST
            imageContainer.classList.add('hidden');
            imagePreview.src = '';
            document.getElementById('is_active').checked = true;

        } else if (mode === 'edit' && data) {
            // --- EDIT MODE ---
            form.action = updateRouteBase + data.id; // dynamically append the ID
            modalTitle.textContent = `Edit Block #${data.block_text}`;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // Populate form fields
            document.getElementById('block_text').value = data.block_text || '';
            document.getElementById('title').value = data.title || '';
            document.getElementById('subtitle').value = data.subtitle || '';
            document.getElementById('content').value = data.content || '';
            document.getElementById('signature_text').value = data.signature_text || '';
            document.getElementById('fig_label').value = data.fig_label || '';
            document.getElementById('order').value = data.order || '';
            document.getElementById('is_active').checked = data.is_active == 1;

            // Handle Image Preview
            if (data.image_url) {
                imageContainer.classList.remove('hidden');
                imagePreview.src = `/storage/${data.image_url}`;
            } else {
                imageContainer.classList.add('hidden');
                imagePreview.src = '';
            }
        }
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") closeModal();
    });
</script>
