(function( $ ){

    var methods = {
        options:{


        },
        init : function( options ) {


            methods.options = $.extend( {

            }, options);

            return this.each(function(){

                var $this = $(this),
                    data = $this.data('editable');

                console.log($this.text());

                var text=$this.text();

                // Если плагин ещё не проинициализирован
                if ( ! data ) {

                    var html='<span class="name">'+$this.text().trim()+'</span> <a class="btn btn-link btnEdit" >Переименовать</a>';

                    $this.html(html);

                    $('.btnEdit', $this).bind('click.editable', methods.clickEdit);
                    /*
                     * Тут выполняем инициализацию
                     */

                    $(this).data('editable', {
                        target : $this,
                        text:text
                    });

                }
            });
        },
        onFocus:function(){

            var $this=$(this).parent();
            $('.editable-input', $this).select();


        },
        pressEnter: function(e){

           /* var code = e.keyCode || e.which;
            if(code == 13) { //Enter keycode
                methods.clickOk();
            }*/
        },
        clickEdit: function(){

            var $this=$(this).parent();

            var text=$('.name', $this).text();

            var html2='<input class="editable-input" type="text" data-value="'+text+'" value="'+text+'" maxlength="20" />'+
                '<a class="btn btn-tools button-1-line  btn-xs btnOk" ><i style="font-size: 12px;" class="glyphicons  ok"></i></a> '+
                '<a class="btn btn-tools button-1-line  btn-xs btnCancel" ><i style="font-size: 12px;" class="glyphicons remove"></i></a>';

            $this.html(html2);

            $('.btnOk', $this).bind('click', methods.clickOk);
            $('.btnCancel', $this).bind('click', methods.clickCancel);

            $('.editable-input', $this).keypress(methods.pressEnter);

            $('.editable-input', $this).focus();

            $('.editable-input', $this).focus(methods.onFocus);

            $('.editable-input', $this).select();



        },
        clickOk: function (){

            console.log($(this))
            var $this=$(this).parent();

            var oldtext=$('.editable-input',$this).data('value');
            var text=$('.editable-input',$this).val();

            var url=$this.data('url');



            url=url.replace(/oldgroupname/g, oldtext).
                    replace(/newgroupname/g, text).
                    replace();

            console.log('url'+url);

            var html='<span class="name">'+$('.editable-input',$this).val()+'</span> <a class="btn btn-link btnEdit" >Переименовать</a>';

            $this.html(html);

            $('.btnEdit', $this).bind('click.editable', methods.clickEdit);

            if(methods.options.onOk){

                console.log("$this.closest('.photo-group')");
                console.log($this.closest('.photo-group'));
                methods.options.onOk(text, $('.editable-input',$this).data('value'), url, $this.closest('.photo-group'));
            }
        },
        clickCancel: function (){

            var $this=$(this).parent();


            var text=$('.editable-input',$this).data('value');
            var html='<span class="name">'+text+'</span> <a class="btn btn-link btnEdit" >Переименовать</a>';

            $this.html(html);

            $('.btnEdit', $this).bind('click.editable', methods.clickEdit);

            if(methods.options.onCancel){

                methods.options.onCancel(text, $this);
            }
        },
        destroy : function( ) {

            return this.each(function(){

                var $this = $(this),
                    data = $this.data('editable');

                // пространства имён рулят!!11
                $(window).unbind('.editable');


                $this.text( $(this).data('editable').text);

                /*data.editable.remove();*/

                $this.removeData('editable');

            })

        },
        reposition : function( ) {
            // ...
        },
        show : function( ) {
            // ...
            //
        },
        hide : function( ) {


        },
        update : function( content ) {

        }
    };

    $.fn.editable = function( method ) {

        if ( methods[method] ) {
            return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Метод с именем ' +  method + ' не существует для jQuery.editable' );
        }

    };
})( jQuery );

/*$(function(){

    $('.editable').editable({

        onOk:function(text){

            console.log('onOk:'+text);
        },
        onCancel:function(text){
            console.log('onCancel:'+text);
        }
    });
    $('.turn-on-editable').bind('click', function(){

        $('.editable').editable()
    });

    $('.turn-off-editable').bind('click', function(){

        $('.editable').editable('destroy');
    });

});*/