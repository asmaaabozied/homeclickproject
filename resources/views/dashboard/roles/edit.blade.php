@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.roles')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.roles.index') }}"> @lang('site.roles')</a></li>
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

                    <form action="{{ route('dashboard.roles.update', $role->id) }}" method="post" enctype="multipart/form-data">

                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="form-group">
                            <label>@lang('site.name')</label>
                            <input type="text" name="name" class="form-control" value="{{ $role->name }}">
                        </div>

                        <div class="form-group">
                            <label>@lang('site.display_name')</label>
                            <input type="text" name="display_name" class="form-control" value="{{ $role->display_name }}">
                        </div>
                        <div class="form-group">
                            <label>@lang('site.description')</label>
                            <input type="text" name="description" class="form-control" value="{{ $role->description }}">
                        </div>


                        <div class="form-group">
                            <h3>@lang('site.permissions')</h3>
                            <div class="form-group">

                                <ul class="nav ">
                                <table class="table table-hover table-bordered">

          
                                    @foreach ($models as $index=>$model)
                                    <tr>
                                    <td>
                                          <li class="form-group {{ $index == 0 ? 'active' : '' }}">@lang('site.' . $model)</li>
                                          </td>
                                          <td>

                                        <div class="form-group {{ $index == 0 ? 'active' : '' }}" id="{{ $model }}">

                                            @foreach ($maps as $map)
                                            <label><input type="checkbox" name="permissions[]" {{ $role->hasPermission($map . '_' . $model) ? 'checked' : '' }} value="{{ $map . '_' . $model }}"> @lang('site.' . $map)</label>


                                            @endforeach

                                            </div>
                                            </td>

                                    </tr>
                                   @endforeach
                                </table>
                                </ul>
                                
                                <div class="tab-content">


                                </div><!-- end of tab content -->
                                
                            </div><!-- end of nav tabs -->
                            
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> @lang('site.edit')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
