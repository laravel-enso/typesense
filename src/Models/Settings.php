<?php

namespace LaravelEnso\Typesense\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Rememberable\Traits\Rememberable;

class Settings extends Model
{
    use HasFactory, Rememberable;

    protected $table = 'typesense_settings';

    protected $guarded = ['id'];

    protected array $rememberableKeys = ['id'];

    public static function current()
    {
        return self::cacheGet(1)
            ?? self::factory()->create();
    }

    public static function enabled()
    {
        return self::current()->enabled;
    }

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }
}
