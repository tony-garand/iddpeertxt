<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/verify/{uuid}', 'UserController@verifyUser')->name('user.verify');
Route::post('/verify-tos/{uuid}', 'UserController@verifyUserTos')->name('user.verify.tos');
Route::get('/user_profile', 'UserController@profile')->name('user.profile');
Route::post('/users_profile_update', 'UserController@profile_update');
Route::get('/users-password-update', 'UserController@passwordUpdate')->name('user.update.password');
Route::post('/users-password-update', 'UserController@postPasswordUpdate')->name('user.update.password');

// placeholders for now
Route::get('/reporting', 'HomeController@index');
Route::get('/conversations', 'HomeController@index');

Route::get('/c/{random_string}', 'ShortlinkController@clickthrough');

## CONTACTS
Route::get('/contacts', 'ContactController@index')->name('contacts');
Route::get('/contacts/table', 'ContactController@indexTable')->name('contacts.index.table');
Route::post('/contacts/save', 'ContactController@save');
Route::get('/contacts/view/{id}', 'ContactController@view');
Route::post('/contacts/update/{id}', 'ContactController@update');
Route::get('/contacts/delete/{id}', 'ContactController@delete');
Route::get('/contacts/undelete/{id}', 'ContactController@undelete');
Route::post('/contacts/import', 'ContactController@import');
Route::post('/contacts/importFinish', 'ContactController@importFinish');
Route::get('/contacts/addTag', 'ContactController@addTag')->name('contacts.addTag');
Route::post('/contacts/verify/{id}', 'ContactController@verifyNumber')->name('contacts.verify.number');
Route::post('/contacts/verify-list', 'ContactController@verifyNumbers')->name('contacts.verify.numbers');

## CUSTOM LABELS
Route::get('/customLabels', 'CustomLabelController@index')->name('customLabels');
Route::get('/customLabels/table', 'CustomLabelController@indexTable')->name('customLabels.index.table');
Route::get('/customLabels/view/{id}', 'CustomLabelController@view');
Route::post('/customLabels/save', 'CustomLabelController@save');
Route::post('/customLabels/update/{id}', 'CustomLabelController@update');
Route::get('/customLabels/delete/{id}', 'CustomLabelController@delete');

## CUSTOM REPLIES
Route::get('/customReplies', 'CustomReplyController@index')->name('customReplies');
Route::get('/customReplies/table', 'CustomReplyController@indexTable')->name('customReplies.index.table');
Route::get('/customReplies/view/{id}', 'CustomReplyController@view');
Route::post('/customReplies/save', 'CustomReplyController@save');
Route::post('/customReplies/update/{id}', 'CustomReplyController@update');
Route::get('/customReplies/delete/{id}', 'CustomReplyController@delete');

## CAMPAIGNS
Route::get('/campaigns', 'CampaignController@index')->name('campaigns.index');
Route::get('/campaigns/table', 'CampaignController@indexTable')->name('campaigns.index.table');
Route::get('/campaigns/ajax_get_campaign_creation_data', 'CampaignController@ajax_get_campaign_creation_data');
Route::get('/campaigns/ajax_get_campaign_work_data', 'CampaignController@ajax_get_campaign_work_data');
Route::get('/campaigns/ajax_run_get_item', 'CampaignController@ajax_run_get_item');
Route::get('/campaigns/ajax_run_get_totals', 'CampaignController@ajax_run_get_totals');
Route::get('/campaigns/ajax_run_cancel_item', 'CampaignController@ajax_run_cancel_item');
Route::get('/campaigns/ajax_run_send_item', 'CampaignController@ajax_run_send_item');
Route::get('/campaigns/ajax_inbox_chat', 'CampaignController@ajax_inbox_chat');
Route::post('/campaigns/ajax_inbox_send', 'CampaignController@ajax_inbox_send');
Route::get('/campaigns/ajax_chat_thread_info', 'CampaignController@ajax_chat_thread_info');
Route::post('/campaigns/ajax_attach_contact_field', 'CampaignController@ajax_attach_contact_field');
Route::post('/campaigns/save', 'CampaignController@save');
Route::get('/campaigns/view/{id}', 'CampaignController@view')->name('campaigns.view');
Route::post('/campaigns/update/{id}', 'CampaignController@update');
Route::get('/campaigns/begin/{id}', 'CampaignController@begin');
Route::get('/campaigns/go_live/{id}', 'CampaignController@go_live');
Route::get('/campaigns/go_send/{id}', 'CampaignController@go_send');
Route::get('/campaigns/watch/{id}', 'CampaignController@watch');
Route::get('/campaigns/run/{id}', 'CampaignController@run');
Route::get('/campaigns/completed', 'CampaignController@completed')->name('campaigns.completed');
Route::get('/campaigns/completed/table', 'CampaignController@completedTable')->name('campaigns.completed.table');
Route::get('/campaigns/completed/view/{id}', 'CampaignController@completedView')->name('campaigns.completed.view');
Route::get('/campaigns/inbox/{id}', 'CampaignController@inbox')->name('campaigns.inbox');
Route::get('/campaigns/pause/{id}', 'CampaignController@pause')->name('campaigns.pause');
Route::get('/campaigns/resume/{id}', 'CampaignController@resume')->name('campaigns.resume');
Route::get('/campaigns/ajax_get_custom_reply', 'CampaignController@ajax_get_custom_reply');
Route::post('/campaigns/numbers/add', 'CampaignController@numbers_add');
Route::get('/campaigns/archive/{campaign}', 'CampaignController@archive')->name('campaigns.archive');

## USERS
Route::get('/users', 'UserController@index');
Route::get('users/table', 'UserController@indexTable')->name('users.index.table');
Route::get('/users/add', 'UserController@add');
Route::post('/users/save', 'UserController@save');
Route::get('/users/view/{id}', 'UserController@view');
Route::post('/users/update/{id}', 'UserController@update');
Route::get('/users/delete/{id}', 'UserController@delete');
Route::get('/users/undelete/{id}', 'UserController@undelete');

## GROUPS
Route::get('/groups', 'GroupController@index');
Route::get('/groups/table', 'GroupController@indexTable')->name('groups.index.table');
Route::post('/groups/save', 'GroupController@save');
Route::get('/groups/view/{id}', 'GroupController@view');
Route::post('/groups/update/{id}', 'GroupController@update');
Route::post('/groups/update_users/{id}', 'GroupController@update_users');
Route::get('/groups/delete/{id}', 'GroupController@delete');

## RIGHTS
Route::get('/rights', 'RightController@index');
Route::post('/rights/save', 'RightController@save');
Route::get('/rights/view/{id}', 'RightController@view');
Route::post('/rights/update/{id}', 'RightController@update');
Route::get('/rights/delete/{id}', 'RightController@delete');
Route::post('/rights/update_users/{id}', 'RightController@update_users');
Route::post('/rights/update_groups/{id}', 'RightController@update_groups');

## TOOLS
Route::get('/tools/roles', 'ToolController@roles_index');
Route::get('/tools/roles/add', 'ToolController@roles_add');
Route::post('/tools/roles/save', 'ToolController@roles_save');
Route::get('/tools/roles/view/{id}', 'ToolController@roles_view');
Route::post('/tools/roles/update/{id}', 'ToolController@roles_update');
Route::get('/tools/roles/delete/{id}', 'ToolController@roles_delete');
Route::get('/tools/companies', 'ToolController@companies_index');
Route::get('/tools/companies/add', 'ToolController@companies_add');
Route::post('/tools/companies/save', 'ToolController@companies_save');
Route::get('/tools/companies/view/{id}', 'ToolController@companies_view');
Route::post('/tools/companies/update/{id}', 'ToolController@companies_update');

// messaging services
Route::get('/tools/messaging_services', 'ToolController@messaging_services_index');
Route::get('/tools/messaging_services/view/{id}', 'ToolController@messaging_services_view');
Route::post('/tools/messaging_services/save', 'ToolController@messaging_services_save');
Route::post('/tools/messaging_services/add_number', 'ToolController@messaging_services_add_number');
Route::post('/tools/messaging_services/update/{id}', 'ToolController@messaging_services_update');

// sms conversations
Route::get('/tools/sms_conversations', 'ToolController@sms_conversations_index');
Route::get('/tools/sms_conversations/add', 'ToolController@sms_conversations_add');
Route::post('/tools/sms_conversations/save', 'ToolController@sms_conversations_save');
Route::post('/tools/sms_conversations/save_script', 'ToolController@sms_conversations_save_script');
Route::get('/tools/sms_conversations/view/{id}', 'ToolController@sms_conversations_view');
Route::post('/tools/sms_conversations/update/{id}', 'ToolController@sms_conversations_update');
Route::post('/tools/sms_conversations/update_script/{id}', 'ToolController@sms_conversations_update_script');
Route::get('/tools/sms_conversations/delete/{id}', 'ToolController@sms_conversations_delete');
Route::get('/tools/sms_conversations/edit_script/{id}', 'ToolController@sms_conversations_edit_script');
Route::get('/tools/sms_conversations/delete_script/{id}', 'ToolController@sms_conversations_delete_script');
Route::get('/tools/sms_conversation_threads/{id}', 'ToolController@sms_conversation_threads');
Route::get('/tools/sms_conversation_users/{id}', 'ToolController@sms_conversation_users');
Route::get('/tools/sms_conversation_thread_view/{id}', 'ToolController@sms_conversation_thread_view');

Route::get('/api/cron_messaging_services', 'ApiController@cron_messaging_services');
//Route::get('/api/map_leads', 'ApiController@map_leads');
//Route::get('/api/yext_scan', 'ApiController@yext_scan');
//Route::get('/api/yext_results/{id}', 'ApiController@yext_results');
//Route::get('/api/google_name_lookup', 'ApiController@google_name_lookup');

Route::post('/sms/convo_hello', 'SmsController@convo_hello');
Route::post('/sms/incoming_sms', 'SmsController@incoming_sms');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('email-test', function () {
    return new \peertxt\Mail\UserCreatedMail(\peertxt\User::first());
});
