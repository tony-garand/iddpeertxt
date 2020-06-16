<?php

namespace peertxt\Jobs;

use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use League\Csv\Reader;
use Log;
use peertxt\models\Contact;
use peertxt\models\ContactField;
use peertxt\Events\ContactImportFinished;
use peertxt\Notifications\ContactImportNotify;
use peertxt\models\User;
use Storage;
use Uuid;

class ProcessContactImport implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $user, $companyId, $filename, $fieldMap, $tags;

	/**
	 * Create a new job instance.
	 *
	 * @param User $user
	 * @param int $companyId
	 * @param string $filename
	 * @param array $fieldMap
	 * @param string $tags
	 */
	public function __construct(User $user, int $companyId, string $filename, array $fieldMap, string $tags)
	{
		$this->user = $user;
		$this->companyId = $companyId;
		$this->filename = $filename;
		$this->fieldMap = $fieldMap;
		$this->tags = $tags;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		set_time_limit(0);

		Log::info(sprintf('Jobs\ProcessContactImport : processing file %s; tags: %s',
			$this->filename,
			$this->tags));

		## open the uploaded CSV file
		$reader = Reader::createFromString(Storage::disk('s3')->get($this->filename));

		## get the header record
		$reader->setHeaderOffset(0);
		$header = $reader->getHeader();

		## process the file records
		$records = $reader->getRecords();

		$validContacts = 0;
        $contacts = new Collection();

		foreach ($records as $offset => $record) {
			$count = 1;
			$contact = null;
			$newFields = [];
			foreach ($record as $key => $value) {
				if ($count <= count($this->fieldMap)) {
					if (substr($this->fieldMap[$count], 0, 7) === 'custom_') {
						## custom label, use its Id and update the contact_fields table
						$field = new ContactField();
						$field->custom_label_id = substr($this->fieldMap[$count], 7);
						$field->value = $value;
						$newFields[] = $field;
					} elseif ($this->fieldMap[$count] != 'skip') {
						## update the contact record
						if ($contact === null) {
							$contact = new Contact();
							$contact->company_id = $this->companyId;
						}

						$tmp = $this->fieldMap[$count];
						$contact->$tmp = $value;
					}
					$count++;
				}
			}

			if ($contact !== null) {
				if (!empty($contact->phone)) {
					## only import contacts with valid Phone #'s
					if (isUsablePhoneNo($contact->phone, $this->companyId)) {
						if ($this->tags !== "")
							$contact->tags = explode(",", $this->tags);

						$contact->uuid = Uuid::generate()->string;
						$contact->save();
                        $contacts->push($contact);
						$validContacts++;

						contactAction('IMPORT CREATED', $contact->id, $this->user->id);

						if ($this->tags !== "")
							$contact->syncTagsWithType(explode(',', $this->tags), 'company' . $this->companyId);

						foreach ($newFields as $field) {
							$field->contact_id = $contact->id;
							$field->save();
						}
					} else {
						Log::info('Jobs\ProcessContactImport : invalid phone # :: ' . $contact->phone);
					}
				} else {
					Log::info('Jobs\ProcessContactImport : empty phone #');
				}
			} else {
				Log::info('Jobs\ProcessContactImport : invalid contact');
			}
		}



        $this->dispatch(new VerifyNumber(Auth::user(), $contacts));

		Log::info('Jobs\ProcessContactImport : deleting import file');

		Storage::disk('s3')->delete($this->filename);

		$this->user->notify(new ContactImportNotify($validContacts));
		broadcast(new ContactImportFinished($this->user->id, 'Contact import finished'));

		Log::info('Jobs\ProcessContactImport : finished');
	}
}
