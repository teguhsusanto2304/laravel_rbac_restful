<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Hash;

class AuthController extends Controller
{
    public $successStatus = 200;
    /**
     * @OA\Post(
     ** path="/v1/login",
     *   tags={"auth"},
     *   summary="Login",
     *   operationId="login",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function login(Request $request){ 
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email', 
            'password' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthenticated'], 401); 
        } 
    }
   /**
 * @OA\Post(
 * path="/v1/logout",
 * summary="Logout",
 * description="Logout user and invalidate token",
 * operationId="authLogout",
 * tags={"auth"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Success"
 *     ),
 * @OA\Response(
 *    response=401,
 *    description="Returns when user is not authenticated",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Not authorized"),
 *    )
 * )
 * )
 */
public function logout(Request $request){

}
public function register(Request $request){
    $validator = Validator::make($request->all(), [ 
        'name' => 'required', 
        'email' => 'required|email|unique:users,email', 
        'password' => 'required', 
        'confirm-password' => 'required|same:password', 
    ]);
    if ($validator->fails()) { 
        return response()->json([
            'status'=>Response::HTTP_BAD_REQUEST,
            'message'=>$validator->errors()], Response::HTTP_BAD_REQUEST);            
    }
$input = $request->all();
$input['password'] = Hash::make($input['password']);
$user = User::create($input);
//$user->assignRole($request->input('roles'));
return response()->json([
    'status'=>Response::HTTP_CREATED,
    'message'=>'Created',
    'data' => $user], Response::HTTP_CREATED);
}
}
