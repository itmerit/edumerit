<script>
    $(function(){
        "use strict";
        gradient_or_solid($('#color_mode').val());
        color_or_image($('#background-type').val());

        // _formValidation()

        $(document).on('change', '#color_mode', function(){
            let val = $(this).val();
            gradient_or_solid(val)
        })

        function gradient_or_solid(val = 'gradient'){
            if (val === 'gradient'){
                $("label[for='gradient_1']").text('{{ __('style.gradient_1') }} *');
                $('#gradient_2_div').show();
                $('#gradient_2').attr('required', true).val($('#gradient_2').data('value'));
                $('#gradient_3_div').show();
                $('#gradient_3').attr('required', true).val($('#gradient_3').data('value'));

                document.documentElement.style.setProperty('--gradient_2', $('#gradient_2').data('value'));
                document.documentElement.style.setProperty('--gradient_3', $('#gradient_3').data('value'));
            } else {
                $("label[for='gradient_1']").text('Solid Color *');
                let solid_val = $('#gradient_1').val();
                $('#gradient_2_div').hide();
                $('#gradient_2').attr('required', false).val(solid_val);
                $('#gradient_3_div').hide();
                $('#gradient_3').attr('required', false).val(solid_val);

                document.documentElement.style.setProperty('--gradient_2', solid_val);
                document.documentElement.style.setProperty('--gradient_3', solid_val);
            }


        }

        $(document).on('input, keyup', '#gradient_1', function(){
            let color_mode = $('#color_mode').val();
            if (color_mode === 'solid'){
                $('#gradient_2').val($(this).val());
                $('#gradient_3').val($(this).val());
                document.documentElement.style.setProperty('--gradient_2', $(this).val());
                document.documentElement.style.setProperty('--gradient_3', $(this).val());
            }
        })

        $(document).on('input', '.color_field', function(){
            set_color_variable($(this));
        });

        $("#background-type").on("change", function () {
            color_or_image($(this).val());
        });

        function color_or_image(val){
            if (val === "color") {
                $("#background-color").show();
                $("#background-image").hide();
            } else if (val === "image") {
                $("#background-color").hide();
                $("#background-image").show();
            }

            let body_bg_default_value = '';
            let bg_type = $('#background-type').val();
            if (bg_type === 'image'){
                body_bg_default_value = $('#old_bg_image').val();
            } else{
                body_bg_default_value = $('#background_color').val();
            }

            set_body_bg(bg_type, body_bg_default_value)
        }

        $(document).on('input', '#background_color', function(){
            if($('#background-type').val() === 'color'){
                set_body_bg('color', $(this).val());

            }
        });

        $(document).on('change', '#background_image', function(){
            let input = this;
            if($('#background-type').val() === 'image'){
                if (input.files && input.files[0]) {
                    $('#placeholderInput').attr('placeholder', input.files[0].name);
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        set_body_bg('image', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });

        $(document).ready(function(){
            $('.color_field').each(function(i, v){
                set_color_variable($(this));
            });

        })

        function set_color_variable($this){
            let field = $this.data('name');
            let val = $this.val();
            console.log(field, val);
            document.documentElement.style.setProperty('--'+field, val);
        }

        function set_body_bg(type = 'image', value){
            console.log(value);
            if (type === 'image'){
                $('body').css({
                    'background': 'url('+ value + ')  no-repeat center',
                    'background-size' : 'cover',
                    'background-attachment': 'fixed',
                    'background-position': 'top'
                });
            } else{
                $('body').css('background', value);
            }
        }

        $(document).on('click', '#reset_to_default', function(){
            document.location.reload(true);
        });

    });
</script>