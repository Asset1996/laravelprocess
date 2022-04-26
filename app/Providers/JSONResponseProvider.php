<?php

namespace App\Providers;


class JSONResponseProvider
{
    /**
     * Returns success.
     *
     * @return JSON
     */
    public function success(array $data = [], string $message = '', int $statusCode = 200)
    {
        return response()->json([
            'result' => True,
            'data' => $data,
            'message' => $message,
            'code' => $statusCode
        ], $statusCode);
    }

    /**
     * Returns error.
     *
     * @return JSON
     */
    public function error($message = 'error occured', int $statusCode = 401)
    {
        return response()->json([
            'result' => False,
            'message' => $message,
            'code' => $statusCode
        ], $statusCode);
    }
}
