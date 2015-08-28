/*jslint unparam: true */
/*global window, $ */
$(function () {




    /*Скролинг */



    var addError=function(msg){

        var el_error=$('<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>'+
            ''+msg+'</div>');
        $('.upload-errors').append(el_error);
    }




    /* Загрузак thumb */


    var startThumbUpload=function(e,data){

        console.log('start thumb upload');
        showLoader('Загрузка...');

    }

    var doneThumbUpload=function (e, data) {

        console.log(data.result);
        if(data && data.hasOwnProperty('result') && data.result.hasOwnProperty('response') && data.result.response.status){

            var thumb_image_url=data.result.response.thumb_url;

            /*$('.thumb img', cont).css('background-image',  'url(' + background_image + ')' );*/

            $('.style-min-thumb').attr('src', thumb_image_url+'?v='+Math.random());


        }else if(data && data.hasOwnProperty('result') && data.result.hasOwnProperty('error')){

            console.error('Upload error');
            addError(data.result.error.msg);
        }else{

            addError('Неизвестная ошибка');
        }



        setTimeout(function(){

            hideLoader();
        }, 1000);


    }

    var progressallThumbUpload=function (e, data) {

        var that= $(e.target);
        var cont=that.closest('.photo-group');

        console.log(that);
        console.log(cont);
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('.progress-bar', cont).css(
            'width',
            progress + '%'
        );

        updateLoader('Загружено '+progress+'%');


    }


    $('.fileupload-thumb').fileupload({
        dataType: 'json',
        dropZone:null,
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 52428800,
        disableValidation: false,
        start:startThumbUpload,
        done: doneThumbUpload,
        progressall: progressallThumbUpload,
        messages: {
            maxFileSize: 'Максимальный размер файла не должен привешать 50MB',
            acceptFileTypes:'Разрешено загружать только jpeg, png'
        }
    }).on('fileuploadadd', function(evt, data) {
            var $this = $(this);
            var validation = data.process(function () {
                return $this.fileupload('process', data);
            });

            validation.done(function() {
                var url=$this.data('url');
                data.submit();
            });
            validation.fail(function(data) {
                console.log('Upload error: ' + data.files[0].error);

                addError(data.files[0].error);
            });
    });




    /* Обновление имени обложки */

    $('#inputCoverName').keyup(function(e){

        var coverName=$('#inputCoverName').val();

        var url = $(this).data('url');
        CoverApi.customRequest({
            url:url,
            data:{value:coverName},
            success:function(result){

                console.log(result);
                if(result.response.status){
                  //  $('.spanTemplateName').html(templateName);
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    });


    /* Обновление цены */

    var coverPriceUpdate=function(e){

        var price=parseFloat($('#inputCoverPrice').val());

        if(isNaN(price)){

            price=0.0;
        }


        if($('#inputCoverPrice').val()!=price){

            $('#inputCoverPrice').val(price);
        }


        var url = $(this).data('url');
        CoverApi.customRequest({
            url:url,
            data:{value:price},
            success:function(result){

                console.log(result);
                if(result.response.status){
                    //  $('.spanTemplateName').html(templateName);
                    if($('#inputCoverPrice').val()!=result.response.value){

                       // $('#inputCoverPrice').val(result.response.value);
                    }
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    }

    $('#inputCoverPrice').keyup(coverPriceUpdate);
    $('#inputCoverPrice').change(coverPriceUpdate);


    /* Обновление смещение окна по горизонтали */

    var coverWindowOffsetXUpdate=function(e){

        var val=parseFloat($('#inputWindowOffsetX').val());

        if(isNaN(val)){

            val=0.0;
        }


        if($('#inputWindowOffsetX').val()!=val){

            $('#inputWindowOffsetX').val(val);
        }


        var url = $(this).data('url');
        CoverApi.customRequest({
            url:url,
            data:{value:val},
            success:function(result){

                console.log(result);
                if(result.response.status){
                    //  $('.spanTemplateName').html(templateName);
                    if($('#inputWindowOffsetX').val()!=result.response.value){

                        //$('#inputWindowOffsetX').val(result.response.value);
                    }
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    }

    $('#inputWindowOffsetX').keyup(coverWindowOffsetXUpdate);
    $('#inputWindowOffsetX').change(coverWindowOffsetXUpdate);



    /* Обновление смещение окна по вертикали */

    var coverWindowOffsetYUpdate=function(e){

        var val=parseFloat($('#inputWindowOffsetY').val());

        if(isNaN(val)){

            val=0.0;
        }


        if($('#inputWindowOffsetY').val()!=val){

            $('#inputWindowOffsetY').val(val);
        }


        var url = $(this).data('url');
        CoverApi.customRequest({
            url:url,
            data:{value:val},
            success:function(result){

                console.log(result);
                if(result.response.status){
                    //  $('.spanTemplateName').html(templateName);
                    if($('#inputWindowOffsetY').val()!=result.response.value){

                      //  $('#inputWindowOffsetY').val(result.response.value);
                    }
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    }

    $('#inputWindowOffsetY').keyup(coverWindowOffsetYUpdate);
    $('#inputWindowOffsetY').change(coverWindowOffsetYUpdate);



    /* Обновление ширины окна обложки */

    var coverWindowWidthUpdate=function(e){

        var val=parseFloat($('#inputWindowWidth').val());

        if(isNaN(val)){

            val=0.0;
        }


        if($('#inputWindowWidth').val()!=val){

            $('#inputWindowWidth').val(val);
        }


        var url = $(this).data('url');
        CoverApi.customRequest({
            url:url,
            data:{value:val},
            success:function(result){

                console.log(result);
                if(result.response.status){
                    //  $('.spanTemplateName').html(templateName);
                    if($('#inputWindowWidth').val()!=result.response.value){

                       // $('#inputWindowWidth').val(result.response.value);
                    }
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    }

    $('#inputWindowWidth').keyup(coverWindowWidthUpdate);
    $('#inputWindowWidth').change(coverWindowWidthUpdate);



    /* Обновление ширины окна обложки */

    var coverWindowHeightUpdate=function(e){

        var val=parseFloat($('#inputWindowHeight').val());

        if(isNaN(val)){

            val=0.0;
        }


        if($('#inputWindowHeight').val()!=val){

            $('#inputWindowHeight').val(val);
        }


        var url = $(this).data('url');
        CoverApi.customRequest({
            url:url,
            data:{value:val},
            success:function(result){

                console.log(result);
                if(result.response.status){
                    //  $('.spanTemplateName').html(templateName);
                    if($('#inputWindowHeight').val()!=result.response.value){

                       // $('#inputWindowHeight').val(result.response.value);
                    }
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    }

    $('#inputWindowHeight').keyup(coverWindowHeightUpdate);
    $('#inputWindowHeight').change(coverWindowHeightUpdate);







    /* Обновление типа материала */

    $('#selectMaterialType').change(function(e){

        var material_type=$('#selectMaterialType').val();

        var url = $(this).data('url');
        CoverApi.customRequest({
            url:url,
            data:{value:material_type},
            success:function(result){

                console.log(result);
                if(result.response.status){
                    //  $('.spanTemplateName').html(templateName);
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    });


    /* Обновление знака цены */

    $('#selectPriceSign').change(function(e){

        var price_sign=$('#selectPriceSign').val();

        var url = $(this).data('url');
        CoverApi.customRequest({
            url:url,
            data:{value:price_sign},
            success:function(result){

                console.log(result);
                if(result.response.status){
                    //  $('.spanTemplateName').html(templateName);
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    });



    /* Обновление флага по умолчанию */

    $('#defaultCheckbox').change(function(){

        var defaultFlag=0;
        if ($(this).is(':checked')){

            defaultFlag=1;
        }


        var url = $(this).data('url');
        CoverApi.customRequest({
            url:url,
            data:{value:defaultFlag},
            success:function(result){

                console.log(result);
                if(result.response.status){
                    /*item.remove();*/
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    });



    /* Обновление публикации */

    $('#publishCheckbox').change(function(){

        if ($(this).is(':checked')){

            publish=1;
        }else{
            publish=0;
        }


        var url = $(this).data('url');
        CoverApi.customRequest({
            url:url,
            data:{value:publish},
            success:function(result){

                console.log(result);
                if(result.response.status){
                    /*item.remove();*/
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    });

   /* $('#publishCheckbox').change(function(e){

        var font_id=$('#selectFont').val();

        var url = $(this).data('url');
        StyleApi.customRequest({
            url:url,
            data:{value:font_id},
            success:function(result){

                console.log(result);
                if(result.response.status){
                    //  $('.spanTemplateName').html(templateName);
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    });*/






    /* Загрузак padded cover */


    var startPaddedCoverUpload=function(e,data){

        console.log('start padded cover');
        showLoader('Загрузка...');

    }

    var donePaddedCoverUpload=function (e, data) {

        console.log(data.result);
        if(data && data.hasOwnProperty('result') && data.result.hasOwnProperty('response') && data.result.response.status){

            var cover_image_url=data.result.response.padded_cover_thumb_url;

            $('#paddedCoverPreview').html('<div class="row"><div class="col-lg-3"><div class="thumbnail"><a class="thumbnail"><img src="'+cover_image_url+'" /></a></div></div></div>');

            //paddedPassepartoutPreview
            //var thumb_image_url=data.result.response.thumb_url;

            /*$('.thumb img', cont).css('background-image',  'url(' + background_image + ')' );*/

            //$('.style-min-thumb').attr('src', thumb_image_url+'?v='+Math.random());

            if(data.total==data.loaded){

                setTimeout(function(){



                    hideLoader();
                }, 1000);
            }


        }else if(data && data.hasOwnProperty('result') && data.result.hasOwnProperty('error')){

            console.error('Upload error');
            addError(data.result.error.msg);
        }else{

            addError('Неизвестная ошибка');
        }
    }

    var progressallPaddedCoverUpload=function (e, data) {

        var that= $(e.target);
        var cont=that.closest('.photo-group');

        console.log(that);
        console.log(cont);
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('.progress-bar', cont).css(
            'width',
            progress + '%'
        );

        updateLoader('Загружено '+progress+'%');



    }


    $('.fileupload-padded-cover').fileupload({
        dataType: 'json',
        dropZone:null,
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 52428800,
        disableValidation: false,
        start:startPaddedCoverUpload,
        done: donePaddedCoverUpload,
        progressall: progressallPaddedCoverUpload,
        messages: {
            maxFileSize: 'Максимальный размер файла не должен привешать 50MB',
            acceptFileTypes:'Разрешено загружать только jpeg, png'
        }
    }).on('fileuploadadd', function(evt, data) {
        var $this = $(this);
        var validation = data.process(function () {
            return $this.fileupload('process', data);
        });

        validation.done(function() {
            var url=$this.data('url');
            data.submit();
        });
        validation.fail(function(data) {
            console.log('Upload error: ' + data.files[0].error);

            addError(data.files[0].error);
        });
    });




    /* Загрузак cover front */


    var startCoverFrontUpload=function(e,data){

        console.log('start cover front');
        showLoader('Загрузка...');

    }

    var doneCoverFrontUpload=function (e, data) {

        console.log(data.result);
        if(data && data.hasOwnProperty('result') && data.result.hasOwnProperty('response') && data.result.response.status){

            var cover_image_url=data.result.response.cover_front_thumb_url;

            $('#coverFrontPreview').html('<div class="row"><div class="col-lg-3"><div class="thumbnail"><a class="thumbnail"><img src="'+cover_image_url+'" /></a></div></div></div>');

            //paddedPassepartoutPreview
            //var thumb_image_url=data.result.response.thumb_url;

            /*$('.thumb img', cont).css('background-image',  'url(' + background_image + ')' );*/

            //$('.style-min-thumb').attr('src', thumb_image_url+'?v='+Math.random());

            if(data.total==data.loaded){

                setTimeout(function(){



                    hideLoader();
                }, 1000);
            }


        }else if(data && data.hasOwnProperty('result') && data.result.hasOwnProperty('error')){

            console.error('Upload error');
            addError(data.result.error.msg);
        }else{

            addError('Неизвестная ошибка');
        }
    }

    var progressallCoverFrontUpload=function (e, data) {

        var that= $(e.target);
        var cont=that.closest('.photo-group');

        console.log(that);
        console.log(cont);
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('.progress-bar', cont).css(
            'width',
            progress + '%'
        );

        updateLoader('Загружено '+progress+'%');



    }


    $('.fileupload-cover-front').fileupload({
        dataType: 'json',
        dropZone:null,
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 52428800,
        disableValidation: false,
        start:startCoverFrontUpload,
        done: doneCoverFrontUpload,
        progressall: progressallCoverFrontUpload,
        messages: {
            maxFileSize: 'Максимальный размер файла не должен привешать 50MB',
            acceptFileTypes:'Разрешено загружать только jpeg, png'
        }
    }).on('fileuploadadd', function(evt, data) {
        var $this = $(this);
        var validation = data.process(function () {
            return $this.fileupload('process', data);
        });

        validation.done(function() {
            var url=$this.data('url');
            data.submit();
        });
        validation.fail(function(data) {
            console.log('Upload error: ' + data.files[0].error);

            addError(data.files[0].error);
        });
    });





    /* Загрузак cover back */


    var startCoverBackUpload=function(e,data){

        console.log('start cover back');
        showLoader('Загрузка...');

    }

    var doneCoverBackUpload=function (e, data) {

        console.log(data.result);
        if(data && data.hasOwnProperty('result') && data.result.hasOwnProperty('response') && data.result.response.status){

            var cover_image_url=data.result.response.cover_back_thumb_url;

            $('#coverBackPreview').html('<div class="row"><div class="col-lg-3"><div class="thumbnail"><a class="thumbnail"><img src="'+cover_image_url+'" /></a></div></div></div>');

            //paddedPassepartoutPreview
            //var thumb_image_url=data.result.response.thumb_url;

            /*$('.thumb img', cont).css('background-image',  'url(' + background_image + ')' );*/

            //$('.style-min-thumb').attr('src', thumb_image_url+'?v='+Math.random());

            if(data.total==data.loaded){

                setTimeout(function(){



                    hideLoader();
                }, 1000);
            }


        }else if(data && data.hasOwnProperty('result') && data.result.hasOwnProperty('error')){

            console.error('Upload error');
            addError(data.result.error.msg);
        }else{

            addError('Неизвестная ошибка');
        }
    }

    var progressallCoverBackUpload=function (e, data) {

        var that= $(e.target);
        var cont=that.closest('.photo-group');

        console.log(that);
        console.log(cont);
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('.progress-bar', cont).css(
            'width',
            progress + '%'
        );

        updateLoader('Загружено '+progress+'%');



    }


    $('.fileupload-cover-back').fileupload({
        dataType: 'json',
        dropZone:null,
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 52428800,
        disableValidation: false,
        start:startCoverBackUpload,
        done: doneCoverBackUpload,
        progressall: progressallCoverBackUpload,
        messages: {
            maxFileSize: 'Максимальный размер файла не должен привешать 50MB',
            acceptFileTypes:'Разрешено загружать только jpeg, png'
        }
    }).on('fileuploadadd', function(evt, data) {
        var $this = $(this);
        var validation = data.process(function () {
            return $this.fileupload('process', data);
        });

        validation.done(function() {
            var url=$this.data('url');
            data.submit();
        });
        validation.fail(function(data) {
            console.log('Upload error: ' + data.files[0].error);

            addError(data.files[0].error);
        });
    });




    $('.tooltips').tooltip();

    var changeColor=function(hex, rgb){

        var url=$(this).data('url');
        console.log(url+" "+hex);
        var color=hex;

        console.log(url);

        StyleApi.customRequest({
            url:url,
            data:{value:color},
            success:function(result){


                if(result.hasOwnProperty('response') && result.response.status){

                }
            },
            error:function(msg){

                if(msg)
                bootbox.alert(msg);
            }
        });
    }

    $('.color-picker').miniColors({
        letterCase: 'upercase',
        change: changeColor
    });


   /* $(".scroll").mCustomScrollbar({
        axis:"y"

    });*/

});