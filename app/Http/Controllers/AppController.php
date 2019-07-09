<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Library;
use App\User;
use App\Team;
use App\Scenario;
use App\Role;


class AppController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    private function getApp($app_name) {
        $app_data = [
            'id'=>$app_name,
            'name'=>$app_name,
            'slug'=>$app_name,
            'options'=>['admin'=>false],
            'user_options'=>null,
            'app'=>[
                'id'=>$app_name,
                'name'=>$app_name,
                'code'=>[
                    'css'=>file_get_contents(public_path($app_name.'/style.css')),
                    'resources'=>json_decode(file_get_contents(public_path($app_name.'/resources.json'))),
                ],
            ],
        ];
        $code_types = ['forms','templates','scripts'];
        foreach($code_types as $type) {
            $files = array_diff(scandir(public_path($app_name.'/'.$type)),['..', '.']);
            foreach($files as $filename) {
                $app_data['app']['code'][$type][] = [
                    'name'=>pathinfo($filename, PATHINFO_FILENAME),
                    'content' => file_get_contents(public_path($app_name.'/'.$type.'/'.$filename)),
                ];
            }
        }
        return view('main',[
            'app_data' => json_encode($app_data),
            'name'=>$app_name
        ]);
    }

    public function getViewerApp(Request $request) {
        return $this->getApp('ePATIENTViewer');
    }
    public function getAdminApp(Request $request) {
        return $this->getApp('ePATIENTAdmin');
    }
    public function getViewerInitData() {
        $libraries = ['labs','solutions','products','providers'];
        $response = [];
        foreach($libraries as $library_type) {
            $libraries_obj = Library::where('type',$library_type)->get();
            $library_arr = [];
            foreach($libraries_obj as $library) {
                $library_arr[] = array_merge([
                    'id'=>$library->id,
                    'created_at'=>$library->created_at->toDateTimeString(),
                    'updated_at'=>$library->updated_at->toDateTimeString(),
                ],$library->data);
            }
            $response[$library_type] = $library_arr;
        }

        /* Get Current User */
        $user = User::where('id',1)->first();
        // Auth::login($user, true);
        // $user = Auth::user();
        $response['user'] = $user;

        /* Get Teams */
        $teams = Team::whereHas('team_members',function($query) use ($user) {
            $query->where('user_id', $user->id)->orWhereHas('user',function($query) use ($user) {
                $query->where('unique_id', $user->id);
            });
        })->with(['scenario'=>function($query){
            $query->select('id','name');
        }])->get();
        $response['myteams'] = $teams;

        /* Get Options */
        $response['options'] = ['admin'=>false];

        return $response;
    }
    public function getAdminInitData() {
        $response = [
            'scenarios' => Scenario::all(),
            'roles' => Role::all(),
            'users' => User::all(),
        ];
        return $response;
    }

    public function initData(Request $request, $app_name)
    {
        if ($app_name === 'ePATIENTViewer') {
            return $this->getViewerInitData();
        } else if ($app_name === 'ePATIENTAdmin') {
            return $this->getAdminInitData();
        }
    }

}
