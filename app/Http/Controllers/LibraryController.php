<?php

namespace App\Http\Controllers;

use Storage;
use App\Library;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function __construct()
    {
        //
    }

    private function flatten(Library $library) {
        $library_arr = [
            'id'=>$library->id,
            'created_at'=>$library->created_at->toDateTimeString(),
            'updated_at'=>$library->updated_at->toDateTimeString(),
        ];
        $library_arr = array_merge($library_arr,$library->data);
        return $library_arr;
    }

    public function browse_all()
    {
        $libraries_obj = Library::all();
        $libraries_arr = [];
        foreach($libraries_obj as $library) {
            $libraries_arr[$library->type][] = self::flatten($library);
        }
        return $libraries_arr;
    }

    public function browse($library_type)
    {
        $libraries_obj = Library::where('type',$library_type)->get();
        $libraries_arr = [];
        foreach($libraries_obj as $library) {
            $libraries_arr[] = self::flatten($library);
        }
        return $libraries_arr;
    }

    public function read($library_type, Library $library) {
        return $library;
    }

    public function read_image($library_type, Library $library) {
        if (isset($library->data['image'])) {
            // $max_age = 2592000; // Cache Images for 30 days
            $max_age = 0; // Cache Images for 0 seconds
            $headers = [
                "Cache-Control"=>"max-age=".$max_age,
                "Pragma"=>"cache",
                "Content-Disposition"=>'inline; filename="'.$library->data['image'].'"'
            ];
            $img_path = config('filesystems.disks.local.root').'/images/'.$library->data['image'];
            if (file_exists($img_path) && is_file($img_path)) {
                return response()->file($img_path, $headers);
            } 
        }
        return response('Image Not Found', 404);
    }

    public function edit(Request $request, $library_type, Library $library)
    {
        $library->update(['data'=>$request->except(['created_at','updated_at','id','type','image'])]);
        if ($request->has('image') && substr($request->image,0,5) === "data:") {
            self::delete_image($library);
            $data = $library->data;
            $data['image'] = self::create_image($library, $request->image);
            $library->data = $data;
            $library->save();
        }
        return self::flatten($library);
    }

    public function add(Request $request, $library_type)
    {
        $library = new Library(['type'=>$library_type, 'data'=>$request->except(['created_at','updated_at','id','type','image'])]);
        $library->save();
        if ($request->has('image') && substr($request->image,0,5) === "data:") {
            $data = $library->data;
            $data['image'] = self::create_image($library, $request->image);
            $library->data = $data;
            $library->save();
        }
        return self::flatten($library);
    }

    public function delete($library_type, Library $library)
    {
        self::delete_image($library);
        if ( $library->delete() ) {
            return [true];
        }
    }

    public function create_image(Library $library, $img_string) {
        $ext = substr($img_string,11,strpos($img_string,';base64,')-11);
        $image = base64_decode(substr($img_string,(strpos($img_string,';base64,')+8)));
        $filename = $library->id.'.'.$ext;
        $path = Storage::put('images/'.$filename,$image);
        return $filename;
    }

    public function delete_image(Library $library) {
        if(isset($library->data['image'])) {
            Storage::delete('images/'.$library->data['image']);
        }
    }

}
