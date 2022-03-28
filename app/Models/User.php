<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Правила валидаций данных.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required',
        ];
    }


    /**
     * Связь с моделью PassLogs.
     *
     * @return $this
     */
    public function passLog()
    {
        return $this->hasMany('App\Models\PassLogs');
    }

    /**
     * Проверяет проходит ли аутентификацию пользователь с данным паролем.
     *
     * @return array|bool
     */
    public function passAuthenticate(array $data){

        $all = $this::select('id', 'name', 'surname', 'lastname', 'password')->get();
        
        foreach($all as $a){
            if(password_verify($data['password'], $a['password'])){
                $uniqId = uniqid();
                return $a->toArray() + ['uid'=>$uniqId];
            }
        }
        return False;
    }
}
