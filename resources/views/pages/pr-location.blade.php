@extends('main')
@section('content')
<meta name="_token" content="{{csrf_token()}}" />

@if(isset($error))
    <div class="alert alert-danger" role="alert">
        <div style="text-align: center"><b>Error: {{ $error }}</b></div>
    </div>
@endif

<div class="row">
    <div class="col-12">
            <div class="card-body">
                <div class="row">

                    <div class="col-12">
                        <form class="form-inline float-right">
                            Space for adding crons

                        </form>
                    </div>
                </div>
            </div>

    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <span class="tab-content ml-1" id="myTabContent">
                            <table id="branches" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">SHA/Link</th>
                                        <th scope="col">Merged at</th>
                                        <th scope="col">Author</th>
                                        <th scope="col">Current location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @if(isset($pullRequests))
                                            @foreach ($pullRequests as $pull)
                                                <tr>
                                                    <td><b>{{ $pull['title'] }}</b></td>
                                                    <td><a href="{{ $pull['pr_link'] }}" target="_blank">{{ $pull['merge_commit_sha'] }}</a></td>
                                                    <td>{{ $pull['merged_at'] }}</td>
                                                    <td><a href="{{ $pull['user_url'] }}" target="_blank">{{ $pull['user_login'] }}</a></td>

                                                    @if($pull['location'] == \App\Client\CallManager::BETA)
                                                        <td class="bg-danger">{{ $pull['location'] }}</td>
                                                    @elseif($pull['location'] == \App\Client\CallManager::EARLY)
                                                        <td class="bg-warning">{{ $pull['location'] }}</td>
                                                    @elseif($pull['location'] == \App\Client\CallManager::STABLE)
                                                        <td class="bg-success">{{ $pull['location'] }}</td>
                                                    @else
                                                        <td class="bg-default">{{ $pull['location'] }}</td>
                                                    @endif

                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                            </table>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

