<?php

class TCGApi {
    private const BASE_URL = 'https://api.tcgdex.net/v2/en';

    public static function getCards(int $limit = 20, int $page = 1): array {
        $offset = ($page - 1) * $limit;
        $url = self::BASE_URL . "/cards?limit={$limit}&offset={$offset}";
        return self::fetch($url) ?? [];
    }

    public static function getCard(string $id): ?array {
        return self::fetch(self::BASE_URL . "/cards/{$id}");
    }

    public static function searchCards(string $query, int $limit = 40): array {
        $encoded = urlencode($query);
        $url = self::BASE_URL . "/cards?name={$encoded}&limit={$limit}";
        return self::fetch($url) ?? [];
    }

    public static function getRandomCards(int $count = 20): array {

    $url = self::BASE_URL . "/cards?limit=250&offset=" . rand(0, 500);
        $cards = self::fetch($url) ?? [];
        shuffle($cards);
        return array_slice($cards, 0, $count);
    }

    public static function getCardImageUrl(string $cardId, string $quality = 'high', string $ext = 'webp'): string {
        return "https://assets.tcgdex.net/en/{$cardId}/{$quality}.{$ext}";
    }

    private static function fetch(string $url): mixed {
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 10,
                'header'  => "Accept: application/json\r\n"
            ]
        ]);
        $response = @file_get_contents($url, false, $ctx);
        if ($response === false) return null;
        return json_decode($response, true);
    }
}
