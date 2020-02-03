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

                <li>@lang('site.add_order')</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title" style="margin-bottom: 10px">@lang('site.categories')</h3>
                        </div>
                        <div class="box-body">
                            @foreach( $categories as $category)
                            <div class="panel-group">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" href="#{{str_replace(' ','-',$category->name)}}">{{$category->name}}</a>
                                        </h4>

                                    </div>
                                    <div id="{{str_replace(' ','-',$category->name)}}" class="panel-collapse collapse">
                                        <div class="panel-body">
                                        @if($category->products->count() > 0)
                                            <table class="table table-hover">
                                                <tr>
                                                    <td>@lang('site.name')</td>
                                                    <td>@lang('site.stock')</td>
                                                    <td>@lang('site.price')</td>
                                                    <td>@lang('site.add')</td>
                                                </tr>
                                                @foreach($category->products as $product)
                                                    @if($product->stock > 0)
                                                    <tr>
                                                        <td>{{$product->name}}</td>
                                                        <td>{{$product->stock >= 0 ? $product->stock : '0'}}</td>
                                                        <td>{{ number_format($product->sale_price, 2) }}</td>
                                                        <td>
                                                            <a href=""
                                                               id="product-{{ $product->id }}"
                                                               data-name="{{ $product->name }}"
                                                               data-id="{{ $product->id }}"
                                                               data-price="{{ $product->sale_price }}"
                                                               class="btn btn-success btn-sm add-product-btn test">
                                                                <i class="fa fa-plus"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                            @else
                                            <h5>@lang('site.no_records')</h5>
                                        @endif

                                    </div>
                                    </div>
                                </div>
                            </div>

                            @endforeach
                        </div>

                    </div>
                </div><!-- end of col -->
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">@lang('site.orders')</h3>
                        </div>
                        <div class="box-body">
                            @include('partials._errors')
                            <form method="post" action="{{route('dashboard.clients.order.store',$client->id)}}">
                            @csrf
                             <table class="table table-hover">
                                 <thead>
                                 <tr>
                                     <td>@lang('site.product')</td>
                                     <td>@lang('site.quantity')</td>
                                     <td>@lang('site.price')</td>
                                 </tr>
                                 </thead>
                                 <tbody class="order-list">

                                 </tbody>

                             </table><!--end of table -->
                                <h4>@lang('site.total') : <span class="total-price">0</span></h4>
                                <button class="btn btn-primary btn-block disabled" id="add-order-form-btn"><i class="fa fa-plus"></i> @lang('site.add_order')</button>
                            </form>
                        </div><!-- end of box body -->

                    </div><!-- end of box -->
                </div><!-- end of col -->
            </div>
        </section>




    </div>

@endsection

