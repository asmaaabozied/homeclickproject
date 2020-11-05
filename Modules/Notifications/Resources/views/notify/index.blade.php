@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.notification')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.pages.index') }}"> @lang('site.notification')</a></li>
                <li class="active">@lang('site.send_notification')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.send_notification')</h3>
                </div><!-- end of box header -->
                <div class="box-body">

                    @include('partials._errors')

                    <form class="form-horizontal" action="{{ route('dashboard.notification.send') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">{{__('Emails')}} ({{__('site.users')}})</label>
                                <div class="col-sm-10">
                                    <select class="form-control selectpicker" name="user_emails[]" multiple data-selected-text-format="count" data-actions-box="true">
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->email}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">{{__('Emails')}} ({{__('site.admins')}})</label>
                                <div class="col-sm-10">
                                    <select class="form-control selectpicker" name="owner_emails[]" multiple data-selected-text-format="count" data-actions-box="true">
                                        @foreach($owners as $owner)
                                            <option value="{{$owner->id}}">{{$owner->email}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="subject">{{__('site.title')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="subject"  value="{{old('title')}}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">{{__('site.notification')}}</label>
                                <div class="col-sm-10">
                                    <textarea class="textarea" name="body" required>{{old("body")}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button class="btn btn-purple" type="submit">{{__('Send')}}</button>
                        </div>
                    </form>


                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
<script src="{{ asset('public/dashboard_files/js/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.textarea').summernote({
            // set editor height
            minHeight: 300,             // set minimum height of editor
            // maxHeight: 300,
        });
        $('.textarea')
    });
</script>
