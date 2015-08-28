
var UserUrl=(function(){

    /*
     const  IMAGE_THUMB      =   '_t';
     const  IMAGE_SMALL      =   '_s';
     const  IMAGE_MIDDLE     =   '_m';
     const  IMAGE_LARGE      =   '_l';
     const  IMAGE_XLARGE     =   '_xl';
     const  IMAGE_XXLARGE    =   '_xxl';
     const  IMAGE_ORIGINAL    =  '_o';

     */
    return {

        Sizes:{
            thumb:'_t',
            small:'_s',
            middle:'_m',
            large:'_l',
            xlarge:'_xl',
            xxlarge:'_xxl',
            original:'_o'
        },
        home:function(ref_id){

            return '/uploads/'+ref_id;
        },
        photobook:function(ref_id, id){

            return this.home(ref_id)+"/pb/"+id;

        },
        photobookPhotos:function(ref_id, id){

            return this.photobook(ref_id, id)+"/photos";
        },
        imageFile:function(image_file, image_size, ext){

            if(!ext){
                ext='jpg';
            }
            return image_file+''+image_size+'.'+ext;

        }
    }

})();