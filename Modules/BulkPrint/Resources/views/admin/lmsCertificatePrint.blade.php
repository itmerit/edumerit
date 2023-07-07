<html>
	<head>
		<title>@lang('admin.student_certificate')</title>
	</head>

	<body>
		<div style="background-image: url('{{ isset($certificate)? asset($certificate->background_image): ''}}'); margin-bottom: {{$gridGap}}px;">
			@foreach($users as $user)
				@php
					$body = App\SmStudentCertificate::certificateBody($certificate->body, $certificate->role, $user->user_id);
				@endphp
				{!!$body!!}
			@endforeach
		</div>
	</body>
</html>
