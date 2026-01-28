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
});
