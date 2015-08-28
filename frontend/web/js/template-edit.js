
$(function () {


    var unit=$('.btnSetUnit.active').data('value');

    var mode="normal";

    var book_width=700;
    var book_height=250;

    var showGrid=$("#showGridCheckbox:checked").length>0;

    var snapToGrid=$("#snapToGridCheckbox:checked").length>0;


    var publish=$("#publishCheckbox:checked").length>0;

    $('#settingsTabBar a').click(function (e) {
        e.preventDefault()
        $(this).tab('show');
    })



    $('.backgroundInputFile');


    function readBackgroundURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {



                $('#canvas_normal').css('background-image', "url("+ e.target.result+")");

                $('#canvas_normal').css('background-size', "cover");

               // $('.logo_url_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".backgroundInputFile").change(function(){
        readBackgroundURL(this);


    });


    $('.uploadInput1LFile');


    /* Загрузак thumb */


    var startFu2Upload=function(e,data){

        console.log('start thumb upload');
        showLoader('Загрузка...');

    }

    var doneFu2Upload=function (e, data) {

        console.log(data.result);
        if(data && data.hasOwnProperty('result') && data.result.hasOwnProperty('response') && data.result.response.status){


            var type=data.result.response.type;


            if(type=='1_L'){


                if($('.btnUpload1L .button-icon i').hasClass('fa-remove')){

                    $('.btnUpload1L .button-icon i').removeClass('fa-remove');


                    $('.btnUpload1L .button-icon i').addClass('fa-check');



                }

            }else if(type=='1_R'){



                if($('.btnUpload1R .button-icon i').hasClass('fa-remove')){

                    $('.btnUpload1R .button-icon i').removeClass('fa-remove');


                    $('.btnUpload1R .button-icon i').addClass('fa-check');



                }

            }
            //var thumb_image_url=data.result.response.thumb_url;

            /*$('.thumb img', cont).css('background-image',  'url(' + background_image + ')' );*/

           // $('.style-min-thumb').attr('src', thumb_image_url+'?v='+Math.random());


        }else if(data && data.hasOwnProperty('result') && data.result.hasOwnProperty('error')){

            console.error('Upload error');
            alert(data.result.error.msg);
        }else{

            alert('Неизвестная ошибка');
        }
    }

    var progressallFu2Upload=function (e, data) {

        var that= $(e.target);


        console.log(that);



        if(data.total==data.loaded){

            setTimeout(function(){



                hideLoader();
            }, 1000);
        }

    }


    $('.uploadInput1LFile').fileupload({
        dataType: 'json',
        dropZone:null,
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(fu2)$/i,
        maxFileSize: 52428800,
        disableValidation: false,
        start:startFu2Upload,
        done: doneFu2Upload,
        progressall: progressallFu2Upload,
        messages: {
            maxFileSize: 'Максимальный размер файла не должен привешать 50MB',
            acceptFileTypes:'Разрешено загружать только fu2'
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

            alert(data.files[0].error);
            //addError(data.files[0].error);
        });
    });



    $('.uploadInput1RFile').fileupload({
        dataType: 'json',
        dropZone:null,
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(fu2)$/i,
        maxFileSize: 52428800,
        disableValidation: false,
        start:startFu2Upload,
        done: doneFu2Upload,
        progressall: progressallFu2Upload,
        messages: {
            maxFileSize: 'Максимальный размер файла не должен привешать 50MB',
            acceptFileTypes:'Разрешено загружать только fu2'
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

            alert(data.files[0].error);
        });
    });




    $('.btnToggleSettings').bind('click', function(){

        if(!$('.btnToggleSettings').hasClass('open')) {

            $('.sidebar').animate({'right': '0px'});

            $('.btnToggleSettings').addClass('open');

        }else{

            $('.sidebar').animate({'right': '-320px'});
            $('.btnToggleSettings').removeClass('open');
        }


    });


    $('.btnSetUnit').bind('click', function(){

        $('.btnSetUnit.active').removeClass('active');

        $(this).addClass('active');

        numberFormat.unit=$('.btnSetUnit.active').data('value');

        if(canvas && canvas.getActiveObject()){
            initObjectPanelEdit(canvas.getActiveObject());
        }else{
            initObjectPanelCreate();
        }
    })


    $('.btnSetMode').bind('click', function(){

        if($(this).hasClass('active'))
            return;

        $('.btnSetMode.active').removeClass('active');

        $(this).addClass('active');

        mode=$('.btnSetMode.active').data('value');

        if(mode=='normal'){

                $('#canvas_normal').closest('.canvas-container').fadeToggle(500, 'swing', function(){

                    setCanvasFocus(canvas_normal);

                    loadCanvasFromObject(canvas_normal, json_normal, true);

                    initSize();

                    if(canvas && canvas.getActiveObject()){

                        initObjectPanelEdit(canvas.getActiveObject());
                    }else{
                        initObjectPanelCreate();
                    }

                });

        }


    });


    $('#templateName').keyup(function(e){

        console.log(e.currentTarget.value);
        console.log( $('#templateName').val());

        var templateName=$('#templateName').val();


       /* if ($(this).is(':checked')){

            publish=1;
        }else{
            publish=0;
        }*/


        var url = $(this).data('url');
        TemplateApi.customRequest({
            url:url,
            data:{name:templateName},
            success:function(result){

                console.log(result);
                if(result.response.status){
                    $('.spanTemplateName').html(templateName);
                }
            },
            error:function(msg){

                console.log(msg);
            }
        });


    });

    $('#publishCheckbox').change(function(){

        if ($(this).is(':checked')){

            publish=1;
        }else{
            publish=0;
        }


        var url = $(this).data('url');
        TemplateApi.customRequest({
            url:url,
            data:{publish:publish},
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



    $("#snapToGridCheckbox").change(function(){

        if ($(this).is(':checked')){

            snapToGrid=true;
        }else{
            snapToGrid=false;
        }
    });


    $("#showGridCheckbox").change(function(){

        if ($(this).is(':checked')){

            showGrid=true;
        }else{
            showGrid=false;
        }

       // gridObject.visible=showGrid;

        gridObject.set('visible', showGrid);

        canvas_normal.renderAll();


    });




    var numberFormat={

        unit:unit,
        dpi:300,
        fromDisplay:function(number){

            if(numberFormat.unit=='mm'){
                return parseFloat(number).toFixed(3);
            }else if(numberFormat.unit=='px'){

                var number_cm=number/10.0;
                var inch=number_cm/2.54;
                return (inch*numberFormat.dpi).toFixed(0);

            }else{

                return parseFloat(number).toFixed(3);
            }

        },
        toDisplay:function(number){

            if(numberFormat.unit=='mm'){

                return parseFloat(number).toFixed(3);

            }else if(numberFormat.unit=='px'){

                var inch=parseFloat(number)/numberFormat.dpi;
                var number_cm=inch*2.54;
                var mm=number_cm*10;

                return mm;

            }else{

                return parseFloat(number).toFixed(3);
            }
        }
    };

    $('.color-picker').miniColors({
        letterCase: 'upercase',
        change: function(hex, rgb) {

            if(canvas && canvas.getActiveObject() && canvas.getActiveObject()._objects.length>1){
                canvas.getActiveObject()._objects[2].set('stroke', hex);
                canvas.getActiveObject()._objects[2].setCoords();
                canvas.renderAll();
                canvas.calcOffset();

                $(this).select();

                canvas.getActiveObject().set('border_color', hex);

                canvas.fire('object:modified', { target: canvas.getActiveObject() });
            }
        }
    });


    $('#objectStrokeWidth').change(function(){

        var that=$(this);

        if(canvas.getActiveObject() && canvas.getActiveObject()._objects.length>1){

            var oldStrokeWidth=parseFloat(canvas.getActiveObject()._objects[2].get('strokeWidth'));

            var newStrokeWidth=parseFloat(numberFormat.toDisplay($(this).val()));


            canvas.getActiveObject()._objects[2].set('strokeWidth', newStrokeWidth);

            canvas.getActiveObject()._objects[2].setCoords();

            canvas.getActiveObject().setCoords();

            canvas.getActiveObject().set('border_width', newStrokeWidth);


            canvas.renderAll();
            canvas.calcOffset();

            $(this).select();

            canvas.fire('object:modified', { target: canvas.getActiveObject() });


        }
    });


    $('#objectWidth').change(function(){

        if(canvas.getActiveObject()){



            var oldLeft=parseFloat($('#objectX').val());//parseFloat(numberFormat.toDisplay(getPosXByPosition(canvas.getActiveObject().left)));

            var oldW=parseFloat(canvas.getActiveObject().get('width'));

            var newW=parseFloat(numberFormat.toDisplay($(this).val()));

            var scale=newW/oldW;

            canvas.getActiveObject().set('scaleX', scale);

            canvas.getActiveObject().setCoords();

            canvas.fire('object:scaling', { target: canvas.getActiveObject() });

            canvas.renderAll();
            canvas.calcOffset();

            $(this).select();


            var newLeft=parseFloat(numberFormat.toDisplay(getPosXByPosition(oldLeft)));

            canvas.getActiveObject().set('left', newLeft);

            canvas.getActiveObject().setCoords();

            canvas.fire('object:scaling', { target: canvas.getActiveObject() });

            canvas.renderAll();
            canvas.calcOffset();

            canvas.fire('object:modified', { target: canvas.getActiveObject() });


        }
    });


    $('#objectHeight').change(function(){

        if(canvas.getActiveObject()){


            var oldTop=parseFloat($('#objectY').val());

            var oldH=parseFloat(canvas.getActiveObject().get('height'));

            var newH=parseFloat(numberFormat.toDisplay($(this).val()));

            var scale=newH/oldH;



            canvas.getActiveObject().set('scaleY', scale);



            canvas.getActiveObject().setCoords();

            canvas.fire('object:scaling', { target: canvas.getActiveObject() });


            canvas.renderAll();
            canvas.calcOffset();

            $(this).select();


            var newTop=parseFloat(numberFormat.toDisplay(getPosYByPosition(oldTop)));

            canvas.getActiveObject().set('top', newTop);

            canvas.getActiveObject().setCoords();

            canvas.fire('object:scaling', { target: canvas.getActiveObject() });


            canvas.renderAll();
            canvas.calcOffset();

            canvas.fire('object:modified', { target: canvas.getActiveObject() });

        }
    });


    $('#objectX').change(function(){

        if(canvas.getActiveObject()){

            var oldLeft=parseFloat(canvas.getActiveObject().get('left'));

            var newLeft=parseFloat(numberFormat.toDisplay(getPosXByPosition($(this).val())));

            canvas.getActiveObject().set('left', newLeft);

            canvas.getActiveObject().setCoords();

            canvas.renderAll();
            canvas.calcOffset();

            $(this).select();

            canvas.fire('object:modified', { target: canvas.getActiveObject() });
        }
    });


    $('#objectY').change(function(){

        if(canvas.getActiveObject()){

            var oldTop=parseFloat(canvas.getActiveObject().get('top'));

            var newTop=parseFloat(numberFormat.toDisplay(getPosYByPosition($(this).val())));

            canvas.getActiveObject().set('top', newTop);

            canvas.getActiveObject().setCoords();

            canvas.renderAll();
            canvas.calcOffset();

            $(this).select();

            canvas.fire('object:modified', { target: canvas.getActiveObject() });

        }
    });


    $("input[name=position]").change(function(){


        if(canvas && canvas.getActiveObject())
        updatePositionCords(canvas.getActiveObject())

        //canvas.fire('object:modified', { target: canvas.getActiveObject() });
    });

    $("#objectMaybeAsText").change(function(){


        if(canvas && canvas.getActiveObject()){
            if ($(this).is(':checked')){

                canvas.getActiveObject().set('object_maybe_as_text', true);
            }else{
                canvas.getActiveObject().set('object_maybe_as_text', false);
            }

            canvas.fire('object:modified', { target: canvas.getActiveObject() });
        }
    });




    $(".stroke").change(function(){


        /*var border_left=false, border_right=false, border_top=false, border_bottom=false;
        $("input[name=objectStrokePosition]:checked").each( function(index, value){

            console.log(value);
            if($(value).val()=='border_left'){
                border_left=true;
            }else if($(value).val()=='border_right'){

                border_right=true;
            }else if($(value).val()=='border_top'){

                border_top=true;
            }else if($(value).val()=='border_bottom'){

                border_bottom=true;
            }
        });
*/
        if(canvas && canvas.getActiveObject())
        createPlaceholder({select:true});

        canvas.fire('object:modified', { target: canvas.getActiveObject() });


    });


    var initObjectPanelEdit=function(obj){


        console.log('initObjectPanelEdit');

        console.log("obj.width:"+obj.width.toFixed(3));
        console.log("obj.width:"+obj.width);
        $('#objectWidth').val(numberFormat.fromDisplay(obj.width));
        $('#objectHeight').val(numberFormat.fromDisplay(obj.height));



        if(obj._objects.length>1 && obj._objects[2]){
            $('#objectStrokeWidth').val(numberFormat.fromDisplay(obj._objects[2].strokeWidth));

            $('#objectStrokeColor').miniColors('value',obj._objects[2].stroke);
        }
        else{
            $('#objectStrokeWidth').val(numberFormat.fromDisplay('0.00'));
            $('#objectStrokeColor').miniColors('value','#FFFFFF');
        }

        console.log('border_top:'+obj.border_top);

        if(obj.border_top)
        {

            $( "input[value=border_top]" ).prop('checked', true);

            console.log($( "input[value=border_top]" ))
        }else{

            $( "input[value=border_top]" ).prop('checked', false);
        }

        if(obj.border_left){

            $( "input[value='border_left']" ).prop('checked', true);
        }else{
            $( "input[value='border_left']" ).prop('checked', false);
        }

        if(obj.border_right){

            $( "input[value='border_right']" ).prop('checked', true);
        }else{

            $( "input[value='border_right']" ).prop('checked', false);
        }

        if(obj.border_bottom){

            $( "input[value='border_bottom']" ).prop('checked', true);
        }else{

            $( "input[value='border_bottom']" ).prop('checked', false);
        }


        if(obj.object_maybe_as_text){


            $( "#objectMaybeAsText" ).prop('checked', true);

        }else{

            $( "#objectMaybeAsText" ).prop('checked', false);

        }


        console.log('connected:'+obj.connected);




        updatePositionCords(obj);



        /*$('#objectX').val(numberFormat.fromDisplay(obj.left));*/
        //$('#objectY').val(numberFormat.fromDisplay(obj.top));



        $('.btnAdd').css('display', 'none');

        $('.btnDelete').css('display', 'block');

        $( "#objectConnected").closest('.form-group').css('display', 'block');

        if(canvas.getActiveObject()){
            if(mode=='text'){

                if(!canvas.getActiveObject().object_text)
                    $('.btnDelete').attr('disabled', true);
                else
                    $('.btnDelete').removeAttr('disabled');
            }else{


                if(canvas.getActiveObject().object_text)
                    $('.btnDelete').attr('disabled', true);
                else
                    $('.btnDelete').removeAttr('disabled');
            }
        }

    }


    var getPositionOriginX=function(){

        var position=$("input[name=position]:checked").val();

        var pos=position.split("_");

        var originX='left';



        if(pos && pos.length>1){

            originX=pos[0];

        }

        return originX;
    }

    var getPositionOriginY=function(){

        var position=$("input[name=position]:checked").val();

        var pos=position.split("_");

        var originY='top';



        if(pos && pos.length>1){

            originY=pos[1];

        }


        return originY;
    }


    var getPosXByPosition=function(value){

        value=parseFloat(value);


        var originX=getPositionOriginX();


        var half_width=parseFloat(numberFormat.fromDisplay(canvas.getActiveObject().width/2));


        if(originX=='left'){

            return value+half_width;

        }else   if(originX=='right'){

            return value-half_width;
        }else if(originX=='center'){

            return value;
        }



    }


    var getPosXByPositionMove=function(value, originX){

        value=parseFloat(value);

        if(!originX){
            originX=getPositionOriginX();
        }


        var half_width=parseFloat(canvas.getActiveObject().width/2);


        if(originX=='left' || originX=='l'){



            return value-half_width;

        }else   if(originX=='right'  || originX=='r'){


            return value+half_width;
        }else if(originX=='center' || originX=='c'){



            return value;
        }



    }



    var getPosYByPosition=function(value){

        value=parseFloat(value);


        var originY=getPositionOriginY();



        var half_height=parseFloat(numberFormat.fromDisplay(canvas.getActiveObject().height/2));


        if(originY=='top'){

            return value+half_height;

        }else   if(originY=='bottom'){

            return value-half_height;
        }else if(originY=='center'){

            return value;
        }




    }


    var getPosYByPositionMove=function(value, originY){

        value=parseFloat(value);


        if(!originY){
            originY=getPositionOriginY();

        }




        var half_height=parseFloat(canvas.getActiveObject().height/2);


        if(originY=='top' || originY=='t'){

            return value-half_height;

        }else   if(originY=='bottom' || originY=='b'){

            return value+half_height;
        }else if(originY=='center' || originY=='m'){

            return value;
        }




    }


    var updatePositionCords=function(obj){

        var position=$("input[name=position]:checked").val();

        var pos=position.split("_");

        var originX='left';
        var originY='top';


        if(pos && pos.length>1){

            originX=pos[0];
            originY=pos[1];
        }


        var half_width=obj.width/2;
        var half_height=obj.height/2;

        console.log('originX:'+originX+" half_width:"+half_width);


        if(originX=='left'){

            $('#objectX').val(numberFormat.fromDisplay(obj.left-half_width));
        }else   if(originX=='right'){

            $('#objectX').val(numberFormat.fromDisplay(obj.left+half_width));
        }else if(originX=='center'){

            $('#objectX').val(numberFormat.fromDisplay(obj.left));
        }

        if(originY=='top'){

            $('#objectY').val(numberFormat.fromDisplay(obj.top-half_height));
        }else   if(originY=='bottom'){

            $('#objectY').val(numberFormat.fromDisplay(obj.top+half_height));
        }else if(originY=='center'){

            $('#objectY').val(numberFormat.fromDisplay(obj.top));
        }


        console.log(position);
    }



    var initObjectPanelCreate=function(){

        console.log('initObjectPanelCreate mode:'+mode);

        $('#objectWidth').val(numberFormat.fromDisplay('170.00'));
        $('#objectHeight').val(numberFormat.fromDisplay('170.00'));

        $('#objectX').val('0.00');
        $('#objectY').val('0.00');

        $('#objectStrokeWidth').val(numberFormat.fromDisplay('3.00'));

       /* $('#objectStrokeColor').val('#FFFFFF');*/


        $('#objectStrokeColor').miniColors('value','#FFFFFF');


        $("input[name=position][value=" + 'left_top' + "]").attr('checked', 'checked');


        //$( "#objectPassepartout" ).removeAttr('checked');



        $('.btnAdd').css('display', 'block');
        $('.btnDelete').css('display', 'none');

        if(mode=='normal'){


            $( "#objectConnected" ).prop('checked', true);
            $( "#objectConnected").closest('.form-group').css('display', 'block');
            $('.btnAdd').html('Добавить объект');
            $('.btnAdd').removeAttr('disabled');
            $('.btnAdd').removeProp('disabled');

        }else if(mode=='text'){


            $( "#objectConnected" ).prop('checked', false);
            $( "#objectConnected").closest('.form-group').css('display', 'none');

            var key=findKeyByNum(canvas_text._objects, 'T');



            $('.btnAdd').html('Добавить текст');

            if(key>=0) {
                $('.btnAdd').attr('disabled', true);
                $('.btnAdd').prop('disabled', true);
            }
            else {
                $('.btnAdd').removeAttr('disabled');
                $('.btnAdd').removeProp('disabled');
            }
        }





    }

    var existNum=function(num){

        var objects=canvas.toObject().objects;



        for(index in objects){
            var obj=objects[index];

            if(obj && obj.type=='placeholder' && obj.data_value==num){
                return true;
            }

        }

        return false;
    }

    var getNumForNewObject= function(){

       var objects=canvas.toObject().objects;

       var num=1;

       while(existNum(num)){
           num++;
       }

       return num;
    }




    $('input.stroke').bind('change', function () {

        if ($(this).is(':checked'))
            $(this).attr('checked', 'checked')
        else
            $(this).removeAttr('checked');

    });

    var createPlaceholder=function(data){


        var border_left=false, border_right=false, border_top=false, border_bottom=false;
        $("input[name=objectStrokePosition]:checked").each( function(index, value){

            console.log(value);
            if($(value).val()=='border_left'){
                border_left=true;
            }else if($(value).val()=='border_right'){

                border_right=true;
            }else if($(value).val()=='border_top'){

                border_top=true;
            }else if($(value).val()=='border_bottom'){

                border_bottom=true;
            }
        });


        var width=parseFloat(numberFormat.toDisplay($('#objectWidth').val()));
        var height=parseFloat(numberFormat.toDisplay($('#objectHeight').val()));

        var x=parseFloat(numberFormat.toDisplay($('#objectX').val()));
        var y=parseFloat(numberFormat.toDisplay($('#objectY').val()));

        var strokeWidth=parseFloat(numberFormat.toDisplay($('#objectStrokeWidth').val()));

        var strokeColor=$('#objectStrokeColor').val();


        var strokeColor=$('#objectStrokeColor').val();


        var objectMaybeAsText=$("#objectMaybeAsText:checked").length>0;


        //var connected=$("#objectConnected:checked").length>0;


        //var objectText=false;//$("#objectText:checked").length>0;




        var position=$("input[name=position]:checked").val();

        var pos=position.split("_");

        var originX='center';
        var originY='center';

        console.log(pos);

        if(pos.length>1){


            if( pos[0]=='left')
            {
                x=x+(width/2);
                console.log(x);
            }

            if( pos[0]=='right')
            {
                x-=width/2;
            }


            if( pos[1]=='top')
            {
                y=y+(height/2);
            }

            if( pos[1]=='bottom')
            {
                y-=height/2;
            }

            /*  pos[0];
             pos[1];*/

        }


        var num=1;
        if(data && data.select){

            num=canvas.getActiveObject().data_value;
        }else{

            num=getNumForNewObject();
        }



        var obj=addPlaceholder(x,y, width, height, strokeWidth, strokeColor, border_left, border_top, border_right, border_bottom, {

            object_maybe_as_text:objectMaybeAsText,
            originX:originX,
            originY:originY,
            num:num

        })







        if(data && data.select){

           canvas.remove(canvas.getActiveObject());
        }




        canvas.setActiveObject(obj);


    }

    var isExistsTextObject=function(){

        var canvas_active_data=canvas_normal.toObject();

        var objects=canvas_active_data.objects;

        var object, object_index;

        var count=0;


        for (object_index in objects){

            //console.log('object_index', object_index);
            object=objects[object_index];

            if(object.hasOwnProperty('type') && object.type=='placeholder'){

                if(object.object_maybe_as_text){
                    count++;
                }

            }
        }

        return (count>0);


    }


    var getCountPlaceholder=function(){

        var canvas_active_data=canvas_normal.toObject();

        //console.log('canvas_active_data', canvas_active_data);
        var objects=canvas_active_data.objects;

        var object, object_index;

        var count=0;


        for (object_index in objects){

            //console.log('object_index', object_index);
            object=objects[object_index];

            if(object.hasOwnProperty('type') && object.type=='placeholder'){

                count++;
            }
        }

        return count;
    }

    $('.btnDelete').bind('click', function(){

        //canvas.remove(canvas.getActiveObject());

        var sel_object=canvas.getActiveObject();


        if(mode=='normal'){

            var data_name=canvas.getActiveObject().data_name;



            //sel_object.sel
            canvas.remove(sel_object);

            var max_num=0;

            for(var i in canvas._objects){
                var num=parseInt(canvas._objects[i].data_name);
                if(num>max_num) max_num=num;
            }

            var num_start=parseInt(data_name)+1;

            for(i=num_start; i<=max_num; i++){

                var key_in_normal=findKeyByNum(canvas._objects, i+'');
                var new_num=(i-1)+'';

                canvas._objects[key_in_normal].data_name=new_num;
                canvas._objects[key_in_normal].data_value=new_num;
                canvas._objects[key_in_normal]._objects[1].text=new_num;
            }


        }


        canvas.deactivateAll();
        //canvas.fire('selection:cleared', { target:sel_object });
        canvas.fire('object:modified', { target:null });



        canvas.renderAll();

        //canvas_text.renderAll();
    });

    $('.btnAdd').bind('click', function(){



        createPlaceholder({select:false})


        canvas.fire('object:modified', { target:null });

    } )


    initObjectPanelCreate();



    fabric.Placeholder = fabric.util.createClass(fabric.Group, {

        type: 'placeholder',

        initialize: function(element, options) {
            this.callSuper('initialize', element, options);
            options && this.set('data_name', options.data_name) && this.set('data_value', options.data_value) &&
            this.set('object_maybe_as_text', options.object_maybe_as_text) && this.set('border_left', options.border_left) &&
            this.set('border_top', options.border_top) && this.set('border_right', options.border_right) &&
            this.set('border_bottom', options.border_bottom) && this.set('border_color', options.border_color) &&
            this.set('border_width', options.border_width);
        },
        toObject: function() {
            return fabric.util.object.extend(this.callSuper('toObject'), {
                data_name: this.data_name,
                data_value: this.data_value,
                object_maybe_as_text:this.object_maybe_as_text,
                object_text:this.object_text,
                border_left:this.border_left,
                border_top:this.border_top,
                border_right:this.border_right,
                border_bottom:this.border_bottom,
                border_color:this.border_color,
                border_width:this.border_width

            });
        },
        toSVG: function(reviver) {
            var markup = [
                '<g ',
                'transform="', this.getSvgTransform(),
                '" data_name="', this.data_name,
                '" data_value="', this.data_value,
                '" object_maybe_as_text="', this.object_maybe_as_text,
                '" object_text="', this.object_text,
                '" border_left="', this.border_left,
                '" border_top="', this.border_top,
                '" border_right="', this.border_right,
                '" border_bottom="', this.border_bottom,
                '" border_color="', this.border_color,
                '" border_width="', this.border_width,
                '" connected="', this.connected,
                '">'
            ];

            for (var i = 0, len = this._objects.length; i < len; i++) {
                markup.push(this._objects[i].toSVG(reviver));
            }

            markup.push('</g>');

            return reviver ? reviver(markup.join('')) : markup.join('');
        }
    });


    fabric.Placeholder.fromObject = function(object, callback) {
        console.log(object);
        fabric.util.enlivenObjects(object.objects, function(enlivenedObjects) {
            delete object.objects;
            callback && callback(new fabric.Placeholder(enlivenedObjects, object));
        });
    };

    fabric.Placeholder.async=true;





    var addPlaceholder = function(x,y, width, height, border_width, border_color, border_left, border_top, border_right, border_bootom, options ) {


        console.log(options);
        /* 0000
           0001
           0010
           0011
           0100
           0101
           0110
           0111
           1000
           1001
           1010
           1011
           1100
           1101
           1110
           1111
         */

        var pathOffsetX=0;
        var pathOffsetY=0;


        var strokeOpacity=1;
        var strokeStyle='';
        var visible=true;

        var stroke=border_color;

        console.log(border_left+" "+border_top+" "+border_right+" "+border_bootom)

        var path='';
        if(!border_left && !border_top && !border_right && border_bootom){          //0001

            path="M 0 "+height+"  L "+width+" "+height;

            pathOffsetY=height/2

        }else if(!border_left && !border_top && border_right && !border_bootom){    //0010

            path="M "+width+" 0  L "+width+" "+height;
            pathOffsetX=width/2
            //pathOffsetY=height/2

        }else if(!border_left && !border_top && border_right && border_bootom){    //0011

            path="M "+width+" 0  L "+width+" "+height+" L 0 "+height;

        }else if(!border_left && border_top && !border_right && !border_bootom){    //0100

            path="M 0 0  L "+width+" 0";
            pathOffsetY=height/2

        }else if(!border_left && border_top && !border_right && border_bootom){    //0101

            path="M 0 0  L "+width+" 0 M "+width+" "+height+" L 0 "+height;

        }else if(!border_left && border_top && border_right && !border_bootom){    //0110

            path="M 0 0  L "+width+" 0 L "+width+" "+height;

        }else if(!border_left && border_top && border_right && border_bootom){    //0111

            path="M 0 0  L "+width+" 0 L "+width+" "+height+" L 0 "+height;

        }else if(border_left && !border_top && !border_right && !border_bootom){    //1000

            path="M 0 "+height+"  L 0 0 ";

            pathOffsetX=width/2

        }else if(border_left && !border_top && !border_right && border_bootom){    //1001

            path="M 0 "+height+"  L 0 0 M "+width+" "+height+" L 0 "+height;

        }else if(border_left && !border_top && border_right && !border_bootom){     //1010

            path="M 0 "+height+"  L 0 0 M "+width+" 0 L "+width+" "+height;

        }else if(border_left && !border_top && border_right && border_bootom){      //1011

            path="M 0 "+height+"  L 0 0 M "+width+" 0 L "+width+" "+height+" L 0 "+height;

        }else if(border_left && border_top && !border_right && !border_bootom){     //1100

            path="M 0 "+height+"  L 0 0 L "+width+ " 0";

        }else if(border_left && border_top && !border_right && border_bootom){     //1101

            path="M 0 "+height+"  L 0 0 L "+width+" 0 M "+width+" "+height+" L 0 "+height;

        }else if(border_left && border_top && border_right && !border_bootom){     //1110

            path="M 0 "+height+"  L 0 0 L "+width+ " 0 L "+width+" "+height;

        }else if(border_left && border_top && border_right && border_bootom){    //1111


            path="M 0 "+height+"  L 0 0 L "+width+ " 0 L "+width+" "+height+" L 0 "+height+" z";
        }else if(!border_left && !border_top && !border_right && !border_bootom){

            path="M 0 "+height+"  L 0 0 L "+width+ " 0 L "+width+" "+height+" L 0 "+height+" z";


            visible=false;
        }

        g=[];

        if(path){
            var path=new fabric.Path(path);

            path.set({fill:'transparent',   stroke:stroke,  visible:visible, strokeWidth:border_width, left:0, top:0,  originX:'center',
                originY:'center', hasBorders:true, centeredScaling:false, borderScaleFactor:1, strokeLinecap:'butt', pathOffset:{x:pathOffsetX, y:pathOffsetY}});



        }


        var rect=new fabric.Rect({
            left: 0,
            top: 0,
            fill: '#ccc',
            width: (width),
            height: (height),
            opacity: 1,
            originX:'center',
            originY:'center'
           /* selectable:true*/

        });



        g[0]=rect;


        var text_str=options.num.toString();

       /* if(options.object_text){

            text_str=options.num.toString();
        }*/

        var text = new fabric.Text(text_str, {
            left: 0,
            top: 0,
            fill: '#bbb',
            width: (width),
            height: (height),
            fontFamily:"Arial",
            originX:'center',
            originY:'center'

        });

        g[1]=text;

        if(path){

            g[2]=path;
        }



        //canvas.add(text);






        var group1 = new fabric.Placeholder(g, { left: x, top: y,  width: (width), height: (height),    originX:'center',
            originY:'center',  data_name:options.num, data_value:options.num, object_maybe_as_text:options.object_maybe_as_text,
            border_left:border_left, border_right:border_right, border_top:border_top, border_bottom:border_bootom, border_color:border_color, border_width:border_width });
        group1.set({ hasBorders:true, centeredScaling:false, borderScaleFactor:1, selectable:true});




        canvas.add(group1);

        console.log(group1);

        return group1;
    };





    var recurciveScale=function(scaleX,scaleY, element){

        var oldW = element.get('width');
        var oldH = element.get('height');

        var newW=scaleX*oldW;
        var newH=scaleY*oldH;
        element.set('width',scaleX*oldW);
        element.set('height',scaleY*oldH);
        element.set('scaleX',1);
        element.set('scaleY',1);


        if(element._objects){

            for(var index in element._objects){

                var el=element._objects[index];
                if(el.type=='path'){

                    for(var index2 in el.path){

                        if(el.path[index2][0]=='M' || el.path[index2][0]=='L'){


                            el.path[index2][1]=el.path[index2][1]*scaleX;
                            el.path[index2][2]=el.path[index2][2]*scaleY;
                        }else if(el.path[index2][0]=='H'){


                            el.path[index2][1]=el.path[index2][1]*scaleX;

                        }else if(el.path[index2][0]=='V'){


                            el.path[index2][1]=el.path[index2][1]*scaleY;
                        }

                    }


                    var oldLeft = el.get('left');
                    var oldTop = el.get('top');




                    el.set('width',scaleX*oldW);
                    el.set('height',scaleY*oldH);

                    el.set('scaleX',1);
                    el.set('scaleY',1);


                    var pathOffset=el.pathOffset;



                     if(el.pathOffset.x!=0)
                         el.pathOffset.x=((1-scaleX)*el.pathOffset.x);

                     if(el.pathOffset.y!=0)
                    el.pathOffset.y=((1-scaleY)* el.pathOffset.y)





                }else{

                    var oldLeft = el.get('left');
                    var oldTop = el.get('top');


                    el.set('left',oldLeft*scaleX);
                    el.set('top',oldTop*scaleY);

                    el.set('width',scaleX*oldW);
                    el.set('height',scaleY*oldH);
                    el.set('scaleX',1);
                    el.set('scaleY',1);
                }
            }

        }
    }



    var scale=1;

    var canvas=null;

    var canvas_normal=null;
    //var canvas_text=null;
    var grid = 5;

    var initCanvasNormal=function(){

        var width =$('#canvas-layout').width();
        var height =$('#canvas-layout').height();

        $('#canvas_normal').css('width',width+'px');
        $('#canvas_normal').css('height',(width/2.8)+'px');

        document.getElementById('canvas_normal').width = width;
        document.getElementById('canvas_normal').height = width/2.8;

        scale=(width/700);



        if(!canvas_normal){

            canvas_normal = new fabric.CanvasWithViewport('canvas_normal');
            canvas_normal.setZoom(canvas_normal.viewport.zoom*scale);

            canvas_normal.viewport.position.x=0;
            canvas_normal.viewport.position.y= 0;
            canvas_normal.viewport.translate();

            canvas_normal.selection = true;
        }
    }


    var initCanvasText=function(){

       // var width =$('#canvas-layout').width();
       // var height =$('#canvas-layout').height();

       // $('#canvas_text').css('width',width+'px');
       // $('#canvas_text').css('height',(width/2)+'px');

       // document.getElementById('canvas_text').width = width;
       // document.getElementById('canvas_text').height = width/2;

       // scale=(width/700)-0.01;

       // if(!canvas_text){

       //     canvas_text = new fabric.CanvasWithViewport('canvas_text');
       //     canvas_text.setZoom(canvas_text.viewport.zoom*scale);

       //     canvas_text.viewport.position.x=0;
       //     canvas_text.viewport.position.y= 0;
       //     canvas_text.viewport.translate();

       //     canvas_text.selection = true;
       //}
    }

    var setCanvasFocus=function(sel_canvas){

        canvas=sel_canvas;

    }



    var initSize=function(){



        var book_aspect=book_width/book_height;

       // $('#canvas-layout').css('height',+'px');

        var layout_width=$('#canvas-layout').width()-30;
        var layout_height= ($(window).height()-$('#navbar').height()-$('.photobook-template-edit').height()-$('.template-object-edit-panel').height()-35);//$('#canvas-layout').height()-15;

        console.log("layout_width:", layout_width);
        console.log("layout_height:", layout_height);

        var layout_aspect=layout_width/layout_height;

        console.log("layout_aspect:", layout_aspect);

        console.log("book_aspect:", book_aspect);

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


        new_width=parseInt(new_width+'');

        if(new_width/2==parseInt((new_width/2)+'')){

            new_width++;
        }


        console.log("new_width:", new_width);
        console.log("new_height:", new_height);


        var image_posX=((layout_width-new_width ) / 2)+15;

        var image_posY=((layout_height-new_height  ) / 2)+5;


       // $('#canvas_normal').css({'top': image_posY+"px", 'left':image_posX+"px", width: new_width+'px', height:new_height+'px'});
       // $('.upper-canvas').css({'top': image_posY+"px", 'left':(image_posX)+"px", width: new_width+'px', height:new_height+'px'});

        $('.canvas-container').css({'top': image_posY+"px", 'left':(image_posX)+"px", width: new_width+'px', height:new_height+'px'});




        $('#canvas_normal').css('width',new_width+'px');
        $('#canvas_normal').css('height',new_height+'px');

        $('.upper-canvas').css('width',new_width+'px');
        $('.upper-canvas').css('height',new_height+'px');


        document.getElementsByClassName('upper-canvas')[0].width = new_width;
        document.getElementsByClassName('upper-canvas')[0].height = new_height;

        document.getElementById('canvas_normal').width = new_width;
        document.getElementById('canvas_normal').height = new_height;

        scale=(new_width/book_width);

        canvas_normal.setZoom(scale);

        canvas_normal.viewport.position.x=0;
        canvas_normal.viewport.position.y= 0;
        canvas_normal.viewport.translate();


        canvas_normal.calcOffset();

        canvas_normal.renderAll();


        canvas_normal.selection = false;

        canvas_normal.selection = true;


        //setCanvasFocus(canvas_normal);


    }

    initCanvasNormal();
   // initCanvasText();



    initSize();

   // $('#canvas_text').closest('.canvas-container').css('display', 'none');


    var svg_normal='';
    var svg_text='';

    var json_normal=$('#canvas_normal').data('json');
    //var json_text=$('#canvas_text').data('jsontext');
    var gridObject;

    var renderGrid=function(canv){

        canv.clear().renderAll();

        gridObject=new fabric.Group();



        gridObject.add(new fabric.Line([ 0, 50, 700, 50], { strokeWidth:0.1, stroke: '#ccc', selectable: false, originX:'center',
            originY:'center', serialize:false  }));


        //     create grid


        for (var i = 0; i <= (700 / grid); i++) {
            gridObject.add(new fabric.Line([ i * grid, 0, i * grid, 250], { strokeWidth:0.1, stroke: '#ccc', selectable: false , originX:'center',
                originY:'center', serialize:false  }));

        }

        for (var i = 0; i <= (250 / grid); i++) {
            gridObject.add(new fabric.Line([ 0, i * grid, 700, i * grid], { strokeWidth:0.1, stroke: '#ccc', selectable: false, originX:'center',
                originY:'center', serialize:false   }))

        }



        canv.add(gridObject);

        canv.add(new fabric.Rect( {left:350, top:125, width:4, height:250,  fill: 'black', selectable: false , originX:'center',
            originY:'center', serialize:false }));



        /*canv.add(new fabric.Line([350, 0, 350, 250], { stroke: '#0000ff', selectable: false , originX:'center',
            originY:'center', serialize:false }));*/

    }

    var loadCanvasFromObject=function(canv, object, clear_all){

        var activeCanvas=canvas;

        setCanvasFocus(canv);

        if(clear_all)
            renderGrid(canv);
;
        for(var index in object['objects']){


            var obj=object['objects'][index];


            if(obj && obj.type=='placeholder'){


                if(obj.left+(obj.width/2)<=0)
                {
                    obj.left=0;

                }


                if(obj.left-(obj.width/2)>=700)
                {
                    obj.left=700-(obj.width/2);

                }


                if(obj.top-(obj.height/2)>=250)
                {
                    obj.top=250-(obj.height/2);

                }


                //var addPlaceholder = function(x,y, width, height, border_width, border_color, border_left, border_top, border_right, border_bootom, options ) {
                var x=obj.left, y=obj.top, width=obj.width, height=obj.height, border_width=obj.border_width, border_color=obj.border_color,
                    border_left=obj.border_left, border_top=obj.border_top, border_right=obj.border_right, border_bottom=obj.border_bottom;

                if(y==null) y=0;

                if(obj.object_text){
                    obj.data_value='T';
                }



                //data_name:options.num, data_value:options.num, passepartout:options.passepartout, object_text:options.object_text
                var options={
                    num:obj.data_value,
                    object_maybe_as_text:obj.object_maybe_as_text

                };

                addPlaceholder(x,y,width, height, border_width, border_color, border_left, border_top, border_right, border_bottom, options);

                canvas.renderAll();
            }

        }

        setCanvasFocus(activeCanvas);
    }

    renderGrid(canvas_normal);
    //renderGrid(canvas_text);


    var initCanvEvent=function(canv){


        canv.on('object:scaling',function(options){



            var oldX = options.target.get('scaleX');
            var oldY = options.target.get('scaleY');




            if(snapToGrid) {

                console.log(options);

                var element=options.target;

                console.log("corner:",element.__corner);

                var oldW = element.get('width');
                var oldH = element.get('height');

                var newW=oldX*oldW;
                var newH=oldY*oldH;

                console.log('newW:', newW, 'newH:', newH);



/*
                var top = options.target.top;



                var widthGrid = Math.round(newW / grid) * grid;

                var originy = getPositionOriginY();

                if (originy == 'top') {

                    var dy = newTop - top_o;
                    newTop = top + dy;

                } else if (originy == 'bottom') {

                    var dy = newTop - top_o;
                    newTop = top + dy;
                }


                var left = options.target.left;

                var left_o = parseFloat((getPosXByPositionMove(left)));
                var newLeft = Math.round(left_o / grid) * grid;


                var originx = getPositionOriginX();

                if (originx == 'left') {

                    var dx = newLeft - left_o;
                    newLeft = left + dx;

                } else if (originx == 'right') {


                    var dx = newLeft - left_o;
                    newLeft = left + dx;
                }
*/

              /*  options.target.set({
                    left: newLeft,
                    top: newTop
                });*/


            }


            recurciveScale(oldX, oldY,options.target );




        });



        canv.on('object:moving', function(options) {


            if(snapToGrid) {
                var top = options.target.top;

                var top_o = parseFloat((getPosYByPositionMove(top)));

                var newTop = Math.round(top_o / grid) * grid;

                var originy = getPositionOriginY();

                if (originy == 'top') {

                    var dy = newTop - top_o;
                    newTop = top + dy;

                } else if (originy == 'bottom') {

                    var dy = newTop - top_o;
                    newTop = top + dy;
                }


                var left = options.target.left;

                var left_o = parseFloat((getPosXByPositionMove(left)));
                var newLeft = Math.round(left_o / grid) * grid;


                var originx = getPositionOriginX();

                if (originx == 'left') {

                    var dx = newLeft - left_o;
                    newLeft = left + dx;

                } else if (originx == 'right') {


                    var dx = newLeft - left_o;
                    newLeft = left + dx;
                }


                options.target.set({
                    left: newLeft,
                    top: newTop
                });
            }
        });

        canv.on('object:selected', function(e){

            console.log(this.lowerCanvasEl.id);
            if(this.lowerCanvasEl.id=='canvas_'+mode){
                console.log('object:selected'); console.log(e); initObjectPanelEdit(e.target);

            }

        });
        canv.on('selection:cleared', function(e){

            if(this.lowerCanvasEl.id=='canvas_'+mode){
                console.log('object:selected:cleared');
                console.log(e);

                initObjectPanelCreate();
            }

        });


        canv.on('object:modified', function(e){
            console.log('object:modified');
            console.log($(this));
            console.log(e.target);
            if(e.target)
                initObjectPanelEdit(e.target);


            var canvas_active_data=clone(canvas.toObject(), 0);

            var svg_active=canvas.toSVG({viewBox:{x:0, y:0, width:700, height:250}}, function(svg) {

                if(svg.indexOf("<line")>=0) svg='';
                return svg;
            });


            canvas_active_data['objects']=normalizeObj(canvas_active_data['objects']);


            if(mode=='normal'){

                json_normal=canvas_active_data;
                svg_normal=svg_active;

                //console.log('json_text:'+json_text)

                /*if(json_text==''){
                    json_text=clone(json_normal);

                    json_text['objects']=cloneObjectsByConnected(json_normal['objects'], json_text['objects']);


                }else{

                    json_text=clone(canvas_text.toObject(), 0);

                    json_text['objects']=normalizeObj(json_text['objects']);

                    json_text['objects']=cloneObjectsByConnected(json_normal['objects'], json_text['objects']);
                }*/


                //loadCanvasFromObject(canvas_text, json_text, true);


                //svg_text=canvas_text.toSVG({viewBox:{x:0, y:0, width:700, height:350}}, function(svg) {

                //    if(svg.indexOf("<line")>=0) svg='';
                //   return svg;
                //});
            }



            var json_n=JSON.stringify(json_normal);

           // var json_t=JSON.stringify(json_text);

            $('.spanTemplateLastEdit').html('Загрузка...');

            var url = $('canvas').data('url');
            TemplateApi.customRequest({
                url:url,
                data:{json:json_n, svg:svg_normal},
                success:function(result){


                    if(result.response.status){

                        var countPlaceholder=getCountPlaceholder();
                        var changed_datetime=result.response.changed_datetime;
                        var updated_ago=result.response.updated_ago;
                        $('.spanCountPlaceholder').html(countPlaceholder);


                        var isExistsTextObj=isExistsTextObject();
                        if(isExistsTextObj){

                            $('.spanExistsText').html( $('.spanExistsText').data('yes'));
                        }else{

                            $('.spanExistsText').html( $('.spanExistsText').data('no'));
                        }

                        $('.spanUpdatedAt').html(changed_datetime);


                        $('.spanTemplateLastEdit').html(updated_ago);

                    }
                },
                error:function(msg){
                    /* bootbox.alert(msg);*/
                    console.log(msg);
                }
            });

        });

    }
    if(json_normal!=''){


        setTimeout( function(){


            var mas=[{json:json_normal, canvas:canvas_normal}];

            for(var i in mas){

                console.log("json"+mas[i].json);
                console.log(mas[i].json);


                var canv=mas[i].canvas;

                if(mas[i].json!=''){

                    var result=mas[i].json;
                    loadCanvasFromObject(canv, result, false);
                }

                initCanvEvent(canv);
            }


            setCanvasFocus(canvas_normal);


            //canvas.fire('object:modified', {target:null});

        },1000)

    }else{


        initCanvEvent(canvas_normal);
        // initCanvEvent(canvas_text);

        setCanvasFocus(canvas_normal);
    }




    var findKeyByNum=function(objects, find_num){

        //console.log('findKeyByNum:'+find_num);

        for(var key in objects){

           //console.log(objects[key]);
            if(objects[key].data_name==find_num){

                return key;
            }
        }

        return -1;
    }

    var cloneObjectsByConnected=function(src, dest){

        for(var key in src){

            if(src[key].connected){

                var dest_key=findKeyByNum(dest, src[key].data_name);

                if(dest_key>=0){

                    dest[dest_key]=clone(src[key]);
                }else{

                    dest[dest.length]=clone(src[key]);
                }


            }
        }

        return dest;

    }

    var clone=function (obj,level) {
        if(obj == null || typeof(obj) != 'object')
            return obj;

        var temp = obj.constructor(); // changed

        for(var key in obj) {


            if(key=='type' && obj[key]!='placeholder' && level==2){

                return;
            }
            if(obj.hasOwnProperty(key)) {

                var tmp=clone(obj[key],level+1);
               // if(key='objects' tmp)
                temp[key] = tmp;
            }
        }
        return temp;
    }

    var normalizeObj=function(obj){

        var newobj=[];
        var i=0;
        for(var key in obj) {

        //    console.log(key+':'+obj[key]);
            if(obj[key]!=undefined){
                newobj[i]=obj[key]
                i++;
            }

        }

        //console.log(newobj);
        return newobj;
    }


    $( window ).resize(function() {
        console.log('resizeing');
        initSize();
    });


});