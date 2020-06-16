<?php

namespace peertxt\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use peertxt\Mail\UserCreatedMail;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use peertxt\models\User;
use Auth;
use peertxt\models\Company;
use Spatie\Permission\Models\Role;
use Webpatser\Uuid\Uuid;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['verifyUser', 'verifyUserTos']);
    }

    public function index(Request $request)
    {

        if (!Auth::user()->hasRole(['administrator', 'manager'])) {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
        }

        if (Auth::user()->hasRole('administrator')) {
            $roles = Role::pluck('name', 'name')->all();
        } else {
            $roles = Role::where('id', '>', 1)->pluck('name', 'name')->all();
        }
        $companies = Company::with('parent_company')->orderBy('company_name')->get();


        return view('users.index', ['roles' => $roles, 'companies' => $companies]);
    }

    public function indexTable(Request $request)
    {
        $query = User::with('Company');

        if (Auth::user()->hasRole('manager')) {
            $query = $query->where('company_id', Auth::user()->company_id);
        }

        return Datatables::of($query)
            ->editColumn('id', 'users.id')
            ->editColumn('roles', function (User $user) {
                $roles = "";

                foreach ($user->roles as $role) {
                    $roles .= $role->name . " ";
                }

                return trim($roles);
            })
            ->editColumn('status', function (User $user) {
                return user_status($user->status, $user->deleted_at);
            })
            ->rawColumns(['id', 'roles'])
            ->make(true);
    }

    public function update(Request $request, $id)
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

        $user = User::where('id', '=', $id)->first();

        $this->validate($request, [
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'name' => 'required'
        ]);

        $role = $request->get('role_id');
        if (!Auth::user()->hasRole('administrator')) {
            if ($role == 'administrator') {
                $request->session()->flash("status", "Access not allowed!");
                return redirect('/');
            }
        }

        $user->roles()->sync([]);
        $user->assignRole($role);

        $status = $request->get('status');
        $previous_status = $user->status;
        $uuid = $user->uuid;
        if (!$uuid) {
            $uuid = Uuid::generate()->string;
        }

        DB::table('users')
            ->where('id', $id)
            ->update([
                'uuid' => $uuid,
                'company_id' => $company_id,
                'status' => $status,
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
            ]);

        if ($previous_status != User::StatusVerify && $status == User::StatusVerify) {
            Mail::to($user->email)->queue(new UserCreatedMail($user));
        }

        if ($request->get('password')) {
            DB::table('users')
                ->where('id', $id)
                ->update([
                    'password' => bcrypt($request->get('password')),
                ]);
        }

        if ($request->has('clickReply')) {
            $clickReply = $request->get('clickReply');

            switch (strtolower($clickReply)) {
                case "click":
                    $user->revokePermissionTo('reply');
                    $user->givePermissionTo('click');
                    break;
                case "reply":
                    $user->revokePermissionTo('click');
                    $user->givePermissionTo('reply');
                    break;
                default:
                    $user->revokePermissionTo('click');
                    $user->revokePermissionTo('reply');
                    break;
            }
        }
        $request->session()->flash("status", "User updated successfully!");
        return redirect('/users');
    }

    public function view(Request $request, $id)
    {

        if (Auth::user()->hasRole('administrator')) {
            $user = User::find($id);
        } elseif (Auth::user()->hasRole('manager')) {
            $user = User::where('id', $id)->where('company_id', Auth::user()->company_id)->whereNull('deleted_at')->first();
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
            exit;
        }

        if (!$user) {
            $request->session()->flash("status", "User not found!");
            return redirect('/users');
        }

        if (Auth::user()->hasRole('administrator')) {
            $roles = Role::pluck('name', 'name')->all();
        } else {
            $roles = Role::where('id', '>', 1)->pluck('name', 'name')->all();
        }
        $userRole = $user->roles->pluck('name', 'name')->first();
        $companies = Company::with('parent_company')->orderBy('company_name')->get();
        return view('users.view', ['user' => $user, 'roles' => $roles, 'companies' => $companies, 'userRole' => $userRole]);
    }

    public function profile()
    {
        $user = DB::table('users')->where('id', Auth::id())->first();
        return view('users.profile', ['user' => $user]);
    }


    public function profile_update(Request $request)
    {

        $user = User::where('id', '=', Auth::id())->first();

        $this->validate($request, [
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'name' => 'required'
        ]);

        DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
            ]);

        if ($request->get('password')) {
            DB::table('users')
                ->where('id', Auth::id())
                ->update([
                    'password' => bcrypt($request->get('password')),
                ]);
        }

        $request->session()->flash("status", "Profile updated successfully!");
        return redirect('/user_profile');
    }

    public function delete(Request $request, $id)
    {

        if (Auth::user()->hasRole('administrator')) {
            $user = DB::table('users')->where('id', $id)->first();
        } elseif (Auth::user()->hasRole('manager')) {
            $user = DB::table('users')->where('id', $id)->where('company_id', Auth::user()->company_id)->whereNull('deleted_at')->first();
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
            exit;
        }

        if (!$user) {
            $request->session()->flash("status", "User not found!");
            return redirect('/users');
        }

        if ($id > 1) {
            DB::table('users')
                ->where('id', $id)
                ->update([
                        'status' => 0,
                        'deleted_at' => \Carbon\Carbon::now()->toDateTimeString()
                    ]
                );
        }

        $request->session()->flash("status", "User deleted successfully!");
        return redirect('/users');
    }

    public function undelete(Request $request, $id)
    {
        if (Auth::user()->hasRole('administrator')) {
            $user = DB::table('users')->where('id', $id)->first();
        } else {
            $request->session()->flash("status", "Access not allowed!");
            return redirect('/');
            exit;
        }

        if (!$user) {
            $request->session()->flash("status", "User not found!");
            return redirect('/users');
        }

        DB::table('users')
            ->where('id', $id)
            ->update([
                    'deleted_at' => null
                ]
            );

        $request->session()->flash("status", "User undeleted successfully!");
        return redirect('/users');
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
            'email' => 'required|email|max:255|unique:users',
            'name' => 'required'
        ]);

        $role = $request->get('role_id');
        if (!Auth::user()->hasRole('administrator')) {
            if ($role == 'administrator') {
                $request->session()->flash("status", "Access not allowed!");
                return redirect('/');
                exit;
            }
        }

        $status = $request->input('status');
        if (is_null($request->input('status'))) {
            $status = User::StatusVerify;
        }

        $user_id = DB::table('users')->insertGetId(
            [
                'uuid' => Uuid::generate()->string,
                'company_id' => $company_id,
                'status' => $status,
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
            ]
        );

        if ($user_id) {
            $user = User::where('id', '=', $user_id)->first();
            $user->assignRole($role);
            Mail::to($user->email)->queue(new UserCreatedMail($user));
            $request->session()->flash("status", "User created successfully!");
            return redirect('/users');
        } else {
            $request->session()->flash("status", "Something bad happened!");
            return redirect('/');
        }

    }


    public function verifyUser(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        if (!$user || $user->status != User::StatusVerify) {
            if (Auth::check()) {
                return redirect()->route('user.profile');
            }
            return redirect('login');
        }


        Auth::logout();

        return view('auth.verify', ['user' => $user]);
    }


    public function verifyUserTos(Request $request, $uuid)
    {
        $this->validate($request, [
            'accept_tos' => 'required'
        ], [
            'accept_tos.required' => 'You need to accept User Terms / User Agreement before proceeding'
        ]);

        $user = User::where('uuid', $uuid)->first();
        if (!$user || $user->status != User::StatusVerify) {
            if (Auth::check()) {
                return redirect()->route('user.profile');
            }
            return redirect('login');
        }

        $user->status = User::StatusActive;
        $user->save();

        Auth::logout();
        Auth::loginUsingId($user->id);
        session(['user_brand' => 'peertxt']);

        return redirect()->route('user.update.password')->with('quick-password-update', true);
    }

    public function passwordUpdate(Request $request)
    {
        $quickPasswordUpdate = $request->session()->get('quick-password-update', false);
        $user = User::where('id', '=', Auth::id())->first();
        if (!$user)
            abort(404);

        if (!$quickPasswordUpdate) {
            return redirect()->route('user.profile');
        }

        return view('users.update_password', ['user' => $user]);
    }

    public function postPasswordUpdate(Request $request)
    {
        $request->session()->flash('quick-password-update', true);
        $user = User::where('id', '=', Auth::id())->first();
        if (!$user)
            abort(404);

        if ($request->input('password')) {
            DB::table('users')
                ->where('id', Auth::id())
                ->update([
                    'password' => bcrypt($request->get('password')),
                ]);
            $request->session()->flash("status", "Password updated successfully!");
        }

        return redirect('/user_profile');
    }
}
