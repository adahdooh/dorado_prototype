/**
 * Created by mhilles on 04/06/2017.
 */

//////////////////////////////////////////////////////////////////////////////////////////////
//                              Start Of Common Functions                                   //
//////////////////////////////////////////////////////////////////////////////////////////////


function validation(jsonObject,elementId,ajax_response,form_id){
    if(!ajax_response){// if ajax return error process
        //validation inputs of laravel -- input names
        if(jsonObject.hasOwnProperty(elementId)){
            $(form_id).find('#'+elementId).parent().find('.validation-error').remove();
            $(form_id).find('#'+elementId).parent().parent().addClass("has-error  has-feedback");
            $(form_id).find('#'+elementId).after($('.validation').html());
            $(form_id).find('#'+elementId).parent().find('span').text(jsonObject[elementId]);
        }else{
            $(form_id).find('#'+elementId).parent().parent().removeClass("has-error  has-feedback");
            $(form_id).find('#'+elementId).parent().find('.validation-error').remove();
        }
    }else{
        $(form_id).find('#'+elementId).parent().parent().removeClass("has-error  has-feedback");
        $(form_id).find('#'+elementId).parent().find('.validation-error').remove();
    }

}
function loader(btn , turn){
    // trun : true to show loader , false to hide
    if(turn){
        btn.parent().attr('disabled',true);
        btn.removeClass('hide');
        btn.addClass('show');
    }  else{
        btn.parent().attr('disabled',false);
        btn.removeClass('show');
        btn.addClass('hide');
    }
}

function blockLoader(block){
    $(block).block({
        message: $('.blockui-animation-container'),

        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.8,
            cursor: 'wait'
        },
        css: {
            width: 36,
            height: 36,
            color: '#fff',
            border: 0,
            padding: 0,
            backgroundColor: 'transparent'
        }
    });

    var animation = $(this).data("animation");
    $('.blockui-animation-container').addClass("animated " + animation).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function () {
        $(this).removeClass("animated " + animation);
    });
}
function resultMessage(title,body,type,button_color,timer){
    swal({
        title: title,
        text: body,
        confirmButtonColor: button_color,
        type: type,
        timer:timer
    });
}
function cancelDelete(){
    swal({
        title: "تم إلغاء الامر",
        text: "لقد قمت بالتراجع عن عملية الحذف",
        confirmButtonColor: "#2196F3",
        type: "error",
        timer:2000
    });
}

function notificationMessage(title,body,color){
    new PNotify({
        title: title,
        text: body,
        addclass: color
    });
}
//////////////////////////////////////////////////////////////////////////////////////////////
//                              End Of Common Functions                                     //
//////////////////////////////////////////////////////////////////////////////////////////////
$(function() {
    var base_url = 'http://' + window.location.host+'/';
    $(document).on('submit', '#users_submit_form', function() {
        var submit = $(this).find("button[type='submit'] > .loader");
        loader(submit,true);// show loader and disable button
        $.ajax({
            type: 'post',
            dataType: "json",
            url: base_url+'users',
            data: new FormData(this),
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {
                var url ='';
                if(data['user'].img != 'default_user.png') url= base_url+'uploads/users'; else url= base_url+'images';
                $("#users-table tbody tr:last").after('<tr> <td> <div class="media-left media-middle" style="padding-right: 0px;"> <img style="border-radius: 50%" src="'+url+'/'+data['user'].img+'" alt=""/> </div> <div class="media-body" style="width: 0px"> <div class="media-heading"> <a href="#" class="letter-icon-title update-user-btn-sub">'+data['user'].name+'</a> <input type="hidden" name="user_id" class="user_id" value="'+data['user'].id+'" /> </div> <div class="text-muted text-size-small"><i class="icon-phone text-size-mini position-left"></i>'+data['user'].phone+'</div> </div> </td> <td> <span class="text-muted text-size-small">'+data['user'].created_at+'</span> </td> <td> <h6 class="text-semibold no-margin"><a href="#"  class="update-user-btn"><i class="  icon-pencil7 update-icon"></i></a><a href="#" class="sweet_combine"> <i class=" icon-user-cancel delete-icon"></i> </a> <input type="hidden" name="user_id" class="user_id" value="'+data['user'].id+'" /></h6> </td> </tr>');


                notificationMessage('رسالة نجاح','تم إضافة مستخدم جديد بنجاح!','bg-success') ;

                var success = data.responseJSON;
                validation(success,'name',true,"#users_submit_form");
                validation(success,'password',true,"#users_submit_form");
                validation(success,'email',true,"#users_submit_form");
                validation(success,'phone',true,"#users_submit_form");
                validation(success,'branch_id',true,"#users_submit_form");
                validation(success,'gender',true,"#users_submit_form");


                loader(submit,false);// hide loader and un-disable button

            },error:function(data){

                notificationMessage('رسالة خطأ','يوجد خطأ في بعض المدخلات','bg-danger') ;

                var errors = data.responseJSON;
                validation(errors,'name',false,"#users_submit_form");
                validation(errors,'password',false,"#users_submit_form");
                validation(errors,'email',false,"#users_submit_form");
                validation(errors,'phone',false,"#users_submit_form");
                validation(errors,'branch_id',false,"#users_submit_form");
                validation(errors,'gender',false,"#users_submit_form");
                loader(submit,false);// hide loader and un-disable button
            }

        });
        return false;

    });

    $(document).on('submit', '#users_update_form', function() {
        var submit = $(this).find("button[type='submit'] > .loader");
        loader(submit,true);// show loader and disable button
        var block =  $('#users_update_form').find('#user-block');
        blockLoader(block);
        $.ajax({
            type: 'post',
            dataType: "json",
            url: base_url+'users/update',
            data: new FormData(this),
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {
                var url ='';

                // users_table_row.parent().parent().parent().remove();
                if(data['user'].img != 'default_user.png') url= base_url+'uploads/users'; else url= base_url+'images';

                if(data['result']=='success'){
                    notificationMessage('رسالة نجاح','تم تعديل المستخدم بنجاح!','bg-success') ;
                    $("#users-table tbody").find(users_table_row).html(' <td> <div class="media-left media-middle" style="padding-right: 0px;"> <img style="border-radius: 50%" src="'+url+'/'+data['user'].img+'" alt=""/> </div> <div class="media-body" style="width: 0px"> <div class="media-heading "> <a href="#" class="letter-icon-title update-user-btn-sub">'+data['user'].name+'</a> <input type="hidden" name="user_id" class="user_id" value="'+data['user'].id+'" /> </div> <div class="text-muted text-size-small"><i class="icon-phone text-size-mini position-left"></i>'+data['user'].phone+'</div> </div> </td> <td> <span class="text-muted text-size-small">'+data['user'].created_at+'</span> </td> <td> <h6 class="text-semibold no-margin"><a href="#"  class="update-user-btn"><i class="  icon-pencil7 update-icon"></i></a><a href="#" class="sweet_combine"> <i class=" icon-user-cancel delete-icon"></i> </a> <input type="hidden" name="user_id" class="user_id" value="'+data['user'].id+'" /></h6> </td> ');
                    $('#users_update_form').find('#user_img').attr('src',url+'/'+data['user'].img  );
                    // before_row=  before_row.next();
                    // users_table_row.parent().parent().parent().remove();
                }else{
                    notificationMessage('رسالة خطأ','يوجد خطأ في بعض المدخلات','bg-danger') ;
                }


                var success = data.responseJSON;
                validation(success,'name',true,"#users_update_form");
                validation(success,'password',true,"#users_update_form");
                validation(success,'email',true,"#users_update_form");
                validation(success,'phone',true,"#users_update_form");
                validation(success,'branch_id',true,"#users_update_form");
                validation(success,'gender',true,"#users_update_form");


                loader(submit,false);// hide loader and un-disable button
                $(block).unblock();
            },error:function(data){

                notificationMessage('رسالة خطأ','يوجد خطأ في بعض المدخلات','bg-danger') ;

                var errors = data.responseJSON;
                validation(errors,'name',false,"#users_update_form");
                validation(errors,'password',false,"#users_update_form");
                validation(errors,'email',false,"#users_update_form");
                validation(errors,'phone',false,"#users_update_form");
                validation(errors,'branch_id',false,"#users_update_form");
                validation(errors,'gender',false,"#users_update_form");
                loader(submit,false);// hide loader and un-disable button
                $(block).unblock();
            }

        });
        return false;

    });

    $(document).on('submit', '#user_branch_submit_form', function() {
        var submit = $(this).find("button[type='submit'] > .loader");
        loader(submit,true);// show loader and disable button
        $.ajax({
            type: 'post',
            dataType: "json",
            url: base_url+'users/'+user_no+'/branches/update',
            data: new FormData(this),
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {

                if(data['result']=='success'){
                    notificationMessage('رسالة نجاح','تم تعديل المستخدم بنجاح!','bg-success') ;

                }else{
                    notificationMessage('رسالة خطأ','يوجد خطأ في بعض المدخلات','bg-danger') ;
                }
                loader(submit,false);// hide loader and un-disable button
                $('.close').click();
            },error:function(data){

                notificationMessage('رسالة خطأ','يوجد خطأ في بعض المدخلات','bg-danger') ;

                loader(submit,false);// hide loader and un-disable button
            }

        });
        return false;

    });


    $(document).on('click','.sweet_combine', function() {
   var user_id =  $(this).parent().find('.user_id').val();
   var table_row = $(this);
    swal({
            title: "هل أنت متأكد؟",
            text: "عملية الحذف ستكون نهائيا!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF5350",
            confirmButtonText: "نعم, قم بالحذف",
            cancelButtonText: "لا, إلغاء الامر",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {

                $.ajax({
                    type: 'get',
                    dataType: "json",
                    url: base_url+ 'users/'+user_id+'/delete',
                    data: "",
                    cache: "false",
                    success: function(data) {
                        if(data['result'] == 'success'){
                            resultMessage("تم الحذف!","لقد قمت بالحذف بنجاح , ستغلق النافذة خلال 3 ثانية","success","#66BB6A",3000);
                            table_row.parent().parent().parent().remove();
                        }else{
                            resultMessage("حدث خطأ","حدث خطأ ما أثناء عملية الحذف","error","#EF5350",4000);

                        }

                    }

                });

            }
            else {
                cancelDelete();
            }
        });
    return false;
});
    var user_no;
    $(document).on('click','.branch-user-btn',function(){

         user_no =  $(this).parent().find('.user_id').val();

        $.ajax({
            type: 'get',
            dataType: "json",
            url: base_url + 'users/'+user_no+'/branches',
            data: "",
            cache: "false",
            success: function(data) {
                var result = data.responseJSON;


                var array=[];
                for(var i = 0;i<data['user_branches'].length;i++){
                    // alert(data['operations'][i]['permission_name']);
                    array[i] = data['user_branches'][i]['branch_id'];

                }
                $('#select-branch-id').val(array);


                $('#select-branch-id').select2();

            }


        });

        return false;
    });

    var users_table_row;
var before_row;
    $(document).on('click','.update-user-btn',function(){
        users_table_row = $(this).parent().parent().parent();
        before_row = users_table_row.parent().parent().parent().prev();
        var user_id =  $(this).parent().find('.user_id').val();
        $('#users_submit_form').removeClass('show');
        $('#users_submit_form').addClass('hide');

        $('#users_update_form').removeClass('hide');
        $('#users_update_form').addClass('show');

        $('#users_update_form').velocity('transition.slideUpBigIn', {
            drag: true
        });

       var success = '';
        validation(success,'name',true,"#users_update_form");
        validation(success,'password',true,"#users_update_form");
        validation(success,'email',true,"#users_update_form");
        validation(success,'phone',true,"#users_update_form");
        validation(success,'branch_id',true,"#users_update_form");
        validation(success,'gender',true,"#users_update_form");


            var block = $('#users_update_container');
             blockLoader(block);


        $.ajax({
            type: 'get',
            dataType: "json",
            url: base_url + 'users/'+user_id+'/edit',
            data: "",
            cache: "false",
            success: function(data) {
                var result = data.responseJSON;
                $('#users_update_form').find('#name').val(data['result'][0]['name']);
                $('#users_update_form').find('#id').val(data['result'][0]['id']);
                $('#users_update_form').find('#email').val(data['result'][0]['email']);
                $('#users_update_form').find('#phone').val(data['result'][0]['phone']);
                $('#users_update_form').find('#img').val('');
                $('#users_update_form').find('.filename').text('No file Selected');

                if(data['result'][0]['img'] != 'default_user.png') url= base_url+'uploads/users'; else url= base_url+'images';
                $('#users_update_form').find('#user_img').attr('src',url+'/'+data['result'][0]['img'] );
                //  $('#users_update_form').find('#branch_id').val(data['result'][0]['branch_id']);
                //
                // $('#users_update_form').find('#branch_id').select2();
                if(data['result'][0]['gender'] == 'm'){
                    $('#users_update_form').find('#gender').parent().removeClass('checked');
                     $('#users_update_form').find('#gender:first').prop('checked',true);
                    $('#users_update_form').find('#gender:first').parent().addClass('checked');
                 }
                    else
                {
                    $('#users_update_form').find('#gender').parent().removeClass('checked');
                     $('#users_update_form').find('#gender:last').prop('checked',true);
                    $('#users_update_form').find('#gender:last').parent().addClass('checked');
                 }
                $(block).unblock();
             }


        });

        return false;
    });

    $(document).on('click','.update-user-btn-sub',function(){

     $(this).parent().parent().parent().parent().find('.update-user-btn').click();
        return false;
    });
    $(document).on('click','.new-user-btn',function(){

        $('#users_submit_form').removeClass('hide');
        $('#users_submit_form').addClass('show');

        $('#users_update_form').removeClass('show');
        $('#users_update_form').addClass('hide');

        $('#users_submit_form').velocity('transition.slideUpBigIn', {
            drag: true
        });
        return false;
    });
});
