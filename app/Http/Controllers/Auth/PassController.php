<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PassLogs;
use App\Models\TimingLogs;
use \App\Providers\JSONResponseProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;
use App\Helpers\Uploader;
use Illuminate\Support\Facades\DB;

class PassController extends Controller
{
    public $uploader;

    public function __construct(Uploader $uploader)
    {
        $this->uploader = $uploader;
    }
    /**
     * Handle an incoming authentication request for pass on tablet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Helpers\JSONResponseProvider
     */
    public function pass(Request $request, User $user, JSONResponseProvider $response){
        $validator = Validator::make($request->all(), [
            'pin_code' => 'required|min:6|min:6',
        ]);

        if ($validator->fails()) {
            return $response->error($validator->errors()->first());
        }

        $userData = $user->passAuthenticate($request->all());

        if($userData){
            Redis::set('id', $userData['id']);
            Redis::set('uid', $userData['uid']);
            return $response->success($userData);
        }else{
            return $response->error('Pass failed. Wrong password.');
        };
    }

    /**
     * Take a picture on pass.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Helpers\JSONResponseProvider
     */
    public function takePicture(Request $request, User $user, JSONResponseProvider $response){
        
        $validator = Validator::make($request->all(), [
            'uid' => [
                'required',
                Rule::in([Redis::get('uid')]),
            ],
            'device_id' => 'required',
            'base64' => 'required'
        ]);

        if ($validator->fails()) {
            return $response->error($validator->errors()->first());
        }

        $uploadedFile = $this->uploader->saveBase64($request->input('base64'));
        
        if (!empty($this->uploader->errors)) return $response->error($this->uploader->getFirstError());

        $user_id = Redis::get('id');
        Redis::del('id');
        Redis::del('uid');

        $is_on_duty = User::select('is_on_duty')->where('id', $user_id)->first()->toArray()['is_on_duty'];

        $passLog = PassLogs::create([
            'user_id' => $user_id,
            'direction' => !$is_on_duty,
            'device_id' => $request->input('device_id'),
            'image_name' => $uploadedFile['fileName'],
            'image_path' => $uploadedFile['filePath'],
            'image_size' => $uploadedFile['fileSize']
        ]);
        User::where('id', $user_id)->update(['is_on_duty'=>DB::raw(
            'CASE 
                WHEN `is_on_duty`=1 THEN 0
                WHEN `is_on_duty`=0 THEN 1 
            END'
        )]);

        TimingLogs::record($user_id, (bool) $is_on_duty);
        
        if ($passLog->save()){
            return $response->success([
                'message' => 'Logs are saved successfully.',
                'is_on_duty' => (bool) !$is_on_duty
            ]);
        }
        return $response->error(['Logs are NOT saved.']);
    }
}
