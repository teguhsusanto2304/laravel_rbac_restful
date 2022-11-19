<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Unsur;
use App\Models\klasifikasi;
use App\Models\Penyelenggara;

class UnsurController extends Controller
{
    public function __construct(){
        //$this->middleware('permission:unsur-list', ['only' => ['index','show']]);
    }
    /**
     * @OA\Get(
     *      path="/v1/unsur",
     *      operationId="get unsur list",
     *      tags={"Refrensi"},

     *      summary="Get list of active tests",
     *      description="Returns list of active tests",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function unsur()
    {
        $data = Unsur::select('kode','unsur')->get(); 
        return response()->json([
            'status'=>Response::HTTP_OK,
            'message'=>'OK',
            'data' => $data], Response::HTTP_OK); 
    }
    /**
     * @OA\Get(
     *      path="/v1/klasifikasi",
     *      operationId="get klasifikasi list",
     *      tags={"Refrensi"},

     *      summary="Get list of active tests",
     *      description="Returns list of active tests",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function klasifikasi()
    {
        $data = Klasifikasi::select('id_bidang_profesi as kode','subklasifikasi as klasifikasi')->where('parent',0)->get();  
        return response()->json([
            'status'=>200,
            'message'=>'OK',
            'data' => $data], 200); 
    }
    /**
     * @OA\Get(
     *      path="/v1/metode",
     *      operationId="get metode list",
     *      tags={"Refrensi"},

     *      summary="Get list of active tests",
     *      description="Returns list of active tests",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function metode()
    {
        $data = Klasifikasi::select('id_bidang_profesi as kode','subklasifikasi as klasifikasi')->where('parent',0)->get();  
        return response()->json([
            'status'=>200,
            'message'=>'OK',
            'data' => $data], 200); 
    }
    /**
     * @OA\Get(
     *      path="/v1/penyelenggara",
     *      operationId="get penyelenggara list",
     *      tags={"Refrensi"},

     *      summary="Get list of active tests",
     *      description="Returns list of active tests",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function penyelenggara()
    {
        $data = Penyelenggara::select('id as kode','penyelenggara')->get();  
        return response()->json([
            'status'=>200,
            'message'=>'OK',
            'data' => $data], 200); 
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
