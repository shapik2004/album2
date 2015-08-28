const STATUS_WAIT_CUSTOMER_COMMENTS=1;
const STATUS_WAIT_PHOTOGRAPH_EDIT=2;
const STATUS_CLOSE=3;
const STATUS_READY=4;


var Page = (function() {

    /*

     const  STATUS_WAIT_CUSTOMER_COMMENTS = 1;
     const  STATUS_WAIT_PHOTOGRAPH_EDIT=2;
     const  STATUS_CLOSE=3;
     const  STATUS_READY=3;
     */


    var that=this;
    var blockPlaceholder=false;
    var ref_id=$('#photos').data('ref');
    var id=$('#photos').data('id');
    this.pages=JSON.parse($('#book').text());
    var $container = $('#book-container');
    var	$bookBlock = $('#bb-bookblock');

    this.currentPage=0;
    this.coverCurrentPage=0;
    this.vellumCurrentPage=0;

    this.coverPageBlock=1;

    this.mode='book';

    this.stateStatus=STATUS_WAIT_CUSTOMER_COMMENTS;

    this.comments=null;



    this.commentsSelectedIndex=0;

    var updateCommentsArea=function(addEvent){

        if($("#book-container").data('access')!='view'){

            return;
        }

        if(that.commentsSelectedIndex>=0 && that.comments[that.commentsSelectedIndex]){

            console.log("that.commentsSelectedIndex:", that.commentsSelectedIndex);

            $('#commentTitle').html(that.comments[ that.commentsSelectedIndex].title);
            $('#commentText').val(that.comments[ that.commentsSelectedIndex].comment);
            $('#commentText').data('index',  that.commentsSelectedIndex );

            $('#commentText').attr('disabled', null);

            $('#commentContainer').css('display', '');



        }else{


            $('#commentContainer').css('display', 'none');
            $('#commentTitle').html('На данной странице, нельзя оставить комментарий');
            $('#commentText').val('');
            $('#commentText').data('index',  -1);

            $('#commentText').attr('disabled', 'disabled');
        }

        if(that.stateStatus!=STATUS_WAIT_CUSTOMER_COMMENTS){

            $('#commentText').attr('readonly', 'readonly');
        }

        if(addEvent){


            $('#commentText').keyup(function(){

                console.log('commentText.text:',   $('#commentText').val());

                var url=$('#commentText').data('url');
                var comment=$('#commentText').val();
                var index=$('#commentText').data('index');


                PhotobookApi.postRequest({
                    url:url,
                    data:{comment:comment, index:index},
                    success:function(result){



                    },
                    error:function(msg){

                       console.log("UpdateCommentsError:", msg);
                    }
                });


            })
        }
    }

    this.updateCommentsArea=updateCommentsArea;



    if($("#book-container").data('access')=='view'){

        this.stateStatus=$("#book-container").data('status');

        this.comments=JSON.parse($('#comments').text());



    }



	var	$items = $bookBlock.children();

    $('#bb-nav-display').text((this.currentPage+1)+"/"+$items.length);

    initSize();


    var cover = $( '#bb-cover' ).bookblock({
        speed : 800,
        perspective : 2000,
        shadowSides	: 0.0,
        shadowFlip	: 0.0,
        onEndFlip : function(old, page, isLimit) {


            that.coverCurrentPage=page;

            if(that.coverCurrentPage==that.coverPageBlock){

                setMode("book");
            }else{

                setMode("cover");

            }

            console.log("that.coverCurrentPag:", that.coverCurrentPage);


            if(that.mode=="book") {
                $('#bb-nav-display').text((that.currentPage + 1) + "/" + $('#bb-bookblock .bb-pitem').length);
            }else{

                $('#bb-nav-display').text('');
            }

            updateNavigation( isLimit );

            var prefix='#navbar ';


            if($('#book-container').data('access')=='view') {


                prefix='';
            }



            if(that.mode=="book"){



                if(that.currentPage==0){

                    $(prefix+'.current-state-title').html("Форзац");

                    that.commentsSelectedIndex=-1;

                }else if(that.currentPage==$('#bb-bookblock .bb-pitem').length-1){

                    $(prefix+'.current-state-title').html("Форзац");

                    that.commentsSelectedIndex=-1;

                }else{

                    $(prefix+'.current-state-title').html("Разворот "+(that.currentPage));

                    that.commentsSelectedIndex=that.currentPage;
                }

                updateCommentsArea(false);

            }else{


                if($('#book-container').data('access')=='view') {

                    $(prefix + '.current-state-title').html("Обложка");

                }


                console.log("Evrica!!!", that.coverCurrentPage, that.comments.length);
                if(that.coverCurrentPage==2){

                    that.commentsSelectedIndex=that.comments.length-1;
                }else{

                    that.commentsSelectedIndex=0;

                }

                updateCommentsArea(false);

            }


            Page.updatePrice();


            /* that.currentPage = page;




             updateTOC();

             updateNavigation( isLimit );

             updatePageHandlers();

             if(that.onChangePage){

             that.onChangePage();
             }*/

        }
    });



    var vellum = $( '#bb-vellum' ).bookblock({
        speed : 800,
        perspective : 2000,
        shadowSides	: 0.0,
        shadowFlip	: 0.0,
        onEndFlip : function(old, page, isLimit) {


            that.vellumCurrentPage=page;

        }
    });

   //



    var	itemsCount = $items.length,

		bb = $( '#bb-bookblock' ).bookblock({
			speed : 800,
			perspective : 2000,
			shadowSides	: 0.0,
			shadowFlip	: 0.0,
			onEndFlip : function(old, page, isLimit) {

                that.currentPage = page;//getBB().getCurrentPage();
                /*getBB().update();*/

                $('#bb-nav-display').text((that.currentPage+1)+"/"+$('#bb-bookblock .bb-pitem').length);
				// update TOC current
				updateTOC();
				// updateNavigation
				updateNavigation( isLimit );

                updatePageHandlers();

                if(that.onChangePage){

                    that.onChangePage();
                }

                var prefix='#navbar ';


                if($('#book-container').data('access')=='view') {


                    prefix='';
                }

                if(that.mode=="book"){


                    if(that.currentPage==0){

                        $(prefix+'.current-state-title').html("Форзац");

                        that.commentsSelectedIndex=-1;

                    }else if(that.currentPage==$('#bb-bookblock .bb-pitem').length-1){

                        $(prefix+'.current-state-title').html("Форзац");

                        that.commentsSelectedIndex=-1;
                    }else{

                        $(prefix+'.current-state-title').html("Разворот "+(that.currentPage));

                        that.commentsSelectedIndex=that.currentPage;
                    }

                    updateCommentsArea(false);

                }else{


                    if($('#book-container').data('access')=='view') {

                        $(prefix + '.current-state-title').html("Обложка");

                    }


                }


                Page.updatePrice();

			}
		}),

		$navNext = $( '#bb-nav-next' ),
		$navPrev = $( '#bb-nav-prev' ).hide(),
		$menuItems = $container.find( 'ul.menu-toc > li' ),
		$tblcontents = $( '#tblcontents' ),
		transEndEventNames = {
			'WebkitTransition': 'webkitTransitionEnd',
			'MozTransition': 'transitionend',
			'OTransition': 'oTransitionEnd',
			'msTransition': 'MSTransitionEnd',
			'transition': 'transitionend'
		},
		transEndEventName = transEndEventNames[Modernizr.prefixed('transition')],
		supportTransitions = Modernizr.csstransitions;

    that.bb=bb;






    /*cover.next(function(){


    });*/



    that.cover=cover;

    //that.mode='cover';


    //cover.next(); //coment by maxb

   /* setTimeout(function(){

        initSize();

    }, 2000);*/

    function renderPage(page_index, page_photos){

    }

	function init(obj) {

		initEvents();

        if(obj){


            if(obj.onChangePage){
                that.onChangePage=obj.onChangePage;
            }

            if(obj.onChangeImagePos){

                that.onChangeImagePos=obj.onChangeImagePos;
            }

            if(obj.onImageRotate){

                that.onImageRotate=obj.onImageRotate;

            }

            if(obj.onPlaceEnterEdit){

                that.onPlaceEnterEdit=obj.onPlaceEnterEdit;
            }

            if(obj.onPlaceExitEdit){

                that.onPlaceExitEdit=obj.onPlaceExitEdit;
            }


            if(obj.onDeletePlaceholder){

                that.onDeletePlaceholder=obj.onDeletePlaceholder;
            }

            if(obj.onDeletePage){

                that.onDeletePage=obj.onDeletePage;
            }

            if(obj.onCompleteDeletePlaceholder){

                that.onCompleteDeletePlaceholder=obj.onCompleteDeletePlaceholder;
            }

            if(obj.onPhotoAddToPage){

                that.onPhotoAddToPage=obj.onPhotoAddToPage;
            }

            if(obj.onReplacePhoto){

                that.onReplacePhoto=obj.onReplacePhoto;
            }
            if(obj.onSwapPhoto){

                that.onSwapPhoto=obj.onSwapPhoto;
            }

            if(obj.onMovePage){

                that.onMovePage=obj.onMovePage;
            }

            if(obj.onAddNewPage){

                that.onAddNewPage=obj.onAddNewPage;
            }


            if(obj.onChangeAction){

                that.onChangeAction=obj.onChangeAction;
            }
            if(obj.onChangeLayout){

                that.onChangeLayout=obj.onChangeLayout;
            }

            if(obj.onAddText){
                that.onAddText=obj.onAddText;
            }

            if(obj.onChangeText){

                that.onChangeText=obj.onChangeText;
            }


            if(obj.onSetMode){

                that.onSetMode=obj.onSetMode;
            }
        }

	}
    function getBB(){

        return that.bb;
    }

    function editCover(){

       /* var obj={
            end:function(page){

                if(page>0){


                    that.bb.prev(obj);


                }else{

                    that.bb.speed(800);

                    that.cover.prev(obj);
               }

                if(page<=0){
                    that.cover.prev();
                }
            }
        }

        //that.bb.speed(400);
        for(var i=1; i<=that.currentPage+1; i++){





            setTimeout(function(obj){

                that.bb.prev(obj);
            }, 200*i, obj)


        }*/

        that.cover.prev();



    }

    function backCoverMode(){


        if(that.coverCurrentPage==2){

            var url=$('.btnBackCover').data('url');

            location.href=url;

        }else {

            that.cover.next({

                end: function (page) {
                    //that.bb.prev();
                }
            });
        }

    }

    function createBBItemHtml(page_index, svg, url){

        var el='<div class="bb-item bb-pitem" id="item'+page_index+'">'+
            '<div class="content" >'+
            '<div class="box">'+
            '<div style="width: 100%; height: 100%; position: absolute;   background-size: cover; background-image: url(\''+url+'\')" >'+
            '<div class="svg svg-'+page_index+'"  data-index="'+page_index+'" style=" height: 100%;">'+
            '<svg  height="100%" viewBox="0 0 700 250">'+
            svg+
            '</svg>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>';

        return el;
    }

    function changeAction(page_index, action){


        that.onChangeAction(page_index, action, function(result){

            that.pages.pages[page_index]=result.page;

        });

    }

    function addNewPage(page_index, photo_id, update_thumb, complete){


        that.onAddNewPage(page_index, photo_id, function(result){


            that.pages.pages=result.pages;

            var background_url=result.background_url;

            var elem_page=$('.div-add-page-placeholder-1');

            var elem_page_clone=elem_page.clone();

            var elem_photo=$('.div-add-photo-placeholder-1');

            var elem_photo_clone=elem_photo.clone();
            //var height=parseInt($('img', elem_photo).css('height'));
            var width=parseInt($('img', elem_photo).css('width'));
            var height=parseInt($('img', elem_photo).css('height'));



            var handler=null;

            var before_flag=false;
            if(page_index<=0){
                before_flag=true;
                handler=$('.div-add-page-placeholder-1');
            }else{
                handler=$('.div-add-photo-placeholder-'+(page_index-1));
            }

            if(before_flag){
                elem_page_clone.insertBefore(handler);
            }else{
                elem_page_clone.insertAfter(handler);
            }

            var base_src= $('.svg-thumb-0'+' img').data('src');
            var src=base_src+'&page='+page_index+'&v='+parseInt(Math.random()*100000);



            $('img', elem_photo_clone).css({width:width+'px', height:height+'px', opacity: 0});
            elem_photo_clone.insertAfter(elem_page_clone);
            $('img', elem_photo_clone).one('load', function(){

                $( 'img', elem_photo_clone).animate({opacity: 1}, 200, 'swing', function(){

                    $('img', elem_photo_clone).css({width: '100%', height:'auto'});
                });



            }).each(function() {
                    if(this.complete) $(this).load();
                });

            $('img', elem_photo_clone).attr('src',src);





            var page=that.pages.pages[page_index];

            var elem=createBBItemHtml(page_index, page.svg, background_url);

            if(before_flag){
                var b=$('#bb-bookblock').children()[0];
                $(elem).insertBefore(b);
            }else{
                var a=$('#bb-bookblock').children()[page_index-1];
                $(elem).insertAfter(a);
            }

            var items=$('#bb-bookblock').children();

            for(var i=0; i<items.length; i++){



                $('.svg',items[i]).attr('class','svg svg-'+i);

                $('.svg',items[i]).attr('data-index',i);

                $(items[i]).prop('id', 'item'+i);
            }

            getBB().update();

            $('#bb-nav-display').text((that.currentPage+1)+"/"+$('#bb-bookblock .bb-pitem').length);




            updateNavigation();

            updatePagesArea();
            updateAllPage(update_thumb);
            updatePageHandlers();


            if(complete)
            {
                complete(page_index, result);
            }


        })

    }

    function movePage(old_page_index, new_page_index, complete){

        console.log('movPage')
        if(that.onMovePage){

            that.onMovePage(old_page_index, new_page_index, function(result){

                console.log('onMovPage complete old_page_index:'+old_page_index+' new_page_index:'+new_page_index);
                that.pages.pages=result.pages;

                var handler=null;

                var before_flag=false;
                if(new_page_index<=0){
                    before_flag=true;
                    handler=$('.div-add-page-placeholder-0');
                }else{
                    handler=$('.div-add-photo-placeholder-'+(new_page_index-1));
                }

                console.log(handler);

                var elem_page= $('.div-add-page-placeholder-'+old_page_index);
                var elem_photo= $('.div-add-photo-placeholder-'+old_page_index);

                if(before_flag){
                    elem_page.insertBefore(handler);
                }else{
                    elem_page.insertAfter(handler);
                }

                elem_photo.insertAfter(elem_page);



                updatePagesArea();
                updateAllPage(false);

                complete(result);


            });

        }

    }

    function updatePagesArea(){

        var items=$('.pages-area').children();
        var count=items.length;
        console.log('count:'+count)

        var j=0;
        for(var i=0; i<count; i++){


           /* if($(items[i]).hasClass('div-add-page-placeholder')){
                if(j==$('.div-add-photo-placeholder').length-1){
                    j--;
                }
            }*/

            var current_index=$(items[i]).data('index');

            if($(items[i]).hasClass('div-add-page-placeholder')){

                var newj=j;

                console.log('newj:'+newj);
                console.log('val:'+($('.div-add-photo-placeholder').length-1));
                if(i==count-1){
                    newj=$('.div-add-photo-placeholder').length
                }

                $(items[i]).attr('data-index', newj);



                $(items[i]).attr('data-after', newj-1);



                /* $(items[i]).removeClass('div-add-page-placeholder-'+current_index);
                 $(items[i]).addClass('div-add-page-placeholder-'+j);*/

                if(i!=count-1){
                    $(items[i]).attr('class', 'col-xs-1 col-md-1 div-add-page-placeholder ui-droppable div-add-page-placeholder-'+newj);

                    if(newj<=1){

                        $(items[i]).addClass('disabled');

                    }else if(newj>=$('.div-add-photo-placeholder').length-1){

                        $(items[i]).addClass('disabled');
                    }
                }



            }else{


                $(items[i]).attr('data-index', j);
                //col-xs-3 col-md-3 div-add-photo-placeholder div-add-photo-placeholder-3
                $(items[i]).attr('class', 'col-xs-3 col-md-3 div-add-photo-placeholder div-add-photo-placeholder-'+j);



                var div_el=$($(items[i]).children()[0]);
                div_el.attr('data-index', j);

                $(div_el.children()[0]).attr('data-index', j);

                $(div_el.children()[0]).attr('class', 'thumbnail svg-thumb svg-thumb-'+j );

                if(j>0 && j<$('.div-add-photo-placeholder').length-1) {
                    $('span', $(items[i])).html('Разворот ' + (j));
                }else{

                    $('span', $(items[i])).html('Форзац');
                }

                $('select', $(items[i])).attr('data-index', j);

                $('select', $(items[i])).attr('class', 'form-control action-select-'+j);






                j++;
            }
        }
    }


    function deletePage(page_index, complete){

        var page=page_index;

        if($('#bb-bookblock').children().length==1){

            complete();
            return;
        }

        if(that.onDeletePage){

            that.onDeletePage(page, function(result){

                var toPage=0;
                var delPage=that.pages.pages[page];


                var pages=[];
                var i=0;
                for(var index in that.pages.pages){

                    if(page!=index){

                        pages[i]=that.pages.pages[index];

                        console.log(pages[i]);
                        i++;
                    }
                }
                that.pages.pages=pages;

                var completeNext=function(page){


                    console.log('page:'+page);

                    bb.update();
                    console.log('page2:'+page);
                    var items=$('#bb-bookblock .bb-pitem');

                    for(var i=0; i<items.length; i++){

                        console.log('item:'+i);
                        $(items[i]).attr('id', 'item'+i);
                        $('.svg',items[i]).attr('class','svg svg-'+i);
                        $('.svg',items[i]).attr('data-index',i);
                    }

                    updatePagesArea();
                    updateNavigation();
                    updateTOC();
                    updatePageHandlers();

                    try{
                        for(var index in delPage.photos){

                            that.onCompleteDeletePlaceholder(delPage.photos[index].file_key);
                        }
                    }catch (e){


                    }

                    if(toPage==1){
                        bb.prev();
                    }else if(toPage==0){
                        bb.next();
                    }


                    if(complete){

                        complete();
                    }

                }


                var maxCountPage=$('#bb-bookblock .bb-pitem').length-1;




                if( that.currentPage==maxCountPage){
                    toPage=0;
                    bb.prev({end:completeNext})//.jumpToPage(page);
                }else if(that.currentPage==0){

                    toPage=1;
                    //Page.jumpToPage(page+2);
                    bb.next({end:completeNext})
                }else{

                    toPage=-1;
                    bb.next({end:completeNext})
                }

                console.log('page:'+page);
                console.log('that.currentPage:'+that.currentPage+' len:'+maxCountPage);




                $('#item'+page).remove();


                console.log('#item'+page);


                $('.div-add-page-placeholder-'+page).remove();


                $('.div-add-photo-placeholder-'+page).fadeToggle(250,'swing', function(){

                    $(this).remove();



                });


            });

        }


    }


    function changeLayout(page_index){

        that.onChangeLayout(page_index,  function(result){


            updatePage(page_index, result.page, true);

            updatePageHandlers();


        })

    }

    function addPhotoToPage(page_index, photo_id){


        that.onPhotoAddToPage(page_index, photo_id, function(result){


            updatePage(page_index, result.page, true);

            updatePageHandlers();


        })

    }



    function updateAllPage(update_thumb){



        for(var index in that.pages.pages){
            updatePage(index, false, update_thumb);
        }

    }

    function updatePage(page_index, page, update_thumb){


        if(page)
            that.pages.pages[page_index]=page;


        $('.svg-'+page_index).html('<svg  height="100%" viewBox="0 0 700 250">'+that.pages.pages[page_index].svg+'</svg>');


        if(update_thumb){
            var base_src=$('.svg-thumb-'+page_index+' img').data('src');

            var height=parseInt($('.svg-thumb-'+page_index+' img').css('height'));

            $('.svg-thumb-'+page_index+' img').css({height:height+'px'});


            $('.svg-thumb-'+page_index+' img').animate({opacity:0}, 200, 'swing', function(){

                $('.svg-thumb-'+page_index+' img').bind('load', function() {
                    // do stuff
                    $(this).animate({opacity:1}, 200);
                    $('.svg-thumb-'+page_index+' img').css({height:'auto'});
                });

                var src=base_src+'&page='+page_index+'&v='+parseInt(Math.random()*100000);

                $('.svg-thumb-'+page_index+' img').attr('src', src);
            })


        }


    }

    function addText(page_index, text, callback){



        that.onAddText(page_index, text, function(result){


            updatePage(page_index, result.page, true);

            updatePageHandlers();

            callback(result);

        })
    }

    function changeText(page_index, text, callback){



        that.onChangeText(page_index, text, function(result){


            updatePage(page_index, result.page, true);

            updatePageHandlers();

            callback(result);

        })
    }





    function jumpToPage(page_index){


        bb.jump(page_index);
        //that.currentPage=page_index;
    }

    function setPageData(page_index, data){

        that.pages.pages[page_index]=data;

        updatePageHandlers();
    }

    function getMode(){

        return that.mode;
    }

    function setMode(value){
        that.mode=value;
        initSize();

        if(that.onSetMode)
        that.onSetMode(that.mode);
    }

    function getCurrentPage(){

        return that.currentPage;

    }

    function findPhotoInCurrentPage(photo_id){

        if(!that.pages.pages[that.currentPage]){

            return false;
        }

        var pages=that.pages.pages[that.currentPage];

        for(var i in pages.photos){

            //console.log(pages.photos[i].file_key);
            if(pages.photos[i].file_key==photo_id){
                return true;
            }
        }

        return false;
    }

    function findPhotoInPhotoBook(photo_id){

        var pages=that.pages.pages[that.currentPage];

        for(var j in that.pages.pages){

            var page=that.pages.pages[j];
            for(var i in page.photos){

                if(page.photos[i].file_key==photo_id){
                    return true;
                }
            }
        }

        return false;
    }

    function getPagesByPhotoId(photo_id){

        var result_pages=[];

        var pages=that.pages.pages[that.currentPage];

        for(var j in that.pages.pages){

            var page=that.pages.pages[j];

            for(var i in page.photos){
                if(page.photos[i].file_key==photo_id){
                    result_pages[result_pages.length]=parseInt(j);
                }
            }
        }

        return result_pages;
    }

	function initEvents() {

		// add navigation events
		$navNext.on( 'click', function() {


            console.log("that.mode:", that.mode);

            if(that.mode=="book") {

                console.log("that.currentPage12:", that.currentPage, $('#bb-bookblock .bb-pitem').length);

                if((that.currentPage+1)==$('#bb-bookblock .bb-pitem').length) {


                    cover.next();

                }else{

                    if(that.currentPage==1 && that.vellumCurrentPage==0){

                        vellum.next();

                    }else{
                        bb.next();
                    }


                }

            }else{




                cover.next();
            }


			return false;
		} );

		$navPrev.on( 'click', function() {

            console.log("that.mode:", that.mode);

            if(that.mode=="book") {

                if(that.currentPage==0){

                    cover.prev();

                }else {

                    if(that.currentPage==1 && that.vellumCurrentPage==1) {

                        vellum.prev();
                    }else{

                        bb.prev();
                    }
                }
            }else{

                cover.prev();
            }

            return false;
		} );


		// show table of contents
		$tblcontents.on( 'click', toggleTOC );

		// click a menu item
		$menuItems.on( 'click', function() {

			var $el = $( this ),
				idx = $el.index(),
				jump = function() {
					bb.jump( idx + 1 );
				};
			
			that.currentPage !== idx ? closeTOC( jump ) : closeTOC();

			return false;
			
		});

		// reinit jScrollPane on window resize
		$( window ).on( 'debouncedresize', function() {
			// reinitialise jScrollPane on the content div
			//setJSP( 'reinit' );
            console.log('resize');

            initSize();
		} );

        $( window ).resize(function() {
            console.log('resizeing');
            initSize();
        });

	}

    function initSize(){


        console.log('init_size');

        var height=$('.div-add-photo-placeholder img').height();
        $('.div-add-page-placeholder').css('height', height+'px');
        $('.div-add-page-placeholder').css('padding-top', (height/2)-20);


        var $body = $( 'body');

        var $wrapper = $( '#wrapper');
        var $navbar=$('#navbar');

        var $tools=$('#tools');
        var $newpage=$('#newpage');

        var navbar_width=$body.width();
        var navbar_height=$navbar.height();
        var wrapper_height=$body.height();
        var tools_height=$tools.height();


        var $book_container=$('#book-container');

        var $edit_layout_area=$('#edit_layout_area');


        var book_width=710;
        var book_height=260;

        var book_aspect=book_width/book_height;

        var layout_width=navbar_width-30;
        var layout_height=wrapper_height-navbar_height-tools_height-16;

        var layout_aspect=layout_width/layout_height;

        var new_width, new_height;
        if (layout_aspect>=book_aspect)
        {
            new_width = Math.round(book_width / (book_height / layout_height));
            new_height = Math.round(layout_height);
        }
        else
        {
            new_width = Math.round(layout_width);
            new_height = Math.round(book_height / (book_width / layout_width));
        }

        new_width=parseInt(new_width+'');

        if(new_width/2!=parseInt((new_width/2)+'')){

            new_width++;
        }

        new_height=parseInt(new_height+'');

        if(new_height/2==parseInt((new_height/2)+'')){

            new_height++;
        }


        var image_posX=Math.round((layout_width-new_width ) / 2)+15;

        var image_posY=Math.round(layout_height-new_height  ) / 2;

        var top=parseFloat($('#book-container').css('top'));

        image_posY+=navbar_height+15;

        $('#book-container').css({'left':image_posX, 'top':image_posY, 'width':new_width, 'height':new_height});

        $('.bb-cover-wrapper').css({'position':'absolute', 'width':new_width, 'height':new_height});

        $('#bb-cover').css({'position':'absolute', 'width':new_width, 'height':new_height});


        var sx=book_width/new_width;
        var sy=book_height/new_height;


        var content_width=Math.round((book_width-10)/sx);

        var content_height=Math.round((book_height-10)/sy);

        $('.editor .bb-custom-wrapper').css({'left':Math.round((new_width-content_width)/2), 'top':Math.round((new_height-content_height)/2), 'width':content_width, 'height':content_height});

        $('#bb-bookblock').css({'position':'absolute', 'width':content_width, 'height':content_height});


        $('.bb-vellum-wrapper').css({'position':'absolute', 'width':content_width, 'height':content_height});

        $('#bb-vellum').css({'position':'absolute', 'width':content_width, 'height':content_height});


        if(that.mode=='book'){
            $tools.animate({'left':image_posX, 'right':image_posX}, 500);
            $newpage.animate({'left':image_posX, 'right':image_posX}, 500);
        }else if(that.mode=='cover'){
            $tools.animate({'left':image_posX, 'right':image_posX}, 500);
            $newpage.animate({'left':image_posX, 'right':image_posX}, 500);
        }else if(that.mode=='pages'){
            $tools.animate({'left':'30px', 'right':'30px'}, 500);
        }

        $('.page-handlers').width(content_width+'px');
        $('.page-handlers').height(content_height+'px');

        updatePageHandlers();
    }

	function setJSP( action, idx ) {
		


	}

	function updateTOC() {
		$menuItems.removeClass( 'menu-toc-current' ).eq( that.currentPage ).addClass( 'menu-toc-current' );
	}

    function updateDX(pid, scale){


        var old_scale= $('.div-placeholder-'+pid+' .overlay-photo').data('scale');

        var value=scale;


        var image_real_width=$('.div-placeholder-'+pid).data('firstimgwidth')*value;
        var image_real_height=$('.div-placeholder-'+pid).data('firstimgheight')*value;

        var width=$('.div-placeholder-'+pid).width();
        var height=$('.div-placeholder-'+pid).height();


        var old_i_pos_x=$('.div-placeholder-'+pid+' .overlay-crop-photo').data('imgposx');
        var old_i_pos_y=$('.div-placeholder-'+pid+' .overlay-crop-photo').data('imgposy');

        var old_center_pos_x=0-(((image_real_width/value)*old_scale)-width)/2;
        var old_center_pos_y=0-(((image_real_height/value)*old_scale)-height)/2;

        var dx=((old_center_pos_x-old_i_pos_x)/old_scale)*value;
        var dy=((old_center_pos_y-old_i_pos_y)/old_scale)*value;

        $('.div-placeholder-'+pid).data('dx', dx);
        $('.div-placeholder-'+pid).data('dy', dy);

    }


    function rotateImage(pid, page_index, photo_id, place_id, deg, x_ratio, y_ratio){


        that.onImageRotate(page_index, photo_id, place_id, deg, function(result){


            console.log(result);

            that.pages.pages[page_index]=result.page;

           /* var page_index=result.page_index;
            var place_num=result.place_num;

            var last_modified=result.last_modified;



            var place_index=0;
            for(var i in that.pages.pages[page_index].json){

                var p=that.pages.pages[page_index].json[i];

                if()
            }

            var placeholder=that.pages.pages[page_index].json[place_index];

            var scale=1;

            console.log(scale);

            var width=placeholder.width*x_ratio;
            var height=placeholder.height*y_ratio;

            var left=placeholder.left*x_ratio;
            var top=placeholder.top*y_ratio;

            left=left-(placeholder.width/2)*x_ratio;
            top=top-(placeholder.height/2)*y_ratio;


            var image_real_width=placeholder.image.image_real_width*x_ratio;
            var image_real_height=placeholder.image.image_real_height*y_ratio;

            var first_image_real_width=placeholder.image.first_image_real_width*x_ratio;
            var first_image_real_height=placeholder.image.first_image_real_height*y_ratio;

            $('.div-placeholder-'+pid).data('firstimgwidth', first_image_real_width);
            $('.div-placeholder-'+pid).data('firstimgheight', first_image_real_height);


            var img_pos_x=placeholder.image.img_pos_x*x_ratio;
            var img_pos_y=placeholder.image.img_pos_y*y_ratio;

            console.log('x_ratio:'+x_ratio);
            console.log('y_ratio:'+y_ratio);



            var new_img_pos_x=0-((placeholder.image.image_real_width*scale)-placeholder.width)/2;
            var new_img_pos_y=0-((placeholder.image.image_real_height*scale)-placeholder.height)/2;

            var del_x=img_pos_x-new_img_pos_x;
            var del_y=img_pos_y-new_img_pos_y;


            console.log('del_x:'+del_x);


            image_real_width=image_real_width*scale;
            image_real_height=image_real_height*scale;


            i_pos_x=0-((image_real_width)-width)/2;
            i_pos_y=0-((image_real_height)-height)/2;


            $('.div-placeholder-'+pid+' .overlay-crop-photo img').attr('src', UserUrl.photobookPhotos(ref_id, id)+'/'+UserUrl.imageFile(photo_id, UserUrl.Sizes.middle)+'?v='+last_modified);
            $('.div-placeholder-'+pid+' .overlay-photo img').attr('src', UserUrl.photobookPhotos(ref_id, id)+'/'+UserUrl.imageFile(photo_id, UserUrl.Sizes.middle)+'?v='+last_modified);
            $('.div-placeholder-'+pid+' .overlay-photo').data('scale', 1);




            $('.div-placeholder-'+pid+' .overlay-crop-photo img').css({transform:'translate('+i_pos_x+'px, '+i_pos_y+'px)', width: image_real_width, height:image_real_height});
            $('.div-placeholder-'+pid+' .overlay-photo img').css({transform:'translate('+i_pos_x+'px, '+i_pos_y+'px)',  width: image_real_width, height:image_real_height});
            $('.div-placeholder-'+pid+' .overlay-photo .image-overlay').css({transform:'translate('+i_pos_x+'px, '+i_pos_y+'px)',  width: image_real_width, height:image_real_height});


            $('.div-placeholder-'+pid+' .overlay-crop-photo').data('imgposx', i_pos_x);
            $('.div-placeholder-'+pid+' .overlay-crop-photo').data('imgposy', i_pos_y);

*/

            updateDX(pid, 1);

            updateAllPage(true);
            updatePageHandlers();

        });


    }


    function updatePageHandlers(){

        console.log('updatePageHandlers:'+that.currentPage);

        var access=$('#book-container').data('access');

        if(access=='view'){

            return;
        }

        try{

            $('.new-page-placeholder').droppable('destroy');
            $('.page-handlers .div-placeholder').draggable('destroy');

            $('.page-handlers .div-placeholder').droppable('destroy');

            $('.page-handlers .div-add-to-page').droppable('destroy');

        }catch (e){


        }


        var page=that.pages.pages[that.currentPage];

        if(!page)
        return;

        console.log(page);

        var $handlers=$('.page-handlers');

        $handlers.html('');

        var real_width=700;
        var real_height=250;
        var layout_width=$handlers.width();
        var layout_height=$handlers.height();

        var x_ratio=layout_width/real_width;
        var y_ratio=layout_height/real_height;



        console.log('layout_width:'+layout_width+' layout_height:'+layout_height);


        var ip=0;

        var place_index=0;
        for(var index in page.json){

            var placeholder=page.json[index];
            console.log(placeholder);


            var scale=placeholder.image.scale;

            var width=placeholder.width*x_ratio;
            var height=placeholder.height*y_ratio;

            var left=placeholder.left*x_ratio;
            var top=placeholder.top*y_ratio;

            left=left-(placeholder.width/2)*x_ratio;
            top=top-(placeholder.height/2)*y_ratio;


            var image_real_width=placeholder.image.image_real_width*x_ratio;
            var image_real_height=placeholder.image.image_real_height*y_ratio;

            var first_image_real_width=placeholder.image.first_image_real_width*x_ratio;
            var first_image_real_height=placeholder.image.first_image_real_height*y_ratio;

            var img_pos_x=placeholder.image.img_pos_x*x_ratio;
            var img_pos_y=placeholder.image.img_pos_y*y_ratio;



            var new_img_pos_x=0-((placeholder.image.image_real_width*scale)-placeholder.width)/2;
            var new_img_pos_y=0-((placeholder.image.image_real_height*scale)-placeholder.height)/2;

            var del_x=img_pos_x-new_img_pos_x;
            var del_y=img_pos_y-new_img_pos_y;

            console.log(" placeholder.image.ext:",  placeholder.image.ext);


            console.log('del_x:'+del_x);

            image_real_width=image_real_width*scale;
            image_real_height=image_real_height*scale;


            var pb_h_id=parseInt(Math.random()*100000000000);

            var html='';

            if(placeholder.hasOwnProperty('object_text') && placeholder.object_text){

                html='<div class="div-placeholder-text div-placeholder-text-'+pb_h_id+'" id="'+pb_h_id+'" data-firstimgwidth="'+first_image_real_width+'"  data-place="'+place_index+'"  data-firstimgheight="'+first_image_real_height+'" data-num="'+placeholder.data_name+'" data-id="'+placeholder.image.photo_id+'" style="left:'+left+'px; top:'+top+'px;   width: '+width+'px; height:'+height+'px;">'+
                           '<div class="overlay"  data-id="'+pb_h_id+'"></div>'+
                      '</div>';


            }else{



               // var place_i=p_index;


                html='<div class="div-placeholder div-placeholder-'+pb_h_id+'" id="'+pb_h_id+'" data-firstimgwidth="'+first_image_real_width+'"  data-place="'+placeholder.data_name+'"  data-firstimgheight="'+first_image_real_height+'" data-num="'+placeholder.data_name+'" data-id="'+placeholder.image.photo_id+'" style="left:'+left+'px; top:'+top+'px;   width: '+width+'px; height:'+height+'px;">'+

                    '<div class="overlay"  data-id="'+pb_h_id+'"></div>'+
                    '<div class="overlay-photo" data-scale="'+scale+'"  style="width: '+width+'px; height:'+height+'px;" >'+
                    '<img style="left:0px; width: '+image_real_width+'px; height: '+image_real_height+'px;  transform:translate('+img_pos_x+'px,'+img_pos_y+'px); -webkit-transform:translate('+img_pos_x+'px,'+img_pos_y+'px); -moz-transform:translate('+img_pos_x+'px,'+img_pos_y+'px); -ms-transform:translate('+img_pos_x+'px,'+img_pos_y+'px); -o-transform:translate('+img_pos_x+'px,'+img_pos_y+'px);" src="'+UserUrl.photobookPhotos(ref_id, id)+'/'+UserUrl.imageFile(placeholder.image.photo_id, UserUrl.Sizes.middle,  placeholder.image.ext)+'?v='+placeholder.image.last_modified+'" />'+
                    '<div class="image-overlay" style="width: '+image_real_width+'px; height: '+image_real_height+'px;  transform: translate('+img_pos_x+'px,'+img_pos_y+'px);  -webkit-transform: translate('+img_pos_x+'px,'+img_pos_y+'px);" -moz-transform: translate('+img_pos_x+'px,'+img_pos_y+'px);" -ms-transform: translate('+img_pos_x+'px,'+img_pos_y+'px); -o-transform: translate('+img_pos_x+'px,'+img_pos_y+'px);"></div>'+
                    '</div>'+
                    '<div class="overlay-crop-photo" data-scale="'+scale+'" data-imgposx="'+img_pos_x+'" data-imgposy="'+img_pos_y+'" data-id="'+pb_h_id+'" style="width: '+width+'px; height:'+height+'px;" >'+

                    '<img data-id="'+placeholder.image.photo_id+'"  style="width: '+image_real_width+'px; height: '+image_real_height+'px;  transform: translate('+img_pos_x+'px,'+img_pos_y+'px);  -webkit-transform: translate('+img_pos_x+'px,'+img_pos_y+'px);  -moz-transform: translate('+img_pos_x+'px,'+img_pos_y+'px);  -ms-transform: translate('+img_pos_x+'px,'+img_pos_y+'px); -o-transform: translate('+img_pos_x+'px,'+img_pos_y+'px); " src="'+UserUrl.photobookPhotos(ref_id, id)+'/'+UserUrl.imageFile(placeholder.image.photo_id, UserUrl.Sizes.middle, placeholder.image.ext)+'?v='+placeholder.image.last_modified+'" />'+
                    '</div>'+
                    '<div class="crop-tool" style="margin-left: '+(width-300)/2+'px; margin-right: '+(width-300)/2+'px; ">'+
                    '<a class="btn btn-primary btnPhotoRotateFromPB"  data-id="'+pb_h_id+'" ><i class="fa fa-rotate-right"></i></a>'+
                    '<div class="crop-zoom"><input style=" width: 160px; height: 40px;" type="text"  data-id="'+pb_h_id+'" value=""  class="crop-slider"  data-slider-min="1" data-slider-max="4" data-slider-step="0.05" data-slider-value="1" data-slider-orientation="horizontal" data-slider-selection="after" data-slider-tooltip="hide"></div>'+
                    '<a class="btn btn-primary btnPhotoOkFromPB" data-id="'+pb_h_id+'"><i class="fa fa-check" ></i></a>'+
                    '</div>'+
                    '<a href="#" class="btn btn-primary  btnPhotoRemoveFromPB" data-id="'+pb_h_id+'"><i class="fa fa-remove"></i></a>'+
                    '<div class="drop-icon"><i class="fa fa-refresh"></i></div>'+
                    '</div>';


                place_index++;

            }


            var $elem=$(html);
            $handlers.append($elem);







            $('.div-placeholder-'+pb_h_id+' .btnPhotoRemoveFromPB').bind('click', function(e){

                console.log('btnPhotoRemoveFromPB');
                 e.preventDefault();

                var page=Page.getCurrentPage();

                var pid=$(this).data('id');

                var place_index=$('.div-placeholder-'+pid).data('num');

                var photo_id=$('.div-placeholder-'+pid).data('id')

                console.log('PAGE:'+that.pages.pages[page]);
                console.log(that.pages.pages[page]);
                if(that.pages.pages[page].photos.length>1){
                    if(that.onDeletePlaceholder){

                        that.onDeletePlaceholder(page, place_index, photo_id, function(result){


                            updatePage(page, result.page, true);
                            updatePageHandlers();

                            that.onCompleteDeletePlaceholder(photo_id);

                        });
                    }
                }else{

                    Page.deletePage(page);

                }


            });

            $('.div-placeholder-'+pb_h_id+' .btnPhotoRotateFromPB').bind('click', function(){

                var pid=$(this).data('id');
                var photo_id=$('.div-placeholder-'+pid).data('id');
                var place_id=$('.div-placeholder-'+pid).data('num');
                rotateImage(pid, that.currentPage, photo_id, place_id, 90, x_ratio, y_ratio);
            });



            $('.div-placeholder-'+pb_h_id+' .crop-slider').slider()
                .on('slide', function(ev){


                    var pid=$(this).data('id');



                    var old_scale= $('.div-placeholder-'+pid+' .overlay-photo').data('scale');

                    var value=ev.value;


                    var image_real_width=$('.div-placeholder-'+pid).data('firstimgwidth')*value;
                    var image_real_height=$('.div-placeholder-'+pid).data('firstimgheight')*value;

                    var width=$('.div-placeholder-'+pid).width();
                    var height=$('.div-placeholder-'+pid).height();


                    var old_i_pos_x=$('.div-placeholder-'+pid+' .overlay-crop-photo').data('imgposx');
                    var old_i_pos_y=$('.div-placeholder-'+pid+' .overlay-crop-photo').data('imgposy');

                    var old_center_pos_x=0-(((image_real_width/value)*old_scale)-width)/2;
                    var old_center_pos_y=0-(((image_real_height/value)*old_scale)-height)/2;

                    var dx=((old_center_pos_x-old_i_pos_x)/old_scale)*value;
                    var dy=((old_center_pos_y-old_i_pos_y)/old_scale)*value;

                    $('.div-placeholder-'+pid).data('dx', dx);
                    $('.div-placeholder-'+pid).data('dy', dy);


                    i_pos_x=0-((image_real_width)-width)/2;
                    i_pos_y=0-((image_real_height)-height)/2;

                    i_pos_x=i_pos_x-dx;
                    i_pos_y=i_pos_y-dy;


                    if(i_pos_x>0){
                        i_pos_x=0;
                    }


                    if(i_pos_y>0){
                        i_pos_y=0;
                    }

                    if(i_pos_x<0-image_real_width+width){

                        i_pos_x=0-image_real_width+width;
                    }

                    if(i_pos_y<0-image_real_height+height){

                        i_pos_y=0-image_real_height+height;
                    }



                    $('.div-placeholder-'+pid+' .overlay-crop-photo img').css({transform:'translate('+i_pos_x+'px, '+i_pos_y+'px) ',  width: image_real_width, height:image_real_height});
                    $('.div-placeholder-'+pid+' .overlay-photo img').css({transform:'translate('+i_pos_x+'px, '+i_pos_y+'px) ',  width: image_real_width, height:image_real_height});
                    $('.div-placeholder-'+pid+' .overlay-photo .image-overlay').css({transform:'translate('+i_pos_x+'px, '+i_pos_y+'px) ',  width: image_real_width, height:image_real_height});


                    /*$('.div-placeholder-'+pid+' .overlay-crop-photo img').css({'-moz-transform':'translate('+i_pos_x+'px, '+i_pos_y+'px) ',  width: image_real_width, height:image_real_height});
                    $('.div-placeholder-'+pid+' .overlay-photo img').css({'-moz-transform':'translate('+i_pos_x+'px, '+i_pos_y+'px) ',  width: image_real_width, height:image_real_height});
                    $('.div-placeholder-'+pid+' .overlay-photo .image-overlay').css({'-moz-transform':'translate('+i_pos_x+'px, '+i_pos_y+'px) ',  width: image_real_width, height:image_real_height});

                    $('.div-placeholder-'+pid+' .overlay-crop-photo img').css({'-webkit-transform':'translate('+i_pos_x+'px, '+i_pos_y+'px) ',  width: image_real_width, height:image_real_height});
                    $('.div-placeholder-'+pid+' .overlay-photo img').css({'-webkit-transform':'translate('+i_pos_x+'px, '+i_pos_y+'px) ',  width: image_real_width, height:image_real_height});
                    $('.div-placeholder-'+pid+' .overlay-photo .image-overlay').css({'-webkit-transform':'translate('+i_pos_x+'px, '+i_pos_y+'px) ',  width: image_real_width, height:image_real_height});*/


                    /*

                     -moz-transform: rotate(15deg);
                    -ms-transform: rotate(15deg);
                    -webkit-transform: rotate(15deg);
                    -o-transform: rotate(15deg);
                    transform: rotate(15deg);
                     */

                    $('.div-placeholder-'+pid+' .overlay-photo').data('scale', value);
                    $('.div-placeholder-'+pid+' .overlay-crop-photo').data('imgposx',i_pos_x );
                    $('.div-placeholder-'+pid+' .overlay-crop-photo').data('imgposy',i_pos_y );


                    updateDX(pid, value);
                }
            );

            console.log('.div-placeholder-'+pb_h_id+' .overlay-crop-photo');

            var onClickEdit=function(){



                console.log('onClickEdit');
                if( $('.div-placeholder-'+$(this).data('id')).hasClass('editable'))
                {
                    return;
                }

                $('.div-placeholder-'+$(this).data('id')).draggable('disable');
                $('.div-placeholder-'+$(this).data('id')).addClass('editable');

                if(that.onPlaceEnterEdit){

                    that.onPlaceEnterEdit($(this).data('id'));
                }

                if(!$('.div-placeholder-'+$(this).data('id')).data('first'))
                    $('.div-placeholder-'+$(this).data('id')).data('first', true);
            };



            $('.div-placeholder-'+pb_h_id+' .overlay-crop-photo').bind('click',onClickEdit );

            var onClickOk=function(){

                var pid=$(this).data('id');

                var value=$('.div-placeholder-'+pid+' .overlay-photo').data('scale');

                updateDX(pid, value);

                var place_index=parseInt($('.div-placeholder-'+pid).data('num'));

                var dx=$('.div-placeholder-'+pid).data('dx');
                var dy=$('.div-placeholder-'+pid).data('dy');



                dx=dx/x_ratio;
                dy=dy/y_ratio;



                if( that.onChangeImagePos){
                    that.onChangeImagePos(
                        that.currentPage,
                        place_index,
                        dx,
                        dy,
                        value,
                        function(result){


                            updatePage(that.currentPage, result.page, true);

                            $('.div-placeholder-'+pid).removeClass('editable');


                            if(that.onPlaceExitEdit){

                                that.onPlaceExitEdit(pid);
                            }

                        }
                    );
                }



                $('.div-placeholder-'+pid).draggable('enable');



            };

            $('.div-placeholder-'+pb_h_id+' .btnPhotoOkFromPB').bind('click',onClickOk );
            $('.div-placeholder-'+pb_h_id+' .overlay').bind('click',onClickOk );


            $('.div-placeholder-'+pb_h_id+' .overlay-crop-photo' ).bind('mousedown',function(event) {

                var pid=$(this).data('id');

                var width =$(this).width();
                var height =$(this).height();

                var img_width=$('.div-placeholder-'+pid+' .overlay-crop-photo img').width();
                var img_height=$('.div-placeholder-'+pid+' .overlay-crop-photo img').height();



                var oldX=event.pageX;
                var oldY=event.pageY;


                var $crop=$(this);

                console.log(event);
                $(window).bind('mouseup', function( event ) {
                    $(window).unbind('mouseup');
                    $(window).unbind('mousemove');

                    console.log('mouseup2');

                });



                $(window).bind('mousemove', function( event ) {


                    var msg = "Handler for .mousemove() called at ";
                    msg += event.pageX + ", " + event.pageY;

                    var pid=$crop.data('id');

                    var first=$('.div-placeholder-'+pid).data('first');


                    if(first){

                        $(window).unbind('mouseup');
                        $(window).unbind('mousemove');

                        $('.div-placeholder-'+pid).data('first', false);

                        return;
                    }

                    var rotate=$('.div-placeholder-'+pid).data('rotate');

                    if(!rotate) rotate=0;


                    var dx=event.pageX-oldX;
                    var dy=event.pageY-oldY;


                    oldX=event.pageX;
                    oldY=event.pageY;

                    var i_pos_x=$crop.data('imgposx');
                    var i_pos_y=$crop.data('imgposy');

                    var scale= $('.div-placeholder-'+pid+' .overlay-photo').data('scale');

                    var image_real_width=$('.div-placeholder-'+pid).data('firstimgwidth')*scale;
                    var image_real_height=$('.div-placeholder-'+pid).data('firstimgheight')*scale;

                    scale=$('.div-placeholder-'+pid+' .overlay-photo').data('scale');
                    updateDX(pid, scale);

                    i_pos_x+=(dx*scale);
                    i_pos_y+=(dy*scale);

                        if(i_pos_x>0){

                            i_pos_x=0;
                        }

                        if(i_pos_y>0){

                            i_pos_y=0;
                        }

                        if(i_pos_x<0-image_real_width+width){

                            i_pos_x=0-image_real_width+width;
                        }


                        if(i_pos_y<0-image_real_height+height){

                            i_pos_y=0-image_real_height+height;
                        }

                    $crop.data('imgposx', i_pos_x);
                    $crop.data('imgposy', i_pos_y);

                    $('.div-placeholder-'+pid+' .overlay-crop-photo img').css({
                        transform: 'translate('+(i_pos_x)+'px, '+(i_pos_y)+'px)'
                    });

                    $('.div-placeholder-'+pid+' .overlay-photo img').css({
                        transform: 'translate('+(i_pos_x)+'px, '+(i_pos_y)+'px)'
                    });

                    $('.div-placeholder-'+pid+' .overlay-photo .image-overlay').css({
                        transform: 'translate('+(i_pos_x)+'px, '+(i_pos_y)+'px)'
                    });

                });



            });


            ip++;

        }

        var left=0;
        var bottom=0;

        width=real_width*x_ratio;
        height=0*x_ratio;


        var html='<div class="div-add-to-page"  style="left:'+left+'px; bottom:'+bottom+'px;   width: '+width+'px; height:'+height+'px;">'+
                 '<label>'+'Добавить на страницу'+'</label>'+
                 '</div>';


        var $elem=$(html);
        $handlers.append($elem);



        $('.page-handlers .div-add-to-page').droppable({

            over:function(ev, ui){

                $('.page-handlers .div-placeholder').droppable('disable');

                console.log('add to page over');

                if(that.currentPage>0) {
                    $('.page-handlers .div-add-to-page').animate({height: '110px'}, 200);

                    $('.draggable-thumb .badge').html('Добавть на страницу');
                    $('.draggable-thumb .badge').css('display', 'inline');

                    var dx = $('.draggable-thumb .badge').width() - $('.draggable-thumb img').width();
                    dx = (dx / 2) + 5;
                    $('.draggable-thumb .badge').css({left: '-' + dx + 'px'});

                }


            },
            out:function(ev, ui){
                if(that.currentPage>0) {

                    $('.page-handlers .div-placeholder').droppable('enable');

                    $('.page-handlers .div-add-to-page').animate({height: '75px'}, 200);

                    $('.draggable-thumb .badge').css('display', 'none');
                }

            },
            drop:function(ev, ui){

                if(that.currentPage>0) {
                    blockPlaceholder = false;

                    console.log('add to page drop');

                    var photo_id = $(ui.helper).data('id');

                    console.log('photo_id:' + photo_id);


                    addPhotoToPage(that.currentPage, photo_id);
                }


            }
        })








        var page_index=that.currentPage;


        var createIcon=function(ev){

            console.log('createIcon2');

            var photo_id=$(ev.currentTarget).data('id');

            var old_place=$(ev.currentTarget).data('place');

            var old_num=$(ev.currentTarget).data('num');

            var src=UserUrl.photobookPhotos(ref_id, id)+'/'+UserUrl.imageFile(photo_id, UserUrl.Sizes.thumb);//$('.editor-thumb.photo_'+photo_id+' img').attr('src');

            return $('<div class="draggable-thumb" data-type="swap"  data-num="'+old_place+'" data-place="'+old_place+'" style="" data-id="'+photo_id+'"><span class="badge"></span><img src="'+src+'" width="60" height="60" /></div>');
        }


        $('.page-handlers .div-placeholder').draggable({
            cursorAt:{left: 30, top:30},
            helper: createIcon,
            appendTo:'body',
            cursor:'move',
            stop: function (ev, ui) {

                console.log('stop drag');
                $('.page-handlers .div-placeholder').removeClass('drop-state');

       //         $('.new-page-placeholder.next').animate({right:'-160px'});
       //         $('.new-page-placeholder.prev').animate({left:'-160px'});

            },
            start:function(ev, ui){

                console.log('start drag');
                $('.page-handlers .div-placeholder').addClass('drop-state');

       //         $('.new-page-placeholder.next').animate({right:'-60px'});
       //         $('.new-page-placeholder.prev').animate({left:'-60px'});

            }
        });

        $('.page-handlers .div-placeholder').droppable({
            tolerance:'intersect',
            over:function(ev, ui){


                if(blockPlaceholder) return;
                console.log('replace to page over');


                var type=$(ui.helper).data('type');

                if(type=='replace'){

                    $('.draggable-thumb .badge').html('Заменить фото');


                }else if(type=='swap'){

                    $('.draggable-thumb .badge').html('Поменять местами фото');
                }


                $('.draggable-thumb .badge').css('display', 'inline');


                var dx=$('.draggable-thumb .badge').width()-$('.draggable-thumb img').width();
                dx=(dx/2)+5;
                $('.draggable-thumb .badge').css({left:'-'+dx+'px'});

                $(this).addClass('drop-hover');




                console.log('type:'+type);

            },
            out:function(ev, ui){
                if(blockPlaceholder) return;

                $(this).removeClass('drop-hover')

                $('.draggable-thumb .badge').css('display', 'none');


            },
            drop:function(ev, ui){

                if(blockPlaceholder) return;


                var type=$(ui.helper).data('type');

                if(type=='replace'){
                    if(that.onReplacePhoto){

                        var place_index=$(ev.target).data('num');

                        var old_photo_id=$(ev.target).data('id');

                        var photo_id=$(ui.helper).data('id');


                        that.onReplacePhoto(that.currentPage, place_index, photo_id, old_photo_id, function(result){


                            updatePage(that.currentPage, result.page, true);
                            updatePageHandlers();


                        });
                    }
                }else if(type=='swap'){

                    if(that.onSwapPhoto){

                        var new_place_index=$(ev.target).data('num');
                        var new_photo_id=$(ev.target).data('id');

                        var old_place_index=$(ui.helper).data('num');
                        var old_photo_id=$(ui.helper).data('id');

                        that.onSwapPhoto(that.currentPage, new_place_index, new_photo_id, old_place_index, old_photo_id, function(result){

                            updatePage(that.currentPage, result.page, true);
                            updatePageHandlers();


                        });
                    }
                }



            }
        });


        console.log('that.mode:'+that.mode);
        if(that.mode=='book'){

            try{
                $('.page-handlers .div-placeholder').droppable('enable');
            }catch (e){

            }

            try{
                $('.page-handlers .div-add-to-page').droppable('enable');
            }catch (e){

            }

            // $('.pages-area').sortable('disable');

        }else{

            try{
                $('.page-handlers .div-placeholder').droppable('disable');
            }catch (e){

            }

            try{
                $('.page-handlers .div-add-to-page').droppable('disable');
            }catch(e) {

            }

            //  $('.pages-area').sortable('enable');
        }




        var page_index=that.currentPage;

        var page=that.pages.pages[page_index];

        var isTextOnPage=false;

        if(page.hasOwnProperty('text')){

            if(page.text.hasOwnProperty('text')){

                $('#dialogAddTextLabel').html('Изменить текст');
                $('#textEdit').val(page.text.text);
                $('.btnCancelChangeText').css({display:'none'});
                $('.btnDeleteText').css({display:'block'});
                $('.btnAddText').css({display:'none'});
                $('.btnChangeText').css({display:'block'});

                isTextOnPage=true;
            }
        }

        if(!isTextOnPage){

            $('#dialogAddTextLabel').html('Добавить текст');
            $('#textEdit').val('');
            $('.btnCancelChangeText').css({display:'block'});
            $('.btnDeleteText').css({display:'none'});

            $('.btnAddText').css({display:'block'});
            $('.btnChangeText').css({display:'none'});
        }


        console.log('End updateHendlers');
        console.log(page);


    }

	function updateNavigation( isLastPage ) {

        if(that.mode=='cover') {


            if( that.coverCurrentPage === 0 ) {
                $navNext.show();
                $navPrev.hide();
            }else if(isLastPage){

                $navNext.hide();
                $navPrev.show();

            }else{

                $navNext.show();
                $navPrev.show();

            }


        }else if(that.mode=="book"){


            $navNext.show();
            $navPrev.show();

        }
		/*if( that.currentPage === 0 ) {
			$navNext.show();
			$navPrev.hide();
		}
		else if( isLastPage ) {
			$navNext.hide();
			$navPrev.show();
		}
		else {
			$navNext.show();
			$navPrev.show();
		}*/

	}

    function updatePrice(){



        var price_spread=parseFloat($('#book-container').data('pricespread'));
        var curse=parseFloat($('#book-container').data('curse'));


        var cover_price_sign=$('#book-container').data('coverpricesign');
        var cover_price=parseFloat($('#book-container').data('coverprice'));



        console.log("updatePrice price_spread:", price_spread);

        console.log("updatePrice curse:", curse);

        console.log("updatePrice cover_price_sign:", cover_price_sign);

        console.log("updatePrice cover_price:", cover_price);

        var total_price=price_spread*($('#bb-bookblock .bb-pitem').length-2);


        console.log("updatePrice total_price:", total_price);


        if(cover_price_sign=="="){

            total_price=cover_price;
        }else if(cover_price_sign=="+"){

            total_price+=cover_price;
        }else if(cover_price_sign=="-"){

            total_price-=cover_price;
        }

        $('#priceDisplay').html((total_price*curse).toFixed(2));



    }

    function updateCommentsAreaRef(addEvent){

        that.updateCommentsArea(addEvent);

    }

    function setStateStatus(status){

        that.stateStatus=status;
    }

	function toggleTOC() {
		var opened = $container.data( 'opened' );
		opened ? closeTOC() : openTOC();
	}

	function openTOC() {
		/*$navNext.hide();
		$navPrev.hide();*/
		$container.addClass( 'slideRight' ).data( 'opened', true );
	}

	function closeTOC( callback ) {

		updateNavigation( that.currentPage === itemsCount - 1 );
		$container.removeClass( 'slideRight' ).data( 'opened', false );
		if( callback ) {
			if( supportTransitions ) {
				$container.on( transEndEventName, function() {
					$( this ).off( transEndEventName );
					callback.call();
				});
			}
			else {
				callback.call();
			}
		}
	}

	return {
        init : init,
        findPhotoInCurrentPage:findPhotoInCurrentPage,
        findPhotoInPhotoBook:findPhotoInPhotoBook,
        getPagesByPhotoId:getPagesByPhotoId,
        getCurrentPage:getCurrentPage,
        jumpToPage:jumpToPage,
        setPageData:setPageData,
        deletePage:deletePage,
        getMode:getMode,
        setMode:setMode,
        setStateStatus:setStateStatus,
        updateCommentsArea:updateCommentsAreaRef,
        movePage:movePage,
        updatePagesArea:updatePagesArea,
        addPhotoToPage:addPhotoToPage,
        addNewPage:addNewPage,
        getBB:getBB,
        updateAllPage:updateAllPage,
        changeAction:changeAction,
        changeLayout:changeLayout,
        editCover:editCover,
        backCoverMode:backCoverMode,
        addText:addText,
        changeText:changeText,
        updatePrice:updatePrice
    };

})();