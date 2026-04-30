/**
 * profile.js
 * Sube el avatar automáticamente al seleccionar un archivo.
 */
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('avatar-input');
    if (!input) return;

    input.addEventListener('change', () => {
        if (!input.files.length) return;

        const form = new FormData();
        form.append('avatar', input.files[0]);

        fetch('/TFG/Codigo/perfil/avatar', {
            method: 'POST',
            body:   form,
        })
        .then(r => r.text())
        .then(() => {
            // Recarga para mostrar el nuevo avatar
            window.location.reload();
        })
        .catch(() => {
            alert('Error al subir el avatar. Inténtalo de nuevo.');
        });
    });
});