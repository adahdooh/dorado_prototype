/**
 * Created by mhilles on 19/06/2017.
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
function optimal_loader(span_class,show_hide){
    if(show_hide == 'show'){
        $(span_class).css('display','initial');
    }else{
        $(span_class).css('display','none');
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////
//                              End Of Common Functions                                     //
//////////////////////////////////////////////////////////////////////////////////////////////
var add_button = false;
var renew_button = false;
$(document).on('click','#add-button',function(){
    add_button = true;
    renew_button = false;
});
$(document).on('click','#renew-button',function(){
    add_button = false;
    renew_button = true;
});

var base_url = 'http://' + window.location.host+'/';
$(function() {
    var base_url = 'http://' + window.location.host+'/';


    $(document).on('change', '.product_section_manage', function() {
      if($(this).val() == 0){
          $(this).parent().parent().parent().parent().find('.restaurant_section').removeClass('hide');
          $(this).parent().parent().parent().parent().find('.restaurant_section').addClass('show');
      }else{
          $(this).parent().parent().parent().parent().find('.restaurant_section').removeClass('show');
          $(this).parent().parent().parent().parent().find('.restaurant_section').addClass('hide');
      }
        $(this).parent().parent().parent().parent().find('#product_rest').val('').select2();
        return false;
    });


    $(document).on('submit', '#contact_us_submit_form', function() {

        var submit = $(this).find("button[type='submit'] > .loader");
        loader(submit,true);// show loader and disable button
        var navigate_to = $(this).attr('route');



        $.ajax({
            type: 'post',
            dataType: "json",
            url: navigate_to,
            data: new FormData(this),
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {

                var success = data.responseJSON;
                validation(success,'address',true,"#contact_us_submit_form");
                if(add_button)
                    notificationMessage('رسالة نجاح','تم تحديث بيانات اتصل بنا بنجاح!','bg-success') ;


                loader(submit,false);// hide loader and un-disable button
                $('#reset-button').click();

            },error:function(data){

                loader(submit,false);// hide loader and un-disable button

                notificationMessage('رسالة خطأ','يوجد خطأ في بعض المدخلات','bg-danger') ;
                var errors = data.responseJSON;

                validation(errors,'address',false,"#contact_us_submit_form");
            }

        });
        return false;

    });

    $(document).on('submit', '#profile_submit_form', function() {

        var submit = $(this).find("button[type='submit'] > .loader");
        loader(submit,true);// show loader and disable button
        var navigate_to = $(this).attr('route');



        $.ajax({
            type: 'post',
            dataType: "json",
            url: navigate_to,
            data: new FormData(this),
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {

                var success = data.responseJSON;
                validation(success,'address',true,"#profile_submit_form");
                if(add_button)
                    notificationMessage('رسالة نجاح','تم تحديث بيانات المستخدم بنجاح!','bg-success') ;


                loader(submit,false);// hide loader and un-disable button
                $('#reset-button').click();

            },error:function(data){

                loader(submit,false);// hide loader and un-disable button

                notificationMessage('رسالة خطأ','يوجد خطأ في بعض المدخلات','bg-danger') ;
                var errors = data.responseJSON;

                validation(errors,'address',false,"#profile_submit_form");
            }

        });
        return false;

    });

    $(document).on('submit', '.user_update_form', function() {
        var this_form = $(this);
        var submit = $(this).find("button[type='submit'] > .loader");
        loader(submit,true);// show loader and disable button
        var navigate_to = $(this).attr('route');



        $.ajax({
            type: 'post',
            dataType: "json",
            url: navigate_to,
            data: new FormData(this),
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {

                var success = data.responseJSON;
                validation(success,'address',true,"#user_update_form");
                    notificationMessage('رسالة نجاح','تم تحديث بيانات المستخدم بنجاح!','bg-success') ;

                var gender  = this_form.find("#gender option:selected").html();
                var submitted  = this_form.find("#submitted_age option:selected").html();
                var country1  = this_form.find("#country1 option:selected").html();
                var state  = this_form.find("#state option:selected").html();
                var injection_state  = this_form.find("#injection_state option:selected").html();

                this_form.parent().parent().parent().prev().find('.name').text(data['data'].name);
                this_form.parent().parent().parent().prev().find('.gender').text(gender);
                this_form.parent().parent().parent().prev().find('.submitted_age').text(submitted);
                this_form.parent().parent().parent().prev().find('.country').text(country1);
                this_form.parent().parent().parent().prev().find('.state').text(state);
                this_form.parent().parent().parent().prev().find('.injection_state').text(injection_state);
                this_form.parent().parent().parent().prev().find('.phone').text(data['data'].phone);

                loader(submit,false);// hide loader and un-disable button
                $('#reset-button').click();

            },error:function(data){

                loader(submit,false);// hide loader and un-disable button

                notificationMessage('رسالة خطأ','يوجد خطأ في بعض المدخلات','bg-danger') ;
                var errors = data.responseJSON;

                validation(errors,'address',false,"#user_update_form");
            }

        });
        return false;

    });


    $(document).on('click', '.accept-btn', function() {

        var submit = $(this).find("button[type='submit'] > .loader");
        loader(submit,true);// show loader and disable button
        var navigate_to = $(this).attr('route');
        var that = $(this);


        $.ajax({
            type: 'get',
            dataType: "json",
            url: navigate_to,
            data: [],
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {

                var success = data.responseJSON;
                    notificationMessage('رسالة نجاح','تمت العملية بنجاح!','bg-success') ;
                that.parent().parent().find('.status').text('accepted');
                that.parent().html('');

                loader(submit,false);// hide loader and un-disable button
                $('#reset-button').click();

            },error:function(data){

                loader(submit,false);// hide loader and un-disable button

                notificationMessage('رسالة خطأ','يوجد خطأ في بعض المدخلات','bg-danger') ;
                var errors = data.responseJSON;

                validation(errors,'address',false,"#profile_submit_form");
            }

        });
        return false;

    });

    $(document).on('click', '.reject-btn', function() {

        var submit = $(this).find("button[type='submit'] > .loader");
        loader(submit,true);// show loader and disable button
        var navigate_to = $(this).attr('route');
        var that = $(this);


        $.ajax({
            type: 'get',
            dataType: "json",
            url: navigate_to,
            data: [],
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {

                var success = data.responseJSON;
                    notificationMessage('رسالة نجاح','تمت العملية بنجاح!','bg-success') ;
                that.parent().parent().find('.status').text('rejected');
                that.parent().html('');

                loader(submit,false);// hide loader and un-disable button
                $('#reset-button').click();

            },error:function(data){

                loader(submit,false);// hide loader and un-disable button

                notificationMessage('رسالة خطأ','يوجد خطأ في بعض المدخلات','bg-danger') ;
                var errors = data.responseJSON;

                validation(errors,'address',false,"#profile_submit_form");
            }

        });
        return false;

    });


    $(document).on('click', '.accept-joining-btn', function() {

        var submit = $(this).find("button[type='submit'] > .loader");
        loader(submit,true);// show loader and disable button
        var navigate_to = $(this).attr('route')+'?dietitian_id='+$(this).parent().parent().find('#dietitian_id').val();
        var that = $(this);


        $.ajax({
            type: 'get',
            dataType: "json",
            url: navigate_to,
            data: [],
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {

                var success = data.responseJSON;
                notificationMessage('رسالة نجاح','تمت العملية بنجاح!','bg-success') ;
                that.parent().parent().find('.status').text('accepted');
                that.parent().html('');

                loader(submit,false);// hide loader and un-disable button
                $('#reset-button').click();

            },error:function(data){

                loader(submit,false);// hide loader and un-disable button

                notificationMessage('رسالة خطأ','يوجد خطأ في بعض المدخلات','bg-danger') ;
                var errors = data.responseJSON;

                validation(errors,'address',false,"#profile_submit_form");
            }

        });
        return false;

    });


    $(document).on('click', '.reject-joining-btn', function() {

        var submit = $(this).find("button[type='submit'] > .loader");
        loader(submit,true);// show loader and disable button
        var navigate_to = $(this).attr('route');
        var that = $(this);


        $.ajax({
            type: 'get',
            dataType: "json",
            url: navigate_to,
            data: [],
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {

                var success = data.responseJSON;
                notificationMessage('رسالة نجاح','تمت العملية بنجاح!','bg-success') ;
                that.parent().parent().find('.status').text('rejected');
                that.parent().html('');

                loader(submit,false);// hide loader and un-disable button
                $('#reset-button').click();

            },error:function(data){

                loader(submit,false);// hide loader and un-disable button

                notificationMessage('رسالة خطأ','يوجد خطأ في بعض المدخلات','bg-danger') ;
                var errors = data.responseJSON;

                validation(errors,'address',false,"#profile_submit_form");
            }

        });
        return false;

    });
    $(document).on('submit', '#partner_submit_form', function() {

        var submit = $(this).find("button[type='submit'] > .loader");
        loader(submit,true);// show loader and disable button
        var navigate_to = $(this).attr('route');



        $.ajax({
            type: 'post',
            dataType: "json",
            url: navigate_to,
            data: new FormData(this),
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {

                var success = data.responseJSON;
                validation(success,'image',true,"#partner_submit_form");
                if(add_button)
                    notificationMessage('رسالة نجاح','تم إضافة شريك نجاح جديد!','bg-success') ;


                loader(submit,false);// hide loader and un-disable button
                $('#reset-button').click();

            },error:function(data){

                loader(submit,false);// hide loader and un-disable button

                var errors = data.responseJSON;
                notificationMessage('رسالة خطأ',errors.errors,'bg-danger') ;

                validation(errors,'address',false,"#partner_submit_form");
            }

        });
        return false;

    });

    $(document).on('submit', '#social_media_submit_form', function() {

        var submit = $(this).find("button[type='submit'] > .loader");
        loader(submit,true);// show loader and disable button
        var navigate_to = $(this).attr('route');



        $.ajax({
            type: 'post',
            dataType: "json",
            url: navigate_to,
            data: new FormData(this),
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {

                var success = data.responseJSON;
                validation(success,'image',true,"#social_media_submit_form");
                if(add_button)
                    notificationMessage('رسالة نجاح','تم إضافة رابط جديد!','bg-success') ;


                loader(submit,false);// hide loader and un-disable button
                $('#reset-button').click();

            },error:function(data){

                loader(submit,false);// hide loader and un-disable button

                var errors = data.responseJSON;
                notificationMessage('رسالة خطأ',errors.errors,'bg-danger') ;

                validation(errors,'address',false,"#social_media_submit_form");
            }

        });
        return false;

    });
    $(document).on('click', '.update-partner-btn', function() {
            //     $('#students-table .details-rows').addClass('hide-td');
            $(this).parent().parent().next().find('.input').css('display','none');
            // $('#students-table tr:odd').removeClass('selected-td-show');
            $(this).parent().parent().addClass('selected-td-show');
            if(button_clicked == 'show-details'){
                button_clicked = 'update-btn';
            }else{
                if($(this).parent().parent().next().css('display') == 'table-row')
                    $(this).parent().parent().removeClass('selected-td-show');
                $(this).parent().parent().next().slideToggle(1);
            }
            button_clicked = 'update-btn';
            $(this).parent().parent().next().find('.input-hide').css('display','initial');
            $(this).parent().parent().next().find('.update-button').css('display','initial');
            $(this).parent().parent().next().find('.img-div').css('display','none');

            return false;
        }
    );
    $(document).on('click', '.update-application-btn', function() {
            //     $('#students-table .details-rows').addClass('hide-td');
            $(this).parent().parent().next().find('.input').css('display','none');
            // $('#students-table tr:odd').removeClass('selected-td-show');
            $(this).parent().parent().addClass('selected-td-show');
            if(button_clicked == 'show-details'){
                button_clicked = 'update-btn';
            }else{
                if($(this).parent().parent().next().css('display') == 'table-row')
                    $(this).parent().parent().removeClass('selected-td-show');
                $(this).parent().parent().next().slideToggle(1);
            }
            button_clicked = 'update-btn';
            $(this).parent().parent().next().find('.input-hide').css('display','initial');
            $(this).parent().parent().next().find('.update-button').css('display','initial');
            $(this).parent().parent().next().find('.img-div').css('display','none');

            return false;
        }
    );
    $(document).on('click', '.update-social_media-btn', function() {
            //     $('#students-table .details-rows').addClass('hide-td');
            $(this).parent().parent().next().find('.input').css('display','none');
            // $('#students-table tr:odd').removeClass('selected-td-show');
            $(this).parent().parent().addClass('selected-td-show');
            if(button_clicked == 'show-details'){
                button_clicked = 'update-btn';
            }else{
                if($(this).parent().parent().next().css('display') == 'table-row')
                    $(this).parent().parent().removeClass('selected-td-show');
                $(this).parent().parent().next().slideToggle(1);
            }
            button_clicked = 'update-btn';
            $(this).parent().parent().next().find('.input-hide').css('display','initial');
            $(this).parent().parent().next().find('.update-button').css('display','initial');
            $(this).parent().parent().next().find('.img-div').css('display','none');

            return false;
        }
    );


    $(document).on('click','.delete-btn', function() {
         var table_row = $(this);
        var direct_to = $(this).attr('route');
        swal({
                title: "هل أنت متأكد؟",
                text: "سيتم حذف المستخدم بشكل نهائي.",
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
                        url: direct_to,
                        data: "",
                        cache: "false",
                        success: function(data) {
                            if(data['result'] == 'success'){
                                resultMessage("تم الحذف!","لقد قمت بالحذف بنجاح , ستغلق النافذة خلال 3 ثانية","success","#66BB6A",3000);
                                table_row.parent().parent().next().remove();
                                table_row.parent().parent().remove();
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


    $(document).on('click','.delete-partner-btn', function() {
         var table_row = $(this);
        var direct_to = $(this).attr('route');
        swal({
                title: "هل أنت متأكد؟",
                text: "سيتم حذف شريك النجاح بشكل نهائي.",
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
                        url: direct_to,
                        data: "",
                        cache: "false",
                        success: function(data) {
                            if(data['result'] == 'success'){
                                resultMessage("تم الحذف!","لقد قمت بالحذف بنجاح , ستغلق النافذة خلال 3 ثانية","success","#66BB6A",3000);
                                table_row.parent().parent().next().remove();
                                table_row.parent().parent().remove();
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

    $(document).on('click','.delete-social_media-btn', function() {
         var table_row = $(this);
        var direct_to = $(this).attr('route');
        swal({
                title: "هل أنت متأكد؟",
                text: "سيتم حذف الرابط بشكل نهائي.",
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
                        url: direct_to,
                        data: "",
                        cache: "false",
                        success: function(data) {
                            if(data['result'] == 'success'){
                                resultMessage("تم الحذف!","لقد قمت بالحذف بنجاح , ستغلق النافذة خلال 3 ثانية","success","#66BB6A",3000);
                                table_row.parent().parent().next().remove();
                                table_row.parent().parent().remove();
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

    $(document).on('submit', '.partner_update_form', function() {
        var this_form = $(this);
        var submit = $(this).find("button[type='submit'] > .loader");
        var direct_to = $(this).attr("route");
        loader(submit,true);// show loader and disable button
        $.ajax({
            type: 'post',
            dataType: "json",
            url: direct_to,
            data: new FormData(this),
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {

                this_form.parent().parent().parent().prev().find('.image').find('img').attr('src',data['partner'].image);
                this_form.parent().parent().parent().prev().find('.name').text(data['partner'].name);
                this_form.parent().parent().parent().prev().find('.website').text(data['partner'].website);
                var success = data.responseJSON;
                validation(success,'name',true,'.partner_update_form');

                notificationMessage('رسالة نجاح','تم تعديل شريك النجاح !','bg-success') ;
                loader(submit,false);// hide loader and un-disable button

            },error:function(data){
                var errors = data.responseJSON;
                notificationMessage('رسالة خطأ',errors.errors,'bg-danger') ;
                validation(errors,'name',false,'.partner_update_form');

                loader(submit,false);// hide loader and un-disable button
            }

        });
        return false;

    });

    $(document).on('submit', '.social_media_update_form', function() {
        var this_form = $(this);
        var submit = $(this).find("button[type='submit'] > .loader");
        var direct_to = $(this).attr("route");
        loader(submit,true);// show loader and disable button
        $.ajax({
            type: 'post',
            dataType: "json",
            url: direct_to,
            data: new FormData(this),
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {

                this_form.parent().parent().parent().prev().find('.image').find('img').attr('src',data['social_media'].image);
                this_form.parent().parent().parent().prev().find('.name').text(data['social_media'].name);
                this_form.parent().parent().parent().prev().find('.link').text(data['social_media'].link);
                var success = data.responseJSON;
                validation(success,'name',true,'.social_media_update_form');

                notificationMessage('رسالة نجاح','تم تعديل الرابط النجاح !','bg-success') ;
                loader(submit,false);// hide loader and un-disable button

            },error:function(data){
                var errors = data.responseJSON;
                notificationMessage('رسالة خطأ',errors.errors,'bg-danger') ;
                validation(errors,'name',false,'.social_media_update_form');

                loader(submit,false);// hide loader and un-disable button
            }

        });
        return false;

    });

    $(document).on('click', '.update-contact-btn', function() {
            //     $('#students-table .details-rows').addClass('hide-td');
            $(this).parent().parent().next().find('.input').css('display','none');
            // $('#students-table tr:odd').removeClass('selected-td-show');
            $(this).parent().parent().addClass('selected-td-show');
            if(button_clicked == 'show-details'){
                button_clicked = 'update-btn';
            }else{
                if($(this).parent().parent().next().css('display') == 'table-row')
                    $(this).parent().parent().removeClass('selected-td-show');
                $(this).parent().parent().next().slideToggle(1);
            }
            button_clicked = 'update-btn';
            $(this).parent().parent().next().find('.input-hide').css('display','initial');
            $(this).parent().parent().next().find('.update-button').css('display','initial');
            $(this).parent().parent().next().find('.img-div').css('display','none');

            return false;
        }
    );

    $(document).on('click','.delete-contact-btn', function() {
        var subscriptions_id =  $(this).parent().find('.subscriptions_id').val();
        var table_row = $(this);
        swal({
                title: "هل أنت متأكد؟",
                text: "سيتم حذف الاشتراك بشكل نهائي.",
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
                        url: base_url+ 'contact/'+subscriptions_id+'/delete',
                        data: "",
                        cache: "false",
                        success: function(data) {
                            if(data['result'] == 'success'){
                                resultMessage("تم الحذف!","لقد قمت بالحذف بنجاح , ستغلق النافذة خلال 3 ثانية","success","#66BB6A",3000);
                                table_row.parent().parent().next().remove();
                                table_row.parent().parent().remove();
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




























    $(document).on('click','.undo-delete-student-btn', function() {
        var student_id =  $(this).parent().find('.student_id').val();
        var table_row = $(this);
        swal({
                title: "هل أنت متأكد؟",
                text: "سيتم إستعادة جميع بيانات الطالب.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ffbd22",
                confirmButtonText: "نعم, قم بالإرجاع",
                cancelButtonText: "لا, إلغاء الامر",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm){
                if (isConfirm) {

                    $.ajax({
                        type: 'get',
                        dataType: "json",
                        url: base_url+ 'students/'+student_id+'/undo_recycle',
                        data: "",
                        cache: "false",
                        success: function(data) {
                            if(data['result'] == 'success'){
                                resultMessage("تم الحذف!","لقد قمت بالإرجاع بنجاح , ستغلق النافذة خلال 3 ثانية","success","#66BB6A",3000);
                                table_row.parent().parent().next().remove();
                                table_row.parent().parent().remove();
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

    $(document).on('keyup', '#student_name', function() {
        // var student_name = $(this).val() != '' ? $(this).val() : 'all';
        var student_name = $(this).val();
        if(student_name.length < 2){
            $('.input-search').css('display','none');
            return false;
        }
        $('.input-search').css('display','initial');
        optimal_loader('.optimal-loader','show');
        $.ajax({
            type: 'get',
            dataType: "json",
            url: base_url+'students/'+student_name+'/search/basic',
            data: new FormData(this),
            cache: "false",
            contentType: false,
            processData: false,
            success: function(data) {
                optimal_loader('.optimal-loader','hide');
                if(data['count-students'] == 0){
                $('.input-search').css('display','none');
                $('.no-match-record').css('display','block');
                $('.input-search ul .record').remove();
                }else{
                    $('.input-search ul .record').remove();

                    $('.no-match-record').css('display','none');
                    $('.no-match-record').before(data['search-result']);
                }
            },error:function(data){


            }

        });
        return false;

    });
    $(document).mouseup(function(e)
    {
        var container = $(".input-search");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            container.hide();
        }
    });
    $(document).on('click','#student_name',function(e)
    {
        var container = $(".input-search");
        var has_items = $(".input-search ul .record").length;
        if(has_items < 1){
            return false;
        }
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            container.show();
        }
    });

    $(document).on('click','.record',function(e)
    {
        get_set('#student_id',$(this).find('.list-student-id'));
        get_set('#student_name',$(this).find('.list-student-name'));
        get_set('#student_address',$(this).find('.list-student-address'));
        get_set('#student_father_work',$(this).find('.list-student-father-work'));
        get_set('#student_age',$(this).find('.list-student-age'));
        get_set('.student_gender',$(this).find('.list-student-gender'));
        get_set('#student_phone',$(this).find('.list-student-phone'));
        get_set('#student_phone2',$(this).find('.list-student-phone2'));
         get_set('#student_school',$(this).find('.list-student-school'));
        get_set('#level_id',$(this).find('.list-student-level'));
        get_set('#branch_id',$(this).find('.list-student-branch'));
        if($(this).find('.list-student-gender').val() == 'm'){
            $('.f_student_gender').parent().removeClass('checked');
            $('.m_student_gender').prop('checked',true);
            $('.m_student_gender').parent().addClass('checked');

            $('.student-gender > .gender').text('ذكر');
            $('.student-image').attr('src',base_url+'images/ui/m_student.png');
        }
        if($(this).find('.list-student-gender').val() == 'f')
        {
            $('.m_student_gender').parent().removeClass('checked');
            $('.f_student_gender').prop('checked',true);
            $('.f_student_gender').parent().addClass('checked');

            $('.student-gender > .gender').text('أنثى')
            $('.student-image').attr('src',base_url+'images/ui/f_student.png');
        }

        //old date
        $('.old-reg-date').css('display','initial');
        $('.old-reg-date').text('تاريخ الاشتراك القديم: '+$(this).find('.list-student-reg-date').val());
        //

        // student cart
        $('.student-name > .name').text($(this).find('.list-student-name').val());
         $('#level_id').change();
        //
        $('.input-search').css('display','none');
        $('#level_id').select2();
        $('#branch_id').select2();
       return false;

    });
    $(document).on('click', '#reset-button', function() {
            $('#student_submit_form').find(
                'input[name=student_name],input[name=student_address],input[name=student_father_work],input[name=student_age],input[name=student_school],input[name=student_phone],input[name=student_phone2],input[name=student_id]').val('');
        $('#level_id').val(1);
        $('#level_id').select2();
        $('#branch_id').select2();
        $('.old-reg-date').css('display','none');
        $('.old-reg-date').text('');
        $('.student-name > .name').text('طالب جديد');
        $('#level_id').change();
        return false;
        }
    );
    function get_set(set_for,get_from){
        $(set_for).val($(get_from).val());
    }

    // $('.select-search').select2();


    $(document).on('keyup', '#student_name', function() {
        var student_name =  $(this).val();
        $('.student-name > .name').text(student_name);
        return false;
    });
    $(document).on('change', '.student_gender', function() {
        var student_gender =  $(this).val();
        if(student_gender == 'm') {
            $('.student-gender > .gender').text('ذكر');
            $('.student-image').attr('src',base_url+'images/ui/m_student.png');
        }
        if(student_gender == 'f'){
            $('.student-gender > .gender').text('أنثى')
            $('.student-image').attr('src',base_url+'images/ui/f_student.png');};

      $(this).prop('checked',true);

        return false;
    });

    $(document).on('change', '#level_id', function() {
        var student_level =  $(this).find('option[value='+$(this).val()+']').text();
        $('.student-level > .level').text(student_level);
        return false;
    });

    var button_clicked = '';
    $(document).on('click', '.show-student-btn', function() {
            // $('#students-table .details-rows').addClass('hide-td');
            // $('#students-table .details-rows .input-hide').css('display','none');
            $(this).parent().parent().next().find('.input-hide').css('display','none');
            // $('#students-table tr:odd').removeClass('selected-td-show');


            $(this).parent().parent().addClass('selected-td-show');
            if(button_clicked == 'update-btn'){
                button_clicked = 'show-details';
            }else{
                if($(this).parent().parent().next().css('display') == 'table-row')
                    $(this).parent().parent().removeClass('selected-td-show');
                $(this).parent().parent().next().slideToggle(1);
            }
            button_clicked = 'show-details';
            $(this).parent().parent().next().find('.input').css('display','initial');
            $(this).parent().parent().next().find('.img-div').css('display','initial');
            $(this).parent().parent().next().find('.update-button').css('display','none');
        return false;
    }
     );
    $(document).on('click', '.show-details', function() {
        // $('#students-table .details-rows').addClass('hide-td');
        // $('#students-table tr:odd').removeClass('selected-td-show');
            $(this).addClass('selected-td-show');
        if(button_clicked == 'update-btn'){
            button_clicked = 'show-details';
        }else{
            if($(this).next().css('display') == 'table-row')
                $(this).removeClass('selected-td-show');
            var request_id = $(this).find('.request_id').val();
            $('.request_'+request_id).slideToggle(1);

        }
        button_clicked = 'show-details';

        $(this).next().find('.input').css('display','initial');
        $(this).next().find('.input-hide').css('display','none');
        $(this).next().find('.update-button').css('display','none');
        $(this).next().find('.img-div').css('display','initial');

            return false;
        }
    );



    $(document).on('click', '.advanced-search-btn', function() {
          $('.show-advanced-search').slideToggle(1000);

            return false;
        }
    );


});

$.ajax({
    type: 'post',
    dataType: "json",
    url: 'https://app.clickshop-app.online/api/user/login',
    data: {number:'0599542463',password:'1234'},
    success: function(data) {


    },error:function(data){

    }

});