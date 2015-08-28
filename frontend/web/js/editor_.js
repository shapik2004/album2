/*jslint unparam: true */
/*global window, $ */
$(function () {



    $( document ).ready(function() {


       // var url=$('#photos').data('url');
        var ref_id=$('#photos').data('ref');
        var id=$('#photos').data('id');


        var current_group_name='0';



        var showLoader=function(){


            $('.loader').fadeToggle();
        }

        hideLoader=function(){

            $('.loader').fadeToggle();
        }

        Page.init(
            {
                onChangePage:function(){

                    console.log('onChangePage:'+Page.getCurrentPage());

                    setTimeout(function(){

                        var thumbs=$('.mCSB_container > .editor-thumb');


                        for(var i=0; i<thumbs.length; i++){


                            if($(thumbs[i]).hasClass('editor-thumb')){

                                var photo_id=$(thumbs[i]).data('id');

                                // console.log(photo_id);

                                addPhoto(ref_id, id, photo_id, true)
                            }

                        }
                    },1000);



                },
                onChangeImagePos:function(page, place_index, posX, posY, scale, callback){


                    var url=$('.page-handlers').data('url');

                    url=url+'&page='+page+'&place_index='+place_index+'&pos_x='+posX+'&pos_y='+posY+'&scale='+scale;


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            console.log(result);
                            if(result.response.status){


                                callback(result.response);

                                $('.svg-'+page).html('<svg  height="100%" viewBox="0 0 700 350">'+result.response.page.svg+'</svg>');

                            }
                        },
                        error:function(msg){
                            bootbox.alert(msg);
                        }
                    });

                },
                onImageRotate:function(page_index, photo_id, place_id, deg, callback){

                    showLoader();

                    var page=page_index;
                    var url=$('.page-handlers').data('rotateurl');
                    url=url+'&photo_id='+photo_id+'&deg='+deg+'&page='+page+'&place_index='+place_id;


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);

                                $('.svg-'+page).html('<svg  height="100%" viewBox="0 0 700 350">'+result.response.page.svg+'</svg>');


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
                onDeletePlaceholder:function(page_index, place_index, photo_id, callback){


                    var url=$('.page-handlers').data('deleteurl');
                    url=url+'&page='+page_index+'&place_index='+place_index+'&photo_id='+photo_id;


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);

                                $('.svg-'+page_index).html('<svg  height="100%" viewBox="0 0 700 350">'+result.response.page.svg+'</svg>');




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
                onAddNewPage:function(page_index, photo_id, callback){

                    var url=$('#book-container').data('addurl');
                    url=url+'&page_index='+page_index+'&photo_id='+photo_id;


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
                onReplacePhoto:function(page_index, place_index, photo_id, old_photo_id, callback){

                    var url=$('.page-handlers').data('replaceurl');
                    url=url+'&page='+page_index+'&place_index='+place_index+'&photo_id='+photo_id;


                    PhotobookApi.customRequest({
                        url:url,
                        success:function(result){

                            if(result.response.status){


                                callback(result.response);

                                $('.svg-'+page_index).html('<svg  height="100%" viewBox="0 0 700 350">'+result.response.page.svg+'</svg>');


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
                onSwapPhoto:function(page_index, new_place_index, new_photo_id, old_place_index, old_photo_id, callback){

                    var url=$('.page-handlers').data('swapurl');
                    url=url+'&page='+page_index+'&new_place_index='+new_place_index+'&old_place_index='+old_place_index;


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

                    $('.photos').animate({bottom:'-70px'}, 500);
                    $('.tools').animate({bottom:'0px'}, 500);

                    $('#navbar').animate({top:'0px'}, 500);
                }
            }
        );


        $( '.photos' ).hover(
            function(){

                $(this).stop(true, true);
                $(this).animate({bottom:'0px'}, 500);

                $('.page-handlers .div-add-to-page').css('display', 'block');
                $('.page-handlers .div-add-to-page').animate({height:'75px'}, 200);

            }, function(){

                $(this).stop(true, true);
                $(this).animate({bottom:'-70px'}, 250);


                if(!$('.page-handlers .div-add-to-page').hasClass('drop-state')){
                    $('.page-handlers .div-add-to-page').animate({height:'0px'}, 200, function(){

                        $('.page-handlers .div-add-to-page').css('display', 'none');
                    });
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




        var addPhoto=function(ref_id, id, photo_id, update){


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

                var random=Math.random();

                $('.photo_'+photo_id+' img').attr('src',UserUrl.photobookPhotos(ref_id, id)+'/'+UserUrl.imageFile(photo_id, UserUrl.Sizes.thumb)+'?v='+random );

            }else{


                    var elem=$('<div  class="editor-thumb photo_'+photo_id+'" data-id="'+photo_id+'">'+
                        '<img data-id="'+photo_id+'" src="'+UserUrl.photobookPhotos(ref_id, id)+'/'+UserUrl.imageFile(photo_id, UserUrl.Sizes.thumb)+'" >'+
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

                        var src=UserUrl.photobookPhotos(ref_id, id)+'/'+UserUrl.imageFile(photo_id, UserUrl.Sizes.thumb);//$('.editor-thumb.photo_'+photo_id+' img').attr('src');

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

                                }else{



                                    $('.pages-area .div-add-page-placeholder').addClass('drop-state');

                                    $('.pages-area .div-add-photo-placeholder').addClass('drop-state');
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
                $('.photos-left').animate({right:'180px'}, 200);
                $('.photos-right').animate({right:'0px'}, 200);

            }else{

                $('.photos-left').animate({right:'0px'}, 200);
                $('.photos-right').animate({right:'-180px'}, 200);

            }

            $('.mCSB_container').html('');

            PhotobookApi.customRequest({
                url:url,
                success:function(result){
                    if(result.response.status){
                        console.log(result.response);
                        var photos=result.response.photos;
                        for(var photo_index in photos){
                            addPhoto(ref_id, id, photos[photo_index], false)
                        }

                        if(start){
                            setTimeout(function(){
                                $('.start-loader').fadeToggle();
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



        });

        var bb_z_index=0;

        $('.btnEditPages').bind('click', function(){

            $('#navbar').animate({top:'-75px'}, 500, 'swing', function(){

                $('#navbar_edit_layout').animate({top:'0px'}, 500, 'easeOutBounce', function(){

                    $('.page-control').fadeToggle();

                    bb_z_index=$('#book-container').css('z-index');
                    $('#book-container').css('z-index', 0)

                });

                $('#edit_layout_area').fadeToggle();


                $('.page-handlers .div-placeholder').droppable('disable');

                $('.page-handlers .div-add-to-page').droppable('disable');

                Page.setMode('pages');


                var createIcon3=function(event){

                    console.log('createIcon3');
                    console.log(event);

                    var html=$(event.currentTarget).html();


                    var src=$('img',event.currentTarget).attr('src');

                    var width=$(event.currentTarget).width();
                    var height=$(event.currentTarget).height();

                    var ox=width/2;
                    var oy=height/2;


                    $(this).draggable("option", "cursorAt", {
                        left: width/2,
                        top: height/2
                    });


                    return '<img src="'+src+'" class="page-drag-handle" style="position:absolute; transform: scale(0.75); width:'+width+'px; height:'+height+'px"/>';
                }

                var dragHandler={
                    cursorAt:{left: 0, top:0},
                    /* helper: createIcon,*/
                    refreshPositions: true,
                    containment: 'frame',
                    helper:createIcon3,
                    appendTo:'body',
                    cursor:'move',
                    /* revert:true,*/
                    //When first dragged
                    stop: function (ev, ui) {


                        console.log('stop drag');

                        console.log(ui);
                        console.log(ev);
                        $('.pages-area .div-add-page-placeholder').removeClass('drop-state');

                        /* $(ui.helper).attr('style', 'position:relative;');*/

                        $(ev.target).css('opacity', 1.0);

                    },
                    start:function(ev, ui){

                        console.log('start drag');
                        $('.pages-area .div-add-page-placeholder').addClass('drop-state');

                        /* $(ui.helper).addClass('page-drag-handle');*/

                        console.log(ui);
                        console.log(ev);

                        $(ev.currentTarget).css('opacity', 0.5);

                    }
                };

                $('.div-add-photo-placeholder div').draggable(dragHandler);

                var AddPhotoToPageOver=false;
                var AddNewPageOver=false

                var dropAddPhotoToPageHandler={
                    greedy:true,
                    tolerance:'pointer',
                    accept: function(d) {
                       /* if(d.hasClass("foo")||(d.attr("id")=="bar")){
                            return true;
                        }*/
                        console.log('accept');
                        console.log(d);
                        if(d.hasClass('mCS_img_loaded')){
                            return true;
                        }
                    },

                    over:function(ev, ui){

                        console.log('add photo to page over');
                       /* if(AddNewPageOver){

                           $(this).trigger('out', {draggable: ui.draggable, helper: ui.helper});
                           return;
                        }*/

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
                        AddPhotoToPageOver=false;
                        /*if(AddNewPageOver)
                            return;*/


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

                      /*  if(AddNewPageOver)
                            return;*/

                        AddPhotoToPageOver=true;
                        console.log('add to page drop');
                        console.log(ui);
                        console.log(ev);

                        var photo_id= $(ui.helper).data('id');
                        var page_index=parseInt($(ev.target).attr('data-index'));

                        Page.addPhotoToPage(page_index, photo_id);



                    }

                }

                $('.div-add-photo-placeholder div').droppable(dropAddPhotoToPageHandler);

                var dropHandler={

                    tolerance:'pointer',
                    greedy:true,
                    /*tolerance:'touch',*/
                    over:function(ev, ui){

                        /* $('.page-handlers .div-placeholder').droppable('disable');*/

                        console.log('add new page over');
                      /*  if(AddPhotoToPageOver){
                            $(this).trigger('out', {draggable: ui.draggable, helper: ui.helper});
                            return;
                        }*/

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

                        // $('.page-handlers .div-add-to-page').animate({height:'110px'}, 200);

                       /* $('.draggable-thumb .badge').html('Добавть на страницу');
                        $('.draggable-thumb .badge').css('display', 'inline');

                        var dx=$('.draggable-thumb .badge').width()-$('.draggable-thumb img').width();
                        dx=(dx/2)+5;
                        $('.draggable-thumb .badge').css({left:'-'+dx+'px'});*/

                    },
                    out:function(ev, ui){

                        AddNewPageOver=false;

                      /*  if(AddPhotoToPageOver)
                            return;*/
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

                       /* $('.draggable-thumb .badge').css('display', 'none');*/

                    },
                    drop:function(ev, ui){

                        AddNewPageOver=false;

                        console.log('add to page drag');
                        console.log(ui);
                        console.log(ev);

                        if($(ev.target).hasClass('enter')){


                            if($(ui.helper).hasClass('draggable-thumb')){

                                var before=0;
                                var after=parseInt($(ev.target).attr('data-after'));

                                var before_flag=false;

                                if(after<0) {
                                    //before=0;
                                    before_flag=true
                                }

                                $(ev.target).removeClass('enter');

                                var page_index=parseInt($(ev.target).attr('data-index'));
                                var photo_id=$(ui.helper).data('id');


                                if(before_flag){

                                    console.log('добавить страницу:'+page_index+' да страницы:'+before);

                                }else{

                                    console.log('добавить страницу:'+page_index+' после страницы:'+after );

                                }


                                Page.addNewPage(page_index, photo_id, false, function(result){



                                    var el_after=$('.div-add-photo-placeholder-'+after);

                                    var el_before=$('.div-add-page-placeholder-'+before);


                                    var el=$('.div-add-page-placeholder-0');

                                    var el_clone=el.clone();

                                    var el2=$('.div-add-photo-placeholder-0');

                                    var el2_clone=el2.clone();


                                    //el.fadeToggle(500, 'swing', function(){

                                        if(before_flag){

                                            el2_clone.insertBefore(el_before);
                                            $('div',el2_clone).css('opacity', 0);

                                            el_clone.insertBefore(el2_clone);
                                            //$('div',el2_clone).fadeTo(500, 1.0);

                                            /*$(this).remove();*/

                                        }else{
                                            el_clone.insertAfter(el_after);

                                            $('div',el2_clone).css('opacity', 0);
                                            el2_clone.insertAfter(el_clone);

                                            //$('div',el2_clone).fadeTo(500, 1.0);
                                            /*$(this).remove();*/
                                        }


                                        Page.updatePagesArea();

                                        $('.div-add-page-placeholder').droppable(dropHandler);
                                        $('.div-add-photo-placeholder div').draggable(dragHandler);

                                        if(before_flag){


                                            var b=$('#bb-bookblock').children()[before];
                                            var page=result.pages[page_index];

                                            var el='<div class="bb-item" id="item'+page_index+'">'+
                                                   '<div class="content" >'+
                                                   '<div class="box">'+
                                                   '<div class="svg svg-'+page_index+'"  data-index="'+page_index+'" style=" height: 100%;">'+
                                                   '<svg  height="100%" viewBox="0 0 700 350">'+
                                                   page.svg+
                                                   '</svg>'+
                                                   '</div>'+
                                                   '</div>'+
                                                   '</div>'+
                                                   '</div>';
                                            $(el).insertBefore(b);





                                        }else{

                                            var a=$('#bb-bookblock').children()[after];

                                            var page=result.pages[page_index];


                                            var el='<div class="bb-item" id="item'+page_index+'">'+
                                                '<div class="content" >'+
                                                '<div class="box">'+
                                                '<div class="svg svg-'+page_index+'"  data-index="'+page_index+'" style=" height: 100%;">'+
                                                '<svg  height="100%" viewBox="0 0 700 350">'+
                                                page.svg+
                                                '</svg>'+
                                                '</div>'+
                                                '</div>'+
                                                '</div>'+
                                                '</div>';
                                            $(el).insertAfter(a);


                                        }

                                        var items=$('#bb-bookblock').children();
                                        for(var i=0; i<items.length; i++){

                                            $(items[i]).attr('id', 'item'+i);

                                            $('.svg', $(items[i])).attr('class', 'svg svg-'+i);
                                            $('.svg', $(items[i])).attr('data-index', i);
                                        }

                                        $('#bb-nav-display').text((Page.getCurrentPage()+1)+"/"+items.length);

                                        Page.updatePagesArea();
                                        Page.getBB().update();

                                        Page.updateAllPage(false);

                                        setTimeout(function(){

                                            console.log('.svg-thumb-'+page_index+' img');
                                            var base_src= $('.svg-thumb-'+page_index+' img').data('src');

                                            var src=base_src+'&page='+page_index+'&v='+parseInt(Math.random()*100000);

                                            console.log('src:'+src);
                                            $('.svg-thumb-'+page_index+' img').attr('src',src );




                                        }, 500);


                                    $('div',el2_clone).css('opacity', 1);
                                    //});

                                    /*el2.fadeToggle(510, 'swing', function(){

                                        $(this).remove();

                                    });*/


                                });






                            }else{

                                var before=0;
                                var after=parseInt($(ev.target).attr('data-after'));

                                var before_flag=false;

                                if(after<0) {
                                    //before=0;
                                    before_flag=true
                                }

                                $(ev.target).removeClass('enter');

                                var page_index=parseInt($(ui.draggable).attr('data-index'));

                                if(before_flag){

                                    console.log('переместить страницу:'+page_index+' да страницы:'+before);

                                }else{

                                    console.log('переместить страницу:'+page_index+' после страницы:'+after );

                                }

                                var old_page_index=page_index;
                                var new_page_index=parseInt($(ev.target).attr('data-index'));

                                if(new_page_index>$('.div-add-photo-placeholder').length-1){

                                    new_page_index=$('.div-add-photo-placeholder').length-1;
                                }


                                Page.movePage(old_page_index, new_page_index, function(result){

                                    var el_after=$('.div-add-photo-placeholder-'+after);

                                    var el_before=$('.div-add-page-placeholder-'+before);


                                    var el=$('.div-add-page-placeholder-'+page_index);

                                    var el_clone=el.clone();

                                    var el2=$('.div-add-photo-placeholder-'+page_index);

                                    var el2_clone=el2.clone();


                                    el.fadeToggle(500, 'swing', function(){

                                        if(before_flag){

                                            el2_clone.insertBefore(el_before);
                                            $('div',el2_clone).css('opacity', 0);

                                            el_clone.insertBefore(el2_clone);
                                            $('div',el2_clone).fadeTo(500, 1.0);

                                            var base_src=$('.svg-thumb-'+page_index+' img').data('src');

                                            $('img',el2_clone).attr('src', base_src+'&page='+new_page_index+'&v='+parseInt(Math.random()*10000) );

                                            $(this).remove();

                                        }else{
                                            el_clone.insertAfter(el_after);

                                            $('div',el2_clone).css('opacity', 0);
                                            el2_clone.insertAfter(el_clone);

                                            $('div',el2_clone).fadeTo(500, 1.0);

                                            /*var base_src=$('.svg-thumb-'+page_index+' img').data('src');

                                            $('img',el2_clone).attr('src', base_src+'&page='+new_page_index+'&v='+parseInt(Math.random()*10000));*/
                                            el.remove();
                                        }

                                        setTimeout(function(){

                                            Page.updatePagesArea();

                                            $('.div-add-page-placeholder').droppable(dropHandler);
                                            $('.div-add-photo-placeholder div').draggable(dragHandler);

                                        },200);
                                    });

                                    el2.fadeToggle(510, 'swing', function(){

                                        el2.remove();

                                    });

                                })


                            }

                        }
                    }

                };

                $('.div-add-page-placeholder').droppable(dropHandler);
            });
        });


        $('.btnBack').bind('click', function(){

            $('#navbar_edit_layout').animate({top:'-75px'}, 500, 'swing', function(){

                $('#book-container').css('z-index', bb_z_index)
                $('#navbar').animate({top:'0px'}, 500, 'easeOutBounce');
                $('#edit_layout_area').fadeToggle();

                $('.page-control').fadeToggle();

               /* $('.page-handlers .div-placeholder').droppable('enable');

                $('.page-handlers .div-add-to-page').droppable('enable');*/

                Page.setMode('book');

                try{
                    $('.div-add-photo-placeholder div').draggable('destroy');
                    $('.div-add-page-placeholder').droppable('destroy');
                }catch (e){

                }

            });


        });


    });


});