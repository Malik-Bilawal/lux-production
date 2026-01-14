@extends('admin.layouts.master-layouts.plain')

@section('title', 'Edit Category | Luxorix Admin')

@section('content')

<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="bg-amber-500 w-10 h-10 rounded-xl flex items-center justify-center">
                <i class="fas fa-edit text-white text-lg"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Collection: {{ $category->name }}</h1>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-gray-700">
            &larr; Back to List
        </a>
    </div>

    <form class="space-y-8" method="POST" action="{{ route('admin.categories.update', $category->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- SECTION 1: GENERAL INFO --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-500"></i> General Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Internal Name</label>
                    <input type="text" name="name" value="{{ $category->name }}" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-gray-50" required>
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Display Title</label>
                    <input type="text" name="title" value="{{ $category->title }}" class="w-full border border-gray-300 rounded-xl px-4 py-3">
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ $category->sort_order }}" class="w-full border border-gray-300 rounded-xl px-4 py-3">
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Home Sort Order</label>
                    <input type="number" name="home_sort_order" value="{{ $category->home_sort_order }}" class="w-full border border-gray-300 rounded-xl px-4 py-3">
                </div>

                <div class="col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-xl px-4 py-3">{{ $category->description }}</textarea>
                </div>
            </div>
        </div>

        {{-- SECTION 2: PRIMARY VISUALS --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-image text-blue-500"></i> Primary Asset
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Banner Image</label>
                    <div class="relative group">
                        <div id="imagePreviewPrimary" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl h-64 flex items-center justify-center cursor-pointer transition hover:border-blue-500 relative overflow-hidden">
                            
                            {{-- SHOW EXISTING IMAGE IF AVAILABLE --}}
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" class="w-full h-full object-cover rounded-xl" alt="Current Banner">
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-sm font-medium"><i class="fas fa-exchange-alt mr-2"></i>Click to Replace</span>
                                </div>
                            @else
                                <div class="text-center p-4">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-500 text-sm">Upload to set banner</p>
                                </div>
                            @endif

                        </div>
                        <input type="file" name="image" id="imageUploadPrimary" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                    </div>
                </div>

                <div class="flex flex-col justify-center">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vibe Tagline</label>
                    <input type="text" name="tagline" value="{{ $category->tagline }}" class="w-full border border-gray-300 rounded-xl px-4 py-3" placeholder="e.g. Essential Form">
                </div>
            </div>
        </div>

        {{-- SECTION 3: SECONDARY VISUALS --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
             <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-expand-arrows-alt text-amber-500"></i> Secondary Asset
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Banner</label>
                    <div class="relative group">
                        <div id="imagePreviewSecondary" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl h-64 flex items-center justify-center cursor-pointer transition hover:border-amber-500 relative overflow-hidden">
                            
                            {{-- SHOW EXISTING SECOND IMAGE --}}
                            @if($category->second_image)
                                <img src="{{ asset('storage/' . $category->second_image) }}" class="w-full h-full object-cover rounded-xl" alt="Current Banner">
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-sm font-medium"><i class="fas fa-exchange-alt mr-2"></i>Click to Replace</span>
                                </div>
                            @else
                                <div class="text-center p-4">
                                    <i class="fas fa-layer-group text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-500 text-sm">Upload to enable Double Grid</p>
                                </div>
                            @endif

                        </div>
                        <input type="file" name="second_image" id="imageUploadSecondary" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                    </div>
                </div>

                <div class="flex flex-col justify-center">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Secondary Tagline</label>
                    <input type="text" name="second_tagline" value="{{ $category->second_tagline }}" class="w-full border border-gray-300 rounded-xl px-4 py-3" placeholder="e.g. Extended Archive">
                </div>
            </div>
        </div>

        {{-- STATUS & ACTION --}}
        <div class="bg-gray-50 p-6 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-6">
             <div class="flex items-center gap-6">
                <span class="text-sm font-medium text-gray-700">Status:</span>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="1" {{ $category->status == 1 ? 'checked' : '' }} class="hidden peer">
                    <span class="px-4 py-2 rounded-lg bg-white border border-gray-300 text-gray-500 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 transition-all shadow-sm">Active</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="radio" name="status" value="0" {{ $category->status == 0 ? 'checked' : '' }} class="hidden peer">
                    <span class="px-4 py-2 rounded-lg bg-white border border-gray-300 text-gray-500 peer-checked:bg-red-500 peer-checked:text-white peer-checked:border-red-500 transition-all shadow-sm">Inactive</span>
                </label>
            </div>

            <div class="flex space-x-4 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl px-8 py-3 shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-1">
                    Update Collection
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

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
                        // This replaces the content with the new image preview
                        preview.innerHTML = `
                            <div class="relative w-full h-full group">
                                <img src="${reader.result}" class="w-full h-full object-cover rounded-xl" alt="Preview">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center rounded-xl">
                                    <span class="bg-white px-3 py-1 text-xs font-bold rounded-full shadow-lg text-black">New Image Selected</span>
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