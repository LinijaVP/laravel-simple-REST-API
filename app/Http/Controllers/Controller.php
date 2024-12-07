<?php

namespace App\Http\Controllers;



/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Laravel API documentation",
 *      description="API for Waitlists",
 *      @OA\Contact(
 *          url="https://github.com/LinijaVP"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 * 
 * @OA\SecurityScheme(
 *      securityScheme="sanctum",
 *      in="header",
 *      name="sanctum",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="Token",
 * ),
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Api server"
 * )
 * 
 *
 * @OA\Tag(
 *     name="Projects",
 *     description="API Endpoints of Projects"
 * )
 */
abstract class Controller
{
    //
}
