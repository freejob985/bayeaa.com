<div class="modal fade" id="contactUser" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title">
					<i class="icon-mail-2"></i> {{ t('contact_advertiser') }}
					@if (isset($user) and !empty($user))
					<a href="{{ \App\Helpers\UrlGen::user($user) }}">
						{{ $post->contact_name }}
					</a>
				@else
					{{ $post->contact_name }}
				@endif
				</h4>
				
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">{{ t('Close') }}</span>
				</button>
			</div>
			
			<form role="form" method="POST" action="{{ lurl('posts/' . $post->id . '/contact') }}" enctype="multipart/form-data">
				{!! csrf_field() !!}
				<div class="modal-body">

					@if (isset($errors) and $errors->any() and old('messageForm')=='1')
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<ul class="list list-check">
								@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
				
					@if (auth()->check())
						<input type="hidden" name="from_name" value="{{ auth()->user()->name }}">
						@if (!empty(auth()->user()->email))
							<input type="hidden" name="from_email" value="{{ auth()->user()->email }}">
						@else
							<!-- from_email -->
							<?php $fromEmailError = (isset($errors) and $errors->has('from_email')) ? ' is-invalid' : ''; ?>
							<div class="form-group required" style="
    display: none;
">
								<label for="from_email" class="control-label">{{ t('E-mail') }}
									@if (!isEnabledField('phone'))
										<sup>*</sup>
									@endif
								</label>
								<div class="input-group">
									<div class="input-group-prepend" style="
    display: none;
">
										<span class="input-group-text"><i class="icon-mail"></i></span>
									</div>
									<input id="from_email" name="from_email" type="text" placeholder="{{ t('ie_email') }}"
										   class="form-control{{ $fromEmailError }}" value="{{ old('from_email', auth()->user()->email) }}" style="
						display: none;
					">
								</div>
							</div>
						@endif
					@else
						<!-- from_name -->
						<?php $fromNameError = (isset($errors) and $errors->has('from_name')) ? ' is-invalid' : ''; ?>
						<div class="form-group required" style="
    display: none;
">
							<label for="from_name" class="control-label">{{ t('Name') }} <sup>*</sup></label>
							<div class="input-group">
								<input id="from_name"
									   name="from_name"
									   class="form-control{{ $fromNameError }}"
									   placeholder="{{ t('your_name') }}"
									   type="text"
									   style="
						display: none;
					"
									   value="{{ old('from_name') }}"
								>
							</div>
						</div>
							
						<!-- from_email -->
						<?php $fromEmailError = (isset($errors) and $errors->has('from_email')) ? ' is-invalid' : ''; ?>
						<div class="form-group required" style="
    display: none;
">
							<label for="from_email" class="control-label">{{ t('E-mail') }}
								@if (!isEnabledField('phone'))
									<sup>*</sup>
								@endif
							</label>
							<div class="input-group">
								<div class="input-group-prepend" style="
    display: none;
">
									<span class="input-group-text"><i class="icon-mail"></i></span>
								</div>
								<input id="from_email" style="
						display: none;
					"  name="from_email" type="text" placeholder="{{ t('ie_email') }}"
									   class="form-control{{ $fromEmailError }}" value="{{ old('from_email') }}">
							</div>
						</div>
					@endif
					
					<!-- from_phone -->
					<?php $fromPhoneError = (isset($errors) and $errors->has('from_phone')) ? ' is-invalid' : ''; ?>
					<div class="form-group required" style="
    display: none;
">
						<label for="phone" class="control-label">{{ t('phone_number') }}
							@if (!isEnabledField('email'))
								<sup>*</sup>
							@endif
						</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span id="phoneCountry" class="input-group-text"><i class="icon-phone-1"></i></span>
							</div>
							<input id="from_phone"
								   name="from_phone"
								   type="text"
								   style="
						display: none;
					"
								   placeholder="{{ t('phone_number') }}"
								   maxlength="60"
								   class="form-control{{ $fromPhoneError }}"
								   value="{{ old('from_phone', (auth()->check()) ? auth()->user()->phone : '') }}"
							>
						</div>
					</div>
					
					<!-- message -->
					<?php $messageError = (isset($errors) and $errors->has('message')) ? ' is-invalid' : ''; ?>
					<div class="form-group required">
						<label for="message" class="control-label" style="
						display: none;
					">
							{{ t('Message') }} <span class="text-count">(500 max)</span> <sup>*</sup>
						</label>
						<textarea id="message"
								  name="message"
								  class="form-control required{{ $messageError }}"
								  placeholder="{{ t('your_message_here') }}"
								  rows="5"
						>{{ old('message') }}</textarea>
					</div>
					<div class="input-group">
						<div class="input-group-prepend">
						</div>
						<input type="file" name="fileToUpload" id="fileToUpload">
					</div>
					@if (isset($parentCat) and isset($parentCat->type) and in_array($parentCat->type, ['job-offer']))
						<!-- filename -->
						<?php $filenameError = (isset($errors) and $errors->has('filename')) ? ' is-invalid' : ''; ?>
						<div class="form-group required" {!! (config('lang.direction')=='rtl') ? 'dir="rtl"' : '' !!}>
							<label for="filename" class="control-label{{ $filenameError }}">{{ t('Resume') }} </label>
							<input id="filename" name="filename" type="file" class="file{{ $filenameError }}">
							<small id="" class="form-text text-muted">
								{{ t('file_types', ['file_types' => showValidFileTypes('file')]) }}
							</small>
						</div>
						<input type="hidden" name="parentCatType" value="{{ $parentCat->type }}">
					@endif
					
					@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.tools.recaptcha', 'layouts.inc.tools.recaptcha'], ['label' => true])
					
					<input type="hidden" name="country_code" value="{{ config('country.code') }}">
					<input type="hidden" name="post_id" value="{{ $post->id }}">
					<input type="hidden" name="messageForm" value="1">
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{ t('Cancel') }}</button>
					<button type="submit" class="btn btn-success pull-right">{{ t('send_message') }}</button>
				</div>
			</form>
			
		</div>
	</div>
</div>
@section('after_styles')
	@parent
	<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
	@if (config('lang.direction') == 'rtl')
		<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
	@endif
	<style>
		.krajee-default.file-preview-frame:hover:not(.file-preview-error) {
			box-shadow: 0 0 5px 0 #666666;
		}
	</style>
@endsection

@section('after_scripts')
    @parent
	
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
	@if (file_exists(public_path() . '/assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js'))
		<script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js') }}" type="text/javascript"></script>
	@endif

	<script>
		/* Initialize with defaults (Resume) */
		$('#filename').fileinput(
		{
			theme: "fa",
            language: '{{ config('app.locale') }}',
			@if (config('lang.direction') == 'rtl')
				rtl: true,
			@endif
			showPreview: false,
			allowedFileExtensions: {!! getUploadFileTypes('file', true) !!},
			showUpload: false,
			showRemove: false,
			maxFileSize: {{ (int)config('settings.upload.max_file_size', 1000) }}
		});
	</script>
	<script>
		$(document).ready(function () {
			@if ($errors->any())
				@if ($errors->any() and old('messageForm')=='1')
					$('#contactUser').modal();
				@endif
			@endif
		});
	</script>
@endsection
