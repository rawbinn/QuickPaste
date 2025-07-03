<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Enums\ExpiryType;
use App\Models\Paste;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PasteService
{
    public function store(array $data): Paste
    {
        $expiryEnum = ExpiryType::from((int) $data['expiry']);
        $code = $this->generateUniqueCode();
        $expiresAt = $this->calculateExpiry($expiryEnum);
        $paste = Paste::create([
            'code' => $code,
            'content' => $data['content'],
            'password' => $data['password'] ? Hash::make($data['password']) : null,
            'expiry_type' => $expiryEnum,
            'expires_at' => $expiresAt,
            'language' => $data['language'],
        ]);

        return $paste;
    }

    public function handleView(Paste $paste)
    {
        // Time-based expiry
        if ($paste->expires_at && now()->greaterThan($paste->expires_at)) {
            $paste->delete();
            throw new Exception('This paste has expired.');
        }

        // One-time view expiry
        if (ExpiryType::AFTER_VIEW === $paste->expiry_type) {
            $paste->delete();
        }

        $paste->increment('views');

        return [
            'qrCode' => $this->generateQRCode($paste->code),
        ];
    }

    private function generateUniqueCode($length = 6): string
    {
        do {
            $code = Str::random($length);
        } while (Paste::where('code', $code)->exists());

        return $code;
    }

    private function calculateExpiry(ExpiryType $expiry): ?DateTime
    {
        $interval = $expiry->toInterval();

        return $interval ? now()->add($interval) : null;
    }

    private function generateQRCode($code)
    {
        $url = url("/{$code}");

        return QrCode::size(200)->generate($url);
    }
}
