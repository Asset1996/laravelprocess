<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    // use HasApiTokens, HasFactory;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'iin',
        'name',
        'surname',
        'lastname',
        'email',
        'password',
        'pass_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'pass_password',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id,
            'iin' => $this->iin,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
        ];
    }

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

        $all = $this::select('id', 'name', 'surname', 'lastname', 'pass_password')->get();
        
        foreach($all as $a){
            if(password_verify($data['pass_password'], $a['pass_password'])){
                $uniqId = uniqid();
                return $a->toArray() + ['uid'=>$uniqId];
            }
        }
        return False;
    }
}
