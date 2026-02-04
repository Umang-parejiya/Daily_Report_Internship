/**
 * product.js
 * Handles product-related interactions.
 * PDP: Image switching.
 * PLP: Product count display.
 */

document.addEventListener('DOMContentLoaded', function () {

    // --- PLP: Product Count & Pagination ---
    const productGrid = document.querySelector('.product-grid');
    if (productGrid) {
        const productCards = document.querySelectorAll('.product-card');
        const count = productCards.length;

        // Update product count display if it exists
        const subtitle = document.querySelector('.section-subtitle');
        // If JS is preferred for simple display, it updates here. 
        // Note: PHP now handles "Showing X of Y" more accurately.

        // Optional: Highlight active page (redundant but requested)
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = urlParams.get('page') || '1';
        document.querySelectorAll('.page-link').forEach(link => {
            if (link.textContent.trim() === currentPage) {
                link.classList.add('active');
            }
        });
    }

    // --- PDP: Image Switching ---
    const productGallery = document.querySelector('.product-gallery');
    if (productGallery) {
        const mainImage = productGallery.querySelector('.main-image');
        const thumbnails = productGallery.querySelectorAll('.thumb-item');

        if (mainImage && thumbnails.length > 0) {
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function () {
                    // Update main image
                    const newSrc = this.querySelector('img').src;

                    // Fade effect
                    mainImage.style.opacity = '0';
                    setTimeout(() => {
                        mainImage.src = newSrc;
                        mainImage.style.opacity = '1';
                    }, 200);

                    // Update active state
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        }
    }
    // --- PDP: AJAX Add to Cart ---
    const addToCartBtn = document.querySelector('button[name="add_to_cart"]');
    if (addToCartBtn) {
        addToCartBtn.closest('form').addEventListener('submit', function (e) {
            e.preventDefault();

            const urlParams = new URLSearchParams(window.location.search);
            const productId = urlParams.get('id');

            if (!productId) return;

            const formData = new FormData();
            formData.append('ajax_add', '1');
            formData.append('product_id', productId);

            // Button loading state
            const originalText = addToCartBtn.textContent;
            addToCartBtn.textContent = 'Adding...';
            addToCartBtn.disabled = true;

            fetch('pdp?id=' + productId, {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Show Success Banner (No Alert)
                        const banner = document.getElementById('pdp-success-banner');
                        if (banner) {
                            banner.style.display = 'flex';
                            void banner.offsetWidth; // Force reflow
                            banner.classList.add('visible');
                        }

                        // Update button text permanently to indicate item is in cart
                        addToCartBtn.textContent = 'More Item Add';
                        addToCartBtn.disabled = false;
                    } else {
                        alert('Error: ' + data.message);
                        // Revert on error
                        addToCartBtn.textContent = originalText;
                        addToCartBtn.disabled = false;
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Something went wrong');
                    // Revert on error
                    addToCartBtn.textContent = originalText;
                    addToCartBtn.disabled = false;
                });
        });
    }

});
