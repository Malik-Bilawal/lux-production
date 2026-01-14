@extends("admin.layouts.master-layouts.plain")

@section('title', 'Category Management | Luxorix | Admin Panel')

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
.category-card {
            transition: all 0.3s ease;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .category-image {
            transition: transform 0.3s ease;
        }
        .category-image:hover {
            transform: scale(1.05);
        }
        .status-badge {
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
        .description-clamp {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush
@section('content')


<header class="bg-white shadow-sm">
    <div class="flex flex-col sm:flex-row justify-between items-center py-4 px-6 gap-4">
        
        <div class="w-full sm:w-auto">
            <h2 class="text-xl font-semibold text-gray-800">Category Management</h2>
            <p class="text-sm text-gray-500">Organize your product categories</p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            
            <form class="relative w-full sm:w-auto">
                <input type="text" placeholder="Search categories..." class="border rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </form>
            
            <a href="{{ route('admin.categories.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition w-full sm:w-auto text-center">
               + Add Category
            </a>
        </div>
        
        @include("admin.components.dark-mode.dark-toggle")
    </div>
</header>

<section class="p-6 flex-1 ml-64">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        @foreach ($categories as $category)
            <div class="bg-white rounded-xl shadow-md overflow-hidden category-card">
                <div class="relative">
                    <img src="{{ asset('storage/' . $category->image) }}" 
                         class="w-full h-48 object-cover category-image" alt="{{ $category->name }}">
                    
                    <div class="absolute top-3 right-3">
                        <span class="status-badge {{ $category->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            {{ $category->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $category->name }}</h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $category->products_count ?? 0 }} products
                        </span>
                    </div>
                    <p class="text-gray-600 mt-2 text-sm description-clamp">
                        {{ $category->description ?? 'No description provided.' }}
                    </p>
                    <div class="flex mt-4 space-x-2">
                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                           class="text-blue-500 h-full hover:text-blue-700 w-full py-2 rounded-lg border border-blue-500 text-center">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-500 hover:text-red-700 w-full py-2 rounded-lg border border-red-500"
                                    onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</section>

<div id="deleteConfirmation" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">Confirm Deletion</h3>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-4">Are you sure you want to delete this category?</p>
            <p class="text-red-500 text-sm mb-6">Note: Deleting a category will also delete all products associated with it.</p>
            <div class="flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors" onclick="closeDeleteConfirmation()">
                    Cancel
                </button>
                <button type="button" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Delete Category
                </button>
            </div>
        </div>
    </div>
</div>
@push("script")
<script>
    function setupImagePreview(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        if(input && preview) {
            input.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function () {
                        // Keep the existing structure but replace image
                        preview.innerHTML = `
                            <div class="relative w-full h-full">
                                <img src="${reader.result}" class="w-full h-full object-cover rounded-xl" alt="Preview">
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center rounded-xl">
                                    <span class="bg-white px-2 py-1 text-xs rounded shadow">New Image Selected</span>
                                </div>
                            </div>
                        `;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    // Initialize both uploaders
    setupImagePreview('imageUploadPrimary', 'imagePreviewPrimary');
    setupImagePreview('imageUploadSecondary', 'imagePreviewSecondary');
</script>
@endpush