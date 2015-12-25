@extends('layout')

@section('content')
<!-- view specific content here -->

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>BusinessId</th>
            <th colspan="2">Name (FI)</th>
        </tr>
    </thead>
    <tbody>
    @foreach($companies as $company)
        <tr>
            <td>{{ $company->id }}</td>
            <td>{{ $company->business_id }}</td>
            <td>{{ $company->name }}</td>
            <td><a class="btn btn-default" href="{{ action('CompaniesController@show', [$company]) }}">show</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
<!-- render pagination -->
<!-- laravel docs: The render method will render the links to the rest of the pages in the result set. Each of these links will already contain the proper ?page query string variable -->
{!! $companies->render() !!}

@endsection


@section('scripts')
<!-- view specific scripts here -->

@endsection