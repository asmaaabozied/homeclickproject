@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.capons')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.capons')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.capons') <small>{{ $capons->total() }}</small></h3>


                    <div class="row">



                        <div class="col-md-4">
                            @if (auth()->user()->hasPermission('create_categories'))

                                <a href="{{ route('dashboard.capons.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                {{-- @else
                                    <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus"></i> @lang('site.add')</a> --}}
                            @endif
                        </div>

                    </div>

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($capons->count() > 0)


                        <table class="table table-hover" id="table">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.code')</th>
                                <th>@lang('site.discount')</th>
                                <th>@lang('site.start_at')</th>

                                <th>@lang('site.end_at')</th>


                                <th>@lang('site.created_at')</th>

                                @if (auth()->user()->hasPermission('update_consultations','delete_consultations'))

                                    <th>@lang('site.action')</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($capons as $index=>$value)

                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{$value->code ??''}}</td>
                                    <td>{{$value->discount ?? ''}}</td>
                                    <td>{{$value->start_at ??''}}</td>
                                    <td>{{$value->end_at ?? ''}}</td>

                                    <td>{{isset($value->created_at) ? $value->created_at->diffForHumans() :'' }}</td>



                                    <td>


                                        @if (auth()->user()->hasPermission('update_consultations'))
                                            <a href="{{ route('dashboard.capons.edit', $value->id) }}"
                                               class="btn btn-info btn-sm"><i
                                                    class="fa fa-edit"></i> @lang('site.edit')</a>
                                        @else
                                            <a href="#" class="btn btn-info btn-sm disabled"><i
                                                    class="fa fa-edit"></i> @lang('site.edit')</a>
                                        @endif
                                        @if (auth()->user()->hasPermission('delete_consultations'))
                                            <form action="{{ route('dashboard.capons.destroy', $value->id) }}"
                                                  method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button type="submit" class="btn btn-danger delete btn-sm"><i
                                                        class="fa fa-trash"></i> @lang('site.delete')</button>
                                            </form><!-- end of form -->
                                        @else
                                            <button class="btn btn-danger btn-sm disabled"><i
                                                    class="fa fa-trash"></i> @lang('site.delete')</button> --}}
                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table><!-- end of table -->

                        {{ $capons->appends(request()->query())->links() }}

                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
