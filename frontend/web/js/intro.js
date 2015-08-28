/**
 * Created by maxbsoft on 10/30/14.
 */

$(function(){


    $('.btnCreatePhotobook').bind('click', function(){

        var demo=$('body').data('demo');


        if(demo==1){



        }else{



        }


    });




    $('.btnAddPhotobook').bind('click', function(){


        $('#dialogAddPhotobook').modal('show');


    });


    $('.btnDialogAddPhotobookClose').bind('click', function(){


        $('#dialogAddPhotobook').modal('hide');


    });

    $('.btnDialogCreatePhotobook').bind('click', function(){



        var $btn= $(this);

        var api_url=$(this).data('url');

        $btn.button('loading');





        var title_line_1=$('#inputName1').val();
        var title_line_2=$('#inputAnd').val();
        var title_line_3=$('#inputName2').val();
        var title_line_4=$('#inputCopyright').val();



        PhotobookApi.postRequest({
            url:api_url,
            data:{title_line_1:title_line_1, title_line_2:title_line_2, title_line_3:title_line_3, title_line_4:title_line_4},
            success:function(result){


                console.log('result:', result);
                if(result.response.status){



                    location.href=result.response.redirect;


                }


                $btn.button('reset');

                $btn.button('disabled');


                //hideLoader();
            },
            error:function(msg){

                $btn.button('reset');



                bootbox.alert(msg);

                //hideLoader();
            }
        });



    });


});