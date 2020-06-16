<?php

use peertxt\models\Contact;
use peertxt\models\ContactActionLog;

function format_user_role($role_id)
{

	$role_name = "";

	switch ($role_id) {
		case 1:
			$role_name = "IDD User";
			break;
		case 2:
			$role_name = "Soar User";
			break;
		case 20:
			$role_name = "IDD Manager";
			break;
		case 21:
			$role_name = "Soar Manager";
			break;
		case 99:
			$role_name = "Superuser";
			break;
	}

	return $role_name;
}

function format_business_status($status)
{

	$status_name = "";

	switch ($status) {
		case -1:
			$status_name = "Closed";
			break;
		case 0:
			$status_name = "New";
			break;
		case 1:
			$status_name = "Ready";
			break;
		case 10:
			$status_name = "Claimed";
			break;
		case 20:
			$status_name = "Contacted";
			break;
	}

	return $status_name;
}

function format_bool_yn($bool)
{

	$answer = "";

	switch ($bool) {
		case 1:
			$answer = "Yes";
			break;
		case 0:
			$answer = "No";
			break;
	}

	return $answer;
}

function right_status($bool, $deleted_at)
{

	$answer = "";

	if ($deleted_at) {
		$answer = 'Deleted';
	} else {
		switch ($bool) {
			case 1:
				$answer = "Active";
				break;
			case 0:
				$answer = "Inactive";
				break;
		}
	}

	return $answer;
}

function group_status($bool, $deleted_at)
{

	$answer = "";

	if ($deleted_at) {
		$answer = 'Deleted';
	} else {
		switch ($bool) {
			case 1:
				$answer = "Active";
				break;
			case 0:
				$answer = "Inactive";
				break;
		}
	}

	return $answer;
}

function user_status($bool, $deleted_at)
{

	$answer = "";

	if ($deleted_at) {
		$answer = 'Deleted';
	} else {
		switch ($bool) {
			case 1:
				$answer = "Active";
				break;
			case -1:
				$answer = "Verify";
				break;
			case 0:
				$answer = "Inactive";
				break;
		}
	}

	return $answer;
}

function campaign_type($type)
{

	$type_name = "";

	switch ($type) {
		case 1:
			$type_name = "Peer to Peer";
			break;
		case 2:
			$type_name = "Direct Delivery";
			break;
	}

	return $type_name;

}

function campaign_contact_status($status)
{

	$status_name = "";

	switch ($status) {
		case 51:
			$status_name = "Completed (Duplicate, not sent)";
			break;
		case 50:
			$status_name = "Completed";
			break;
		case 20:
			$status_name = "SMS Delivering";
			break;
		case 10:
			$status_name = "User Locked";
			break;
		case 1:
			$status_name = "Ready";
			break;
		case 0:
			$status_name = "Unlocked";
			break;
		case -1:
			$status_name = "Deleted";
			break;
	}

	return $status_name;

}

function campaign_status($status)
{

	$status_name = "";

	switch ($status) {
		case 99:
			$status_name = "Archived";
			break;
		case 50:
			$status_name = "Completed";
			break;
		case 30:
			$status_name = "Paused";
			break;
		case 21:
			$status_name = "Sending";
			break;
		case 20:
			$status_name = "Running";
			break;
		case 10:
			$status_name = "Ready to Run";
			break;
		case 6:
			$status_name = "Processing (locked)";
			break;
		case 5:
			$status_name = "Processing";
			break;
		case 1:
			$status_name = "Ready to Process";
			break;
		case 0:
			$status_name = "Draft";
			break;
		case -1:
			$status_name = "Deleted";
			break;
	}

	return $status_name;

}

function sms_status($bool)
{

	$answer = "";

	switch ($bool) {
		case 1:
			$answer = "Active";
			break;
		case 0:
			$answer = "Inactive";
			break;
	}

	return $answer;
}

function ht_status($status)
{

	$status_name = "";

	switch ($status) {
		case 1:
			$status_name = "Completed";
			break;
		case 0:
			$status_name = "Processing";
			break;
	}

	return $status_name;

}

function company_status($status)
{

	$status_name = "";

	switch ($status) {
		case 1:
			$status_name = "Active";
			break;
		case 0:
			$status_name = "Inactive";
			break;
	}

	return $status_name;

}

function contact_verified($status)
{

	$status_name = "";

	switch ($status) {
		case 2:
			$status_name = "<span class='verified_phone_label verified'>Mobile Number Verified</span>";
			break;
		case 1:
			$status_name = "<span class='verified_phone_label part_verified'>Phone Format Valid</span>";
			break;
		case 0:
			$status_name = "<span class='verified_phone_label not_verified'>Not Verified</span>";
			break;
	}

	return $status_name;

}

function rander($length = 10)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function create_html_link($url, $text, $class = '', $target = '_self')
{
	return "<a class=\"{$class}\" href=\"{$url}\" target=\"{$target}\">{$text}</a>";
}

function create_user_link($user, $class = '', $target = '_self')
{
	return create_html_link("/users/view/{$user->id}", $user->name, $target);
}

function state_strings()
{
	$states = array(
		'AL' => 'Alabama',
		'AK' => 'Alaska',
		'AZ' => 'Arizona',
		'AR' => 'Arkansas',
		'CA' => 'California',
		'CO' => 'Colorado',
		'CT' => 'Connecticut',
		'DE' => 'Delaware',
		'DC' => 'District Of Columbia',
		'FL' => 'Florida',
		'GA' => 'Georgia',
		'HI' => 'Hawaii',
		'ID' => 'Idaho',
		'IL' => 'Illinois',
		'IN' => 'Indiana',
		'IA' => 'Iowa',
		'KS' => 'Kansas',
		'KY' => 'Kentucky',
		'LA' => 'Louisiana',
		'ME' => 'Maine',
		'MD' => 'Maryland',
		'MA' => 'Massachusetts',
		'MI' => 'Michigan',
		'MN' => 'Minnesota',
		'MS' => 'Mississippi',
		'MO' => 'Missouri',
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'OH' => 'Ohio',
		'OK' => 'Oklahoma',
		'OR' => 'Oregon',
		'PA' => 'Pennsylvania',
		'RI' => 'Rhode Island',
		'SC' => 'South Carolina',
		'SD' => 'South Dakota',
		'TN' => 'Tennessee',
		'TX' => 'Texas',
		'UT' => 'Utah',
		'VT' => 'Vermont',
		'VA' => 'Virginia',
		'WA' => 'Washington',
		'WV' => 'West Virginia',
		'WI' => 'Wisconsin',
		'WY' => 'Wyoming',
		'AB' => 'Alberta',
		'BC' => 'British Columbia',
		'MB' => 'Manitoba',
		'NB' => 'New Brunswick',
		'NL' => 'Newfoundland',
		'NS' => 'Nova Scotia',
		'ON' => 'Ontario',
		'PE' => 'Prince Edward Island',
		'QC' => 'Quebec',
		'SK' => 'Saskatchewan',
	);
	return $states;
}

function isUsablePhoneNo(string $phoneNo, int $companyId)
{
	$phone = str_replace('-', '', filter_var($phoneNo, FILTER_SANITIZE_NUMBER_INT));
	$stripped_phone = str_replace("+1", "", $phone);
	$stripped_phone = str_replace("+", "", $stripped_phone);
	$stripped_phone = ltrim($stripped_phone, '0');
	$stripped_phone = ltrim($stripped_phone, '1');
	$stripped_phone = trim(preg_replace("/[^0-9]/", "", $stripped_phone));

	$contactCheck = Contact::where('company_id', $companyId)
		->where('phone', $stripped_phone)
		->count();

	if ($contactCheck > 0)
		return false;
	else
		return true;
}

function customReplyFields()
{
	return [
		'contacts.first_name' => 'First Name',
		'contacts.last_name' => 'Last Name',
		'contacts.phone' => 'Phone #',
		'contacts.email' => 'Email',
		'companies.company_name' => 'Company Name'
	];
}

function contactAction($action, $contactId, $userId=0)
{
	$entry = new ContactActionLog();
	$entry->contact_id = $contactId;
	$entry->action = $action;
	$entry->action_by = (Auth::check() ? Auth::user()->id : $userId);
	$entry->save();
}
