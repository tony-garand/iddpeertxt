<?php

namespace peertxt\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use peertxt\models\Shortlink;
use peertxt\models\ShortlinkClick;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Twilio\Rest\Client;
use Twilio\Twiml;

class ShortlinkController extends Controller
{

	public function __construct() {
	}

	public function clickthrough(Request $request, $code) {

		$link = Shortlink::where('code', $code)->first();
		if ($link) {
		    $shortlinkClick = new ShortlinkClick();
		    $shortlinkClick->shortlink_id = $link->id;
		    $shortlinkClick->ip = $request->ip();
		    $shortlinkClick->refer = null;
		    $shortlinkClick->full_request_headers = $request->headers;
		    $shortlinkClick->save();

		    ## increment the click count for the links campaign
		    $link->Campaign->link_click_count = $link->Campaign->link_click_count + 1;
		    $link->Campaign->save();

			return redirect(trim($link->destination));

		} else {
			return redirect('https://' . env('APP_DOMAIN', 'www.peertxt.co'));
		}

	}

}

