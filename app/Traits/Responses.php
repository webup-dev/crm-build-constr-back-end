<?php

namespace App\Traits;


trait Responses
{
    public function resp($code, $pre = null, $data = null)
    {
        switch ($code) {
            case 200:
                $response = [
                    'success' => true,
                    'code'    => 200,
                    'message' => $pre . '. Result is successful.',
                    'data'    => $data
                ];
                break;

            case 209:
                $response = [
                    'success' => true,
                    'code'    => 209,
                    'message' => $pre . '. Content is empty.',
                    'data'    => null
                ];
                break;

            case 422:
                $response = [
                    'success' => false,
                    'code'    => 422,
                    'message' => $pre . '. The given data was invalid.',
                    'data'    => null
                ];
                break;

            case 453:
                $response = [
                    'success' => false,
                    'code'    => 453,
                    'message' => $pre . '. Permission is absent by the role.',
                    'data'    => null
                ];
                break;

            case 454:
                $response = [
                    'success' => false,
                    'code'    => 454,
                    'message' => $pre . '. Permission to the department is absent.',
                    'data'    => null
                ];
                break;

            case 456:
                $response = [
                    'success' => false,
                    'code'    => 456,
                    'message' => $pre . '. Incorrect ID in URL.',
                    'data'    => null
                ];
                break;

            case 457:
                $response = [
                    'success' => false,
                    'code'    => 457,
                    'message' => $pre . '. You are not the author.',
                    'data'    => null
                ];
                break;

            case 458:
                $response = [
                    'success' => false,
                    'code'    => 458,
                    'message' => $pre . '. Private information.',
                    'data'    => null
                ];
                break;

            case 459:
                $response = [
                    'success' => false,
                    'code'    => 459,
                    'message' => $pre . '. File extension is invalid.',
                    'data'    => null
                ];
                break;

            case 500:
                $response = [
                    'success' => false,
                    'code'    => 500,
                    'message' => $pre . '. Could not make action.',
                    'data'    => null
                ];
                break;

            default:
                $response = [
                    'success' => false,
                    'code'    => $code,
                    'message' => $pre . '. Result is unknown.',
                    'data'    => $data
                ];
        }

        return $response;
    }

}
