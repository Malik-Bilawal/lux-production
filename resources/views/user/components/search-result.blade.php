@if($products->count() > 0)
    @foreach($products as $product)
        @include("user.components.navbar-cards", ['product' => $product])
    @endforeach
@else
    <p class="text-gray-400 text-sm">No products found</p>
@endif
