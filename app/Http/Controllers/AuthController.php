<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
   /**
    * Register a user
    * @OA\Post(
    *      path="/register",
    *      operationId="register",
    *      tags={"User Authentication"},
    *      summary="Creates a user",
    *      description="Create a new user",
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *               required={"name","email", "password", "password_confirmation"},
    *               @OA\Property(property="name", type="string", example="Name"),
    *               @OA\Property(property="email", type="email", example="vp@gmail.com"),
    *               @OA\Property(property="password", type="string", example="pass"),
    *               @OA\Property(property="password_confirmation", type="string", example="pass"),
    *           )
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successfully registered",
    *       ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad Request"
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      ),
    *      @OA\Response(
    *         response=422,
    *         description="Validation error",
    *         @OA\JsonContent(
    *             @OA\Property(
    *                 property="message",
    *                 type="string",
    *                 example="The email has already been taken."
    *             )
    *          )
    *      )
    * )
    * 
    */
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'=> ['required', 'string','confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
            'status' => 'error',
            'errors' => $validator->errors(),
            ], 422);
        }

        $fields = $validator->validated();

        $user = User::create($fields);

        $token = $user->createToken($request->name);
        return [
            $user,
            $token->plainTextToken,
        ];
    }
   /**
    * Log in a user
    * @OA\Post(
    *      path="/login",
    *      operationId="login",
    *      tags={"User Authentication"},
    *      summary="Log a user in",
    *      description="Log a user in",
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\JsonContent(
    *               required={"email", "password"},
    *               @OA\Property(property="email", type="email", example="vp@gmail.com"),
    *               @OA\Property(property="password", type="string", example="pass"),
    *           )
    *      ),
    *      @OA\Response(
    *          response=200,
    *          description="Successfully logged in",
    *       ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad Request"
    *      ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    * 
    */
    public function login(Request $request){
        $fields = $request->validate([
            "email"=> "required|email|exists:users",
            "password"=> "required",
        ]);

        $user = User::where("email", $request->email)->first();

        if  (!$user || !Hash::check($request->password, $user->password)) {
            return ["message" => "The provided credentials are incorrect"];
        }

        $token = $user->createToken($user->name);
        return [
            $user,
            $token->plainTextToken,
        ];
    }
   /**
    * Logs a user out
    * @OA\Post(
    *      path="/logout",
    *      operationId="logout",
    *      tags={"User Authentication"},
    *      summary="Logs a user out",
    *      description="Logs a user out",
    *      security={{"sanctum": {}},},
    *      @OA\Response(
    *          response=200,
    *          description="Successfully logged out",
    *       ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad Request"
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    * 
    */

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return ["message"=> "You are logged out"];
    }
}
