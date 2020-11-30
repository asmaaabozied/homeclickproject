@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.orders')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')
                    </a></li>
                <li class="active">@lang('site.orders')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.orders')
                        <small>{{ $orders->total() }}</small></h3>

                    <form action="{{ route('dashboard.orders.index') }}" method="get">

                        <div class="row">


                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($orders->count() > 0)

                        <table class="table table-hover" id="table">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.users')</th>

                                <th>@lang('site.sellers')</th>

                                <th>@lang('site.address')</th>

                                <th>@lang('site.number')</th>

                                <th>@lang('site.total')</th>

                                <th>@lang('site.status')</th>

                                <th>@lang('site.date')</th>

                                <th>@lang('site.created_at')</th>


                                <th>@lang('site.action')</th>

                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($orders as $index=>$order)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ isset($order->user->name) ? $order->user->name :'' }}</td>

                                    <td>{{$order->store->name}}</td>
                                    <td>{{ isset($order->address->address) ? $order->address->address :'' }}</td>

                                    <td>{{ isset($order->number) ? $order->number :'' }}</td>
                                    <td>{{ isset($order->total) ? $order->total :'' }}</td>


                                    <td>
                                        <form action="{{ route('dashboard.orders.status', $order->id) }}" method="post"
                                              style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('POST') }}

                                            @if( $order->status=="accept")
                                                <input type="hidden" name="status" value="accept">
                                                <input type="hidden" name="id" value="{{$order->id}}">
                                                <button type="submit" class="btn btn-success update btn-sm">
                                                    <i class="fa fa-check"></i> @lang('site.accept')
                                                </button>

                                            @elseif( $order->status=="canceled")
                                                <input type="hidden" name="status" value="canceled">
                                                <input type="hidden" name="id" value="{{$order->id}}">
                                                <button type="submit" class="btn btn-info update btn-sm">
                                                    <i class="fa fa-reply"></i> @lang('site.canceled')
                                                </button>
                                            @elseif( $order->status=="waiting")
                                                <input type="hidden" name="status" value="waiting">
                                                <input type="hidden" name="id" value="{{$order->id}}">
                                                <button type="submit" class="btn btn-warning update btn-sm">
                                                    <i class="fa fa-info-circle"></i> @lang('site.waiting')
                                                </button>
                                            @endif

                                        </form><!-- end of form -->
                                    </td>


                                    <td>{{isset($order->date) ? $order->date :'' }}</td>

                                    <td>{{ isset($order->created_at) ? $order->created_at->diffForHumans() :''	 }}</td>


                                    {{--                                            @if (auth()->user()->hasPermission('delete_contacts'))--}}

                                    <td>


                                        @if (auth()->user()->hasPermission('update_consultations'))
                                            <a href="{{ route('dashboard.orders.show', $order->id) }}"
                                               class="btn btn-info btn-sm"><i
                                                    class="fa fa-address-book"></i> @lang('site.show')</a>
                                        @else
                                            <a href="#" class="btn btn-info btn-sm disabled"><i
                                                    class="fa fa-edit"></i> @lang('site.edit')</a>
                                        @endif

                                        <form action="{{ route('dashboard.orders.destroy', $order->id) }}" method="post"
                                              style="display: inline-block">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <button type="submit" class="btn btn-danger delete btn-sm"><i
                                                    class="fa fa-trash"></i> @lang('site.delete')</button>
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
