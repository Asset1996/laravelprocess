<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PassLogs;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\Headers;
use App\Helpers\Paginator;

class TimingLogs extends Model
{
    protected $table = 'timing_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'day',
        'minutes',
    ];

    /**
     * Если на сегодня сотрудник впервые заходит, 
     * то создает новую запись в таблице($hours = 0).
     * Если выходит, то добавляет к записи отработанные часы.
     *
     * @var array<int, string>
     */
    public static function record(int $user_id, bool $is_on_duty){

        $minutes = 0;

        if($is_on_duty){

            $lastLogForToday = PassLogs::select('created_at')
            ->where('user_id', $user_id)
            ->where('direction', 1)
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            // ->skip(1)
            ->first();
            
            if(!empty($lastLogForToday)){
                if($lastLogForToday->created_at){
                    $created_at = Carbon::parse($lastLogForToday->created_at);
                    $minutes = $created_at->diffInMinutes(Carbon::now());
                }
                return self::updateOrCreate(
                    ['day' => date('Y:m:d'), 'user_id' => $user_id],
                    ['minutes' => DB::raw('minutes + ' . (string)($minutes+1))]
                );
            }
        }
        return True;
    }

    /**
     * По окончанию рабоче дня, отработанные часы отсанавливаются.
     *
     * @var array<int, string>
     */
    public static function recordForAll(array $user_id){

        $minutes = 0;

        $lastLogForToday = DB::table(DB::raw('pass_logs as pl1'))
            ->join(
                DB::raw("(SELECT user_id as uid, MAX(created_at) AS created
                    FROM pass_logs
                    WHERE direction=1
                    GROUP BY user_id) as pl2"), 
                function($query){
                    $query->on('pl1.user_id', '=', 'pl2.uid')
                        ->on('pl1.created_at', '=', 'pl2.created');
                }
            )
            ->whereIn('user_id', $user_id)
            ->get();

        
        foreach ($lastLogForToday as $value) {
            if($value->created_at){
                $created_at = Carbon::parse($value->created_at);
                $minutes = $created_at->diffInMinutes(Carbon::now());
            }
            self::updateOrCreate(
                ['day' => date('Y:m:d'), 'user_id' => $value->user_id],
                ['minutes' => DB::raw('minutes + ' . (string)($minutes+1))]
            );
        }

        return True;
    }

    /**
     * Получние списка информации о времени пребывание на работе.
     *  
     * @param int user_id - ID пользователя
     * @return array
     */
    public static function getList(int $user_id){
        $result = array();
        $timingList = self::where('user_id', $user_id)->paginate($_ENV['PAGE_NUM']);

        $result['headers'] = Headers::get('user.timingList');
        foreach($timingList as $timing){
            $result['body'][] = [
                'day' => $timing['day'],
                'minutes' => self::convertToHoursMins($timing['minutes'])
            ];
        }
        $result['paginator'] = Paginator::get($timingList);
        $result['userFio'] = User::getFioById($user_id);
        return $result;
    }

    /**
     * Минуты конвертирует в чч:мм.
     *  
     * @param int time - время в минутах
     * @param string format
     * @return string
     */
    protected static function convertToHoursMins($time, $format = '%02dч:%02dм') {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }
}
