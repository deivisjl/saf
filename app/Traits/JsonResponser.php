<?php

namespace App\Traits;


trait JsonResponser{
    
    protected function successResponse($data, $code = 200){

		return response()->json(['data' => $data], $code);
	}

	protected function errorResponse($message, $code = 423){

		return response()->json(['error' => $message, 'code' => $code], $code);
	}
}