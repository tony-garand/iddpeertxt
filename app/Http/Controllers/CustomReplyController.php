<?php

namespace peertxt\Http\Controllers;

use Auth;
use peertxt\models\Company;
use peertxt\models\CustomReply;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\DataTables;

class CustomReplyController extends Controller
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

		$companies = Company::where('status', 1)->orderBy('company_name')->get();

		$fields = [
			'first_name',
			'last_name',
			'phone',
			'email',
			'company_name'
		];

		return view('customReplies.index', compact('companies', 'fields'));
	}

	public function indexTable(Request $request)
	{
		$query = CustomReply::with('Company');

		if (Auth::user()->hasRole('manager'))
			$query = $query->where('company_id', Auth::user()->company_id);

		return DataTables::of($query)
			->editColumn('id', 'customReplies.id')
			->rawColumns(['id'])
			->make(true);
	}

	public function save(Request $request)
	{
		$companyId = null;
		if ($request->has('company_id')) {
			$companyId = $request->get('company_id');
		} else {
			$companyId = Auth::user()->company_id;
		}

		$reply = new CustomReply();
		$reply->company_id = $companyId;
		$reply->reply_name = $request->get('reply_name');
		$reply->reply_body = $request->get('reply_body');
		$reply->save();

		$request->session()->flash('status', 'Custom reply saved!');
		return redirect('/customReplies');
	}

	public function view(Request $request, $id)
	{
		if (Auth::user()->hasRole('administrator')) {
			$reply = CustomReply::where('id', $id)->first();
		} elseif (Auth::user()->hasRole('manager')) {
			$reply = CustomReply::Where('id', $id)
				->where('company_id', Auth::user()->company_id)
				->first();
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
		}

		if (!$reply) {
			$request->session()->flash("status", "Custom reply not found!");
			return redirect(route('customReplies'));
		}

		$companies = Company::where('status', 1)->orderBy('company_name')->get();;

		return view('customReplies.view', compact('reply', 'companies'));
	}

	public function update(Request $request, $id)
	{
		if (Auth::user()->hasRole('administrator')) {
			$reply = CustomReply::where('id', $id)->first();
			$companyId = $request->get('company_id');
		} elseif (Auth::user()->hasRole('manager')) {
			$reply = CustomReply::Where('id', $id)
				->where('company_id', Auth::user()->company_id)
				->first();
			$companyId = Auth::user()->company_id;
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
		}

		if (!$reply) {
			$request->session()->flash("status", "Custom reply not found!");
			return redirect(route('customReplies'));
		}

		## validate any fields that are in the body
		$validator = Validator::make($request->all(), [
			'reply_body' => [
				'required',
				function ($attribute, $value, $fail) {
					$matches = preg_match_all("/\[\[(.*?)\]\]/", $value, $fields);
					if ($matches > 0) {
						$replyFields = customReplyFields();

						foreach ($fields[1] as $k=>$field) {
							if (array_key_exists($field, $replyFields) === false) {
								## invalid field
								return $fail('Body has invalid field!');
							}
						}
					}

					return true;
				}
			]
		]);

		$validator->validate();

		$reply->reply_name = $request->get('reply_name');
		$reply->reply_body = $request->get('reply_body');
		$reply->company_id = $companyId;
		$reply->save();

		$request->session()->flash('status', 'Custom reply updated!');
		return redirect(route('customReplies'));
	}
}
