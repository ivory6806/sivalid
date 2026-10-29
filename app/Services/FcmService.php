<?php

namespace App\Services;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Pengirim notifikasi FCM HTTP v1 tanpa library tambahan.
 * Kredensial: service account JSON (Firebase > Project settings > Service accounts).
 */
class FcmService
{
    private const SCOPES = 'https://www.googleapis.com/auth/firebase.messaging';

    private ?string $accessToken = null;
    private int $accessTokenExpiresAt = 0;

    public function sendToUsers(iterable $userIds, string $title, string $body, array $data = []): void
    {
        try {
            $ids = collect($userIds)->filter()->unique()->values()->all();
            if (empty($ids)) {
                return;
            }

            if (!config('services.fcm.project_id') || !config('services.fcm.credentials')) {
                Log::warning('FCM belum dikonfigurasi: set FCM_PROJECT_ID dan FCM_CREDENTIALS di .env.');
                return;
            }

            $tokens = DeviceToken::whereIn('user_id', $ids)->pluck('token')->unique();

            foreach ($tokens as $token) {
                $this->sendToToken($token, $title, $body, $data);
            }
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim notifikasi FCM: ' . $e->getMessage());
        }
    }

    private function sendToToken(string $token, string $title, string $body, array $data): void
    {
        try {
            $response = Http::withToken($this->accessToken())
                ->acceptJson()
                ->timeout(10)
                ->post($this->endpoint(), [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'data' => array_map('strval', $data),
                        'android' => [
                            'priority' => 'high',
                            'notification' => [
                                'channel_id' => 'sivalid_default',
                                'sound' => 'default',
                            ],
                        ],
                    ],
                ]);

            if ($response->failed()) {
                Log::warning('FCM menolak pengiriman: ' . $response->body());

                // Token sudah tidak valid (app di-uninstall / token kedaluwarsa)
                if ($response->status() === 404
                    || str_contains($response->body(), 'UNREGISTERED')
                    || str_contains($response->body(), 'INVALID_ARGUMENT')) {
                    DeviceToken::where('token', $token)->delete();
                }
            }
        } catch (\Throwable $e) {
            Log::error('FCM gagal kirim ke satu token: ' . $e->getMessage());
        }
    }

    private function endpoint(): string
    {
        return 'https://fcm.googleapis.com/v1/projects/'
            . config('services.fcm.project_id')
            . '/messages:send';
    }

    private function accessToken(): string
    {
        if ($this->accessToken !== null && time() < $this->accessTokenExpiresAt) {
            return $this->accessToken;
        }

        $credentialsPath = config('services.fcm.credentials');
        $credentials = is_readable($credentialsPath)
            ? json_decode(file_get_contents($credentialsPath), true)
            : null;

        if (!is_array($credentials) || empty($credentials['client_email']) || empty($credentials['private_key'])) {
            throw new \RuntimeException('Service account FCM tidak valid atau tidak terbaca: ' . $credentialsPath);
        }

        $now = time();
        $jwt = $this->signJwt([
            'iss' => $credentials['client_email'],
            'scope' => self::SCOPES,
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
        ], $credentials['private_key']);

        $response = Http::asForm()
            ->timeout(10)
            ->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);
        $response->throw();

        $this->accessToken = $response->json('access_token');
        $this->accessTokenExpiresAt = time() + (int) ($response->json('expires_in') ?? 3600) - 60;

        return $this->accessToken;
    }

    private function signJwt(array $payload, string $privateKey): string
    {
        $encode = static fn (string $value): string
            => rtrim(strtr(base64_encode($value), '+/', '-_'), '=');

        $signingInput = $encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']))
            . '.' . $encode(json_encode($payload));

        if (!openssl_sign($signingInput, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new \RuntimeException('Gagal menandatangani JWT FCM.');
        }

        return $signingInput . '.' . $encode($signature);
    }
}
