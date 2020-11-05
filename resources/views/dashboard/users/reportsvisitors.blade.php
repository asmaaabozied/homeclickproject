@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.reportvisitor')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.reportvisitor')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

{{--                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.cases') <small>{{ $cases->total() }}</small></h3>--}}

{{--                    <form action="{{ route('dashboard.cases.index') }}" method="get">--}}

{{--                        <div class="row">--}}

{{--                            <div class="col-md-4">--}}
{{--                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}">--}}
{{--                            </div>--}}

{{--                            <div class="col-md-4">--}}
{{--                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>--}}

{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </form><!-- end of form -->--}}

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($visitors->count() > 0)

                        <table class="table table-hover" id="table">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.device_id')</th>

                                <th>@lang('site.country')</th>

                                <th>@lang('site.name')</th>

                                <th>@lang('site.status')</th>

                                <th>@lang('site.time')</th>

                                <th>@lang('site.created_at')</th>

                                <th>@lang('site.action')</th>

                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($visitors as $index=>$visitor)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{isset($visitor->ip)?$visitor->ip: '' }}</td>

                                    <td>{{geoip()->getLocation($visitor->ip)['country']}}</td>

                                    <td>{{geoip()->getLocation($visitor->ip)['city']}}</td>

                                    <td>{{geoip()->getLocation($visitor->ip)['state_name']}}</td>

                                    <td>{{geoip()->getLocation($visitor->ip)['timezone']}}</td>


                                    <td>{{ isset($visitor->created_at) ?  $visitor->created_at->diffForHumans() :''	 }}</td>



                                    <td>


                                        @if (auth()->user()->hasPermission('delete_cases'))


                                            <form action="{{ route('dashboard.visitors.destroy', $visitor->id) }}" method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                            </form><!-- end of form -->
                                        @else
                                            <button class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                        @endif

                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table><!-- end of table -->

{{--                        {{ $case->appends(request()->query())->links() }}--}}

                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
