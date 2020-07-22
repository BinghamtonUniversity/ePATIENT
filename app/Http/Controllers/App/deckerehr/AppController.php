<?php

namespace App\Http\Controllers\App\deckerehr;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Library;
use App\User;
use App\Team;
use App\Scenario;

class AppController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $app_name = 'DeckerEHRViewer';
    public function __construct() {
    }

    public function getInitData() {
        return [];
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
        $code_types = ['templates','scripts'];
        foreach($code_types as $type) {
            $files = ($type == 'scripts')?[
                'Main.js',
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

    public function getViewer(Request $request) {
        $init_data = $this->getInitData();
        return view('uapp_engine.main',[
            'app_name' => $this->app_name,
            'app_definition' => json_encode($this->getAppDefinition()),
            'init_data' => json_encode($init_data),
        ]);
    }

    public function home(Request $request) {
        return view('apps.decherehr.home',['user'=>Auth::user()]);
    }

}
