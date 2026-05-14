/**
 * wishlist-page.js
 * Gestiona la eliminación de cartas en la vista Mi Lista de Deseos sin recargar la página.
 */

(function () {
    let totalCards = window.wishlistData?.total ?? 0;

    function updateCount() {
        const el = document.getElementById('wishlist-count');
        if (el) el.textContent = totalCards + ' carta' + (totalCards !== 1 ? 's' : '') + ' en tu lista de deseos';
    }

    function showEmpty() {
        const grid = document.getElementById('cards-grid');
        if (grid) {
            grid.outerHTML = `
                <div class="collection-empty" id="wishlist-empty">
                    <div class="empty-icon">🗂️</div>
                    <h3>Tu lista de deseos está vacía</h3>
                    <p>Busca cartas y añádelas para llevar un registro de tu lista de deseos.</p>
                    <a href="/TFG/Codigo/home" class="btn-explore">Explorar cartas</a>
                </div>`;
        }
    }

    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.btn-remove');
        if (!btn) return;

        if (!confirm('¿Eliminar esta carta de tu lista de deseos?')) return;

        const cardId = btn.dataset.cardId;
        btn.disabled = true;
        btn.textContent = '…';

        try {
            const response = await fetch('/TFG/Codigo/wishlist/remove', {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    'card_id=' + encodeURIComponent(cardId),
            });

            const data = await response.json();

            if (data.success) {
                const item = document.getElementById('card-item-' + cardId);
                if (item) item.remove();

                totalCards = Math.max(0, totalCards - 1);
                updateCount();

                if (totalCards === 0) showEmpty();
            } else {
                btn.disabled = false;
                btn.textContent = '✕';
                alert(data.message ?? 'Error al eliminar');
            }
        } catch (err) {
            console.error('wishlist-page error:', err);
            btn.disabled = false;
            btn.textContent = '✕';
            alert('Error de red. Inténtalo de nuevo.');
        }
    });
})();