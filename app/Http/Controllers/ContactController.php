<?php

namespace peertxt\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Log;
use peertxt\Jobs\ProcessContactImport;
use peertxt\Jobs\VerifyNumber;
use peertxt\models\CampaignContact;
use peertxt\models\Company;
use peertxt\models\Contact;
use peertxt\models\CustomLabel;
use peertxt\models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Integer;
use Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Auth;
use Illuminate\Support\Facades\Session;
use Webpatser\Uuid\Uuid;
use League\Csv\Reader;
use Yajra\DataTables\DataTables;

class ContactController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(Request $request)
	{
		$states = state_strings();
		$companies = Company::orderBy('company_name')->where('status', 1)->get();

		if (Auth::user()->hasRole('administrator')) {
			$tags = Tag::distinct('name')
				->pluck('name');
		} else {
			$tags = Tag::where('type', 'company' . Auth::user()->company_id)
				->distinct('name')
				->pluck('name');
		}

		return view('contacts.index', ['companies' => $companies, 'states' => $states, 'tags' => $tags]);
	}

	public function indexTable(Request $request)
	{
		$query = Contact::with('Company.parent_company');

		if (Auth::user()->hasRole('manager')) {
			$companyIds = Company::getCompanyIds(Auth::user()->company_id);
			$query = $query->whereIn('contacts.company_id', $companyIds);
		}

		if ($request->has('tagFilter')) {
			$tagFilter = $request->get('tagFilter');

			if ($tagFilter != '') {
				$query = $query->withAllTags($tagFilter);
			}
		}
		return Datatables::of($query)
			->editColumn('id', 'contacts.id')
			->rawColumns(['id'])
			->make(true);
	}

	public function import(Request $request)
	{
		if (!Auth::user()->hasRole(['administrator', 'manager'])) {
			$request->session()->flash("status", "Access not permitted.");
			return redirect('/');
		}

		if ($request->isMethod('POST')) {
			$this->validate($request, [
				'file_upload' => 'required'
			]);

			if (@$request->hasFile('file_upload')) {
				## save the file
				$filename = uniqid();
				$file = $request->file('file_upload');
				$filePath = 'imports/' . $filename;
				Storage::disk('s3')->put($filePath, file_get_contents($file));

				try {
					## open the uploaded CSV file\
					$reader = Reader::createFromString(Storage::disk('s3')->get($filePath));

					## get the header record
					$reader->setHeaderOffset(0);
					$header = $reader->getHeader();

					## validate the header
					foreach ($header as $k=>$v) {
						if ($v === "") {
							$request->session()->flash("status", "Invalid header record!");
							return redirect('/contacts');
						}
					}

					## get the column count
					$columnCount = count($reader->fetchOne());

					## process the file records
					$records = $reader->getRecords();

					## get the first 5 records for display
					$count = 0;
					foreach ($records as $offset => $record) {
						$count++;

						$sample[] = $record;
						if ($count >= 5)
							break;
					}

					## fields that are available to fill in the contacts table
					$contactFields = [
						'none' => '',
						'first_name' => 'First Name',
						'last_name' => 'Last Name',
						'phone' => 'Phone #',
						'email' => 'Email',
						'address1' => 'Address 1',
						'address2' => 'Address 2',
						'city' => 'City',
						'state' => 'State',
						'zip' => 'Zip'
					];

					$companyId = null;
					if ($request->has('company_id')) {
						$companyId = $request->get('company_id');
					} else {
						$companyId = Auth::user()->company_id;
					}

					$tags = $request->get('tags');

					## custom fields
					$customFields = CustomLabel::where('company_id', $companyId)->pluck('label', 'id');
					$customFields->prepend('', 'none');
					$customFields->put('new', 'New Field');

					return view('contacts.import', [
						'filename' => $filePath,
						'companyId' => $companyId,
						'sample' => $sample,
						'contactFields' => $contactFields,
						'customFields' => $customFields,
						'columnCount' => $columnCount,
						'tags' => $tags
					]);
				} catch (\Exception $e) {
					$request->session()->flash("status", "Error processing import file!");
					Log::error("Error processing contact import file; " . $e->getMessage() . "; " . $e->getLine());
					return redirect('/contacts');
				}

			}
		}
	}

	public function importFinish(Request $request)
	{
		if (!Auth::user()->hasRole(['administrator', 'manager'])) {
			$request->session()->flash("status", "Access not permitted.");
			return redirect('/');
		}

		if ($request->has('filename') && Storage::disk('s3')->exists($request->get('filename'))) {
			$filename = $request->get('filename');
			$columnCount = $request->get('columnCount');
			$companyId = $request->get('companyId');
			$tags = $request->get('tags');

			$validContacts = 0;

			$fieldMap = [];

			for ($column = 1; $column <= $columnCount; $column++) {
				if ($request->has('contact_field_' . $column)) {
					$contactField = $request->get('contact_field_' . $column);

					if ($contactField === 'none') {
						if ($request->has('custom_field_' . $column)) {
							$customField = $request->get('custom_field_' . $column);

							if ($customField === 'new') {
								if ($request->has('new_field_' . $column)) {
									$newField = $request->get('new_field_' . $column);

									## create the new field
									$label = new CustomLabel();
									$label->company_id = $request->get('companyId');
									$label->label = $newField;
									$label->save();

									$fieldMap[$column] = 'custom_' . $label->id;
								} else {
									## TODO: this shouldn't occur; handle it if it does
								}
							} else {
								if ($customField != 'none')
									$fieldMap[$column] = 'custom_' . $customField;
								else
									$fieldMap[$column] = 'skip';
							}
						} else {
							## TODO: this shouldn't occur; handle it if it does
						}
					} else {
						$fieldMap[$column] = $contactField;
					}
				} else {
					## TODO: if this happens there is something wrong with the data; handle this
				}
			}

			if (is_null($tags))
				$tags = "";

			if (count($fieldMap) > 0) {
				ProcessContactImport::dispatch(Auth::user(), $companyId, $filename, $fieldMap, $tags);
			}
		} else {
			## TODO: if this happens there is something wrong with the data; handle this
		}

		$request->session()->flash("status", "Contacts import queued!");
		return redirect()->route('contacts');
	}

	public function update(Request $request, $id)
	{

		if (Auth::user()->hasRole('administrator')) {
			$contact = Contact::where('id', $id)->first();
			$company_id = $request->get('company_id');
		} elseif (Auth::user()->hasRole('manager')) {
			$companyIds = Company::getCompanyIds(Auth::user()->company_id);
			$contact = Contact::where('id', $id)
				->whereIn('company_id', $companyIds)
				->first();
			$company_id = Auth::user()->company_id;
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
		}

		if (!$contact) {
			$request->session()->flash("status", "Contact not found!");
			return redirect('/contacts');
		}

		$phone = str_replace('-', '', filter_var($request->get('phone'), FILTER_SANITIZE_NUMBER_INT));
		$stripped_phone = str_replace("+1", "", $phone);
		$stripped_phone = str_replace("+", "", $stripped_phone);
		$stripped_phone = ltrim($stripped_phone, '0');
		$stripped_phone = ltrim($stripped_phone, '1');
		$stripped_phone = trim(preg_replace("/[^0-9]/", "", $stripped_phone));

		$this->validate($request, [
			'phone' => 'required'
		]);

		$contact->status = $request->get('status');
		if (!$contact->company_id)
			$contact->company_id = $company_id;
		$contact->first_name = $request->get('first_name');
		$contact->last_name = $request->get('last_name');
		if ($stripped_phone != $contact->phone) {
			$contact->verified_phone = Contact::VerifiedPhoneNo;
            $contacts = (new Collection())->push($contact);
            $this->dispatch(new VerifyNumber(Auth::user(), $contacts));
		}
		$contact->phone = $stripped_phone;
		$contact->email = $request->get('email');
		$contact->address1 = $request->get('address1');
		$contact->address2 = $request->get('address2');
		$contact->city = $request->get('city');
		$contact->state = $request->get('state');
		$contact->zip = $request->get('zip');
		$contact->url = $request->get('url');
		$contact->save();

		if ($request->has('tags') && $request->get('tags') != null) {
			$contact->syncTags(explode(',', $request->get('tags')));

			## sync the tags with a type of the current Company ID
			$contact->syncTagsWithType(explode(',', $request->get('tags')), 'company' . $company_id);
		} else {
			## remove all tags
			$contact->syncTags([]);
		}

		contactAction('UPDATED', $contact->id);

		$request->session()->flash("status", "Contact updated successfully!");
		return redirect('/contacts');
	}

	public function view(Request $request, $id)
	{

		if (Auth::user()->hasRole('administrator')) {
			$contact = Contact::where('id', $id)->first();
		} elseif (Auth::user()->hasRole('manager')) {
			$companyIds = Company::getCompanyIds(Auth::user()->company_id);
			$contact = Contact::where('id', $id)
				->whereIn('company_id', $companyIds)
				->first();
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
		}

		if (!$contact) {
			$request->session()->flash("status", "Contact not found!");
			return redirect('/contacts');
		}

		$states = state_strings();
		$companies = Company::where('status', 1)
			->orderBy('company_name')
			->get();

		$campaigns = CampaignContact::where('contact_id', $id)->get();

		return view('contacts.view', compact('contact', 'states', 'companies', 'campaigns'));
	}

	public function undelete(Request $request, $id)
	{
		if (Auth::user()->hasRole('administrator')) {
			$contact = DB::table('contacts')->where('id', $id)->first();
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
			exit;
		}

		if (!$contact) {
			$request->session()->flash("status", "Contact not found!");
			return redirect('/contacts');
		}

		DB::table('contacts')
			->where('id', $id)
			->update([
					'deleted_at' => null
				]
			);

		$request->session()->flash("status", "Contact undeleted successfully!");
		return redirect('/contacts');
	}

	public function delete(Request $request, $id)
	{

		if (Auth::user()->hasRole('administrator')) {
			$contact = Contact::where('id', $id)->first();
		} elseif (Auth::user()->hasRole('manager')) {
			$companyIds = Company::getCompanyIds(Auth::user()->company_id);
			$contact = Contact::where('id', $id)
				->whereIn('company_id', $companyIds)
				->first();
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
		}

		if (!$contact) {
			$request->session()->flash("status", "Contact not found!");
			return redirect('/contacts');
		}

		CampaignContact::where('contact_id', $id)
			->update(['cc_status' => -1]);

		$contact->status = 0;
		$contact->save();
		$contact->delete();

		contactAction('DELETED', $contact->id);

		$request->session()->flash("status", "Contact deleted successfully!");
		return redirect('/contacts');
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

		$phone = str_replace('-', '', filter_var($request->get('phone'), FILTER_SANITIZE_NUMBER_INT));
		$stripped_phone = str_replace("+1", "", $phone);
		$stripped_phone = str_replace("+", "", $stripped_phone);
		$stripped_phone = ltrim($stripped_phone, '0');
		$stripped_phone = ltrim($stripped_phone, '1');
		$stripped_phone = trim(preg_replace("/[^0-9]/", "", $stripped_phone));

		$contact_check = DB::table('contacts')->where('company_id', $company_id)->where('phone', $stripped_phone)->whereNull('deleted_at')->first();
		if ($contact_check) {
			$request->session()->flash("status", "Contact already exists!");
			return redirect('/contacts');
			exit;
		}

		$this->validate($request, [
			'phone' => 'required'
		]);

		$contact_uuid = Uuid::generate()->string;

		$contact = new Contact();
		$contact->uuid = $contact_uuid;
		$contact->status = $request->get('status');
		$contact->company_id = $company_id;
		$contact->first_name = $request->get('first_name');
		$contact->last_name = $request->get('last_name');
		$contact->phone = $stripped_phone;
		$contact->email = $request->get('email');
		$contact->address1 = $request->get('address1');
		$contact->address2 = $request->get('address2');
		$contact->city = $request->get('city');
		$contact->state = $request->get('state');
		$contact->zip = $request->get('zip');
        $contact->verified_phone = Contact::VerifiedPhoneNo;
		$contact->url = $request->get('url');
		$contact->tags = explode(",", $request->get('tags'));
		$contact->save();

        $contacts = (new Collection())->push($contact);
        $this->dispatch(new VerifyNumber(Auth::user(), $contacts));

		## sync the tags with a type of the current Company ID
		$contact->syncTagsWithType(explode(',', $request->get('tags')), 'company' . $company_id);

		contactAction('CREATED', $contact->id);

		$request->session()->flash("status", "Contact created successfully!");
		return redirect('/contacts');

	}

	public function addTag(Request $request)
	{
		if ($request->has('filters') && $request->has('newTag')) {
			$filters = $request->get('filters');
			$newTag = $request->get('newTag');

			$contacts = Contact::withAnyTags($filters)->get();

			foreach ($contacts as $contact) {
				$contact->attachTag($newTag);
				$contact->syncTagsWithType([$newTag], 'company' . $contact->company_id);
			}
			return json_encode(['result' => true]);
		} else {
			return json_encode(['result' => false]);
		}
	}


	public function verifyNumbers(Request $request)
	{
		$this->validate($request, [
			'tagValues' => 'required'
		]);

		$query = Contact::with('Company');

		if (Auth::user()->hasRole('manager')) {
			$companyIds = Company::getCompanyIds(Auth::user()->company_id);
			$query = $query->whereIn('contacts.company_id', $companyIds);
		}

		$tagValues = $request->get('tagValues');
		$query = $query->withAllTags($tagValues);


		$contactIdsArr = Datatables::of($query)->rawColumns(['id'])->toArray();
		if (isset($contactIdsArr['data']) && $contactIdsArr) {
			$contactIds = array_pluck($contactIdsArr['data'], 'id');
			$contacts = Contact::whereIn('id', $contactIds)->get();
			$this->dispatch(new VerifyNumber(Auth::user(), $contacts, true));

			return response()->json(['result' => true]);
		}

		return response()->json(['result' => false]);
	}

	public function verifyNumber(Request $request, $id)
	{
		if (Auth::user()->hasRole('administrator')) {
			$contact = Contact::where('id', $id)->first();
		} elseif (Auth::user()->hasRole('manager')) {
			$companyIds = Company::getCompanyIds(Auth::user()->company_id);
			$contact = Contact::where('id', $id)
				->whereIn('company_id', $companyIds)
				->first();
		} else {
			$request->session()->flash("status", "Access not allowed!");
			return redirect('/');
		}

		if (!$contact) {
			$request->session()->flash("status", "Contact not found!");
			return redirect('/contacts');
		}

		$contacts = (new Collection())->push($contact);
		$this->dispatch(new VerifyNumber(Auth::user(), $contacts, true));

		return response()->json(['result' => true]);
	}
}
