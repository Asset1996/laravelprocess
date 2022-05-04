<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Headers;
use App\Helpers\Paginator;
use App\Models\Device;
use Illuminate\Support\Facades\DB;

class PassLogs extends Model
{
    use HasFactory;
    protected $table = 'pass_logs';

    protected $fillable = [
        'user_id',
        'direction',
        'device_id',
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
            'user_id' => 'required',
        ];
    }

    protected $casts = [
        'updated_at' => 'datetime:d/M/Y H:i',
        'created_at' => 'datetime:d/M/Y H:i',
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Связь с моделью User.
     *
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Связь с моделью Device.
     *
     * @return $this
     */
    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    /**
     * Получние списка логов.
     *
     * @return array
     */
    // public static function getList(): array
        // {
        //     $result = array();
            
        //     $logs = self::with('user.roles', 'device')
        //         ->user_id(request()->user_id)
        //         ->created_at(request()->created_at)
        //         ->orderBy('created_at', 'desc')
        //         ->paginate($_ENV['PAGE_NUM']);

        //     $result['headers'] = Headers::get('passLogs.list');

        //     foreach($logs as $log){
        //         $result['body'][] = [
        //             'id' => $log['id'],
        //             'user_id' => $log['user_id'],
        //             'device_id' => $log['device_id'],
        //             'image_path' => $log['image_path'],
        //             'direction' => [
        //                 'key' => $log['direction'],
        //                 'value' => trans('base.direction.'.(string)$log['direction'])
        //             ],
        //             'device_id' => isset($log['device']) ? [
        //                 'key' => $log['device']['id'],
        //                 'value' => $log['device']['location'],
        //             ] : '',
        //             'iin' => $log['user']['iin'],
        //             'fio' => $log['user']['name'] . ' ' . $log['user']['surname'],
        //             'roles_id' => $log['user']['roles_id'],
        //             'role' => $log['user']['roles']['title'],
        //             'created_at' => $log['created_at']->format('d/M/Y H:i'),
        //         ];
        //     }
        //     $result['paginator'] = Paginator::get($logs);
            
        //     return $result;
    // }

    public function scopeLocation($query, $location)
    {
        if (!is_null($location)) {
            return $query->whereHas('device', function($q) use($location){
                $q->where('location', $location);
            });
        }

        return $query;
    }

    public function scopeFio($query, $fio)
    {
        if (!is_null($fio)) {
            return $query->whereHas('user', function($q) use($fio){
                $q->where('name', 'like', '%'.$fio.'%')
                    ->orWhere('surname', 'like', '%'.$fio.'%')
                    ->orWhere('lastname', 'like', '%'.$fio.'%');
            });
        }

        return $query;
    }

    public static function getList(): array
    {
        $result = array();
        
        $created_at = request()->input('created_at') 
            ? request()->input('created_at')
            : \Carbon\Carbon::today()->format('Y-m-d');

        $logs = self::with(['user.roles'])
            ->with(['device'])
            ->user_id(request()->user_id)
            ->location(request()->location)
            ->fio(request()->fio)
            ->where(DB::raw('DATE(created_at)'), $created_at)
            ->orderBy('created_at', 'asc')
            ->get();
        
        $logs_grouped = $logs->groupBy('user_id');

        foreach($logs_grouped as $key=>$lg){
            $couple = [];
            foreach($lg as $keyIn=>$item){
                if($item->direction == 1){
                    $couple[$keyIn][1] = $item;
                }else{
                    $couple[$keyIn-1][0] = $item;
                }
            }
            $result['body'][$key]['couples'] = array_values($couple);
            $result['body'][$key]['user'] = [
                'id' => $lg[0]['user']['id'],
                'fio' => $lg[0]['user']['name'] . ' '. $lg[0]['user']['surname']
            ];
        }
        $result['body'] = array_values($result['body']);
        return $result;
    }

    /**
     * Создание лога.
     *
     * @return array
     */
    public static function createLog(int $user_id, int $is_on_duty, $uploadedFile){
        
        return self::create([
            'user_id' => $user_id,
            'direction' => !$is_on_duty,
            'device_id' => request()->input('device_id'),
            'image_name' => $uploadedFile['fileName'],
            'image_path' => $uploadedFile['filePath'],
            'image_size' => $uploadedFile['fileSize']
        ])->save();
    }

    /**
     * Создание логов для всех сотрудников, которые забыли сделать выход.
     *
     * @return bool
     */
    public static function createLogForAll(array $user_ids){
        
        $uploadedFile = [
            'fileName' => 'SystemOut.jpeg',
            'filePath' => '/public/passphotos/SystemOut.jpeg',
            'fileSize' => 4000
        ];
        foreach ($user_ids as $user_id) {
            self::create([
                'user_id' => $user_id,
                'direction' => 0,
                'device_id' => 'CRON',
                'image_name' => $uploadedFile['fileName'],
                'image_path' => $uploadedFile['filePath'],
                'image_size' => $uploadedFile['fileSize']
            ])->save();
        }
        return true;
    }

    public function scopeUser_id($query, $user_id)
    {
        if (!is_null($user_id)) {
            return $query->where('user_id', $user_id);
        }

        return $query;
    }

    public function scopeCreated_at($query, $created_at)
    {
        if ($created_at) {
            // $created_at = \Illuminate\Support\Str::limit($created_at, 15, $end='');
            $created_at = new \DateTime($created_at);
            return $query->whereDate('created_at', $created_at);
        }

        return $query;
    }
}
