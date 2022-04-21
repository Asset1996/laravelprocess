<?php

namespace App\Http\Controllers;

use App\Models\TimingLogs;
use App\Providers\JSONResponseProvider;
use App\Helpers\Excel;

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
        $this->middleware('auth:api', ['except' => ['login', 'exportExcel']]);
    }

    /**
     * Return the list of timings for particular user.
     * 
     * @param int $user_id
     * @return JSONResponseProvider
     */
    public function timingsList($user_id)
    {
        $logs = TimingLogs::getList((int) $user_id);
        return $this->jsonResponder->success($logs);
    }

    /**
     * Downloads the excel report.
     * 
     * @param int $user_id
     * @return excel writer
     */
    public function exportExcel($user_id)
    {
        Excel::exportTimingsByUserId($user_id);
    }
}
