<!-- Video Section Tab -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Promotional Video Section</h3>
        <p class="text-gray-600 mb-6">Add a promotional video to showcase your brand. YouTube or Vimeo links are supported.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Form -->
            <form action="{{ route('admin.home-video.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-span-1">
                    <!-- Video URL -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="videoUrl">Video URL</label>
                        <input type="text" id="videoUrl" name="video_link"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="https://youtube.com/watch?v=..."
                            value="{{ old('video_link', optional($video)->video_link) }}">
                    </div>



                    <!-- Thumbnail Upload -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2">Video Thumbnail</label>
                        <div class="upload-area cursor-pointer" onclick="document.getElementById('videoThumbnail').click()">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500">Click to upload a custom thumbnail</p>
                            <p class="text-xs text-gray-400 mt-1">JPG or PNG up to 5MB</p>
                            <input type="file" id="videoThumbnail" name="thumbnail" class="hidden" accept="image/*">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">If no thumbnail is uploaded, YouTube/Vimeo thumbnail will be used</p>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end mt-6">
                        <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            Save Video Settings
                        </button>
                    </div>
                </div>
            </form>

            <!-- Preview -->
            @php
    $youtubeID = getYouTubeID(optional($video)->video_link);
@endphp

@if ($youtubeID)
    <iframe 
        class="w-full h-full rounded-lg"
        src="https://www.youtube.com/embed/{{ $youtubeID }}?autoplay=1&mute=1&loop=1&playlist={{ $youtubeID }}"
        frameborder="0"
        allow="autoplay; encrypted-media"
        allowfullscreen>
    </iframe>
@else
    <img src="https://via.placeholder.com/400x200?text=No+Video" alt="No Video" class="w-full h-48 object-cover rounded-lg">
@endif


        </div>
    </div>
</div>


<?php

if (!function_exists('getYouTubeID')) {
    function getYouTubeID($url)
    {
        preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);
        return $matches[1] ?? null;
    }
}
