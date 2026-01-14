@extends("admin.layouts.master-layouts.plain")

<title>Referrals Partners</title>

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
        .partner-row {
            transition: all 0.2s ease;
        }
        .partner-row:hover {
            background-color: #f9fafb;
            transform: translateY(-1px);
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
        .modal-overlay {
            transition: opacity 0.3s ease;
        }
        .modal-content {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        .modal-open .modal-overlay {
            opacity: 1;
            pointer-events: auto;
        }
        .modal-open .modal-content {
            transform: translateY(0);
            opacity: 1;
        }
        .dropdown-menu {
            transition: all 0.2s ease;
            transform-origin: top right;
        }
        .dropdown-open .dropdown-menu {
            transform: scale(1);
            opacity: 1;
            pointer-events: auto;
        }
        .tab-button {
            transition: all 0.2s ease;
        }
        .tab-button.active {
            background-color: #3B82F6;
            color: white;
        }
        .progress-bar {
            transition: width 0.5s ease-in-out;
        }
        .real-time-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
@endpush


@section("content")
<header class="bg-white shadow-sm">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center py-4 px-6 gap-4">
        
        <div class="w-full sm:w-auto">
            <h2 class="text-xl font-semibold text-gray-800">Referral Partners Management</h2>
            <p class="text-sm text-gray-500">Manage referral partners and their status</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            
            <form action="{{ route('admin.referral.index') }}" class="w-full sm:w-auto">
                @csrf
                <div class="relative">
                    <input type="text" placeholder="Search partners..." class="border rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </form>
            
            @include("admin.components.dark-mode.dark-toggle")
        </div>
    </div>
</header>

<section class="px-6 py-4 mt-1">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Total Partners</p>
                    <p class="text-2xl font-bold">{{ $totalPartners }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-handshake text-blue-500"></i>
                </div>
            </div>
            <p class="text-xs {{ $totalChange >= 0 ? 'text-green-500' : 'text-red-500' }} mt-2">
                <i class="fas {{ $totalChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                {{ abs($totalChange) }}% from last month
            </p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Pending Approval</p>
                    <p class="text-2xl font-bold">{{ $pendingPartners }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-500"></i>
                </div>
            </div>
            <p class="text-xs {{ $pendingChange >= 0 ? 'text-green-500' : 'text-red-500' }} mt-2">
                <i class="fas {{ $pendingChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                {{ abs($pendingChange) }}% from last month
            </p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Active Partners</p>
                    <p class="text-2xl font-bold">{{ $approvedPartners }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
            </div>
            <p class="text-xs {{ $approvedChange >= 0 ? 'text-green-500' : 'text-red-500' }} mt-2">
                <i class="fas {{ $approvedChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                {{ abs($approvedChange) }}% from last month
            </p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Declined/Removed</p>
                    <p class="text-2xl font-bold">{{ $rejectedPartners }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-times-circle text-red-500"></i>
                </div>
            </div>
            <p class="text-xs {{ $rejectedChange >= 0 ? 'text-green-500' : 'text-red-500' }} mt-2">
                <i class="fas {{ $rejectedChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                {{ abs($rejectedChange) }}% from last month
            </p>
        </div>
    </div>
</section>

<form method="GET" action="{{ route('admin.referral.index') }}">
    <section class="px-6 py-4 bg-white shadow-sm mt-1">
        <div class="flex flex-col sm:flex-row sm:flex-wrap items-end gap-4">

            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Joined</label>
                <div class="flex flex-col sm:flex-row items-center gap-2 border rounded-md px-3 py-2 text-sm w-full sm:w-64">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full outline-none">
                    <span class="text-gray-500 hidden sm:block">to</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full outline-none">
                </div>
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort</label>
                <select name="sort" class="border rounded-md px-3 py-2 text-sm w-full sm:w-40">
                    <option value="a-z" {{ request('sort')=='a-z'?'selected':'' }}>A to Z</option>
                    <option value="z-a" {{ request('sort')=='z-a'?'selected':'' }}>Z to A</option>
                    <option value="latest" {{ request('sort')=='latest'?'selected':'' }}>Latest</option>
                    <option value="oldest" {{ request('sort')=='oldest'?'selected':'' }}>Oldest</option>
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm flex items-center justify-center action-btn w-full sm:w-auto">
                    <i class="fas fa-filter mr-2"></i>
                    Apply Filters
                </button>
            </div>
        </div>
    </section>
</form>


<section class="p-6 flex-1">
    
    <style>
      @media (max-width: 767px) {
        /* This creates the label-left, data-right layout */
        td[data-label] {
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 0.75rem 1.5rem; /* py-3 px-6 */
        }
        
        /* The Label (Left Side) */
        td[data-label]::before {
          content: attr(data-label);
          font-weight: 600;
          color: #6b7280; /* gray-500 */
          text-transform: uppercase;
          font-size: 0.75rem; /* text-xs */
          text-align: left;
          margin-right: 1rem;
        }
        
        /* The Data (Right Side) */
        td[data-label] > * {
          text-align: right;
        }
      }
    </style>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 hidden md:table-header-group">
                    <tr>
                        <th class="px-6 py-3"><input type="checkbox" class="rounded text-blue-500"></th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Partner ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name & Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Join Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="block md:table-row-group divide-y divide-gray-200">
                    @foreach($referrals as $referral)
                    
                    <tr class="partner-row block md:table-row mb-4 md:mb-0 shadow-md md:shadow-none border border-gray-200 md:border-none rounded-lg md:rounded-none">
                        
                        <td class="block md:table-cell md:px-6 md:py-4 border-b md:border-b-0" data-label="Select">
                            <input type="checkbox" class="rounded text-blue-500">
                        </td>
                        
                        <td class="block md:table-cell md:px-6 md:py-4 border-b" data-label="Partner ID">
                            <span class="font-medium text-blue-600">{{ $referral->id }}</span>
                        </td>
                        
                        <td class="block md:table-cell md:px-6 md:py-4 border-b">
                            <div class="flex items-center space-x-3">
                                <img src="{{ asset('storage/' . $referral->profile_picture) }}"
                                     alt="Profile"
                                     class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $referral->name }}</div>
                                    <div class="text-gray-500 text-sm">{{ $referral->company ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="block md:table-cell md:px-6 md:py-4 border-b" data-label="Join Date">
                            <span>{{ $referral->created_at->format('M d, Y') }}</span>
                        </td>
                        
                        <td class="block md:table-cell md:px-6 md:py-4 border-b" data-label="Type">
                            <span class="font-medium">{{ $referral->type }}</span>
                        </td>
                        
                        <td class="block md:table-cell md:px-6 md:py-4" data-label="Actions">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2 gap-2">
                                <button 
                                    class="text-blue-500 hover:text-blue-700 open-referral-btn text-left sm:text-center" 
                                    data-referral='@json($referral)'>
                                    <i class="fas fa-eye"></i>
                                    <span class="sm:hidden ml-2">View Details</span>
                                </button>

                                <div class="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0">
                                    <form method="POST" action="{{ route('admin.referrals.updateStatus', $referral->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="w-full px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.referrals.updateStatus', $referral->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="w-full px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                                            Decline
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<div class="p-4 flex flex-col sm:flex-row items-center justify-between border-t border-gray-200 gap-4">
    <div class="text-sm text-gray-700">
        Showing 
        {{ $referrals->firstItem() }} 
        to 
        {{ $referrals->lastItem() }} 
        of 
        {{ $referrals->total() }} users
    </div>
    
    <div class="flex flex-wrap justify-center space-x-2">
        {{-- Previous Page --}}
        @if ($referrals->onFirstPage())
            <span class="px-3 py-1 rounded-md bg-gray-200 text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $referrals->previousPageUrl() }}" class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($referrals->getUrlRange(1, $referrals->lastPage()) as $page => $url)
            @if ($page == $referrals->currentPage())
                <span class="px-3 py-1 rounded-md bg-blue-500 text-white">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- Next Page --}}
        @if ($referrals->hasMorePages())
            <a href="{{ $referrals->nextPageUrl() }}" class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="px-3 py-1 rounded-md bg-gray-200 text-gray-400 cursor-not-allowed">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </div>
</div>
  <!-- Partner Modal -->
<!-- Partner Modal -->
<div id="partnerModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <!-- Overlay -->
    <div class="modal-overlay fixed inset-0 bg-black bg-opacity-50 opacity-0 pointer-events-none transition-opacity duration-300"></div>

    <!-- Modal Content -->
    <div class="modal-content bg-white rounded-xl shadow-2xl w-full max-w-3xl mx-4 transform translate-y-10 opacity-0 transition-all duration-300">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Partner Details - <span id="modalPartnerId"></span></h3>
            <button class="text-gray-400 hover:text-gray-600" onclick="closePartnerModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 max-h-[70vh] overflow-y-auto space-y-6">            <div class="flex items-center space-x-6">
                <img src="/path/to/default/avatar.png" class="w-24 h-24 rounded-full object-cover partner-img">
                <div class="space-y-1">
                    <p class="font-bold text-gray-900 partner-name">-</p>
                    <p class="text-gray-600 partner-company">-</p>
                    <p class="text-gray-500 text-sm partner-type">Type: -</p>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="font-medium text-gray-700">Email</p>
                    <p class="partner-email">-</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Phone</p>
                    <p class="partner-phone">-</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Location</p>
                    <p class="partner-location">-</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Social Links</p>
                    <p class="partner-social flex flex-wrap gap-2">-</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Bank Name</p>
                    <p class="partner-bank-name">-</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Account Number</p>
                    <p class="partner-account-number">-</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Payment Method</p>
                    <p class="partner-payment-method">-</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Age</p>
                    <p class="partner-age">-</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Gender</p>
                    <p class="partner-gender">-</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="font-medium text-gray-700">Bio</p>
                    <p class="partner-bio">-</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="font-medium text-gray-700">Niche</p>
                    <p class="partner-niche">-</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Referral Estimate</p>
                    <p class="partner-referral-estimate">-</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Followers Count</p>
                    <p class="partner-followers-count">-</p>
                </div>
            </div>

        </div>
    </div>
</div>


    </div>
    </div>
@endsection


@push("script")
<script>
document.addEventListener("DOMContentLoaded", () => {
    console.log("JS loaded for referral modal");

    function openPendingReferralModal(referral) {
        const modal = document.getElementById('partnerModal');
        const overlay = modal.querySelector('.modal-overlay');
        const content = modal.querySelector('.modal-content');

        document.getElementById('modalPartnerId').textContent = referral.id;
        modal.querySelector('.partner-name').textContent = referral.name || '-';
        modal.querySelector('.partner-company').textContent = referral.company || '-';
        modal.querySelector('.partner-type').textContent = `Type: ${referral.type || '-'}`;
        modal.querySelector('.partner-email').textContent = referral.email || '-';
        modal.querySelector('.partner-phone').textContent = referral.phone || '-';
        modal.querySelector('.partner-location').textContent = `${referral.city || '-'}, ${referral.country || '-'}`;
        modal.querySelector('.partner-age').textContent = referral.age || '-';
        modal.querySelector('.partner-gender').textContent = referral.gender || '-';
        modal.querySelector('.partner-bank-name').textContent = referral.bank_name || '-';
        modal.querySelector('.partner-account-number').textContent = referral.account_number || '-';
        modal.querySelector('.partner-payment-method').textContent = referral.payment_method || '-';
        modal.querySelector('.partner-bio').textContent = referral.bio || '-';
        modal.querySelector('.partner-niche').textContent = referral.niche || '-';
        modal.querySelector('.partner-referral-estimate').textContent = referral.referral_estimate || '-';
        modal.querySelector('.partner-followers-count').textContent = referral.followers_count || 0;

        const socialLinks = referral.social_links || {};
        let socialHTML = '';
        for (const [key, value] of Object.entries(socialLinks)) {
            socialHTML += `<a href="${value}" target="_blank" class="text-blue-500 hover:underline mr-2">${key}</a>`;
        }
        modal.querySelector('.partner-social').innerHTML = socialHTML || '-';

        modal.querySelector('.partner-img').src = referral.profile_picture 
            ? '/storage/' + referral.profile_picture 
            : '/path/to/default/avatar.png';

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-50');
            content.classList.remove('translate-y-10', 'opacity-0');
            content.classList.add('translate-y-0', 'opacity-100');
        }, 10);
    }

    function closePartnerModal() {
        const modal = document.getElementById('partnerModal');
        const overlay = modal.querySelector('.modal-overlay');
        const content = modal.querySelector('.modal-content');

        overlay.classList.add('opacity-0', 'pointer-events-none');
        overlay.classList.remove('opacity-50');
        content.classList.add('translate-y-10', 'opacity-0');
        content.classList.remove('translate-y-0', 'opacity-100');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    document.querySelectorAll('.open-referral-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const referral = JSON.parse(btn.getAttribute('data-referral'));
            openPendingReferralModal(referral);
        });
    });

    document.querySelectorAll('[onclick="closePartnerModal()"]').forEach(btn => {
        btn.addEventListener('click', closePartnerModal);
    });
});


    function toggleDropdown(button) {
        const dropdown = button.nextElementSibling;
        const isOpen = dropdown.classList.contains('hidden');

        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== dropdown) {
                menu.classList.add('hidden');
            }
        });

        if (isOpen) {
            dropdown.classList.remove('hidden');
            setTimeout(() => {
                dropdown.parentElement.classList.add('dropdown-open');
            }, 10);
        } else {
            dropdown.parentElement.classList.remove('dropdown-open');
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 200);
        }
    }

    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        });
    });

    document.addEventListener('click', (e) => {
        if (!e.target.matches('.dropdown-toggle')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.parentElement.classList.remove('dropdown-open');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 200);
            });
        }
    });

    document.getElementById('partnerModal').addEventListener('click', (e) => {
        if (e.target === document.querySelector('.modal-overlay')) {
            closePartnerModal();
        }
    });

    setInterval(() => {
        const liveIndicator = document.querySelector('.real-time-pulse');
        if (liveIndicator) {
            liveIndicator.style.animation = 'none';
            setTimeout(() => {
                liveIndicator.style.animation = 'pulse 2s infinite';
            }, 10);
        }
    }, 5000);

</script>
@endpush
