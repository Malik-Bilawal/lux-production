// ===============================
// MAIN SCRIPT
// ===============================

import '@css/customer-support/order-history.css';

window.addEventListener('DOMContentLoaded', () => {

    // ===============================
    // TOGGLE VIEW DETAILS
    // ===============================
    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderCard = this.closest('.modern-card');
            const timeline = orderCard.querySelector('.order-timeline');

            if (timeline) {
                timeline.classList.toggle('hidden');

                if (timeline.classList.contains('hidden')) {
                    this.innerHTML = '<i class="fas fa-eye"></i> View Details';
                } else {
                    this.innerHTML = '<i class="fas fa-eye-slash"></i> Hide Details';
                }
            }
        });
    });

    // ===============================
    // FILTERING
    // ===============================
    const filterSelect = document.querySelector('.filter-select');
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            const status = this.value.toLowerCase();
            const orders = document.querySelectorAll('.modern-card');

            orders.forEach(order => {
                const orderStatus = order.querySelector('.status-badge').textContent.toLowerCase();
                order.style.display = (status === '' || orderStatus.includes(status)) ? 'block' : 'none';
            });
        });
    }

    // ===============================
    // SEARCH
    // ===============================
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const orders = document.querySelectorAll('.modern-card');

            orders.forEach(order => {
                const orderId = order.querySelector('h2').textContent.toLowerCase();
                const productName = order.querySelector('h3').textContent.toLowerCase();

                order.style.display = (orderId.includes(searchTerm) || productName.includes(searchTerm)) ? 'block' : 'none';
            });
        });
    }

    // ===============================
    // REORDER FUNCTIONALITY
    // ===============================
    document.querySelectorAll('.reorder-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault(); // prevent full reload

            const button = form.querySelector('button');
            const orderCode = form.action.split('/').pop();
            const token = form.querySelector('input[name="_token"]').value;

            button.disabled = true;
            button.textContent = "Placing reorder...";

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _token: token })
                });

                const data = await response.json();

                button.disabled = false;
                button.innerHTML = '<i class="fas fa-rotate-left"></i> Reorder';

                if (response.ok && data.success && data.order) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Reorder Placed!',
                        html: `Your reorder has been placed successfully.<br>
                               Tracking Code: <strong>${data.order.tracking_code}</strong>`,
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

            } catch (err) {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-rotate-left"></i> Reorder';
                console.error(err);

                Swal.fire({
                    icon: 'error',
                    title: 'Reorder Failed',
                    text: "Failed to place reorder. Check console for details.",
                    customClass: { popup: 'max-w-lg' }
                });
            }
        });
    });
});

// GLOBAL FUNCTIONS (for HTML onclick)

window.openCancelModal = function(orderId) {
    const modal = document.getElementById('cancelModal');
    if (!modal) return console.warn("Cancel modal not found!");

    modal.classList.remove('hidden');
    const input = document.getElementById('cancelOrderId');
    if (input) input.value = orderId;
}

window.closeCancelModal = function() {
    const modal = document.getElementById('cancelModal');
    if (modal) modal.classList.add('hidden');
}
