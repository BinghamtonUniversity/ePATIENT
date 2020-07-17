<?php

namespace App\Http\Controllers;

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

class TeamController extends Controller
{
    public function __construct()
    {
        //
    }

    public function browse()
    {
        if (isset(Auth::user()->permissions['manage_teams'])) {
            $teams = Team::all();
            return $teams;
        } else {
            $teams = Team::whereHas('team_members',function($query) {
                $query->where('user_id',Auth::user()->id)->where('admin',true);
            })->get();
        }
        return $teams;
    }

    public function read(Team $team)
    {
        return Team::where('id',$team->id)
            ->with('scenario')
            ->with('team_scenario')
            ->with('team_members.user')
            ->first();
    }

    public function edit(Request $request, Team $team)
    {
        $team->update($request->all());
        return $team;
    }

    public function add(Request $request)
    {
        $team = new Team($request->all());

        
        $team->save();
        $team_scenario = new TeamScenario(['team_id'=>$team->id,'user_id'=>Auth::user()->id,'state'=>Scenario::find($request->scenario_id)->scenario]);
        $team_scenario->save();
        $team->team_scenario_id = $team_scenario->id;

        $team->save();
        return $team;
    }

    public function delete(Team $team)
    {
        if ( Team::where('id',$team->id)->delete() ) {
            return [true];
        }
    }

    public function list_members(Team $team)
    {
        return TeamMember::where('team_id',$team->id)->with('user')->get();
    }

    public function add_member(Request $request, Team $team, User $user)
    {
        $team_member = new TeamMember(['user_id'=>$user->id,'team_id'=>$team->id]);
        if ($request->has('role_id')) {
            $team_member->role_id = $request->role_id;
        }
        if ($request->has('admin')) {
            if ($request->admin === true || $request->admin === 'true') {
                $team_member->admin = true;
            } else {
                $team_member->admin = false;
            }
        }
        $team_member->save();
        return TeamMember::where('team_id',$team->id)->where('user_id',$user->id)->with('user')->first();
    }

    public function remove_member(Request $request, Team $team, $user_id)
    {
        if ( TeamMember::where('team_id',$team->id)->where('user_id',$user_id)->delete() ) {
            return [true];
        }
    }

    public function update_member(Request $request, Team $team, User $user)
    {
        $this->remove_member($request, $team, $user->id);
        return $this->add_member($request, $team, $user);
    }

    public function list_messages(Team $team)
    {
        return  TeamMessage::where('team_id',$team->id)->with('user')->get();
    }

    public function add_message(Request $request, Team $team)
    {
        $this->validate($request,['message'=>['required']]);
        $user = Auth::user();

        $team_message = new TeamMessage(['user_id'=>$user->id,'team_id'=>$team->id,'message'=>$request->message]);
        $team_message->save();
        return TeamMessage::where('id',$team_message->id)->with('user')->first();
    }

    public function remove_message(Team $team, TeamMessage $message)
    {
        if ( $message->delete() ) {
            return [true];
        }
    }

    public function list_notes(Team $team)
    {
        return TeamNote::where('team_id',$team->id)->with('user')->get();
    }

    public function add_note(Request $request, Team $team)
    {
        $this->validate($request,['note'=>['required']]);
        $user = Auth::user();

        $team_note = new TeamNote(['user_id'=>$user->id,'team_id'=>$team->id,'note'=>$request->note]);
        $team_note->save();
        return TeamNote::where('id',$team_note->id)->with('user')->first();
    }

    public function remove_note(Team $team, TeamNote $note)
    {
        if ( $note->delete() ) {
            return [true];
        }
    }

    public function list_activity_log(Team $team)
    {
        return TeamActivityLog::where('team_id',$team->id)->with('user')->get();
    }

}
