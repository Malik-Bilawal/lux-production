@extends("admin.layouts.master-layouts.plain")

<title>Offer | Edit</title>

@section("content")

        
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Edit Offer</h2>
            @include("admin.components.dark-mode.dark-toggle")

            <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Category -->
                <div class="mb-4">
                    <label class="block mb-2 font-medium text-gray-700">Select Category</label>
                    <select id="categorySelect" class="border rounded p-2 w-full" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ $offer->product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Product -->
                <div class="mb-4">
                    <label class="block mb-2 font-medium text-gray-700">Select Product</label>
                    <select name="product_id" id="productSelect" class="border rounded p-2 w-full" required>
                        <option value="">-- Select Product --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                {{ $offer->product_id == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Title -->
                <div class="mb-4">
                    <label class="block mb-2 font-medium text-gray-700">Offer Title</label>
                    <input type="text" name="title" value="{{ $offer->title }}" class="border rounded p-2 w-full" required>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="block mb-2 font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="border rounded p-2 w-full" required>{{ $offer->description }}</textarea>
                </div>

                <!-- Caption -->
                <div class="mb-4">
                    <label class="block mb-2 font-medium text-gray-700">Caption</label>
                    <input type="text" name="caption" value="{{ $offer->caption }}" class="border rounded p-2 w-full">
                </div>

                <!-- Tags -->
                <div class="mb-4">
                    <label class="block mb-2 font-medium text-gray-700">Tags (comma separated)</label>
                    <input type="text" name="tags" value="{{ $offer->tags }}" class="border rounded p-2 w-full">
                </div>

                <!-- Timer -->
                <div class="mb-4">
                    <label class="block mb-2 font-medium text-gray-700">Offer Timer</label>
                    <div class="flex gap-2">
                        <input type="number" name="days" min="0" max="30" placeholder="Days" value="{{ $offer->days }}" class="border rounded p-2 w-20">
                        <input type="number" name="hours" min="0" max="23" placeholder="Hours" value="{{ $offer->hours }}" class="border rounded p-2 w-20">
                        <input type="number" name="minutes" min="0" max="59" placeholder="Minutes" value="{{ $offer->minutes }}" class="border rounded p-2 w-20">
                        <input type="number" name="seconds" min="0" max="59" placeholder="Seconds" value="{{ $offer->seconds }}" class="border rounded p-2 w-20">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Update Offer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('categorySelect').addEventListener('change', function() {
        let categoryId = this.value;
        let productSelect = document.getElementById('productSelect');
        productSelect.innerHTML = '<option>Loading...</option>';

        if (categoryId) {
            let url = "{{ route('admin.products.byCategory', ['categoryId' => ':id']) }}".replace(':id', categoryId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    productSelect.innerHTML = '<option value="">-- Select Product --</option>';
                    data.forEach(product => {
                        productSelect.innerHTML += `<option value="${product.id}">${product.name}</option>`;
                    });
                })
                .catch(error => console.error('Error:', error));
        } else {
            productSelect.innerHTML = '<option value="">-- Select Product --</option>';
        }
    });
</script>
@endsection
