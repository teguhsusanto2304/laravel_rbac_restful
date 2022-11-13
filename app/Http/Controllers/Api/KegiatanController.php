<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\KegiatanTerverifikasi;

class KegiatanController extends Controller
{
    public function __construct(){
        $this->middleware('permission:kegiatan-list|kegiatan1-create|kegiatan1-edit|kegiatan1-delete', ['only' => ['index','show']]);
        //$this->middleware('permission:user-list', ['only' => ['create','store']]);
        // $this->middleware('permission:user-create', ['only' => ['create','store']]);
        // $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
/**
 * @OA\Get(path="/v1/kegiatan",
 *   tags={"Kegiatan"},
  *      security={{
 *        "bearerAuth":{}
 *      }},
 *   summary="Get the details of an authenticated user",
 *   description="",
  *     @OA\Header(
 *       header="Authorization",
 *       @OA\Schema(
 *           type="integer",
 *           format="int32"
 *       ),
 *       description="calls per hour allowed by the user"
 *     ),
 *   operationId="getAuthUser",
 *   @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\Schema(type="string"),
 *     @OA\Header(
 *       header="X-Expires-After",
 *       @OA\Schema(
 *          type="string",
 *          format="date-time",
 *       ),
 *       description="date in UTC when token expires"
 *     )
 *   ),
 *   @OA\Response(response=400, description="Error xXx"),
 * )
 */
    public function index()
    {
        $data = KegiatanTerverifikasi::where('created_by',Auth::user()->id)->paginate(10); 
        return response()->json([
            'status'=>Response::HTTP_OK,
            'message'=>'OK',
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
        $request['created_by'] = Auth::user()->id;
        $validator = Validator::make($request->all(), [ 
            'nama_kegiatan' => "required|unique:kegiatan_terverifikasis,nama_kegiatan,{$request->get('created_by')}", 
            'lokasi_kegiatan' => 'required',
            'unsur' => 'required|exists:unsur_kegiatan,kode', 
            'klasifikasi' => 'required|exists:subklasifikasi,id_bidang_profesi',  
            'metode' => 'required|exists:metode_kegiatan,kode'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status'=>Response::HTTP_BAD_REQUEST,
                'message'=>$validator->errors()], Response::HTTP_BAD_REQUEST);            
        }
    //$input = $request->all();
    $input = [];
    $input['unsur_id'] = $request->get('unsur');
    $input['klasifikasi_id'] = $request->get('klasifikasi');
    $input['metode_id'] = $request->get('metode');
    $input['lokasi_kegiatan'] = $request->get('lokasi_kegiatan');
    $input['nama_kegiatan'] = $request->get('nama_kegiatan');
    $input['tingkat_id'] = 1;
    $input['data_status'] = 1;
    $input['created_by'] = Auth::user()->id;
    $user = KegiatanTerverifikasi::create($input);
    //$user->assignRole($request->input('roles'));
    return response()->json([
        'status'=>Response::HTTP_CREATED,
        'message'=>'Created',
        'data' => $user], Response::HTTP_CREATED); 
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
