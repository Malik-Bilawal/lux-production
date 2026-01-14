<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
  <div>
    <h3 class="text-lg font-semibold text-gray-800">Promo Codes</h3>
    <p class="text-gray-600">Manage discount and promotional codes</p>
  </div>
  <button onclick="promoOpenAddModal()" class="px-4 py-2 bg-blue-500 text-white rounded-lg
                                               w-full sm:w-auto"> Add Promo
  </button>
</div>
<div class="bg-white rounded-xl shadow-md p-4">
  <table class="w-full block md:table">
    <!-- Desktop Table Head -->
    <thead class="bg-gray-50 hidden md:table-header-group">
      <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid From</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid To</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage Limit</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
      </tr>
    </thead>

    <tbody class="block md:table-row-group">
      @foreach($promos as $promo)
      <tr class="block md:table-row border border-gray-200 md:border-b rounded-lg md:rounded-none shadow-md md:shadow-none mb-4 md:mb-0">
        <!-- Code -->
        <td class="block md:table-cell px-4 py-2 md:px-6 md:py-4 border-b md:border-none border-gray-200 responsive-td" data-label="Code">
          {{ $promo->code }}
        </td>

        <!-- Discount -->
        <td class="block md:table-cell px-4 py-2 md:px-6 md:py-4 border-b md:border-none border-gray-200 responsive-td" data-label="Discount">
          {{ $promo->discount_percent }}%
        </td>

        <!-- Valid From -->
        <td class="block md:table-cell px-4 py-2 md:px-6 md:py-4 border-b md:border-none border-gray-200 responsive-td" data-label="Valid From">
          {{ $promo->start_date->format('M d, Y') }}
        </td>

        <!-- Valid To -->
        <td class="block md:table-cell px-4 py-2 md:px-6 md:py-4 border-b md:border-none border-gray-200 responsive-td" data-label="Valid To">
          {{ $promo->end_date->format('M d, Y') }}
        </td>

        <!-- Usage Limit -->
        <td class="block md:table-cell px-4 py-2 md:px-6 md:py-4 border-b md:border-none border-gray-200 responsive-td" data-label="Usage Limit">
          {{ $promo->usage_limit == 0 ? 'Unlimited' : $promo->used_count . '/' . $promo->usage_limit }}
        </td>

        <!-- Status -->
        <td class="block md:table-cell px-4 py-2 md:px-6 md:py-4 border-b md:border-none border-gray-200 responsive-td" data-label="Status">
          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $promo->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ ucfirst($promo->status) }}
          </span>
        </td>

        <!-- Actions -->
        <td class="block md:table-cell px-4 py-2 md:px-6 md:py-4 responsive-td" data-label="Actions">
          <button class="edit-promo text-blue-600 hover:text-blue-900 mr-3" data-promo='@json($promo)'>
            <i class="fas fa-edit"></i> Edit
          </button>
          <form action="{{ route('admin.promo.delete', $promo->id) }}" method="POST" class="inline">
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

<style>
  /* Show label on the left for mobile */
  .responsive-td::before {
    content: attr(data-label) ":";
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
    width: 110px;
    /* adjust as needed */
    color: #6B7280;
    /* gray-500 */
    margin-right: 0.5rem;
  }

  @media (min-width: 768px) {
    .responsive-td::before {
      content: none;
    }
  }
</style>





<div id="promoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative">
    <button id="promoCloseBtn" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
      <i class="fas fa-times"></i>
    </button>

    <h2 id="promoModalTitle" class="text-xl font-semibold text-gray-800 mb-4">Add Promo Code</h2>

    <form id="promoForm" method="POST" class="space-y-4">
      @csrf
      <input type="hidden" id="promoId" name="id">

      <div>
        <label class="block text-sm font-medium text-gray-700">Code</label>
        <input type="text" name="code" id="promoCode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
      </div>


      <div id="percentField">
        <label class="block text-sm font-medium text-gray-700">Discount Percent</label>
        <input type="number" name="discount_percent" id="promoPercent" placeholder="e.g. 20" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
      </div>



      <div>
        <label class="block text-sm font-medium text-gray-700">Usage Limit</label>
        <input type="number" name="usage_limit" id="promoUsageLimit" placeholder="0 = Unlimited" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Valid Days</label>
        <input type="number" name="valid_days" id="promoValidDays" placeholder="e.g. 7, 15, 30" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select id="promoStatus" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>

      <div class="flex justify-end space-x-2">
        <button type="button" id="promoCancelBtn" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Save</button>
      </div>
    </form>
  </div>
</div>