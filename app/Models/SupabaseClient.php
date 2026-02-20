<?php
declare(strict_types=1);

namespace App\Models;

use GuzzleHttp\Client;

final class SupabaseClient
{
    private Client $http;

    public function __construct(
        private string $url,
        private string $key,
    ) {
        $this->url = rtrim($this->url, '/');
        if ($this->url === '' || $this->key === '') {
            throw new \RuntimeException('Faltan SUPABASE_URL o SUPABASE_ANON_KEY');
        }

        $this->http = new Client([
            'base_uri' => $this->url . '/rest/v1/',
            'timeout'  => 15,
        ]);
    }

    private function headers(array $extra = []): array
    {
        return array_merge([
            'apikey'        => $this->key,
            'Authorization' => 'Bearer ' . $this->key,
            'Accept'        => 'application/json',
        ], $extra);
    }

    /** @return array<int, array<string,mixed>> */
    public function get(string $table, array $query = []): array
    {
        $resp = $this->http->request('GET', ltrim($table, '/'), [
            'headers' => $this->headers(),
            'query'   => $query,
        ]);

        $decoded = json_decode((string)$resp->getBody(), true);
        return is_array($decoded) ? $decoded : [];
    }

    /** @return array<int, array<string,mixed>> */
    public function insert(string $table, array $payload): array
    {
        $resp = $this->http->request('POST', ltrim($table, '/'), [
            'headers' => $this->headers([
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation',
            ]),
            'body' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);

        $decoded = json_decode((string)$resp->getBody(), true);
        return is_array($decoded) ? $decoded : [];
    }

    public function update(string $table, array $query, array $payload): void
    {
        $resp = $this->http->request('PATCH', ltrim($table, '/'), [
            'headers' => $this->headers([
                'Content-Type' => 'application/json',
                'Prefer' => 'return=minimal',
            ]),
            'query' => $query,
            'body' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);

        (string)$resp->getBody(); // consume
    }

    public function delete(string $table, array $query): void
    {
        $resp = $this->http->request('DELETE', ltrim($table, '/'), [
            'headers' => $this->headers([
                'Prefer' => 'return=minimal',
            ]),
            'query' => $query,
        ]);

        (string)$resp->getBody();
    }
}