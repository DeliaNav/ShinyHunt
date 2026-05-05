// public/js/card-detail.js

document.addEventListener('DOMContentLoaded', () => {

    // Botones añadir al carrito 
    document.querySelectorAll('.btn-add-cart').forEach(btn => {
        btn.addEventListener('click', async () => {
            const listingId = btn.dataset.listingId;
            const stock = parseInt(btn.dataset.stock, 10);

            btn.disabled = true;

            try {
                const res  = await fetch('/TFG/Codigo/carrito/add', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body:    'listing_id=' + encodeURIComponent(listingId),
                });

                const data = await res.json();

                if (data.success) {
                    // Actualiza badge de cantidad en el carrito
                    const badge = document.getElementById('cart-qty-' + listingId);
                    if (badge) {
                        badge.textContent = 'x' + data.quantity;
                        badge.style.display = 'inline';
                    }

                    // Deshabilita el botón si se agotó el stock
                    if (data.quantity >= data.stock) {
                        btn.textContent = 'Sin stock';
                        btn.disabled = true;
                    } else {
                        btn.disabled = false;
                    }

                    showToast(data.message);
                } else {
                    btn.disabled = false;
                    showToast(data.message, true);
                }

            } catch (err) {
                btn.disabled = false;
                showToast('Error de red', true);
            }
        });
    });

    //  Toast 
    function showToast(msg, error = false) {
        let toast = document.getElementById('global-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'global-toast';
            toast.style.cssText = `
                position:fixed;bottom:1.5rem;left:50%;transform:translateX(-50%);
                padding:.65rem 1.4rem;border-radius:8px;font-size:.9rem;
                color:#fff;z-index:9999;opacity:0;
                transition:opacity .25s ease;pointer-events:none;
            `;
            document.body.appendChild(toast);
        }
        toast.textContent      = msg;
        toast.style.background = error ? '#e53935' : '#2e7d32';
        toast.style.opacity    = '1';
        clearTimeout(toast._t);
        toast._t = setTimeout(() => { toast.style.opacity = '0'; }, 2800);
    }

});