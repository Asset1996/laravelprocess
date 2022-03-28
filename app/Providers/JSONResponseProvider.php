<?php

namespace App\Providers;


class JSONResponseProvider
{
    /**
     * Returns success.
     *
     * @return JSON
     */
    public function success(array $data = [], int $statusCode = 201)
    {
        return json_encode([
            'result' => True,
            'data' => $data,
            'status' => $statusCode
        ]);
    }

    /**
     * Returns error.
     *
     * @return JSON
     */
    public function error($message = 'error occured', int $statusCode = 401)
    {
        return json_encode([
            'result' => False,
            'message' => $message,
            'code' => $statusCode
        ]);
    }
}
