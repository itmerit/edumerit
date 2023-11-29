<!DOCTYPE html>
<html lang="">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="icon" href="{{asset('public/backEnd/')}}/img/favicon.png" type="image/png"/>
    <title>Infix Edu ERP | Verify Your purchase {{@$name}} Module</title>
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/jquery-ui.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/jquery.data-tables.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/rowReorder.dataTables.min.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap-datepicker.min.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap-datetimepicker.min.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/themify-icons.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/flaticon.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/nice-select.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/magnific-popup.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/fastselect.min.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/software.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/fullcalendar.min.css">
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/toastr.min.css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/fullcalendar.print.css">

    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/js/select2/select2.css"/>
    <!-- main css -->
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/style.css"/>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/infix.css"/>
    <style>
        h2,h5{color: whitesmoke}
        .card-body {
            padding: 5.25rem;
        }

        .single-report-admit .card-header {
            background-position: right;
            margin-top: -5px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            padding: 20px;
        }
        .form-control{
          font-size: 14px !important;
          border: 1px solid #d73cff !important;
        }
        .text-red{
            color: red;
        }
        .purchase-alert{
            font-size: 16px;
            line-height: 1.2;
        }

    </style>
</head>


<body class="admin">
<div class="container">
    <div class="col-md-10 offset-1  mt-40">
        <div class="card">
            <div class="single-report-admit">
                <div class="card-header">
                    <h2 class="text-center text-uppercase">Attention please!</h2>
                </div>
            </div>
            <div class="card-body">
                <div class="justify-content-center">
                    <p class=" text-red purchase-alert">If you are using {{@$name}} Module without purchase, Please contact with us at <a target="_blank" href="mailto:support@spondonit.com">support@spondonit.com</a>.</p>
                    <p class="purchase-alert"> If you already purchase {{@$name}} Module, please verification again. Thanks !</p>

                       @if(Session::has('message-danger'))
                            <p class="text-danger">** {{ Session::get('message-danger') }}</p>
                        @endif
                        @if (@$errors->any())
                            Ops sorry ! Please enter valid input!
                            @foreach ($errors->all() as $error)
                                <p class="text-danger">** {{$error}}</p>
                            @endforeach
                        @endif



               <form method="post" action="{{url('check-verified-input')}}">
                   {{csrf_field()}}
                   <div class="form-group">
                       <label for="user">Email :</label>
                       <input type="text" class="form-control " name="email"  required="required"  placeholder="Enter Your Email" value="{{old('email')}}">
                   </div>
                   <div class="form-group">
                       <label for="purchasecode">Purchase Code:</label>
                       <input type="text" class="form-control" name="code" required="required" placeholder="Enter Your Purchase Code" value="{{old('purchasecode')}}">
                   </div>
                   <div class="form-group">
                       <label for="domain">Installation Domain:</label>
                       <input type="text" class="form-control" name="domain" required="required" value="{{url('/')}}">
                   </div>
                   <input type="submit" value="Next" class="offset-3 col-sm-6 primary-btn fix-gr-bg mt-40" style="background-color: rebeccapurple;color: whitesmoke">
               </form>

                </div>
            </div>
        </div>
    </div>


</div>
</body>
</html>
