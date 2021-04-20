$(document).ready(function(){
// ====================================================== //
		$('body').append('<div id="nameInfo" class="info"></div>');
		$('body').append('<div id="emailInfo" class="info"></div>');
		$('body').append('<div id="lastnameInfo" class="info"></div>');
		
var jVal = {

	'FirstNameNew' : function() {
	
		var nameInfo = $('#nameInfo');
		var ele = $('#FirstNameNew');
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
	
	'LastNameNew' : function() {
	
		var lastnameInfo = $('#lastnameInfo');
		var ele = $('#LastNameNew');
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
	
	
	
	'EmailNew' : function() {
	
	    var EmailNew;
		EmailNew =  $("#EmailNew").val();
	
		var emailInfo = $('#emailInfo');
		var ele = $('#EmailNew');
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
		url: "valid_f_profile.php",
		type: "GET",
		data: "EmailNew=" + EmailNew,
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
	
	
		
	'sendIt' : function (){
		if(!jVal.errors) {
			$('#profile').submit();
		}
	}
};

// ====================================================== //

$('#send').click(function (){
	var obj = $.browser.webkit ? $('body') : $('html');
	obj.animate({ scrollTop: $('#profile').offset().top }, 750, function (){
		jVal.errors = false;
		jVal.FirstNameNew();
		jVal.LastNameNew();
		jVal.EmailNew();
		jVal.sendIt();
	});
	return false;
});

$('#FirstNameNew').change(jVal.FirstNameNew);
$('#LastNameNew').change(jVal.LastNameNew);
$('#EmailNew').change(jVal.EmailNew);



// ====================================================== //
});