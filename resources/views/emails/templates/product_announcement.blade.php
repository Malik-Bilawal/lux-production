<div style="background-color: #1a1a1a; border: 1px solid #2a2a2a; padding: 0; margin-bottom: 20px;">
    
    @if($product->image || $product->image_url)
        <div style="position: relative; width: 100%;">
            <div style="position: absolute; top: 10px; left: 10px;">
                @if($product->is_new_arrival)
                    <span style="background-color: #050505; color: #FFFFFF; border: 1px solid #333; font-size: 10px; padding: 4px 8px; letter-spacing: 1px; text-transform: uppercase; font-family: 'Montserrat', sans-serif;">New Drop</span>
                @endif
                @if($product->is_top_selling)
                    <span style="background-color: #D4AF37; color: #000000; font-size: 10px; padding: 4px 8px; letter-spacing: 1px; text-transform: uppercase; font-weight: bold; font-family: 'Montserrat', sans-serif;">Best Seller</span>
                @endif
            </div>

            <img src="{{ $product->image_url ?? $product->image }}" alt="{{ $product->name }}" style="width: 100%; height: auto; display: block; border-bottom: 1px solid #2a2a2a;">
        </div>
    @endif

    <div style="padding: 25px;">
        
        <p style="color: #9CA3AF; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;">
            {{ $product->category->name ?? 'Collection' }} 
            @if(is_array($product->tags) && count($product->tags) > 0)
                // {{ $product->tags[0] }}
            @endif
        </p>

        <h2 style="color: #FFFFFF; font-family: 'Cinzel', serif; font-size: 24px; font-weight: 400; margin: 0 0 5px 0;">
            {{ $product->name }}
        </h2>
        
        @if($product->title)
            <p style="color: #666; font-size: 12px; margin: 0 0 15px 0; font-style: italic; font-family: 'Playfair Display', serif;">
                {{ $product->title }}
            </p>
        @endif

        <div style="color: #cccccc; font-size: 13px; line-height: 1.6; margin-bottom: 20px;">
            {!! Str::limit(strip_tags($product->description), 120) !!}
        </div>

        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-top: 1px solid #2a2a2a; border-bottom: 1px solid #2a2a2a; margin-bottom: 20px;">
            <tr>
                <td style="padding: 15px 0;">
                    <span style="color: #D4AF37; font-size: 20px; font-family: 'Cinzel', serif; font-weight: 700;">
                        ${{ number_format($product->price, 2) }}
                    </span>
                    
                    @if($product->cut_price && $product->cut_price > $product->price)
                        <span style="color: #666; text-decoration: line-through; font-size: 14px; margin-left: 10px;">
                            ${{ number_format($product->cut_price, 2) }}
                        </span>
                        
                        <span style="display: block; color: #4ade80; font-size: 10px; margin-top: 5px; text-transform: uppercase; letter-spacing: 1px;">
                            Member Saving: ${{ number_format($product->cut_price - $product->price, 2) }}
                        </span>
                    @endif
                </td>
                
                <td align="right" style="padding: 15px 0;">
                     @if($product->stock_quantity > 0 && $product->stock_quantity < 10)
                        <span style="color: #ef4444; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; border: 1px solid #ef4444; padding: 4px 8px;">
                            Only {{ $product->stock_quantity }} Left
                        </span>
                    @elseif($product->stock_quantity == 0)
                        <span style="color: #666; font-size: 10px; text-transform: uppercase;">Sold Out</span>
                    @else
                        <span style="color: #9CA3AF; font-size: 10px; text-transform: uppercase;">In Stock</span>
                    @endif
                </td>
            </tr>
        </table>

        <a href="{{ route('products.show', $product->slug) }}" style="display: block; text-align: center; background-color: #D4AF37; color: #000000; padding: 14px 0; text-decoration: none; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; font-family: 'Montserrat', sans-serif; transition: background 0.3s;">
            Secure This Item
        </a>

    </div>
</div>