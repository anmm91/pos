@extends('layouts.dashboard.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <small>@lang('site.orders') @lang('site.'.$orders->total())</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i>@lang('site.dashboard')</a></li>
                <li class="active">@lang('site.orders')</li>

            </ol>

        </section>
        <section class="content">
            <div class="row">
                <div class="col-sm-8">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title" style="margin-bottom: 10px">@lang('site.orders')</h3>
                            <form method="get" action="{{route('dashboard.orders.index')}}">
                                <div class="col-sm-8">
                                    <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{request()->search}}">
                                </div>
                                <div class="col-sm-4">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                                </div>

                            </form>
                        </div> <!-- end of box header -->
                        @if($orders->count() > 0)

                            <div class="box-body table-responsive">
                                <table class="table table-hover">
                                    <tr>
                                        <th>@lang('site.client_name')</th>
                                        <th>@lang('site.price')</th>
{{--                                        <th>@lang('site.status')</th>--}}
                                        <th>@lang('site.created_at')</th>
                                        <th>@lang('site.action')</th>
                                    </tr>

                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{$order->client->name}}</td>
                                            <td>{{number_format($order->total_price,2)}}</td>
{{--                                            <td>tets</td>--}}
                                            <td>{{$order->created_at->toFormattedDateString()}}</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm order-products"
                                                        data-url="{{route('dashboard.orders.products',$order->id)}}"
                                                        data-method="get"
                                                        >
                                                    <i class="fa fa-list"></i>
                                                    @lang('site.show')
                                                </button>
                                                @if(auth()->user()->hasPermission('update_orders'))
                                                <a
                                                    href="{{route('dashboard.orders.edit',$order->id)}}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fa fa-pencil"></i> edit</a>
                                                @else
                                                   <a href="#" class="btn btn-warning btn-sm disabled">
                                                       <i class="fa fa-pencil"></i> edit</a>
                                                @endif

                                                @if(auth()->user()->hasPermission('delete_orders'))
                                                    <form style="display: inline-block" method="post" action="{{route('dashboard.orders.destroy',$order->id)}}">
                                                        @csrf
                                                        @method('delete')
                                                        <button
                                                            type="submit"
                                                            class="btn btn-danger btn-sm">
                                                            <i class="fa fa-trash"></i> delete</button>
                                                    </form>
                                                    @else
                                                    <a href="#" class="btn btn-danger btn-sm disabled">
                                                        <i class="fa fa-trash"></i> delete
                                                    </a>
                                                    @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>

                                {{$orders->appends(request()->query())->links()}}

                            </div> <!-- end of table responsive -->
                        @else


                            <div class="box-body">
                                <h3>@lang('site.no_data_found')</h3>
                            </div>
                        @endif


                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">@lang('site.show_products')</h3>
                        </div>
                        <div class="box-body">
                            <div style="display:none;flex-direction:column; align-items:center" id="loading">
                                <div class="loader">
                                    <p style="margin-top: 10px">@lang('site.loading')</p>
                                </div>
                            </div>
                            <div id="order-product-list">

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </section>

    </div>
@endsection

