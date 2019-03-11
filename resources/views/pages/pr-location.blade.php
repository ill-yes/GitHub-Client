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
                            Button for manual updating?
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
                        <table id="pullrequests" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Repository</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Merged at</th>
                                    <th scope="col">Author</th>
                                    <th scope="col">Current location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($pullRequests))
                                    @foreach ($pullRequests as $pull)
                                        <tr>
                                            <td><b>{{ $pull['repository'] }}</b></td>
                                            <td><a href="{{ $pull['pr_link'] }}" target="_blank">{{ $pull['title'] }}</a></td>

                                            <td>{{ \Carbon\Carbon::parse($pull['merged_at'])->format('Y-m-d, H:i | l') }}</td>
                                            <td><a href="{{ $pull['user_url'] }}" target="_blank">{{ $pull['user_login'] }}</a></td>

                                                @if($pull['location'] == \App\Client\CallManager::BETA)
                                                    <td class="bg-danger" style="text-align: center">{{ $pull['location'] }}</td>
                                                @elseif($pull['location'] == \App\Client\CallManager::EARLY)
                                                    <td class="bg-warning" style="text-align: center">{{ $pull['location'] }}</td>
                                                @elseif($pull['location'] == \App\Client\CallManager::STABLE)
                                                    <td class="bg-success" style="text-align: center">{{ $pull['location'] }}</td>
                                                @else
                                                    <td class="bg-default">{{ $pull['location'] }}</td>
                                                @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop


@section('js')
    <script>
        $(document).ready( function () {
            $('#pullrequests').DataTable();
        } );
    </script>
@endsection
