/*jslint unparam: true */
/*global window, $ */
$(function () {




    /*Скролинг */

    $(document).ready(function(){
        $(".files").mCustomScrollbar({
            axis:"x",
            advanced:{
                autoExpandHorizontalScroll:true
            }
    });

        $(".templates").mCustomScrollbar({
            axis:"x",
            advanced:{
                autoExpandHorizontalScroll:true
            }
        });

    });


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

        if(data.total==data.loaded){

            setTimeout(function(){



                hideLoader();
            }, 1000);
        }

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



    /* Обновление цены */

    var priceUpdate=function(e){

        var price=parseFloat($('#inputPrice').val());

        if(isNaN(price)){

            price=0.0;
        }


        if($('#inputPrice').val()!=price){

            $('#inputPrice').val(price);
        }


        var url = $(this).data('url');
        StyleApi.customRequest({
            url:url,
            data:{value:price},
            success:function(result){

                console.log(result);
                if(result.response.status){
                    //  $('.spanTemplateName').html(templateName);
                    if($('#inputPrice').val()!=result.response.value){

                        // $('#inputCoverPrice').val(result.response.value);
                    }
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    }

    $('#inputPrice').keyup(priceUpdate);
    $('#inputPrice').change(priceUpdate);


    /* Обновление имени стиля */

    $('#inputStyleName').keyup(function(e){

        var styleName=$('#inputStyleName').val();

        var url = $(this).data('url');
        StyleApi.customRequest({
            url:url,
            data:{value:styleName},
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


    /* Обновление максимального количества разворотов */

    var styleMaxSpreadUpdate=function(e){

        var maxSpread=parseInt($('#inputStyleMaxSpread').val());

        if(isNaN(maxSpread)){

            maxSpread=2;
        }

        if(maxSpread<=1){


            maxSpread=2
        }

        if($('#inputStyleMaxSpread').val()!=maxSpread){

            $('#inputStyleMaxSpread').val(maxSpread);
        }


        var url = $(this).data('url');
        StyleApi.customRequest({
            url:url,
            data:{value:maxSpread},
            success:function(result){

                console.log(result);
                if(result.response.status){
                    //  $('.spanTemplateName').html(templateName);
                    if($('#inputStyleMaxSpread').val()!=result.response.value){

                        $('#inputStyleMaxSpread').val(result.response.value);
                    }
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    }

    $('#inputStyleMaxSpread').keyup(styleMaxSpreadUpdate);
    $('#inputStyleMaxSpread').change(styleMaxSpreadUpdate);




    /* Обновление текста для иконки */

    $('#inputTextForIcon').keyup(function(e){

        var textForIcon=$('#inputTextForIcon').val();

        var url = $(this).data('url');
        StyleApi.customRequest({
            url:url,
            data:{value:textForIcon},
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




    /* Обновление шрифта */

    $('#selectFont').change(function(e){

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


    });



    /* Обновление публикации */

    $('#publishCheckbox').change(function(){

        if ($(this).is(':checked')){

            publish=1;
        }else{
            publish=0;
        }


        var url = $(this).data('url');
        StyleApi.customRequest({
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







    /* Загрузак padded passepartout */


    var startPaddedPassepartoutUpload=function(e,data){

        console.log('start padded passepartout');
        showLoader('Загрузка...');

    }

    var donePaddedPassepartoutUpload=function (e, data) {

        console.log(data.result);
        if(data && data.hasOwnProperty('result') && data.result.hasOwnProperty('response') && data.result.response.status){

            var passepartout_image_url=data.result.response.padded_passepartout_thumb_url;

            $('#paddedPassepartoutPreview').html('<div class="row"><div class="col-lg-3"><div class="thumbnail"><a class="thumbnail"><img src="'+passepartout_image_url+'" /></a></div></div></div>');

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

    var progressallPaddedPassepartoutUpload=function (e, data) {

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


    $('.fileupload-padded-passepartout').fileupload({
        dataType: 'json',
        dropZone:null,
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 52428800,
        disableValidation: false,
        start:startPaddedPassepartoutUpload,
        done: donePaddedPassepartoutUpload,
        progressall: progressallPaddedPassepartoutUpload,
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



    /* Активируем jQuery.editable */

    var onOkEditable=function(text,oldtext, url, that){

        //Обрабатываем изменение имени группы
        var $that=that;

        console.log($that);

        url=url.replace(/newgroupname/g,text).replace();

        url=url.replace(/newname/g,text).replace();

        //Сохраняем на сервер изменения
        PhotobookApi.changeGroupName({
            url:url,
            success:function(){

                //Удачно
                //Готови url для загрузки файлов
               /* $('.fileupload', $that).attr('data-group', text);
                var base_url=$('.fileupload', $that).attr('data-base');

                if(base_url){
                    var url=base_url.replace(/groupname/g, $('.fileupload', $that).attr('data-group')).replace();
                    $('.fileupload', $that).fileupload({url: url})
                }*/


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