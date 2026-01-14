import '@css/pages/checkout.css';

document.addEventListener("DOMContentLoaded", () => {
    let appliedPromo = null;

    // Element bindings
    const subtotalEl = document.getElementById("subtotal");
    const shippingEl = document.getElementById("shipping");
    const taxEl = document.getElementById("tax");
    const totalEl = document.getElementById("total");
    const saleDiscountEl = document.getElementById("sale-discount");
    const saleDiscountRow = document.getElementById("sale-discount-row");
    const promoDiscountEl = document.getElementById("promo-discount");
    const promoRow = document.getElementById("promo-discount-row");
    const promoMessage = document.getElementById("promo-message");
    const applyBtn = document.getElementById("apply-promo");
    const promoInput = document.getElementById("promo-code");

    // Mobile elements
    const mSubtotalEl = document.getElementById("mobile-subtotal");
    const mShippingEl = document.getElementById("mobile-shipping");
    const mTaxEl = document.getElementById("mobile-tax");
    const mTotalEl = document.getElementById("mobile-total");
    const mBottomTotalEl = document.getElementById("mobile-bottom-total");

    const updateSummaryDesktop = (data) => {
        if (!subtotalEl) return;

        subtotalEl.textContent = `Rs.${data.subtotal.toFixed(2)}`;
        shippingEl.textContent = `Rs.${data.shipping.toFixed(2)}`;
        taxEl.textContent = `Rs.${data.tax.toFixed(2)}`;
        totalEl.textContent = `Rs.${data.total.toFixed(2)}`;

        if (saleDiscountEl && saleDiscountRow) {
            saleDiscountRow.style.display = data.saleDiscount > 0 ? "block" : "none";
            saleDiscountEl.textContent = `- Rs.${data.saleDiscount.toFixed(2)}`;
        }

        if (promoDiscountEl && promoRow) {
            promoRow.style.display = data.promoDiscount > 0 ? "block" : "none";
            promoDiscountEl.textContent = `- Rs.${data.promoDiscount.toFixed(2)}`;
        }

        const totalInput = document.getElementById("total_amount");
        if (totalInput) totalInput.value = data.total.toFixed(2);
    };

    const updateSummaryMobile = (data) => {
        if (!mSubtotalEl) return;

        mSubtotalEl.textContent = `Rs.${data.subtotal.toFixed(2)}`;
        mShippingEl.textContent = `Rs.${data.shipping.toFixed(2)}`;
        mTaxEl.textContent = `Rs.${data.tax.toFixed(2)}`;
        mTotalEl.textContent = `Rs.${data.total.toFixed(2)}`;
        
        if (mBottomTotalEl) {
            mBottomTotalEl.textContent = `Rs.${data.total.toFixed(2)}`;
        }
    };

    const recalcSummary = async (promoCode = null, shippingId = null) => {
        if (!shippingId) {
            const selectedShipping = document.querySelector('.shipping-option:checked');
            shippingId = selectedShipping ? parseInt(selectedShipping.value) : null;
        }

        const payload = {
            code: promoCode,
            shipping_id: shippingId
        };

        try {
            const res = await fetch(window.checkoutData.applyPromoRoute, {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": window.checkoutData.csrfToken
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (data && (data.subtotal !== undefined)) {
                updateSummaryDesktop(data);
                updateSummaryMobile(data);
            }

            if (promoCode && promoMessage) {
                promoMessage.classList.remove("hidden");

                if (data.success) {
                    promoMessage.textContent = `✅ ${data.message}`;
                    promoMessage.className = "mt-2 text-sm text-green-400";

                    if (data.promoCodeId) {
                        document.getElementById("promo_code_id").value = data.promoCodeId;
                    }
                } else {
                    promoMessage.textContent = `❌ ${data.message}`;
                    promoMessage.className = "mt-2 text-sm text-red-400";
                    document.getElementById("promo_code_id").value = "";
                }
            }
        } catch (error) {
            console.error("Error recalculating summary:", error);
        }
    };

    // Shipping change event
    document.querySelectorAll('.shipping-option').forEach(option => {
        option.addEventListener('change', () => {
            recalcSummary(appliedPromo, parseInt(option.value));
        });
    });

    // Apply promo code
    if (applyBtn && promoInput) {
        applyBtn.addEventListener('click', () => {
            const promoCode = promoInput.value.trim();
            if (!promoCode) return;

            appliedPromo = promoCode;
            const shippingId = parseInt(document.querySelector('.shipping-option:checked')?.value) || null;
            recalcSummary(appliedPromo, shippingId);
        });
    }

    // Payment method forms handling
    const paymentOptions = document.querySelectorAll('.payment-option-card');
    const paymentForms = document.querySelectorAll('.payment-form');

    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            paymentOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');

            paymentForms.forEach(form => form.classList.remove('active'));
            
            // You'll need to implement the logic to show the correct form
            // based on the selected payment method
        });
    });

    // Form submission
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            // Ensure all hidden inputs are updated
            const shippingMethod = document.querySelector('.shipping-option:checked');
            if (shippingMethod) {
                document.getElementById('final_shipping_method').value = shippingMethod.value;
            }

            const countrySelect = document.querySelector('.country-select');
            if (countrySelect) {
                document.getElementById('country_id').value = countrySelect.value;
            }

        });
    }

    // Initialize with current values
    const initShippingId = parseInt(document.querySelector('.shipping-option:checked')?.value) || null;
    recalcSummary(null, initShippingId);
});