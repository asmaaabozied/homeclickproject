@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.subscriptions')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.subscriptions.index') }}"> @lang('site.subscriptions')</a></li>
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

                    <form action="{{ route('dashboard.subscriptions.update', $subscribe->id) }}" method="post" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="row">
                            @foreach (config('translatable.locales') as $locale)
                                <div class="form-group col-md-6">
                                    @if(count(config('translatable.locales'))>1)
                                        <label>@lang('site.' . $locale . '.name')</label>
                                    @else
                                        <label>@lang('site.name')</label>
                                    @endif
                                    <input type="text" name="{{ $locale }}[name]" class="form-control" value="{{ $subscribe->translate($locale)->name }}">
                                </div>
                            @endforeach
                        </div>




                        <div class="row">
                            @foreach (config('translatable.locales') as $locale)
                                <div class="form-group col-md-6">
                                    @if(count(config('translatable.locales'))>1)
                                        <label>@lang('site.' . $locale . '.description')</label>
                                    @else
                                        <label>@lang('site.description')</label>
                                    @endif

                                    <textarea class="textarea" name="{{ $locale }}[description]" row="5"
                                              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{strip_tags( $subscribe->translate($locale)->description) }}</textarea>
                                </div>
                            @endforeach
                        </div>


                        <div class="row">

                            <!-- Discount Start Date Field -->
                            <div class="form-group col-md-6">
                                <label>@lang('site.start_at')</label>

                                <input type="date" name="start_at" class="form-control date" value="{{$subscribe->start_at}}" required>


                            </div>

                            <!-- Discount End Date Field -->
                            <div class="form-group col-md-6">
                                <label>@lang('site.end_at')</label>

                                <input type="date" name="end_at" class="form-control date"   value="{{$subscribe->end_at}}" required>

                            </div>

                        </div>


                        <div class="row">


                            <div class="form-group col-md-6">

                                <label>@lang('site.price')</label>

                                <input type="text" name="price" class="form-control"    value="{{$subscribe->price}}"  required >
                            </div>

                            <div class="form-group col-md-6">

                                <label>@lang('site.period')</label>

                                <input type="text" name="period" class="form-control"  value="{{$subscribe->period}}" required >
                            </div>
                        </div>



                        <div class="form-group">
                            <button type="button" class="btn btn-warning mr-1"
                                    onclick="history.back();">
                                <i class="fa fa-backward"></i> @lang('site.back')
                            </button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> @lang('site.edit')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
