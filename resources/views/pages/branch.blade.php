@extends('main')
@section('content')

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
                        <form class="form-inline float-right" method="POST" action="{{ route('setBranches') }}">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            {{--<div class="form-group">--}}
                                {{--<input type="text" class="form-control mb-2 mr-sm-2" name="repository_name" required--}}
                                    {{--placeholder="Repository">--}}
                            {{--</div>--}}

                            <div class="form-group" style=" margin-right: 5px">
                                <select class="form-control" name="repository_name">
                                    @if(isset($orgaRepo))
                                        @foreach ($orgaRepo as $key=>$value)
                                            <option value={{ $key }}>{{ $key }}</option>
                                        @endforeach
                                    @else
                                        <option>Kein Repo gefunden!</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group" style=" margin-right: 15px">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">0 = all</div>
                                    </div>
                                    <input type="number" class="form-control" name="amount_of_pages" required
                                           placeholder="Pages (1 = 100PR)">
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

    </div>
</div>


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
