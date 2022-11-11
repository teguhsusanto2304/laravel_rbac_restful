<?php

namespace App\Http\Controllers\Api;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use Auth;

class PermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:role-list', ['only' => ['index']]); 
        //$this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index','store']]);
        $this->middleware('permission:permission-create', ['only' => ['create','store']]);
         //$this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         //$this->middleware('permission:role-delete', ['only' => ['destroy']]);
         
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data1 = Auth::user();
        //$data2 = $data1->hasAllRoles(Role::all());
        //$data2 = $data1->hasRole('Admin');
        $data2 = $data1->hasPermissionTo('permission-list');
        $data = Permission::paginate(10); 
        return response()->json([
            'status'=>Response::HTTP_OK,
            'message'=>'OK'.$data1->can('permission-list').'/'.$data2,
            'data' => $data], Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
