@extends('admin.layouts.master-layouts.plain')

@section('title', 'Edit Product Gallery | Admin Panel')

@push("style")
<style>
    body {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .input-field {
        @apply w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition;
    }
    .btn-primary {
        @apply bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl px-6 py-3 flex items-center justify-center transition;
    }
    .btn-secondary {
        @apply bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl px-6 py-3 flex items-center justify-center transition;
    }
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }
</style>
@endpush

@section('content')
<div class="flex h-screen overflow-hidden">
    <aside class="w-64 bg-white shadow h-screen fixed top-0 left-0">
        @include('admin.layouts.master-layouts.sidebar')
    </aside>

    <div class="ml-64 flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100 p-6">
        <div class="w-full max-w-screen-xl mx-auto">
            <div class="bg-white rounded-2xl shadow p-8">

                <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-image text-blue-600 mr-3"></i>
                        Edit Product Gallery
                    </h1>
                </div>

                <form action="{{ route('admin.products.gallery-update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Add to Cart URL</label>
                            <input type="url" name="add_to_cart_uri" class="input-field" required value="{{ old('add_to_cart_uri', $product->gallery->add_to_cart_uri) }}">
                            </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Buy Now URL</label>
                            <input type="url" name="buy_now_uri" class="input-field" required value="{{ old('buy_now_uri', $product->gallery->buy_now_uri) }}">
                            </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Craftsmanship Description</label>
                            <textarea name="craftsmanship_desc" rows="4" class="input-field" required>{{ old('craftsmanship_desc', $product->gallery->craftsmanship_desc) }}</textarea>
                            </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Material Description</label>
                            <textarea name="material_desc" rows="4" class="input-field" required>{{ old('material_desc', $product->gallery->material_desc) }}</textarea>
                            </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Key Features</label>
                            <textarea name="key_features" rows="4" class="input-field" required>{{ old('key_features', $product->gallery->key_features) }}</textarea>
                            </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Banner Image</label>
                            <input type="file" name="banner" accept="image/*" class="input-field">
                            <div id="bannerPreview" class="mt-4 max-w-xs h-32 overflow-hidden rounded border border-gray-300">
                                @if($product->gallery->banner)
                                    <img src="{{ asset('storage/' . $product->gallery->banner) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block font-semibold mb-2">Gallery Images</label>
                            <input type="file" name="images[]" id="galleryInput" accept="image/*" multiple class="input-field">
                            <div id="galleryPreview" class="gallery-grid mt-4">
                                @foreach ($product->images as $image)
                                    <div class="rounded-xl overflow-hidden shadow border">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-40 object-cover">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="btn-primary">Update</button>
                        <button type="button" onclick="resetForm()" class="btn-secondary">Reset</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push("script")
<script>
    const galleryInput = document.getElementById('galleryInput');
    const galleryPreview = document.getElementById('galleryPreview');
    const bannerInput = document.querySelector('input[name="banner"]');
    const bannerPreview = document.getElementById('bannerPreview');

    bannerInput.addEventListener('change', function () {
        bannerPreview.innerHTML = '';
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            bannerPreview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(file);
    });

    galleryInput.addEventListener('change', function () {
        galleryPreview.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                galleryPreview.innerHTML += `
                    <div class="rounded-xl overflow-hidden shadow border">
                        <img src="${e.target.result}" class="w-full h-40 object-cover">
                    </div>`;
            };
            reader.readAsDataURL(file);
        });
    });

    function resetForm() {
        document.getElementById('productForm').reset();
        galleryPreview.innerHTML = '';
        bannerPreview.innerHTML = '';
    }
</script>
@endpush
