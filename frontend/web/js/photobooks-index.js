/*jslint unparam: true */
/*global window, $ */
var onChangePbStatus=false;
$(function () {


    /*Активируем tooltips*/
    $('.tooltips').tooltip();

    /* Активируем jQuery.editable */

    var onOkEditable=function(text,oldtext, url, that){

        //Обрабатываем изменение имени группы
        var $that=that;


        //var url='photobooks/'
        console.log($that);

        url=url.replace(/newname/g, text).
            replace();


        //Сохраняем на сервер изменения
        PhotobookApi.changeName({
            url:url,
            success:function(){

                //Удачно
                //Готови url для загрузки файлов
              /*  $('.fileupload', $that).attr('data-group', text);
                var base_url=$('.fileupload', $that).attr('data-base');
                var url=base_url.replace(/groupname/g, $('.fileupload', $that).attr('data-group')).replace();
                $('.fileupload', $that).fileupload({url: url})*/
                //$('form', $that).attr('action', url);


                //console.log('onOk:'+text);
            },
            error:function(e){

                //console.log('error:'+ e.message);
            }
        });
    }


    var onCancelEditable=function(text, that){
        console.log('onCancel:'+text);
    }

    $('.editable').editable({
        onOk:onOkEditable,
        onCancel:onCancelEditable
    });

    $('.btnDelete').bind('click', function(e){

        e.preventDefault();

        var question=$(this).data('bootbox-confirm');
        var item=$(this).closest('.photobook-item');

        var url=$(this).attr('href');
        bootbox.confirm(question, function(result) {
            /*Example.show("Confirm result: "+result);*/

            console.log(result);

            if(result){
                PhotobookApi.customRequest({
                    url:url,
                    success:function(result){


                        if(result.response.status){
                            item.remove();
                        }
                    },
                    error:function(msg){
                        bootbox.alert(msg);
                    }
                });

                console.log('да:'+url);
            }

        });

    });


    $('.btnSendToCustomer').bind('click', function(){


        var $btn=$(this);
        var api_url=$btn.data('url');


        showLoader('Загрузка...');


        PhotobookApi.customRequest({
            url:api_url,

            success:function(result){


                console.log('result:', result);
                if(result.response.status){

                    var photobook=result.response.photobook;


                    $('.btnGetLinkForCustomer').data('url', photobook.recieveLinkForCustomerUrl);

                    $('.btnDeleteLinkForCustomer').data('url', photobook.deleteLinkForCustomerUrl);

                    $('.btnSendEmailWithToCustomer').data('url', photobook.sendEmailWithLinkToCustomerUrl);


                    console.log('photobook.view_access_key:', photobook.view_access_key);

                    if(photobook.view_access_key==null || photobook.view_access_key=='' || photobook.view_access_key==undefined){

                        $('#viewLinkNotExistsForm').css('display', '');

                        $('#viewLinkExistsForm').css('display', 'none');

                        $('#linkFormContent').css('display', 'none');



                    }else{


                        $('#viewLinkNotExistsForm').css('display', 'none');

                        $('#viewLinkExistsForm').css('display', '');


                        $('#linkFormContent').css('display', '');

                        $('#viewLinkBox').html(photobook.viewLinkUrl);


                    }

                    onChangePbStatus=false;

                    $('#dialogGetLink').modal('show');


                }


                hideLoader();
            },
            error:function(msg){

                hideLoader();

                bootbox.alert(msg);


            }
        });


    });





    $('.btnDialogShowLinkClose').bind('click', function(){

        $('#dialogGetLink').modal('hide');

    });


    $('.btnGetLinkForCustomer').bind('click', function(){

        var $btn= $(this);

        var api_url=$btn.data('url');

        $btn.button('loading');

        PhotobookApi.customRequest({
            url:api_url,
            success:function(result){


                console.log('result:', result);
                if(result.response.status){


                    $('#viewLinkNotExistsForm').css('display', 'none');

                    $('#viewLinkExistsForm').css('display', '');

                    $('#viewLinkBox').html(result.response.url);

                    $('#linkFormContent').css('display', '');

                   //location.reload();

                    onChangePbStatus=true;


                }


                $btn.button('reset');
                //hideLoader();
            },
            error:function(msg){

                $btn.button('reset');

                bootbox.alert(msg);

                //hideLoader();
            }
        });


    });



    $('.btnDeleteLinkForCustomer').bind('click', function(){


        var $btn= $(this);

        var api_url=$btn.data('url');


        $btn.button('loading');

        PhotobookApi.customRequest({
            url:api_url,
            success:function(result){


                console.log('result:', result);
                if(result.response.status){


                    $('#viewLinkNotExistsForm').css('display', '');

                    $('#viewLinkExistsForm').css('display', 'none');

                    $('#linkFormContent').css('display', 'none');

                   // location.reload();

                    onChangePbStatus=true;


                }


                $btn.button('reset');
                //hideLoader();
            },
            error:function(msg){

                $btn.button('reset');

                bootbox.alert(msg);

                //hideLoader();
            }
        });


    });



    $('.btnSendEmailWithToCustomer').bind('click', function(){


        var $btn= $(this);

        var api_url=$(this).data('url');
        var email=$('#inputCustomerEmail').val();


        $btn.button('loading');

        $('#inputCustomerEmail').attr('disabled', 'disabled');
        console.log('send email:', email, api_url);



        PhotobookApi.postRequest({
            url:api_url,
            data:{email:email},
            success:function(result){


                console.log('result:', result);
                if(result.response.status){





                }


                $btn.button('reset');

                $btn.button('disabled');

                $('#inputCustomerEmail').attr('disabled', null);
                //hideLoader();
            },
            error:function(msg){

                $btn.button('reset');

                $('#inputCustomerEmail').attr('disabled', null);

                bootbox.alert(msg);

                //hideLoader();
            }
        });



    });



    $('#dialogGetLink').on('hidden.bs.modal', function () {
        // do something…

        if(onChangePbStatus){

            location.reload();
        }
    })


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



    $('.btnPhotobookCopy').bind('click', function(){



        var $btn= $(this);

        var api_url=$(this).data('url');


        bootbox.confirm('Вы уверены, что хотите создать копию этой книги?', function(result){




            if(result){

                showLoader('Копирывание...');



                PhotobookApi.customRequest({
                    url:api_url,

                    success:function(result){
                        console.log('result:', result);
                        if(result.response.status){


                            location.href=result.response.redirect;
                        }

                        hideLoader();
                    },
                    error:function(msg){


                        hideLoader();

                        bootbox.alert(msg);


                    }
                });

            }

        });

       // showLoader('Копирывание...');





    });


    /* обработчик добавление новой  группы */

   /* var onAddGroup=function(){

        var url=$(this).data('url');

        PhotobookApi.addGroup({
            url:url,
            success:function(data){

                if(data.response.status){


                    var elem=$(data.response.current_group);

                    elem.css('display', 'none');

                    $('.groups').append(elem);


                    elem.fadeToggle();

                    $(".files", elem).mCustomScrollbar({
                        axis:"x",
                        advanced:{
                            autoExpandHorizontalScroll:true
                        }
                    });

                    $('.fileupload', elem).fileupload({
                        dataType: 'json',
                        start:startUpload,
                        done: doneUpload,
                        progressall: progressallUpload
                    }).prop('disabled', !$.support.fileInput)
                        .parent().addClass($.support.fileInput ? undefined : 'disabled');

                    $('.editable', elem).editable({
                        onOk:onOkEditable,
                        onCancel:onCancelEditable
                    });


                }
                console.log(data);
                // console.log('onOk:'+text);
            },
            error:function(e){

                //console.log('error:'+ e.message);
            }
        });


    }

    $('.btnAddGroup').bind('click', onAddGroup);*/

});