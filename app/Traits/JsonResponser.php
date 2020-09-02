<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait JsonResponser{
    
    protected function successResponse($data, $code = 200){

		return response()->json(['data' => $data], $code);
	}

	protected function errorResponse($message, $code = 423){

		return response()->json(['error' => $message, 'code' => $code], $code);
	}

	protected function showAll(Collection $collection, $code = 200)
	{
		return response()->json(['data'=>$collection],$code);
	}
}