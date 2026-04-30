/**
 * wishlist.js
 * Gestiona el botón "Añadir / Quitar de Lista de Deseos" en la vista de detalle de carta.
 */

document.addEventListener('DOMContentLoaded', () => {

    const btn = document.getElementById('btn-wishlist');
    if (!btn) return;

    const { cardId, cardName, imageUrl, inWishlist: initialState } = window.cardData ?? {};

    if (!cardId) {
        console.warn('wishlist: window.cardData no está definido.');
        return;
    }

    let inWishlist = initialState;

    function renderBtn(inWish, loading = false) {
        btn.disabled = loading;

        if (loading) {
            btn.textContent = '⏳ Procesando…';
            btn.classList.remove('btn-wishlist--active');
            return;
        }

        if (inWish) {
            btn.textContent = '✓ En tu Lista de Deseos';
            btn.classList.add('btn-wishlist--active');
        } else {
            btn.textContent = '☆ Añadir a Lista de Deseos';
            btn.classList.remove('btn-wishlist--active');
        }
    }

    function showToast(message, isError = false) {
        let toast = document.getElementById('wishlist-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'wishlist-toast';
            toast.style.cssText = `
                position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
                padding: .65rem 1.4rem; border-radius: 8px; font-size: .9rem;
                color: #fff; z-index: 9999; opacity: 0;
                transition: opacity .25s ease; pointer-events: none;
            `;
            document.body.appendChild(toast);
        }

        toast.textContent      = message;
        toast.style.background = isError ? '#e53935' : '#2e7d32';
        toast.style.opacity    = '1';

        clearTimeout(toast._hideTimer);
        toast._hideTimer = setTimeout(() => { toast.style.opacity = '0'; }, 2800);
    }

    renderBtn(inWishlist);
    btn.disabled = false;

    btn.addEventListener('click', async () => {

        const endpoint = inWishlist
            ? '/TFG/Codigo/wishlist/remove'
            : '/TFG/Codigo/wishlist/add';

        const body = new URLSearchParams({ card_id: cardId });

        if (!inWishlist) {
            body.append('card_name', cardName);
            body.append('image_url', imageUrl);
        }

        renderBtn(inWishlist, true);

        try {
            const response = await fetch(endpoint, {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    body.toString(),
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();

            if (data.success) {
                inWishlist = data.inWishlist;
                renderBtn(inWishlist);
                showToast(data.message ?? (inWishlist ? 'Carta añadida' : 'Carta eliminada'));
            } else {
                renderBtn(inWishlist);
                showToast(data.message ?? 'Algo salió mal', true);
            }

        } catch (err) {
            console.error('wishlist error:', err);
            renderBtn(inWishlist);
            showToast('Error de red. Inténtalo de nuevo.', true);
        }
    });
});