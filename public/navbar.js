document.addEventListener('DOMContentLoaded', () => {
    class LuxuryNavigation {
        constructor() {
            this.isInitialized = false;
            this.cartData = {
                items: [],
                total: 0,
                subtotal: 0
            };
            this.processingItems = new Set(); 
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

        safeAddEventListener(element, event, handler) {
            if (element) {
                element.addEventListener(event, handler);
            }
        }

        setupEventListeners() {
            document.addEventListener('click', (e) => {
                if (this.userBtn && !this.userBtn.contains(e.target) && this.userDropdown) {
                    this.userDropdown.classList.add('hidden');
                }
                if (this.mobileMenuBtn && !this.mobileMenuBtn.contains(e.target) && this.mobileMenu && !this.mobileMenu.contains(e.target)) {
                    this.mobileMenu.classList.add('hidden');
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.closeCartDrawer();
                    this.closeSearchDrawer();
                    if (this.userDropdown) this.userDropdown.classList.add('hidden');
                    if (this.mobileMenu) this.mobileMenu.classList.add('hidden');
                }
            });

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

                const totalEl = document.querySelector('.cart-grand-total');
                if (totalEl) {
                    totalEl.textContent = subtotal.toFixed(2);
                }

                if (this.checkoutBtn) {
                    this.checkoutBtn.innerHTML = `⚡ Checkout Now — PKR. ${subtotal.toLocaleString()}`;
                    this.checkoutBtn.classList.add('opacity-80');
                    setTimeout(() => this.checkoutBtn.classList.remove('opacity-80'), 300);
                }

                if (this.cartBadge) {
                    this.cartBadge.textContent = totalItemsCount;
                    this.cartBadge.style.display = totalItemsCount > 0 ? 'flex' : 'none';
                }

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
                    const totalItems = data.items?.length || 0; 
                    this.cartBadge.textContent = totalItems;
                    this.cartBadge.style.display = totalItems > 0 ? 'flex' : 'none';
                
                    if (totalItems > 0) {
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
                return;
            }

            this.cartItemsContainer.innerHTML = items.map((item, index) => {
                const productName = this.escapeHtml(item.product?.name || 'Unknown Item');
                const productPrice = item.product?.price ? item.product.price.toLocaleString() : '0';
                const productImage = `/storage/${item.product.mainImage?.image_path}`;


                const itemId = this.escapeHtml(item.id.toString());

                return `
                    <div class="luxury-cart-item cart-item-row-container animate-fade-in-up" 
                         data-id="${itemId}" 
                         style="animation-delay: ${index * 0.1}s">
                         
                        <div class="luxury-cart-image-wrapper">
                            <img src="${productImage}" 
                                 class="luxury-cart-image" 
                                 alt="${productName}"
                                 onerror="this.src='data:image/jpeg;base64,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'">
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
                // await this.fetchCartData();

            } catch (err) {
                console.error('❌ Update failed:', err.message);

                quantitySpan.textContent = oldQuantity;
                if (minusBtn) minusBtn.disabled = (oldQuantity === 1);

                this.showError(err.message || 'Failed to update quantity');
            } finally {
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
            // container.style.transform = 'translateX(50px)';
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
                            <p class="text-theme-primary">Discovering premium products...</p>
                        </div>
                    </div>
                `;
            }

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

        // MOBILE MENU METHODS
        toggleMobileMenu() {
            if (this.mobileMenu) {
                this.mobileMenu.classList.toggle('hidden');
                this.mobileMenu.classList.toggle('animate-slide-down');
            }
        }

        //  USER DROPDOWN METHODS
        toggleUserDropdown() {
            if (this.userDropdown) {
                this.userDropdown.classList.toggle('hidden');
                this.userDropdown.classList.toggle('animate-slide-down');
            }
        }

        //  UTILITY METHODS
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

    window.luxuryNavigation = new LuxuryNavigation();

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

    //  Enhanced Checkout Validation
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

    console.log(" Luxury Navigation System Ready!");
});

if (typeof module !== 'undefined' && module.exports) {
    module.exports = LuxuryNavigation;
}
