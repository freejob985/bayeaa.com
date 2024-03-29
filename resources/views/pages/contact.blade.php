{{--
 * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
--}}
@extends('layouts.master')

@section('search')
	@parent
	@includeFirst([config('larapen.core.customizedViewPath') . 'pages.inc.contact-intro', 'pages.inc.contact-intro'])
@endsection

@section('content')
	@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
	<div class="main-container">
		<div class="container">
			<div class="row clearfix">
				
				@if (isset($errors) and $errors->any())
					<div class="col-xl-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h5><strong>{{ t('oops_an_error_has_occurred') }}</strong></h5>
							<ul class="list list-check">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					</div>
				@endif

				@if (Session::has('flash_notification'))
					<div class="col-xl-12">
						<div class="row">
							<div class="col-xl-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif
				
				<div class="col-md-12">
					<div class="contact-form">
						<h5 class="list-title gray mt-0">
							<strong>{{ t('Contact Us') }}</strong>
						</h5>

						<form class="form-horizontal" method="post" action="{{ lurl(trans('routes.contact')) }}">
							{!! csrf_field() !!}
							<fieldset>
								<div class="row">
									<div class="col-md-6">
										<?php $firstNameError = (isset($errors) and $errors->has('first_name')) ? ' is-invalid' : ''; ?>
										<div class="form-group required">
											<input id="first_name" name="first_name" type="text" placeholder="{{ t('first_name') }}"
												   class="form-control{{ $firstNameError }}" value="{{ old('first_name') }}">
										</div>
									</div>

									<div class="col-md-6" style="
    display: none;
">
										<?php $lastNameError = (isset($errors) and $errors->has('last_name')) ? ' is-invalid' : ''; ?>
										<div class="form-group required">
											<input id="last_name" name="last_name" type="text" placeholder="{{ t('last_name') }}"
												   class="form-control{{ $lastNameError }}" value="{{ old('last_name') }}">
										</div>
									</div>

									<div class="col-md-6">
										<?php $companyNameError = (isset($errors) and $errors->has('company_name')) ? ' is-invalid' : ''; ?>
										<div class="form-group required">
											<input id="company_name" name="company_name" type="text" placeholder="{{ t('company_name') }}"
												   class="form-control{{ $companyNameError }}" value="{{ old('company_name') }}">
										</div>
									</div>

									<div class="col-md-6" style="
    display: none;
">
										<?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
										<div class="form-group required">
											<input id="email" name="email" type="text" placeholder="{{ t('email_address') }}" class="form-control{{ $emailError }}"
												   value="{{ old('email') }}">
										</div>
									</div>

									<div class="col-md-12">
										<?php $messageError = (isset($errors) and $errors->has('message')) ? ' is-invalid' : ''; ?>
										<div class="form-group required">
											<textarea class="form-control{{ $messageError }}" id="message" name="message" placeholder="{{ t('Message') }}"
													  rows="7">{{ old('message') }}</textarea>
										</div>
										
										
											<div class="form-group required">
																			  <input type="file" name="fileToUpload" id="fileToUpload">

										</div>
										
										
										
										
<br>
										@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.tools.recaptcha', 'layouts.inc.tools.recaptcha'])

										<div class="form-group">
											<button type="submit" class="btn btn-primary btn-lg">{{ t('submit') }}</button>
										</div>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script src="{{ url('assets/js/form-validation.js') }}"></script>
@endsection
