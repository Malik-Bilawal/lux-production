@extends("admin.layouts.master-layouts.plain")
<!-- Alpine.js CDN (latest v3) -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<title></title>

@push("script")
<script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        dark: '#1F2937',
                        light: '#F9FAFB'
                    }
                }
            }
        }
    </script>
@endpush


@push("style")
<style>
        .review-avatar {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .review-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
        }
        .action-btn {
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }
        .rating-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
        }
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            color: white;
            font-weight: 500;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            z-index: 1000;
        }
        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        .toast.success {
            background-color: #10B981;
        }
        .toast.error {
            background-color: #EF4444;
        }
        .toast.info {
            background-color: #3B82F6;
        }
        .email-link {
            transition: all 0.2s ease;
        }
        .email-link:hover {
            color: #3B82F6;
            text-decoration: underline;
        }
        .star-rating {
            color: #FBBF24;
        }
        .reply-indicator {
            background-color: #F3F4F6;
            border-left: 3px solid #3B82F6;
        }
        .review-content {
            max-height: 100px;
            overflow-y: auto;
        }
    </style>
@endpush


@section("content")

<div class="flex-1">

<header class="bg-white shadow-sm">
    <div class="flex flex-wrap justify-between items-center py-4 px-6 gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Customer Reviews</h2>
            <p class="text-sm text-gray-500">Manage and moderate customer reviews</p>
        </div>
        <div class="flex items-center w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <input type="text" placeholder="Search reviews..." class="border rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>
</header>

<section class="px-6 py-4 bg-white shadow-sm mt-1">
    <div class="flex flex-wrap items-end gap-4">
        <div class="w-full sm:w-auto">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="statusFilter" class="border rounded-md px-3 py-2 text-sm w-full">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        
        <div class="w-full sm:w-auto">
            <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
            <select id="ratingFilter" class="border rounded-md px-3 py-2 text-sm w-full">
                <option value="">All Ratings</option>
                <option value="5">5 Stars</option>
                <option value="4">4 Stars</option>
                <option value="3">3 Stars</option>
                <option value="2">2 Stars</option>
                <option value="1">1 Star</option>
            </select>
        </div>
        
        <div class="w-full sm:w-auto">
            <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
            <select id="sortBy" class="border rounded-md px-3 py-2 text-sm w-full">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="rating_high">Rating (High to Low)</option>
                <option value="rating_low">Rating (Low to High)</option>
            </select>
        </div>
        
        <div class="w-full sm:w-auto">
            <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm flex items-center justify-center w-full" id="applyFilters">
                <i class="fas fa-filter mr-2"></i>
                Apply Filters
            </button>
        </div>
    </div>
</section>

<section class="p-6 flex-1">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">

        <div class="overflow-x-auto hidden lg:block">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reviewer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Review</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($reviews as $key => $review)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $reviews->firstItem() + $key }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($review->reviewer_image)
                                    <img class="h-10 w-10 rounded-full object-cover mr-3" src="{{ asset($review->reviewer_image) }}" alt="{{ $review->reviewer_name }}">
                                @else
                                    @php
                                        $fullName = trim(
                                            ($review->order?->addresses?->first_name ?? 'Hello') . ' ' .
                                            ($review->order?->addresses?->last_name ?? 'World')
                                        );
                                        $initials = collect(explode(' ', $fullName))
                                            ->map(fn($word) => strtoupper($word[0] ?? ''))
                                            ->take(2)
                                            ->implode('');
                                    @endphp
                                    <div class="h-10 w-10 rounded-full mr-3 flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-semibold text-sm">
                                        {{ $initials }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">
                                        {{ $review->order?->addresses?->first_name ?? 'Hello World' }}
                                    </div>
                                    <div class="text-gray-500 text-sm">Verified Purchase</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="flex items-center">
                                <img class="h-10 w-10 rounded-md object-cover mr-3" src="{{ asset('storage/' . $review->product->image) }}" alt="Product">
                                <div>
                                    <div class="font-medium">{{ $review->product->name }}</div>
                                    <div class="text-gray-500 text-xs">SKU: {{ $review->product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="inline-flex items-center px-2 py-1 rounded-md bg-yellow-100 text-yellow-800 text-sm font-medium">
                                <i class="fas fa-star mr-1"></i> {{ $review->rating }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="review-content space-y-3">
                                <p>{{ $review->comment }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-md text-xs font-medium
                                @if($review->status == 'approved') bg-green-100 text-green-800
                                @elseif($review->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($review->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-3">
                                @if($review->status != 'approved')
                                <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST">@csrf
                                    <button class="text-green-500 hover:text-green-700" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                @if($review->status != 'rejected')
                                <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST">@csrf
                                    <button class="text-red-500 hover:text-red-700" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                                @php
                                $reviewImages = $review->images()->get()->map(function($img) {
                                    $path = str_replace('\\','/',$img->path);
                                    $relativePath = preg_replace('#^' . preg_quote(str_replace('\\','/',storage_path('app/public')),'#') . '/#','',$path);
                                    return [
                                        'id' => $img->id,
                                        'path' => asset('storage/' . $relativePath)
                                    ];
                                });
                                $productImage = $review->product
                                    ? asset('storage/' . str_replace('\\','/', $review->product->image ?? ''))
                                    : '';
                                @endphp
                                <button 
                                    class="text-indigo-500 hover:text-indigo-700 view-review-btn"
                                    data-reviewer="{{ $review->order->addresses->first_name ?? 'Hello World' }}"
                                    data-product="{{ $review->product->name ?? '' }}"
                                    data-comment="{{ $review->comment }}"
                                    data-images='@json($reviewImages)'
                                    data-rating="{{ $review->rating }}"
                                    data-adminreply="{{ $review->admin_reply ?? '' }}"
                                    data-productimage="{{ $productImage }}"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                                <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-gray-500 hover:text-gray-700" title="Delete">
                                        <i class="fas fa-trash-alt"></i>                                        
                                    </button>
                                </form>
                                <button class="text-blue-500 hover:text-blue-700" title="Reply"
                                    onclick="openReplyModal(
                                        {{ $review->id }},
                                        '{{ addslashes($review->order->addresses->first_name ?? 'Customer') }}',
                                        '{{ addslashes($review->product->name ?? 'Product') }}',
                                        '{{ addslashes($review->comment ?? '') }}'
                                    )">
                                    <i class="fas fa-reply"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="block lg:hidden">
            <div class="p-4 space-y-4">
                @foreach($reviews as $key => $review)
                <div class="border rounded-lg overflow-hidden border-gray-200">
                    
                    <div class="flex items-center p-4 bg-gray-50 border-b">
                        @if($review->reviewer_image)
                            <img class="h-10 w-10 rounded-full object-cover mr-3" src="{{ asset($review->reviewer_image) }}" alt="{{ $review->reviewer_name }}">
                        @else
                            @php
                                $fullName = trim(
                                    ($review->order?->addresses?->first_name ?? 'Hello') . ' ' .
                                    ($review->order?->addresses?->last_name ?? 'World')
                                );
                                $initials = collect(explode(' ', $fullName))
                                    ->map(fn($word) => strtoupper($word[0] ?? ''))
                                    ->take(2)
                                    ->implode('');
                            @endphp
                            <div class="h-10 w-10 rounded-full mr-3 flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-semibold text-sm">
                                {{ $initials }}
                            </div>
                        @endif
                        <div>
                            <div class="font-medium text-gray-900">
                                {{ $review->order?->addresses?->first_name ?? 'Hello World' }}
                            </div>
                            <div class="text-gray-500 text-sm">Verified Purchase</div>
                        </div>
                    </div>

                    <div class="p-4 space-y-4">
                        <div class="flex items-center">
                            <img class="h-10 w-10 rounded-md object-cover mr-3" src="{{ asset('storage/' . $review->product->image) }}" alt="Product">
                            <div>
                                <div class="font-medium text-sm">{{ $review->product->name }}</div>
                                <div class="text-gray-500 text-xs">SKU: {{ $review->product->sku }}</div>
                            </div>
                        </div>

                        <div>
                            <div class="inline-flex items-center px-2 py-1 rounded-md bg-yellow-100 text-yellow-800 text-sm font-medium">
                                <i class="fas fa-star mr-1"></i> {{ $review->rating }}
                            </div>
                            <p class="text-sm text-gray-700 mt-2">{{ $review->comment }}</p>
                        </div>

                        <div class="pt-3 border-t space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Status</span>
                                <span class="px-2 py-1 rounded-md text-xs font-medium
                                    @if($review->status == 'approved') bg-green-100 text-green-800
                                    @elseif($review->status == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($review->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Date</span>
                                <span class="text-sm text-gray-700">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-5 gap-1 p-2 bg-gray-50 border-t">
                        @if($review->status != 'approved')
                        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="flex justify-center">@csrf
                            <button class="flex flex-col items-center p-2 text-green-500 hover:text-green-700" title="Approve">
                                <i class="fas fa-check text-lg"></i>
                                <span class="text-xs font-medium mt-1">Approve</span>
                            </button>
                        </form>
                        @endif
                        @if($review->status != 'rejected')
                        <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="flex justify-center">@csrf
                            <button class="flex flex-col items-center p-2 text-red-500 hover:text-red-700" title="Reject">
                                <i class="fas fa-times text-lg"></i>
                                <span class="text-xs font-medium mt-1">Reject</span>
                            </button>
                        </form>
                        @endif
                        
                        @php
                        $reviewImages = $review->images()->get()->map(function($img) {
                            $path = str_replace('\\','/',$img->path);
                            $relativePath = preg_replace('#^' . preg_quote(str_replace('\\','/',storage_path('app/public')),'#') . '/#','',$path);
                            return [
                                'id' => $img->id,
                                'path' => asset('storage/' . $relativePath)
                            ];
                        });
                        $productImage = $review->product
                            ? asset('storage/' . str_replace('\\','/', $review->product->image ?? ''))
                            : '';
                        @endphp
                        
                        <button 
                            class="flex flex-col items-center p-2 text-indigo-500 hover:text-indigo-700 view-review-btn"
                            data-reviewer="{{ $review->order->addresses->first_name ?? 'Hello World' }}"
                            data-product="{{ $review->product->name ?? '' }}"
                            data-comment="{{ $review->comment }}"
                            data-images='@json($reviewImages)'
                            data-rating="{{ $review->rating }}"
                            data-adminreply="{{ $review->admin_reply ?? '' }}"
                            data-productimage="{{ $productImage }}"
                        >
                            <i class="fas fa-eye text-lg"></i>
                            <span class="text-xs font-medium mt-1">View</span>
                        </button>
                        
                        <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');" class="flex justify-center">
                            @csrf
                            @method('DELETE')
                            <button class="flex flex-col items-center p-2 text-gray-500 hover:text-gray-700" title="Delete">
                                <i class="fas fa-trash-alt text-lg"></i>
                                <span class="text-xs font-medium mt-1">Delete</span>
                            </button>
                        </form>
                        
                        <button class="flex flex-col items-center p-2 text-blue-500 hover:text-blue-700" title="Reply"
                            onclick="openReplyModal(
                                {{ $review->id }},
                                '{{ addslashes($review->order->addresses->first_name ?? 'Customer') }}',
                                '{{ addslashes($review->product->name ?? 'Product') }}',
                                '{{ addslashes($review->comment ?? '') }}'
                            )">
                            <i class="fas fa-reply text-lg"></i>
                            <span class="text-xs font-medium mt-1">Reply</span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        </div>
</section>
</div>

<!-- Review Modal -->
<!-- Review Modal -->
<div id="reviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg w-11/12 max-w-3xl p-6 relative shadow-lg">
        <!-- Close Button -->
        <button id="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
            &times;
        </button>

        <!-- Top: Product & Customer -->
        <div class="flex items-center gap-4 mb-4">
            <!-- Product Image -->
            <img id="modalProductImage" class="h-20 w-20 object-cover rounded-lg" src="" alt="Product">

            <div>
                <h2 id="modalProductName" class="text-xl font-semibold"></h2>
                <p class="text-gray-600">Reviewed by <span id="modalReviewerName" class="font-medium"></span></p>
            </div>
        </div>

        <!-- Rating -->
        <div id="modalRating" class="flex gap-1 mb-4"></div>

        <!-- Comment -->
        <p id="modalComment" class="mb-4 text-gray-700"></p>

        <!-- Review Images -->
        <div id="modalImages" class="flex flex-wrap gap-2 mb-4"></div>

        <!-- Admin Reply -->
        <div id="modalAdminReply" class="bg-gray-100 p-3 rounded hidden">
            <h4 class="font-semibold text-gray-700 mb-1">Admin Reply:</h4>
            <p id="modalAdminReplyText" class="text-gray-800"></p>
        </div>
    </div>
</div>





    <!-- Reply Modal -->
<div id="replyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg w-11/12 max-w-2xl p-6 relative shadow-lg">
        <!-- Close Button -->
        <button id="closeReplyModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
            &times;
        </button>

        <h2 class="text-xl font-semibold mb-4">Reply to Review</h2>

        <p><strong>Customer:</strong> <span id="replyModalReviewer"></span></p>
        <p><strong>Product:</strong> <span id="replyModalProduct"></span></p>
        <p class="mb-4"><strong>Review:</strong> <span id="replyModalReview"></span></p>

        <!-- Reply Form -->
        <form id="replyForm" method="POST" action="{{ route('admin.reviews.reply') }}">
            @csrf
            <input type="hidden" name="review_id" id="replyReviewId">
            <textarea name="response" class="w-full border rounded p-2 mb-4" rows="4" placeholder="Write your reply here..."></textarea>
            <div class="flex justify-end gap-2">
                <button type="button" id="cancelReply" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Send Reply</button>
            </div>
        </form>
    </div>
</div>


        </div>

        <div id="toast" class="toast"></div>

@endsection


@push("script")

<script>
document.querySelectorAll('.view-review-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const reviewer_name = btn.dataset.reviewer;
        const product_name = btn.dataset.product;
        const comment = btn.dataset.comment;
        const reviewImages = JSON.parse(btn.dataset.images || '[]'); // safe
        const rating = parseInt(btn.dataset.rating || 0);
        const admin_reply = btn.dataset.adminreply;
        const productImage = btn.dataset.productimage;

        openReviewModal(
            reviewer_name,
            product_name,
            comment,
            reviewImages,
            rating,
            admin_reply,
            productImage
        );
    });
});

function openReviewModal(
    reviewer_name,
    product_name,
    comment,
    reviewImages = [],
    rating = 0,
    admin_reply = '',
    productImage = ''
) {
    const modal = document.getElementById('reviewModal');

    // Product info
    document.getElementById('modalProductImage').src = productImage || '';
    document.getElementById('modalProductName').innerText = product_name || '';
    document.getElementById('modalReviewerName').innerText = reviewer_name || '';

    // Rating stars
    const ratingContainer = document.getElementById('modalRating');
    ratingContainer.innerHTML = '';
    for (let i = 1; i <= 5; i++) {
        const star = document.createElement('i');
        star.classList.add('fas', 'fa-star', i <= rating ? 'text-yellow-400' : 'text-gray-300');
        ratingContainer.appendChild(star);
    }

    // Comment
    document.getElementById('modalComment').innerText = comment || '';

    // Review images
    const imagesContainer = document.getElementById('modalImages');
    imagesContainer.innerHTML = '';
    reviewImages.forEach(img => {
        const imageEl = document.createElement('img');
        imageEl.src = img.path;
        imageEl.className = 'h-24 w-24 object-cover rounded-lg shadow-sm';
        imagesContainer.appendChild(imageEl);
    });

    // Admin reply
    const adminReplyContainer = document.getElementById('modalAdminReply');
    const adminReplyText = document.getElementById('modalAdminReplyText');
    if (admin_reply && admin_reply.trim() !== '') {
        adminReplyText.innerText = admin_reply;
        adminReplyContainer.classList.remove('hidden');
    } else {
        adminReplyContainer.classList.add('hidden');
    }

    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
document.getElementById('closeModal').addEventListener('click', () => {
    const modal = document.getElementById('reviewModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
});


//REPLY Modal
function openReplyModal(reviewId, reviewerName, productName, reviewText) {
    const modal = document.getElementById('replyModal');
    modal.classList.remove('hidden');

    // Fill values
    document.getElementById('replyReviewId').value = reviewId;
    document.getElementById('replyModalReviewer').innerText = reviewerName;
    document.getElementById('replyModalProduct').innerText = productName;
    document.getElementById('replyModalReview').innerText = reviewText;
}

// Close modal buttons
document.getElementById('closeReplyModal').addEventListener('click', () => {
    document.getElementById('replyModal').classList.add('hidden');
});
document.getElementById('cancelReply').addEventListener('click', () => {
    document.getElementById('replyModal').classList.add('hidden');
});

</script>


@endpush


