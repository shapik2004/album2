/*jslint unparam: true */
/*global window, $ */
$(function () {



    $( document ).ready(function() {

        var ref_id=$('#photos').data('ref');
        var id=$('#photos').data('id');


        var current_group_name='0';


        Page.init(
        {
                onChangePage:function(){

                    console.log('onChangePage:'+Page.getCurrentPage());

                    setTimeout(function(){

                        var thumbs=$('.mCSB_container > .editor-thumb');


                        for(var i=0; i<thumbs.length; i++){


                            if($(thumbs[i]).hasClass('editor-thumb')){

                                var photo_id=$(thumbs[i]).data('id');

                                addPhoto(ref_id, id, photo_id, true)
                            }

                        }
                    },1000);



                },
                onChangeImagePos:function(page, place_num, posX, posY, scale, callback){


                    var url=$('.page-handlers').data('url');

                    url=url+'&page='+page+'&place_num='+place_num+'&pos_x='+posX+'&pos_y='+posY+'&scale='+scale;


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            console.log(result);
                            if(result.response.status){


                                callback(result.response);

                                $('.svg-'+page).html('<svg  height="100%" viewBox="0 0 700 250">'+result.response.page.svg+'</svg>');

                            }
                        },
                        error:function(msg){
                            bootbox.alert(msg);
                        }
                    });

                },
                onImageRotate:function(page_index, photo_id, place_num, deg, callback){

                    showLoader();

                    var page=page_index;
                    var url=$('.page-handlers').data('rotateurl');
                    url=url+'&photo_id='+photo_id+'&deg='+deg+'&page='+page+'&place_num='+place_num;


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);

                                $('.svg-'+page).html('<svg  height="100%" viewBox="0 0 700 250">'+result.response.page.svg+'</svg>');


                                addPhoto(ref_id, id,photo_id, true)

                            }
                            hideLoader();
                        },
                        error:function(msg){
                            bootbox.alert(msg);

                            hideLoader();
                        }
                    });

                },
                onDeletePlaceholder:function(page_index, place_num, photo_id, callback){


                    var url=$('.page-handlers').data('deleteurl');
                    url=url+'&page='+page_index+'&place_num='+place_num+'&photo_id='+photo_id;


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);

                                $('.svg-'+page_index).html('<svg  height="100%" viewBox="0 0 700 250">'+result.response.page.svg+'</svg>');




                            }
                            //hideLoader();
                        },
                        error:function(msg){
                            bootbox.alert(msg);

                            //hideLoader();
                        }
                    });


                },
                onDeletePage:function(page, callback){

                    var url=$('.page-handlers').data('deletepageurl');
                    url=url+'&page='+page;

                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);





                            }
                            //hideLoader();
                        },
                        error:function(msg){
                            bootbox.alert(msg);

                            //hideLoader();
                        }
                    });


                },
                onPhotoAddToPage:function(page_index, photo_id, callback){

                    var url=$('.page-handlers').data('addphotourl');
                    url=url+'&page='+page_index+'&photo_id='+photo_id;


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);




                                addPhoto(ref_id,id,photo_id, true);


                            }
                            //hideLoader();
                        },
                        error:function(msg){
                            bootbox.alert(msg);

                            //hideLoader();
                        }
                    });


                },
                onAddText:function(page_index, text, callback){

                    var url=$('.page-handlers').data('addtexturl');
                    url=url+'&page_index='+page_index;


                    PhotobookApi.customRequest({
                        url:url,
                        type:'POST',
                        data:{text:text},
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);




                               /* addPhoto(ref_id,id,photo_id, true);*/


                            }
                            //hideLoader();
                        },
                        error:function(msg){
                            bootbox.alert(msg);

                            //hideLoader();
                        }
                    });


                },

                onChangeText:function(page_index, text, callback){

                    var url=$('.page-handlers').data('changetexturl');
                    url=url+'&page_index='+page_index;


                    PhotobookApi.customRequest({
                        url:url,
                        type:'POST',
                        data:{text:text},
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);




                                /* addPhoto(ref_id,id,photo_id, true);*/


                            }
                            //hideLoader();
                        },
                        error:function(msg){
                            bootbox.alert(msg);

                            //hideLoader();
                        }
                    });


                },
                onAddNewPage:function(page_index, photo_id, callback){

                    var url=$('#book-container').data('addurl');
                    url=url+'&page_index='+page_index+'&photo_id='+photo_id;


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);

                                addPhoto(ref_id,id,photo_id, true);


                                Page.updatePrice();


                            }
                            //hideLoader();
                        },
                        error:function(msg){
                            bootbox.alert(msg);

                            //hideLoader();
                        }
                    });


                },
                onReplacePhoto:function(page_index, place_num, photo_id, old_photo_id, callback){

                    var url=$('.page-handlers').data('replaceurl');
                    url=url+'&page='+page_index+'&place_num='+place_num+'&photo_id='+photo_id;


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);

                                $('.svg-'+page_index).html('<svg  height="100%" viewBox="0 0 700 250">'+result.response.page.svg+'</svg>');


                                addPhoto(ref_id,id,photo_id, true);
                                addPhoto(ref_id,id,old_photo_id, true);



                            }
                            //hideLoader();
                        },
                        error:function(msg){
                            bootbox.alert(msg);

                            //hideLoader();
                        }
                    });


                },
                onChangeLayout:function(page_index, callback){

                    var url=$('.page-handlers').data('changelayouturl');
                    url=url+'&page_index='+page_index;


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);

                                /*$('.svg-'+page_index).html('<svg  height="100%" viewBox="0 0 700 350">'+result.response.page.svg+'</svg>');*/


                               /* addPhoto(ref_id,id,photo_id, true);
                                addPhoto(ref_id,id,old_photo_id, true);*/



                            }
                            //hideLoader();
                        },
                        error:function(msg){
                            bootbox.alert(msg);

                            //hideLoader();
                        }
                    });


                },
                onSwapPhoto:function(page_index, new_place_num, new_photo_id, old_place_num, old_photo_id, callback){

                    var url=$('.page-handlers').data('swapurl');
                    url=url+'&page='+page_index+'&new_place_num='+new_place_num+'&old_place_num='+old_place_num;


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);

                               // $('.svg-'+page_index).html('<svg  height="100%" viewBox="0 0 700 350">'+result.response.page.svg+'</svg>');


                                addPhoto(ref_id,id,new_photo_id, true);
                                addPhoto(ref_id,id,old_photo_id, true);



                            }
                            //hideLoader();
                        },
                        error:function(msg){
                            bootbox.alert(msg);

                            //hideLoader();
                        }
                    });


                },
                onChangeAction:function(page_index, action, callback){

                    var url=$('.page-handlers').data('actionurl');
                    url=url+'&page_index='+page_index+'&action='+action;


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);




                            }
                            //hideLoader();
                        },
                        error:function(msg){
                            bootbox.alert(msg);

                            //hideLoader();
                        }
                    });


                },
                onMovePage:function(old_page_index, new_page_index, callback){

                    console.log('onMovePage start');
                    var url=$('#book-container').data('moveurl');
                    url=url+'&old_page_index='+old_page_index+'&new_page_index='+new_page_index;

                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){
                            if(result.response.status){
                                callback(result.response);
                            }
                        },
                        error:function(msg){
                            bootbox.alert(msg);
                        }
                    });


                },
                onCompleteDeletePlaceholder:function(photo_id){

                    addPhoto(ref_id, id, photo_id, true)
                },
                onPlaceEnterEdit:function(pid){

                    $('.photos').animate({bottom:'-170px'}, 500);
                    $('.tools').animate({bottom:'-170px'}, 500);

                    $('#navbar').animate({top:'-75px'}, 500);

                },
                onPlaceExitEdit:function(pid){

                    $('.photos').animate({bottom:'0px'}, 500);
                    $('.tools').animate({bottom:'0px'}, 500);

                    $('#navbar').animate({top:'0px'}, 500);
                },
                onSetMode:function(mode){


                    if($('#book-container').data('access')!='view') {

                        if (mode == 'cover') {

                            $('#navbar').animate({top: '-95px'}, 500, 'swing', function () {


                                //Page.setMode('cover');
                                $('#navbar_edit_cover').animate({top: '0px'}, 500, 'easeOutBounce');

                            });
                        } else if (mode == 'book') {


                            $('#navbar_edit_cover').animate({top: '-95px'}, 500, 'swing', function () {

                                $('#navbar').animate({top: '0px'}, 500, 'easeOutBounce');


                                //Page.setMode('book');
                                //Page.backCoverMode();

                            });

                        } else if (mode == 'pages') {


                        }

                    }


                }
            }
        );






        $( '.photos' ).hover(
            function(){

              /*  $(this).stop(true, true);
                $(this).animate({bottom:'0px'}, 500);*/

                if(Page.getCurrentPage()>0  && Page.getCurrentPage()<$('#bb-bookblock .bb-pitem').length-1) {
                    $('.page-handlers .div-add-to-page').css('display', 'block');
                    $('.page-handlers .div-add-to-page').animate({height: '75px'}, 200);
                }

            }, function(){

               /* $(this).stop(true, true);
                $(this).animate({bottom:'-70px'}, 250);*/
                if(Page.getCurrentPage()>0  && Page.getCurrentPage()<$('#bb-bookblock .bb-pitem').length-1) {

                    if (!$('.page-handlers .div-add-to-page').hasClass('drop-state')) {
                        $('.page-handlers .div-add-to-page').animate({height: '0px'}, 200, function () {

                            $('.page-handlers .div-add-to-page').css('display', 'none');
                        });
                    }

                }

            } );


       /* $( '.photos' ).mouseenter( function(){

            $(this).animate({bottom:'0px'}, 500);

        }).mouseleave( function(){

                $(this).animate({bottom:'-70px'}, 250);

        });*/

        var save_form=null;
        var l=null;
        var startUpload=function(e,data){

            console.log('start');


            l = Ladda.create($('.btnUploadPhotos')[0]);
            l.start();
        }

        var doneUpload=function (e, data) {


            console.log('doneUpload');
            console.log(e);
            console.log(data);
            if(data.result && data.result.response && data.result.response.status){

                console.log(data.result);

                addPhoto(ref_id, id,  data.result.response.photo_id, false );

            }else{

                console.error('Upload error');
            }

            l.stop();



            $('.fileuplod-cont').html(save_form);

            var group_name=$('.btnSelectGroup.active').data('value');

            var url=$('.fileupload').data('base')+'&group='+group_name;



            $('.fileupload').attr('data-url', url);

            $('.fileupload').closest('form').attr('action', url);




            //if

            $('.fileupload').fileupload({
                dataType: 'json',
                start:startUpload,
                done: doneUpload,
                alwaysUpload:alwaysUpload,
                progressall: progressallUpload
            }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');


        }

        var progressallUpload=function (e, data) {


            var progress = parseInt(data.loaded / data.total * 100, 10);

            l.setProgress( progress/100 );

        }


        var   alwaysUpload=function(e, data){

            console.log('alwaysUpload');
        }






        $(".photos-cont").mCustomScrollbar({
            axis:"x",
            advanced:{
                autoExpandHorizontalScroll:true
            }
        });

        var onDeleteGroup=function(){

            if($('.btnSelectGroup.active').length>0){

                var group_name=$('.btnSelectGroup.active').data('value');
                var url=$(this).data('url')+'&group='+group_name;

                var deleteFlag=true;
                var items=$('.mCSB_container').children();
                if(items.length>0){


                    for(var i=0; i<items.length; i++){

                        var photo_id=$(items[i]).data('id');
                        console.log(photo_id);

                        var exits=Page.findPhotoInPhotoBook(photo_id);

                        console.log(exits);
                        if(exits){
                            deleteFlag=false;
                            break;
                        }


                    }
                }

                if(!deleteFlag){

                    bootbox.alert('Вы не можите удалить группу "'+group_name+'", так как фотографии из этой группы используются в фотокниги.');
                }else{
                    //bootbox.alert('Удаляем...');


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){


                            if(result.response.status){

                                $('.btnSelectGroup.active').fadeToggle();

                                initPhotos('0');

                                $('.btnSelectGroup.all').addClass('active');
                                //item.fadeToggle();
                            }
                        },
                        error:function(msg){
                            bootbox.alert(msg);
                        }
                    });
                }

            }
        }

        $('.btnDeleteGroup').bind('click', onDeleteGroup);

        var onSelectGroup=function(){


            $('.btnSelectGroup').removeClass('active');

            if(save_form==null)
                save_form=$('.fileuplod-cont').html();


            $('.fileuplod-cont').html(save_form);


            var group_name=$(this).data('value');


            if(group_name=='text'){


                $('.fileuplod-cont').css('display', 'none');

                $('.btnAddNewText').css('display', 'block');

                group_name=$(this).html();
            }else{

                $('.fileuplod-cont').css('display', 'block');

                $('.btnAddNewText').css('display', 'none');

            }

            var url=$('.fileupload').data('base')+'&group='+group_name;



            $('.fileupload').attr('data-url', url);

            $('.fileupload').closest('form').attr('action', url);




            //if

            $('.fileupload').fileupload({
                dataType: 'json',
                start:startUpload,
                done: doneUpload,
                alwaysUpload:alwaysUpload,
                progressall: progressallUpload
            }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');



            $(this).addClass('active');

            console.log(group_name);

            initPhotos(group_name);

        };

        $('.btnSelectGroup').bind('click', onSelectGroup);

        $('.btnAddGroup').bind('click', function(){

            var url=$(this).data('url');
            bootbox.prompt('Введите имя группы', function(result) {
                if (result === null) {
                   // Example.show("Prompt dismissed");
                    console.log("Prompt dismissed");
                } else {
                    //Example.show("Hi <b>"+result+"</b>");

                    console.log(result);


                    if(result.length>0){

                        url+='&group='+result;

                        PhotobookApi.addGroup({
                            url:url,
                            success:function(data){

                                if(data.response.status){

                                    var elem=$('<a href="#" class="btn btn-tools btnSelectGroup" data-value="'+result+'">'+result+'</a>');

                                    $('.groups').append(elem);

                                    elem.bind('click', onSelectGroup);
                                }
                                console.log(data);
                                // console.log('onOk:'+text);
                            },
                            error:function(e){

                                console.log(e);
                                //console.log('error:'+ e.message);
                            }
                        });
                    }else{

                        bootbox.alert('Введите правильное название группы.');
                    }
                }
            });

        } )


        var addPhoto=function(ref_id, id, photo_id, update, ext){

            if(!ext){

                ext='jpg';
            }

            var exits_in_current_page=Page.findPhotoInCurrentPage(photo_id);
            var exits_in_photo_book=Page.findPhotoInPhotoBook(photo_id);

            var badgeClass='badge myhidden';


            if(exits_in_current_page){
                badgeClass='badge current';
            }else if(exits_in_photo_book){

                badgeClass='badge';
            }

            if(badgeClass!="badge myhidden"){

                var title='';
                var pages=Page.getPagesByPhotoId(photo_id);

                for(var page_index in pages){

                    if(title.length>0){
                        title+=", ";
                    }

                    title+=(pages[page_index]+1)+'';
                }

                if(pages.length==1){
                    title='На странице: '+title;
                }if(pages.length>1){
                    title='На страницах: '+title;
                }
            }


            console.log('exits_in_current_page:'+exits_in_current_page);

            if(update){

                $('.photo_'+photo_id+' .badge').attr('title', title);


                $('.photo_'+photo_id+' .badge').attr('class', badgeClass);

                ext=$('.photo_'+photo_id+'').data('ext');

                var random=Math.random();

                $('.photo_'+photo_id+' img').attr('src',UserUrl.photobookPhotos(ref_id, id)+'/'+UserUrl.imageFile(photo_id, UserUrl.Sizes.thumb, ext)+'?v='+random );

            }else{


                    var elem=$('<div  class="editor-thumb photo_'+photo_id+'" data-id="'+photo_id+'" data-ext="'+ext+'">'+
                        '<img data-id="'+photo_id+'" data-ext="'+ext+'" src="'+UserUrl.photobookPhotos(ref_id, id)+'/'+UserUrl.imageFile(photo_id, UserUrl.Sizes.thumb, ext)+'" >'+
                        '<a class="'+badgeClass+'" data-toggle="tooltip" data-placement="top" title="'+title+'"> <i class="fa fa-check"></i> </a> </div>');


                    elem.css('display', 'none');




                    $("img", elem).one("load", function() {
                        elem.fadeToggle();
                    }).each(function() {
                            if(this.complete) $(this).load();
                    });



                    $('.mCSB_container').append(elem);

                    $('.badge', elem).tooltip();

                    $('.badge', elem).bind('click', function(){

                        if(Page.getMode()!='book')
                        return false;
                        //$()
                        var pages=Page.getPagesByPhotoId(photo_id);

                        if(pages.length>0){
                            var currentPage=parseInt(Page.getCurrentPage());
                            currentPage++;

                            var index=0;
                            for(var i in pages){

                                if(parseInt(pages[i])+1==currentPage){

                                    index=i;
                                }
                            }

                            if(index<pages.length-1){
                                index++;

                            }else{

                                index=0;
                            }

                            if((parseInt(pages[index])+1)!=currentPage){

                                console.log('index:'+index+' parseInt(pages[index]):'+parseInt(pages[index])+1);
                                Page.jumpToPage(parseInt(pages[index])+1);
                            }

                        }

                    });

                    var createIcon=function(event){

                        console.log('createIcon');
                        console.log(event);

                        var photo_id=$(event.target).data('id');

                        var src=UserUrl.photobookPhotos(ref_id, id)+'/'+UserUrl.imageFile(photo_id, UserUrl.Sizes.thumb, ext);//$('.editor-thumb.photo_'+photo_id+' img').attr('src');

                        return $('<div class="draggable-thumb"  data-type="replace" style="" data-id="'+photo_id+'"><span class="badge"></span><img src="'+src+'" width="60"  height="60" /></div>');
                    }


                        $('img', elem).draggable({
                            connectToSortable: ".pages-area",
                            cursorAt:{left: 30, top:30},
                            helper: createIcon,
                            containment: 'frame',

                            appendTo:'body',
                            cursor:'move',
                            //When first dragged
                            stop: function (ev, ui) {

                                if(Page.getMode()=='book'){
                                    $('.page-handlers .div-add-to-page').removeClass('drop-state');

                                    $('.page-handlers .div-add-to-page').animate({height:'0px'}, 200, function(){

                                        $('.page-handlers .div-add-to-page').css('display', 'none');
                                    });

                                    $('.page-handlers .div-placeholder').removeClass('drop-state');

                                    $('.new-page-placeholder').removeClass('drop-state');
                                    /*$('.new-page-placeholder.next').animate({right:'-440px'});
                                    $('.new-page-placeholder.prev').animate({left:'-440px'});*/

                                    /*$('#newpage').animate({bottom:'-135px'});*/


                                    $('.tools').animate({bottom:'0px'}, 700, 'swing' );
                                    $('.photos').animate({bottom:'0px'}, 700, 'swing' );

                                    try {
                                        $('.new-page-placeholder.next').droppable('destroy');
                                    }catch(e){


                                    }

                                    try {
                                        $('.new-page-placeholder.prev').droppable('destroy');
                                    }catch(e){


                                    }

                                }else{

                                    $('.pages-area .div-add-page-placeholder').removeClass('drop-state');

                                    $('.pages-area .div-add-photo-placeholder').removeClass('drop-state');
                                }
                            },
                            start:function(ev, ui){

                                console.log('start drag');

                                if(Page.getMode()=='book'){


                                    $('.page-handlers .div-add-to-page').addClass('drop-state');

                                    $('.page-handlers .div-placeholder').addClass('drop-state');

                                    $('.new-page-placeholder').addClass('drop-state');

                                    var startDrop=function(className){

                                        console.log("startDrop "+ className);

                                        $('.new-page-placeholder.'+className).droppable({

                                            tolerance:'pointer',
                                            over:function(ev, ui){

                                                console.log('mega over over');

                                                $(ev.target).addClass('over');

                                                $('.page-handlers .div-placeholder').droppable('disable');



                                                /*$('.page-handlers .div-add-to-page').animate({height:'110px'}, 200);*/

                                                $('.draggable-thumb .badge').html('Создать новую страницу');
                                                $('.draggable-thumb .badge').css('display', 'inline');

                                                var dx=$('.draggable-thumb .badge').width()-$('.draggable-thumb img').width();
                                                dx=(dx/2)+5;
                                                $('.draggable-thumb .badge').css({left:'-'+dx+'px'});


                                            },
                                            out:function(ev, ui){

                                                console.log('mega over out');

                                                $(ev.target).removeClass('over');
                                                $('.page-handlers .div-placeholder').droppable('enable');

                                                /*$('.page-handlers .div-add-to-page').animate({height:'75px'}, 200);*/

                                                $('.draggable-thumb .badge').css('display', 'none');

                                            },
                                            drop:function(ev, ui){

                                                $(ev.target).removeClass('over')
                                                $(ev.target).removeClass('drop-hover')
                                                blockPlaceholder=false;

                                                console.log('add to page drop');

                                                var photo_id=$(ui.helper).data('id');

                                                console.log('photo_id:'+photo_id);

                                                if( $(ev.target).hasClass('next')){

                                                    //(page_index, photo_id, update_thumb, complete){
                                                    Page.addNewPage(Page.getCurrentPage()+1, photo_id,true, function(){



                                                        bb.next();
                                                        /*jumpToPage(that.currentPage+1);*/
                                                    });
                                                }else{

                                                    var page=Page.getCurrentPage();

                                                    if(page>0){
                                                        page=page;
                                                    }
                                                    Page.addNewPage(page, photo_id, true, function(){

                                                        bb.prev();


                                                    });
                                                }


                                            }
                                        })
                                    }

                                    console.log("Page.getCurrentPage():", Page.getCurrentPage());

                                    if(Page.getCurrentPage()>1) {
                                        startDrop('prev');
                                    }
                                    if(Page.getCurrentPage()>0 && Page.getCurrentPage()<$('#bb-bookblock .bb-pitem').length-1){

                                        startDrop('next');

                                    }

                                   /* $('.new-page-placeholder.next').animate({right:'0px'}, 'swing', startDrop);
                                    $('.new-page-placeholder.prev').animate({left:'0px'});*/

                                   /* $('#newpage').animate({bottom:'0px'});*/
                                    $('.tools').animate({bottom:'-175px'}, 700, 'swing' );
                                    $('.photos').animate({bottom:'-175px'}, 700, 'swing' );



                                }else{



                                    $('.pages-area .div-add-page-placeholder').not('.disabled').addClass('drop-state');

                                    $('.pages-area .div-add-photo-placeholder').not('.disabled').addClass('drop-state');
                                }


                                //$('.page-handlers .div-placeholder')

                            }
                        });



                    $(elem).mouseenter( function(){

                        console.log('hover');
                        $(this).animate({top:'-5px'}, 200);

                    }).mouseleave( function(){

                            $(this).animate({top:'0px'}, 200);

                    });
                }

        }

        var  initPhotos=function(group_name, start){

            var url=$('#photos').data('url');

            current_group_name=group_name;

            if(group_name!='0'){

                url=url+'&group_name='+group_name;
                $('.photos-left').animate({right:'190px'}, 200);
                $('.photos-right').animate({right:'20px'}, 200);

            }else{

                $('.photos-left').animate({right:'20px'}, 200);
                $('.photos-right').animate({right:'-185px'}, 200);

            }

            $('.mCSB_container').html('');

            PhotobookApi.customRequest({
                url:url,
                success:function(result){
                    if(result.response.status){
                        console.log(result.response);
                        var photos=result.response.photos;
                        var texts=result.response.texts;

                        console.log('texts', texts);
                        for(var photo_index in photos){

                            var photo_id= photos[photo_index];

                            var ext='jpg';

                            if(texts[photo_id]){

                                ext='png';
                            }

                            addPhoto(ref_id, id, photos[photo_index], false, ext)
                        }

                        if(start){
                            setTimeout(function(){
                                $('.start-loader').fadeToggle();

                                Page.setMode('cover');
                            }, 5000);
                        }
                    }
                },
                error:function(msg){
                    bootbox.alert(msg);
                }
            });
        }



        initPhotos(current_group_name, true);


        $('.btnChangeLayout').bind('click', function(){


            Page.changeLayout(Page.getCurrentPage());


        });

        var bb_z_index=0;

        $('.btnEditPages').bind('click', function(){

            $('#navbar').animate({top:'-95px'}, 500, 'swing', function(){

                $('#navbar_edit_layout').animate({top:'0px'}, 500, 'easeOutBounce', function(){

                    $('.page-control').fadeToggle();

                    bb_z_index=$('#book-container').css('z-index');
                    $('#book-container').css('z-index', 0)

                });

                $('.tools').css('z-index', 1021);

                $('.photos').css('z-index', 1022);


                myFadeToggle( $('#edit_layout_area'));




                $('.page-handlers .div-placeholder').droppable('disable');

                $('.page-handlers .div-add-to-page').droppable('disable');

                Page.setMode('pages');


                var createIcon3=function(event){

                    console.log('createIcon3');
                    console.log(event);

                    var html=$(event.currentTarget).html();


                    var index = $(event.currentTarget).attr('data-index');
                    var base_src=$('img',event.currentTarget).data('src');

                    var src=base_src+'&page='+index+'&v='+parseInt(Math.random()*100000);

                    var width=$(event.currentTarget).width();
                    var height=$(event.currentTarget).height();

                    var ox=width/2;
                    var oy=height/2;


                    $(this).draggable("option", "cursorAt", {
                        left: width/2,
                        top: height/2
                    });


                    return '<div style="transform: scale(0.75); width: '+width+'px; height:'+height+'px; background:#cccccc; "><img src="'+src+'" class="page-drag-handle" style="position:absolute;  width:'+width+'px; height:'+height+'px"/></div>';
                }

                var dragHandler={
                    cursorAt:{left: 0, top:0},
                    /* helper: createIcon,*/
                    refreshPositions: true,
                    containment: 'frame',
                    helper:createIcon3,
                    appendTo:'#edit_layout_area',
                    cursor:'move',
                    /* revert:true,*/
                    //When first dragged
                    stop: function (ev, ui) {

                        console.log('stop drag');
                        console.log(ui);
                        console.log(ev);
                        $('.pages-area .div-add-page-placeholder').removeClass('drop-state');

                        $(ev.target).css('opacity', 1.0);

                    },
                    start:function(ev, ui){

                        console.log('start drag1');
                        $('.pages-area .div-add-page-placeholder').not('.disabled').addClass('drop-state');

                        console.log(ui);
                        console.log(ev);

                        $(ev.currentTarget).css('opacity', 0.5);

                    }
                };



                var AddPhotoToPageOver=false;
                var AddNewPageOver=false

                var dropAddPhotoToPageHandler={
                    greedy:true,
                    tolerance:'pointer',
                    accept: function(d) {
                       // console.log('accept');
                       // console.log(d);
                        if(d.hasClass('mCS_img_loaded')){
                            return true;
                        }
                    },

                    over:function(ev, ui){

                        console.log('add photo to page over');
                        AddPhotoToPageOver=true;

                        console.log(ui);
                        console.log(ev);



                        var turnon_index=$(ev.target).data('index');
                        var turnon_type='addtopage';


                        $('.draggable-thumb .badge').attr('data-index-turnon',  turnon_index );
                        $('.draggable-thumb .badge').attr('data-type-turnon',  turnon_type );

                        $('.draggable-thumb .badge').html('Добавть на страницу');
                        $('.draggable-thumb .badge').css('display', 'inline');

                        var dx=$('.draggable-thumb .badge').width()-$('.draggable-thumb img').width();
                        dx=(dx/2)+5;
                        $('.draggable-thumb .badge').css({left:'-'+dx+'px'});

                        $('.draggable-thumb .badge').addClass('over');



                    },
                    out:function(ev, ui){

                        console.log('add photo to page out');
                        console.log(ui);
                        console.log(ev);

                        var index=$(ev.target).data('index');

                        var turnon_index=$('.draggable-thumb .badge').attr('data-index-turnon' );
                        var turnon_type=$('.draggable-thumb .badge').attr('data-type-turnon' );


                       if(turnon_type=='addtopage' && turnon_index==index){

                            $('.draggable-thumb .badge').css('display', 'none');
                       }

                        $('.draggable-thumb .badge').removeClass('over');

                    },
                    drop:function(ev, ui){


                        console.log('add to page drop');
                        console.log(ui);
                        console.log(ev);

                        var photo_id= $(ui.helper).data('id');
                        var page_index=parseInt($(ev.target).attr('data-index'));

                        Page.addPhotoToPage(page_index, photo_id);



                    }

                }



                var dropHandler={

                    tolerance:'pointer',
                    greedy:true,

                    over:function(ev, ui){

                        console.log('add new page over');

                        AddNewPageOver=true;

                        console.log(ui);
                        console.log(ev);

                        if(!$(ui.helper).hasClass('draggable-thumb')){

                            var after=parseInt($(ev.target).attr('data-after'));

                            var page_index=parseInt($(ui.draggable).attr('data-index'));

                            console.log('len:'+ $('.div-add-photo-placeholder').length)
                            if(after!=page_index-1 && after!=page_index && page_index!= $('.div-add-photo-placeholder').length-1){

                                $(ev.target).addClass('enter');

                            }else if(page_index== $('.div-add-photo-placeholder').length-1){

                                if(after!=page_index-1 && after!=page_index+1 ){

                                    $(ev.target).addClass('enter');
                                }
                            }
                        }else{

                            $(ev.target).addClass('enter');

                            var turnon_index=$(ev.target).data('index');
                            var turnon_type='addnewpage';

                            $('.draggable-thumb .badge').attr('data-index-turnon',  turnon_index );
                            $('.draggable-thumb .badge').attr('data-type-turnon',  turnon_type );

                            $('.draggable-thumb .badge').html('Добавть новую страницу');
                            $('.draggable-thumb .badge').css('display', 'inline');

                            var dx=$('.draggable-thumb .badge').width()-$('.draggable-thumb img').width();
                            dx=(dx/2)+5;
                            $('.draggable-thumb .badge').css({left:'-'+dx+'px'});


                            $('.draggable-thumb .badge').addClass('over');

                        }
                    },
                    out:function(ev, ui){

                        AddNewPageOver=false;
                        console.log('add new page out');
                        console.log(ui);
                        console.log(ev);
                        if($(ev.target).hasClass('enter')){

                            $(ev.target).removeClass('enter');
                        }
                        var index=$(ev.target).data('index');
                        var turnon_index=$('.draggable-thumb .badge').attr('data-index-turnon' );
                        var turnon_type=$('.draggable-thumb .badge').attr('data-type-turnon' );
                        if(turnon_type=='addnewpage' && turnon_index==index){

                            $('.draggable-thumb .badge').css('display', 'none');
                        }
                        $('.draggable-thumb .badge').removeClass('over');
                    },
                    drop:function(ev, ui){

                        AddNewPageOver=false;

                        console.log('add to page drag');
                        console.log(ui);
                        console.log(ev);

                        if($(ev.target).hasClass('enter')){

                            $(ev.target).removeClass('enter');

                            if($(ui.helper).hasClass('draggable-thumb')){


                                var page_index=parseInt($(ev.target).attr('data-index'));
                                var photo_id=$(ui.helper).data('id');

                                console.log('добавить страницу в:'+page_index+' с фото:'+photo_id );

                                Page.addNewPage(page_index, photo_id, false, function(page_index, result){

                                    $('.div-add-page-placeholder-'+page_index).droppable(dropHandler);

                                    $('.div-add-photo-placeholder-'+page_index+' div').not('.disabled').droppable(dropAddPhotoToPageHandler);

                                    $('.div-add-photo-placeholder-'+page_index+' div').draggable(dragHandler);

                                    $('.div-add-photo-placeholder-'+page_index+' select').bind('change', onChangeAction);

                                });

                            }else{


                                var new_page_index=parseInt($(ev.target).attr('data-index'));
                                var old_page_index=parseInt($(ui.draggable).attr('data-index'));


                                console.log('переместить страницу:'+old_page_index+' в:'+new_page_index);

                                Page.movePage(old_page_index, new_page_index, function(result){

                                })


                            }

                        }
                    }

                };

                var onChangeAction=function(e){


                    console.log('change action ');
                    console.log(e);
                    console.log($(this).val());

                    var action=$(this).val();
                    var page_index=parseInt($(this).attr('data-index'));
                    console.log('page_index:'+page_index);

                    if(action=='delete'){

                        showLoader('Удаляем...');
                        Page.deletePage(page_index, function(){

                            hideLoader();
                        });

                        /*$('.div-add-photo-placeholder-'+page_index).remove();
                        $('.div-add-page-placeholder-'+page_index).remove();*/

                        Page.updatePagesArea();

                    }else{


                        Page.changeAction(page_index, action );
                    }

                }

                $('.div-add-photo-placeholder div.subdiv').not('.disabled').droppable(dropAddPhotoToPageHandler);
                $('.div-add-photo-placeholder div').not('.disabled').draggable(dragHandler);
                $('.div-add-page-placeholder').not('.disabled').droppable(dropHandler);
                $('.div-add-photo-placeholder select').bind('change',onChangeAction);





            });
        });



        $('.btnEditCover').bind('click', function(){


            Page.editCover();
            $('.page-control').fadeToggle();



            $('#navbar').animate({top:'-95px'}, 500, 'swing', function(){


                Page.setMode('cover');
                $('#navbar_edit_cover').animate({top:'0px'}, 500, 'easeOutBounce');

            });


        });


        $('.btnBackCover').bind('click', function(){

            Page.backCoverMode();
        });


        $('.btnBack').bind('click', function(){

            $('#navbar_edit_layout').animate({top:'-95px'}, 500, 'swing', function(){

                $('#book-container').css('z-index', bb_z_index)
                $('#navbar').animate({top:'0px'}, 500, 'easeOutBounce');


                //$('#edit_layout_area').fade();


                myFadeToggle(  $('#edit_layout_area'));

                $('.page-control').fadeToggle();

                Page.setMode('book');

                $('.tools').css('z-index', 98);

                $('.photos').css('z-index', 99);

                try{
                    $('.div-add-photo-placeholder select').unbind('change');
                    $('.div-add-photo-placeholder div').draggable('destroy');
                    $('.div-add-page-placeholder').droppable('destroy');
                    $('.div-add-photo-placeholder div').droppable('destroy');


                    //
                }catch (e){

                }

            });


        });


        var onClickBtnAddNewText=function(){


            $('#dialogAddText').modal('show');


        }


        $('.btnAddNewText').bind('click', onClickBtnAddNewText)


        $('.btnAddText').bind('click', function(){


            $('#dialogAddText').modal('hide');

            showLoader('Сохраняем...');



            var url = $(this).data('url');

            var text=$('#textEdit').val();

            PhotobookApi.postRequest({
                url:url,
                data:{text:text},
                success:function(result){

                    console.log(' result.response:', result.response);

                    addPhoto(ref_id, id,  result.response.photo_id, false, 'png');

                    hideLoader();

                },
                error:function(msg){

                    console.log(msg);

                    hideLoader();
                }
            });


            /* Page.addText(Page.getCurrentPage(), $('#textEdit').val(), function(result){


                hideLoader();
            });*/


        });



        $('.btnChangeText').bind('click', function(){


            showLoader('Сохраняем...')

            Page.changeText(Page.getCurrentPage(), $('#textEdit').val(), function(result){

                hideLoader();

            });


        });



        /* Обновление текста обложки */

        var coverWindowTextUpdate=function(e){

            var val1=$(this).val();



            var url = $(this).data('url');
            PhotobookApi.postRequest({
                url:url,
                data:{value:val1},
                success:function(result){

                    console.log(result);
                    if(result.response.status){
                        //  $('.spanTemplateName').html(templateName);
                        /*
                         'text_image_url'=>$text_image_url,
                         'window_offset_x'=>$cover->window_offset_x,
                         'window_offset_y'=>$cover->window_offset_y,
                         'window_width'=>$cover->window_width,
                         'window_height'=>$cover->window_height
                         */

                        var tracing_text_image_url=result.response.tracing_text_image_url;

                        var text_image_url=result.response.text_image_url;
                        var window_offset_x=parseFloat(result.response.window_offset_x);
                        var window_offset_y=parseFloat(result.response.window_offset_y);

                        var window_width=parseFloat(result.response.window_width);
                        var window_height=parseFloat(result.response.window_height);

                        var name=result.response.name;

                        var cover_width=710;
                        var cover_height=260;

                        var area_width=100;
                        var area_height=100;

                        var scale_x=7.1;
                        var scale_y=2.6;

                        window_offset_x/=scale_x;
                        window_offset_y/=scale_y;

                        window_width/=scale_x;
                        window_height/=scale_y;


                        $('#coverWindowPreview').css('top', window_offset_y+'%' );
                        $('#coverWindowPreview').css('left', window_offset_x+'%' );


                        $('#coverWindowPreview').css('width', window_width+'%' );

                        $('#coverWindowPreview').css('height', window_height+'%' );


                        $('.tracing-text-background').css('background-image', 'url('+tracing_text_image_url+'?r='+Math.random()+')' );


                        $('#coverWindowPreview').css('background-image', 'url('+text_image_url+'?r='+Math.random()+')' );

                        $('.photobook-title').html(name);


                    }
                },
                error:function(msg){

                    console.log(msg);
                }
            });


        }

        $('.inputCoverWindowText').keyup(coverWindowTextUpdate);
        $('.inputCoverWindowText').change(coverWindowTextUpdate);



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

        $('.btnShowGetLinkDialogLink').bind('click', function (){


            $('#dialogGetLink').modal('show');

            return;

            var url=$(this).data('url');


            showLoader('Загрузка...');

        });


        $('.btnSendToEditFromCustomer').bind('click', function(){

            var $btn=$(this);
            var api_url=$btn.data('url');


            $btn.button('loading');


            PhotobookApi.postRequest({
                url:api_url,

                success:function(result){


                    console.log('result:', result);
                    if(result.response.status){


                        $('.btnSendToEditFromCustomer').closest('.btn-group').remove();

                        $('.btnSendToPrintFromCustomer').closest('.btn-group').remove();

                        $('#divSendToEditFromCustomer').css('display','');

                        Page.setStateStatus(STATUS_WAIT_PHOTOGRAPH_EDIT);

                        Page.updateCommentsArea(false);


                    }


                    //hideLoader();
                },
                error:function(msg){

                    $btn.button('reset');
                    bootbox.alert(msg);


                }
            });

        })


        $('.btnSendToPrintFromCustomer').bind('click', function(){


            var $btn=$(this);
            var api_url=$btn.data('url');


            $btn.button('loading');


            PhotobookApi.postRequest({
                url:api_url,

                success:function(result){


                    console.log('result:', result);
                    if(result.response.status){


                        $('.btnSendToEditFromCustomer').closest('.btn-group').remove();

                        $('.btnSendToPrintFromCustomer').closest('.btn-group').remove();

                        $('#divSendToPrintFromCustomer').css('display','');

                        Page.setStateStatus(STATUS_READY);

                        Page.updateCommentsArea(false);


                    }


                    //hideLoader();
                },
                error:function(msg){

                    $btn.button('reset');
                    bootbox.alert(msg);


                }
            });


        })


        if($("#book-container").data('access')=='view'){

            Page.updateCommentsArea(true);
        }



        var myFadeToggle=function ($obj){


            $obj.fadeToggle();

            return;

           /* var visibility=$obj.css('visibility');

            console.log("visibility:", visibility);

            if(visibility=='visible'){


                $obj.fadeTo("slow", 0, function(){

                    $obj.css('visibility', 'hidden');

                });

            }else{

                $obj.css('opacity', 0);

                $obj.css('visibility', 'visible');

                $obj.fadeTo("slow", 1, function(){

                    //$obj.css('visibility', 'hidden');

                });


            }*/

        }


        $('.cover-thumb-link').bind('click', function(){


            var $this=$(this);
            var cover_id=$this.data('id');
            var cover_front_url=$this.data('cover_front_url');
            var cover_back_url=$this.data('cover_back_url');
            var padded_cover_url=$this.data('padded_cover_url');



            var cover_price=parseFloat($this.data('price'));

            var cover_price_sign=$this.data('price_sign');

            $('.cover-thumb-link').removeClass('active');

            $this.addClass('active');


            console.log('cover_id', cover_id);
            console.log('cover_front_url', cover_front_url);

            console.log('cover_back_url', cover_back_url);

            console.log('padded_cover', padded_cover_url);

            $('#coverFrontBackground').css('background-image', 'url('+cover_front_url+')');

            $('#coverBackBackground').css('background-image', 'url('+cover_back_url+')');

            $('#coverPaddedBackground').css('background-image', 'url('+padded_cover_url+')');



            var url=$this.data('url');


            $('#book-container').data('coverprice', cover_price);
            $('#book-container').data('coverpricesign', cover_price_sign);


            /* тут нужно обновить цену */

            Page.updatePrice();



            PhotobookApi.customRequest({
                url:url,
                success:function(result){

                    if(result.response.status){


                      console.log(result);



                    }
                    //hideLoader();
                },
                error:function(msg){
                    bootbox.alert(msg);

                    //hideLoader();
                }
            });


        })


    });


});