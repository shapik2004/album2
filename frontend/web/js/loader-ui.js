/**
 * Created by maxbsoft on 12/12/14.
 */
$(function () {


    $(document).ready(function(){

        loaderVisible=false;
        showLoader=function(msg){

            if(!msg) msg='Поворачиваем...';

            $('.loader label').html(msg);

            if(!loaderVisible);
            $('.loader').fadeToggle();

            loaderVisible=true;
        }

        updateLoader=function(msg){

            $('.loader label').html(msg);
        }

        hideLoader=function(){

            loaderVisible=false;
            $('.loader').fadeToggle();
        }

    })

});