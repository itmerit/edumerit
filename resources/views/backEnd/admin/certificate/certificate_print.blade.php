<html>
	<head>
		<title>@lang('admin.certificate')</title>
		<style>
			.certificate_box_wrapper {
				background-image: url(./img/bg.jpg);
				width: <?php echo $certificate->background_width?? 164?>mm;
				min-height: <?php echo $certificate->background_height?? 145?>mm;
				/* display: flex; */
				justify-content: center;
				margin: auto;
				background-repeat: no-repeat;
				background-size: cover;
				background-position: center center;
				padding-top: <?php echo $certificate->padding_top?? 5?>mm;
				padding-left: <?php echo $certificate->pading_left?? 5?>mm;
				padding-right: <?php echo $certificate->padding_right?? 5?>mm;
				padding-bottom: <?php echo $certificate->padding_bottom?? 5?>mm;
			}
    	</style>
	</head>
	<body>
		@foreach($users as $user)
			<div class="certificate_box_wrapper" style="background-image: url('{{ isset($certificate)? asset($certificate->background_image): ''}}'); margin-bottom: {{$gridGap}}px;">
				@if(moduleStatusCheck('Lms')== TRUE && $type== 'lms')
					@php
						$body = App\SmStudentCertificate::certificateBody($certificate->body, 'Lms', $user->user_id);
					@endphp
				@else
					@php
						$body = App\SmStudentCertificate::certificateBody($certificate->body, @$certificate->role, $user->user_id);
					@endphp
				@endif
					{!!$body!!}
			</div>
		@endforeach
	</body>
</html>
