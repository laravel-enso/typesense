<?php

namespace LaravelEnso\Typesense\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Settings extends Model
{
    use HasFactory;

    protected $table = 'typesense_settings';

    protected $guarded = ['id'];

    protected array $rememberableKeys = ['id'];

    public static function current()
    {
        return self::find(Config::get('enso.typesense.settingsId'))
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
