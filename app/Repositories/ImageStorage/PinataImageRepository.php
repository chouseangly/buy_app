<?php

namespace App\Repositories\ImageStorage;

use Illuminate\Support\Facades\Http;

class PinataImageRepository implements ImageStorageInterface {
    public function store($image): array {
        $response = Http::withHeaders([
            'pinata_api_key' => config('services.pinata.key'),
            'pinata_secret_api_key' => config('services.pinata.secret'),
        ])
        ->attach('file', file_get_contents($image->getRealPath()), $image->getClientOriginalName())
        ->post('https://api.pinata.cloud/pinning/pinFileToIPFS');

        $cid = $response->json('IpfsHash');
        return [
            'cid' => $cid,
            'url' => config('services.pinata.gateway') . $cid
        ];
    }
}
