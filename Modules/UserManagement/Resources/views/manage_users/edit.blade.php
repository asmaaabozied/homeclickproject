@extends('layouts.dashboard.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">

        <h1>@lang('site.users')</h1>

        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a>
            </li>
            <li><a href="{{ route('dashboard.manage_users.index') }}"> @lang('site.users')</a></li>
            <li class="active">@lang('site.edit')</li>
        </ol>
    </section>

    <section class="content">

        <div class="box box-primary">

            <div class="box-header">
                <h3 class="box-title">@lang('site.edit')</h3>
            </div><!-- end of box header -->

            <div class="box-body">

                @include('partials._errors')

                <form action="{{ route('dashboard.manage_users.update', $user->id) }}" method="post"
                    enctype="multipart/form-data">

                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <input id="user_id" hidden type="text" name="user_id" value="{{$user->id}}" required>
                    <div class="form-group">
                        <label>@lang('site.first_name')</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                    </div>

                    <div class="form-group">
                        <label>@lang('site.email')</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                    </div>

                    <div class="form-group" >
                        <label>@lang('site.phone')</label>
                        <div id="result">

                            <input  type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                            {{--                            id="phone" type="tel"--}}
                            <span id="valid-msg" class="hide">✓ Valid</span>
                            <span id="error-msg" class="hide"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>@lang('site.image')</label>
                        <input type="file" name="image" class="form-control image">
                    </div>

                    <div class="form-group">
                        <img src="{{ $user->image_path }}" style="width: 100px" class="img-thumbnail image-preview"
                            alt="">
                    </div>
                    @foreach ($userMetaskey as $key=>$type)
                        @if($key=='country')
                            <div class="form-group col-md-6">
                                <label>@lang('site.country')</label>
                                <select id="country" name="country" class="form-control" required>
                                    <option value="" disabled selected hidden>@lang('site.pleaseChoose')  ... </option>
                                    @foreach( $countries as $key => $value)
                                    <option value="{{$key}}">
                                        {{$value}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        @elseif($key=='city')
                            <div class="form-group col-md-6">
                                <label>@lang('site.city')</label>
                                <select id="city" name="city" class="form-control" required>
                                    <option value="" disabled selected hidden>@lang('site.pleaseChoose')  ... </option>
                                </select>
                            </div>
                        @else
                        <div class="form-group">
                            <label {{$type}}>@lang('site.'.$key)</label>
                            @if( !empty($userMetas[$key]) )<input type="{{$type}}" name="{{$key}}" class="form-control" value="{{ $userMetas[$key]}}">
                            @else <input type="{{$type}}" name="{{$key}}" class="form-control" value="">
                            @endif
                        </div>
                        @endif
                    @endforeach

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i>
                            @lang('site.edit')</button>
                    </div>


                </form><!-- end of form -->

            </div><!-- end of box body -->

        </div><!-- end of box -->

    </section><!-- end of content -->

</div><!-- end of content wrapper -->
<script>
    (function() {
            var cities=@json($cities);
            //fill city When Country Changed
            function fillCityOps(country_id){
                if(country_id !=undefined){
                    var options='';
                    cities[country_id].forEach(element => {
                        options+='<option value="'+element['id']+'">'+element['name']+'</option>'
                    });
                    $("#city").html(options);
                }
            }
            $("#country").change(function(){
                var country_id= $("#country").val();
                fillCityOps(country_id);
            });


            //fill City If Exist
            var country='@if(!empty($userMetas["country"])){{$userMetas["country"]}}@endif';
            if(country!=""){
                $("#country").val(country).change();
                fillCityOps(country);
            }
            //fill City If Exist
            var city='@if(!empty($userMetas["city"])){{$userMetas["city"]}}@endif';
            if(city!=""){
                $("#city").val(city).change();
            }

            var input = document.querySelector("#phone"),
            errorMsg = document.querySelector("#error-msg"),
            validMsg = document.querySelector("#valid-msg");

            // here, the index maps to the error code returned from getValidationError - see readme
            var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];

            // initialise plugin
            var iti = window.intlTelInput(input, {
                hiddenInput: "phone",
                nationalMode: true,
                initialCountry: 'ae',
                preferredCountries: ['ae','eg'],
                utilsScript: "../../../../public/dashboard_files/plugins/intl-tel-input/build/js/utils.js?1585994360633"
            });

            var reset = function() {
            input.classList.remove("error");
            errorMsg.innerHTML = "";
            errorMsg.classList.add("hide");
            validMsg.classList.add("hide");
            };

            var handleChange = function() {
                var text = (iti.isValidNumber()) ? "International: " + iti.getNumber() : "Please enter a number below";
                var textNode = document.createTextNode(text);
                output.innerHTML = "";
                output.appendChild(textNode);
            };

            // on blur: validate
            input.addEventListener('blur', function() {
            reset();
            if (input.value.trim()) {
                if (iti.isValidNumber()) {
                validMsg.classList.remove("hide");
                } else {
                input.classList.add("error");
                var errorCode = iti.getValidationError();
                errorMsg.innerHTML = errorMap[errorCode];
                errorMsg.classList.remove("hide");
                }
            }
            });

            // on keyup / change flag: reset
            input.addEventListener('change', reset);
            input.addEventListener('keyup', reset);

            // try {
            //     //  var input = document.querySelector("#phone");
            //     window.intlTelInput(input, {
            //     initialCountry: "auto",
            //     geoIpLookup: function(callback) {
            //         $.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
            //         var countryCode = (resp && resp.country) ? resp.country : "";
            //         callback(countryCode);
            //         });
            //     },
            //     utilsScript: "../../../../public/dashboard_files/plugins/intl-tel-input/build/js/utils.js?1590403638580" // just for formatting/placeholders etc
            //     });
            // } catch (error) {

            // }


        })();
</script>
@endsection
