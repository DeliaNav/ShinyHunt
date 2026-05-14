// public/js/checkout.js

const stripe = Stripe(stripePublishableKey); // viene de checkout.php
let elements;

const stripeAppearance = {
    theme: 'stripe',
    variables: {
        colorPrimary:    '#9A0002',
        colorBackground: '#ffffff',
        colorText:       '#1a0a0a',
        colorDanger:     '#9A0002',
        borderRadius:    '8px',
    },
    rules: {
        '.Input': {
            border:          '1.5px solid #d9ccc4',
            backgroundColor: '#f7f2ee',
        },
        '.Input:focus': {
            border:    '1.5px solid #9A0002',
            boxShadow: '0 0 0 3px rgba(154,0,2,.1)',
        },
        '.Label': { color: '#5c3a3a' },
    },
};

fetch('/TFG/Codigo/carrito/pagar', { method: 'POST' })
    .then(r => r.json())
    .then(data => {
        if (data.error) { mostrarError(data.error); return; }

        elements = stripe.elements({
            clientSecret: data.clientSecret,
            appearance:   stripeAppearance,
        });

        elements.create('payment').mount('#payment-element');
        document.getElementById('btn-pagar').disabled = false;
    })
    .catch(() => mostrarError('No se pudo conectar con el servidor de pagos.'));

document.getElementById('btn-pagar').addEventListener('click', async () => {
    setLoading(true);
    mostrarError('');

    const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: stripeReturnUrl, // viene de checkout.php
        },
    });

    if (error) {
        mostrarError(error.message ?? 'Error al procesar el pago.');
        setLoading(false);
    }
});

function mostrarError(msg) {
    const el = document.getElementById('payment-message');
    el.textContent   = msg;
    el.style.display = msg ? 'block' : 'none';
}

function setLoading(on) {
    document.getElementById('btn-pagar').disabled         = on;
    document.getElementById('btn-texto').style.display    = on ? 'none'   : '';
    document.getElementById('btn-cargando').style.display = on ? 'inline' : 'none';
}