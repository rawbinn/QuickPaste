<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Enums\ExpiryType;
use Illuminate\Database\Eloquent\Model;

class Paste extends Model
{
    protected $fillable = [
        'code',
        'content',
        'password',
        'expiry_type',
        'expires_at',
        'views',
        'language',
    ];

    protected $casts = [
        'expiry_type' => ExpiryType::class,
    ];

    public function getRouteKeyName(): string
    {
        return 'code';
    }
}
