$(document).ready(function(){
// ====================================================== //

		$('body').append('<div id="usernameInfo" class="info"></div>');
		$('body').append('<div id="passwordInfo" class="info"></div>');
		
var jVal = {

	
	'Username' : function() {
		  

	
		var usernameInfo = $('#usernameInfo');
		var ele = $('#Username');
		var pos = ele.offset();


		
		usernameInfo.css({
			top: pos.top-3,
			left: pos.left+ele.width()+15
		});
		
		
		if(ele.val().length < 1) {
			jVal.errors = true;
				usernameInfo.removeClass('correct').addClass('error').html('&larr; Это поле должно быть заполненно').show();
				ele.removeClass('normal').addClass('wrong');				
		} else {
				usernameInfo.removeClass('error').addClass('correct').html('&radic;').show();
				ele.removeClass('wrong').addClass('normal');
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
		
		
        if(ele.val().length < 1) {
			jVal.errors = true;
				passwordInfo.removeClass('correct').addClass('error').html('&larr; Это поле должно быть заполненно').show();
				ele.removeClass('normal').addClass('wrong');				
		} else {
			passwordInfo.removeClass('error').addClass('correct').html('&radic;').show();
				ele.removeClass('wrong').addClass('normal');
		}
	},
	
	

	
	'sendIt' : function (){
		if(!jVal.errors) {
			$('#login').submit();
		}
	}
};

// ====================================================== //

$('#send').click(function (){
	var obj = $.browser.webkit ? $('body') : $('html');
	obj.animate({ scrollTop: $('#login').offset().top }, 750, function (){
		jVal.errors = false;
		jVal.Username();
		jVal.Password();
		jVal.sendIt();

	});
	return false;
});

$('#Username').change(jVal.Username);
$('#Password').change(jVal.Password);


// ====================================================== //
});