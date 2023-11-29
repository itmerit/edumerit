@push('css')
     <link rel="stylesheet" href="{{ asset('public/backEnd/vendors/css/bootstrap-datepicker.min.css') }}" />
     <link rel="stylesheet" href="{{ asset('public/backEnd/vendors/css/bootstrap-datetimepicker.min.css') }}" />
@endpush

@push('script')
     <script src="{{asset('public/backEnd/')}}/vendors/js/bootstrap_datetimepicker.min.js"></script>
     <script src="{{asset('public/backEnd/')}}/vendors/js/bootstrap-datepicker.min.js"></script>
     <script type="text/javascript">
          (function($){
              $.fn.datepicker.dates[_locale] = new Object({
                  "days" : {!! json_encode(__('calender.days')) !!},
                  "daysShort": {!! json_encode(__('calender.daysShort')) !!},
                  "daysMin": {!! json_encode(__('calender.daysMin')) !!},
                  "months": {!! json_encode(__('calender.months')) !!},
                  "monthsShort": {!! json_encode(__('calender.monthsShort')) !!},
                  "today": {!! json_encode(__('calender.today')) !!},
                  "clear": {!! json_encode(__('calender.clear')) !!}
              })
          }(jQuery));
      
          $("#search-icon").on("click", function () {
               $("#search").focus();
             });
           
             $("#start-date-icon").on("click", function () {
               $("#startDate").focus();
             });
           
             $("#end-date-icon").on("click", function () {
               $("#endDate").focus();
             });
           
             $(".primary_input_field.date").datepicker({
               autoclose: true,
               setDate: new Date(),
           
             });
             $(".primary_input_field.date").datepicker({
               autoclose: true,
               setDate: new Date(),
               format: "mm/dd",
             });
             $(".primary_input_field.date").on("changeDate", function (ev) {
               // $(this).datepicker('hide');
               $(this).focus();
             });
           
             $(".primary_input_field.time").datetimepicker({
               format: "LT",
             });
           
             if ($)
               $(".primary_input_field.datetime").datetimepicker({
                 format: "YYYY-MM-DD H:mm",
               });
      </script>
@endpush