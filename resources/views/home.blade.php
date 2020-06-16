@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-md-offset-0">

				@if (count($errors) > 0)
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif

				@if(Session::has('status'))
					<div class="alert alert-success">
						{{Session::get('status')}}
					</div>
				@endif

				@if (Auth::user()->hasRole(['SoarUser','SoarManager']))

					<script>
						var loaded = false;
						var markers = [];
						var infowindows = [];

						function initMap() {
							var map = new google.maps.Map(document.getElementById('map'), {
								zoom: 12
							});

							if (navigator.geolocation) {
								navigator.geolocation.getCurrentPosition(function (position) {
									initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
									map.setCenter(initialLocation);
								});
							}

							google.maps.event.addListener(map, 'idle', function () {
								if (loaded) {
									getData(map, markers, infowindows);
								}
							});

							google.maps.event.addListenerOnce(map, 'tilesloaded', function (evt) {
								loaded = true;
								getData(map, markers, infowindows);
							});
						}

						function getData(map, markers, infowindows) {
							var nelat = map.getBounds().getNorthEast().lat();
							var nelng = map.getBounds().getNorthEast().lng();
							var swlat = map.getBounds().getSouthWest().lat();
							var swlng = map.getBounds().getSouthWest().lng();
							var uri = '/api/map_leads?nelat=' + nelat + '&nelng=' + nelng + '&swlat=' + swlat + '&swlng=' + swlng;

							while (markers.length) {
								markers.pop().setMap(null);
							}
							$('#map_data').html("");

							$.getJSON(uri, function (data) {
								$.each(data, function (id, val) {
									setPin(map, val, markers, infowindows);
								});
							});
						}

						function setPin(map, data, markers, infowindows) {
							console.log('setPin called.. ');
							console.log(data);

							var marker = new google.maps.Marker({
								position: new google.maps.LatLng(data.lat, data.lng),
								map: map,
								category: data.PRIMARY_SIC_DESC,
								idx: data.id
							});

							var cont = '<div class="map_popup"><div class="row"><div class="column"><div class="map_location_name">' + data.Company + '</div><div class="map_address">' + data.Address + '<br/>' + data.City + ', ' + data.Mailing_State + ' ' + data.Zip + '<br/>Phone: ' + data.Phone + '<br/>Contact: ' + data.FULLNAME1 + '<br/>Category: ' + data.PRIMARY_SIC_DESC + '</div><div class="map_action"><a href="javascript:;" data-href="/businesses/queue_scan/' + data.id + '" class="btn btn-primary map_btn"><i class="fa fa-cog" aria-hidden="true"></i> Queue Scan</a></div></div></div></div>';
							var grid = '<div class="map_item col-md-6"><div class="map_location_name">' + data.Company + '</div><div class="map_address">' + data.Address + '<br/>' + data.City + ', ' + data.Mailing_State + ' ' + data.Zip + '<br/>Phone: ' + data.Phone + '<br/>Contact: ' + data.FULLNAME1 + '<br/>Category: ' + data.PRIMARY_SIC_DESC + '</div><div class="map_action"><a href="javascript:;" data-href="/businesses/queue_scan/' + data.id + '" class="btn btn-primary map_btn"><i class="fa fa-cog" aria-hidden="true"></i> Queue Scan</a></div></div>';

							$('#map_data').append(grid).fadeIn();
							markers.push(marker);
							marker.setVisible(true);

							var my_infowindow = new google.maps.InfoWindow({
								content: cont
							});
							infowindows.push(my_infowindow);
							marker.addListener('click', function () {
//							closemarkers();
								my_infowindow.open(marker.get('map'), marker);
//							highlight(marker.idx);
							});

							// addM(marker, cont);
							// marker.setVisible(true);

							// function addM(marker, msg) {
							// 	var my_infowindow = new google.maps.InfoWindow({
							// 		content: msg
							// 	});
							// 	infowindows.push(my_infowindow);
							// 	marker.addListener('click', function() {
							// 		closemarkers();
							// 		my_infowindow.open(marker.get('map'), marker);
							// 		highlight(marker.idx);
							// 	});
							// }

						}

						function closemarkers() {
							for (var i = 0; i < infowindows.length; i++) {
								infowindows[i].close();
							}
						}

					</script>
					<script async defer
									src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap"></script>

					<div class="dashboard_panel panel panel-default">
						<div class="panel-heading">My Work Queue</div>
						<div class="panel-body">
							You currently do not have any work queued.
						</div>
					</div>

					<div class="dashboard_panel panel panel-default">
						<div class="panel-heading">Sales Map</div>
						<div class="panel-body">
							<div id="map" style="height:500px;"></div>
							<div id="map_data">
							</div>
						</div>
					</div>
				@else
					<div class="dashboard_panel panel panel-default">
						<div class="panel-heading">Welcome</div>
						<div class="panel-body">
							Hello again. This will eventually be your dashboard.
						</div>
					</div>
				@endif

			</div>
		</div>
	</div>
@endsection