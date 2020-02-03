@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.categories')</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a>
                </li>
                <li>
                    <a href="{{route('dashboard.categories.index')}}">@lang('site.categories')</a>
                </li>
                <li>@lang('site.categories')</li>
            </ol>
        </section>
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('site.add')</h3>

                </div>
                <div class="box-body">
                    @include('partials._errors')
                    <form method="post" action="{{route('dashboard.categories.store')}}">
                        @csrf
                        @foreach(config('translatable.locales') as $locale)
                            <div class="form-group">
{{--                                site.en.name--}}
                                <label>@lang('site.'. $locale.'.name')</label>
{{--                                en['name']--}}
                                <input type="text" name="{{$locale}}[name]" class="form-control" value="{{old($locale.'.name')}}">
                            </div>
                        @endforeach


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

