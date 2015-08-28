/**
 * Created by maxb on 7/24/15.
 */
$(function () {


    var onAddCurseRow=function(){

        var index=$(this).data('index');
        index++;
        $(this).data('index', index)
        var cont=$(this).closest('.cont');

        var $newrow=$(cont.html());


        $('.code', $newrow).attr('name', 'SettingForm[currencies]['+index+'][code]');

        $('.value', $newrow).attr('name', 'SettingForm[currencies]['+index+'][value]');

        $('.btnAddCurseRow', $newrow).remove();


        $('.code', $newrow).attr('value', $('.code').val());
        $('.value', $newrow).attr('value', $('.value').val());


        $('.code', $newrow).removeClass('code');
        $('.value', $newrow).removeClass('value');

        $('.col-xs-2', $newrow).html('<a class="btn btn-primary btnMinusCurseRow"  ><i style="padding-right: 5px;" class="fa fa-minus"></i></a>');


        $('.btnMinusCurseRow', $newrow).bind('click', onRemoveCurseRow);


        $('.code').val('');
        $('.value').val('');



        $($newrow).insertBefore(cont);


        /* SettingForm[currencies][1000][code]*/

    };

    var onRemoveCurseRow=function(){

        $(this).closest('.row').remove();


    }



    $('.btnAddCurseRow').bind('click', onAddCurseRow );

    $('.btnMinusCurseRow').bind('click', onRemoveCurseRow);



});