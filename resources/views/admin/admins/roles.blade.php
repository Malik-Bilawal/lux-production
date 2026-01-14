@extends('admin.layouts.master-layouts.plain')

@section('title', 'Role Management | Luxorix')

@section('content')
<div class="min-h-screen bg-gray-50/50 dark:bg-slate-900 p-4 sm:p-6 lg:p-8 transition-colors duration-300">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight flex items-center gap-3">
                <span class="bg-indigo-100 dark:bg-indigo-500/20 p-2 rounded-lg text-indigo-600 dark:text-indigo-400">
                    <i class="fas fa-user-shield text-xl"></i>
                </span>
                Roles & Permissions
            </h1>
            <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400 max-w-lg">
                Manage system access levels. Define what your team can view and edit within the Luxorix ecosystem.
            </p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @include("admin.components.dark-mode.dark-toggle")
            
            <button onclick="openAddRoleModal()" 
                class="group relative inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white transition-all duration-200 bg-indigo-600 font-pj rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 hover:bg-indigo-700 active:scale-95 shadow-lg shadow-indigo-500/30">
                <i class="fas fa-plus mr-2 transition-transform group-hover:rotate-90"></i>
                Create New Role
            </button>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/60 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-indigo-500 to-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Roles</p>
                    <h3 class="text-3xl font-black text-slate-800 dark:text-white mt-2">{{ $roles->count() }}</h3>
                </div>
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl text-indigo-600 dark:text-indigo-400">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700/60 relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute right-0 top-0 h-full w-1 bg-gradient-to-b from-emerald-500 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">System Permissions</p>
                    <h3 class="text-3xl font-black text-slate-800 dark:text-white mt-2">{{ $permissions->count() }}</h3>
                </div>
                <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl text-emerald-600 dark:text-emerald-400">
                    <i class="fas fa-key text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Area --}}
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-700 overflow-hidden">
        
        {{-- Desktop View (Hidden on Mobile) --}}
        <div class=" md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-700/30 border-b border-slate-100 dark:border-slate-700 text-slate-500 dark:text-slate-400">
                        <th class="px-8 py-5 text-xs font-bold uppercase tracking-wider">Role Identity</th>
                        <th class="px-6 py-5 text-xs font-bold uppercase tracking-wider">Default Route</th>
                        <th class="px-6 py-5 text-xs font-bold uppercase tracking-wider">Access Scope</th>
                        <th class="px-8 py-5 text-right text-xs font-bold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($roles as $role)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/20 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                    {{ substr($role->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $role->name }}</div>
                                    <div class="text-xs text-slate-400 font-mono bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5 rounded inline-block mt-1">{{ $role->slug }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            @if($role->default_route)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800">
                                    <i class="fas fa-location-arrow text-[10px]"></i> {{ $role->default_route }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400 italic">Not Configured</span>
                            @endif
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-2 max-w-sm">
                                @forelse($role->permissions->where('pivot.can_view', 1)->take(3) as $perm)
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-md border border-slate-200 dark:border-slate-600">
                                        {{ $perm->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-slate-400">Restricted Access</span>
                                @endforelse
                                
                                @if($role->permissions->where('pivot.can_view', 1)->count() > 3)
                                    <span class="px-2.5 py-1 text-[10px] font-bold bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 rounded-md">
                                        +{{ $role->permissions->where('pivot.can_view', 1)->count() - 3 }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end items-center gap-1">
                                <button onclick="openEditRoleModal({{ $role->id }})" 
                                    class="h-9 w-9 flex items-center justify-center rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all"
                                    title="Edit Configuration">
                                    <i class="fas fa-pen-to-square"></i>
                                </button>
                                
                                @if($role->slug !== 'super_admin')
                                <form action="{{ route('admin.admins-roles.destroy', $role->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                        class="h-9 w-9 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition-all"
                                        onclick="return confirm('Are you sure? This action cannot be undone and might affect users assigned to this role.')"
                                        title="Delete Role">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile View (Card Grid) --}}
        <div class="md:hidden grid grid-cols-1 divide-y divide-slate-100 dark:divide-slate-700">
            @foreach($roles as $role)
            <div class="p-5 flex flex-col gap-4 bg-white dark:bg-slate-800">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                         <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                            {{ substr($role->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-white">{{ $role->name }}</h3>
                            <span class="text-xs font-mono text-slate-400">{{ $role->slug }}</span>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <button onclick="openEditRoleModal({{ $role->id }})" class="p-2 text-slate-400 hover:text-indigo-600"><i class="fas fa-edit"></i></button>
                         @if($role->slug !== 'super_admin')
                            <form action="{{ route('admin.admins-roles.destroy', $role->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600" onclick="return confirm('Delete role?')"><i class="fas fa-trash"></i></button>
                            </form>
                         @endif
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm border-b border-dashed border-slate-200 dark:border-slate-700 pb-2">
                        <span class="text-slate-500">Landing Page</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300">
                            {{ $role->default_route ?: 'None' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase mb-2 block">Permissions</span>
                        <div class="flex flex-wrap gap-2">
                             @forelse($role->permissions->where('pivot.can_view', 1)->take(5) as $perm)
                                <span class="px-2 py-1 text-[10px] font-bold bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded">
                                    {{ $perm->name }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-400">None</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($roles->isEmpty())
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 mb-4 text-slate-400">
                <i class="fas fa-search text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-slate-900 dark:text-white">No roles found</h3>
            <p class="mt-1 text-slate-500 text-sm">Get started by creating a new role for your system.</p>
        </div>
        @endif
    </div>
</div>

{{-- Enhanced Modal --}}
<div id="roleModal" class="hidden fixed inset-0 z-[100] transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop with blur --}}
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeRoleModal()"></div>

    <div class="flex items-center justify-center min-h-screen p-4 sm:p-0">
        <div class="relative bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-3xl transform transition-all scale-100 flex flex-col max-h-[90vh]">
            
            {{-- Modal Header --}}
            <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-white dark:bg-slate-800 rounded-t-3xl z-10">
                <div>
                    <h2 id="roleModalTitle" class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Add New Role</h2>
                    <p class="text-sm text-slate-500 mt-1">Configure role details and access matrix.</p>
                </div>
                <button onclick="closeRoleModal()" class="h-10 w-10 flex items-center justify-center rounded-full bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 text-slate-500 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Modal Content (Scrollable) --}}
            <div class="overflow-y-auto p-8 custom-scrollbar space-y-8">
                <form id="roleForm" method="POST">
                    @csrf
                    <input type="hidden" name="role_id" id="role_id">

                    {{-- Role Identity Section --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Display Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="role_name" placeholder="e.g. Sales Manager" 
                                class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium outline-none" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">System Slug <span class="text-red-500">*</span></label>
                            <input type="text" name="slug" id="role_slug" placeholder="e.g. sales_manager" 
                                class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-mono text-sm outline-none" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Default Landing Route</label>
                        <div class="relative">
                            <select name="default_route" id="role_default_route" 
                                class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                                <option value="">-- No Specific Landing Page --</option>
                                @foreach(config('admin_routes.available_routes', []) as $label => $routeName)
                                    <option value="{{ $routeName }}">{{ $label }} ({{ $routeName }})</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Permission Matrix with Toggles --}}
                    <div class="space-y-4 pt-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fas fa-shield-alt text-indigo-500"></i> Access Permissions
                            </h3>
                            <button type="button" onclick="toggleAllPermissions()" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                Toggle All
                            </button>
                        </div>
                        
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                            <div class="grid grid-cols-12 bg-slate-100 dark:bg-slate-700/50 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-300 py-3 px-4">
                                <div class="col-span-6">Module Resource</div>
                                <div class="col-span-3 text-center">View</div>
                                <div class="col-span-3 text-center">Edit</div>
                            </div>
                            
                            <div class="max-h-[300px] overflow-y-auto custom-scrollbar divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($permissions as $permission)
                                <div class="grid grid-cols-12 items-center py-3 px-4 hover:bg-white dark:hover:bg-slate-800 transition-colors">
                                    <div class="col-span-6 font-medium text-slate-700 dark:text-slate-300">
                                        {{ $permission->name }}
                                    </div>
                                    
                                    <div class="col-span-3 flex justify-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="permissions[{{ $permission->id }}][view]" value="1" id="perm_view_{{ $permission->id }}" class="sr-only peer">
                                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>

                                    <div class="col-span-3 flex justify-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="permissions[{{ $permission->id }}][edit]" value="1" id="perm_edit_{{ $permission->id }}" class="sr-only peer">
                                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500"></div>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="pt-6 border-t border-slate-100 dark:border-slate-700 flex flex-col-reverse sm:flex-row gap-3">
                        <button type="button" onclick="closeRoleModal()" class="flex-1 px-6 py-3.5 rounded-xl font-bold text-sm bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-800 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-600 transition-all">
                            Cancel Operation
                        </button>
                        <button type="submit" id="roleModalBtn" class="flex-[2] px-6 py-3.5 rounded-xl font-bold text-sm text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/20 active:scale-[0.98] transition-all flex justify-center items-center gap-2">
                            <i class="fas fa-save"></i> <span>Save Role Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Scrollbar for better UI inside the modal */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
</style>
@endsection

@push('script')
<script>
const modal = document.getElementById('roleModal');
const modalContent = modal.querySelector('div.relative');

function closeRoleModal() {
    modal.classList.add('opacity-0');
    // Wait for transition to finish before hiding
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('opacity-0');
    }, 300);
    document.body.classList.remove('overflow-hidden');
}

function resetRoleCheckboxes() {
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
}

function openAddRoleModal() {
    document.getElementById('roleModalTitle').innerText = 'Add New Role';
    document.getElementById('roleModalBtn').querySelector('span').innerText = 'Create Role';
    document.getElementById('roleForm').action = "{{ route('admin.admins-roles.store') }}";

    let methodInput = document.getElementById('_method');
    if (methodInput) methodInput.remove();

    ['role_id','role_name','role_slug','role_default_route'].forEach(id => {
        document.getElementById(id).value = '';
    });

    resetRoleCheckboxes();
    
    // Animation Open
    modal.classList.remove('hidden');
    // Small delay to allow display:block to apply before opacity transition
    setTimeout(() => {
        modal.classList.remove('opacity-0'); 
    }, 10);
    document.body.classList.add('overflow-hidden');
}

function openEditRoleModal(roleId) {
    // Show loading state or similar if desired
    fetch(`/admin/roles/${roleId}/edit`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('roleModalTitle').innerText = 'Edit System Role';
            document.getElementById('roleModalBtn').querySelector('span').innerText = 'Update Changes';
            document.getElementById('roleForm').action = `/admin/roles/${roleId}`;

            if (!document.getElementById('_method')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_method';
                input.id = '_method';
                input.value = 'PUT';
                document.getElementById('roleForm').appendChild(input);
            }

            document.getElementById('role_id').value = data.id || '';
            document.getElementById('role_name').value = data.name || '';
            document.getElementById('role_slug').value = data.slug || '';
            document.getElementById('role_default_route').value = data.default_route || '';

            resetRoleCheckboxes();
            const perms = data.permissions || {};
            Object.keys(perms).forEach(pid => {
                const viewBox = document.getElementById(`perm_view_${pid}`);
                const editBox = document.getElementById(`perm_edit_${pid}`);
                if (viewBox) viewBox.checked = !!perms[pid].view;
                if (editBox) editBox.checked = !!perms[pid].edit;
            });

            modal.classList.remove('hidden');
             setTimeout(() => {
                modal.classList.remove('opacity-0'); 
            }, 10);
            document.body.classList.add('overflow-hidden');
        })
        .catch(err => {
            console.error(err);
            alert('Failed to fetch role details.');
        });
}

function toggleAllPermissions() {
    const checkboxes = document.querySelectorAll('#roleForm input[type="checkbox"]');
    const isAnyUnchecked = Array.from(checkboxes).some(cb => !cb.checked);
    checkboxes.forEach(cb => cb.checked = isAnyUnchecked);
}
</script>
@endpush