@if(count($combinations[0]) > 0)

@php
$variant_product=Session::get('product_variant');
@endphp
<table class="table table-bordered aiz-table">
	<thead>
		<tr>
			<td class="text-center">
				{{translate('Variant')}}
			</td>
			<td class="text-center">
				{{translate('Action')}}
			</td>
		</tr>
	</thead>
	<tbody>
		@foreach ($combinations as $key => $combination)
		@php
		$sku = '';
		foreach (explode(' ', $product_name) as $key => $value) {
		$sku .= substr($value, 0, 1);
		}

		$str = '';
		foreach ($combination as $key => $item){
		if($key > 0 ){
		$str .= '-'.str_replace(' ', '', $item);
		$sku .='-'.str_replace(' ', '', $item);
		}
		else{
		if($colors_active == 1){
		$color_name = \App\Models\Color::where('code', $item)->first()->name;
		$str .= $color_name;
		$sku .='-'.$color_name;
		}
		else{
		$str .= str_replace(' ', '', $item);
		$sku .='-'.str_replace(' ', '', $item);
		}
		}
		}
		@endphp
		@if(strlen($str) > 0)
		<tr class="variant">
			<td>
				<label for="" class="control-label">{{ $str }} </label>
			</td>
			<td>


				@if($variant_product && (array_search($str, array_column($variant_product, 'variant_id')))>-1)

				<button type="button" class="btn btn-outline-danger variant-remove-btn" data-variant="{{ $str }}">Remove</button>
				@else
				<button type="button" class="btn btn-info variant-modal-btn" data-variant="{{ $str }}">Add Variant</button>
				@endif


			</td>

		</tr>
		@endif
		@endforeach
	</tbody>
</table>
@endif