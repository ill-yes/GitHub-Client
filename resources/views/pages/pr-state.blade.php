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
                                        <th scope="col">Branch name</th>
                                        <th scope="col">Author</th>
                                        <th scope="col">Merged at</th>
                                        <th scope="col">Pull Request</th>
                                    </tr>
                                </thead>
                            </table>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

