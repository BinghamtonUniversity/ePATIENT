<?php

namespace App\Http\Controllers;

use App\Library;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function __construct()
    {
        //
    }

    private function flatten($library) {
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

    public function read($library_type, $library_id)
    {
        $library = Library::where('id',$library_id)->first();
        if (!is_null($library)) {
            return self::flatten($library);
        } else {
            return response('library_id not found', 404);
        }
    }

    public function edit(Request $request, $library_type, $library_id)
    {
        $library = Library::where('id',$library_id)->first();
        $library->update(['data'=>$request->except(['created_at','updated_at','id','type'])]);
        return self::flatten($library);
    }

    public function add(Request $request, $library_type)
    {
        $library = new Library(['type'=>$library_type, 'data'=>$request->except(['created_at','updated_at','id','type'])]);
        $library->save();
        return self::flatten($library);
    }

    public function delete($library_type, $library_id)
    {
        if ( Library::where('id',$library_id)->delete() ) {
            return [true];
        }
    }

}
