@extends("admin.layouts.master-layouts.plain")


<meta name="csrf-token" content="{{ csrf_token() }}">


@section('title', 'Admin Management | Luxorix | Admin Panel')

@push("script")
<script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        dark: '#1F2937',
                        light: '#F9FAFB'
                    }
                }
            }
        }
    </script>
@endpush


@push("style")
<style>
        .admin-avatar {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .admin-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .role-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
        }
        .action-btn {
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .permission-toggle {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }
        .permission-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .permission-toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }
        .permission-toggle-slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        .permission-toggle input:checked + .permission-toggle-slider {
            background-color: #10B981;
        }
        .permission-toggle input:checked + .permission-toggle-slider:before {
            transform: translateX(26px);
        }
        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .status-online {
            background-color: #10B981;
        }
        .status-offline {
            background-color: #9CA3AF;
        }
        .status-busy {
            background-color: #F59E0B;
        }
        .smooth-transition {
            transition: all 0.3s ease;
        }


        @layer components {

    .responsive-td::before {
        content: attr(data-label);
        position: absolute;
        left: 0.75rem; /* 12px */
        top: 0.75rem; /* 12px */
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.75rem; /* 12px */
        color: #6b7280; /* text-gray-500 */
        display: block;
    }

    /* On desktop, we hide this label */
    @media (min-width: 768px) { /* md: */
        .responsive-td::before {
            display: none;
        }
    }
}
    </style>
@endpush

@section("content")

        
     

        <!-- Header -->
        <header class="bg-white shadow-sm rounded-lg">
            <div class="flex flex-wrap justify-between items-center py-4 px-6 gap-4">
                <!-- Left Side: Title -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Admin Management</h2>
                    <p class="text-sm text-gray-500">Manage administrators and permissions</p>
                </div>
                
                <!-- Right Side: Controls -->
                <div class="flex items-center flex-wrap gap-3">
                    <div class="relative">
                        <input type="text" placeholder="Search admins..." class="border rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>

                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center action-btn" 
                        onclick="openAddAdminModal()">
                        <i class="fas fa-plus mr-2"></i> Add Admin
                    </button>

                    <a href="{{ route("admin.admins-role-management") }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center action-btn">
                        <i class="fas fa-user-shield mr-2"></i> Manage ROLE
                    </a>
                    
                    @include("admin.components.dark-mode.dark-toggle")
                </div>
            </div>
        </header>

        <section class="px-6 py-4 bg-white shadow-sm mt-6 rounded-lg">
    <div class="flex flex-col md:flex-row md:flex-wrap md:items-end gap-4">

        <div class="w-full md:w-auto">
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select class="border rounded-md px-3 py-2 text-sm w-full md:w-40">
                <option value="">All Roles</option>
                <option value="super-admin">Super Admin</option>
                <option value="admin">Admin</option>
                <option value="moderator">Moderator</option>
            </select>
        </div>
        
        <div class="w-full md:w-auto">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select class="border rounded-md px-3 py-2 text-sm w-full md:w-32">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        
        <div class="w-full md:w-auto">
            <label class="block text-sm font-medium text-gray-700 mb-1">Activity</label>
            <select class="border rounded-md px-3 py-2 text-sm w-full md:w-40">
                <option value="">All</option>
                <option value="online">Online Now</option>
                <option value="recent">Recently Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        
        <div class="w-full md:w-auto">
            <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm flex items-center w-full md:w-auto justify-center">
                <i class="fas fa-filter mr-2"></i>
                Apply Filters
            </button>
        </div>

    </div>
</section>

        <!-- ... Your other content, tables, etc. go here ... -->

        <section class="p-6 flex-1">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div>
            <table class="w-full block md:table">
                <thead class="bg-gray-50 hidden md:table-header-group">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Active</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="block md:table-row-group">
                    @foreach($admins as $admin)
                        <tr class="hover:bg-gray-50 transition-colors
                                   block md:table-row 
                                   border border-gray-200 md:border-b 
                                   rounded-lg md:rounded-none 
                                   shadow-md md:shadow-none 
                                   mb-4 md:mb-0">
                            
                            <td class="px-6 py-4 block md:table-cell border-b md:border-none border-gray-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 mr-4">
                                        <img class="h-12 w-12 rounded-full admin-avatar object-cover" 
                                             src="{{ $admin->profile_pic ? asset('storage/' . $admin->profile_pic) : 'https://via.placeholder.com/150' }}" 
                                             alt="{{ $admin->name }}">
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $admin->name }}</div>
                                        <div class="text-gray-500 text-sm">{{ $admin->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="block md:table-cell px-6 md:px-6 py-3 md:py-4 pl-32 md:pl-6 relative border-b md:border-none border-gray-200 responsive-td" 
                                data-label="Role">
                                <span class="role-badge bg-blue-100 text-blue-800">{{ $admin->role->name }}</span>
                            </td>

                            <td class="block md:table-cell px-6 md:px-6 py-3 md:py-4 pl-32 md:pl-6 relative border-b md:border-none border-gray-200 responsive-td"
                                data-label="Last Active">
                                <div class="text-sm text-gray-900">
                                    {{ $admin->last_login_at ? $admin->last_login_at->format('d M Y, h:i A') : 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    From IP: {{ $admin->last_login_ip ?? 'N/A' }}
                                </div>
                            </td>

                            <td class="block md:table-cell px-6 md:px-6 py-3 md:py-4 pl-32 md:pl-6 relative border-b md:border-none border-gray-200 responsive-td"
                                data-label="Status">
                                @php
                                    $isOnline = $admin->isOnline();
                                    $status = $isOnline ? 'Online' : 'Offline';
                                    $statusClass = $isOnline ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                                @endphp

                                <span class="inline-block w-2 h-2 rounded-full {{ $isOnline ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                <span class="ml-2 px-2 py-1 rounded text-xs font-semibold {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </td>

                            <td class="block md:table-cell px-6 md:px-6 py-3 md:py-4 pl-32 md:pl-6 relative responsive-td"
                                data-label="Actions">
                                <div class="flex space-x-2">
                                    @php
                                        $loggedIn = Auth::guard('admin')->user();
                                    @endphp

                                    {{-- ========= SUPER ADMIN FULL CONTROL ========= --}}
                                    @if($loggedIn->role->slug === 'super_admin')
                                        
                                        {{-- Super Admin can control all except other super admins --}}
                                        @if($admin->role->slug !== 'super_admin')
                                            <button class="text-blue-500 hover:text-blue-700 action-btn" 
                                                    onclick="openEditAdminModal({{ $admin->id }})"
                                                    title="Edit Admin">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button class="text-orange-500 hover:text-orange-700 action-btn force-logout-btn"
                                                    data-admin-id="{{ $admin->id }}"
                                                    data-force-url="{{ route('admin.force.logout', $admin) }}"
                                                    title="Force Logout">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </button>

                                            <form action="{{ route('admin.admins.destroy', $admin->id) }}" 
                                                  method="POST" 
                                                  id="deleteAdminForm{{ $admin->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-500 hover:text-red-700 action-btn" 
                                                        onclick="return confirm('Are you sure you want to delete this admin?')"
                                                        title="Delete Admin">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            {{-- ✅ Super Admin viewing their own profile (self edit only) --}}
                                            @if($loggedIn->id === $admin->id)
                                                <button class="text-blue-500 hover:text-blue-700 action-btn" 
                                                        onclick="openEditAdminModal({{ $admin->id }})"
                                                        title="Edit Your Profile">
                                                    <i class="fas fa-user-edit"></i>
                                                </button>
                                            @endif
                                        @endif

                                    @elseif($loggedIn->role->slug === 'admin')
                                        @if($loggedIn->id === $admin->id)
                                            <button class="text-blue-500 hover:text-blue-700 action-btn" 
                                                    onclick="openEditAdminModal({{ $admin->id }})"
                                                    title="Edit Your Profile">
                                                <i class="fas fa-user-edit"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

    </div>

</div>
<!-- Add/Edit Admin Modal -->
<div id="addAdminModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-96 relative p-6 transform scale-95 transition-transform duration-200">
        
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold text-gray-800" id="modalTitle">Add Admin / Sub Admin</h2>
            <button onclick="closeAddAdminModal()" class="text-gray-400 hover:text-gray-600 transition-colors text-2xl">&times;</button>
        </div>

        <!-- Modal Form -->
        <form id="adminForm" action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="admin_id" id="admin_id">

            <input type="text" name="name" id="name" placeholder="Full Name"
                class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>

            <input type="email" name="email" id="email" placeholder="Email"
                class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>

            <input type="password" name="password" id="password" placeholder="Password"
                class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">

            <div class="flex items-center space-x-4">
                <img id="profilePicPreview" src="" alt="Profile Preview" class="w-20 h-20 rounded-full object-cover hidden border border-gray-200">
                <label class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded transition-colors">
                    Choose Picture
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*" class="hidden">
                </label>
            </div>
            <select name="role_id" id="role_id" required>
    @foreach($roles as $role)
        @if($role->slug === 'super_admin')
            @if(!$superAdminAssigned || (isset($admin) && $admin->role && $admin->role->slug === 'super_admin'))
                <option value="{{ $role->id }}" 
                    {{ old('role_id', $admin->role_id ?? '') == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endif
        @else
            <option value="{{ $role->id }}" 
                {{ old('role_id', $admin->role_id ?? '') == $role->id ? 'selected' : '' }}>
                {{ $role->name }}
            </option>
        @endif
    @endforeach
</select>





            <button type="submit" id="modalSubmitBtn"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition-colors">
                Add Admin
            </button>
        </form>
    </div>
</div>



@endsection


@push("script")
<script>
   function openAddRoleModal() {
    document.getElementById('addRoleModal').classList.remove('hidden');
}
function closeAddRoleModal() {
    document.getElementById('addRoleModal').classList.add('hidden');
}

// ADD ADMINS
function openAddAdminModal() {
    document.getElementById('addAdminModal').classList.remove('hidden');
}

function openEditAdminModal(adminId) {
    fetch(`/admin/admins/${adminId}/edit`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('modalTitle').innerText = 'Edit Admin / Sub Admin';
            document.getElementById('modalSubmitBtn').innerText = 'Update Admin';
            document.getElementById('adminForm').action = `/admin/admins/${adminId}`;
            document.getElementById('adminForm').method = 'POST';

            if (!document.getElementById('_method')) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_method';
                input.id = '_method';
                input.value = 'PUT';
                document.getElementById('adminForm').appendChild(input);
            }

            if(data.profile_pic){
    let preview = document.getElementById('profilePicPreview');
    preview.src = '/' + data.profile_pic; 
    preview.classList.remove('hidden');
} else {
    document.getElementById('profilePicPreview').classList.add('hidden');
}

            document.getElementById('admin_id').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('role_id').value = data.role_id;
        });

    document.getElementById('addAdminModal').classList.remove('hidden');
}

function closeAddAdminModal() {
    document.getElementById('addAdminModal').classList.add('hidden');
    document.getElementById('adminForm').reset();
    document.getElementById('modalTitle').innerText = 'Add Admin / Sub Admin';
    document.getElementById('modalSubmitBtn').innerText = 'Add Admin';
    document.getElementById('adminForm').action = "{{ route('admin.admins.store') }}";
    document.getElementById('_method')?.remove();
}
document.addEventListener('click', async function (e) {
    const btn = e.target.closest('.force-logout-btn');
    if (!btn) return;

    const adminId = btn.dataset.adminId;
    const url = btn.dataset.forceUrl; // from Blade route()

    if (!confirm("Are you sure you want to force logout this admin?")) return;


    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');


    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            const text = await response.text();
            throw { status: response.status, responseText: text };
        }

        const data = await response.json();
        alert(data.message || 'Done');
    } catch (err) {

        let msg = 'Something went wrong! Check console & logs.';
        try {
            const json = JSON.parse(err.responseText);
            if (json.message) msg = json.message;
        } catch (e) {
            if (err.status === 419) msg = 'CSRF token mismatch (419). Refresh the page.';
            else if (err.status === 403) msg = 'Forbidden (403) — permission denied.';
            else if (err.status === 404) msg = 'Route not found (404).';
        }
        alert(msg + ' (see console for details)');
    } 
});



</script>
@endpush