<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempImage; // Pastikan model TempImage diimpor

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->image;

        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            // Buat instance TempImage
            $tempImage = new TempImage();
            $tempImage->name = $newName; // Gunakan $newName yang benar
            $tempImage->save();

            // Pindahkan file gambar ke folder 'public/temp'
            $image->move(public_path() . '/temp', $newName);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'message' => 'Image uploaded successfully'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'No image provided'
        ]);
    }
}
