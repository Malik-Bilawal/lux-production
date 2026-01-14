<div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Home Page Slider Images</h3>
            <p class="text-gray-600 mb-6">Upload images for the homepage carousel. Images should be high-quality and consistent in dimensions.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Upload Form -->
                <div class="col-span-1">
                    <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Upload New Slider Images</label>
                            <div class="upload-area" onclick="document.getElementById('sliderUpload').click()">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500">Click to upload or drag and drop</p>
                                <p class="text-xs text-gray-400 mt-1">JPG or PNG up to 5MB each</p>
                                <input type="file" name="images[]" id="sliderUpload" class="hidden" accept="image/*" multiple>
                            </div>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Upload</button>
                    </form>

                    <div class="bg-gray-50 p-4 rounded-lg mt-6">
                        <h4 class="font-medium text-gray-700 mb-3">Current Slider Images</h4>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach($sliders as $slider)
                            <div class="relative group">
                                <img src="{{ asset($slider->image) }}" class="w-full h-24 object-cover rounded-md image-preview">
                                <form action="{{ route('admin.sliders.delete', $slider->id) }}" method="POST" class="absolute top-1 right-1">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-sm text-gray-500">
                            <p><i class="fas fa-info-circle mr-2"></i> Images will be displayed in the order shown above</p>
                        </div>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="col-span-1">
                    <div class="bg-gray-800 p-4 rounded-lg">
                        <h4 class="font-medium text-white mb-3">Preview</h4>
                        <div class="bg-white p-4 rounded-lg">
                            <div class="relative overflow-hidden rounded-lg h-64">
                                <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent"></div>
                                @if(count($sliders))
                                <img src="{{ asset($sliders[0]->image) }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">No Preview Available</div>
                                @endif
                                <div class="absolute bottom-0 left-0 p-6 text-white">
                                </div>
                                <div class="absolute bottom-6 right-6 flex space-x-2">
                                    <button class="w-3 h-3 rounded-full bg-white"></button>
                                    <button class="w-3 h-3 rounded-full bg-white/30"></button>
                                    <button class="w-3 h-3 rounded-full bg-white/30"></button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>