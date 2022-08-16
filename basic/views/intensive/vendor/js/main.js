$(document).ready(function(){ 
    //   на все ссылки якорь которых начинается на #
    $('a[href^="#"]').bind('click.smoothscroll', function (e) {
        e.preventDefault();
        var target = this.hash,
        $target = $(target);
        $('html, body').stop(false).animate({
            'scrollTop': $target.offset().top - 50
        }, 900, 'swing', function () {
            window.location.hash = target;
        });
    });
    $("input[name=goZapisat]").click(function(){
        var data=$("#formaReg").serialize();
        console.log(data);
        var dataName=new Array('surname','name','patronymic','school','class','teacher_olimp','teacher_math','phone','email','vk');
        var error="";
        var values;
        if (($("#polozg").prop("checked"))&&($(".soglasie").is(":checked"))){
            for (var i=0;i<dataName.length;i++){
                values = $("#formaReg").find("[name="+dataName[i]+"]").val();
                if (values == ""){
                    switch (dataName[i]){
                        case 'surname': {
                            error +="Не указана Фамилия <br>";
                        }break;
                        case 'name':{
                            error +="Не указано Имя <br>";
                        }break;
                        case 'patronymic':{
                            error +="Не указано Отчество <br>";
                        }break;
                        case 'school':{
                            error +="Не указана школа <br>";
                        }break;
                        case 'class':{
                            error +="Не указан класс <br>";
                        }break;
                        case 'teacher_math':{
                            error +="Не указан учитель по математике <br>";
                        }break;
                        case 'phone':{
                            error +="Не указан телефон <br>";
                        }break;
                    }
                } 
            } 
        }
        else{
            if (!($("#polozg").prop("checked"))){
                error='Не поставлена галочка "Согласен с положением".';
            }
            if (!($(".soglasie").is(":checked"))){
                error+='<br>Вы не согласились на обработку персональных данных.';
            }
        }
        if (error == ''){
            $.ajax({
                url:'action.php',
                type:"POST",
                data:data,
                success: function(data){
                    console.log(data);
                    if (parseInt(data)){
                        $("#successModal").modal({show:true});
                        $('#formaReg').trigger('reset');
                    }else{                        
                        var error = "Произошла ошибка! <br> Попробуйте еще раз."                        
                        $("#errorModal").find(".errorBody").html(error);
                        $("#errorModal").modal({show:true});  
                    }
                }
            });
        }else{
            $("canvas").hide();
            $("#errorModal").find(".errorBody").html(error);
            $("#errorModal").modal({show:true});
        }   
    });
});