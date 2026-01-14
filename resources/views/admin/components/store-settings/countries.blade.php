  <!-- Promo Codes Tab -->
  <style>
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
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-800">Shipping Countries</h3>
        <p class="text-gray-600">Manage countries you ship to</p>
    </div>
    <button
        id="addCountryBtn"
        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors action-btn
               w-full sm:w-auto" onclick="countryOpenAddModal()">
        <i class="fas fa-plus mr-2"></i>Add Country
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-blue-50 p-4 rounded-lg flex items-center">
        <div class="bg-blue-100 p-3 rounded-full mr-4">
            <i class="fas fa-globe-americas text-blue-600 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-600">Total Countries</p>
            <p class="text-xl font-semibold text-gray-800">{{ $totalCountries }}</p>
        </div>
    </div>
    <div class="bg-green-50 p-4 rounded-lg flex items-center">
        <div class="bg-green-100 p-3 rounded-full mr-4">
            <i class="fas fa-check-circle text-green-600 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-600">Active Countries</p>
            <p class="text-xl font-semibold text-gray-800">{{ $activeCountries }}</p>
        </div>
    </div>
    <div class="bg-yellow-50 p-4 rounded-lg flex items-center">
        <div class="bg-yellow-100 p-3 rounded-full mr-4">
            <i class="fas fa-pause-circle text-yellow-600 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-600">Inactive Countries</p>
            <p class="text-xl font-semibold text-gray-800">{{ $inactiveCountries }}</p>
        </div>
    </div>
</div>
<div>
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 hidden md:table-header-group">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipping Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Free Shipping Threshold</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="md:divide-y md:divide-gray-200 block md:table-row-group">
                @foreach($countries as $country)
                    <tr class="block md:table-row bg-white hover:bg-gray-50 mb-4 md:mb-0 rounded-lg md:rounded-none shadow-md md:shadow-none border border-gray-200 md:border-b">
                        
                        <!-- Country -->
                        <td class="block md:table-cell px-4 py-3 border-b md:border-none" data-label="Country">
                            <div class="flex items-center">
                                <img src="https://flagcdn.com/w40/{{ strtolower($country->code) }}.png"
                                     srcset="https://flagcdn.com/w80/{{ strtolower($country->code) }}.png 2x"
                                     width="40" alt="{{ $country->name }}" class="mr-3 rounded-sm">
                                <div class="text-sm font-medium text-gray-900">{{ $country->name }}</div>
                            </div>
                        </td>

                        <!-- Code -->
                        <td class="block md:table-cell px-4 py-3 border-b md:border-none" data-label="Code">
                            <div class="text-sm text-gray-900">{{ strtoupper($country->code) }}</div>
                        </td>

                        <!-- Shipping Rate -->
                        <td class="block md:table-cell px-4 py-3 border-b md:border-none" data-label="Shipping Rate">
                            <div class="text-sm text-gray-900">Rs. {{ number_format($country->shipping_rate, 2) }}</div>
                        </td>

                        <!-- Free Shipping Threshold -->
                        <td class="block md:table-cell px-4 py-3 border-b md:border-none" data-label="Free Shipping">
                            <div class="text-sm text-gray-900">Rs. {{ number_format($country->free_shipping_threshold, 2) }}</div>
                        </td>

                        <!-- Status -->
                        <td class="block md:table-cell px-4 py-3 border-b md:border-none" data-label="Status">
                            @if($country->status === 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Inactive</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="block md:table-cell px-4 py-3 border-b md:border-none text-sm text-gray-500" data-label="Actions">
                            <div class="flex flex-wrap gap-2 md:gap-3">
                                <button class="text-blue-600 hover:text-blue-900 action-btn edit-country"
                                        data-country='@json($country)'>
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('admin.shipping-countries.delete', $country->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 action-btn">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>






<!-- Add/Edit Country Modal -->
<div id="countryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
        <h3 class="text-lg font-semibold mb-4" id="countryModalTitle">Add New Country</h3>
        <form id="countryForm" method="POST" action="">
            @csrf
            <input type="hidden" name="country_id" id="country_id">
            <input type="hidden" name="_method" id="country_method" value="POST"> <!-- default POST -->

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Country Name</label>
                <input type="text" name="name" id="country_name" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Country Code</label>
                <input type="text" name="code" id="country_code" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Shipping Rate</label>
                <input type="number" step="0.01" name="shipping_rate" id="country_shipping_rate" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Free Shipping Threshold</label>
                <input type="number" step="0.01" name="free_shipping_threshold" id="country_free_threshold" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="country_status" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="button" id="closeCountryModal" class="mr-2 px-4 py-2 bg-gray-300 rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md" id="countryModalSubmit">Add Country</button>
            </div>
        </form>
    </div>
</div>
