@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.sellers')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.sellers')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.sellers') <small>{{ $sellers->total() }}</small></h3>

                    <form action="{{ route('dashboard.sellers.index') }}" method="get">

                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}">
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>

                                {{--                               @if (auth()->user()->hasPermission('create_jobs'))--}}

                                    <a href="{{ route('dashboard.sellers.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
{{--                               @else--}}
{{--                                    <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus"></i> @lang('site.add')</a>--}}
{{--                               @endif--}}
                            </div>

                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($sellers->count() > 0)

                        <table class="table table-hover" id="table">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.name')</th>
                                <th>@lang('site.phone')</th>
                                <th>@lang('site.geography')</th>

                                <th>@lang('site.email')</th>

                                <th>@lang('site.subscriptions')</th>
                                <th>@lang('site.created_at')</th>
                                <th>@lang('site.status')</th>

                                <th>@lang('site.action')</th>

                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($sellers as $index=>$seller)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $seller->name ?? '' }}</td>
                                    <td>{{ $seller->phone  ?? ''}}</td>
                                    <td>{{ $seller->geogrphy->name  ?? ''}}</td>


                                    <td>{{ $seller->email  ?? ''}}</td>
                                    <td>{{$seller->subsribe->name}}</td>
                                    <td>{{ isset($seller->created_at) ? $seller->created_at->diffForHumans() :''	 }}</td>
                                    <td>

                                        <form action="{{ route('dashboard.sellers.status', $seller->id) }}" method="post" style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('POST') }}

                                            @if( $seller->status==1)
                                                <button type="submit" class="btn btn-success update btn-sm">
                                                    <i class="fa fa-check"></i> @lang('site.open')
                                                </button>
                                            @elseif( $seller->status==0)
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-close"></i> @lang('site.close')
                                                </button>
                                            @endif

                                        </form>

                                    </td>


                                      <td>



                                    <td>


                                        @if (auth()->user()->hasPermission('update_consultations'))
                                            <a href="{{ route('dashboard.sendEmailorders', $seller->id) }}" class="btn btn-success btn-sm"><i class="fa fa-envelope"></i> @lang('site.sendemail')</a>
                                        @else
                                            <a href="#" class="btn btn-info btn-sm disabled"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                        @endif

                                        {{--                                        @if (auth()->user()->hasPermission('edit_jobs'))--}}

                                            <a href="{{ route('dashboard.sellers.edit', $seller->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>
{{--                                        @else--}}
{{--                                            <a href="#" class="btn btn-info btn-sm disabled"><i class="fa fa-edit"></i> @lang('site.edit')</a>--}}
{{--                                        @endif--}}
{{--                                            @if (auth()->user()->hasPermission('delete_jobs'))--}}


                                            <form action="{{ route('dashboard.sellers.destroy', $seller->id) }}" method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                            </form><!-- end of form -->
{{--                                        @else--}}
{{--                                            <button class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i> @lang('site.delete')</button>--}}
{{--                                            @endif--}}
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table><!-- end of table -->





                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
