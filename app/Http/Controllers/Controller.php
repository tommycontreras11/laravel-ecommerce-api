<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
* @OA\Info(
*    title="Documentación API ",
*    version="1.0",
*    description="Documentación API ",
*   @OA\Contact(
*       name="API Support",
*       email = "tommycontreras34@gmail.com",
*   ),
* ),
* @OA\SecurityScheme(
*   securityScheme="token",
*   type="http",
*   name="Authorization",
*   in="header",
*   scheme="Bearer"
* )
*/

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
