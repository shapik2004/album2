/*jslint unparam: true */
/*global window, $ */

$(function () {





    $('.paymentTypeSelect').change(function(){

        var payment_type=$(this).val();


        if($('.type-'+payment_type).data('online')){


            $('.onlinePayButton').css('display', '');

            $('.offlineInfo').css('display', 'none');

            $('.btnPay').data('type', payment_type);

        }else{


            $('.onlinePayButton').css('display', 'none');

            $('.offlineInfo').css('display', '');

        }

    });

    $('.btnPay').bind('click', function(){


        var url=$(this).data('url');

        var payment_type=$(this).data('type');

        url+='&payment_type='+payment_type;

        location.href=url;

    });



    $('.tooltips').tooltip();



});