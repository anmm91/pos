@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.clients')</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a>
                </li>
                <li>
                    <a href="{{route('dashboard.clients.index')}}">@lang('site.clients')</a>
                </li>
                <li>@lang('site.clients')</li>
            </ol>
        </section>
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('site.add')</h3>

                </div>
                <div class="box-body">
                    @include('partials._errors')
                    <form method="post" action="{{route('dashboard.clients.store')}}">
                        @csrf
                        <div class="form-group">
                                <label>@lang('site.name')</label>
                                <input type="text" name="name" class="form-control" value="{{old('name')}}">
                        </div>
                        @for($i=0;$i<2;$i++)

                            <div class="form-group">
                                <label>@lang('site.phone')</label>
                                <input type="text" name="phone[]" class="form-control">
                            </div>

                        @endfor

                        <div class="form-group">
                            <label>@lang('site.address')</label>
                            <textarea class="form-control" name="address">

                            </textarea>
                        </div>



                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>@lang('site.add')
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </section>
    </div>
@endsection

