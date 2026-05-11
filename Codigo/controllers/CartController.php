<?php

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Listing.php';

class CartController {
    private Cart    $cart;
    private Listing $listing;

    public function __construct() {
        $this->cart    = new Cart();
        $this->listing = new Listing();
    }

    // GET /carrito — vista del carrito
    public function index() {
        Auth::require();
        $userId = Auth::userId();
        $items  = $this->cart->getByUser($userId);
        $total  = $this->cart->total($userId);

        $pageTitle = 'Mi Carrito · ShinnyHunt';
        $extraCss  = 'cart.css';
        require_once __DIR__ . '/../views/carts/cart_index.php';
    }

    // POST /carrito/add — añade 1 unidad al carrito (llamado por fetch)
    public function add() {
        Auth::require();
        header('Content-Type: application/json');
        $userId    = Auth::userId();
        $listingId = (int)($_POST['listing_id'] ?? 0);

        if (!$listingId) {
            echo json_encode(['success' => false, 'message' => 'Listing no válido']);
            exit();
        }

        $listing = $this->listing->getById($listingId);
        if (!$listing || $listing['status'] !== 'active' || $listing['quantity'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'Esta carta ya no está disponible']);
            exit();
        }

        if ($listing['seller_id'] == $userId) {
            echo json_encode(['success' => false, 'message' => 'No puedes comprar tus propias cartas']);
            exit();
        }

        $item       = $this->cart->getItem($userId, $listingId);
        $currentQty = $item ? $item['quantity'] : 0;
        if ($currentQty >= $listing['quantity']) {
            echo json_encode(['success' => false, 'message' => 'No hay más stock disponible']);
            exit();
        }

        $ok     = $this->cart->add($userId, $listingId, 1);
        $newQty = $currentQty + ($ok ? 1 : 0);

        echo json_encode([
            'success'   => $ok,
            'quantity'  => $newQty,
            'stock'     => $listing['quantity'],
            'message'   => $ok ? 'Añadido al carrito' : 'Error al añadir',
            'cartCount' => $this->cart->count($userId),
        ]);
        exit();
    }

    // POST /carrito/remove — elimina un item del carrito
    public function remove() {
        Auth::require();
        header('Content-Type: application/json');
        $userId    = Auth::userId();
        $listingId = (int)($_POST['listing_id'] ?? 0);

        $ok = $this->cart->remove($userId, $listingId);
        echo json_encode([
            'success'   => $ok,
            'cartTotal' => number_format($this->cart->total($userId), 2),
            'cartCount' => $this->cart->count($userId),
        ]);
        exit();
    }

    // GET /carrito/checkout — pasarela de pago
    public function checkout() {
        Auth::require();
        $userId = Auth::userId();
        $items  = $this->cart->getByUser($userId);

        if (empty($items)) {
            header("Location: /TFG/Codigo/carrito");
            exit();
        }

        $total     = $this->cart->total($userId);
        $pageTitle = 'Pagar · ShinnyHunt';
        $extraCss  = 'cart.css';

        require_once __DIR__ . '/../lib/stripe.php';
        require_once __DIR__ . '/../vendor/autoload.php';
        require_once __DIR__ . '/../views/carts/checkout.php';
    }

    //  POST /carrito/pagar — crea PaymentIntent (fetch desde checkout)
    public function pagar() {
        Auth::require();
        header('Content-Type: application/json');

        $userId = Auth::userId();
        $items  = $this->cart->getByUser($userId);

        if (empty($items)) {
            http_response_code(400);
            echo json_encode(['error' => 'Carrito vacío']);
            exit();
        }

        $totalCentimos = (int) round($this->cart->total($userId) * 100);

        if ($totalCentimos <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Importe inválido']);
            exit();
        }

        require_once __DIR__ . '/../lib/stripe.php';
        require_once __DIR__ . '/../vendor/autoload.php';

        try {
            $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);

            $intent = $stripe->paymentIntents->create([
                'amount'                    => $totalCentimos,
                'currency'                  => 'eur',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata'                  => ['user_id' => $userId],
            ]);

            $_SESSION['stripe_intent'] = $intent->id;

            echo json_encode(['clientSecret' => $intent->client_secret]);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }

        exit();
    }

    // GET /carrito/resultado — Stripe redirige aquí tras el pago
    public function resultado() {
        Auth::require();

        $userId          = Auth::userId();
        $paymentIntentId = $_GET['payment_intent'] ?? '';

        if (!$paymentIntentId) {
            header('Location: /TFG/Codigo/carrito');
            exit();
        }

        if (($_SESSION['stripe_intent'] ?? '') !== $paymentIntentId) {
            header('Location: /TFG/Codigo/carrito');
            exit();
        }

        require_once __DIR__ . '/../lib/stripe.php';
        require_once __DIR__ . '/../vendor/autoload.php';

        try {
            $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
            $intent = $stripe->paymentIntents->retrieve($paymentIntentId);
            $status = $intent->status;
        } catch (\Exception $e) {
            $status = 'error';
        }

        if ($status === 'succeeded') {
            // se quita del stock (si hay mas de 1 se resta y ya)
            $items = $this->cart->getByUser($userId);
            foreach ($items as $item) {
                $this->listing->decreaseQuantity(
                    (int) $item['listing_id'],
                    (int) $item['quantity']
                );
            }

            $this->cart->clear($userId);
            unset($_SESSION['stripe_intent']);
        }

        $pageTitle = 'Resultado del pago · ShinnyHunt';
        $extraCss  = 'cart.css';
        require_once __DIR__ . '/../views/carts/checkout_resultado.php';
    }
}