@extends("admin.layouts.master-layouts.plain")

<title>Store Setting Luxorix</title>




@push("style")
<style>
        .tab-content {
            display: none !important;
        }
        .tab-content.active {
            display: block !important;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .tab-button {
            transition: all 0.3s ease;
            position: relative;
        }
        .tab-button::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background-color: #3B82F6;
            transition: width 0.3s ease;
        }
        .tab-button.active {
            color: #3B82F6;
            font-weight: 600;
        }
        .tab-button.active::after {
            width: 100%;
        }
        .upload-area {
            border: 2px dashed #cbd5e0;
            border-radius: 0.75rem;
            padding: 2rem;
            text-align: center;
            background-color: #f9fafb;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .upload-area:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        .upload-area.active {
            border-color: #10b981;
            background-color: #ecfdf5;
        }
        .table-row:hover {
            background-color: #f3f4f6;
        }
        .action-btn {
            transition: transform 0.2s ease;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }
        .image-preview {
            transition: transform 0.3s ease;
        }
        .image-preview:hover {
            transform: scale(1.03);
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }
        .toggle-checkbox:checked {
            right: 0;
            border-color: #10B981;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #10B981;
        }

  /* This hides all tab content by default */
  .tab-content {
    display: none;
  }

  /* This SHOWS only the active one. 
     The 'flex-1' class will now ONLY apply to this one. */
  .tab-content.active {
    display: block; 
  }
</style>
@endpush


@section("content")

   <!-- Header -->
   <header class="bg-white shadow-sm">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 py-4 px-6">
        
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Settings Management</h2>
            <p class="text-sm text-gray-500">Manage promo codes, shipping, payment methods, and more</p>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center action-btn w-full sm:w-auto">
                <i class="fas fa-sync-alt mr-2"></i>
                Save All Changes
            </button>
            
            @include("admin.components.dark-mode.dark-toggle")
        </div>

    </div>
</header>
        <!-- Tabs Navigation -->
        <section class="px-6 py-4 bg-white shadow-sm mt-1">
    <div class="flex overflow-x-auto whitespace-nowrap border-b border-gray-200">
        <button class="tab-button py-3 px-6 text-gray-600 active" data-tab="promo-codes">Promo Codes</button>
        <button class="tab-button py-3 px-6 text-gray-600" data-tab="shipping-countries">Shipping Countries</button>
        <button class="tab-button py-3 px-6 text-gray-600" data-tab="payment-methods">Payment Methods</button>
        <button class="tab-button py-3 px-6 text-gray-600" data-tab="shipping-methods">Shipping Methods</button>
        
        </div>

        <style>
section.tab-content {
    display: none !important;
  }

  /*
   * This SHOWS *only* the section that has the 'active' class.
   */
  section.tab-content.active {
    display: block !important;
  }
</style>
</section>

     <!-- // PROMO CODES -->
     <section id="promo-codes" class="tab-content p-6 flex-1 active">
@include("admin.components.store-settings.promo-codes")
     </section>


        <!-- Shipping Countries Tab -->
        <section id="shipping-countries" class="tab-content p-6 flex-1">
  @include("admin.components.store-settings.countries")
        </section>

        <!-- Payment Methods Tab -->
        <section id="payment-methods" class="tab-content p-6 flex-1">
@include("admin.components.store-settings.payment-methods")
        </section>

        <!-- Shipping Methods Tab -->
        <section id="shipping-methods" class="tab-content p-6 flex-1">
@include("admin.components.store-settings.shipping-methods")
        </section>

        </div>

        <style>
  /* This hides all tab content by default */
  .tab-content {
    display: none;
  }

  /* This SHOWS only the active one. */
  .tab-content.active {
    display: block; 
  }
</style>


@endsection


@push("script")
<script>



  document.addEventListener('DOMContentLoaded', () => {

    console.log('Tab script is now loaded AND running!');

    document.querySelectorAll('.tab-button').forEach(button => {
      button.addEventListener('click', () => {
        
        console.log('Button clicked:', button.getAttribute('data-tab'));

        // 1. Deactivate all buttons and content
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        
        // 2. Activate the clicked button
        button.classList.add('active');
        
        // 3. Activate the corresponding content
        const tabId = button.getAttribute('data-tab');
        const activeTabContent = document.getElementById(tabId);
        
        if (activeTabContent) {
          activeTabContent.classList.add('active');
        } else {
          console.error('No tab content found with ID:', tabId);
        }
      });
    });




// Shipping Modal Variables
const shippingModal = document.getElementById('shippingModal');
const shippingModalTitle = document.getElementById('shippingModalTitle');
const shippingForm = document.getElementById('shippingForm');

const shippingIdField = document.getElementById('shipping_id');
const shippingNameField = document.getElementById('shipping_name');
const shippingDeliveryField = document.getElementById('shipping_delivery_time');
const shippingCostField = document.getElementById('shipping_cost');
const shippingThresholdField = document.getElementById('shipping_free_threshold');
const shippingDaysField = document.getElementById('shipping_delivery_days');
const shippingCountriesField = document.getElementById('shipping_countries');
const shippingStatusField = document.getElementById('shipping_status');

const shippingCloseBtn = document.getElementById('closeShippingModal');

window.shippingOpenAddModal = function () {
    shippingModalTitle.textContent = "Add Shipping Method";
    shippingForm.action = "/admin/shipping-methods/store";
    shippingForm.reset();

    shippingForm.querySelectorAll("input[name='_method']").forEach(el => el.remove());

    shippingIdField.value = "";
    shippingModal.classList.remove("hidden");

    shippingDaysField.value = ""; 
    shippingCountriesField.value = "";
};
window.shippingOpenEditModal = function (shippingData) {
    shippingModalTitle.textContent = "Edit Shipping Method";
    shippingForm.action = `/admin/shipping-methods/update/${shippingData.id}`;

    shippingForm.querySelectorAll("input[name='_method']").forEach(el => el.remove());

    let methodField = document.createElement("input");
    methodField.type = "hidden";
    methodField.name = "_method";
    methodField.value = "PUT";
    shippingForm.appendChild(methodField);

    shippingIdField.value = shippingData.id;
    shippingNameField.value = shippingData.name;
    shippingDeliveryField.value = shippingData.delivery_time;
    shippingDaysField.value = shippingData.delivery_time_days;
    shippingCostField.value = shippingData.cost;
    shippingThresholdField.value = shippingData.free_threshold;
    shippingStatusField.value = shippingData.status;

    window.dispatchEvent(new CustomEvent('fill-shipping-countries', {
        detail: shippingData.countries
    }));

    shippingModal.classList.remove("hidden");
};


function shippingCloseModal() {
    shippingModal.classList.add("hidden");
}
shippingCloseBtn.addEventListener('click', shippingCloseModal);

window.addEventListener('click', (e) => {
    if (e.target === shippingModal) shippingCloseModal();
});



const paymentModal = document.getElementById('paymentModal');
const paymentModalTitle = document.getElementById('paymentModalTitle');
const paymentForm = document.getElementById('paymentForm');
const paymentIdField = document.getElementById('payment_id');
const paymentNameField = document.getElementById('payment_name');
const paymentCodeField = document.getElementById('payment_code');
const paymentStatusField = document.getElementById('payment_status');
const paymentSettingsField = document.getElementById('payment_settings');
const paymentCloseBtn = document.getElementById('closePaymentModal');
const paymentNameDropdown = document.getElementById('payment_name');

paymentNameDropdown.addEventListener('change', function () {
    let selectedOption = this.options[this.selectedIndex];
    paymentCodeField.value = selectedOption.dataset.code || "";
});


window.paymentOpenAddModal = function () {
    paymentModalTitle.textContent = "Add Payment Method";
    paymentForm.action = "/admin/payment-methods/store";
    paymentForm.reset();
    paymentIdField.value = "";
    paymentModal.classList.remove("hidden");
};

window.paymentOpenEditModal = function (paymentData) {
    paymentModalTitle.textContent = "Edit Payment Method";
    paymentForm.action = `/admin/payment-methods/update/${paymentData.id}`;

    paymentIdField.value = paymentData.id;
    paymentNameField.value = paymentData.name;
    paymentCodeField.value = paymentData.code;
    paymentStatusField.value = paymentData.status;
    paymentSettingsField.value = paymentData.settings ? JSON.stringify(paymentData.settings, null, 2) : "";

    paymentModal.classList.remove("hidden");
};

// 🔹 Close Modal
function paymentCloseModal() {
    paymentModal.classList.add("hidden");
}
paymentCloseBtn.addEventListener('click', paymentCloseModal);

// Close modal when clicking outside
window.addEventListener('click', (e) => {
    if (e.target === paymentModal) paymentCloseModal();
});

document.getElementById('addPaymentBtn').addEventListener('click', paymentOpenAddModal);

//  Edit Button Click
document.querySelectorAll('.edit-payment').forEach(btn => {
    btn.addEventListener('click', function() {
        const paymentData = JSON.parse(this.dataset.payment);
        paymentOpenEditModal(paymentData);
    });
});



// countruy modal

const countryModal = document.getElementById('countryModal');
const countryModalTitle = document.getElementById('countryModalTitle');
const countryForm = document.getElementById('countryForm');
const countryIdField = document.getElementById('country_id');
const countryNameField = document.getElementById('country_name');
const countryCodeField = document.getElementById('country_code');
const countryShippingField = document.getElementById('country_shipping_rate');
const countryThresholdField = document.getElementById('country_free_threshold');
const countryStatusField = document.getElementById('country_status');
const countryCloseBtn = document.getElementById('closeCountryModal');

// Open Add Country Modal
window.countryOpenAddModal = function () {
    countryModalTitle.textContent = "Add New Country";
    countryForm.action = "/admin/shipping-countries/store"; 
    countryForm.reset();

    countryForm.querySelectorAll("input[name='_method']").forEach(el => el.remove());

    countryIdField.value = "";
    countryModal.classList.remove("hidden");
};

// Open Edit Country Modal
window.countryOpenEditModal = function (countryData) {
    countryModalTitle.textContent = "Edit Country";
    countryForm.action = `/admin/shipping-countries/update/${countryData.id}`; 

    countryForm.querySelectorAll("input[name='_method']").forEach(el => el.remove());

    let methodField = document.createElement("input");
    methodField.type = "hidden";
    methodField.name = "_method";
    methodField.value = "PUT";
    countryForm.appendChild(methodField);

    countryIdField.value = countryData.id;
    countryNameField.value = countryData.name;
    countryCodeField.value = countryData.code;
    countryShippingField.value = countryData.shipping_rate;
    countryThresholdField.value = countryData.free_shipping_threshold;
    countryStatusField.value = countryData.status;

    countryModal.classList.remove("hidden");
};

// Bind edit buttons
document.querySelectorAll('.edit-country').forEach(btn => {
    btn.addEventListener('click', function () {
        const countryData = JSON.parse(this.dataset.country);
        countryOpenEditModal(countryData);
    });
});

// Close modal
function countryCloseModal() {
    countryModal.classList.add("hidden");
}
countryCloseBtn.addEventListener('click', countryCloseModal);
window.addEventListener('click', (e) => {
    if (e.target === countryModal) countryCloseModal();
});




//PROMO MODAL
    const promoModal = document.getElementById('promoModal');
    const promoModalTitle = document.getElementById('promoModalTitle');
    const promoForm = document.getElementById('promoForm');
    const promoIdField = document.getElementById('promoId');
    const promoCodeField = document.getElementById('promoCode');
    const promoPercentField = document.getElementById('promoPercent');
    const promoUsageLimitField = document.getElementById('promoUsageLimit');
    const promoValidDaysField = document.getElementById('promoValidDays');
    const promoStatusField = document.getElementById('promoStatus');
    const promoCloseBtn = document.getElementById('promoCloseBtn');
    const promoCancelBtn = document.getElementById('promoCancelBtn');

    //  Open Add Modal
    window.promoOpenAddModal = function () {
        promoModalTitle.textContent = "Add Promo Code";
        promoForm.action = "/admin/promos/store";
        promoForm.reset();
        promoIdField.value = "";
        promoModal.classList.remove("hidden");
    };

    document.querySelectorAll('.edit-promo').forEach(btn => {
    btn.addEventListener('click', function() {
        const promoData = JSON.parse(this.dataset.promo);
        promoOpenEditModal(promoData);
    });
});


    //  Open Edit Modal
    window.promoOpenEditModal = function (promoData) {
        promoModalTitle.textContent = "Edit Promo Code";
        promoForm.action = `/admin/promos/update/${promoData.id}`;

        promoIdField.value = promoData.id;
        promoCodeField.value = promoData.code;
        promoPercentField.value = promoData.discount_percent;
        promoUsageLimitField.value = promoData.usage_limit;
        
        const start = new Date(promoData.start_date);
        const end = new Date(promoData.end_date);
        const diffDays = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        promoValidDaysField.value = diffDays;

        promoStatusField.value = promoData.status;

        promoModal.classList.remove("hidden");
    };

    //  Close Modal
    function promoCloseModal() {
        promoModal.classList.add("hidden");
    }

    promoCloseBtn.addEventListener('click', promoCloseModal);
    promoCancelBtn.addEventListener('click', promoCloseModal);

    window.addEventListener('click', (e) => {
        if (e.target === promoModal) promoCloseModal();
    });

    
});

</script>
@endpush