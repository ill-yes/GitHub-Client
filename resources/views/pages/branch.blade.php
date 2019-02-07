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

                            <div class="form-group" style=" margin-right: 5px">
                                <select class="form-control" id="repository_name">
                                    @if(isset($orgaRepo))
                                        @foreach ($orgaRepo as $key=>$value)
                                            <option value={{ $key }}>{{ $key }}</option>
                                        @endforeach
                                    @else
                                        <option>No repository found!</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group" style=" margin-right: 15px">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">0 = all</div>
                                    </div>
                                    <input type="number" class="form-control" id="amount_of_pages" required
                                           placeholder="Pages (1 = 100PR)">
                            </div>

                            <div class="form-group">
                                <button id="submit" class="btn btn-primary">
                                    <span id="submitText" style="display: block;">Submit</span>
                                    <span id="submitLoading" style="display: none;"><i class="fas fa-spinner fa-spin"></i></span>
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

    </div>
</div>


@if(isset($branches) || true)
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
@endif

@stop

@section('js')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>

        <script>
        jQuery(document).ready(function(){
            jQuery('#submit').click(function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                        url: "{{ route('getDeadBranches') }}",
                        method: 'post',
                        dataType: 'json',
                        data: {
                            repository: jQuery('#repository_name').val(),
                            pagination: jQuery('#amount_of_pages').val()
                        },
                        beforeSend: function() {
                            toggledisplay(document.getElementById("submitText"));
                            toggledisplay(document.getElementById("submitLoading"));
                            document.getElementById("submit").disabled = true;

                        },
                        complete: function(){
                            toggledisplay(document.getElementById("submitText"));
                            toggledisplay(document.getElementById("submitLoading"));
                            document.getElementById("submit").disabled = false;
                        },
                        success: function(result){
                            /*jQuery('.alert').show();
                            jQuery('.alert').html("Success!");*/

                            $('#branches').DataTable().clear();
                            $('#branches').DataTable( {
                                "data": result,
                                "destroy": true,
                                "searching": true,
                                "paging": false,
                                "columns": [
                                    { "data": "title" },
                                    { "data": "branch_name" },
                                    { "data": "user_login" },
                                    { "data": "merged_at" },
                                    { "data": "pr_link" }
                                ]});
                        }
                });
            });
            function toggledisplay(elementID)
            {
                if (elementID.style.display === "none") {
                    elementID.style.display = "block";
                } else {
                    elementID.style.display = "none";
                }
            }
        });
    </script>
@endsection
