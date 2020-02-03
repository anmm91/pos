@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @lang('site.categories')

            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
                <li>@lang('site.categories')</li>
            </ol>
        </section>
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.categories')<small> @lang('site.'.$categories->total())</small></h3>
                    <form action="{{route('dashboard.categories.index')}}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{request()->search}}">
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                                @if(auth()->user()->hasPermission('create_categories'))
                                <a href="{{route('dashboard.categories.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.create')</a>
                                @else
                                    <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus"></i> @lang('site.create')</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.name')</th>
                                <th>@lang('site.products_count')</th>
                                <th>@lang('site.related_products')</th>
                                <th>@lang('site.action')</th>
                            </tr>
                        </thead>
                        @if($categories->count() > 0)
                            @foreach($categories as $index=>$category)
                        <tbody>
                            <tr>
                                <td>{{$index + 1}}</td>
                                <td>{{$category->name}}</td>
                                <td>{{$category->products->count()}}</td>
                                <td><a href="{{route('dashboard.products.index',['category_id'=>$category->id])}}" class="btn btn-info btn-sm">@lang('site.related_products')</a></td>

                                <td>
                                    @if(auth()->user()->hasPermission('update_categories'))
                                        <a class="btn btn-success " href="{{route('dashboard.categories.edit',$category->id)}}"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                    @else
                                        <a class="btn btn-success btn-sm disabled" href="#">@lang('site.edit')</a>
                                    @endif
                                    @if(auth()->user()->hasPermission('delete_categories'))
                                        <form style="display: inline-block" method="post" action="{{route('dashboard.categories.destroy',$category->id)}}">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger delete"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                        </form>
                                    @else
                                        <button class="btn btn-danger disabled">@lang('site.delete')</button>
                                    @endif
                                </td>

                            </tr>
                        </tbody>
                        @endforeach

                        @else
                            <h3>@lang('site.no_data_found')</h3>
                        @endif
                    </table>
                    {{$categories->appends(request()->query())->links()}}
                </div>

            </div>
        </section>
    </div>
@endsection
