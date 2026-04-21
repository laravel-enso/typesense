<?php

namespace LaravelEnso\Typesense\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use LaravelEnso\Rememberable\Traits\Rememberable;
use LaravelEnso\Typesense\Database\Factories\SettingsFactory;

class Settings extends Model
{
    use HasFactory;

    protected $table = 'typesense_settings';

    protected $guarded = ['id'];

    protected array $rememberableKeys = ['id'];

    public static function current()
    {
        $id = Config::get('enso.typesense.settingsId');

        return self::find($id)
            ?? self::unguarded(fn () => self::create(
                ['id' => $id] + self::factory()->make()->getAttributes()
            ));
    }

    public static function enabled()
    {
        return self::current()->enabled;
    }

    protected static function newFactory(): SettingsFactory
    {
        return SettingsFactory::new();
    }

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }
}
