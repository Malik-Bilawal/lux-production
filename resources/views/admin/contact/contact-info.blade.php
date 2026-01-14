@extends("admin.layouts.master-layouts.plain")

@section('title', 'Contact Info | Luxorix | Admin Panel')

@section("content")
<div class="flex h-screen overflow-hidden">

    <aside class="w-64 bg-white shadow h-screen fixed top-0 left-0">
        @include("admin.layouts.partials.sidebar")
    </aside>

    <div class="ml-64 flex-1 overflow-y-auto bg-gray-50 p-6">

        <!-- Header -->
        <header class="bg-white shadow-sm rounded-lg mb-6">
            <div class="flex justify-between items-center py-4 px-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Contact Info Management</h2>
                    <p class="text-sm text-gray-500">Add, edit, or remove contact details for frontend display</p>
                </div>
                <button onclick="openContactModal()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center space-x-2 transition duration-300">
                    <i class="fas fa-plus"></i>
                    <span>Add Contact Info</span>
                </button>
                @include("admin.components.dark-mode.dark-toggle")

            </div>
        </header>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">All Contact Info</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-gray-700">Type</th>
                            <th class="px-4 py-2 text-gray-700">Label</th>
                            <th class="px-4 py-2 text-gray-700">Value / Link</th>
                            <th class="px-4 py-2 text-gray-700">Icon</th>
                            <th class="px-4 py-2 text-gray-700">Platform</th>
                            <th class="px-4 py-2 text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($infos as $info)
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="px-4 py-2">{{ ucfirst($info->type) }}</td>
                                <td class="px-4 py-2">{{ $info->label }}</td>
                                <td class="px-4 py-2 break-all">{{ $info->value }}</td>
                                <td class="px-4 py-2">{{ $info->icon ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $info->platform ?? '-' }}</td>
                                <td class="px-4 py-2 space-x-2">
                                <button onclick='openContactModal(@json($info))' 
        class="px-2 py-1 bg-yellow-400 hover:bg-yellow-500 text-white rounded-md transition duration-300">
    Edit
</button>

                                    <form action="{{ route('admin.contact.contact-info.destroy', $info->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 bg-red-500 hover:bg-red-600 text-white rounded-md transition duration-300"
                                            onclick="return confirm('Are you sure you want to delete this info?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-2 text-center text-gray-400">No contact info found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="contactModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div id="contactModalContent" class="bg-white rounded-lg w-full max-w-lg p-6 shadow-lg transform scale-95 opacity-0 transition-all duration-300">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center" id="modalTitle">Add Contact Info</h2>
                <form id="contactForm" method="POST" action="{{ route('admin.contact.contact-info.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="contact_id" id="contact_id">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" id="type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="visit">Visit</option>
                            <option value="call">Call</option>
                            <option value="email">Email</option>
                            <option value="social">Social</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                        <input type="text" name="label" id="label" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="E.g., Karachi Office / +0332721847 / Facebook">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Value / Link</label>
                        <input type="text" name="value" id="value" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="E.g., tel:+92332721847 / mailto:support@luxorix.com / https://facebook.com/luxorix">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Optional)</label>
                        <input type="text" name="icon" id="icon" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g., fas fa-phone-alt">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Platform (Only for Social)</label>
                        <input type="text" name="platform" id="platform" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="facebook, instagram, tiktok, youtube">
                    </div>

                    <div class="flex justify-center mt-4">
                        <button type="submit" id="modalButtonText" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition duration-300">
                            <i class="fas fa-save"></i>
                            <span>Save Info</span>
                        </button>
                    </div>
                </form>

                <button onclick="closeContactModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

    </div>
</div>

@push('script')
<script>
function openContactModal(info = null) {
    const modal = document.getElementById('contactModal');
    const content = document.getElementById('contactModalContent');

    // Show modal container
    modal.classList.remove('hidden');

    // Animate content
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 50);

    // Populate fields if editing
    if(info) {
        document.getElementById('modalTitle').innerText = "Edit Contact Info";
        document.getElementById('modalButtonText').innerText = "Update Info";
        document.getElementById('contact_id').value = info.id;
        document.getElementById('type').value = info.type;
        document.getElementById('label').value = info.label;
        document.getElementById('value').value = info.value;
        document.getElementById('icon').value = info.icon ?? '';
        document.getElementById('platform').value = info.platform ?? '';
        document.getElementById('contactForm').action = `/admin/contact-info/${info.id}`;
        document.getElementById('contactForm').method = "POST";

        if (!document.getElementById('_method')) {
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.id = '_method';
            methodInput.value = 'PUT';
            document.getElementById('contactForm').appendChild(methodInput);
        }
    } else {
        document.getElementById('modalTitle').innerText = "Add Contact Info";
        document.getElementById('modalButtonText').innerText = "Save Info";
        document.getElementById('contactForm').reset();
        document.getElementById('contactForm').action = "{{ route('admin.contact.contact-info.store') }}";
        document.getElementById('contactForm').method = "POST";
        const methodInput = document.getElementById('_method');
        if (methodInput) methodInput.remove();
    }
}

function closeContactModal() {
    const modal = document.getElementById('contactModal');
    const content = document.getElementById('contactModalContent');

    // Animate out
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');

    // Hide modal after animation
    setTimeout(() => modal.classList.add('hidden'), 200);
}

</script>
@endpush
@endsection
