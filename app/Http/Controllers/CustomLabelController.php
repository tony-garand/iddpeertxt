<?php

namespace peertxt\Http\Controllers;

use Auth;
use peertxt\models\Company;
use peertxt\models\CustomLabel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CustomLabelController extends Controller
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

		return view('customLabels.index', ['companies' => $companies]);
	}

	public function indexTable(Request $request)
	{
		$query = CustomLabel::with('Company');

		if (Auth::user()->hasRole('manager'))
			$query = $query->where('company_id', Auth::user()->company_id);

		return DataTables::of($query)
			->editColumn('id', 'customLabels.id')
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

		$label = new CustomLabel();
		$label->company_id = $companyId;
		$label->label = $request->get('label');
		$label->save();

		$request->session()->flash('status', 'Custom label saved!');
		return redirect('/customLabels');
	}

	public function view(Request $request, $id)
	{
		if (Auth::user()->hasRole('administrator')) {
			$label = CustomLabel::where('id', $id)->first();
		} elseif (Auth::user()->hasRole('manager')) {
			$label = CustomLabel::Where('id', $id)
				->where('company_id', Auth::user()->company_id)
				->first();
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
		}

		if (!$label) {
			$request->session()->flash("status", "Custom label not found!");
			return redirect('/contacts/labels');
		}

		$companies = Company::where('status', 1)->orderBy('company_name')->get();;

		return view('customLabels.view', ['label' => $label, 'companies' => $companies]);
	}

	public function update(Request $request, $id)
	{
		if (Auth::user()->hasRole('administrator')) {
			$label = CustomLabel::where('id', $id)->first();
			$companyId = $request->get('company_id');
		} elseif (Auth::user()->hasRole('manager')) {
			$label = CustomLabel::Where('id', $id)
				->where('company_id', Auth::user()->company_id)
				->first();
			$companyId = Auth::user()->company_id;
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
		}

		if (!$label) {
			$request->session()->flash("status", "Custom label not found!");
			return redirect('/customLabels');
		}

		$label->label = $request->get('label');
		$label->company_id = $companyId;
		$label->save();

		$request->session()->flash('status', 'Custom label updated!');
		return redirect('/customLabels');
	}
}
