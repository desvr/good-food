@extends('admin.layouts.app')

@php
if(!empty($product)) {
    $title = 'Редактирование товара ' . $product->name ?? '';
} else {
    $title = 'Создание товара';
}

$badge_label_list = App\Enum\ProductBadge::getBadgeLabelList();
$current_badge_label = old('label') ?? $product->label ?? '';
@endphp

@section('title', $title)

@section('content')
    <div class="page-header">
        <h1 class="page-title">{{ $title }}</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form method="POST" enctype="multipart/form-data" action="@if(!empty($product)) {{ route('admin.product.update', ['product_id' => $product->id ?? 0]) }} @else {{ route('admin.product.store') }} @endif">
                    @csrf

                    <div class="card-header">
                        <div class="card-title col-sm-6 col-md-8 col-xl-9"><b>
                            @if(!empty($product))
                                Товар: {{ $product->name ?? '' }}
                            @else
                                Новый товар
                            @endif
                        </b></div>

                        <div class="row col-sm-6 col-md-4 col-xl-3 right-align px-0">
                            <label for="active" class="col-sm-6 col-xl-6 form-label">Статус:</label>
                            <div class="col-sm-6 col-xl-6 left-align px-0">
                                <select id="active" name="active" class="form-control form-select form-select-sm select2" data-bs-placeholder="Статус товара">
                                    <option selected value="1">Включен</option>
                                    <option @selected(isset($product->active) && $product->active !== 1) value="0">Выключен</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    {{ $error }} <br>
                                @endforeach
                            </div>
                        @endif

                        @if (session('status') && session('status_message'))
                            <div class="alert alert-{{ session('status') }}">
                                {{ session('status_message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
                            </div>
                        @endif

                            <div class="panel panel-primary">
                                {{-- Tabs --}}
                                <div class="tab-menu-heading tab-menu-heading-boxed pb-4">
                                    <div class="tabs-menu tabs-menu-border">
                                        <ul class="nav panel-tabs">
                                            <li><a href="#tab_settings" class="active" data-bs-toggle="tab"><span><i class="fe fe-settings me-1"></i></span>Основные настройки</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="panel-body tabs-menu-body">
                                    <div class="tab-content">
                                        {{-- Settings tab --}}
                                        <div class="tab-pane active" id="tab_settings">
                                            <div class="row mb-4">
                                                <label for="name" class="col-md-3 form-label">Наименование*:</label>
                                                <div class="col-md-9">
                                                    <input id="name" required name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Наименование товара" value="{{ old('name') ?? $product->name ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="slug" class="col-md-3 form-label">URL SLUG:</label>
                                                <div class="form-group col-md-9">
                                                    <div class="input-group from-control-sm disabled">
                                                        <span class="input-group-text" id="basic-addon1">/</span>
                                                        <input id="slug"
                                                               name="slug"
                                                               type="text"
                                                               placeholder="В случае отсутствия, формируется автоматически"
                                                               class="form-control @error('slug') is-invalid @enderror"
                                                               @if(!empty($product)) readonly="" @endif
                                                               value="{{ old('slug') ?? $product->slug ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="categories" class="col-md-3 form-label">Категория:</label>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <select id="categories" name="categories" class="form-control select2">
                                                            @foreach($categories as $category)
                                                                <option @selected(in_array($category->slug, old('category') ? [old('category')] : $product_categories ?? [])) value="{{ $category->id }}">
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="label" class="col-md-3 form-label">Бейдж:</label>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <select id="label" name="label" class="form-control select2">
                                                            <option @selected(!in_array($current_badge_label, $badge_label_list)) value="">Отсутствует</option>
                                                            @foreach($badge_label_list as $badge_label_key => $badge_label_value)
                                                                <option @selected($current_badge_label === $badge_label_value) value={{$badge_label_value}}>{{$badge_label_key}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="description" class="col-md-3 form-label">Описание:</label>
                                                <div class="col-md-9 mb-4">
                                                    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Описание товара до 512 символов" rows="2">{{ old('description') ?? $product->description ?? '' }}</textarea>
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <label for="weight" class="col-md-3 form-label">Вес (в граммах):</label>
                                                <div class="col-md-9">
                                                    <input id="weight" name="weight" type="number" class="form-control @error('weight') is-invalid @enderror" placeholder="Вес" value="{{ old('weight') ?? $product->weight ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <label for="calories" class="col-md-3 form-label">Калории (кКал/100 г.):</label>
                                                <div class="col-md-9">
                                                    <input id="calories" name="calories" type="text" class="form-control @error('calories') is-invalid @enderror" placeholder="Калории" value="{{ old('calories') ?? $product->calories ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <label for="price" class="col-md-3 form-label">Цена*:</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <span class="input-group-text">₽</span>
                                                        <input id="price" name="price" required type="number" class="form-control br-0 @error('price') is-invalid @enderror" aria-label="Цена" value="{{ old('price') ?? $product->price ?? '' }}">
                                                        <span class="input-group-text">.00</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label for="image" class="col-md-3 form-label">Главное изображение:</label>
                                                <div class="col-md-9">
                                                    <input type="file"
                                                           id="image"
                                                           name="image"
                                                           class="dropify"
                                                           onchange="readURL(this);"
                                                           data-height="200"
                                                           data-allowed-file-extensions="jpg jpeg png"
                                                           data-default-file="{{ asset($product->image ?? '') }}"
                                                           data-max-file-size="2M"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                <a href="@if(!empty($product)) {{ route('admin.product.modify', ['product_id' => $product->id]) }} @else {{ route('admin.product.create') }} @endif" class="btn btn-default">Сбросить изменения</a>
                                @if(!empty($product))
                                    <a class="modal-effect btn btn-danger d-grid mb-3 float-end" data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal_product_delete">Удалить товар</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(!empty($product))
        <div class="modal fade" id="modal_product_delete">
            <div class="modal-dialog modal-dialog-centered text-center" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">Удаление товара</h6>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <h4 class="font-bold text-xl pb-4">Вы действительно хотите удалить товар?</h4>
                        <p>Категория товара будет сброшена.</p>
                        <p>Вернуть удаленный товар можно на <a class="underline" target="_blank" href="{{ route('admin.product.index') }}">странице всех товаров</a>.</p>
                    </div>
                    <div class="modal-footer justify-between">
                        <form method="POST" action="{{ route('admin.product.delete', ['product_id' => $product->id]) }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">Удалить товар</button>
                        </form>
                        <button class="btn btn-light" data-bs-dismiss="modal">Отмена</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <!-- INTERNAL WYSIWYG Editor JS -->
    <script src="{{ asset("admin/plugins/wysiwyag/jquery.richtext.js") }}"></script>
    <script src="{{ asset("admin/plugins/wysiwyag/wysiwyag.js") }}"></script>

    <!-- INTERNAL File-Uploads Js-->
    <script src="{{ asset("admin/plugins/fancyuploder/jquery.ui.widget.js") }}"></script>
    <script src="{{ asset("admin/plugins/fancyuploder/jquery.fileupload.js") }}"></script>
    <script src="{{ asset("admin/plugins/fancyuploder/jquery.iframe-transport.js") }}"></script>
    <script src="{{ asset("admin/plugins/fancyuploder/jquery.fancy-fileupload.js") }}"></script>
    <script src="{{ asset("admin/plugins/fancyuploder/fancy-uploader.js") }}"></script>

    <!-- FILE UPLOADES JS -->
    <script src="{{ asset("admin/plugins/fileuploads/js/fileupload.js") }}"></script>
    <script src="{{ asset("admin/plugins/fileuploads/js/file-upload.js") }}"></script>
@endpush
