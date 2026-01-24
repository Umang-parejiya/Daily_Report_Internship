/**
 * product.js
 * Handles product-related interactions.
 * PDP: Image switching.
 * PLP: Product count display.
 */

document.addEventListener('DOMContentLoaded', function () {

    // --- PLP: Product Count ---
    const productGrid = document.querySelector('.product-grid');
    if (productGrid) {
        const productCards = document.querySelectorAll('.product-card');
        const count = productCards.length;

        // Look for the subtitle "Showing X product(s)"
        // In plp.php: <p class="section-subtitle">Showing ... product(s)</p>
        const subtitle = document.querySelector('.section-subtitle');
        if (subtitle && subtitle.textContent.includes('Showing')) {
            // Extract text parts to preserve formatting if needed, or just replace
            subtitle.textContent = `Showing ${count} product(s)`;
        }
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
