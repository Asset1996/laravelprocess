<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PassLogs;
use \App\Providers\JSONResponseProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Uploader;

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
            'password' => 'required|min:4',
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
            'base64' => 'required'
        ]);

        if ($validator->fails()) {
            return $response->error($validator->errors()->first());
        }

        $uploadedFile = $this->uploader->saveBase64($request->input('base64'));
        
        if (!empty($this->uploader->errors)) return $response->error($this->uploader->getFirstError());

        $passLog = PassLogs::create([
            'user_id' => Redis::get('id'),
            'image_name' => $uploadedFile['fileName'],
            'image_path' => $uploadedFile['filePath'],
            'image_size' => $uploadedFile['fileSize']
        ]);
        Redis::del('id');
        Redis::del('uid');
        if ($passLog->save()){
            return $response->success(['Logs are saved successfully.']);
        }
        return $response->error(['Logs are NOT saved.']);
    }
}
