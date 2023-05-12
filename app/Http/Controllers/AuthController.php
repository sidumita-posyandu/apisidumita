<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use App\Keluarga;
use App\OperatorPosyandu;
use App\PetugasKesehatan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','logout']]);
    }

    public function registerAdmin()
    {
        $validator = Validator::make(request()->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=> 'required',
            'role_id'=> 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->messages());
        }

        $user = User::create([
            'name'=> request('name'),
            'email'=> request('email'),
            'password'=> Hash::make(request('password')),
            'role_id'=> request('role_id'),
        ]);

        if(request('role_id') == 2){
            $operator_posyandu = OperatorPosyandu::create([
                'nama' => request('name'),
                'kecamatan_id' => request('kecamatan_id'),
                'user_id' => $user->id
            ]);
        }
        
        if(request('role_id') == 3){
            $operator_posyandu = PetugasKesehatan::create([
                'nama' => request('name'),
                'dusun_id' => request('kecamatan_id'),
                'user_id' => $user->id
            ]);
        }

        if($user){
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran Berhasil'
            ]);    
        }else{
            return response()->json([
                'message' => 'Pendaftaran Gagal',
                'success' => false
            ]);
        }
    }

    public function register()
    {
        // if(Keluarga::where('no_kartu_keluarga', request('no_kartu_keluarga'))->get()->isEmpty())
        // {
            // request()->validate([
            //     'name' => 'required',
            //     'email' => 'required|unique:users,email',
            //     'password' => 'required',
            //     'no_kartu_keluarga' => 'required|unique:tb_keluarga',
            //     'kepala_keluarga' => 'required',
            //     'alamat' => 'required',
            //     'dusun_id' => 'required|exists:m_dusun,id',
            // ]);

            $validator = Validator::make(request()->all(),[
                'name'=>'required',
                'email'=>'required|email|unique:users',
                'password'=> 'required',
                'role_id'=> 'required',
                'no_kartu_keluarga' => 'required|unique:tb_keluarga',
                'kepala_keluarga' => 'required',
                'alamat' => 'required',
                'dusun_id' => 'required|exists:m_dusun,id',
            ]);
    
            if($validator->fails()){
                return response()->json($validator->messages());
            }
            
            $user = User::create([
                'name'=> request('name'),
                'email'=> request('email'),
                'password'=> Hash::make(request('password')),
                'role_id'=> request('role_id'),
            ]);

            Keluarga::create([
                'no_kartu_keluarga' => request('no_kartu_keluarga'),
                'kepala_keluarga' => request('kepala_keluarga'),
                'alamat' => request('alamat'),
                'dusun_id' => request('dusun_id'),
                'user_id' => $user->id,
            ]);
    
            if($user){
                return response()->json([
                    'success' => true,
                    'message' => 'Pendaftaran Berhasil']);
                    
                }else{
                    return response()->json([
                        'message' => 'Pendaftaran Gagal',
                        'success' => false
                ]);
                }
            // }
    }

    public function changePassword(Request $request)
    {
        // $validator = Validator::make(request()->all(),[
        //     'password'=> 'required'
        // ]);

        // if($validator->fails()){
        //     return response()->json($validator->messages());
        // }

        $data = User::where("id", auth()->user()->id)->first();
        // dd($request->password);
        $data->password = Hash::make($request->password);

        $data->save();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data keluarga berhasil diubah"
        ], 200);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized',
        'success' => false], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}