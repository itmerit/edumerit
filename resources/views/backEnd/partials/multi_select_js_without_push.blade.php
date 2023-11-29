<script src="{{asset('public/backEnd/multiselect/')}}/js/jquery.multiselect.js"></script>
<script type="text/javascript">
     $(function () {
            $("select[multiple].active.multypol_check_select").multiselect({
                columns: 1,
                placeholder: "Select",
                search: true,
                searchOptions: {
                    default: "Select",
                },
                
                selectAll: true,
            });
        });
</script>