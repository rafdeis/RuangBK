<?php

namespace App\Http\Controllers;

use App\Http\Controller\{
    AuthController,
};
use App\Models\{
    Siswa,
    User,

};

use Illuminate\Http\Request;
use Hash;
use Auth;
use Validator;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $request->keywords;
        $request->page;
        $siswa = Siswa::leftjoin('users', 'users.id', '=', 'user_id')->where('users.nama_user', 'like', '%'.strtolower($request->keywords)."%")
        ->orderBy("siswas.created_at", 'desc')
        ->paginate($request->perpage, [
            'siswas.id',
            'siswas.user_id',
            'users.status',
            'siswas.nama_siswa',
            'siswas.tempat_lahir',
            'siswas.tanggal_lahir',
            'siswas.foto',
            'siswas.alamat',
            'siswas.created_at' 
        ]);

        return response()->json([
            'status' => 'success',
            'perpage' => $request->perpage,
            'message' => 'sukses menampilkan data',
            'data' => $siswa,
            'user' => $user->id
        ]);
    }


    public function store(Request $request)
    {
        $user = $request->user();
        {
            $rules = array(
                'nama_siswa' => 'required|string|max:20',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string|max:255',
                'foto' => 'required',
            );
    
            $cek = Validator::make($request->all(),$rules);
    
            if($cek->fails()){
                $errorString = implode(",",$cek->messages()->all());
                return response()->json([
                    'message' => $errorString
                ], 401);
            }else{
                    $siswa = Siswa::create([
                        'user_id' => $user->id,
                        'nama_siswa' => $request->nama_siswa,
                        'tempat_lahir' => $request->tempat_lahir,
                        'tanggal_lahir' => $request->tanggal_lahir,
                        'alamat' => $request->alamat,
                        'foto' => $request->foto,
                    ]);
        
                return response()->json([
                    "status" => "success",
                    "message" => 'Berhasil Menyimpan Data',
                ]);
            }
    
        }
    }   
}
