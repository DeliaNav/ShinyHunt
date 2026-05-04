<?php

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../models/Listing.php';

class SalesController {
    private Listing $model;

    public function __construct() {
        $this->model = new Listing();
    }

    public function index() {
        Auth::require();
        $userId = Auth::userId();
        $tab    = in_array($_GET['tab'] ?? '', ['active', 'sold', 'cancelled']) ? $_GET['tab'] : 'active';

        $all      = $this->model->getBySeller($userId);

        $active    = array_filter($all, fn($l) => $l['status'] === 'active');
        $sold      = array_filter($all, fn($l) => $l['status'] === 'sold');
        $cancelled = array_filter($all, fn($l) => $l['status'] === 'cancelled');

        $listings       = match($tab) { 'sold' => $sold, 'cancelled' => $cancelled, default => $active };
        $activeCount    = count($active);
        $soldCount      = count($sold);
        $cancelledCount = count($cancelled);
        $total          = array_sum(array_column(array_values($sold), 'quantity'));
        $totalEarned    = array_sum(array_map(fn($l) => $l['price'] * $l['quantity'], array_values($sold)));

        $pageTitle = 'Mis Ventas · TCGMarket';
        $extraCss  = 'sales.css';
        require_once __DIR__ . '/../views/sales/sales.php';
    }
}
