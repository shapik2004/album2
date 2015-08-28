/*jslint unparam: true */
/*global window, $ */
updatePages=[];
count=0;
$(function () {



    var ref=$('.pages-area').data('ref');
    var id=$('.pages-area').data('id');

    $(document).ready(function(){
        $(".replace-photos").mCustomScrollbar({
            axis:"x",
            advanced:{
                autoExpandHorizontalScroll:true
            }
    });

    });


    /* Загрузак файлов */
    'use strict';

    var url = window.location.hostname === 'blueimp.github.io' ?
        '//jquery-file-upload.appspot.com/' : 'server/php/';


    var imgLoader=function(url, complete){

        console.log('start imgLoader:'+url);

        var img=new Image();

        img.onload=function(){
            complete(this);
        }
        img.src=url;
    }

    var pageLoader=function(src, page_index, completePageLoad){

        console.log('start pageLoader:'+url);

        var img=new Image();

        img.onload=function(){
            completePageLoad(this, page_index);
        }
        img.src=src;
    }




    /*

     */
    var existsUpdatePage=function(page_index){

        for(var i=0; i<updatePages.length; i++){

            if(updatePages[i]==page_index){
                return true;
            }

        }
        return false;
    }


    var addUpdatePage=function(page_index){


        console.log('addUpdatePage:'+page_index);
        if(!(existsUpdatePage(page_index))){

            updatePages[count]=page_index;
            count++;

            console.log('len:'+updatePages.length+' count:'+count);
        }

    }

    var addError=function(msg){

        var el_error=$('<div class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>'+
        ''+msg+'</div>');
        $('.upload-errors').append(el_error);
    }

    var upload_count=0;
    var upload_complete_count=0;


    var changeUpload=function(e, data){



        console.log('changeUpload');
        console.log(e);
        console.log(data);

        upload_count=0;
        upload_complete_count=0;

        updatePages=[];
        count=0;

        var display=$('#replacePhotosArea').css('display');

        if(display=='none'){
            $('#replacePhotosArea').fadeToggle();
        }
    }

    var startUpload=function(e,data){
        console.log('start');




        showLoader('Загрузка...');
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

                console.log('updatePages:'+updatePages.length+' count:'+count);
                console.log(updatePages);
                for(var i=0; i< count; i++){

                    var p_index=updatePages[i];

                    var v=parseInt(Math.random()*10000);
                    var src=$('.page-'+p_index+' img').data('src')+'&page='+p_index+'&v='+v;

                    console.log('update page src:'+src);

                    pageLoader(src, p_index, function(img, page_index){

                        console.log('complete update page src:'+img.src);
                        $('.page-'+page_index+' img').fadeToggle(500, 'swing', function(){

                            $(this).attr('src', img.src);
                            $(this).fadeToggle();
                        })

                    });
                }

                hideLoader();
            }, 1000);



            upload_count=0;
            upload_complete_count=0;

        }



    }

    var doneUpload=function (e, data) {

        console.log(data.result);
        if(data.result.hasOwnProperty('response') && data.result.response.status){

            console.log('Upload complete');



            var photo_id=data.result.response.photo_id;
            var mtime=data.result.response.mtime;
            var pages=data.result.response.pages;

            for(var index in pages){
                var page_index=pages[index];

                addUpdatePage(page_index);

            }

            var url=UserUrl.photobookPhotos(ref, id)+'/'+UserUrl.imageFile(photo_id, UserUrl.Sizes.thumb)+'?v='+mtime;

            imgLoader(url, function(img){

                $('.photo_'+photo_id+' img').fadeToggle(500, 'swing', function(){

                    $(this).attr('src', img.src);

                    $(this).fadeToggle();

                    $('.photo_'+photo_id+' .badge').addClass('active');
                })
            });



        }else{

            console.error('Upload error');

            addError(data.result.error.msg);
        }
    }

    var progressallUpload=function (e, data) {

        console.log('progressallUpload');
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





        }
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
        disableValidation: false,
        change:changeUpload,
        start:startUpload,
        fail:failUpload,
        always:alwaysUpload,


        done: doneUpload,
        progressall: progressallUpload,
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
                upload_count++;
                data.submit();
            });
            validation.fail(function(data) {
                console.log('Upload error: ' + data.files[0].error);

                addError(data.files[0].error);
            });

    });



    var clickMakePhotoZip=function(e){

        e.stopPropagation();
        console.log('clickMakePhotoZip');
        var url=$(this).data('url');



        showLoader('Создаем архив с фото...')

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




    console.log('init clickMakePhotoZip');
    $('.btnMakePhotoZip').bind('click', clickMakePhotoZip);


    $('.tooltips').tooltip();

    $('.btnCloseReplacePhoto').bind('click', function(){

        $('#replacePhotosArea').fadeToggle();
    })

   /* $(".scroll").mCustomScrollbar({
        axis:"y"

    });*/

});