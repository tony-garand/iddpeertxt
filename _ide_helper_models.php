<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace peertxt\models{
/**
 * peertxt\models\GroupUser
 *
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\GroupUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\GroupUser newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\GroupUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\GroupUser query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\GroupUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\GroupUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\GroupUser whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\GroupUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\GroupUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\GroupUser whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\GroupUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\GroupUser withoutTrashed()
 */
	class GroupUser extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\Right
 *
 * @property int $id
 * @property int $company_id
 * @property int $status
 * @property string|null $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \peertxt\models\Company $Company
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Right newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Right newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Right onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Right query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Right whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Right whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Right whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Right whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Right whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Right whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Right whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Right whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Right withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Right withoutTrashed()
 */
	class Right extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\MessagingServiceNumber
 *
 * @property int $id
 * @property int $messaging_service_id
 * @property string|null $number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingServiceNumber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingServiceNumber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingServiceNumber query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingServiceNumber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingServiceNumber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingServiceNumber whereMessagingServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingServiceNumber whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingServiceNumber whereUpdatedAt($value)
 */
	class MessagingServiceNumber extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\Shortlink
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $destination
 * @property int $campaign_id
 * @property int $contact_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \peertxt\models\Campaign $Campaign
 * @property-read \peertxt\models\ShortlinkClick $ShortlinkClick
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Shortlink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Shortlink newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Shortlink onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Shortlink query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Shortlink whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Shortlink whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Shortlink whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Shortlink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Shortlink whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Shortlink whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Shortlink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Shortlink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Shortlink withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Shortlink withoutTrashed()
 */
	class Shortlink extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\Tag
 *
 * @property int $id
 * @property array $name
 * @property array $slug
 * @property string|null $type
 * @property int|null $order_column
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $translations
 * @property \Illuminate\Database\Eloquent\Collection|\Spatie\Tags\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Tags\Tag containing($name, $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Tags\Tag ordered($direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag withAllTags($tags, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag withAnyTags($tags, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Tag withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Tags\Tag withType($type = null)
 */
	class Tag extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\CustomLabel
 *
 * @property int $id
 * @property int $company_id
 * @property string|null $label
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \peertxt\models\Company $Company
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomLabel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomLabel newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\CustomLabel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomLabel query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomLabel whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomLabel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomLabel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomLabel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomLabel whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomLabel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\CustomLabel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\CustomLabel withoutTrashed()
 */
	class CustomLabel extends \Eloquent {}
}

namespace peertxt{
/**
 * peertxt\RoleCategory
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\RoleCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\RoleCategory newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\RoleCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\RoleCategory query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\RoleCategory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\RoleCategory withoutTrashed()
 */
	class RoleCategory extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\CampaignRight
 *
 * @property int $id
 * @property int $campaign_id
 * @property int $right_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignRight newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignRight newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignRight query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignRight whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignRight whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignRight whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignRight whereRightId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignRight whereUpdatedAt($value)
 */
	class CampaignRight extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\SmsConvoMessagingService
 *
 * @property int $id
 * @property int $sms_convo_id
 * @property int $messaging_service_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoMessagingService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoMessagingService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoMessagingService query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoMessagingService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoMessagingService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoMessagingService whereMessagingServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoMessagingService whereSmsConvoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoMessagingService whereUpdatedAt($value)
 */
	class SmsConvoMessagingService extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\CampaignDeliveryRule
 *
 * @property int $id
 * @property int $campaign_id
 * @property int $day
 * @property int $whole_day
 * @property int|null $from_time
 * @property int|null $to_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignDeliveryRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignDeliveryRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignDeliveryRule query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignDeliveryRule whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignDeliveryRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignDeliveryRule whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignDeliveryRule whereFromTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignDeliveryRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignDeliveryRule whereToTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignDeliveryRule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignDeliveryRule whereWholeDay($value)
 */
	class CampaignDeliveryRule extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\MessagingService
 *
 * @property int $id
 * @property int $company_id
 * @property string|null $sid
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \peertxt\models\Campaign $Campaign
 * @property-read \peertxt\models\Company $Company
 * @property-read \Illuminate\Database\Eloquent\Collection|\peertxt\models\MessagingServiceNumber[] $Numbers
 * @property-read int|null $numbers_count
 * @property-read mixed $number_list
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingService query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingService whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingService whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingService whereSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\MessagingService whereUpdatedAt($value)
 */
	class MessagingService extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\ShortlinkClick
 *
 * @property int $id
 * @property int $shortlink_id
 * @property string|null $ip
 * @property string|null $refer
 * @property string|null $geodata
 * @property string|null $full_request_headers
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ShortlinkClick newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ShortlinkClick newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\ShortlinkClick onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ShortlinkClick query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ShortlinkClick whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ShortlinkClick whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ShortlinkClick whereFullRequestHeaders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ShortlinkClick whereGeodata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ShortlinkClick whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ShortlinkClick whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ShortlinkClick whereRefer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ShortlinkClick whereShortlinkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ShortlinkClick whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\ShortlinkClick withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\ShortlinkClick withoutTrashed()
 */
	class ShortlinkClick extends \Eloquent {}
}

namespace peertxt{
/**
 * peertxt\SmsConvoThread
 *
 * @property int $id
 * @property int|null $sms_convo_id
 * @property string|null $messaging_service_sid
 * @property string|null $sms_message_sid
 * @property string|null $account_sid
 * @property string|null $from
 * @property string|null $to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\SmsConvoThread newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\SmsConvoThread newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\SmsConvoThread query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\SmsConvoThread whereAccountSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\SmsConvoThread whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\SmsConvoThread whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\SmsConvoThread whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\SmsConvoThread whereMessagingServiceSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\SmsConvoThread whereSmsConvoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\SmsConvoThread whereSmsMessageSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\SmsConvoThread whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\SmsConvoThread whereUpdatedAt($value)
 */
	class SmsConvoThread extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\Company
 *
 * @property int $id
 * @property int|null $parent_company_id
 * @property int $status
 * @property string $company_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $default_zipcode
 * @property string|null $default_areacode
 * @property string|null $default_nearphone
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\peertxt\models\Company[] $child_companies
 * @property-read int|null $child_companies_count
 * @property-read \peertxt\models\Company|null $parent_company
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Company newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Company onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Company query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Company whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Company whereDefaultAreacode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Company whereDefaultNearphone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Company whereDefaultZipcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Company whereParentCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Company whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Company withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Company withoutTrashed()
 */
	class Company extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\SmsConvo
 *
 * @property int $id
 * @property int $company_id
 * @property string $trigger
 * @property int $all_locations
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $welcome
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvo whereAllLocations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvo whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvo whereTrigger($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvo whereWelcome($value)
 */
	class SmsConvo extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\SmsConvoThreadReply
 *
 * @property int $id
 * @property int $sms_convo_thread_id
 * @property int|null $sms_convo_script_id
 * @property string $reply_body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoThreadReply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoThreadReply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoThreadReply query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoThreadReply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoThreadReply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoThreadReply whereReplyBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoThreadReply whereSmsConvoScriptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoThreadReply whereSmsConvoThreadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoThreadReply whereUpdatedAt($value)
 */
	class SmsConvoThreadReply extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\ContactActionLog
 *
 * @property int $id
 * @property int $contact_id
 * @property string $action
 * @property int $action_by
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \peertxt\models\User $User
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactActionLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactActionLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactActionLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactActionLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactActionLog whereActionBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactActionLog whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactActionLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactActionLog whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactActionLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactActionLog whereUpdatedAt($value)
 */
	class ContactActionLog extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\Group
 *
 * @property int $id
 * @property int $company_id
 * @property int $status
 * @property string|null $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \peertxt\models\Company $Company
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Group newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Group onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Group query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Group whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Group whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Group whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Group whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Group whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Group withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Group withoutTrashed()
 */
	class Group extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\SmsConvoScript
 *
 * @property int $id
 * @property int $sms_convo_id
 * @property int $step
 * @property string $script_body
 * @property string|null $data_destination
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoScript newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoScript newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoScript query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoScript whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoScript whereDataDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoScript whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoScript whereScriptBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoScript whereSmsConvoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoScript whereStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsConvoScript whereUpdatedAt($value)
 */
	class SmsConvoScript extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\CampaignContact
 *
 * @property int $id
 * @property string|null $uuid
 * @property int $campaign_id
 * @property int $contact_id
 * @property int $cc_status
 * @property int|null $user_id
 * @property string|null $sms_sid
 * @property int $content_option
 * @property string|null $content_sent
 * @property string|null $mms_sent
 * @property string|null $audit_locked_by_user
 * @property string|null $audit_submit_sms
 * @property string|null $audit_sms_sid_rcvd
 * @property string|null $audit_sms_stop_rcvd
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \peertxt\models\Campaign $Campaign
 * @property-read \peertxt\models\Contact $Contact
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\CampaignContact onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereAuditLockedByUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereAuditSmsSidRcvd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereAuditSmsStopRcvd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereAuditSubmitSms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereCcStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereContentOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereContentSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereMmsSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereSmsSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignContact whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\CampaignContact withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\CampaignContact withoutTrashed()
 */
	class CampaignContact extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\CustomReply
 *
 * @property int $id
 * @property int $company_id
 * @property string $reply_name
 * @property string $reply_body
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \peertxt\models\Company $Company
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomReply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomReply newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\CustomReply onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomReply query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomReply whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomReply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomReply whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomReply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomReply whereReplyBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomReply whereReplyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CustomReply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\CustomReply withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\CustomReply withoutTrashed()
 */
	class CustomReply extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\LogOutgoing
 *
 * @property-read \peertxt\models\Campaign $Campaign
 * @property-read \peertxt\models\CampaignContact $CampaignContact
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\LogOutgoing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\LogOutgoing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\LogOutgoing query()
 */
	class LogOutgoing extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\CampaignReplyNotification
 *
 * @property int $id
 * @property int $campaign_id
 * @property int $user_id
 * @property int $reply_count
 * @property int $in_use
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignReplyNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignReplyNotification newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\CampaignReplyNotification onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignReplyNotification query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignReplyNotification whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignReplyNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignReplyNotification whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignReplyNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignReplyNotification whereInUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignReplyNotification whereReplyCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignReplyNotification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignReplyNotification whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\CampaignReplyNotification withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\CampaignReplyNotification withoutTrashed()
 */
	class CampaignReplyNotification extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\CampaignTag
 *
 * @property int $id
 * @property int $campaign_id
 * @property string $tag
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignTag whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignTag whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\CampaignTag whereUpdatedAt($value)
 */
	class CampaignTag extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\ChatThread
 *
 * @property int $id
 * @property string|null $uuid
 * @property int $chat_id
 * @property int $direction
 * @property int $status
 * @property string|null $subject
 * @property string|null $message
 * @property string|null $private_notes
 * @property string|null $media_url
 * @property string|null $user_id
 * @property string|null $audit_sms_rcvd
 * @property string|null $audit_sms_sent
 * @property string|null $sms_sid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $saved
 * @property-read \peertxt\models\Chat $Chat
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereAuditSmsRcvd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereAuditSmsSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereMediaUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread wherePrivateNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereSaved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereSmsSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ChatThread whereUuid($value)
 */
	class ChatThread extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\Campaign
 *
 * @property int $id
 * @property string|null $uuid
 * @property int $company_id
 * @property int $messaging_service_id
 * @property int $campaign_status
 * @property int $campaign_type
 * @property int|null $created_by
 * @property string|null $campaign_name
 * @property string|null $description
 * @property string|null $content_template_1
 * @property string|null $content_template_2
 * @property string|null $content_template_3
 * @property string|null $content_template_4
 * @property string|null $conversion_link_1
 * @property string|null $conversion_link_2
 * @property string|null $conversion_link_3
 * @property string|null $conversion_link_4
 * @property string|null $content_media_1
 * @property string|null $content_media_2
 * @property string|null $content_media_3
 * @property string|null $content_media_4
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $zipcode
 * @property string|null $areacode
 * @property int $rights_type
 * @property string|null $nearphone
 * @property int $rollup_total
 * @property int $rollup_completed
 * @property int $link_click_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\peertxt\models\CampaignContact[] $CampaignContacts
 * @property-read int|null $campaign_contacts_count
 * @property-read \peertxt\models\Company $Company
 * @property-read \peertxt\models\User|null $CreatedBy
 * @property-read \peertxt\models\MessagingService $MessagingService
 * @property-read \Illuminate\Database\Eloquent\Collection|\peertxt\models\Shortlink[] $Shortlink
 * @property-read int|null $shortlink_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\peertxt\models\CampaignDeliveryRule[] $delivery_rule
 * @property-read int|null $delivery_rule_count
 * @property-read mixed $sms_sent_count
 * @property \Illuminate\Database\Eloquent\Collection|\Spatie\Tags\Tag[] $tags
 * @property-read \peertxt\models\Shortlink $shortlinkCountRelation
 * @property-read int|null $tags_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Campaign onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereAreacode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereCampaignName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereCampaignStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereCampaignType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereContentMedia1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereContentMedia2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereContentMedia3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereContentMedia4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereContentTemplate1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereContentTemplate2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereContentTemplate3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereContentTemplate4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereConversionLink1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereConversionLink2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereConversionLink3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereConversionLink4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereLinkClickCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereMessagingServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereNearphone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereRightsType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereRollupCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereRollupTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign whereZipcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign withAllTags($tags, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign withAnyTags($tags, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Campaign withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Campaign withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Campaign withoutTrashed()
 */
	class Campaign extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\User
 *
 * @property int $id
 * @property string|null $uuid
 * @property int $status
 * @property int $company_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_role
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \peertxt\models\Company $Company
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User whereUserRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\User whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\ClientNote
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ClientNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ClientNote newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\ClientNote onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ClientNote query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\ClientNote withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\ClientNote withoutTrashed()
 */
	class ClientNote extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\Chat
 *
 * @property int $id
 * @property string|null $uuid
 * @property int $company_id
 * @property int $campaign_id
 * @property int $contact_id
 * @property int $overall_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \peertxt\models\Campaign $Campaign
 * @property-read \Illuminate\Database\Eloquent\Collection|\peertxt\models\ChatThread[] $ChatThread
 * @property-read int|null $chat_thread_count
 * @property-read \peertxt\models\Company $Company
 * @property-read \peertxt\models\Contact $Contact
 * @property-read mixed $latest_thread
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Chat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Chat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Chat query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Chat whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Chat whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Chat whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Chat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Chat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Chat whereOverallStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Chat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Chat whereUuid($value)
 */
	class Chat extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\SmsData
 *
 * @property int $id
 * @property int $status
 * @property int $company_id
 * @property string|null $from
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsData query()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsData whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsData whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsData whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsData whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsData whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsData whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\SmsData whereUpdatedAt($value)
 */
	class SmsData extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\Contact
 *
 * @property int $id
 * @property string|null $uuid
 * @property int $company_id
 * @property int $status
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $phone
 * @property string|null $email
 * @property string|null $address1
 * @property string|null $address2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip
 * @property string|null $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $sms_stopped
 * @property int $verified_phone
 * @property-read \Illuminate\Database\Eloquent\Collection|\peertxt\models\ContactActionLog[] $ActionLog
 * @property-read int|null $action_log_count
 * @property-read \peertxt\models\Company $Company
 * @property \Illuminate\Database\Eloquent\Collection|\Spatie\Tags\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Contact onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereSmsStopped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereVerifiedPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact whereZip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact withAllTags($tags, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact withAllTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact withAnyTags($tags, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\Contact withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Contact withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\Contact withoutTrashed()
 */
	class Contact extends \Eloquent {}
}

namespace peertxt\models{
/**
 * peertxt\models\ContactField
 *
 * @property int $id
 * @property int $contact_id
 * @property int $custom_label_id
 * @property string|null $value
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactField newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactField newQuery()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\ContactField onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactField query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactField whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactField whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactField whereCustomLabelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactField whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactField whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactField whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactField whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\peertxt\models\ContactField whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\ContactField withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\peertxt\models\ContactField withoutTrashed()
 */
	class ContactField extends \Eloquent {}
}

