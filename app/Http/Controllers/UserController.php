<?php
namespace App\Http\Controllers;

use App\Providers\JSONResponseProvider;
use App\Models\User;
use App\Http\Requests\User\CreateUserRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Hash;

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
     * @param CreateUserRequest $request - validated request
     * @return JSONResponseProvider jsonResponder
     */
    public function createUserGet(){
        $data = User::getCreateData();
        return $this->jsonResponder->success($data);
    }

    /**
     * Создает нового пользователя в БД.
     * 
     * @param CreateUserRequest $request - validated request
     * @return JSONResponseProvider jsonResponder
     */
    public function createUserPost(CreateUserRequest $request){
        $validated = $request->validated();
        // print_r($validated);
        // exit();
        $result = User::createUser($validated);
        return $this->jsonResponder->success([], 'Пользователь добавлен.');
    }
}
