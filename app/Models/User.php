<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Helpers\Headers;
use App\Helpers\Paginator;

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
        'roles_id',
        'email',
        'password',
        'pin_code',
        'is_on_duty',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'pin_code',
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
            'roles_id' => $this->roles_id,
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
     * Связь с моделью Roles.
     *
     * @return $this
     */
    public function roles()
    {
        return $this->belongsTo('App\Models\Roles');
    }

    /**
     * Проверяет проходит ли аутентификацию пользователь с данным паролем.
     *
     * @return array|bool
     */
    public function passAuthenticate(array $data){

        $all = $this::select('id', 'name', 'surname', 'lastname', 'pin_code')->get();
        
        foreach($all as $a){
            if(password_verify($data['pin_code'], $a['pin_code'])){
                $uniqId = uniqid();
                return $a->toArray() + ['uid'=>$uniqId];
            }
        }
        return False;
    }

    /**
     * Получние списка пользователей.
     *
     * @return array
     */
    public static function getList(): array
    {
        $result = array();
        $userList = User::with('roles')->paginate($_ENV['PAGE_NUM']);

        $result['headers'] = Headers::get('user.list');

        foreach($userList as $user){
            $result['body'][] = [
                'id' => $user['id'],
                'iin' => $user['iin'],
                'fio' => $user['name'] . ' ' . $user['surname'],
                'role' => [
                    'key' => $user['roles_id'],
                    'value' => $user['roles']['title']
                ],
                'is_on_duty' => [
                    'key' => $user['is_on_duty'],
                    'value' => trans('base.is_on_duty.'.(string)$user['is_on_duty'])
                ],
            ];
        }
        $result['paginator'] = Paginator::get($userList);;
        return $result;
    }
}
