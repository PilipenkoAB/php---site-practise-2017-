$(document).ready(function(){
// ====================================================== //


		$('body').append('<div id="nameInfo" class="info"></div>');
		$('body').append('<div id="emailInfo" class="info"></div>');
		$('body').append('<div id="lastnameInfo" class="info"></div>');
		$('body').append('<div id="usernameInfo" class="info"></div>');
		$('body').append('<div id="passwordInfo" class="info"></div>');
		$('body').append('<div id="passwordcheckInfo" class="info"></div>');
		
var jVal = {

	'FirstName' : function() {
	
		var nameInfo = $('#nameInfo');
		var ele = $('#FirstName');
		var pos = ele.offset();
		
		nameInfo.css({
			top: pos.top-3,
			left: pos.left+ele.width()+15
		});
		
		var patt = /^[а-я]{1,}$/i;
		
		if(!patt.test(ele.val())) {
			jVal.errors = true;
				nameInfo.removeClass('correct').addClass('error').html('&larr; Заполните поле(только Русские буквы)').show();
				ele.removeClass('normal').addClass('wrong');				
		} else {
				nameInfo.removeClass('error').addClass('correct').html('&radic;').show();
				ele.removeClass('wrong').addClass('normal');
		}
	},
	
	'LastName' : function() {
	
		var lastnameInfo = $('#lastnameInfo');
		var ele = $('#LastName');
		var pos = ele.offset();
		
		lastnameInfo.css({
			top: pos.top-3,
			left: pos.left+ele.width()+15
		});
		
		var patt = /^[а-я]{1,}$/i;
		
		if(!patt.test(ele.val())) {
			jVal.errors = true;
				lastnameInfo.removeClass('correct').addClass('error').html('&larr; Заполните поле(только Русские буквы)').show();
				ele.removeClass('normal').addClass('wrong');				
		} else {
				lastnameInfo.removeClass('error').addClass('correct').html('&radic;').show();
				ele.removeClass('wrong').addClass('normal');
		}
	},
	
	'Username' : function() {
	
	    var Username;
		Username =  $("#Username").val();
	
		var usernameInfo = $('#usernameInfo');
		var ele = $('#Username');
		var pos = ele.offset();


		
		usernameInfo.css({
			top: pos.top-3,
			left: pos.left+ele.width()+15
		});
		
		var patt = /^[0-9-a-z-_]{4,}$/i;
		
		if(!patt.test(ele.val())) {
				jVal.errors = true;
				usernameInfo.removeClass('correct').addClass('error').html('&larr; как минимум 4 символа(латиница,цифры,нижнее подчеркивание!').show();
				ele.removeClass('normal').addClass('wrong');
				
		} else {
		$.ajax({
		url: "valid_f.php",
		type: "GET",
		data: "Username=" + Username,
		cache: false,
		success: function(response){
		if(response == "1"){


			usernameInfo.removeClass('correct').addClass('error').html('&larr; Такой пользователь уже существует, выберите другой Логин').show();
			ele.removeClass('normal').addClass('wrong');
					$("#send").block({ message: "Processing…" });
		}else{
				usernameInfo.removeClass('error').addClass('correct').html('&radic;').show();
				ele.removeClass('wrong').addClass('normal');
					}
				}
			});
			}
	},
	
	'Email' : function() {
        var a='';;
	    var Email;
		Email =  $("#Email").val();
	
		var emailInfo = $('#emailInfo');
		var ele = $('#Email');
		var pos = ele.offset();
		
		emailInfo.css({
			top: pos.top-3,
			left: pos.left+ele.width()+15
		});
		
		var patt = /^[0-9-a-z._\-].+@.+[0-9-a-z-.][.].[a-z]{1,}$/i;
		
		if(!patt.test(ele.val())) {
			jVal.errors = true;
				emailInfo.removeClass('correct').addClass('error').html('&larr; Введите email в виде xxx@xxx.xx').show();
				ele.removeClass('normal').addClass('wrong');					
		} else {
		$.ajax({
		url: "valid_f.php",
		type: "GET",
		data: "Email=" + Email,
		cache: false,
		success: function(response){
		if(response == "1"){
			emailInfo.removeClass('correct').addClass('error').html('&larr; Такой почтовый ящик уже используется, выберите другой.').show();
			ele.removeClass('normal').addClass('wrong');
		}else{		
				emailInfo.removeClass('error').addClass('correct').html('&radic;').show();
				ele.removeClass('wrong').addClass('normal');
					}
			
				}
			
			});
			
		}
         
	},
	
	
	'Password' : function() {
	
		var passwordInfo = $('#passwordInfo');
		var ele = $('#Password');
		var pos = ele.offset();
		
		passwordInfo.css({
			top: pos.top-3,
			left: pos.left+ele.width()+15
		});
		
		var patt = /^[0-9-a-z]{8,}$/i;
		
		if(!patt.test(ele.val())) {
			jVal.errors = true;
				passwordInfo.removeClass('correct').addClass('error').html('&larr; как минимум 8 символов! Допустимы только латиница и цифры').show();
				ele.removeClass('normal').addClass('wrong');				
		} else {
				passwordInfo.removeClass('error').addClass('correct').html('&radic;').show();
				ele.removeClass('wrong').addClass('normal');
		}
	},
	
	
	'Password_check' : function() {
			
	    var PassCheck_1 = $('#Password').val();
		var PassCheck_2 = $('#Password_check').val();
		
		var passwordcheckInfo = $('#passwordcheckInfo');
		var ele = $('#Password_check');
		var pos = ele.offset();
		
		passwordcheckInfo.css({
			top: pos.top-3,
			left: pos.left+ele.width()+15
		});
		
		var patt = /^[0-9-a-z]{8,}$/i;
		
		if(!patt.test(ele.val())) {
			jVal.errors = true;
				passwordcheckInfo.removeClass('correct').addClass('error').html('&larr; как минимум 8 символов!Допустимы только латиница и цифры').show();
				ele.removeClass('normal').addClass('wrong');				
		} else {
		
		if (PassCheck_1 != PassCheck_2) {
		jVal.errors = true;
				passwordcheckInfo.removeClass('correct').addClass('error').html('&larr; Пароли не совпадают!').show();
				ele.removeClass('normal').addClass('wrong');
		} else {
				passwordcheckInfo.removeClass('error').addClass('correct').html('&radic;').show();
				ele.removeClass('wrong').addClass('normal');
				}
		}
	},
	
	'sendIt' : function (){
		if(!jVal.errors) {
			$('#signup').submit();
		}
	}
};

// ====================================================== //

$('#send').click(function (){
	var obj = $.browser.webkit ? $('body') : $('html');
	obj.animate({ scrollTop: $('#signup').offset().top }, 750, function (){
		jVal.errors = false;
		jVal.FirstName();
		jVal.LastName();
		jVal.Username();
		jVal.Email();
		jVal.Password();
		jVal.Password_check();
		jVal.sendIt();

	});
	return false;
});

$('#FirstName').change(jVal.FirstName);
$('#LastName').change(jVal.LastName);
$('#Email').change(jVal.Email);
$('#Username').change(jVal.Username);
$('#Password').change(jVal.Password);
$('#Password_check').change(jVal.Password_check);

// ====================================================== //
});