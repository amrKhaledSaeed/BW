<?php
namespace App;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

trait helpers{
    function apiResponse($data, $status = 200)
    {
        return response()->json($data, $status);
    }

}
