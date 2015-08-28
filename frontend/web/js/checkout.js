/*jslint unparam: true */
/*global window, $ */

$(function () {






    var clickMakeLayoutsZip=function(e){

        e.stopPropagation();
        console.log('clickMakeLayoutsZip');
        var url=$(this).data('url');



        showLoader('Создаем архив с макетами...')

        PhotobookApi.customRequest({
            url:url,
            success:function(result){



                if(result.response.status){
                    hideLoader();
                    document.location=result.response.url;
                }else{

                    if(result.error){

                        bootbox.alert(result.error.msg);
                    }
                }
            },
            error:function(msg){
                hideLoader();
                bootbox.alert(msg);

            }
        });
    }

    $('.btnDeleteCartRow').bind('click', function(){


        var $input=$(this);

        var url=$input.data('url');
        var id=$input.data('id');

        var course=$('#totalCart').data('course');

        var currency=$('#totalCart').data('currency');

        PhotobookApi.customRequest({
            url:url,
            success:function(result){
                if(result.response.status){


                    $('.cart-rows-'+id).remove();

                    var total=0;

                    for(var i=0; i<$('.cart-rows').length; i++){

                        var subtotal=$($('.cart-rows')[i]).data('subtotal');



                        $('.row-index',$('.cart-rows')[i]).html(i+1);
                        total+=subtotal;

                    }

                    $('#totalDisplay').html((total*course).toFixed(2)+' '+currency);



                }else{
                    if(result.error){
                        bootbox.alert(result.error.msg);
                    }

                }
            },
            error:function(msg){

                bootbox.alert(msg);


            }
        });

    });



    $('.inputQuantity').keyup(function(){

        console.log("inputQuantity");
        var $input=$(this);
        var url=$input.data('url');
        var id=$input.data('id');

        var old_quantity=$input.data('value');
        var quantity=parseInt($input.val());

        if(isNaN(quantity) || quantity==0){

            $input.val(old_quantity);
            $input.select();
            return;

        }

        if(quantity!=$input.val()){

            $input.val(quantity);
        }


        if(quantity==old_quantity){

            return;
        }


        var course=$('#totalCart').data('course');

        var currency=$('#totalCart').data('currency');

        $input.data('value', quantity);

        PhotobookApi.postRequest({
            url:url,
            data:{quantity:quantity},
            success:function(result){
                if(result.response.status){

                   var sub_total=result.response.sub_total;

                    $('.subtotal-'+id).html((sub_total*course).toFixed(2)+' '+currency);

                    $('.cart-rows-'+id).data('subtotal', sub_total);

                    var total=0;

                    for(var i=0; i<$('.cart-rows').length; i++){

                        var subtotal=$($('.cart-rows')[i]).data('subtotal');


                        total+=subtotal;

                    }

                    $('#totalDisplay').html((total*course).toFixed(2)+' '+currency);



                }else{
                    if(result.error){
                        bootbox.alert(result.error.msg);
                    }
                    $input.val(old_quantity);
                    $input.data('value', old_quantity);
                }
            },
            error:function(msg){

                bootbox.alert(msg);
                $input.val(old_quantity);
                $input.data('value', old_quantity);

            }
        });



    });

    var updateDeliveryAdress=function(url, $elem){


        var value=($elem.val());


        PhotobookApi.postRequest({
            url:url,
            data:{value:value},
            success:function(result){
                if(result.response.status){





                }else{
                    if(result.error){
                       setTimeout(function(){

                           updateDeliveryAdress(url, $elem);
                       }, 2000);
                    }

                }
            },
            error:function(msg){

                setTimeout(function(){

                    updateDeliveryAdress(url, $elem);
                }, 2000);

            }
        });

    }


    $('#deliveryAddress').keyup(function(){


        var $input=$(this);
        var url=$input.data('url');


        updateDeliveryAdress(url, $input);




    });





    console.log('init clickMakeLayoutsZip');
    $('.btnMakeLayoutsZip').bind('click', clickMakeLayoutsZip);


    $('.tooltips').tooltip();

   /* $('.btnCloseReplacePhoto').bind('click', function(){

        $('#replacePhotosArea').fadeToggle();
    })*/

   /* $(".scroll").mCustomScrollbar({
        axis:"y"

    });*/

});