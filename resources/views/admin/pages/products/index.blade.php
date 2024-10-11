@extends('admin.layouts.app')

@section('title', 'Список товаров')

@section('content')
    @if (session('status') && session('status_message'))
        <div class="mt-6 alert alert-{{ session('status') }}">
            {{ session('status_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
        </div>
    @endif

    <div class="page-header">
        <h1 class="page-title">Список всех товаров</h1>
        <div>
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                Создать <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('admin.product.create') }}">Создать товар</a></li>
            </ul>
        </div>
    </div>

    @if (!empty($products))
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="grid-margin">
                            <div class="panel panel-primary">
                                <div class="panel-body tabs-menu-body border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab5">
                                            <div class="table-responsive">
                                                <table id="data-table" class="table text-nowrap mb-0">
                                                    <thead class="">
                                                    <tr class="font-bold">
                                                        <th class="bg-transparent border-bottom-0 text-center">Бейдж</th>
                                                        <th class="bg-transparent border-bottom-0">Товар</th>
                                                        <th class="bg-transparent border-bottom-0 text-center">Категории</th>
                                                        <th class="bg-transparent border-bottom-0">Вес</th>
                                                        <th class="bg-transparent border-bottom-0">Цена</th>
                                                        <th class="bg-transparent border-bottom-0">Изменен</th>
                                                        <th class="bg-transparent border-bottom-0 text-center">Статус</th>
                                                        <th class="bg-transparent border-bottom-0 text-center"></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($products as $product)
                                                        <tr class="border-bottom hover:bg-indigo-900 hover:bg-opacity-30 @if(!empty($product->deleted_at) || !$product->active) text-gray-500 @endif">
                                                            <td class="text-center">
                                                                @if (!empty($product->label))
                                                                    <div class="flex mt-1 justify-center">
                                                                        @switch($product->label)
                                                                            @case('new')
                                                                                <span class="badge bg-success-transparent rounded-md text-xs text-success py-1 px-2">{{ mb_strtoupper($product->label) }}</span>
                                                                                @break
                                                                            @case('sale')
                                                                                <span class="badge bg-danger-transparent rounded-md text-xs text-danger py-1 px-2">{{ mb_strtoupper($product->label) }}</span>
                                                                                @break
                                                                        @endswitch
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="flex">
                                                                    <span class="avatar rounded-md" style="width: 2.5rem; background-image: url({{ asset($product->image) }})"></span>
                                                                    <div class="ml-3 mt-1.5">
                                                                        <span class="font-normal">{{ $product->name }}</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            @if(empty($only_trashed_products))
                                                                <td>
                                                                    <div class="flex mt-1.5 justify-center">
                                                                        @foreach ($product->categories as $category)
                                                                            @if ($loop->iteration > 1)
                                                                                <span>,&nbsp;</span>
                                                                            @endif
                                                                            <a href="javascript:void(0);" class="font-normal underline underline-offset-2">{{ $category->name }}</a>
                                                                        @endforeach
                                                                    </div>
                                                                </td>
                                                            @endif
                                                            <td>
                                                                <div class="flex mt-1.5">
                                                                    <span class="font-normal">{{ $product->weight }} гр.</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="flex mt-1.5">
                                                                    <span class="font-normal">{{ $product->price }} р.</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="flex mt-1.5">
                                                                    <span class="font-normal">{{ $product->updated_at->format('d/m/Y') }}</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="flex mt-1 justify-center">
                                                                    @if(!empty($product->deleted_at))
                                                                        <span class="badge bg-danger-transparent rounded-md text-xs text-danger py-1 px-2">Удален</span>
                                                                    @else
                                                                        @switch($product->active)
                                                                            @case(1)
                                                                                <span class="badge bg-success-transparent rounded-md text-xs text-success py-1 px-2">Вкл.</span>
                                                                                @break
                                                                            @default
                                                                                <span class="badge bg-default-transparent rounded-md text-xs text-default py-1 px-2">Выкл.</span>
                                                                        @endswitch
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @if(empty($product->deleted_at))
                                                                    <div class="flex mt-0.5">
                                                                        <a href="{{ route('admin.product.modify', ['product_id' => $product->id]) }}"
                                                                           class="btn text-primary btn-sm hover:border-1 hover:border-gray-600">
                                                                            <span class="fe fe-edit fs-18"></span>
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    <form method="POST" action="{{ route('admin.product.restore_product', ['product_id' => $product->id]) }}">
                                                                        @csrf

                                                                        <div class="flex mt-0.5">
                                                                            <button type="submit" class="btn text-success btn-sm hover:border-1 hover:border-gray-600">
                                                                                <span class="fe fe-rotate-ccw fs-18"></span>
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{ $products->links('pagination::bootstrap-5') }}
    @endif
@endsection
