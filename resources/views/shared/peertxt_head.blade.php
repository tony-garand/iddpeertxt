<head>
	<title>{{ config('app.name', 'Laravel') }} @yield('title')</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="{{ mix('/css/app.css') }}" rel="stylesheet">

	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
	<link href="{{ mix('/css/amsify.suggestags.css') }}" rel="stylesheet">
	<link href="{{ mix('/css/chosen.css') }}" rel="stylesheet">
	<script>
		window.Laravel = <?php echo json_encode([
			'csrfToken' => csrf_token(),
		]); ?>
	</script>
	<script src="https://use.fontawesome.com/68798271d7.js"></script>
	<link rel="icon" type="image/png" href="/images/peertxt_favicon.png" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script>
	<script src="https://cdn.datatables.net/plug-ins/1.10.15/dataRender/datetime.js"></script>
</head>
