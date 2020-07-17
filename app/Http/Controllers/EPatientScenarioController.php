<?php

namespace App\Http\Controllers;

use App\Scenario;
use Illuminate\Http\Request;

class EPatientScenarioController extends Controller
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

    public function read(Scenario $scenario)
    {
        return $scenario;
    }

    public function edit(Request $request, Scenario $scenario)
    {
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

    public function delete(Scenario $scenario)
    {
        if ( $scenario->delete() ) {
            return [true];
        }
    }

}
