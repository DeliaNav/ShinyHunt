<?php

class TCGApi {
    private const BASE_URL = 'https://api.tcgdex.net/v2/en';

    public static function getSets(): array {
        return self::fetch(self::BASE_URL . '/sets') ?? [];
    }

    public static function getSet(string $setId): ?array {
        return self::fetch(self::BASE_URL . "/sets/{$setId}");
    }

    public static function getCard(string $id): ?array {
        return self::fetch(self::BASE_URL . "/cards/{$id}");
    }

    public static function searchCards(string $query, int $limit = 40): array {
        $query   = strtolower(trim($query));
        $sets    = self::getSets();
        $results = [];

        foreach ($sets as $set) {
            $setData = self::getSet($set['id']);
            if (empty($setData['cards'])) continue;

            foreach ($setData['cards'] as $card) {
                // Solo cartas con imagen
                if (empty($card['image'])) continue;

                if (str_contains(strtolower($card['name']), $query)) {
                    $results[] = $card;
                }

                if (count($results) >= $limit) break 2;
            }
        }

        return $results;
    }

    /**
     * Devuelve N cartas aleatorias que tengan imagen.
     */
    public static function getRandomCards(int $count = 20): array {
        $sets = self::getSets();
        if (empty($sets)) return [];

        $cards    = [];
        $attempts = 0;

        while (count($cards) < $count && $attempts < 15) {
            $attempts++;
            $randomSet = $sets[array_rand($sets)];
            $setData   = self::getSet($randomSet['id']);

            if (!empty($setData['cards'])) {
                foreach ($setData['cards'] as $card) {
                    // Solo añadir cartas que tengan imagen
                    if (!empty($card['image'])) {
                        $cards[$card['id']] = $card;
                    }
                }
            }
        }

        $cards = array_values($cards);
        shuffle($cards);
        return array_slice($cards, 0, $count);
    }

    public static function getImageUrl(array $card, string $quality = 'low', string $ext = 'png'): string {
        if (!empty($card['image'])) {
            return $card['image'] . "/{$quality}.{$ext}";
        }
        return '/TFG/Codigo/public/img/card-placeholder.png';
    }

    private static function fetch(string $url): mixed {
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 15,
                'header'  => "Accept: application/json\r\n",
                'method'  => 'GET',
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ]
        ]);

        $response = @file_get_contents($url, false, $ctx);
        if ($response === false) return null;

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) return null;

        return $data;
    }
}