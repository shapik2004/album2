var PhotobookApi = {


    customRequest:function (data){

        var type='GET';

        if(data.hasOwnProperty('type'))
            type=data.type;

        var send_data=null;
        if(data.hasOwnProperty('data')){

            send_data=data.data;
        }

        $.ajax({
            url: data.url,
            type: type,
            data: send_data,
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


    },
    postRequest:function (data){

        var type='POST';

        if(data.hasOwnProperty('type'))
            type=data.type;

        var send_data=null;
        if(data.hasOwnProperty('data')){

            send_data=data.data;
        }

        $.ajax({
            url: data.url,
            type: type,
            data: send_data,
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


    },
    changeGroupName:function (data){


        $.ajax({
            url: data.url,
            type: 'GET',
            data: data.data,
            dataType:'json',
            success: function(result) {
                //called when successful
                if(data.success)
                    data.success();
            },
            error: function(e) {
                //called when there is an error
                //console.log(e.message);

                if(data.error)
                    data.error(e);
            }
        });


    },
    addGroup:function (data){

        $.ajax({
            url: data.url,
            type: 'GET',
            data: data.data,
            dataType:'json',
            success: function(result) {
                //called when successful
                if(data.success)
                    data.success(result);
            },
            error: function(e) {
                //called when there is an error
                //console.log(e.message);

                if(data.error)
                    data.error(e);
            }
        });
    },
    changeName:function (data){


        $.ajax({
            url: data.url,
            type: 'GET',
            data: data.data,
            dataType:'json',
            success: function(result) {
                //called when successful
                if(data.success)
                    data.success();
            },
            error: function(e) {
                //called when there is an error
                //console.log(e.message);

                if(data.error)
                    data.error(e);
            }
        });


    }


}