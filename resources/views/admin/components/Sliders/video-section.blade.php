<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-6">
        
        <style>
          .upload-area {
            border: 2px dashed #cbd5e1; /* gray-300 */
            border-radius: 0.5rem; /* rounded-lg */
            padding: 1.5rem; /* p-6 */
            text-align: center;
            cursor: pointer;
            background-color: #f8fafc; /* gray-50 */
            transition: background-color 0.2s;
          }
          .upload-area:hover {
            background-color: #f1f5f9; /* gray-100 */
          }
        </style>

        <h3 class="text-lg font-semibold text-gray-800 mb-4">Promotional Video Section</h3>
        <p class="text-gray-600 mb-6">Add a promotional video to showcase your brand. Upload an MP4 file directly.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <form action="{{ route('admin.home-video.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Upload MP4 Video</label>
                    <input type="file" name="video" accept="video/mp4" 
                           class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Only MP4 format. Max size: 20MB</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Video Thumbnail</label>
                    <div class="upload-area cursor-pointer" onclick="document.getElementById('videoThumbnail').click()">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Click to upload a custom thumbnail</p>
                        <p class="text-xs text-gray-400 mt-1">JPG or PNG up to 5MB</p>
                        <input type="file" id="videoThumbnail" name="thumbnail" class="hidden" accept="image/*">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">If no thumbnail is uploaded, the browser will use a default frame.</p>
                </div>

                <div class="flex justify-end mt-6">
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors w-full sm:w-auto">
                        Save Video Settings
                    </button>
                </div>
            </form>

            <div>
                @if ($video && $video->video_link)
                    <video 
                        src="{{ asset('storage/' . $video->video_link) }}" 
                        autoplay 
                        loop 
                        muted 
                        playsinline 
                        class="w-full h-64 rounded-lg object-cover">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <div class="w-full h-64 rounded-lg bg-gray-200 flex items-center justify-center">
                        <p class="text-gray-500">No Video Preview</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>