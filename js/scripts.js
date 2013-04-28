$(document).ready(function(){
    $('#next').click(function(){
        
        // ------------------ STEP 1 Verification --------------------//
        
        var flag = 0;
        var message = '';
        if($('#fname').val().trim() == ''){
            flag = 1;
            message += 'Full Name is necessary<br/>';
        }
        if ($('#roll_number').val().trim() == ''){
            flag = 1;
            message += 'Roll Number is necessary<br/>';
        }
        if ($('#branch').val().trim() == ''){
            flag = 1;
            message += 'Branch is necessary<br/>';
        }
        if ($('#room').val().trim() == ''){
            flag = 1;
            message += 'Room no. is necessary<br/>';
        }
        if ($('#email').val().trim() == ''){
            flag = 1;
            message += 'Email is necessary<br/>';
        }
        if ($('#secques').val().trim() == ''){
            flag = 1;
            message += 'Security Question is necessary<br/>';
        }
        if ($('#secans').val().trim() == ''){
            flag = 1;
            message += 'Security Answer is necessary<br/>';
        }
        if(flag == 1){
            if($('#step1 > #message').length == 0)
                $('#step1').html('<div id="message" class="alert alert-block"><button type="button" class="close" data-dismiss="alert">&times;</button>'+message+'</div>'+$('#step1').html())
            else
                $('#step1 > #message').html(message);
        }
        else if($("#step1").css('display') != 'none'){
            $("#step1").slideUp();
            $("#step2").slideDown();
            $("#regbar").animate({
                'width': '38%'
            });
            return;
        }
        else if(flag != 0){
            $("#step1").slideDown();
            $("#step2").slideUp();
            $("#step3").slideUp();
        }
        
        // --------------- STEP 2 ---------------- //
        
        if($('#nick1').val().trim() == ''){
            flag = 1;
            message += 'One nick is necessay<br/>';
        }
        if($('#pass1').val().trim() == ''){
            flag = 1;
            message += 'Password is necessay<br/>';
        }
        if(flag == 1){
            if($('#step2 > #message').length == 0)
                $('#step2').html('<div id="message" class="alert alert-block"><button type="button" class="close" data-dismiss="alert">&times;</button>'+message+'</div>'+$('#step2').html())
            else
                $('#step2 > #message').html(message);
        }
        else if($("#step2").css('display') != 'none'){
            $("#step2").slideUp();
            $("#step3").slideDown();
            $("#regbar").animate({
                'width': '71%'
            });
            $('#next').html('Register');
            return;
        }
        else if(flag != 0){
            $("#step1").slideUp();
            $("#step2").slideDown();
            $("#step3").slideUp();
        }
        
        // ------------------ STEP 3 -------------------- //
        
        if($('#terms').attr('checked') != 'checked'){
            flag = 1;
            message += 'You must agree to Terms and Conditions<br/>';
        }
        if(flag == 1){
            if($('#step3 > #message').length == 0)
                $('#step3').html('<div id="message" class="alert alert-block"><button type="button" class="close" data-dismiss="alert">&times;</button>'+message+'</div>'+$('#step3').html())
            else
                $('#step3 > #message').html(message);
        }
        else{
            $('#form').submit();
        }
    });
});