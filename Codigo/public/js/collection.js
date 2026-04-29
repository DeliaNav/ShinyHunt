document.addEventListener('DOMContentLoaded', () => {

    const btn = document.getElementById('btn-collection');
    if (!btn) return;

    // datos inyectados desde PHP en show.php
    const { cardId, cardName, imageUrl, inCollection: initialState } = window.cardData ?? {};

    if (!cardId) {
        console.warn('collection: window.cardData no está definido.');
        return;
    }

    let inCollection = initialState;

    function renderBtn(inCol, loading = false) {
        btn.disabled = loading;

        if (loading) {
            btn.textContent = '⏳ Procesando…';
            btn.classList.remove('btn-collection--active');
            return;
        }

        if (inCol) {
            btn.textContent = '✓ En tu Colección';
            btn.classList.add('btn-collection--active');
        } else {
            btn.textContent = '+ Añadir a Mi Colección';
            btn.classList.remove('btn-collection--active');
        }
    }

    function showToast(message, isError = false) {
        let toast = document.getElementById('collection-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'collection-toast';
            toast.style.cssText = `
                position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
                padding: .65rem 1.4rem; border-radius: 8px; font-size: .9rem;
                color: #fff; z-index: 9999; opacity: 0;
                transition: opacity .25s ease; pointer-events: none;
            `;
            document.body.appendChild(toast);
        }

        toast.textContent   = message;
        toast.style.background = isError ? '#e53935' : '#2e7d32';
        toast.style.opacity = '1';

        clearTimeout(toast._hideTimer);
        toast._hideTimer = setTimeout(() => { toast.style.opacity = '0'; }, 2800);
    }

    renderBtn(inCollection);
    btn.disabled = false;   // el botón llega con disabled desde el HTML

    btn.addEventListener('click', async () => {

        const endpoint = inCollection
            ? '/TFG/Codigo/coleccion/remove'
            : '/TFG/Codigo/coleccion/add';

        const body = new URLSearchParams({ card_id: cardId });

        // Solo add necesita nombre e imagen
        if (!inCollection) {
            body.append('card_name', cardName);
            body.append('image_url', imageUrl);
        }

        renderBtn(inCollection, true);   // estado de carga

        try {
            const response = await fetch(endpoint, {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    body.toString(),
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                inCollection = data.inCollection;
                renderBtn(inCollection);
                showToast(data.message ?? (inCollection ? 'Carta añadida' : 'Carta eliminada'));
            } else {
                renderBtn(inCollection);   // revertir al estado anterior
                showToast(data.message ?? 'Algo salió mal', true);
            }

        } catch (err) {
            console.error('collection error:', err);
            renderBtn(inCollection);
            showToast('Error de red. Inténtalo de nuevo.', true);
        }
    });
});