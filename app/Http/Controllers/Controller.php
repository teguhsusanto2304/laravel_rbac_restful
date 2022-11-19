<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
* @OA\OpenApi(
*     @OA\Info(
*         version="1.0.0",
*         title="Dokumentasi API Aplikasi PKB",
*         description="ini adalah dokumentasi API Aplikasi PKB untuk integrasi dengan sistem PKB",
*         termsOfService="http://lpjk.pu.go.id/pkb/terms/",
*         @OA\Contact(
*             email="admin@lpjk.net"
*         ),
*         @OA\License(
*             name="Apache 2.0",
*             url="http://www.apache.org/licenses/LICENSE-2.0.html"
*         )
*     ),
*     @OA\Server(
*         description="PKB API host",
*         url="http://dev.siki.pu.go.id/pkb/api/api/"
*     ),
*     @OA\Server(
*         description="Local API host",
*         url="http://localhost:8000/api/"
*     ),
*     @OA\ExternalDocumentation(
*         description="Tentang Aplikasi PKB",
*         url="http://lpjk.pu.go.id/pkb"
*     )
* )
*     @OA\Components(
*         @OA\SecurityScheme(
*             type="http",
*             description="Use a global client_id / client_secret and your username / password combo to obtain a token",
*             name="Authorization",
*             in="header",
*             scheme="bearer",
*             bearerFormat="JWT",
*             securityScheme="bearerAuth",
*          )
*     )
*/
class Controller extends BaseController
{
    
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
