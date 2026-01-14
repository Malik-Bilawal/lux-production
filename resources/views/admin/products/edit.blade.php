@extends('admin.layouts.master-layouts.plain')

@section('title', 'Product Update | Luxorix | Admin Panel')

@push('script')
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#4f46e5',
                    secondary: '#f9fafb',
                    success: '#10b981',
                    danger: '#ef4444',
                    warning: '#f59e0b',
                    border: '#e5e7eb',
                    text: '#1f2937',
                    'text-light': '#6b7280'
                },
                boxShadow: {
                    card: '0 6px 14px rgba(0,0,0,0.06)',
                }
            }
        }
    }
</script>
@endpush

@section('content')


            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-5 mb-8">
                <div class="flex items-center space-x-3">
                    <div class="bg-primary text-white w-11 h-11 flex items-center justify-center rounded-xl">
                        <i class="fas fa-edit text-lg"></i>
                    </div>
                    <h1 class="text-2xl font-semibold text-gray-800">Edit Product</h1>
                </div>
                @include('admin.components.dark-mode.dark-toggle')
            </div>

            <!-- Product Form -->
            <form id="productForm" method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Product Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary" placeholder="e.g. Rolex Gold Watch">
                    </div>
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Category</label>
                        <select name="category_id" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id) == $cat->id) ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Price ($)</label>
                        <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Discounted Price ($)</label>
                        <input type="number" name="cut_price" step="0.01" value="{{ old('cut_price', $product->cut_price) }}" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="text-gray-700 font-medium mb-1 block">Description</label>
                    <textarea name="description" rows="5" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary" placeholder="Write product details...">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Images (main, sub, gallery) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Main Image -->
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Main Product Image</label>
                        <div id="mainImagePreview" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl h-52 flex items-center justify-center cursor-pointer hover:border-primary transition overflow-hidden">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover rounded-xl" id="currentMainImage" />
                            @else
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-500 text-sm">Click to upload</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" name="image" id="mainImageUpload" class="hidden" accept="image/*">
                        <!-- If admin wants to delete/replace old main image, we just replace on upload -->
                    </div>

                    <!-- Sub Image -->
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Sub Image (Hover)</label>
                        <div id="subImagePreview" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl h-52 flex items-center justify-center cursor-pointer hover:border-primary transition overflow-hidden">
                            @if ($product->sub_image)
                                <img src="{{ asset('storage/' . $product->sub_image) }}" class="w-full h-full object-cover rounded-xl" id="currentSubImage" />
                            @else
                                <div class="text-center">
                                    <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-500 text-sm">Click to upload</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" name="sub_image" id="subImageUpload" class="hidden" accept="image/*">
                    </div>

                    <!-- Gallery Images -->
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Gallery Images</label>

                        <!-- existing + preview container -->
                        <div id="galleryPreviewContainer" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3 mb-3">
                            {{-- Existing gallery images --}}
                            @foreach($product->images as $img)
                                <div class="relative group rounded-lg overflow-hidden border border-gray-300 bg-gray-50">
                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-28 object-cover" />
                                <button type="button"
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition"
                                            onclick="markForDeletion({{ $img->id }}, this)">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <!-- Upload Area -->
                        <div id="galleryArea" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl h-44 flex items-center justify-center cursor-pointer hover:border-primary hover:bg-gray-100 transition">
                            <div class="text-center">
                                <i class="fas fa-images text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500 text-sm">Click to upload images</p>
                                <p class="text-xs text-gray-400 mt-1">You can add multiple images</p>
                            </div>
                        </div>
                        <input type="file" name="gallery_images[]" id="galleryUpload" class="hidden" multiple accept="image/*">

                        <!-- Hidden container where we will append deleted gallery ids -->
                        <div id="deletedGalleryInputs"></div>
                    </div>
                </div>

                <!-- Tags and Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Tags</label>
                        <input type="text" name="tags" value="{{ old('tags', $product->tags) }}" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary" placeholder="e.g. Luxury, Gold, Trending">
                    </div>

                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Status</label>
                        <div class="flex items-center gap-6 mt-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="active" {{ old('status', $product->status) == 'active' ? 'checked' : '' }} class="text-primary">
                                <span class="text-gray-700 text-sm">Active</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="inactive" {{ old('status', $product->status) == 'inactive' ? 'checked' : '' }} class="text-primary">
                                <span class="text-gray-700 text-sm">Inactive</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Flags -->
                <div>
                    <label class="text-gray-700 font-medium mb-3 block">Product Flags</label>
                    <div class="flex gap-4 flex-wrap">
                        <label class="flex items-center space-x-2 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="is_top_selling" {{ old('is_top_selling', $product->is_top_selling) ? 'checked' : '' }} class="text-primary rounded">
                            <span class="text-sm text-gray-700">Top Selling</span>
                        </label>
                        <label class="flex items-center space-x-2 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="is_new_arrival" {{ old('is_new_arrival', $product->is_new_arrival) ? 'checked' : '' }} class="text-primary rounded">
                            <span class="text-sm text-gray-700">New Arrival</span>
                        </label>
                        <label class="flex items-center space-x-2 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="is_feature_card" {{ old('is_feature_card', $product->is_feature_card) ? 'checked' : '' }} class="text-primary rounded">
                            <span class="text-sm text-gray-700">Featured</span>
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 pt-6 border-t">
                    <a href="{{ route('admin.products.index') }}" class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 rounded-xl px-6 py-3">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white rounded-xl px-6 py-3">
                        <i class="fas fa-save mr-2"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    // ------- MAIN / SUB image preview & replace -------
    const mainImageUpload = document.getElementById('mainImageUpload');
    const mainImagePreview = document.getElementById('mainImagePreview');
    mainImagePreview.addEventListener('click', () => mainImageUpload.click());
    mainImageUpload.addEventListener('change', e => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = ev => mainImagePreview.innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover rounded-xl">`;
            reader.readAsDataURL(file);
        }
    });

    const subImageUpload = document.getElementById('subImageUpload');
    const subImagePreview = document.getElementById('subImagePreview');
    subImagePreview.addEventListener('click', () => subImageUpload.click());
    subImageUpload.addEventListener('change', e => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = ev => subImagePreview.innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover rounded-xl">`;
            reader.readAsDataURL(file);
        }
    });

    // -------- GALLERY: mark existing images for deletion (no AJAX) --------
    function markForDeletion(imageId, btn) {
        // create hidden input inside #deletedGalleryInputs
        const container = document.getElementById('deletedGalleryInputs');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'deleted_gallery_ids[]';
        input.value = imageId;
        container.appendChild(input);

        btn.closest('div').remove();
    }

    const galleryArea = document.getElementById('galleryArea');
    const galleryUpload = document.getElementById('galleryUpload');
    const galleryPreviewContainer = document.getElementById('galleryPreviewContainer');

    let selectedNewFiles = []; 

    galleryArea.addEventListener('click', () => galleryUpload.click());

    galleryUpload.addEventListener('change', function() {
        selectedNewFiles = [...selectedNewFiles, ...Array.from(this.files)];
        renderNewGalleryPreviews();
    });

    function renderNewGalleryPreviews() {

        galleryPreviewContainer.querySelectorAll('[data-new="1"]').forEach(n => n.remove());

        selectedNewFiles.forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group rounded-lg overflow-hidden border border-gray-300 bg-gray-50';
                wrapper.setAttribute('data-new', '1');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-28 object-cover';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition';
                removeBtn.innerHTML = '<i class="fas fa-times text-xs"></i>';

                removeBtn.addEventListener('click', () => {
                    selectedNewFiles.splice(idx, 1);
                    renderNewGalleryPreviews();
                    updateGalleryInputFiles();
                });

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                galleryPreviewContainer.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });

        updateGalleryInputFiles();
    }

    function updateGalleryInputFiles() {
        const dt = new DataTransfer();
        selectedNewFiles.forEach(f => dt.items.add(f));
        galleryUpload.files = dt.files;
    }

</script>
@endpush
