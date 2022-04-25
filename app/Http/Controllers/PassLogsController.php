<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\App;

use App\Models\PassLogs;
use Illuminate\Http\Request;
use App\Providers\JSONResponseProvider;
use App\Models\User;
use App\Models\Roles;

class PassLogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('isTokenValid');
    }

    public function list(JSONResponseProvider $response){
        $logs = PassLogs::getList();
        return $response->success($logs);
    }
}
