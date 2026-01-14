import '@css/pages/product-detail.css';

   // Image gallery functionality
    document.addEventListener("DOMContentLoaded", () => {
        console.log("Product Detail JS Loaded");

        const ADD_TO_CART_URL = window.productDetailData.addToCartUrl;
const CSRF_TOKEN = window.productDetailData.csrfToken;
const LOGIN_URL = window.productDetailData.loginUrl;


        // Thumbnail click to change main image
        const mainImage = document.getElementById("main-watch-image");
        const thumbnails = document.querySelectorAll(".thumbnail");

        thumbnails.forEach((thumb, idx) => {
            thumb.addEventListener("click", () => {
                mainImage.src = thumb.querySelector("img").src;

                // Remove active from all
                thumbnails.forEach(t => t.classList.remove("active"));
                thumb.classList.add("active");
            });
        });
    

    // Timer functionality
    const ONE_HOUR = 60 * 60 * 1000;
    const hoursEl = document.getElementById('hours');
    const minutesEl = document.getElementById('minutes');
    const secondsEl = document.getElementById('seconds');

    let endTime = localStorage.getItem('timerEnd');
    if (!endTime) {
        endTime = new Date().getTime() + ONE_HOUR;
        localStorage.setItem('timerEnd', endTime);
    } else {
        endTime = parseInt(endTime);
    }

    function updateTimer() {
        const now = new Date().getTime();
        let distance = endTime - now;

        if (distance <= 0) {
            hoursEl.textContent = "00";
            minutesEl.textContent = "00";
            secondsEl.textContent = "00";
            localStorage.removeItem('timerEnd');
            clearInterval(timerInterval);
            return;
        }

        const h = Math.floor(distance / (1000 * 60 * 60));
        const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const s = Math.floor((distance % (1000 * 60)) / 1000);

        hoursEl.textContent = h.toString().padStart(2, '0');
        minutesEl.textContent = m.toString().padStart(2, '0');
        secondsEl.textContent = s.toString().padStart(2, '0');
    }

    // Initial update
    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);

    // Animation for elements
        const animatedElements = document.querySelectorAll('.watch-image, .details-section, .description-box');
        animatedElements.forEach((el, index) => {
            setTimeout(() => {
                el.classList.add('animate-fade-in');
            }, 300 * index);
        });

    // Quantity selector
    document.querySelectorAll('.quantity-box').forEach(box => {
        const minusBtn = box.querySelector('.minus-btn');
        const plusBtn = box.querySelector('.plus-btn');
        const quantityValue = box.querySelector('.quantity-value');

        let quantity = parseInt(quantityValue.textContent);

        plusBtn.addEventListener("click", () => {
            quantity++;
            quantityValue.textContent = quantity;
        });

        minusBtn.addEventListener("click", () => {
            if (quantity > 1) {
                quantity--;
                quantityValue.textContent = quantity;
            }
        });
    });

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let productId = this.getAttribute('data-id');
            let btnText = this.querySelector('.btn-text');
            let btnSpinner = this.querySelector('.btn-spinner');

            let quantityElement = document.querySelector('.quantity-value');
            let quantity = quantityElement ? parseInt(quantityElement.textContent) : 1;

            // Show spinner
            btnText.classList.add('hidden');
            btnSpinner.classList.remove('hidden');
            this.disabled = true;

            fetch(ADD_TO_CART_URL, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": CSRF_TOKEN,
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.openCartDrawer(); // call the global function

                        fetchCartData();
                    }
                })
                .catch(err => console.error(err))
                .finally(() => {
                    btnSpinner.classList.add('hidden');
                    btnText.classList.remove('hidden');
                    btn.disabled = false;
                });
        });
    });

        const reviewModal = document.getElementById("reviewModal");
        const writeReviewBtn = document.getElementById("writeReviewBtn");
        const writeFirstReviewBtn = document.getElementById("writeFirstReviewBtn");
        const closeReviewModal = document.getElementById("closeReviewModal");
        const reviewForm = document.getElementById("reviewForm");
        const ratingStars = document.querySelectorAll(".star-rating");
        const selectedRating = document.getElementById("selectedRating");
        const ratingError = document.getElementById("ratingError");
        const uploadImagesBtn = document.getElementById("uploadImagesBtn");
        const reviewImages = document.getElementById("reviewImages");
        const imagePreview = document.getElementById("imagePreview");
        const reviewBtnText = document.getElementById("reviewBtnText");
        const reviewBtnLoader = document.getElementById("reviewBtnLoader");
        const guestReviewBtn = document.getElementById("guestReviewBtn");

        // 🔒 Guest user alert
        if (guestReviewBtn) {
            guestReviewBtn.addEventListener("click", function() {
                Swal.fire({
                    title: "Login Required",
                    text: "Kindly log in first to write a review.",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonText: "Go to Login",
                    cancelButtonText: "Cancel",
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    background: "#1a1a1a",
                    color: "#fff",
                }).then(result => {
                    if (result.isConfirmed) {
                        window.location.href = LOGIN_URL;
                    }
                });
            });
            return; // Stop rest of code for guests
        }

        // ⭐ Modal open/close
        if (writeReviewBtn) writeReviewBtn.addEventListener("click", openReviewModal);
        if (writeFirstReviewBtn) writeFirstReviewBtn.addEventListener("click", openReviewModal);
        closeReviewModal.addEventListener("click", closeReviewModalFunc);

        function openReviewModal() {
            reviewModal.classList.remove("hidden");
            document.body.classList.add("overflow-hidden");
        }

        function closeReviewModalFunc() {
            reviewModal.classList.add("hidden");
            document.body.classList.remove("overflow-hidden");
        }

        // ⭐ Rating stars
        ratingStars.forEach(star => {
            star.addEventListener("click", function() {
                const rating = parseInt(this.dataset.rating);
                selectedRating.value = rating;

                ratingStars.forEach((s, index) => {
                    const icon = s.querySelector("i");
                    if (index < rating) {
                        icon.classList.replace("far", "fas");
                        s.classList.add("text-amber-400");
                    } else {
                        icon.classList.replace("fas", "far");
                        s.classList.remove("text-amber-400");
                    }
                });
                ratingError.classList.add("hidden");
            });
        });

        let selectedFiles = [];

        uploadImagesBtn.addEventListener("click", () => reviewImages.click());

        reviewImages.addEventListener("change", function() {
            const files = Array.from(this.files);

            if (selectedFiles.length + files.length > 3) {
                Swal.fire("Too many images!", "You can upload a maximum of 3 images.", "warning");
                return;
            }

            files.forEach(file => {
                if (!file.type.startsWith("image/")) return;
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire("File too large!", `${file.name} exceeds 5MB.`, "error");
                    return;
                }
                selectedFiles.push(file);
            });

            this.value = "";
            renderPreview();
        });

        function renderPreview() {
            imagePreview.innerHTML = "";
            imagePreview.classList.toggle("hidden", selectedFiles.length === 0);

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = e => {
                    const imgContainer = document.createElement("div");
                    imgContainer.className = "relative group rounded-lg overflow-hidden";

                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.className = "w-full h-24 object-cover rounded-lg transition-transform duration-300 group-hover:scale-105";

                    const removeBtn = document.createElement("button");
                    removeBtn.type = "button";
                    removeBtn.className = `
                    absolute top-1 right-1 bg-red-600/80 hover:bg-red-600
                    text-white rounded-full w-6 h-6 flex items-center justify-center 
                    text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200
                `;
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';

                    removeBtn.addEventListener("click", () => {
                        selectedFiles.splice(index, 1);
                        renderPreview();
                    });

                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeBtn);
                    imagePreview.appendChild(imgContainer);
                };
                reader.readAsDataURL(file);
            });
        }

        // 📨 Submit Review
        reviewForm.addEventListener("submit", function(e) {
            e.preventDefault();

            if (selectedRating.value === "0") {
                ratingError.classList.remove("hidden");
                return;
            }

            reviewBtnText.classList.add("hidden");
            reviewBtnLoader.classList.remove("hidden");

            const formData = new FormData(reviewForm);
            selectedFiles.forEach(file => formData.append("images[]", file));

            fetch(reviewForm.action, {
                    method: "POST",
                    body: formData,
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Review Submitted!",
                            text: "Your review will be visible after admin approval.",
                            confirmButtonColor: "#4f46e5",
                            background: "#1e293b",
                            color: "#f8fafc",
                            iconColor: "#00f2fe",
                        }).then(() => location.reload());

                        closeReviewModalFunc();
                        selectedFiles = [];
                        renderPreview();
                        reviewForm.reset();
                        selectedRating.value = "0";
                        ratingStars.forEach(star => {
                            const icon = star.querySelector("i");
                            icon.classList.replace("fas", "far");
                            star.classList.remove("text-amber-400");
                        });
                    } else {
                        throw new Error(data.message || "Something went wrong");
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: "error",
                        title: "Submission Failed",
                        text: err.message || "There was a problem submitting your review. Please try again.",
                        confirmButtonColor: "#ec4899",
                        background: "#1e293b",
                        color: "#f8fafc",
                    });
                })
                .finally(() => {
                    reviewBtnLoader.classList.add("hidden");
                    reviewBtnText.classList.remove("hidden");
                });
        });
    });


const CSRF_TOKEN = window.productDetailData?.csrfToken;

const modal = document.getElementById("notifyModal");
const openBtn = document.getElementById("notifyBtn");
const closeBtn = document.getElementById("closeModal");
const form = document.getElementById("notifyForm");
const btnText = document.getElementById("btnText");
const btnLoader = document.getElementById("btnLoader");

// Open Modal
openBtn?.addEventListener("click", () => {
    modal.classList.remove("hidden");
});

// Close Modal
closeBtn?.addEventListener("click", () => {
    modal.classList.add("hidden");
});

// Submit Email
form?.addEventListener("submit", function(e) {
    e.preventDefault();

    btnText.classList.add("hidden");
    btnLoader.classList.remove("hidden");

    // Use FormData and do NOT set Content-Type manually
    const formData = new FormData(form);

    fetch(form.action, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": CSRF_TOKEN,
            "Accept": "application/json"
        },
        body: formData
    })
    .then(res => {
        if (!res.ok) throw new Error("Network response not ok");
        return res.json();
    })
    .then(data => {
        btnLoader.classList.add("hidden");
        btnText.classList.remove("hidden");
        modal.classList.add("hidden");
        form.reset();

        Swal.fire({
            icon: "success",
            title: "Subscribed!",
            text: data.message ?? "We will notify you as soon as this product is available.",
            confirmButtonColor: "#4f46e5",
            background: "#1e293b",
            color: "#f8fafc",
            iconColor: "#00f2fe"
        });
    })
    .catch(err => {
        console.error("Notify form error:", err);
        btnLoader.classList.add("hidden");
        btnText.classList.remove("hidden");

        Swal.fire({
            icon: "error",
            title: "Oops!",
            text: "Something went wrong. Try again.",
            confirmButtonColor: "#ec4899",
            background: "#1e293b",
            color: "#f8fafc"
        });
    });
});
