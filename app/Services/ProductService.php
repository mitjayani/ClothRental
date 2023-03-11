<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Color;
use App\Models\User;
use App\Utility\ProductUtility;
use Combinations;
use Illuminate\Support\Str;

class ProductService
{
    public function store(array $data)
    {
        $collection = collect($data);
        $approved = 1;
        $variantBasedProduct = array();
        $variant_id_arr = [];

        if (isset($collection['has_variant_product'])) {
            $newcollection = $collection['variants_data'][0];
            $newcollection['category_id'] = $collection['category_id'];
            $newcollection['brand_id'] = $collection['brand_id'];
            $newcollection['name'] = $collection['name'];
            $newcollection['unit'] = $collection['unit'];
            $newcollection['tags'] = $collection['tags'];
            $newcollection['colors'] = $collection['colors'];
            $newcollection['choice_no'] = isset($collection['choice_no']) ? $collection['choice_no'] : [];
            $newcollection['choice_attributes'] = isset($collection['choice_attributes']) ? $collection['choice_attributes'] : [];
            $newcollection['button'] = $collection['button'];
            $newcollection['variants_data'] = $collection['variants_data'];
            $newcollection['variants_ids'] = array_column($collection['variants_data'], "variant_id");

            if (isset($collection['choice_no']) && $collection['choice_no']) {
                foreach ($collection['choice_no'] as $key => $no) {
                    $str = 'choice_options_' . $no;
                    $newcollection[$str] = $collection[$str];
                }
            }
            $collection = collect($newcollection);
            $variantBasedProduct = $newcollection;
            unset($variantBasedProduct['variants_data']);
        }
        $variations = $collection['variant_id'];
        // dd($variations, $collection);
        unset($collection['tax_id']);
        unset($collection['tax']);
        unset($collection['tax_type']);

        if (auth()->user()->user_type == 'seller') {
            $user_id = auth()->user()->id;
            if (get_setting('product_approve_by_admin') == 1) {
                $approved = 0;
            }
        } else {
            $user_id = User::where('user_type', 'admin')->first()->id;
        }
        $tags = array();
        if ($collection['tags'] && $collection['tags'][0] != null) {
            foreach (json_decode($collection['tags'][0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $collection['tags'] = implode(',', $tags);
        $discount_start_date = null;
        $discount_end_date   = null;
        if ($collection['date_range'] != null) {
            $date_var               = explode(" to ", $collection['date_range']);
            $discount_start_date = strtotime($date_var[0]);
            $discount_end_date   = strtotime($date_var[1]);
        }
        unset($collection['date_range']);

        if ($collection['meta_title'] == null) {
            $collection['meta_title'] = $collection['name'];
        }
        if ($collection['meta_description'] == null) {
            $collection['meta_description'] = strip_tags($collection['description']);
        }

        if ($collection['meta_img'] == null) {
            $collection['meta_img'] = $collection['thumbnail_img'];
        }


        $shipping_cost = 0;
        if (isset($collection['shipping_type'])) {
            if ($collection['shipping_type'] == 'free') {
                $shipping_cost = 0;
            } elseif ($collection['shipping_type'] == 'flat_rate') {
                $shipping_cost = $collection['flat_shipping_cost'];
            }
        }
        unset($collection['flat_shipping_cost']);

        $slug = Str::slug($collection['name']);
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = ($same_slug_count > -1) ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $colors = json_encode(array());
        if (
            // isset($collection['colors_active']) &&
            // $collection['colors_active'] &&
            $collection['colors'] &&
            count($collection['colors']) > 0
        ) {

            $variantColorsArr = array();
            foreach ($collection['variants_ids'] as $value) {
                $variantColorsArr = array_merge($variantColorsArr, explode('-', $value));
            }
            $colors_name = Color::whereIn('name', $variantColorsArr)->get();

            $variant_colors_code = $colors_name->pluck('code')->toArray();
            $allColors = $collection['colors'];

            $intersect = array_intersect($allColors, $variant_colors_code);
            $filtered = array_filter($allColors, function ($color) use ($intersect) {
                return in_array($color, $intersect);
            });

            $colors = json_encode($filtered);
        }


        $options = ProductUtility::get_attribute_options($collection);

        $combinations = Combinations::makeCombinations($options);
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = ProductUtility::get_combination_string($combination, $collection);

                unset($collection['price_' . str_replace('.', '_', $str)]);
                unset($collection['sku_' . str_replace('.', '_', $str)]);
                unset($collection['qty_' . str_replace('.', '_', $str)]);
                unset($collection['img_' . str_replace('.', '_', $str)]);
            }
        }

        unset($collection['colors_active']);

        $choice_options = array();
        if (isset($collection['choice_no']) && $collection['choice_no']) {
            $str = '';
            $item = array();
            foreach ($collection['choice_no'] as $key => $no) {
                $str = 'choice_options_' . $no;
                $item['attribute_id'] = $no;
                $attribute_data = array();

                foreach ($collection[$str] as $key => $eachValue) {
                    array_push($attribute_data, $eachValue);
                }
                // unset($collection[$str]);
                $item['values'] = $attribute_data;
                array_push($choice_options, $item);
            }
        }
        $choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);
        if (isset($collection['choice_no']) && $collection['choice_no']) {
            $attributes = json_encode($collection['choice_no']);
        } else {
            $attributes = json_encode(array());
        }
        $published = 1;
        if ($collection['button'] == 'unpublish' || $collection['button'] == 'draft') {
            $published = 0;
        }
        $data = $collection->merge(compact(
            'user_id',
            'approved',
            'discount_start_date',
            'discount_end_date',
            'shipping_cost',
            'slug',
            'colors',
            'choice_options',
            'variations',
            'attributes',
            'published',
        ))->toArray();

        if (isset($collection['variant_product'])) {
            Product::create($data);
        } else {
            $collection['variation_parent_product_data'] =   Product::create($data);
        }

        if (isset($collection['variants_data'])) {
            $totalVariant = count($collection['variants_data']);
            for ($i = 1; $i < $totalVariant; $i++) {
                $variantData = array_merge($variantBasedProduct, $collection['variants_data'][$i]);
                $variantData['variation_parent_product_data'] = $collection['variation_parent_product_data'];
                $variantData['variation_parent_product_id'] =  $collection['variation_parent_product_data']['id'];
                $this->store($variantData);
            }
        }
        return $collection['variation_parent_product_data'];
    }



























































    public function update(array $data, Product $product)
    {
        $collection = collect($data);

        $slug = Str::slug($collection['name']);
        $slug = $collection['slug'] ? Str::slug($collection['slug']) : Str::slug($collection['name']);
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count > 1 ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        if (addon_is_activated('refund_request') && !isset($collection['refundable'])) {
            $collection['refundable'] = 0;
        }

        if (!isset($collection['is_quantity_multiplied'])) {
            $collection['is_quantity_multiplied'] = 0;
        }

        if (!isset($collection['cash_on_delivery'])) {
            $collection['cash_on_delivery'] = 0;
        }
        if (!isset($collection['featured'])) {
            $collection['featured'] = 0;
        }
        if (!isset($collection['todays_deal'])) {
            $collection['todays_deal'] = 0;
        }

        if ($collection['lang'] != env("DEFAULT_LANGUAGE")) {
            unset($collection['name']);
            unset($collection['unit']);
            unset($collection['description']);
        }
        unset($collection['lang']);

        $tags = array();
        if ($collection['tags'][0] != null) {
            foreach (json_decode($collection['tags'][0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $collection['tags'] = implode(',', $tags);
        $discount_start_date = null;
        $discount_end_date   = null;
        if ($collection['date_range'] != null) {
            $date_var               = explode(" to ", $collection['date_range']);
            $discount_start_date = strtotime($date_var[0]);
            $discount_end_date   = strtotime($date_var[1]);
        }
        unset($collection['date_range']);

        if ($collection['meta_title'] == null) {
            $collection['meta_title'] = $collection['name'];
        }
        if ($collection['meta_description'] == null) {
            $collection['meta_description'] = strip_tags($collection['description']);
        }

        if ($collection['meta_img'] == null) {
            $collection['meta_img'] = $collection['thumbnail_img'];
        }

        $shipping_cost = 0;
        if (isset($collection['shipping_type'])) {
            if ($collection['shipping_type'] == 'free') {
                $shipping_cost = 0;
            } elseif ($collection['shipping_type'] == 'flat_rate') {
                $shipping_cost = $collection['flat_shipping_cost'];
            }
        }
        unset($collection['flat_shipping_cost']);

        $colors = json_encode(array());
        if (
            isset($collection['colors_active']) &&
            $collection['colors_active'] &&
            $collection['colors'] &&
            count($collection['colors']) > 0
        ) {
            $colors = json_encode($collection['colors']);
        }

        $options = ProductUtility::get_attribute_options($collection);
        $combinations = Combinations::makeCombinations($options);
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = ProductUtility::get_combination_string($combination, $collection);

                unset($collection['price_' . str_replace('.', '_', $str)]);
                unset($collection['sku_' . str_replace('.', '_', $str)]);
                unset($collection['qty_' . str_replace('.', '_', $str)]);
                unset($collection['img_' . str_replace('.', '_', $str)]);
            }
        }

        unset($collection['colors_active']);

        $choice_options = array();
        if (isset($collection['choice_no']) && $collection['choice_no']) {
            $str = '';
            $item = array();
            foreach ($collection['choice_no'] as $key => $no) {
                $str = 'choice_options_' . $no;
                $item['attribute_id'] = $no;
                $attribute_data = array();
                // foreach (json_decode($request[$str][0]) as $key => $eachValue) {
                foreach ($collection[$str] as $key => $eachValue) {
                    // array_push($data, $eachValue->value);
                    array_push($attribute_data, $eachValue);
                }
                unset($collection[$str]);

                $item['values'] = $attribute_data;
                array_push($choice_options, $item);
            }
        }

        $choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);

        if (isset($collection['choice_no']) && $collection['choice_no']) {
            $attributes = json_encode($collection['choice_no']);
            unset($collection['choice_no']);
        } else {
            $attributes = json_encode(array());
        }

        unset($collection['button']);

        $data = $collection->merge(compact(
            'discount_start_date',
            'discount_end_date',
            'shipping_cost',
            'slug',
            'colors',
            'choice_options',
            'attributes',
        ))->toArray();

        $product->update($data);

        return $product;
    }
}
