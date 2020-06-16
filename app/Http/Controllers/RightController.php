<?php

namespace peertxt\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use peertxt\models\Right;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Auth;
use Illuminate\Support\Facades\Session;
use Webpatser\Uuid\Uuid;

class RightController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}

	public function index(Request $request) {

		if (Auth::user()->hasRole('administrator')) {
			$rights = Right::with('Company')
						->orderBy('name')
						->get();
		} elseif (Auth::user()->hasRole('manager')) {
			$rights = Right::with('Company')
						->where('rights.company_id', Auth::user()->company_id)
						->orderBy('name')
						->get();
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
			exit;
		}

		$companies = DB::table('companies')->orderBy('company_name')->where('status', 1)->get();
		return view('rights.index', ['rights' => $rights, 'companies' => $companies]);
	}

	public function update_groups(Request $request, $id) {

		if (Auth::user()->hasRole('administrator')) {
			$right = DB::table('rights')->where('id', $id)->first();
			$company_id = $request->get('company_id');
		} elseif (Auth::user()->hasRole('manager')) {
			$right = DB::table('rights')->where('id', $id)->where('company_id', Auth::user()->company_id)->whereNull('deleted_at')->first();
			$company_id = Auth::user()->company_id;
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
			exit;
		}

		if (!$right) {
			$request->session()->flash("status", "Right not found!");
			return redirect('/rights');
		}

		DB::table('right_groups')->where('right_id', $id)->delete();

		foreach ($request->get('groups') as $g) {
			$right_group_id = DB::table('right_groups')->insertGetId(
				[
					'right_id' => $id,
					'group_id' => $g,
					'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
					'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
				]
			);
		}

		$request->session()->flash("status", "Right groups updated successfully!");
		return redirect('/rights');

	}

	public function update_users(Request $request, $id) {

		if (Auth::user()->hasRole('administrator')) {
			$right = DB::table('rights')->where('id', $id)->first();
			$company_id = $request->get('company_id');
		} elseif (Auth::user()->hasRole('manager')) {
			$right = DB::table('rights')->where('id', $id)->where('company_id', Auth::user()->company_id)->whereNull('deleted_at')->first();
			$company_id = Auth::user()->company_id;
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
			exit;
		}

		if (!$right) {
			$request->session()->flash("status", "Right not found!");
			return redirect('/rights');
		}

		DB::table('right_users')->where('right_id', $id)->delete();

		foreach ($request->get('users') as $u) {
			$right_user_id = DB::table('right_users')->insertGetId(
				[
					'right_id' => $id,
					'user_id' => $u,
					'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
					'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
				]
			);
		}

		$request->session()->flash("status", "Right users updated successfully!");
		return redirect('/rights');

	}

	public function update(Request $request, $id) {

		if (Auth::user()->hasRole('administrator')) {
			$right = DB::table('rights')->where('id', $id)->first();
			$company_id = $request->get('company_id');
		} elseif (Auth::user()->hasRole('manager')) {
			$right = DB::table('rights')->where('id', $id)->where('company_id', Auth::user()->company_id)->whereNull('deleted_at')->first();
			$company_id = Auth::user()->company_id;
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
			exit;
		}

		if (!$right) {
			$request->session()->flash("status", "Right not found!");
			return redirect('/rights');
		}

		$this->validate($request, [
			'name' => 'required'
		]);

		DB::table('rights')
			->where('id', $id)
			->update([
				'status' => $request->get('status'),
				'company_id' => $company_id,
				'name' => $request->get('name'),
				'description' => $request->get('description'),
				'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
		]);

		$request->session()->flash("status", "Right updated successfully!");
		return redirect('/rights');
	}

	public function view(Request $request, $id) {

		if (Auth::user()->hasRole('administrator')) {
			$right = DB::table('rights')->where('id', $id)->first();
		} elseif (Auth::user()->hasRole('manager')) {
			$right = DB::table('rights')->where('id', $id)->where('company_id', Auth::user()->company_id)->whereNull('deleted_at')->first();
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
			exit;
		}

		if (!$right) {
			$request->session()->flash("status", "Right not found!");
			return redirect('/rights');
		}

		$users = DB::table('users')
					->select('users.*')
					->where('users.company_id', $right->company_id)
					->whereNull('deleted_at')
					->orderBy('name')
					->get();

		$groups = DB::table('groups')
					->select('groups.*')
					->where('groups.company_id', $right->company_id)
					->whereNull('deleted_at')
					->orderBy('name')
					->get();

		$selected_users = array();
		$right_users = DB::table('right_users')->select('right_users.*')->where('right_users.right_id', $id)->get();
		foreach ($right_users as $right_user) {
			$selected_users[] = $right_user->user_id;
		}

		$selected_groups = array();
		$right_groups = DB::table('right_groups')->select('right_groups.*')->where('right_groups.right_id', $id)->get();
		foreach ($right_groups as $right_group) {
			$selected_groups[] = $right_group->group_id;
		}

		$companies = DB::table('companies')->orderBy('company_name')->where('status', 1)->get();
		return view('rights.view', ['right' => $right, 'users' => $users, 'groups' => $groups, 'selected_users' => $selected_users, 'selected_groups' => $selected_groups, 'companies' => $companies]);
	}

	public function delete(Request $request, $id) {

		if (Auth::user()->hasRole('administrator')) {
			$right = DB::table('rights')->where('id', $id)->first();
		} elseif (Auth::user()->hasRole('manager')) {
			$right = DB::table('rights')->where('id', $id)->where('company_id', Auth::user()->company_id)->whereNull('deleted_at')->first();
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
			exit;
		}

		if (!$right) {
			$request->session()->flash("status", "Right not found!");
			return redirect('/rights');
		}

		DB::table('rights')
			->where('id', $id)
			->update([
				'status' => 0,
				'deleted_at' => \Carbon\Carbon::now()->toDateTimeString()
			]
		);

		$request->session()->flash("status", "Right deleted successfully!");
		return redirect('/rights');
	}

	public function save(Request $request) {

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

		$right_id = DB::table('rights')->insertGetId(
			[
				'status' => $request->get('status'),
				'company_id' => $company_id,
				'name' => $request->get('name'),
				'description' => $request->get('description'),
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
				'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
			]
		);

		$request->session()->flash("status", "Right created successfully!");
		return redirect('/rights');

	}

}
