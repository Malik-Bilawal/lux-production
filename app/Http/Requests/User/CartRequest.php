<?php
namespace App\Http\Requests\User;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    public function rules(): array
    {
        
        
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1', 'max:100'], 
        ];
    }
    protected function prepareForValidation()
{
    Log::info('CartRequest raw input:', $this->all());

    $this->merge([
        'product_id' => (int) $this->product_id,
        'quantity'   => (int) $this->quantity,
    ]);
}

}