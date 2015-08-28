var CoverApi = {


    customRequest:function (data){

        $.ajax({
            url: data.url,
            type: 'POST',
            data: data.data,
            dataType:'json',
            success: function(result) {
                //called when successful


                if(result.hasOwnProperty('response')){
                    if(data.success)
                        data.success(result);
                }else{

                    if(data.error)
                        data.error(result.error.msg);
                }
            },
            error: function(e) {
                //called when there is an error
                //console.log(e.message);

                console.log(e);
                if(data.error)
                    data.error(e.responseText);
            }
        });


    }


}