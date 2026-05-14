// public/js/collection-page

(function () {
    let totalCards = window.collectionData?.total ?? 0;

    function updateCount() {
        const el = document.getElementById('collection-count');
        if (el) el.textContent = totalCards + ' carta' + (totalCards !== 1 ? 's' : '') + ' en tu colección';
    }

    function showEmpty() {
        const grid = document.getElementById('cards-grid');
        if (grid) {
            grid.outerHTML = `
                <div class="collection-empty" id="collection-empty">
                    <div class="empty-icon">🗂️</div>
                    <h3>Tu colección está vacía</h3>
                    <p>Busca cartas y añádelas para llevar un registro de tu colección física.</p>
                    <a href="/TFG/Codigo/home" class="btn-explore">Explorar cartas</a>
                </div>`;
        }
    }

    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.btn-remove');
        if (!btn) return;

        if (!confirm('¿Eliminar esta carta de tu colección?')) return;

        const cardId = btn.dataset.cardId;
        btn.disabled = true;
        btn.textContent = '…';

        try {
            const response = await fetch('/TFG/Codigo/coleccion/remove', {
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
            console.error('collection-page error:', err);
            btn.disabled = false;
            btn.textContent = '✕';
            alert('Error de red. Inténtalo de nuevo.');
        }
    });
})();