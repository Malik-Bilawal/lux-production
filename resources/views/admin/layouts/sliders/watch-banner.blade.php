<!-- Watches Hero Tab -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Watches Collection Hero Section</h3>
            <p class="text-gray-600 mb-6">Customize the hero banner for the watches collection page.</p>

            <form action="{{ route('admin.watch-banner.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Form -->
                    <div class="col-span-1">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Title</label>
                            <input type="text" name="title" class="w-full border rounded-lg px-4 py-2" placeholder="Enter title"
                                value="{{ old('title', $watchBanner->title ?? '') }}">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Description</label>
                            <textarea name="description" rows="2" class="w-full border rounded-lg px-4 py-2"
                                placeholder="Enter description">{{ old('description', $watchBanner->description ?? '') }}</textarea>
                        </div>
                        <div class="mb-4">
    <label class="block text-gray-700 text-sm font-medium mb-2">Tags</label>
    <input type="text" name="tags" class="w-full border rounded-lg px-4 py-2" placeholder="e.g. luxury, men, sports"
        value="{{ old('tags', $watchBanner->tags ?? '') }}">
</div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Button Text</label>
                                <input type="text" name="button_text" class="w-full border rounded-lg px-4 py-2"
                                    placeholder="Shop Now" value="{{ old('button_text', $watchBanner->button_text ?? '') }}">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Button URL</label>
                                <input type="text" name="button_url" class="w-full border rounded-lg px-4 py-2"
                                    placeholder="/collection/watches" value="{{ old('button_url', $watchBanner->button_url ?? '') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Hero Image</label>
                            <input type="file" accept="image/" name="image" class="w-full border px-4 py-2 rounded">
                            @if (!empty($watchBanner->image))
                                <img src="{{ asset('storage/' . $watchBanner->image) }}" class="h-28 mt-2 rounded shadow">
                            @endif
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                Save Watches Hero
                            </button>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="col-span-1">
                        <div class="bg-gray-800 p-4 rounded-lg">
                            <h4 class="font-medium text-white mb-3">Preview</h4>
                            <div class="relative rounded-lg overflow-hidden h-80">
                                <div class="absolute inset-0 bg-gradient-to-r from-black/30 to-transparent"></div>
                                <img src="{{ !empty($watchBanner->image) ? asset('storage/' . $watchBanner->image) : 'https://via.placeholder.com/400x300' }}"
                                    alt="Watches Hero Preview" class="w-full h-full object-cover">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="text-white p-8 max-w-md">
                                        <h3 class="text-2xl font-bold">{{ $watchBanner->title ?? 'Premium Watches Collection' }}</h3>
                                        <p class="mt-2">{{ $watchBanner->description ?? 'Discover timeless elegance with our curated selection of premium watches.' }}</p>
                                        @if(!empty($watchBanner->button_text))
                                            <a href="{{ $watchBanner->button_url ?? '#' }}" class="mt-4 inline-block bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                                                {{ $watchBanner->button_text }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
