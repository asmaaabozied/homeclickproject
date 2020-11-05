@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.settings')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')
                    </a></li>
                <li class="active">@lang('site.settings')</li>
            </ol>
        </section>

        <div class="box-body">
            @include('partials._errors')
            <div class="content">
                <form action="{{ route('dashboard.settings.updateAll') }}" method="post">
                    <hr style="border-color: #3c8dbcab"/>

                    @foreach($settings as $key=>$setting)
                        {{ csrf_field() }}
{{--                        @lang('site.' . $locale .{{$setting->slug}} )--}}
                        <label style="margin: 5px">  {{$setting->slug}} </label>
                        <input class="form-control" value="{{$setting->value}}" name="{{$setting->slug}}">
                    @endforeach
                   <br>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-primary text-center"><i class="fa fa-edit"></i> @lang('site.edit')</button>
                    </div>
                </form>


                <br>





            </div>


        </div>


    </div>


@endsection

