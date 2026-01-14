@extends('admin.layouts.master-layouts.plain')

@section('title', 'Product Store | Luxorix | Admin Panel')

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
                        <i class="fas fa-box-open text-lg"></i>
                    </div>
                    <h1 class="text-2xl font-semibold text-gray-800">Add New Product</h1>
                </div>
                @include('admin.components.dark-mode.dark-toggle')
            </div>

            <!-- Product Form -->
            <form method="POST" action="{{ route('admin.products.create') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Product Name</label>
                        <input type="text" name="name" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary" placeholder="e.g. Rolex Gold Watch">
                    </div>
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Category</label>
                        <select name="category_id" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Price ($)</label>
                        <input type="number" name="price" step="0.01" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Discounted Price ($)</label>
                        <input type="number" name="cut_price" step="0.01" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="text-gray-700 font-medium mb-1 block">Description</label>
                    <textarea name="description" rows="5" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary" placeholder="Write product details..."></textarea>
                </div>

                <!-- Images -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Main Image -->
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Main Product Image</label>
                        <div id="mainImagePreview" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl h-52 flex items-center justify-center cursor-pointer hover:border-primary transition">
                            <div class="text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500 text-sm">Click to upload</p>
                            </div>
                        </div>
                        <input type="file" name="image" id="mainImageUpload" class="hidden" accept="image/*">
                    </div>

                    <!-- Sub Image -->
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Sub Image (Hover)</label>
                        <div id="subImagePreview" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl h-52 flex items-center justify-center cursor-pointer hover:border-primary transition">
                            <div class="text-center">
                                <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500 text-sm">Click to upload</p>
                            </div>
                        </div>
                        <input type="file" name="sub_image" id="subImageUpload" class="hidden" accept="image/*">
                    </div>

                    <!-- Gallery Images -->
                    <div>
  <label class="text-gray-700 font-medium mb-1 block">Gallery Images (Multiple)</label>

  <!-- Gallery Preview -->
  <div id="galleryPreview" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3 mb-3"></div>

  <!-- Upload Area -->
  <div id="galleryArea"
       class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl h-44 flex items-center justify-center cursor-pointer hover:border-primary hover:bg-gray-100 transition">
      <div class="text-center">
          <i class="fas fa-images text-3xl text-gray-400 mb-2"></i>
          <p class="text-gray-500 text-sm">Click to upload images</p>
      </div>
  </div>

  <input type="file" name="gallery_images[]" id="galleryUpload" class="hidden" multiple accept="image/*">
</div>


                <!-- Tags -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Tags</label>
                        <input type="text" name="tags" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary" placeholder="e.g. Luxury, Gold, Trending">
                    </div>

                    <div>
                        <label class="text-gray-700 font-medium mb-1 block">Status</label>
                        <div class="flex items-center gap-6 mt-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="active" checked class="text-primary">
                                <span class="text-gray-700 text-sm">Active</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="inactive" class="text-primary">
                                <span class="text-gray-700 text-sm">Inactive</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-gray-700 font-medium mb-3 block">Product Flags</label>
                    <div class="flex gap-4 flex-wrap">
                        <label class="flex items-center space-x-2 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="is_top_selling"  class="text-primary rounded">
                            <span class="text-sm text-gray-700">Top Selling</span>
                        </label>
                        <label class="flex items-center space-x-2 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="is_new_arrival"  class="text-primary rounded">
                            <span class="text-sm text-gray-700">New Arrival</span>
                        </label>
                        <label class="flex items-center space-x-2 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 cursor-pointer hover:bg-gray-100">
                            <input type="checkbox" name="is_feature_card"  class="text-primary rounded">
                            <span class="text-sm text-gray-700">Featured</span>
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 pt-6 border-t">
                    <button type="reset" class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 rounded-xl px-6 py-3">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </button>
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white rounded-xl px-6 py-3">
                        <i class="fas fa-save mr-2"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    // Main Image Preview
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

    // Sub Image Preview
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

const galleryUpload = document.getElementById('galleryUpload');
const galleryArea = document.getElementById('galleryArea');
const galleryPreview = document.getElementById('galleryPreview');

let selectedFiles = [];

galleryArea.addEventListener('click', () => galleryUpload.click());

galleryUpload.addEventListener('change', function () {
    selectedFiles = [...selectedFiles, ...Array.from(this.files)];

    renderGallery();
});

function renderGallery() {
    galleryPreview.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            const wrapper = document.createElement('div');
            wrapper.className = 'relative group rounded-lg overflow-hidden border border-gray-300 shadow-sm aspect-square'; // Square preview

            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-full h-full object-cover rounded-lg';

            const removeBtn = document.createElement('button');
            removeBtn.innerHTML = '<i class="fas fa-times text-xs"></i>';
            removeBtn.className = 'absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition';

            removeBtn.addEventListener('click', () => {
                selectedFiles.splice(index, 1);
                renderGallery();
            });

            wrapper.appendChild(img);
            wrapper.appendChild(removeBtn);
            galleryPreview.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
    });

    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(f => dataTransfer.items.add(f));
    galleryUpload.files = dataTransfer.files;
}

</script>
@endpush
