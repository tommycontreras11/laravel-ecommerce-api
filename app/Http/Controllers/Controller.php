<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
* @OA\Info(
*             title="E-commerce Api", 
*             version="1.0",
*             description="This is project is about a simple E-commerce API"
* )
*
* @OA\Server(url="http://127.0.0.1:8000")
*/
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
