@extends('main')
@section('content')

@if(isset($error))
{{ $error }}
@endif

@if(isset($login))
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title mb-4">
                        <div class="d-flex justify-content-start">
                            <div class="image-container">
                                <img src="{{ $avatar_url }}" id="imgProfile" style="width: 150px; height: 150px" class="img-thumbnail" />
                                <div class="middle">
                                    <input type="file" style="display: none;" id="profilePicture" name="file" />
                                </div>
                            </div>
                            <div class="ml-3">
                                <h2 style="font-size: 1.5rem; font-weight: bold">{{ $login }}</h2>
                                <h6>{{ $company }}</h6>
                                <h6><a href="{{ $html_url }}" target="_blank">{{ $html_url }}</a></h6>
                            </div>
                            <div class="mx-auto">
                                <h7 class="d-block">Last update: {{ \Carbon\Carbon::parse($updated_at)->format('d-m-Y, H:i') }}</h7>
                                <br>
                                <h6 class="d-block">Followers: {{ $followers }}</h6>
                                <h6 class="d-block">Following: {{ $following }}</h6>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="tab-content ml-1" id="myTabContent">

                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label style="font-weight:bold;">Full Name</label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            {{ $fullname }}
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label style="font-weight:bold;">Location</label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            {{ $location }}
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label style="font-weight:bold;">Public Repositories</label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            {{ $public_repos }}
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label style="font-weight:bold;">Here since</label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            @php
                                                $date = Carbon\Carbon::parse($created_at)->format('d.m.Y');
                                                $startDate = Carbon\Carbon::parse($created_at);
                                                $now = Carbon\Carbon::now();
                                                $length = $startDate->diffInDays($now);
                                                echo $date . "  -  " . $length . " Days";
                                            @endphp
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-sm-3 col-md-2 col-5">
                                            <label style="font-weight:bold;">User Subscription</label>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            {{ $planName }}
                                        </div>
                                    </div>
                                    <hr />

                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>
@endif

@stop
