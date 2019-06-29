<?php

namespace App\Http\Controllers;

use App\Scenario;
use Illuminate\Http\Request;

class ScenarioController extends Controller
{
    public function __construct()
    {
        //
    }

    public function browse()
    {
        $scenarios = Scenario::all();
        return $scenarios;
    }

    public function read($scenario_id)
    {
        $scenario = Scenario::where('id',$scenario_id)->first();
        if (!is_null($scenario)) {
            return $scenario;
        } else {
            return response('scenario_id not found', 404);
        }
    }

    public function edit(Request $request, $scenario_id)
    {
        $scenario = Scenario::where('id',$scenario_id)->first();
        $scenario->update($request->all());
        return $scenario;
    }

    public function add(Request $request)
    {
        $this->validate($request,['name'=>['required']]);
        $scenario = new Scenario($request->all());
        $scenario->save();
        return $scenario;
    }

    public function delete($scenario_id)
    {
        if ( Scenario::where('id',$scenario_id)->delete() ) {
            return [true];
        }
    }

}
