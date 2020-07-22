<?php

namespace App\Http\Controllers\App\epatient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Library;
use App\User;
use App\Team;
use App\Scenario;

class AppController extends Controller
{
    private $app_name = 'ePATIENTViewer';

    public function __construct() {
    }

    public function getInitData() {
        $libraries = ['labs','solutions','additives','products','prescribers'];
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
        $user = Auth::user();
        $response['user'] = $user;

        /* Get Teams */
        $teams = Team::whereHas('team_members',function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['scenario'=>function($query){
            $query->select('id','name');
        }])->with(['team_members'=>function($query) use ($user) {
            $query->where('user_id',$user->id);
        }])->get();
        $response['myteams'] = $teams;

        /* Get Options */
        // $response['options'] = ['admin'=>false];
        $response['admin'] = false;

        return $response;
    }

    private function getAppDefinition() {
        $app_name = $this->app_name;
        $app_data = [
            'id'=>$app_name,
            'name'=>$app_name,
            'slug'=>$app_name,
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
            $files = ($type == 'scripts')?[
                'Main.js',
                'init.js'
                ]:array_diff(scandir(public_path($app_name.'/'.$type)),['..', '.']);
            foreach($files as $filename) {
                $app_data['app']['code'][$type][] = [
                    'name'=>pathinfo($filename, PATHINFO_FILENAME),
                    'content' => file_get_contents(public_path($app_name.'/'.$type.'/'.$filename)),
                ];
            }
        }
        return $app_data;
    }

    public function getViewer(Request $request, $team_id) {
        $init_data = $this->getInitData();
        $init_data['team_id'] = $team_id;
        return view('uapp_engine.main',[
            'app_name' => $this->app_name,
            'app_definition' => json_encode($this->getAppDefinition()),
            'init_data' => json_encode($init_data),
        ]);
    }
    public function getTeamConfig(Request $request, $team_id) {
        $init_data = $this->getInitData();
        $init_data['team_id'] = $team_id;
        $init_data['admin'] = true;
        return view('uapp_engine.main',[
            'app_definition' => json_encode($this->getAppDefinition()),
            'init_data' => json_encode($init_data),
        ]);
    }

    public function getScenarioConfig(Request $request, $scenario_id) {
        $init_data = $this->getInitData();
        $init_data['scenario_id'] = $scenario_id;
        $init_data['admin'] = true;
        return view('uapp_engine.main',[
            'app_definition' => json_encode($this->getAppDefinition()),
            'init_data' => json_encode($init_data),
        ]);
    }

    public function home(Request $request) {
        $teams = Team::whereHas('team_members',function($query) {
            $query->where('user_id', Auth::user()->id);
        })->with(['scenario'=>function($query){
            $query->select('id','name');
        }])->get();
        // dd($teams);
        return view('apps.epatient.home',['teams'=>$teams,'user'=>Auth::user()]);
    }

}
