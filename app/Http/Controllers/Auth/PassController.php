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
use App\Helpers\Uploader;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class PassController extends Controller
{
    public $uploader;

    public function __construct(Uploader $uploader)
    {
        $this->middleware('auth:api', ['except' => ['testget', 'verifyKit']]);
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

    // public function verifyKit(){

    //     $serverKey = 'ybs3c8743db15fb6cc52f13a88f894c972709b86a8faafe7aabb653fa0525';
    //     $clientIp = '172.20.10.2';
    //     $vfk = new \VerifyKit\Web($serverKey);
    //     $validationMethodList = $vfk->getValidationMethodList();

    //     $validationMethod = 'whatsapp';
    //     $lang = 'en';
    //     $deeplink = true;
    //     $qrCode = false;
    //     $validationStart = $vfk->startValidation($validationMethod, $lang, $deeplink, $qrCode);
    //     Redis::set('reference', $validationStart->getReference());

    //     echo 'Перейдите по ссылке: ' . $validationStart->getDeeplink();
        
    // }

    // public function testget(Request $request, JSONResponseProvider $response){

    //     $serverKey = 'ybs3c8743db15fb6cc52f13a88f894c972709b86a8faafe7aabb653fa0525';
    //     $vfk = new \VerifyKit\Web($serverKey);

    //     $reference = Redis::get('reference');
    //     Redis::del('reference');
    //     $validationCheck = $vfk->checkValidation($reference);

    //     if ($validationCheck->getValidationStatus()) {
    //         $sessionId = $validationCheck->getSessionId(); // session id for the validation result
    //         $appPlatform = $validationCheck->getAppPlatform(); // web, android or ios
    //         return $response->success([
    //             'status' => 'success',
    //             'message' => 'Successfully logged in.',
    //             'sessionId' => $sessionId,
    //             'appPlatform' => $appPlatform
    //         ]);
    //     }else{
    //         $response->error(['Login failed.']);
    //     }
    // }
}
