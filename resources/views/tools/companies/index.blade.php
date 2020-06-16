@extends('layouts.app')

@section('title', ' - Companies')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				@include('shared.messages')

				<div class="actions">
					<div class="pull-left info">
						<h1>Companies</h1>
					</div>
					<div class="pull-right action">
						<a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#addCompany">Add New Company</a>
					</div>
				</div>

				<div class="panel panel-default">
					<div class="panel-body">

						<div class="loadin"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></div>
						<table style="display:none;" class="datatable table table-striped">
							<thead>
								<tr>
									<th>ID</th>
									<th>Status</th>
									<th>Parent Company</th>
									<th class="sort_by" data-sort_order="asc">Name</th>
									<th>Zip Code</th>
									<th>Near Phone</th>
									<th>Area Code</th>
									<th>Created Date</th>
								</tr>
							</thead>

							<tbody>
								@foreach ($companies as $company)
									<tr>
										<td><a href="/tools/companies/view/{{ $company->id }}">{{ $company->id }}</a></td>
										<td>{{ company_status($company->status) }}</td>
										<td>{{ $company->parent_company ? $company->parent_company->company_name : $company->company_name }}</td>
										<td>{{ $company->company_name }}</td>
										<td>{{ $company->default_zipcode }}</td>
										<td>{{ $company->default_nearphone }}</td>
										<td>{{ $company->default_areacode }}</td>
										<td>{{ $company->created_at }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addCompany" tabindex="-1" role="dialog" aria-labelledby="addCompanyLabel">
		<div class="modal-dialog modal_med" role="document">
			<div class="modal-content">
				<div class="modal-body">
					@include('shared.forms.add_company')
				</div>
			</div>
		</div>
	</div>

@endsection

