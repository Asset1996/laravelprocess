<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Helpers\Headers;
use App\Helpers\Paginator;
use App\Helpers\Handbook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        'positions_id',
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
            'password' => 'required|min:6',
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
     * Связь с моделью Positions.
     *
     * @return $this
     */
    public function positions()
    {
        return $this->belongsTo('App\Models\Positions');
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
     * Получение списка пользователей.
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
        $result['paginator'] = Paginator::get($userList);
        return $result;
    }

    /**
     * Получение полей заполнения для создания нового пользователя.
     *
     * @return array
     */
    public static function getCreateData(): array
    {
        $result = array();
        $fields = Headers::get('user.createData');

        // print_r($fields);exit();

        $fields = Handbook::getHandbooks((array) $fields);

        $result['fields'] = $fields;
        return $result;
    }

    /**
     * Создает новый пользователь в БД.
     *
     * @return array
     */
    public static function createUser(array $data){
        $data['pin_code'] = Hash::make($data['pin_code']);
        $data['password'] = Hash::make($data['password']);
        self::create($data);
        return True;
    }

    /**
     * Получние ФИО и ID пользователей.
     *
     * @return array
     */
    public static function getFioAndIdList(): array
    {
        $result = array();
        $userList = User::select('id', 'name', 'surname')->get();

        foreach($userList as $user){
            $result[] = [
                'id' => $user['id'],
                'fio' => $user['name'] . ' ' . $user['surname'],
            ];
        }
        return $result;
    }

    /**
     * Получние данных о том, находится ли сотрудник на работе.
     *
     * @param int user_id
     * @return int
     */
    public static function getIsOnDuty(int $user_id)
    {
        return self::select('is_on_duty')->where('id', $user_id)->first()->toArray()['is_on_duty'];
    }

    /**
     * Update is_on_duty.
     *
     * @param int user_id
     * @return bool
     */
    public static function updateIsOnDuty(int $user_id)
    {
        return self::where('id', $user_id)->update(['is_on_duty'=>DB::raw(
            'CASE 
                WHEN `is_on_duty`=1 THEN 0
                WHEN `is_on_duty`=0 THEN 1 
            END'
        )]);
    }
}
