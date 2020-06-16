<?php

namespace peertxt\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use peertxt\models\Group;
use peertxt\models\GroupUser;
use peertxt\models\Company;
use peertxt\models\User;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Auth;
use Illuminate\Support\Facades\Session;
use Webpatser\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class GroupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (!Auth::user()->hasRole(['administrator', 'manager'])) {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
        }

        $companies = Company::orderBy('company_name')->where('status', 1)->get();

        return view('groups.index', ['companies' => $companies]);
    }

    public function indexTable(Request $request)
		{
			$query = Group::with('Company');

			if (Auth::user()->hasRole('manager')) {
				$query = $query->where('company_id', Auth::user()->company_id);
			}

			return Datatables::of($query)
				->editColumn('id', 'groups.id')
				->editColumn('status', function (Group $group) {
					return group_status($group->status, $group->deleted_at);
				})
				->rawColumns(['id'])
				->make(true);
		}
    public function update_users(Request $request, $id)
    {

        if (Auth::user()->hasRole('administrator')) {
            $group = Group::where('id', $id)->first();
            $company_id = $request->get('company_id');
        } elseif (Auth::user()->hasRole('manager')) {
            $group = Group::where('id', $id)->where('company_id', Auth::user()->company_id)->first();
            $company_id = Auth::user()->company_id;
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
        }

        if (!$group) {
            $request->session()->flash("status", "Group not found!");
            return redirect('/groups');
        }

        GroupUser::where('group_id', $id)->delete();

        foreach ($request->get('users') as $u) {
            $groupUser = new GroupUser();
            $groupUser->group_id = $id;
            $groupUser->user_id = $u;
            $groupUser->save();
        }

        $request->session()->flash("status", "Group users updated successfully!");
        return redirect('/groups');

    }

    public function update(Request $request, $id)
    {

        if (Auth::user()->hasRole('administrator')) {
            $group = Group::where('id', $id)->first();
            $company_id = $request->get('company_id');
        } elseif (Auth::user()->hasRole('manager')) {
            $group = Group::where('id', $id)
                ->where('company_id', Auth::user()->company_id)
                ->first();
            $company_id = Auth::user()->company_id;
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
        }

        if (!$group) {
            $request->session()->flash("status", "Group not found!");
            return redirect('/groups');
        }

        $this->validate($request, [
            'name' => 'required'
        ]);

        $group->status = $request->get('status');
        $group->company_id = $company_id;
        $group->name = $request->get('name');
        $group->description = $request->get('description');
        $group->save();

        $request->session()->flash("status", "Group updated successfully!");
        return redirect('/groups');
    }

    public function view(Request $request, $id)
    {

        if (Auth::user()->hasRole('administrator')) {
            $group = Group::where('id', $id)->first();
        } elseif (Auth::user()->hasRole('manager')) {
            $group = Group::where('id', $id)
                ->where('company_id', Auth::user()->company_id)
                ->first();
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
        }

        if (!$group) {
            $request->session()->flash("status", "Group not found!");
            return redirect('/groups');
        }

        $users = User::select('users.*')
            ->where('users.company_id', $group->company_id)
            ->orderBy('name')
            ->get();

        $selected_users = array();
        $group_users = GroupUser::select('group_users.*')
            ->where('group_users.group_id', $id)
            ->get();

        foreach ($group_users as $group_user) {
            $selected_users[] = $group_user->user_id;
        }

        $companies = Company::orderBy('company_name')->where('status', 1)->get();

        return view('groups.view', ['group' => $group, 'users' => $users, 'selected_users' => $selected_users, 'companies' => $companies]);
    }

    public function delete(Request $request, $id)
    {

        if (Auth::user()->hasRole('administrator')) {
            $group = Group::where('id', $id)->first();
        } elseif (Auth::user()->hasRole('manager')) {
            $group = Group::where('id', $id)
                ->where('company_id', Auth::user()->company_id)
                ->first();
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
        }

        if (!$group) {
            $request->session()->flash("status", "Group not found!");
            return redirect('/groups');
        }

        $group->delete();

        $request->session()->flash("status", "Group deleted successfully!");
        return redirect('/groups');
    }

    public function save(Request $request)
    {

        if (Auth::user()->hasRole('administrator')) {
            $company_id = $request->get('company_id');
        } elseif (Auth::user()->hasRole('manager')) {
            $company_id = Auth::user()->company_id;
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
            exit;
        }

        $this->validate($request, [
            'name' => 'required'
        ]);

        $group = new Group();
        $group->status = $request->get('status');
        $group->company_id = $company_id;
        $group->name = $request->get('name');
        $group->description = $request->get('description');
        $group->save();

        $request->session()->flash("status", "Group created successfully!");

        return redirect('/groups');

    }

}
