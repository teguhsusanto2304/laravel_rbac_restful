<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use Hash;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store','update']]);
        //$this->middleware('permission:user-create', ['only' => ['create','store']]);
        //$this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        //$this->middleware('permission:user-delete', ['only' => ['destroy']]);
        //$this->middleware('auth');
        //$this->middleware('HasPermission:user-create');
        //$this->middleware('permission:user-list', ['only' => ['create','store']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(10); 
        return response()->json([
            'status'=>Response::HTTP_OK,
            'message'=>'OK',
            'data' => $users], Response::HTTP_OK); 
    }
    /**
* Store a newly created resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
    public function store(Request $request)
    {
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
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            //'email' => 'required|email|unique:users,email', 
            'password' => 'required|same:confirm-password', 
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status'=>Response::HTTP_BAD_REQUEST,
                'message'=>$validator->errors()], Response::HTTP_BAD_REQUEST);            
        }
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
        $user = User::find($id);
        $user->update($input);
        return response()->json([
            'status'=>Response::HTTP_CREATED,
            'message'=>'Updated',
            'data' => $user], Response::HTTP_CREATED); 
    }
}
