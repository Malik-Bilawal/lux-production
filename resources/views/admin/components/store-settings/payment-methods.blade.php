<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Payment Methods</h3>
                <p class="text-gray-600">Manage accepted payment methods</p>
            </div>
            <button onclick="paymentOpenAddModal()" id="addPaymentBtn" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors action-btn">
                <i class="fas fa-plus mr-2"></i>Add Payment Method
            </button>
        </div>

        <style>
            /* This targets any <td> with a 'data-label' to show the label on mobile */
            td[data-label]::before {
                content: attr(data-label);
                display: block;
                font-weight: 600;
                color: #6b7280;
                /* Tailwind gray-500 */
                text-transform: uppercase;
                font-size: 0.75rem;
                /* Tailwind text-xs */
                margin-bottom: 0.25rem;
            }
        </style>

        @php
        $icons = [
        'visa' => 'fab fa-cc-visa text-blue-600',
        'mastercard' => 'fab fa-cc-mastercard text-red-600',
        'amex' => 'fab fa-cc-amex text-blue-500',
        'discover' => 'fab fa-cc-discover text-orange-600',
        'paypal' => 'fab fa-paypal text-blue-500',
        'stripe' => 'fab fa-cc-stripe text-indigo-600',
        'payoneer' => 'fas fa-globe text-orange-500',
        'apple' => 'fab fa-apple-pay text-black',
        'google' => 'fab fa-google-pay text-green-600',
        'samsung' => 'fas fa-mobile-alt text-blue-500',
        'bank' => 'fas fa-university text-green-600',
        'cod' => 'fas fa-truck text-gray-600',
        'easypaisa' => 'fas fa-mobile-alt text-green-500',
        'jazzcash' => 'fas fa-wallet text-red-500',
        'bitcoin' => 'fab fa-bitcoin text-orange-500',
        'ethereum' => 'fab fa-ethereum text-indigo-500',
        'tether' => 'fas fa-coins text-green-500',
        ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            @foreach($paymentMethods as $method)
            @if($method->status === 'active')
            <div class="bg-white border border-gray-200 rounded-lg p-4 flex flex-col items-center shadow-sm hover:shadow-md transition">
                <div class="p-3 rounded-full mb-4 bg-gray-100">
                    <i class="{{ $icons[$method->code] ?? 'fas fa-credit-card text-gray-600' }} text-2xl"></i>
                </div>
                <h4 class="font-semibold text-gray-800">{{ $method->name }}</h4>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $method->description ?? 'Available for payments' }}
                </p>
                <span class="mt-2 px-3 py-1 text-xs rounded-full 
                            {{ $method->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ ucfirst($method->status) }}
                </span>
            </div>
            @endif
            @endforeach
        </div>


        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 hidden md:table-header-group">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Fee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="md:divide-y md:divide-gray-200 block md:table-row-group">
                    @foreach($paymentMethods as $method)
                    <tr class="block md:table-row bg-white hover:bg-gray-50 mb-4 md:mb-0 rounded-lg md:rounded-none shadow-md md:shadow-none border border-gray-200 md:border-b">

                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap border-b md:border-none" data-label="Method">
                            <div class="flex items-center">
                                <i class="{{ $icons[$method->code] ?? 'fas fa-credit-card text-gray-500' }} text-2xl mr-3"></i>
                                <div class="text-sm font-medium text-gray-900">{{ $method->name }}</div>
                            </div>
                        </td>

                        <td class="block md:table-cell px-6 py-4 border-b md:border-none" data-label="Description">
                            <div class="text-sm text-gray-900">{{ $method->description ?? '—' }}</div>
                        </td>

                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap border-b md:border-none" data-label="Transaction Fee">
                            <div class="text-sm text-gray-900">{{ $method->transaction_fee ?? '—' }}</div>
                        </td>

                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap border-b md:border-none" data-label="Status">
                            @if($method->status === 'active')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                            @endif
                        </td>

                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-b md:border-none" data-label="Actions">
                            <button
                                class="text-blue-600 hover:text-blue-900 mr-3 action-btn edit-payment"
                                data-payment='@json($method)'>
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('admin.payment-methods.destroy', $method->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 action-btn">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>



<!-- MODEL -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
        <h3 class="text-lg font-semibold mb-4" id="paymentModalTitle">Add New Payment Method</h3>

        <form id="paymentForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="payment_id" id="payment_id">
            <input type="hidden" name="_method" value="PUT">


            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                <select name="name" id="payment_name"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    <option value="">-- Select Method --</option>
                    <option value="PayPal" data-code="paypal">PayPal</option>
                    <option value="Stripe" data-code="stripe">Stripe</option>
                    <option value="Easypaisa" data-code="easypaisa">Easypaisa</option>
                    <option value="JazzCash" data-code="jazzcash">JazzCash</option>
                    <option value="COD" data-code="cod">Cash on Delivery</option>
                    <option value="Bank Transfer" data-code="bank_transfer">Bank Transfer</option>
                </select>
            </div>

            <input type="hidden" name="code" id="payment_code">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Icon (optional)</label>
                <input type="file" name="icon" id="payment_icon"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="payment_status"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Settings (JSON)</label>
                <textarea name="settings" id="payment_settings" rows="3"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2"
                    placeholder='{"api_key":"xxxx", "secret":"yyyy"}'></textarea>
            </div>

            <div class="flex justify-end">
                <button type="button" id="closePaymentModal"
                    class="mr-2 px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                <button type="submit" id="paymentModalSubmit"
                    class="px-4 py-2 bg-green-500 text-white rounded-md">Save</button>
            </div>
        </form>
    </div>
</div>