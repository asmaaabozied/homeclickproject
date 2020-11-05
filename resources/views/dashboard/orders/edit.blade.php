@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.orders')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.orders.index') }}"> @lang('site.orders')</a></li>
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

                    <form action="{{ route('dashboard.orders.update', $order->id) }}" enctype="multipart/form-data" method="post">

                        {{ csrf_field() }}
                        {{ method_field('put') }}




                        <div class="row">

                            <div class="form-group col-md-6">

                                <label>@lang('site.users')</label>

                                <select class="form-control select2" name="user_id" id="parent" required>
                                    <option selected disabled>{{trans('site.select')}}</option>
                                    @foreach($users as $id => $item)
                                        <option value="{{$id}}"   @if($order->user_id ==$id) selected @endif  >{{$item}}</option>
                                    @endforeach
                                </select>

                            </div>





                            <div class="form-group col-md-6">

                                <label>@lang('site.number')</label>

                                <input type="number" name="number" value="{{$order->number}}" class="form-control" required>

                            </div>

                        </div>


                        <div class="row">


                            <div class="form-group col-md-6">

                                <label>@lang('site.capons')</label>

                                <select class="form-control select2" name="capon_id" id="parent" required>
                                    <option selected disabled>{{trans('site.select')}}</option>
                                    @foreach($capons as $id => $item)
                                        <option value="{{$id}}"  @if($order->capon_id ==$id) selected @endif>{{$item}}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="form-group col-md-6">

                                <label>@lang('site.sellers')</label>

                                <select class="form-control select2" name="store_id" id="parent" required>
                                    <option selected disabled>{{trans('site.select')}}</option>
                                    @foreach($offer as $id => $item)
                                        <option value="{{$id}}"  @if($order->store_id ==$id) selected @endif >{{$item}}</option>
                                    @endforeach
                                </select>

                            </div>

                        </div>

                        <div class="row">


                            <div class="form-group col-md-6">

                                <label>@lang('site.total')</label>

                                <input type="number" name="total" value="{{$order->total}}" class="form-control" required>

                            </div>





                            <!-- Discount Start Date Field -->
                            <div class="form-group col-md-6">
                                <label>@lang('site.date')</label>

                                <input type="date" name="date" class="form-control date"  value="{{$order->date}}" required>


                            </div>

                            <!-- Discount End Date Field -->


                        </div>

                        <div class="row">



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

