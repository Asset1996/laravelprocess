<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\User;

class AuthController extends Controller
{
    public function index(){
        // $users = DB::select('select * from user');
        // return json_encode($users, JSON_UNESCAPED_UNICODE);
        // $result = DB::insert('insert into user (name, surname, lastname) values (?, ?, ?)', ['Marc', 'Marcov', 'Marcovich']);
        // $result = DB::update('update user set surname = "Markovinski2 where id = ?', [3]);
        try{
            // $users = DB::table('user')->select('id', 'name', 'surname', 'lastname')->whereIn('id', [1,2])->get();
            $users = User::whereIn('id', [1,3])->get();
        }catch(QueryException $qe){
            return $qe->getMessage();
        }
        
        return $users;
    }
}
