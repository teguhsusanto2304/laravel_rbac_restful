<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\KegiatanTerverifikasi;
use App\Models\PesertaKegiatanTerverifikasi;
use DB;

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
 *   summary="mendapatkan data kegiatan",
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
        $data = DB::table('kegiatan_terverifikasis')
        ->select('kegiatan_terverifikasis.kode','kegiatan_terverifikasis.nama_kegiatan',
        'siki_asmet.user_sistem.Nama as penyelenggara',
        'unsur_kegiatan.unsur',
        'subklasifikasi.subklasifikasi as klasifikasi',
        'metode_kegiatan.metode',
        'kegiatan_terverifikasis.lokasi_kegiatan',
        DB::raw('\'Nasional\' as tingkat'),
        DB::raw('DATE_FORMAT(kegiatan_terverifikasis.created_at,\'%d-%m-%Y\') as tanggal_aktif'),
        DB::raw('(SELECT COUNT(kegiatan_id) FROM peserta_kegiatan_terverifikasis WHERE kegiatan_id=kegiatan_terverifikasis.id) as peserta'))
        ->join('siki_asmet.user_sistem','kegiatan_terverifikasis.penyelenggara_id','=','siki_asmet.user_sistem.IDUser')
        ->join('unsur_kegiatan','kegiatan_terverifikasis.unsur_id','=','unsur_kegiatan.kode')
        ->join('subklasifikasi','kegiatan_terverifikasis.klasifikasi_id','=','subklasifikasi.id_bidang_profesi')
        ->join('metode_kegiatan','kegiatan_terverifikasis.metode_id','=','metode_kegiatan.kode')
        ->where(['kegiatan_terverifikasis.created_by'=>Auth::user()->id,'subklasifikasi.isparent'=>1])->paginate(10); 
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
     * @OA\Post(
     ** path="/v1/kegiatan",
 *   tags={"Kegiatan"},
  *      security={{
 *        "bearerAuth":{}
 *      }},
 *   summary="membuat data kegiatan baru",
     *   operationId="kegiatan",
     *
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="nama_kegiatan", type="string", format="text", example="nama kegiatan"),
 *       @OA\Property(property="lokasi_kegiatan", type="string", format="text", example="www.penyelenggara.com"),
 *        @OA\Property(property="unsur", type="string", format="text", example="U001"),
  *        @OA\Property(property="klasifikasi", type="string", format="text", example="AS"),
    *        @OA\Property(property="metode", type="string", format="text", example="M01"),
        *        @OA\Property(property="kode_penyelenggara", type="string", format="text", example="2761"),
 *    ),
 * ),
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
    public function store(Request $request)
    {
        $request['created_by'] = Auth::user()->id;
        $validator = Validator::make($request->all(), [ 
            'nama_kegiatan' => "required|unique:kegiatan_terverifikasis,nama_kegiatan,{$request->get('created_by')}", 
            'lokasi_kegiatan' => 'required',
            'unsur' => 'required|exists:unsur_kegiatan,kode', 
            'klasifikasi' => 'required|exists:subklasifikasi,id_bidang_profesi',  
            'metode' => 'required|exists:metode_kegiatan,kode',
            'kode_penyelenggara' => 'required|exists:penyelenggara,id'
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
    $input['penyelenggara_id'] = $request->get('kode_penyelenggara');
    $user = KegiatanTerverifikasi::create($input)->id;
    $update = KegiatanTerverifikasi::find($user);
    $update['kode'] = SHA1($user);
    $update->update(); 
    $data = DB::table('kegiatan_terverifikasis')
        ->select('kegiatan_terverifikasis.kode','kegiatan_terverifikasis.nama_kegiatan',
        'siki_asmet.user_sistem.Nama as penyelenggara',
        'unsur_kegiatan.unsur',
        'subklasifikasi.subklasifikasi as klasifikasi',
        'metode_kegiatan.metode',
        'kegiatan_terverifikasis.lokasi_kegiatan',
        DB::raw('\'Nasional\' as tingkat'),
        DB::raw('DATE_FORMAT(kegiatan_terverifikasis.created_at,\'%d-%m-%Y\') as tanggal_aktif'))
        ->join('siki_asmet.user_sistem','kegiatan_terverifikasis.penyelenggara_id','=','siki_asmet.user_sistem.IDUser')
        ->join('unsur_kegiatan','kegiatan_terverifikasis.unsur_id','=','unsur_kegiatan.kode')
        ->join('subklasifikasi','kegiatan_terverifikasis.klasifikasi_id','=','subklasifikasi.id_bidang_profesi')
        ->join('metode_kegiatan','kegiatan_terverifikasis.metode_id','=','metode_kegiatan.kode')
        ->where(['kegiatan_terverifikasis.id'=>$user,'subklasifikasi.isparent'=>1])
        ->get();
    //$user->assignRole($request->input('roles'));
    return response()->json([
        'status'=>Response::HTTP_CREATED,
        'message'=>'Created',
        'data' => $data], Response::HTTP_CREATED); 
    }

/**
 * @OA\Get(path="/v1/kegiatan/{kode}/peserta",
 *   tags={"Kegiatan"},
  *      security={{
 *        "bearerAuth":{}
 *      }},
 *      @OA\Parameter(
     *         name="kode",
     *         in="path",
     *         description="kode kegiatan",
     *         required=true,
     *      ),
 *   summary="mendapatkan data peserta kegiatan",
 *   description="",
  *     @OA\Header(
 *       header="Authorization",
 *       @OA\Schema(
 *           type="integer",
 *           format="int32"
 *       ),
 *       description="calls per hour allowed by the user"
 *     ),
 *   operationId="getPeserta",
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
public function peserta($kode)
{
    $data = DB::table('peserta_kegiatan_terverifikasis')
    ->select('peserta_kegiatan_terverifikasis.nik','peserta_kegiatan_terverifikasis.nama_peserta','metode_kegiatan.metode',
    'unsur_kegiatan.unsur',
    DB::raw('DATE_FORMAT(peserta_kegiatan_terverifikasis.created_at,\'%d-%m-%Y\') as tanggal_ikutserta'),
    DB::raw('IFNULL(siki_asmet.personal_profile.NoUrutBaru,\'Tidak Terdaftar\') as peserta_pkb'))
    ->join('kegiatan_terverifikasis','peserta_kegiatan_terverifikasis.kegiatan_id','=','kegiatan_terverifikasis.id')
    ->join('metode_kegiatan','peserta_kegiatan_terverifikasis.metode_id','=','metode_kegiatan.kode')
    ->join('unsur_kegiatan','peserta_kegiatan_terverifikasis.unsur_id','=','unsur_kegiatan.kode')
    ->leftJoin('siki_asmet.personal_profile','peserta_kegiatan_terverifikasis.nik','=','siki_asmet.personal_profile.NoKTP')
    ->where('kegiatan_terverifikasis.kode',$kode)->paginate(10); 
    return response()->json([
        'status'=>Response::HTTP_OK,
        'message'=>'OK',
        'data' => $data], Response::HTTP_OK); 
}


    /**
     * @OA\Post(
     ** path="/v1/kegiatan/{kode}/peserta",
 *   tags={"Kegiatan"},
  *      security={{
 *        "bearerAuth":{}
 *      }},
  *      @OA\Parameter(
     *         name="kode",
     *         in="path",
     *         description="kode kegiatan",
     *         required=true,
     *      ),
 *   summary="membuat data peserta kegiatan baru",
     *   operationId="pesertaKegiatan",
     *
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"nik","nama_peserta"},
 *  @OA\Property(property="nik", type="string", format="text", example="XXXXXXXXXXXXXXXX"),
 *       @OA\Property(property="nama_peserta", type="string", format="text", example="nama peserta"),
 *        @OA\Property(property="unsur", type="string", format="text", example="U001"),
    *        @OA\Property(property="metode", type="string", format="text", example="M01"),
 *    ),
 * ),
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
    public function peserta_add(Request $request,$kode)
    {
        $request['created_by'] = Auth::user()->id;
        $request['kode'] = $kode;
        $validator = Validator::make($request->all(), [ 
            'kode' => "required|exists:kegiatan_terverifikasis,kode", 
            'nik' => 'required|min:16',
            'nama_peserta' => 'required',
            'unsur' => 'required|exists:unsur_kegiatan,kode',  
            'metode' => 'required|exists:metode_kegiatan,kode'
        ]);
        if ($validator->fails()) { 
            return response()->json([
                'status'=>Response::HTTP_BAD_REQUEST,
                'message'=>$validator->errors()], Response::HTTP_BAD_REQUEST);            
        }
    //$input = $request->all();
    $input = [];
    $input['kegiatan_id'] = 1;
    $input['unsur_id'] = $request->get('unsur');
    $input['metode_id'] = $request->get('metode');
    $input['nik'] = $request->get('nik');
    $input['nama_peserta'] = $request->get('nama_peserta');
    $input['tingkat_id'] = 1;
    $input['data_status'] = 1;
    $input['created_by'] = Auth::user()->id;
    $user = PesertaKegiatanTerverifikasi::create($input);
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
