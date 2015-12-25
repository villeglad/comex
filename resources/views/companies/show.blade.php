@extends('layout')

@section('content')

<!-- link back to previous page -->
<a href="{{ URL::previous() }}"><span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span></a>

<div class="panel panel-info">
    <div class="panel-heading">
        <h3>{{ $company->name }}</h3>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <th width="20%"><span class="small">BusinessId</span></th>
                <td>{{ $company->business_id }}</td>
            <tr>
            <tr>
                <th><span class="small">Company Form</span></th>
                <td>{{ $company->company_form }}</td>
            </tr>
                <th><span class="small">Registration Date</span></th>
                <td>{{ $company->registration_date }}</td>
            </tr>
        </table>
    </div>
    
    <div class="panel-footer">
        <p>Last updated: {{ $company->updated_at }}</p>
    </div>
</div>

@endsection
