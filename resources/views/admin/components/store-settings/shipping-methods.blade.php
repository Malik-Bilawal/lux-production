<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Shipping Methods</h3>
                <p class="text-gray-600">Manage your shipping options and carriers</p>
            </div>
            <button onclick="shippingOpenAddModal()" id="addShippingBtn" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors action-btn w-full sm:w-auto">
                <i class="fas fa-plus mr-2"></i>Add Shipping Method
            </button>
        </div>

        <style>
          /* This targets any <td> with a 'data-label' to show the label on mobile */
          td[data-label]::before {
            content: attr(data-label);
            display: block;
            font-weight: 600;
            color: #6b7280; /* Tailwind gray-500 */
            text-transform: uppercase;
            font-size: 0.75rem; /* Tailwind text-xs */
            margin-bottom: 0.25rem;
          }
        </style>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-2 rounded-md mr-4">
                        <i class="fas fa-shipping-fast text-blue-600 text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-800">Standard Shipping</h4>
                </div>
                <p class="text-sm text-gray-600 mb-4">Delivery within 5-7 business days</p>
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-800">$4.99</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                 <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-2 rounded-md mr-4">
                        <i class="fas fa-truck text-green-600 text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-800">Express Shipping</h4>
                </div>
                <p class="text-sm text-gray-600 mb-4">Delivery within 2-3 business days</p>
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-800">$12.99</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-2 rounded-md mr-4">
                        <i class="fas fa-rocket text-purple-600 text-xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-800">Next Day Delivery</h4>
                </div>
                <p class="text-sm text-gray-600 mb-4">Delivery next business day</p>
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-800">$24.99</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 hidden md:table-header-group">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Free Threshold</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="md:divide-y md:divide-gray-200 block md:table-row-group">
                    @foreach($shippingMethods as $method)
                    <tr class="block md:table-row bg-white hover:bg-gray-50 mb-4 md:mb-0 rounded-lg md:rounded-none shadow-md md:shadow-none border border-gray-200 md:border-b">
                        
                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap border-b md:border-none" data-label="Method">
                            <div class="flex items-center">
                                <i class="fas fa-shipping-fast text-blue-600 text-xl mr-3"></i>
                                <div class="text-sm font-medium text-gray-900">{{ $method->name }}</div>
                            </div>
                        </td>

                        <td class="block md:table-cell px-6 py-4 text-sm text-gray-900 border-b md:border-none" data-label="Delivery Time">
                            {{ $method->delivery_time }}
                        </td>

                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b md:border-none" data-label="Cost">
                            Rs.{{ number_format($method->cost, 2) }}
                        </td>

                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b md:border-none" data-label="Free Threshold">
                            Rs. {{ number_format($method->free_threshold, 2) }}
                        </td>

                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap border-b md:border-none" data-label="Status">
                            @if($method->status === 'active')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                            @endif
                        </td>

                        <td class="block md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-b md:border-none" data-label="Actions">
                            <button class="text-blue-600 hover:text-blue-900 mr-3"
                                    onclick='shippingOpenEditModal(@json($method))'>
                                <i class="fas fa-edit"></i> Edit
                            </button>

                            <form method="POST" action="{{ route('admin.shipping-methods.destroy', $method->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
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

<!-- MODEL --><div id="shippingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md max-h-[90vh] p-6 relative overflow-y-auto">
        <h3 class="text-lg font-semibold mb-4" id="shippingModalTitle">Add Shipping Method</h3>

        <form id="shippingForm" method="POST" action="">
            @csrf
            <input type="hidden" id="shipping_id" name="id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Method Name</label>
                <input type="text" id="shipping_name" name="name"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Delivery Time</label>
                <input type="text" id="shipping_delivery_time" name="delivery_time"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Cost</label>
                <input type="number" id="shipping_cost" name="cost"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Delivery Time (days for calculation)</label>
                <input type="number" id="shipping_delivery_days" name="delivery_time_days"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2" min="1" required>
                <p class="text-xs text-gray-500 mt-1">This value will be used to calculate estimated delivery datetime.</p>
            </div>


            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Free Shipping Threshold</label>
                <input type="number" id="shipping_free_threshold" name="free_threshold"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>

            <div class="mb-4" x-data="shippingCountries()" x-init="fetchCountries()">
    <label class="block text-sm font-medium text-gray-700 mb-2">Select Countries</label>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2  overflow-y-auto border border-gray-300 rounded-md p-2">
        <template x-for="country in countries" :key="country.id">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" :value="country.id" name="countries[]" x-model="selectedCountries"
                       class="w-4 h-4 text-green-500 border-gray-300 rounded">
                <span x-text="country.name" class="text-gray-700 text-sm"></span>
            </label>
        </template>
    </div>

    <p class="text-xs text-gray-500 mt-1">Select one or more countries where this shipping method applies.</p>
</div>


            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select id="shipping_status" name="status"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="flex justify-end">
                <button type="button" id="closeShippingModal"
                    class="mr-2 px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-green-500 text-white rounded-md">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
function shippingCountries() {
    return {
        countries: [],
        selectedCountries: [],
        async fetchCountries() {
            try {
                const res = await fetch('/admin/get/shipping-countries');
                const data = await res.json();
                if (data.success) {
                    this.countries = data.data;
                }
            } catch (err) {
                console.error('Failed to fetch countries', err);
            }
        },
        setSelected(ids = []) {
            this.selectedCountries = ids;
        }
    }
}
</script>
