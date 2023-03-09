<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required|max:255',
            'category_id'   => 'required',
            'unit'          => 'required',
            // 'thumbnail_img'  => 'required|max:20000||required_if:has_variant_product,null',
            // 'photos.*' => 'required|max:20000|required_without:has_variant_product',
            // 'min_qty'       => 'numeric|required_without:has_variant_product',
            // 'unit_price'    => 'numeric|required_without:has_variant_product',
            // 'discount'      => 'numeric|required_without:has_variant_product', //|sometimes|lt:unit_price
            // 'current_stock' => 'numeric|required_without:has_variant_product',
        ];
    }

    /**
     * Get the validation messages of rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'             => 'Product name is required',
            'category_id.required'      => 'Category is required',
            'unit.required'             => 'Unit field is required',
            'min_qty.required'          => 'Minimum purchase quantity is required',
            'min_qty.numeric'           => 'Minimum purchase must be numeric',
            'unit_price.required'       => 'Unit price is required',
            'unit_price.numeric'        => 'Unit price must be numeric',
            'discount.required'         => 'Discount is required',
            'discount.numeric'          => 'Discount must be numeric',
            'discount.lt:unit_price'    => 'Discount can not be gretaer than unit price',
            'current_stock.required'    => 'Current stock is required',
            'current_stock.numeric'     => 'Current stock must be numeric',
        ];
    }
}
