@extends('main')
@section('content')

@if(isset($error))
{{ $error }}
@endif

@if(isset($repoDataArray))
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="tab-content ml-1" id="myTabContent">

                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Private</th>
                                        <th scope="col">URL</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col">Updated at</th>
                                        <th scope="col">Language</th>
                                        <th scope="col">Forks</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($repoDataArray as $repo)
                                            <tr>
                                                <td>{{ $repo['name'] }}</td>
                                                <td>{{ $repo['private'] ? 'yes' : 'no' }}</td>
                                                <td><a href="{{ $repo['html_url'] }}" target="_blank">{{ $repo['html_url'] }}</a></td>
                                                <td>{{ $repo['created_at'] }}</td>
                                                <td>{{ $repo['updated_at'] }}</td>
                                                <td>{{ $repo['language'] }}</td>
                                                <td>{{ $repo['forks'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>
@endif

@stop
