<?php

namespace App\Api\V1\Traits;

use Illuminate\Http\Response;

trait ApiResponsesVadis
{

    public function apiResponses($code, $message = null, $data = null)
    {
        $arrays = [
            200 => [true, 'Ok', 'custom'],
            204 => [true, 'Content is empty', null],
            422 => [false, 'The given data was invalid', null],
            456 => [false, 'Incorrect Entity ID in the URL', null],
            453 => [false, 'Permission is absent due to Role', null],
            454 => [false, 'Permission to the department is absent', null],
            457 => [false, 'You are not the author', null],
            458 => [false, 'There is a child', null],
            459 => [false, 'There is a soft-deleted child', null],
            460 => [false, 'There is a parent', null],
            461 => [false, 'There is a soft-deleted parent', null],
            462 => [false, 'Id of this Entity is used as foreign key', null],
        ];

        $response = [
            "success" => $arrays[$code][0],
            "code"    => $code,
            "message" => $message === null ? $arrays[$code][1] : $message,
            "data"    => $data === null ? $arrays[$code][2] : $data
        ];

        return $response;
    }
}
