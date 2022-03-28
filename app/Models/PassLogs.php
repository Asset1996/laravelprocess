<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassLogs extends Model
{
    use HasFactory;
    protected $table = 'pass_logs';

    protected $fillable = [
        'user_id',
        'image_name',
        'image_path',
        'image_size'
    ];

    /**
     * Правила валидаций данных.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'photo' => 'required',
            'user_id' => 'required',
        ];
    }
}
