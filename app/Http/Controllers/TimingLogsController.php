<?php

namespace App\Http\Controllers;

use App\Models\TimingLogs;
use App\Providers\JSONResponseProvider;

class TimingLogsController extends Controller
{
    protected $jsonResponder;
    /**
     * Constructor.
     * @param JSONResponseProvider
     * @return void
     */
    public function __construct(JSONResponseProvider $jsonResponder)
    {
        $this->jsonResponder = $jsonResponder;
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function timingsList($user_id)
    {
        $logs = TimingLogs::getList((int) $user_id);
        return $this->jsonResponder->success($logs);
    }
}
