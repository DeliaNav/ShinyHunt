// public/js/cart.js

document.addEventListener('DOMContentLoaded', () => {

    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.cart-item-remove');
        if (!btn) return;

        const listingId = btn.dataset.listingId;
        btn.disabled = true;

        try {
            const res  = await fetch('/TFG/Codigo/carrito/remove', {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    'listing_id=' + encodeURIComponent(listingId),
            });

            const data = await res.json();

            if (data.success) {
                const item = document.getElementById('cart-item-' + listingId);
                if (item) item.remove();

                const totalEl = document.getElementById('cart-total');
                if (totalEl) totalEl.textContent = data.cartTotal + ' €';

                // Si no quedan items muestra estado vacío
                const grid = document.getElementById('cart-items');
                if (grid && grid.children.length === 0) {
                    location.reload();
                }
            }
        } catch (err) {
            btn.disabled = false;
        }
    });

});
