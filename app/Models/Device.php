<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    /**
     * Имя таблицы в БД.
     *
     * @return $this
     */
    protected $table = 'device';

    /**
     * Relationship по string key не работает без данного свойства.
     *
     * @return $this
     */
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'location'
    ];

    /**
     * Связь с моделью PassLogs.
     *
     * @return $this
     */
    public function pass_logs()
    {
        return $this->hasMany('App\Models\PassLogs');
    }
}

