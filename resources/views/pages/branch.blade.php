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
                                        <th scope="col">Name</th>
                                        <th scope="col">Pull Request</th>
                                        <th scope="col">Merged at</th>
                                        <th scope="col">Exists</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($branches as $branch)
                                            <tr>
                                                <td><b>{{ $branch['name'] }}</b></td>
                                                <td><a href="{{ $branch['pr_link'] }}" target="_blank">Link</a></td>
                                                <td>{{ \Carbon\Carbon::parse($branch['merged_at'])->format('d-m-Y H:i') }}</td>
                                                <td>
                                                    @if($branch['exists'])
                                                        <span class="badge badge-pill badge-danger">yes</span>
                                                    @else($branch['exists'])
                                                        <span class="badge badge-pill badge-success">no</span>
                                                    @endif
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
