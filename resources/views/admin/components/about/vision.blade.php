<div class="bg-gray-100 min-h-screen p-4 sm:p-6">
    
    {{-- HEADER --}}
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-gray-800">Company Vision</h3>
        <p class="text-gray-500">Manage the core vision statement displayed on the About page.</p>
    </div>

    {{-- MAIN CONTENT: CONDITIONAL DISPLAY --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- LEFT: VISUAL PREVIEW / EMPTY STATE --}}
        <div class="lg:col-span-2">
            
            @if($vision)
                <div class="bg-white rounded-xl shadow-md overflow-hidden relative group">
                    <div class="absolute top-4 right-4 z-10">
                        @if($vision->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 shadow-sm">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 shadow-sm">
                                <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span> Hidden
                            </span>
                        @endif
                    </div>

                    <div class="p-8 sm:p-10 relative">
                        <div class="absolute top-6 left-6 text-gray-100 text-9xl font-serif select-none -z-0">
                            <i class="fas fa-quote-left"></i>
                        </div>

                        <div class="relative z-10">
                            <h2 class="text-3xl sm:text-4xl font-serif text-gray-800 italic leading-relaxed mb-6">
                                "{{ $vision->quote }}"
                            </h2>
                            
                            <p class="text-gray-600 leading-relaxed text-lg mb-8 max-w-2xl">
                                {{ $vision->description }}
                            </p>

                            <div class="flex items-center justify-between border-t border-gray-100 pt-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-md">
                                        {{ $vision->initials }}
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Footer Text</p>
                                        <p class="text-sm font-semibold text-gray-700">{{ $vision->footer_text }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 flex justify-end gap-3 border-t border-gray-100">
                        <button onclick="openVisionModal('edit', {{ json_encode($vision) }})" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:text-indigo-600 transition shadow-sm font-medium flex items-center gap-2">
                            <i class="fas fa-edit"></i> Edit Vision
                        </button>
                        
                        <form action="{{ route('admin.vision.delete', $vision->id) }}" method="POST" onsubmit="return confirm('Are you sure? This will remove the vision section.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition shadow-sm font-medium flex items-center gap-2">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border-2 border-dashed border-gray-300 p-12 text-center h-full flex flex-col items-center justify-center hover:border-indigo-400 transition-colors duration-300">
                    <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-lightbulb text-3xl text-indigo-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Vision Set</h3>
                    <p class="text-gray-500 mb-8 max-w-sm mx-auto">Create a vision statement to display your company's core values and future outlook.</p>
                    
                    <button onclick="openVisionModal('add')" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-200 transition transform hover:-translate-y-1 font-semibold flex items-center gap-2">
                        <i class="fas fa-plus"></i> Create Vision Statement
                    </button>
                </div>
            @endif
        </div>

        {{-- RIGHT: INSTRUCTIONS / HELP --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 h-fit">
            <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-500"></i> Display Tips
            </h4>
            <ul class="space-y-4 text-sm text-gray-600">
                <li class="flex gap-3">
                    <span class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex-shrink-0 flex items-center justify-center font-bold text-xs">1</span>
                    <p><strong>The Quote</strong> should be short, punchy, and memorable. It acts as the headline.</p>
                </li>
                <li class="flex gap-3">
                    <span class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex-shrink-0 flex items-center justify-center font-bold text-xs">2</span>
                    <p><strong>Initials</strong> are typically the CEO's or Founder's initials (e.g., "JD") displayed in a circle.</p>
                </li>
                <li class="flex gap-3">
                    <span class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex-shrink-0 flex items-center justify-center font-bold text-xs">3</span>
                    <p>Only <strong>one active vision</strong> can exist. If you delete it, the section will hide from the frontend.</p>
                </li>
            </ul>
        </div>
    </div>

    {{-- 3. UNIFIED MODAL --}}
    <div id="visionModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" onclick="closeVisionModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold leading-6 text-gray-900" id="visionModalTitle">Create Vision</h3>
                        <button onclick="closeVisionModal()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <form id="visionForm" method="POST">
                    @csrf
                    <div id="visionMethodField"></div>

                    <div class="px-4 py-5 sm:p-6 space-y-5">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Main Quote</label>
                            <textarea name="quote" id="v_quote" rows="2" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 outline-none font-serif text-lg" placeholder="e.g. Innovation is our DNA..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Detailed Description</label>
                            <textarea name="description" id="v_description" rows="4" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 outline-none" placeholder="Elaborate on the vision..."></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Initials (Avatar)</label>
                                <input type="text" name="initials" id="v_initials" maxlength="3" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 outline-none uppercase" placeholder="e.g. CEO">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Footer Text</label>
                                <input type="text" name="footer_text" id="v_footer_text" class="w-full rounded-lg border-gray-300 border px-3 py-2 focus:ring-indigo-500 outline-none" placeholder="e.g. Since 1998">
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" id="v_is_active" value="1" class="sr-only peer" checked>
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-700">Display this section on website</span>
                            </label>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto">
                            Save Changes
                        </button>
                        <button type="button" onclick="closeVisionModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 4. JAVASCRIPT --}}
    <script>
        const visionModal = document.getElementById('visionModal');
        const visionForm = document.getElementById('visionForm');
        const visionModalTitle = document.getElementById('visionModalTitle');
        const visionMethodField = document.getElementById('visionMethodField');

        // ROUTES
        const visionStoreRoute = "{{ route('admin.vision.store') }}";
        const visionUpdateRouteBase = "{{ route('admin.vision.update', ['id' => '__ID__']) }}";
        function openVisionModal(mode, data = null) {
    visionModal.classList.remove('hidden');

    if (mode === 'add') {
        visionForm.reset();
        visionForm.action = visionStoreRoute;
        visionModalTitle.textContent = "Create Vision Statement";
        visionMethodField.innerHTML = ''; // POST
        document.getElementById('v_is_active').checked = true;
    } 
    else if (mode === 'edit' && data) {
        // Replace placeholder with actual ID
        let finalUrl = visionUpdateRouteBase.replace('__ID__', data.id);
        visionForm.action = finalUrl;

        visionModalTitle.textContent = "Edit Vision";
        visionMethodField.innerHTML = '<input type="hidden" name="_method" value="PUT">'; // PUT

        // Fill form fields
        document.getElementById('v_quote').value = data.quote || '';
        document.getElementById('v_description').value = data.description || '';
        document.getElementById('v_initials').value = data.initials || '';
        document.getElementById('v_footer_text').value = data.footer_text || '';
        document.getElementById('v_is_active').checked = data.is_active == 1;
    }
}


        function closeVisionModal() {
            visionModal.classList.add('hidden');
        }
        
        // Escape key close
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeVisionModal();
        });
    </script>
</div>