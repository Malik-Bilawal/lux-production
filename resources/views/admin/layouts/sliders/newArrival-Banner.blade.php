            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">New Arrivals Banner</h3>
                    <p class="text-gray-600 mb-6">Update the banner for the new arrivals section. This appears on the homepage.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <form action="{{ route('admin.new-arrival-banner.update') }}" method="POST" enctype="multipart/form-data">
    @csrf


    <!-- Upload Image -->
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-medium mb-2">Banner Image</label>
        <div class="upload-area cursor-pointer" onclick="document.getElementById('arrivalsImage').click()">
            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
            <p class="text-gray-500">Click to upload banner image</p>
            <p class="text-xs text-gray-400 mt-1">JPG or PNG up to 2MB</p>
            <input type="file" id="arrivalsImage" name="image" class="hidden" accept="image/*">
        </div>
        @error('image')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Status Toggle -->
    <div class="mb-4">
        <label class="flex items-center space-x-2">
            <input type="checkbox" name="status" value="1" {{ isset($banner) && $banner->status ? 'checked' : '' }}>
            <span class="text-sm text-gray-700">Active</span>
        </label>
    </div>

    <!-- Current Banner -->
    <div class="mt-6">
        <h4 class="font-medium text-gray-700 mb-3">Current Banner</h4>
        @if(isset($banner))
            <img src="{{ asset('storage/' . $newArrivalBanner->image) }}" alt="Current New Arrivals Banner" class="w-full rounded-lg">
        @else
            <p class="text-sm text-gray-400">No banner uploaded yet.</p>
        @endif
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end mt-6">
        <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
            Update Banner
        </button>
    </div>
</form>
                        <!-- Preview -->
                        <div class="col-span-1">
                            <div class="bg-gray-800 p-4 rounded-lg">
                                <h4 class="font-medium text-white mb-3">Preview</h4>
                                <div class="bg-white p-4 rounded-lg">
                                    <div class="relative rounded-lg overflow-hidden">
                                        <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1287&q=80" alt="New Arrivals Preview" class="w-full rounded-lg">
                                    </div>
                                    <div class="mt-4 text-sm text-gray-500 text-center">
                                        <p><i class="fas fa-info-circle mr-1"></i> This banner appears without any text overlay</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
 >