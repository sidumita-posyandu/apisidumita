<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Konten;
use Validator;
use Carbon\Carbon;

class KontenController extends Controller
{
    public function index() 
    {
        $kontens = Konten::all();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $kontens
        ]);

    }

    public function store(Request $request)
    {
        // dd($request);
       $validator = Validator::make($request->all(), 
        [ 
            'judul' => 'required',
            'konten' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);   
 
        if ($validator->fails()) {          
            return response()->json(['error'=>$validator->errors()], 400);                        
        }  
 
  
        if ($images = $request->file('gambar')) {
            
            //store file into document folder
            $filename = pathinfo($request->gambar->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = pathinfo($request->gambar->getClientOriginalName(), PATHINFO_EXTENSION);
            $path = $filename . "_" . Carbon::now()->format('Y-m-d_H-i-s') . ".".$extension;
            $image = $request->file('gambar')->storeAs('public/konten_image', $path);
            
            //store your file into database
            $konten = new Konten();
            $konten->gambar = 'konten_image/'.$path;
            $konten->judul = $request->judul;
            $konten->konten = $request->konten;
            $konten->save();
              
            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $konten
            ]);
  
        }
    }

    public function show($id) 
    {
        $kontens = Konten::findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $kontens
        ]);

    }

    public function update(Request $request, Konten $konten)
    {
        $validasi = Validator::make($request->all(), [
            'judul' => 'required',
            'konten' => 'required',
            'gambar' => 'required',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $konten->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data vitamin berhasil diubah",
            'data' => $konten
        ], 200);
    }

    public function destroy(Konten $konten)
    {
        $konten->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data vitamin berhasil dihapus!",
        ], 200);
    }
}