<?php

namespace App\Http\Controllers\App\epatient;

use App\User;
use App\Team;
use App\TeamMember;
use App\TeamMessage;
use App\TeamNote;
use App\Scenario;
use App\TeamScenario;
use App\TeamActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TeamController extends \App\Http\Controllers\TeamController
{
    public function __construct()
    {
        //
    }

    public function activity(Request $request,Team $team,$last_activity_id=null)
    {
        if (!is_null($last_activity_id) && $last_activity_id != '') {
            $ids = json_decode(base64_decode($last_activity_id));
            $activity = TeamActivityLog::where('team_id',$team->id)
                ->where('id','>',$ids->activity)
                ->orderBy('id', 'asc')->with('user')->get();
            $messages = TeamMessage::where('team_id',$team->id)
                ->where('id','>',$ids->messages)
                ->orderBy('id', 'asc')->with('user')->get();
            $notes = TeamNote::where('team_id',$team->id)
                ->where('id','>',$ids->notes)
                ->orderBy('id', 'asc')->get();
        } else {
            $ids = (Object) ['activity'=>0,'messages'=>0,'notes'=>0];
            $activity = TeamActivityLog::where('team_id',$team->id)->with('user')->orderBy('id', 'asc')->get();
            $messages = TeamMessage::where('team_id',$team->id)->with('user')->orderBy('id', 'asc')->get();
            $notes = TeamNote::where('team_id',$team->id)->with('user')->orderBy('id', 'asc')->get();
        }
        $ids = [
            'activity'=>!count($activity)?$ids->activity:$activity->last()->id,
            'messages'=>!count($messages)?$ids->messages:$messages->last()->id,
            'notes'=>!count($notes)?$ids->notes:$notes->last()->id
        ];
        return [
            'last_activity_id' => base64_encode(json_encode($ids)),
            'activity' => $activity,
            'messages' => $messages,
            'notes' => $notes,
        ];
    }

    public function add_activity_log(Request $request, Team $team)
    {
        $team_activity_log = new TeamActivityLog($request->all());
        $team_activity_log->user_id = Auth::user()->id;
        $team_activity_log->team_id = $team->id;
        $team_activity_log->save();
        return TeamActivityLog::where('id',$team_activity_log->id)->with('user')->first();
    }

    public function list_scenario_logs(Team $team)
    {
        return TeamScenario::where('team_id',$team->id)->with('user')->get();
    }

    public function add_scenario_log(Request $request, Team $team)
    {
        $user = Auth::user();

        $team_scenario = new TeamScenario(['user_id'=>$user->id,'team_id'=>$team->id,'state'=>$request->state]);
        $team_scenario->save();
        $team->team_scenario_id = $team_scenario->id;
        $team->save();
        return $team_scenario;
    }
}
