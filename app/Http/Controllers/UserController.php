<?php
namespace App\Http\Controllers;

use App\Providers\JSONResponseProvider;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Constructor.
     * 
     * @param JSONResponseProvider
     * @return void
     */
    public function __construct(JSONResponseProvider $jsonResponder)
    {
        $this->jsonResponder = $jsonResponder;
        $this->middleware('isTokenValid');
    }

    /**
     * Извлекает список пользователей из БД.
     * 
     * @return JSONResponseProvider jsonResponder
     */
    public function usersList(){
        $usersList = User::getList();
        return $this->jsonResponder->success($usersList);
    }

    /**
     * Извлекает список ID и ФИО пользователей.
     * 
     * @return JSONResponseProvider jsonResponder
    */
    public function getUsersFioAndId(){
        $usersList = User::getFioAndIdList();
        return $this->jsonResponder->success($usersList);
    }

    /**
     * Создает нового пользователя в БД.
     * 
     * @return JSONResponseProvider jsonResponder
     */
    public function createUser(){
        $method = request()->method();
        if($method == "GET"){
            $data = User::getCreateData();
            return $this->jsonResponder->success($data);
        }elseif($method == "POST"){
            echo 'post'; exit();
        }else{
            return $this->jsonResponder->error('Method not allowed', 405);
        }
    }
}
