@php
    if (!isset($product_quantity_cart)) $product_quantity_cart = $product->quantity;
@endphp

{{-- Necessary for closing the outer form --}}
<button type="submit"></button>
</form>

<div id="productRegulatorDiv" class="mx-auto flex h-8 items-stretch justify-center text-gray-600">
    <form name="decreaseProduct" data-method="POST" data-action="@if ($product_quantity_cart < 2) {{ route('cart.removeProduct') }} @else {{ route('cart.decreaseProduct') }} @endif">
        @csrf
        <input class="hidden" type="text" name="product_id" value="{{ $product_id ?? $product->id}}" />
        <button class="flex items-center font-medium rounded-l-lg bg-gray-100 px-1.5 h-8 transition duration-200 hover:bg-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z" clip-rule="evenodd" />
            </svg>
        </button>
    </form>

    <div class="flex items-center bg-gray-50 px-4 font-bold text-xs uppercase transition">{{ $product_quantity_cart }}</div>

    <form name="increaseProduct" data-method="POST" data-action="{{ route('cart.increaseProduct') }}">
        @csrf
        <input class="hidden" type="text" name="product_id" value="{{ $product_id ?? $product->id}}" />
        <button class="flex items-center font-medium rounded-r-lg bg-gray-100 px-1.5 h-8 transition duration-200 hover:bg-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
            </svg>
        </button>
    </form>
</div>
