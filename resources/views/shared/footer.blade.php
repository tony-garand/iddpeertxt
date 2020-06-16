<div class="container copyright">
	<div class="row">
		<div class="col-md-12 col-md-offset-0">
			<div class="pull-left">&copy; {{ date('Y') }} PeerTxt. All rights reserved.
			 - 5.8
			</div>
			@if(file_exists(storage_path('../version.txt')))
				<div class="pull-right">Version {{ Config::get('app.version_number') }}.{{ File::get(storage_path('../version.txt')) }}</div>
			@else
				<div class="pull-right">Version {{ Config::get('app.version_number') }}</div>
			@endif
		</div>
	</div>
</div>
