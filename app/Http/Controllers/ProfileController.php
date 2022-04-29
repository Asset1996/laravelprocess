<?php
namespace App\Http\Controllers;

use App\Providers\JSONResponseProvider;
use App\Models\User;
use App\Helpers\Uploader;
use App\Http\Requests\User\Profile\UploadRequest;

class ProfileController extends Controller
{
    /**
     * Constructor.
     * 
     * @param JSONResponseProvider
     * @return void
     */
    public function __construct(JSONResponseProvider $jsonResponder, Uploader $uploader)
    {
        $this->jsonResponder = $jsonResponder;
        $this->uploader = $uploader;
        $this->middleware('isTokenValid');
    }

    /**
     * Профиль текущего пользователя.
     * 
     * @return JSONResponseProvider jsonResponder
     */
    public function profile(){
        $user = User::getProfile();
        return $this->jsonResponder->success($user);
    }

    /**
     * Загрузка аватарки пользователя.
     * 
     * @return JSONResponseProvider jsonResponder
     */
    public function uploadPhoto(UploadRequest $request){

        $request->validated();
        $filePath = $this->uploader->saveFile();
        User::updatePhoto((string) $filePath);
        return $this->jsonResponder->success(['photo'=>$filePath], 'Succesfully uploaded.');
    }
}
