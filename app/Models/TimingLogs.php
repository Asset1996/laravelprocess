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
            
            if($lastLogForToday->created_at){
                $created_at = Carbon::parse($lastLogForToday->created_at);
                $minutes = $created_at->diffInMinutes(Carbon::now());
            }

            return self::updateOrCreate(
                ['day' => date('Y:m:d'), 'user_id' => $user_id],
                ['minutes' => DB::raw('minutes + ' . $minutes)]
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
        return $result;
    }

    protected static function convertToHoursMins($time, $format = '%02dч:%02dм') {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }
}
