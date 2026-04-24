<?php
// debug_cards.php

require_once 'lib/TCGApi.php';

echo '<h2>Test getRandomCards</h2>';
$cards = TCGApi::getRandomCards(5);
echo 'Cartas recibidas: ' . count($cards) . '<br>';
echo '<pre>' . print_r($cards, true) . '</pre>';

echo '<h2>Test searchCards</h2>';
$results = TCGApi::searchCards('Pikachu');
echo 'Resultados: ' . count($results) . '<br>';
echo '<pre>' . print_r($results, true) . '</pre>';

echo '<h2>Test imagen</h2>';
if (!empty($cards)) {
    $card = $cards[0];
    echo 'image field: ' . $card['image'] . '<br>';
    echo 'URL generada: ' . TCGApi::getImageUrl($card) . '<br>';
    echo '<img src="' . TCGApi::getImageUrl($card) . '" style="width:200px">';
}