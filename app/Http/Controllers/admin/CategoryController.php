<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;


class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::latest();

        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%'.$request->get('keyword').'%');
            }
            
        $categories = $categories->paginate(10);
        
        return view('admin.category.list', compact('categories'));

    }

    public function create(){
        return view('admin.category.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                'name' => 'required',
                'slug' => 'required | unique:categories',
            ]);

            if ($validator->passes()) {

                $category = new Category();
                $category->name = $request->name;
                $category->slug = $request->slug;
                $category->status = $request->status;
                $category->showHome = $request->showHome;
                $category->save();

                // Save Image Here
                if (!empty($request->image_id)) {
                    $tempImage = TempImage::find($request->image_id);
                    $extArray = explode('.', $tempImage->name);
                    $ext = last($extArray); // Mengambil ekstensi file

                    $newImageName = $category->id . '.' . $ext;
                    $sPath = public_path() . '/temp/' . $tempImage->name;
                    $dPath = public_path() . '/uploads/category/' . $newImageName;
                    File::copy($sPath,$dPath);

                    // Generate Image Thumbnail
                    $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                
                    $category->image = $newImageName;
                    $category->save();
                }
                
                return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
                ]);

            } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
    }
}

public function edit($categoryId)
{
    // Mengambil kategori berdasarkan ID
    $category = Category::find($categoryId);

    // Jika kategori tidak ditemukan, alihkan ke halaman kategori
    if (!$category) {
        return redirect()->route('categories.index')->with('error', 'Kategori tidak ditemukan.');
    }

    // Mengirim data kategori ke view untuk ditampilkan di form edit
    return view('admin.category.edit', compact('category'));
}

public function update($categoryId, Request $request)
{
    // Mengambil kategori berdasarkan ID
    $category = Category::find($categoryId);

    // Jika kategori tidak ditemukan, kirimkan respons error
    if (!$category) {
        return response()->json([
            'status' => false,
            'notFound' => true,
            'message' => 'Kategori tidak ditemukan',
        ]);
    }

    // Validasi input
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
    ]);

    // Jika validasi gagal
    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]);
    }

    // Update kategori
    $category->name = $request->name;
    $category->slug = $request->slug;
    $category->status = $request->status;
    $category->showHome = $request->showHome;
    $category->save();

    $oldImage = $category->image;

    // Proses gambar kategori jika ada
    if (!empty($request->image_id)) {
        $tempImage = TempImage::find($request->image_id);
        $extArray = explode('.', $tempImage->name);
        $ext = last($extArray); // Mengambil ekstensi file

        $newImageName = $category->id .'-'.time(). '.' . $ext;
        $sPath = public_path() . '/temp/' . $tempImage->name;
        $dPath = public_path() . '/uploads/category/' . $newImageName;
        File::copy($sPath, $dPath);

        // Generate Thumbnail
        $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
      
        $category->image = $newImageName;
        $category->save();


        // Delete Old Images Here
        File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
        File::delete(public_path().'/uploads/category/'.$oldImage);


    }

    // Mengembalikan respons jika sukses
    return response()->json([
        'status' => true,
        'message' => 'Kategori berhasil diperbarui',
    ]);
}


    public function destroy($categoryId, Request $request){
        $category = Category::find($categoryId);
    
    if (is_null($category)) {
        return response()->json([
            'status' => false,
            'message' => 'Category not found'
        ]);
    }

    // Hapus file gambar terkait kategori jika ada
    if (!empty($category->image)) {
        $thumbPath = public_path('/uploads/category/thumb/' . $category->image);
        $imagePath = public_path('/uploads/category/' . $category->image);

        if (file_exists($thumbPath)) {
            unlink($thumbPath);
        }

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Hapus kategori dari database
    $category->delete();

    return response()->json([
        'status' => true,
        'message' => 'Category deleted successfully'
    ]);


    }
}
