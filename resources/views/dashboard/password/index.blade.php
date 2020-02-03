@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @lang('site.password')

            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.change.password')}}"><i class="fa fa-dashboard"></i>@lang('site.password')</a></li>
                <li>@lang('site.password')</li>
            </ol>
        </section>
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.password')</h3>
                </div>
                <div class="box-body">
                    @include('partials._errors')
                    <form action="{{route('dashboard.change.password')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label>@lang('site.email')</label>
                            <input type="text" name="email" class="form-control">

                        </div>
                        <div class="form-group">
                            <label>@lang('site.password')</label>
                            <input type="password" name="password" class="form-control">

                        </div>
                        <div class="form-group">
                            <label>@lang('site.password_confirmation')</label>
                            <input type="password" name="password_confirmation" class="form-control">

                        </div>
                        <div class="form-group">

                            <button type="submit"  class="btn btn-primary">@lang('site.change_password')</button>

                        </div>
                    </form>


                </div>

            </div>
        </section>
    </div>
@endsection
