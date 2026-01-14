document.addEventListener('DOMContentLoaded', () => {
    class LuxuryNavigation {
        constructor() {
            this.isInitialized = false;
            this.cartData = {
                items: [],
                total: 0,
                subtotal: 0
            };
            this.processingItems = new Set(); // Track items being updated
            this.csrfToken = this.getCSRFToken();
            this.init();
        }

        init() {
            if (this.isInitialized) return;

            this.setupCartSystem();
            this.setupSearchSystem();
            this.setupMobileMenu();
            this.setupUserDropdown();
            this.setupEventListeners();
            this.setupStickyNavbar();
            this.initializeDrawerWidths();

            this.fetchCartData();

            this.isInitialized = true;
            console.log('🎯 Luxury Navigation Fully Initialized');
        }

        // Security: Safe CSRF token retrieval
        getCSRFToken() {
            return window.appConfig?.csrfToken || 
                   document.querySelector('meta[name="csrf-token"]')?.content ||
                   '';
        }

        initializeDrawerWidths() {
            if (window.innerWidth >= 640) {
                document.documentElement.style.setProperty('--drawer-search-desktop', '500px');
                document.documentElement.style.setProperty('--drawer-cart-desktop', '450px');
            } else {
                document.documentElement.style.setProperty('--drawer-search-mobile', '95vw');
                document.documentElement.style.setProperty('--drawer-cart-mobile', '95vw');
            }
        }

        setupCartSystem() {
            this.cartToggle = document.getElementById('cartToggle');
            this.cartDrawer = document.getElementById('cartDrawer');
            this.cartOverlay = document.getElementById('cartOverlay');
            this.closeCart = document.getElementById('closeCart');
            this.cartBadge = document.getElementById('cartBadge');
            this.cartItemsContainer = document.getElementById('cartItems');
            this.checkoutContainer = document.getElementById('checkoutContainer');
            this.emptyCart = document.getElementById('emptyCart');
            this.cartTotal = document.getElementById('cartTotal');
            this.checkoutBtn = document.getElementById('checkoutBtn');

            // Event listeners with error handling
            this.safeAddEventListener(this.cartToggle, 'click', (e) => {
                e.stopPropagation();
                this.openCartDrawer();
            });

            this.safeAddEventListener(this.closeCart, 'click', (e) => {
                e.stopPropagation();
                this.closeCartDrawer();
            });

            this.safeAddEventListener(this.cartOverlay, 'click', (e) => {
                e.stopPropagation();
                this.closeCartDrawer();
            });
        }

        setupSearchSystem() {
            this.searchToggle = document.getElementById('searchToggle');
            this.searchDrawer = document.getElementById('searchDrawer');
            this.searchOverlay = document.getElementById('searchOverlay');
            this.closeSearch = document.getElementById('closeSearch');
            this.searchInput = document.getElementById('searchInput');
            this.searchIcon = document.getElementById('searchIcon');

            this.safeAddEventListener(this.searchToggle, 'click', (e) => {
                e.stopPropagation();
                this.openSearchDrawer();
            });

            this.safeAddEventListener(this.closeSearch, 'click', (e) => {
                e.stopPropagation();
                this.closeSearchDrawer();
            });

            this.safeAddEventListener(this.searchOverlay, 'click', (e) => {
                e.stopPropagation();
                this.closeSearchDrawer();
            });

            this.safeAddEventListener(this.searchIcon, 'click', (e) => {
                e.stopPropagation();
                this.performSearch();
            });

            this.setupSearchInput();
        }

        setupMobileMenu() {
            this.mobileMenuBtn = document.getElementById('mobile-menu-button');
            this.mobileMenu = document.getElementById('mobile-menu');

            this.safeAddEventListener(this.mobileMenuBtn, 'click', (e) => {
                e.stopPropagation();
                this.toggleMobileMenu();
            });
        }

        setupUserDropdown() {
            this.userBtn = document.getElementById('userMenuButton');
            this.userDropdown = document.getElementById('userDropdown');

            this.safeAddEventListener(this.userBtn, 'click', (e) => {
                e.stopPropagation();
                this.toggleUserDropdown();
            });
        }

        // Security: Safe event listener attachment
        safeAddEventListener(element, event, handler) {
            if (element) {
                element.addEventListener(event, handler);
            }
        }

        setupEventListeners() {
            // Close dropdowns when clicking outside
            document.addEventListener('click', (e) => {
                if (this.userBtn && !this.userBtn.contains(e.target) && this.userDropdown) {
                    this.userDropdown.classList.add('hidden');
                }
                if (this.mobileMenuBtn && !this.mobileMenuBtn.contains(e.target) && this.mobileMenu && !this.mobileMenu.contains(e.target)) {
                    this.mobileMenu.classList.add('hidden');
                }
            });

            // Escape key to close drawers and dropdowns
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeCartDrawer();
                    this.closeSearchDrawer();
                    if (this.userDropdown) this.userDropdown.classList.add('hidden');
                    if (this.mobileMenu) this.mobileMenu.classList.add('hidden');
                }
            });

            // Window resize handler with debounce
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    this.initializeDrawerWidths();
                }, 250);
            });

            this.preventBodyScroll();
        }

        setupStickyNavbar() {
            const navbar = document.getElementById('main-navbar');
            if (!navbar) return;

            let lastScrollY = window.scrollY;
            let ticking = false;

            const updateNavbar = () => {
                if (window.scrollY > 100) {
                    navbar.classList.add('sticky');
                } else {
                    navbar.classList.remove('sticky');
                }

                if (window.scrollY > lastScrollY && window.scrollY > 200) {
                    navbar.style.transform = 'translateY(-100%)';
                } else {
                    navbar.style.transform = 'translateY(0)';
                }

                lastScrollY = window.scrollY;
                ticking = false;
            };

            window.addEventListener('scroll', () => {
                if (!ticking) {
                    requestAnimationFrame(updateNavbar);
                    ticking = true;
                }
            });
        }

        preventBodyScroll() {
            const originalStyle = document.body.style.overflow;

            [this.cartDrawer, this.searchDrawer].forEach(drawer => {
                if (drawer) {
                    const observer = new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            if (mutation.attributeName === 'class') {
                                if (drawer.classList.contains('open')) {
                                    document.body.style.overflow = 'hidden';
                                    document.documentElement.style.overflow = 'hidden';
                                } else if (!this.cartDrawer?.classList.contains('open') && !this.searchDrawer?.classList.contains('open')) {
                                    document.body.style.overflow = originalStyle;
                                    document.documentElement.style.overflow = '';
                                }
                            }
                        });
                    });

                    observer.observe(drawer, { attributes: true });
                }
            });
        }

        // 🛒 CART METHODS
        openCartDrawer() {
            console.log('🛒 Opening cart drawer');
            if (this.cartDrawer) {
                this.cartDrawer.classList.add('open');
            }
            if (this.cartOverlay) this.cartOverlay.classList.add('active');
            this.fetchCartData();
        }

        closeCartDrawer() {
            console.log('🛒 Closing cart drawer');
            if (this.cartDrawer) {
                this.cartDrawer.classList.remove('open');
            }
            if (this.cartOverlay) this.cartOverlay.classList.remove('active');
        }

        updateCartSummary(data) {
            try {
                const subtotal = parseFloat(data.total || 0);
                const items = data.items || [];
                const totalItemsCount = items.reduce((acc, i) => acc + (i.quantity || 0), 0);

                // Update grand total text
                const totalEl = document.querySelector('.cart-grand-total');
                if (totalEl) {
                    totalEl.textContent = subtotal.toFixed(2);
                }

                // Update checkout button
                if (this.checkoutBtn) {
                    this.checkoutBtn.innerHTML = `⚡ Checkout Now — PKR. ${subtotal.toLocaleString()}`;
                    this.checkoutBtn.classList.add('opacity-80');
                    setTimeout(() => this.checkoutBtn.classList.remove('opacity-80'), 300);
                }

                // Update header badge
                if (this.cartBadge) {
                    this.cartBadge.textContent = totalItemsCount;
                    this.cartBadge.style.display = totalItemsCount > 0 ? 'flex' : 'none';
                }

                // Toggle checkout container visibility
                if (this.checkoutContainer) {
                    if (totalItemsCount > 0) {
                        this.checkoutContainer.classList.remove('hidden');
                    } else {
                        this.checkoutContainer.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('❌ Error updating cart summary:', error);
            }
        }

        async fetchCartData() {
            try {
                console.log('🛒 Fetching cart data...');
                const response = await fetch('/cart/data');
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                console.log('🛒 Cart data received:', data);

                this.cartData = data;
                this.renderCartItems(data.items || []);
                this.updateCartSummary(data);

                if (this.cartBadge) {
                    const itemCount = data.items?.reduce((acc, item) => acc + (item.quantity || 0), 0) || 0;
                    this.cartBadge.textContent = itemCount;
                    this.cartBadge.style.display = itemCount > 0 ? 'flex' : 'none';

                    if (itemCount > 0) {
                        this.cartBadge.style.animation = 'none';
                        setTimeout(() => {
                            this.cartBadge.style.animation = 'pulse 2s infinite';
                        }, 10);
                    }
                }

                this.toggleCartEmptyState(data.items?.length > 0);

            } catch (err) {
                console.error('❌ Cart fetch error:', err);
                this.toggleCartEmptyState(false);
                this.showError('Failed to load cart data. Please try again.');
            }
        }

        toggleCartEmptyState(hasItems) {
            if (this.checkoutContainer) {
                this.checkoutContainer.classList.toggle('hidden', !hasItems);
            }
            if (this.emptyCart) {
                this.emptyCart.classList.toggle('hidden', hasItems);
            }
        }

        renderCartItems(items) {
            if (!this.cartItemsContainer) return;

            if (!items || items.length === 0) {
                this.cartItemsContainer.innerHTML = '<div class="text-center p-5 text-gray-500">Your cart is empty</div>';
                return;
            }

            // Security: Sanitize HTML output
            this.cartItemsContainer.innerHTML = items.map((item, index) => {
                const productName = this.escapeHtml(item.product?.name || 'Unknown Item');
                const productPrice = item.product?.price ? item.product.price.toLocaleString() : '0';
                const productImage = item.product?.image ? `/storage/${item.product.image}` : '/images/placeholder-product.jpg';
                const itemId = this.escapeHtml(item.id.toString());

                return `
                    <div class="luxury-cart-item cart-item-row-container animate-fade-in-up" 
                         data-id="${itemId}" 
                         style="animation-delay: ${index * 0.1}s">
                         
                        <div class="luxury-cart-image-wrapper">
                            <img src="${productImage}" 
                                 class="luxury-cart-image" 
                                 alt="${productName}"
                                 onerror="this.src='/images/placeholder-product.jpg'">
                        </div>
                        
                        <div class="luxury-cart-info">
                            <div class="luxury-cart-top">
                                <h4 class="luxury-cart-name">${productName}</h4>
                                <button class="luxury-remove-btn" data-id="${itemId}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            
                            <div class="luxury-cart-bottom">
                                <div class="luxury-quantity-controls-wrapper"> 
                                    <div class="luxury-quantity-controls">
                                        <button class="luxury-quantity-btn minus-btn" data-id="${itemId}" ${item.quantity <= 1 ? 'disabled' : ''}>−</button>
                                        <span class="luxury-quantity-count">${item.quantity || 1}</span>
                                        <button class="luxury-quantity-btn plus-btn" data-id="${itemId}">+</button>
                                    </div>
                                </div>
                
                                <div class="luxury-cart-price-wrapper">
                                    <span class="luxury-cart-price">PKR. ${productPrice}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            this.attachCartEvents();
        }

        // Security: HTML escaping function
        escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        attachCartEvents() {
            document.addEventListener('click', async (e) => {
                // Plus button
                const plusBtn = e.target.closest('.plus-btn');
                if (plusBtn) {
                    e.stopPropagation();
                    const id = plusBtn.dataset.id;
                    await this.handleQuantityUpdate(id, 1, plusBtn);
                    return;
                }

                // Minus button
                const minusBtn = e.target.closest('.minus-btn');
                if (minusBtn) {
                    e.stopPropagation();
                    const id = minusBtn.dataset.id;
                    const quantitySpan = minusBtn.nextElementSibling;
                    const currentQty = parseInt(quantitySpan.textContent.trim());

                    if (currentQty > 1) {
                        await this.handleQuantityUpdate(id, -1, minusBtn);
                    } else if (currentQty === 1) {
                        this.confirmRemoveItem(id);
                    }
                }

                // Remove button
                const removeBtn = e.target.closest('.luxury-remove-btn');
                if (removeBtn) {
                    e.stopPropagation();
                    this.confirmRemoveItem(removeBtn.dataset.id);
                }
            });
        }

        async handleQuantityUpdate(itemId, change, buttonElement) {
            // Security: Prevent spam clicks and race conditions
            if (this.processingItems.has(itemId)) {
                console.log('🛑 Item already being processed:', itemId);
                return;
            }

            const container = document.querySelector(`.cart-item-row-container[data-id="${itemId}"]`);
            if (!container) return;

            const quantitySpan = container.querySelector('.luxury-quantity-count');
            const minusBtn = container.querySelector('.minus-btn');

            const oldQuantity = parseInt(quantitySpan.textContent.trim());
            const newQuantity = oldQuantity + change;
            
            if (newQuantity < 1) return;

            // Update UI immediately for better UX
            quantitySpan.textContent = newQuantity;
            if (minusBtn) minusBtn.disabled = (newQuantity === 1);

            // Lock this item
            this.processingItems.add(itemId);
            const originalBtnContent = buttonElement.innerHTML;
            buttonElement.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`;
            buttonElement.classList.add('opacity-50', 'cursor-not-allowed');

            try {
                const response = await fetch('/cart-update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({
                        cart_item_id: itemId,
                        change: change
                    })
                });

                const data = await response.json();

                if (response.status === 422) {
                    throw new Error(data.message || 'Stock limit reached');
                }

                if (!response.ok) {
                    throw new Error(`Server error: ${response.status}`);
                }

                // Refresh cart data from server
                await this.fetchCartData();

            } catch (err) {
                console.error('❌ Update failed:', err.message);
                
                // Rollback UI on error
                quantitySpan.textContent = oldQuantity;
                if (minusBtn) minusBtn.disabled = (oldQuantity === 1);
                
                this.showError(err.message || 'Failed to update quantity');
            } finally {
                // Unlock item
                this.processingItems.delete(itemId);
                buttonElement.innerHTML = originalBtnContent;
                buttonElement.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        confirmRemoveItem(itemId) {
            if (typeof Swal === 'undefined') {
                if (confirm('Remove this item from your cart?')) {
                    this.removeCartItem(itemId);
                }
                return;
            }

            Swal.fire({
                title: 'Remove Item?',
                text: "Are you sure you want to remove this?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D4AF37',
                cancelButtonColor: '#374151',
                confirmButtonText: 'Yes, remove',
                background: '#1E1E1E',
                color: '#E5E7EB',
                customClass: {
                    container: 'luxury-swal-container',
                    popup: 'swal-popup',
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    this.removeCartItem(itemId);
                }
            });
        }

        async removeCartItem(itemId) {
            const container = document.querySelector(`.cart-item-row-container[data-id="${itemId}"]`);
            if (!container) return;

            // Animate removal
            container.style.transition = 'all 0.4s ease';
            container.style.opacity = '0';
            container.style.transform = 'translateX(50px)';
            container.style.height = '0px';
            container.style.margin = '0px';
            container.style.padding = '0px';
            container.style.overflow = 'hidden';

            try {
                const response = await fetch('/cart-remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    body: JSON.stringify({ cart_item_id: itemId })
                });

                if (!response.ok) throw new Error('Remove failed');

                const data = await response.json();
                
                // Update cart data
                await this.fetchCartData();
                
                this.showToast('success', 'Item removed');

                // Remove from DOM after animation
                setTimeout(() => {
                    if (container.parentNode) {
                        container.remove();
                    }
                }, 500);

            } catch (err) {
                console.error('❌ Remove failed:', err);
                
                // Rollback animation on error
                container.style.transition = 'none';
                container.style.opacity = '1';
                container.style.transform = 'none';
                container.style.height = '';
                container.style.margin = '';
                container.style.padding = '';
                
                this.showError('Failed to remove item. Please try again.');
            }
        }

        // 🔍 SEARCH METHODS
        openSearchDrawer() {
            console.log('🔍 Opening search drawer');
            if (this.searchDrawer) {
                this.searchDrawer.classList.add('open');
            }
            if (this.searchOverlay) this.searchOverlay.classList.add('active');
            setTimeout(() => this.searchInput?.focus(), 300);
        }

        closeSearchDrawer() {
            console.log('🔍 Closing search drawer');
            if (this.searchDrawer) {
                this.searchDrawer.classList.remove('open');
            }
            if (this.searchOverlay) this.searchOverlay.classList.remove('active');
            this.clearSearch();
        }

        setupSearchInput() {
            let debounceTimeout;

            if (this.searchInput) {
                this.searchInput.addEventListener('input', (e) => {
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(() => {
                        this.performSearch();
                    }, 500);
                });

                this.searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.performSearch();
                    }
                });
            }

            // Tag click handlers
            document.querySelectorAll('.luxury-tag').forEach(tag => {
                this.safeAddEventListener(tag, 'click', () => {
                    if (this.searchInput) {
                        this.searchInput.value = tag.textContent.trim();
                        this.performSearch();
                    }
                });
            });
        }

        performSearch() {
            const query = this.searchInput?.value.trim();
            const popular = document.getElementById('popularSearches');
            const recommended = document.getElementById('recommendedProducts');
            const searchResults = document.getElementById('searchResults');
            const searchContainer = document.getElementById('searchResultsContainer');

            if (!query || query.length < 2) {
                this.clearSearch();
                return;
            }

            if (popular) popular.classList.add('hidden');
            if (recommended) recommended.classList.add('hidden');
            if (searchResults) searchResults.classList.remove('hidden');

            // Show loading state
            if (searchContainer) {
                searchContainer.innerHTML = `
                    <div class="flex justify-center items-center py-12">
                        <div class="luxury-loading">
                            <i class="fas fa-spinner fa-spin text-brand-gold text-2xl mb-2"></i>
                            <p class="text-brand-gray">Discovering premium products...</p>
                        </div>
                    </div>
                `;
            }

            // Security: Encode URI component to prevent XSS
            const encodedQuery = encodeURIComponent(query);
            
            fetch(`/search-products?query=${encodedQuery}`)
                .then(res => {
                    if (!res.ok) throw new Error('Search failed');
                    return res.text();
                })
                .then(html => {
                    if (searchContainer) {
                        searchContainer.innerHTML = html;
                        this.attachSearchEvents();
                    }
                })
                .catch(err => {
                    console.error('❌ Search error:', err);
                    if (searchContainer) {
                        searchContainer.innerHTML = `
                            <div class="text-center py-12">
                                <i class="fas fa-search text-brand-gray text-4xl mb-4"></i>
                                <p class="text-brand-muted">Search failed. Please try again.</p>
                            </div>
                        `;
                    }
                });
        }

        clearSearch() {
            const popular = document.getElementById('popularSearches');
            const recommended = document.getElementById('recommendedProducts');
            const searchResults = document.getElementById('searchResults');
            const searchContainer = document.getElementById('searchResultsContainer');

            if (popular) popular.classList.remove('hidden');
            if (recommended) recommended.classList.remove('hidden');
            if (searchResults) searchResults.classList.add('hidden');

            if (searchContainer) {
                searchContainer.innerHTML = '';
            }

            if (this.searchInput) {
                this.searchInput.value = '';
            }
        }

        attachSearchEvents() {
            document.querySelectorAll('.luxury-tag').forEach(tag => {
                this.safeAddEventListener(tag, 'click', () => {
                    if (this.searchInput) {
                        this.searchInput.value = tag.textContent.trim();
                        this.performSearch();
                    }
                });
            });
        }

        // 📱 MOBILE MENU METHODS
        toggleMobileMenu() {
            if (this.mobileMenu) {
                this.mobileMenu.classList.toggle('hidden');
                this.mobileMenu.classList.toggle('animate-slide-down');
            }
        }

        // 👤 USER DROPDOWN METHODS
        toggleUserDropdown() {
            if (this.userDropdown) {
                this.userDropdown.classList.toggle('hidden');
                this.userDropdown.classList.toggle('animate-slide-down');
            }
        }

        // 💫 UTILITY METHODS
        showToast(icon, title) {
            if (typeof Swal === 'undefined') {
                console.log(`${icon}: ${title}`);
                return;
            }

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#1E1E1E',
                color: '#E5E7EB',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                },
                customClass: {
                    container: 'luxury-swal-container',
                    popup: 'swal-popup',
                },
            });

            Toast.fire({ icon, title });
        }

        showError(message) {
            if (typeof Swal === 'undefined') {
                console.error('❌', message);
                alert(message);
                return;
            }

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
                confirmButtonColor: '#D4AF37',
                background: '#1E1E1E',
                color: '#E5E7EB',
                customClass: {
                    container: 'luxury-swal-container',
                    popup: 'swal-popup',
                },
            });
        }

        showSuccess(message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Success!',
                    text: message,
                    icon: 'success',
                    confirmButtonColor: '#D4AF37',
                    background: '#1E1E1E',
                    color: '#E5E7EB',
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: {
                        container: 'luxury-swal-container',
                        popup: 'swal-popup',
                    },
                });
            } else {
                console.log('✅', message);
            }
        }
    }

    // 🎯 Initialize Luxury Navigation
    window.luxuryNavigation = new LuxuryNavigation();

    // 🌍 Global functions for HTML onclick attributes
    window.openCartDrawer = function () {
        window.luxuryNavigation?.openCartDrawer();
    };

    window.closeCartDrawer = function () {
        window.luxuryNavigation?.closeCartDrawer();
    };

    window.clearCart = function () {
        if (confirm('Are you sure you want to clear your entire cart?')) {
            window.luxuryNavigation?.clearCart();
        }
    };

    // 🎯 Enhanced Checkout Validation
    document.addEventListener('DOMContentLoaded', () => {
        const checkoutBtn = document.getElementById('checkoutBtn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', (e) => {
                const cartCount = parseInt(document.querySelector('.cart-count-badge')?.textContent || '0');
                if (cartCount === 0) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Empty Collection',
                            text: 'Your collection is empty! Add some premium tech first.',
                            icon: 'warning',
                            confirmButtonColor: '#D4AF37',
                            background: '#1E1E1E',
                            color: '#E5E7EB',
                            customClass: {
                                container: 'luxury-swal-container',
                                popup: 'swal-popup',
                            },
                        });
                    } else {
                        alert('Your cart is empty! Add some items first.');
                    }
                }
            });
        }
    });

    console.log("🎉 Luxury Navigation System Ready!");
});

// Module export for Node.js environment
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LuxuryNavigation;
}