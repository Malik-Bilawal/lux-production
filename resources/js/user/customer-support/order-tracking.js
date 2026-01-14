import '@css/customer-support/track-order.css';
     document.addEventListener('DOMContentLoaded', function() {
        console.log("Order Tracking JS Loaded");


        const trackingForm = document.getElementById('trackingForm');
        const resultsSection = document.getElementById('resultsSection');
        const loader = document.getElementById('loader');
        const noOrderMessage = document.getElementById('noOrderMessage');

        const cancelModal = document.getElementById('cancelModal');
        const closeModal = document.getElementById('closeModal');
        const confirmCancel = document.getElementById('confirmCancel');
        const cancelReason = document.getElementById('cancelReason');
        const cancelComment = document.getElementById('cancelComment');
        const cancelButton = document.getElementById('cancelButton');
        const downloadInvoiceBtn = document.getElementById('downloadInvoiceBtn');
        const reorderButton = document.getElementById('reorderButton');


       let currentOrderId = null;
let currentOrderCode = null;

        function formatPrice(value) {
            return (typeof value === 'number') ? `$${value.toFixed(2)}` : '-';
        }

// ---------------------------
// Form Submit - Fetch Order
/// ---------------------------
// Tracking Form Submit
// ---------------------------
trackingForm?.addEventListener('submit', async function (e) {
e.preventDefault();

const trackingCode = document.getElementById('trackingCode').value.trim();
const emailOrPhone = document.getElementById('emailOrPhone').value.trim();


if (!trackingCode || !emailOrPhone) {
    alert("Please enter tracking code and email/phone.");
    return;
}

await fetchOrderDetails(trackingCode, emailOrPhone);
});

// ---------------------------
// Fetch Order Details (Debug Version)
// ---------------------------
async function fetchOrderDetails(trackingCode, emailOrPhone) {
loader?.classList.remove("hidden");
resultsSection?.classList.add("hidden");
noOrderMessage?.classList.add("hidden");

try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const bodyData = {
        tracking_code: trackingCode,
        contact: emailOrPhone
    };

    const response = await fetch("/track-order", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify(bodyData)
    });


    const data = await response.json();

    loader?.classList.add("hidden");

    if (data.success && data.order) {

        console.log("✅ Order fetched:", data.order);

        const order = data.order;
        currentOrderId = order.id;
        currentOrderCode = order.order_code;

        // Show/Hide Cancel Button
        if (['pending', 'processing'].includes(order.status?.toLowerCase()) &&
            order.payment_method?.trim().toUpperCase() === 'COD') {
            cancelButton?.classList.remove('hidden');
            cancelButton.dataset.orderId = order.id;
        } else {
            cancelButton?.classList.add('hidden');
        }

        // Download Invoice Button
        if (!['cancelled', 'refunded', 'cancellation requested'].includes(order.status?.toLowerCase())) {
            downloadInvoiceBtn.classList.remove('hidden');
        } else {
            downloadInvoiceBtn.classList.add('hidden');
        }

        // Reorder Button
        const status = order.status?.trim().toLowerCase() || '';
        const paymentMethod = order.payment_method?.trim().toLowerCase() || '';

        if (['delivered', 'completed'].includes(status)    &&
        (paymentMethod === 'cash on delivery' || paymentMethod === 'cod')) {
            reorderButton.classList.remove('hidden');
            reorderButton.style.display = 'flex';
        } else {
            reorderButton.classList.add('hidden');
            reorderButton.style.display = 'none';
        }

        // Populate Order Info
        document.getElementById('order-code').textContent = order.order_code ?? '-';
        document.getElementById('order-date').textContent = order.placed_on ?? '-';
        document.getElementById('order-status-badge').textContent = order.status ?? '-';
        setStatusBadge(order.status);
        document.getElementById('order-total').textContent = formatPrice(order.total);
        document.getElementById('order-grand-total').textContent = formatPrice(order.total);
        document.getElementById('promo-code-discount').textContent = order.promo_discount ?? '-';
        document.getElementById('order-payment').textContent = order.payment_method ?? '-';
        document.getElementById('order-subtotal').textContent = formatPrice(order.subtotal);
        document.getElementById('order-shipping').textContent = formatPrice(order.shipping);
        document.getElementById('order-tax').textContent = formatPrice(order.tax);

        // Order Items
        const itemsContainer = document.getElementById('order-items');
        itemsContainer.innerHTML = '';
        if (order.items?.length > 0) {
            document.getElementById('items-count').textContent = order.items.length;
            order.items.forEach(item => {
                const div = document.createElement('div');
                div.className = "flex justify-between items-center py-3 border-b border-gray-700/40";
                div.innerHTML = `
                    <div class="flex items-center gap-4">
                        <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-lg">
                        <div>
                            <p class="font-medium">${item.name}</p>
                            <p class="text-sm text-gray-400">${item.description ?? ''}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium">${formatPrice(item.price)}</p>
                        <p class="text-sm text-gray-400">Qty: ${item.qty}</p>
                    </div>
                `;
                itemsContainer.appendChild(div);
            });
        } else {
            document.getElementById('items-count').textContent = 0;
            itemsContainer.innerHTML = `<p class="text-gray-400 text-center py-4">No items found.</p>`;
        }

        // Delivery Info
        document.getElementById('delivery-estimate').textContent = order.delivery_estimate ?? '-';
        document.getElementById('shipping-method').textContent = order.shipping_method ?? '-';

        // Address Info
        if (order.addresses && Object.keys(order.addresses).length > 0) {
            const addr = order.addresses; // directly use object
            document.getElementById('shipping-address').textContent =
                ` ${addr.address_1}, ${addr.address_2}, ${addr.city}, ${addr.state}, ${addr.country}`;
        } else {
            document.getElementById('shipping-address').textContent = '-';
        }
        
        
        // Final Display
        resultsSection?.classList.remove("hidden");
        resultsSection?.scrollIntoView({ behavior: 'smooth' });
    } else {
        console.warn("❌ No order found in response:", data);
        noOrderMessage?.classList.remove("hidden");
    }

} catch (error) {
    console.error("🔥 Error fetching order:", error);
    loader?.classList.add("hidden");
    noOrderMessage?.classList.remove("hidden");
}
}


        // ---------------------------
        // Cancel Modal Handlers
        // ---------------------------
// ---------------------------
// Cancel Order Modal Logic
// ---------------------------
cancelButton?.addEventListener('click', () => cancelModal?.classList.remove('hidden'));
closeModal?.addEventListener('click', () => cancelModal?.classList.add('hidden'));
cancelModal?.addEventListener('click', e => {
if (e.target === cancelModal) cancelModal.classList.add('hidden');
});

// ---------------------------
// Confirm Cancel (Modern JS)
// ---------------------------
confirmCancel?.addEventListener('click', async function () {
const reason = cancelReason.value;
const comment = cancelComment.value.trim();

if (!reason) {
    Swal.fire({
        icon: 'warning',
        title: 'Select Reason',
        text: 'Please select a cancellation reason.',
    });
    return;
}

if (!currentOrderId) {
    Swal.fire({
        icon: 'error',
        title: 'Order Missing',
        text: 'Order ID missing.',
    });
    return;
}

try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const cancelUrl = document.querySelector('meta[name="order-cancellation-url"]').getAttribute('content');

    const response = await fetch(cancelUrl, {
            method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify({
            order_id: currentOrderId,
            reason,
            comment
        })
    });

    const data = await response.json();
    cancelModal.classList.add('hidden');

    if (data?.success) {
        setStatusBadge('Cancellation Requested');
        Swal.fire({
            icon: 'success',
            title: 'Cancelled',
            text: data.message || 'Order cancellation requested successfully!',
        });
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Cannot Cancel',
            text: data.message || 'Cancellation failed, server returned false.',
        });
    }

} catch (error) {
    cancelModal.classList.add('hidden');

    let message = 'Something went wrong.';
    if (error?.status === 403) {
        message = 'You are not authorized to cancel this order.';
    } else if (error?.response?.message) {
        message = error.response.message;
    }

    Swal.fire({
        icon: 'error',
        title: 'Cancellation Failed',
        text: message,
    });

    console.warn("Error during cancellation:", error);
}
});


        // ---------------------------
        // Status Badge Helper
        // ---------------------------
        function setStatusBadge(status) {
            const badge = document.getElementById('order-status-badge');
            if (!badge) return;

            let statusClass = '';
            let icon = '';

            switch (status.toLowerCase()) {
                case 'pending':
                    statusClass = 'status-pending';
                    icon = '<i class="fas fa-clock"></i>';
                    break;
                case 'processing':
                    statusClass = 'status-processing';
                    icon = '<i class="fas fa-cog"></i>';
                    break;
                case 'delivered':
                    statusClass = 'status-delivered';
                    icon = '<i class="fas fa-check-circle"></i>';
                    break;
                case 'cancelled':
                case 'cancellation requested':
                    statusClass = 'status-cancelled';
                    icon = '<i class="fas fa-times-circle"></i>';
                    break;
                default:
                    statusClass = 'status-pending';
                    icon = '<i class="fas fa-info-circle"></i>';
            }

            badge.className = `status-badge ${statusClass}`;
            badge.innerHTML = `${icon} <span>${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
        }



        downloadInvoiceBtn.onclick = async function () {

if (!currentOrderId) {
    console.error("Invoice download failed: Order ID missing.");
    alert("Order ID missing.");
    return;
}


const invoiceUrl = `/invoice/${currentOrderId}`;

try {

    const response = await fetch(invoiceUrl, {
        method: "GET",
        headers: {
            "Accept": "application/pdf"
        }
    });

    if (!response.ok) {
        console.error("Invoice fetch failed. HTTP Status:", response.status);
        alert("Failed to generate invoice. Please try again later.");
        return;
    }


    // Get filename from Content-Disposition header
    const contentDisposition = response.headers.get("Content-Disposition");

    let filename = `invoice-${currentOrderId}.pdf`;
    if (contentDisposition && contentDisposition.includes("filename=")) {
        filename = contentDisposition.split("filename=")[1].replace(/['"]/g, "").trim();
    }

    // Convert response to Blob
    const blob = await response.blob();
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;

    // Trigger download
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);


} catch (error) {
    console.error("Invoice download failed:", error);
    alert("Failed to generate invoice. Check console for details.");
}
};




        //  REORDER THE GREAT  SYSTEM

        reorderButton?.addEventListener('click', async () => {
            if (!currentOrderCode) {
                return Swal.fire({
                    icon: 'error',
                    title: 'Order Missing',
                    text: 'Order ID missing.'
                });
            }
        
            reorderButton.disabled = true;
            reorderButton.textContent = "Placing reorder...";
        
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
                // Log what we're sending
                console.log("🚀 Reorder Request:", {
                    url: `/reorder/${currentOrderCode}`,
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    }
                });
        
                const response = await fetch(`/reorder/${currentOrderCode}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    }
                });
        
                // Log raw response
                console.log("📥 Raw Response:", response);
        
                // Try parsing JSON
                let data;
                try {
                    data = await response.json();
                    console.log("✅ Parsed Response:", data);
                } catch (jsonError) {
                    console.error("❌ JSON Parse Error:", jsonError);
                    const text = await response.text();
                    console.log("📄 Response Text:", text);
                    throw new Error("Server did not return valid JSON.");
                }
        
                reorderButton.disabled = false;
                reorderButton.textContent = "Reorder";
        
                if (data.success && data.order) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Reorder Placed!',
                        html: `Your reorder has been placed successfully.<br>
                               Tracking Code: <strong>${data.order.order_code}</strong>`,
                        confirmButtonText: 'View Tracking',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        window.location.href = `/checkout/success/${data.order.order_code}`;
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cannot Reorder',
                        text: data.message || "Failed to place reorder."
                    });
                }
        
            } catch (error) {
                reorderButton.disabled = false;
                reorderButton.textContent = "Reorder";
        
                console.error("🔥 Reorder request failed:", error);
        
                Swal.fire({
                    icon: 'error',
                    title: 'Reorder Failed',
                    text: "Failed to place reorder. Check console for details.",
                    customClass: { popup: 'max-w-lg' }
                });
            }
        });
        



    });