@extends('layouts.dashboard.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                @lang('site.clients')

            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
                <li>@lang('site.clients')</li>
            </ol>
        </section>
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.clients')<small> @lang('site.'.$clients->total())</small></h3>
                    <form action="{{route('dashboard.clients.index')}}" method="get">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{request()->search}}">
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                                @if(auth()->user()->hasPermission('create_clients'))
                                <a href="{{route('dashboard.clients.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.create')</a>
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
                                <th>@lang('site.phone')</th>
                                <th>@lang('site.address')</th>
                                <th>@lang('site.orders')</th>
                                <th>@lang('site.action')</th>
                            </tr>
                        </thead>
                        @if($clients->count() > 0)
                            @foreach($clients as $index=>$client)
                        <tbody>
                            <tr>
                                <td>{{$index + 1}}</td>
                                <td>{{$client->name}}</td>
{{--                                array_filter used to filter (0,_,-)--}}
                                <td>{{is_array($client->phone)?implode($client->phone,'-'):$client->phone}}</td>
                                <td>{{$client->address}}</td>
                                <td>
                                    @if(auth()->user()->hasPermission('create_orders'))
                                    <a href="{{route('dashboard.clients.order.create',$client->id)}}" class="btn btn-info">@lang('site.add_order')</a>
                                    @else
                                        <a href="#" class="btn btn-info disabled">@lang('site.add_order')</a>
                                    @endif
                                </td>
                                <td>
                                    @if(auth()->user()->hasPermission('update_clients'))
                                        <a class="btn btn-success " href="{{route('dashboard.clients.edit',$client->id)}}"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                    @else
                                        <a class="btn btn-success btn-sm disabled" href="#">@lang('site.edit')</a>
                                    @endif
                                    @if(auth()->user()->hasPermission('delete_clients'))
                                        <form style="display: inline-block" method="post" action="{{route('dashboard.clients.destroy',$client->id)}}">
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
                    {{$clients->appends(request()->query())->links()}}
                </div>

            </div>
        </section>
    </div>
@endsection
