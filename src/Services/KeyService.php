<?php

namespace BrosSquad\Linker\Api\Services;

use BrosSquad\Linker\Api\Models\Key;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use RuntimeException;

class KeyService
{
    /**
     * @return LengthAwarePaginator<Key>
     */
    public function get(int $page = 1, int $perPage = 15): LengthAwarePaginator
    {
        return Key::query()
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page)
        ;
    }

    /**
     * @throws \Throwable
     */
    public function create(string $name): array
    {
        $apiKey = 'BrosSquadLinker.%s.%s';

        $key = new Key([
            'name' => $name,
        ]);

        $appKey = sodium_base642bin($_ENV['APP_KEY'], SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING);

        $key->saveOrFail();
        $nonce = sodium_bin2base64(
            random_bytes(SODIUM_CRYPTO_GENERICHASH_BYTES_MAX),
            SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING
        );
        $apiKey = sprintf($apiKey, $key->id, $nonce);
        $signature = sodium_bin2base64(sodium_crypto_auth($apiKey, $appKey), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING);
        $apiKey .= '.'.$signature;
        $key->key = sodium_bin2base64(
            sodium_crypto_generichash($apiKey, '', SODIUM_CRYPTO_GENERICHASH_BYTES_MAX),
            SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING
        );
        $key->saveOrFail();

        return [
            'apiKey' => $apiKey,
            'key' => $key,
        ];
    }

    public function verify(string $key): bool
    {
        [$service, $id, $nonce, $signature] = explode('.', $key);

        $appKey = sodium_base642bin($_ENV['APP_KEY'], SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING);

        if (!sodium_crypto_auth_verify(
            sodium_base642bin($signature, SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING),
            sprintf('%s.%s.%s', $service, $id, $nonce),
            $appKey
        )) {
            throw new RuntimeException('API Key is not valid');
        }

        $keyDb = Key::query()->findOrFail($id);

        return 0 ===
               sodium_compare(
                   $keyDb->key,
                   sodium_bin2base64(
                       sodium_crypto_generichash($key, '', SODIUM_CRYPTO_GENERICHASH_BYTES_MAX),
                       SODIUM_BASE64_VARIANT_ORIGINAL_NO_PADDING
                   )
               );
    }

    /**
     * @param string | int $idOrName
     *
     * @throws \Exception
     */
    public function delete($idOrName): bool
    {
        if (0 !== ($id = intval($idOrName))) {
            $key = Key::query()->findOrFail($id);
        } else {
            $key = Key::query()
                ->where('name', '=', $idOrName)
                ->firstOrFail()
            ;
        }

        return $key->delete();
    }
}
