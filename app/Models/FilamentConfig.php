<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilamentConfig extends Model
{
    protected $guarded = ['id'];

    public static function getFiltersFor(string $resource, string $field): array
    {
        if (!Schema::hasTable('filament_configs')) {
            return []; // leeres Array, wenn Tabelle noch nicht da
        }
        return self::query()
            ->where('type', 'option')
            ->where('field', $field)
            ->where('resource', $resource)
            ->pluck('value', 'key') // ergibt ein Array: ['key1' => 'value1', ...]
            ->toArray();
    }
}
