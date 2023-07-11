<html>

	<head>
		<title>@lang('student.student_certificate')</title>

		<link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css" />
		<link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/style.css" />
		<style rel="stylesheet">
			.tdWidth{
				width: 33.33%;
			}
			.bgImage{
				height:auto; 
				background-repeat:no-repeat;
				background-image: url({{asset($certificate->file)}});
				  
			}
			table{
				/* margin-top: 160px; */
				text-align: center; 
			}
			 
			td{
				padding: 25px !important;
			}
			.DivBody{    
				height: 100vh;
				border: 1px solid white !important;
				margin-top: 0px;
				  
			}
			.tdBody{
				text-align: justify !important;				
			    height: 140px;
			    padding-top: 0px;
			    padding-bottom: 0px;
			    padding-left: 65px;
			    padding-right: 65px;

			}
			img{
				position: absolute;
			}
			table{
				position: relative;
				top:100;			
			}
			body{
				padding:0px !important;
				margin:0px !important;
			}
			html{
				background:red;
			}
			@page { 
				margin: 2px; 
				size: 21cm 17cm; 
				}
			body { margin: 1px; }
			@media print{.DivBody{page-break-after:always}}
		</style>
	</head>

	<body>

		@foreach($students as $student)
		<div class="DivBody">
			<img src="{{asset($certificate->file)}}">
			<table width="80%" align="center">
				<tr>
					<td style="text-align: left;" class="tdWidth">{{ @$certificate->header_left_text}}:</td>
					<td style="text-align: center;" class="tdWidth"></td>
					<td style="text-align: right;" class="tdWidth">@lang('common.date'): {{ @$certificate->date}}</td>
				</tr>
				<tr>
					<td colspan="3" class="tdBody">{{ isset($student->id) ? App\SmStudentCertificate::certificateBody($certificate->body, $student->id) : '' }}</td>
				</tr>
				<tr>
					<td style="text-align: left;" class="tdWidth">{{ @$certificate->footer_left_text}}</td>
					<td style="text-align: center;" class="tdWidth">{{ @$certificate->footer_center_text}}</td>
					<td style="text-align: right;" class="tdWidth">{{ @$certificate->footer_right_text}}</td>
				</tr>
			</table>
		</div>
		@endforeach	 
	</body>
</html>
