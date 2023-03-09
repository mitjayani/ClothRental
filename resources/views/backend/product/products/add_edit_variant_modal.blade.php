@section('modal')
<div class="modal fade" id="variant-modal" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content  h-100">
			<div class="modal-header">
				<h5 class="modal-title h6">{{translate('Add New Variant ddd')}}</h5>
				<button type="button" class="close" data-dismiss="modal"></button>
			</div>
			<div class="modal-body product-variant-modal-body">
				<form class="form form-horizontal mar-top variant-modal-form"  method="POST" enctype="multipart/form-data" id="variant-modal-form">
					<div class="row gutters-5">
						<div class="col-lg-8">
							@csrf
							<input type="hidden" name="added_by" value="admin">
							<input type="hidden" name="variant_id" >

							<div class="card">
								<div class="card-header">
									<h5 class="mb-0 h6">{{translate('Product Images')}}</h5>
								</div>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Thumbnail Image')}} <small>(300x300)</small></label>
										<div class="col-md-8">
											<div class="input-group" data-toggle="aizuploader" data-type="image">
												<div class="input-group-prepend">
													<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
												</div>
												<div class="form-control file-amount">{{ translate('Choose File') }}</div>
												<input type="hidden" name="thumbnail_img" class="selected-files">
											</div>
											<div class="file-preview box sm">
											</div>
											<small class="text-muted">{{translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.')}}</small>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Gallery Images')}} <small>(600x600)</small></label>
										<div class="col-md-8">
											<div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
												<div class="input-group-prepend">
													<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
												</div>
												<div class="form-control file-amount">{{ translate('Choose File') }}</div>
												<input type="hidden" name="photos" class="selected-files">
											</div>
											<div class="file-preview box sm">
											</div>
											<small class="text-muted">{{translate('These images are visible in product details page gallery. Use 600x600 sizes images.')}}</small>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<h5 class="mb-0 h6">{{translate('Product Videos')}}</h5>
								</div>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-md-3 col-from-label">{{translate('Video Provider')}}</label>
										<div class="col-md-8">
											<select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">
												<option value="youtube">{{translate('Youtube')}}</option>
												<option value="dailymotion">{{translate('Dailymotion')}}</option>
												<option value="vimeo">{{translate('Vimeo')}}</option>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-from-label">{{translate('Video Link')}}</label>
										<div class="col-md-8">
											<input type="text" class="form-control" name="video_link" placeholder="{{ translate('Video Link') }}">
											<small class="text-muted">{{translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.")}}</small>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<h5 class="mb-0 h6">{{translate('Product price + stock')}}</h5>
								</div>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-md-3 col-from-label">{{translate('Selling price')}} <span class="text-danger">*</span></label>
										<div class="col-md-6">
											<input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Selling price') }}" name="unit_price" class="form-control">
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-3 control-label" for="start_date">{{translate('Discount Date Range')}}</label>
										<div class="col-sm-9">
											<input type="text" class="form-control aiz-date-range" name="date_range" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-3 col-from-label">{{translate('Discount')}} <span class="text-danger">*</span></label>
										<div class="col-md-6">
											<input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Discount') }}" name="discount" class="form-control">
										</div>
										<div class="col-md-3">
											<select class="form-control aiz-selectpicker" name="discount_type">
												<option value="amount">{{translate('Flat')}}</option>
												<option value="percent">{{translate('Percent')}}</option>
											</select>
										</div>
									</div>

									@if(addon_is_activated('club_point'))
									<div class="form-group row">
										<label class="col-md-3 col-from-label">
											{{translate('Set Point')}}
										</label>
										<div class="col-md-6">
											<input type="number" lang="en" min="0" value="0" step="1" placeholder="{{ translate('1') }}" name="earn_point" class="form-control">
										</div>
									</div>
									@endif

									<div>
										<div class="form-group row">
											<label class="col-md-3 col-from-label">{{translate('Quantity')}} <span class="text-danger">*</span></label>
											<div class="col-md-6">
												<input type="number" lang="en" min="0" value="0" step="1" placeholder="{{ translate('Quantity') }}" name="current_stock" class="form-control">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-md-3 col-from-label">
												{{translate('SKU')}}
											</label>
											<div class="col-md-6">
												<input type="text" placeholder="{{ translate('SKU') }}" name="sku" class="form-control">
											</div>
										</div>
									</div>

								</div>
							</div>

							<div class="card">
								<div class="card-header">
									<h5 class="mb-0 h6">
										{{translate('Shipping Charges')}}
									</h5>
								</div>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-md-3 col-from-label">{{translate('Local Charge')}} </label>
										<div class="col-md-6">
											<input type="number" lang="en" placeholder="{{ translate('Local Charge') }}" name="local_delivery_charge" class="form-control">
										</div>
									</div>
									<div class="form-group row">

										<label class="col-md-3 col-from-label">{{translate('Zonal Charge')}}</label>
										<div class="col-md-6">
											<input type="number" lang="en" placeholder="{{ translate('Zonal Charge') }}" name="zonal_delivery_charge" class="form-control">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-from-label">{{translate('National Charge')}} </label>
										<div class="col-md-6">
											<input type="number" lang="en" placeholder="{{ translate('National Charge') }}" name="zonal_delivery_charge" class="form-control">
										</div>
									</div>
								</div>
							</div>

							<div class="card">
								<div class="card-header">
									<h5 class="mb-0 h6">{{translate('Product Packaging')}}</h5>
								</div>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-md-3 col-from-label">{{translate('Package Weight')}}</label>
										<div class="col-md-8">
											<input type="text" class="form-control" name="weight" placeholder="{{ translate('Package Weight in KG') }}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-from-label">{{translate('Package Length')}}</label>
										<div class="col-md-8">
											<input type="text" class="form-control" name="length" placeholder="{{ translate('Package Length in CM') }}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-from-label">{{translate('Package Breadth')}}</label>
										<div class="col-md-8">
											<input type="text" class="form-control" name="breadth" placeholder="{{ translate('Package Breadth in CM') }}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-from-label">{{translate('Package Height')}}</label>
										<div class="col-md-8">
											<input type="text" class="form-control" name="height" placeholder="{{ translate('Package Height in CM') }}">
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<h5 class="mb-0 h6">{{translate('Product Description')}}</h5>
								</div>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-md-3 col-from-label">{{translate('Description')}}</label>
										<div class="col-md-8">
											<textarea class="aiz-text-editor-md" name="description"></textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<h5 class="mb-0 h6">{{translate('PDF Specification')}}</h5>
								</div>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('PDF Specification')}}</label>
										<div class="col-md-8">
											<div class="input-group" data-toggle="aizuploader" data-type="document">
												<div class="input-group-prepend">
													<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
												</div>
												<div class="form-control file-amount">{{ translate('Choose File') }}</div>
												<input type="hidden" name="pdf" class="selected-files">
											</div>
											<div class="file-preview box sm">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<h5 class="mb-0 h6">{{translate('SEO Meta Tags')}}</h5>
								</div>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-md-3 col-from-label">{{translate('Meta Title')}}</label>
										<div class="col-md-8">
											<input type="text" class="form-control" name="meta_title" placeholder="{{ translate('Meta Title') }}">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-from-label">{{translate('Description')}}</label>
										<div class="col-md-8">
											<textarea name="meta_description" rows="8" class="form-control"></textarea>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Meta Image') }}</label>
										<div class="col-md-8">
											<div class="input-group" data-toggle="aizuploader" data-type="image">
												<div class="input-group-prepend">
													<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
												</div>
												<div class="form-control file-amount">{{ translate('Choose File') }}</div>
												<input type="hidden" name="meta_img" class="selected-files">
											</div>
											<div class="file-preview box sm">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="card">
								<div class="card-header">
									<h5 class="mb-0 h6">{{translate('Tax Details')}}</h5>
								</div>
								<div class="card-body">
									@foreach(\App\Models\Tax::where('tax_status', 1)->get() as $tax)
									<label for="name">
										{{$tax->name}}
										<input type="hidden" value="{{$tax->id}}" name="tax_id[]">
									</label>

									<div class="form-row">
										<div class="form-group col-md-6">
											<input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Tax') }}" name="tax[]" class="form-control">
										</div>
										<div class="form-group col-md-6">
											<select class="form-control aiz-selectpicker" name="tax_type[]">
												<option value="amount">{{translate('Flat')}}</option>
												<option value="percent">{{translate('Percent')}}</option>
											</select>
										</div>
									</div>
									@endforeach
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<h5 class="mb-0 h6">{{translate('Low Stock Quantity Warning')}}</h5>
								</div>
								<div class="card-body">
									<div class="form-group mb-3">
										<label for="name">
											{{translate('Quantity')}}
										</label>
										<input type="number" name="low_stock_quantity" value="1" min="0" step="1" class="form-control">
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-header">
									<h5 class="mb-0 h6">{{translate('Shipping Configration')}}</h5>
								</div>
								<div class="card-body">
									<div class="form-group mb-3">
										<label for="name">
											{{translate('Procedure SLA')}}
										</label>
										<div class="input-group">
											<input type="number" class="form-control" name="est_shipping_days" min="1" step="1" placeholder="{{translate('Shipping Days')}}">
											<div class="input-group-prepend">
												<span class="input-group-text" id="inputGroupPrepend">{{translate('Days')}}</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
				<button type="button" name="button" value="publish" class="btn btn-success action-btn add-variant-btn">{{ translate('Add') }}</button>
			</div>

		</div>
	</div>
</div>
@endsection