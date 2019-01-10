@extends('main')
@section('content')

@if(isset($error))
{{ $error }}
@endif

@if(isset($branches))
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <span class="tab-content ml-1" id="myTabContent">


                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Branch name</th>
                                        <th scope="col">Author</th>
                                        <th scope="col">Merged at</th>
                                        <th scope="col">Pull Request</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($branches as $branch)
                                            <tr>
                                                <td><b>{{ $branch['title'] }}</b></td>
                                                <td><b>{{ $branch['branch_name'] }}</b></td>
                                                <td>{{ $branch['user_login'] }}</td>
                                                <td>{{ \Carbon\Carbon::parse($branch['merged_at'])->format('d-m-Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ $branch['pr_link'] }}" target="_blank" class="badge badge-danger">Link</a>
                                                </td>
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
