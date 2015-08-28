/**
 * Created by maxbsoft on 10/30/14.
 */

$(function(){

    var className='btn-primary';


    $('.color-picker').miniColors({
        letterCase: 'upercase',
        change: function(hex, rgb) {
            refreshSample();
        }
    });


    $('.btnColorDefault').bind('click', function(){

        //$('#color_1').val('#993149');
        //$('#color_2').val('#ffffff');

        $('#color_1').miniColors('value','#993149' );

        $('#color_2').miniColors('value','#ffffff' );

        refreshSample();

    });


    $('.btnLogoDefault').bind('click', function(){


        $('#defaultLogo').val('1');


        $('.logo_url_preview').attr('src', '/images/default-logo.png');

    });


    /*$('#select-all').on('click', function(e) {
        e.preventDefault();
        $('#css-text').select();
    });*/

    refreshSample();

    function refreshSample() {


        var color_1=$('#color_1').val(); //Фон
        var color_2=$('#color_2').val(); //Текст


        var allColors = [];
        for (var i in tinycolor.names) {
            allColors.push(i);
        }


        var invert_color_1=color_2;//tinycolor.mostReadable(color_1, allColors).toHexString();;








        $('#default_back_color').val(invert_color_1);
        $('#default_border_color').val(color_1);

        if(tinycolor(invert_color_1).isLight()) {
            $('#default_active_back_color').val(tinycolor(invert_color_1).darken().toHexString());
        }else{

            $('#default_active_back_color').val(tinycolor(invert_color_1).lighten().toHexString());

        }
        $('#default_active_border_color').val(color_1);

        $('#default_text_color').val(color_1);
        $('#default_active_text_color').val(color_1);




        $('#primary_back_color').val(color_1);
        $('#primary_border_color').val(color_1);

        if(tinycolor(color_1).isLight()) {

            $('#primary_active_back_color').val(tinycolor(color_1).darken().toHexString());
            $('#primary_active_border_color').val(tinycolor(color_1).darken().toHexString());
        }else{

            $('#primary_active_back_color').val(tinycolor(color_1).lighten().toHexString());
            $('#primary_active_border_color').val(tinycolor(color_1).lighten().toHexString());
        }

        $('#primary_text_color').val(color_2);
        $('#primary_active_text_color').val(color_2);


        $('#link_color').val(color_1);
        $('#active_link_color').val(color_1);






        var css = pbColorTemplate
            .replace(/@default-text-color/g, $('#default_text_color').val())
            .replace(/@default-back-color/g, $('#default_back_color').val())
            .replace(/@default-border-color/g, $('#default_border_color').val())
            .replace(/@default-active-back-color/g, $('#default_active_back_color').val())
            .replace(/@default-active-text-color/g, $('#default_active_text_color').val())
            .replace(/@default-active-border-color/g, $('#default_active_border_color').val())
            .replace(/@primary-text-color/g, $('#primary_text_color').val())
            .replace(/@primary-back-color/g, $('#primary_back_color').val())
            .replace(/@primary-border-color/g, $('#primary_border_color').val())
            .replace(/@primary-active-back-color/g, $('#primary_active_back_color').val())
            .replace(/@primary-active-text-color/g, $('#primary_active_text_color').val())
            .replace(/@primary-active-border-color/g, $('#primary_active_border_color').val())
            .replace(/@link-color/g, $('#link_color').val())
            .replace(/@active-link-color/g, $('#active_link_color').val())
            .replace();

        $('head style[data-role="custom"]').remove();
        $('<style type="text/css" data-role="custom">').appendTo('head').html(css);

        $('#css').val(css);


    }



    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.logo_url_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#logo_url").change(function(){
        readURL(this);

        $('#defaultLogo').val('0');
    });

});