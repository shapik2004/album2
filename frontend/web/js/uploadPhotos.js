/*jslint unparam: true */
/*global window, $ */
$(function () {

    var ref=$('.groups').data('ref');
    var id=$('.groups').data('id');

    var thumbClick;
    $(document).ready(function(){
        $(".files").mCustomScrollbar({
            axis:"x",
            advanced:{
                autoExpandHorizontalScroll:true
            }
         });

        var updateImageLoaded=function(){

            var new_img_width=this.width;
            var new_img_height=this.height;
            var new_img_src=this.src;


            $('.zoom-cont').fadeToggle(400, 'swing', function(e){

                var photo_id=$('.zoom-cont').data('id');
                $('.zoom-cont').remove();
                var new_zoom_cont=$(renderImage(new_img_width, new_img_height, new_img_src, photo_id));

                new_zoom_cont.css('display', 'none');
                new_zoom_cont.insertAfter($('.zoom-overlay-back'));
                new_zoom_cont.fadeToggle();
                initEvents();
            })

        }


        var clickClose=function(e){

            $('.zoom-overlay').fadeToggle(400, 'swing', function(){

                $('.zoom-overlay').remove();
            });

        }

        var clickDelete=function(e){

            showLoader('Удаляем...')
            var photo_id=$(this).data('id');
           // var group=$(this).data('group');

            var url=$('.groups').data('deletephotourl');
            url=url+'&photo_id='+photo_id;

            PhotobookApi.customRequest({
                url:url,
                success:function(result){
                    if(result.response.status){

                        $('.thumb-'+result.response.photo_id).remove();

                        clickClose(null);
                    }
                    hideLoader();
                },
                error:function(msg){
                    bootbox.alert(msg);
                    hideLoader();
                }
            });

            console.log('clickDelete');
        }

        var clickRotate=function(e){

            showLoader('Поворачиваем...')
            console.log('clickRotate');
            var photo_id=$(this).data('id');

            //var page=page_index;
            var url=$('.groups').data('rotateurl');
            url=url+'&photo_id='+photo_id+'&deg=90';


            PhotobookApi.customRequest({
                url:url,
                success:function(result){
                    if(result.response.status){
                        var data=result.response;
                        var newImg = new Image() ;
                        newImg.onload = updateImageLoaded ;
                        newImg.src = UserUrl.photobookPhotos(ref, id)+'/'+UserUrl.imageFile(data.photo_id, UserUrl.Sizes.middle)+'?v='+data.last_modified;

                        $('.thumb-'+data.photo_id).attr('href', UserUrl.photobookPhotos(ref, id)+'/'+UserUrl.imageFile(data.photo_id, UserUrl.Sizes.middle)+'?v='+data.last_modified);

                        $('.thumb-'+data.photo_id+' img').attr('src', UserUrl.photobookPhotos(ref, id)+'/'+UserUrl.imageFile(data.photo_id, UserUrl.Sizes.thumb)+'?v='+data.last_modified);
                    }
                    hideLoader();
                },
                error:function(msg){
                    bootbox.alert(msg);
                    hideLoader();
                }
            });
        }

        var initEvents=function(){
            $('.zoom-overlay .btnRotate').bind('click', clickRotate);
            $('.zoom-overlay .btnDelete').bind('click', clickDelete);
            $('.zoom-overlay .zoom-overlay-back').bind('click', clickClose);
            $('.zoom-overlay .btnClose').bind('click', clickClose);
        }

        var renderImage=function(width, height, src, photo_id){

            var book_width=width;
            var book_height=height;

            var book_aspect=book_width/book_height;

            var layout_width=$(window).width()-200;
            var layout_height=$(window).height()-200;

            var layout_aspect=layout_width/layout_height;

            var new_width, new_height;
            if (layout_aspect>=book_aspect)
            {
                new_width = book_width / (book_height / layout_height);
                new_height = layout_height;
            }
            else
            {
                new_width = layout_width;
                new_height = book_height / (book_width / layout_width);
            }

            var image_posX=100+(layout_width-new_width ) / 2;

            var image_posY=100+(layout_height-new_height  ) / 2;

            var result='<div class="zoom-cont" data-id="'+photo_id+'">'+
                '<img style="position: fixed; width:'+new_width+'px; height:'+new_height+'px; left:'+image_posX+'px; top: '+image_posY+'px;" src="'+src+'"/>'+
                '<div  style="position: fixed; width:'+new_width+'px; height:30px; left:'+image_posX+'px; top: '+(image_posY-50)+'px;">'+
                '<a style="" class="btn btn-primary btnClose pull-right"><i class="fa fa-close"></i></a>'+
                '</div>'+
                '<div style="position: fixed; width:'+new_width+'px; height:30px; left:'+image_posX+'px; top: '+(image_posY+new_height+10)+'px;">'+
                '<a style="" data-id="'+photo_id+'" class="btn btn-primary btnRotate pull-left"><i class="fa fa-rotate-right"></i></a>'+
                '<a style="" data-id="'+photo_id+'" class="btn btn-primary btnDelete pull-right"><i class="glyphicon glyphicon-trash"></i></a>'+
                '</div>'+
                '</div>';

            return result;

        }

        thumbClick=function(e){

            e.preventDefault();

            var photo_id=$(this).data('id');


            var imageLoaded = function() {

                var cont=renderImage(this.width, this.height, this.src, photo_id );
                var r=parseInt((Math.random()*10000)+'');
                var el=$('<div class="zoom-overlay  zoom-overlay-'+r+'">'+
                         '<div class="zoom-overlay-back"></div>'+
                         cont+
                         '</div>');

                $('body').append(el);

                $('.zoom-overlay').fadeToggle();

                initEvents();

            }



            var midImg = new Image() ;
            midImg.onload = imageLoaded ;
            midImg.src = $(this).attr('href');


        }
        $('.thumb').bind('click', thumbClick);
    });


    var changeStyle=function(){

        console.log("change style");

        var url=$(this).data('url');
        var button=$(this);

        console.log("url:"+url);

        showLoader("Загрузка...");

        PhotobookApi.customRequest({
            url:url,
            success:function(result){
                if(result.response.status){
                    var data=result.response;

                    $('.style-thumb').removeClass('active');

                    var div_cont=button.closest('div');

                    div_cont.addClass('active');

                }
                hideLoader();
            },
            error:function(msg){
                bootbox.alert(msg);
                hideLoader();
            }
        });


        return false;

    }

    $('.buttonChangeStyle').bind('click', changeStyle);



    /* Загрузак файлов */
    'use strict';

    var upload_count=0;
    var upload_complete_count=0;

    var startUpload=function(e,data){

        console.log('startUpload');
        console.log(e);
        console.log(data);

        var that= $(e.target);
        var cont=that.closest('.photo-group');

        console.log(that);
        console.log(cont);
        //var progress = parseInt(data.loaded / data.total * 100, 10);
        $('.progress-bar', cont).css(
            'width',
            '0px'
        );




        showLoader('Загрузка...');
    }

    var chunkalwaysUpload=function(e, data){
        console.log('chunkalwaysUpload');
        console.log(e);
        console.log(data);

    }

    var alwaysUpload=function(e, data){

        console.log('alwaysUpload');
        console.log(e);
        console.log(data);

        var that= $(e.target);
        var cont=that.closest('.photo-group');

        upload_complete_count++;

        console.log('upload_count='+upload_count+' upload_complete_count='+upload_complete_count)

        if(upload_complete_count>=upload_count){

            setTimeout(function(){

                $('.progress-bar', cont).css(
                    'width',
                    0 + 'px'
                );

                hideLoader();
            }, 1000);

            upload_count=0;
            upload_complete_count=0;

        }



    }

    var doneUpload=function (e, data) {

        console.log('doneUpload');
        console.log(data.result);
        if(data.result.response.status){

            var that= $(e.target);
            var cont=that.closest('.photo-group');

            console.log(cont);
           // $('.content .mCSB_container ul', cont)

            console.log($('.mCSB_container', cont));

            var elem=$(data.result.response.current_photo);

            elem.css('display', 'none');



            $("img", elem).one("load", function() {

                elem.fadeToggle();

            }).each(function() {

                if(this.complete) $(this).load();

            });


            $('.mCSB_container', cont).append(elem);








            console.log('test');
        }else{

            console.error('Upload error');
        }

        $('.thumb').unbind('click');
        $('.thumb').bind('click', thumbClick);


    }

    var changeUpload=function(e, data){



        console.log('changeUpload');
        console.log(e);
        console.log(data);

        upload_count=0;
        upload_complete_count=0;
    }

    var progressallUpload=function (e, data) {

        console.log('progressallUpload');
        var that= $(e.target);
        var cont=that.closest('.photo-group');

        console.log(that);
        console.log(cont);
        console.log('data.total:'+data.total+' data.loaded:'+data.loaded);
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('.progress-bar', cont).css(
            'width',
            progress + '%'
        );

        updateLoader('Загружено '+progress+'%');


    }

    var failUpload=function(e, data){

        upload_complete_count++;
        console.log(failUpload);
    }



    $('.fileupload').fileupload({
        dataType: 'json',
        dropZone:null,
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 52428800,
        sequentialUploads:true,
        change:changeUpload,

        disableValidation: false,
        start:startUpload,
        fail:failUpload,
        always:alwaysUpload,
        chunkalways:chunkalwaysUpload,
        done: doneUpload,
        progressall: progressallUpload,
        messages: {
            maxFileSize: 'Максимальный размер файла не должен привешать 50MB',
            acceptFileTypes:'Разрешено загружать только jpeg, png'
        }
    }).on('fileuploadadd', function(evt, data) {

            console.log('fileuploadadd');
            console.log(data);
            var $this = $(this);
            var validation = data.process(function () {
                return $this.fileupload('process', data);
            });


            validation.done(function() {
                var url=$this.data('url');
                upload_count++;
                data.submit();

            });
            validation.fail(function(data) {
                console.log('Upload error: ' + data.files[0].error);
            });
    });


    /* Активируем jQuery.editable */

    var onOkEditable=function(text,oldtext, url, that){

        //Обрабатываем изменение имени группы
        var $that=that;


        //var url='photobooks/'
        console.log($that);

        url=url.replace(/newname/g,text );

        //Сохраняем на сервер изменения
        PhotobookApi.changeGroupName({
            url:url,
            success:function(){

                //Удачно
                //Готови url для загрузки файлов
                $('.fileupload', $that).attr('data-group', text);
                var base_url=$('.fileupload', $that).attr('data-base');

                if(base_url){
                    var url=base_url.replace(/groupname/g, $('.fileupload', $that).attr('data-group')).replace();
                    $('.fileupload', $that).fileupload({url: url})
                }
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


    /* обработчик добавление новой  группы */

    var onAddGroup=function(){

        var cont=$(this).closest('.photo-group');

        var group=$('span.name', cont).html();
        var url=$(this).data('url')+'&after_group='+group;

        console.log(url);

        PhotobookApi.addGroup({
            url:url,
            success:function(data){

                if(data.response.status){

                    var elem=$(data.response.current_group);
                    elem.css('display', 'none');
                    elem.insertAfter(cont);

                    elem.fadeToggle();

                    $(".files", elem).mCustomScrollbar({
                        axis:"x",
                        advanced:{
                            autoExpandHorizontalScroll:true
                        }
                    });

                    $('.fileupload', elem).fileupload({
                        dataType: 'json',
                        dropZone:null,
                        autoUpload: false,
                        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                        maxFileSize: 52428800,
                        sequentialUploads:true,
                        change:changeUpload,

                        disableValidation: false,
                        start:startUpload,
                        fail:failUpload,
                        always:alwaysUpload,
                        chunkalways:chunkalwaysUpload,
                        done: doneUpload,
                        progressall: progressallUpload,
                        messages: {
                            maxFileSize: 'File exceeds maximum allowed size of 50MB',
                            acceptFileTypes:'Accept gif, jpeg, png'
                        }
                    }).on('fileuploadadd', function(evt, data) {
                        var $this = $(this);
                        var validation = data.process(function () {
                            return $this.fileupload('process', data);
                        });

                        validation.done(function() {
                            var url=$this.data('url');
                            upload_count++;
                            data.submit();
                        });
                        validation.fail(function(data) {
                            console.log('Upload error: ' + data.files[0].error);
                        });

                    });

                    $('.editable', elem).editable({
                        onOk:onOkEditable,
                        onCancel:onCancelEditable
                    });

                    $('.spinner', elem).TouchSpin({}).change(onSpinChnage);

                    $('.btnAddGroup', elem).bind('click', onAddGroup);

                    $('.btnDelete', elem).bind('click', onGroupDelete);

                    $('.tooltips', elem).tooltip();


                }
                console.log(data);

            },
            error:function(e){


            }
        });


    }

    $('.btnAddGroup').bind('click', onAddGroup);

    var onSpinChnage=function(e){
        console.log('spin change');

        console.log(e);

        var input= $(e.currentTarget);

        var url=input.data('url');

        var item=input.closest('.photo-group');

        var groupname=$('.editable span.name', item).text();

        url=url.replace(/reversalsvalue/g, input.val())
            .replace(/groupname/g, groupname)
            .replace();

        console.log(url);

        PhotobookApi.customRequest({
            url:url,
            success:function(result){


                if(result.response.status){
                    //item.remove();
                }
            },
            error:function(msg){
                bootbox.alert(msg);
            }
        });


        e.preventDefault();
        e.stopPropagation();

    }

    $('.spinner').TouchSpin({booster:false}).change(onSpinChnage);


    var onGroupDelete=function(e){

        e.preventDefault();

        var url=$(this).attr('href');

        var item=$(this).closest('.photo-group');

        var groupname=$('.editable span.name', item).text();

        url=url.replace(/groupname/g, groupname).replace();


        PhotobookApi.customRequest({
            url:url,
            success:function(result){


                if(result.response.status){
                    item.fadeToggle();
                }
            },
            error:function(msg){
                bootbox.alert(msg);
            }
        });

    };

    $('.btnDelete').bind('click', onGroupDelete);


    $('.tooltips').tooltip();



});