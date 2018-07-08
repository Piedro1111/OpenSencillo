var ctrok=0,
	ctrer=0;
$(document).ready(function(){
	$('.sencillo-errors-list').hide();
	$('#gpio-all-reset').click(function(){
		$.post(server_name+'/ajax.slot.php',{
			atype:'raspberrypi::gpio::reset::all',
			response:200
		},function(data){
			checkServerStatus();
		});
	});
	$('#shutdown').click(function(){
		$.post(server_name+'/ajax.slot.php',{
			atype:'raspberrypi::shutdown',
			time:'now',
		},function(data){
			checkServerStatus();
		});
	});
	$('#restart').click(function(){
		$.post(server_name+'/ajax.slot.php',{
			atype:'raspberrypi::restart',
			time:'now',
		},function(data){
			checkServerStatus();
		});
	});
	$('select.gpio-in-out').change(function(){
		$.post(server_name+'/ajax.slot.php',{
			atype:'raspberrypi::gpio::out::set',
			gpio:$(this).data('name'),
			set:$(this).val(),
			response:200
		},function(data){
			checkServerStatus();
		});
	});
	$('select.gpio-val').change(function(){
		$.post(server_name+'/ajax.slot.php',{
			atype:'raspberrypi::gpio::val::set',
			gpio:$(this).data('name'),
			set:$(this).val(),
			response:200
		},function(data){
			checkServerStatus();
		});
	});
	$("#send_login").click(function(){
		var email_var = $('#email').val();
		var pass_var = $('#pass').val();
        $.post(server_name+"/ajax.slot.php",{
			atype:'login',
			email:email_var,
			pass:pass_var
		},function(data){
			location.reload();
		});
    });
    $("#send_fgot").click(function(){
		var email_var = $('#email').val();
        $.post(server_name+"/ajax.slot.php",{
			atype:'forgot',
			email:email_var
		},function(data){
			window.open(server_name+'/forgot/password','_self');
		});
    });
    $("#send_newpass").click(function(){
		var code_var = $('#code').val();
		var pass_var = $('#pass').val();
		var rtppass_var = $('#rtppass').val();
        $.post(server_name+"/ajax.slot.php",{
			atype:'newpass',
			fgotcode:code_var,
			eregpass:pass_var,
			eregrtp:rtppass_var
		},function(data){
			window.open(server_name+'/forgot/password/success','_self');
		});
    });
	$("#send_ereg").click(function(){
		var email_var = $('#email').val();
		var pass_var = $('#pass').val();
		var rtppass_var = $('#rtppass').val();
		$('.sencillo-errors-list').hide();
		if(email_var.length>=4)
		{
			if(pass_var.length>=6)
			{
				$.post(server_name+"/ajax.slot.php",{
					atype:'ereg',
					eregemail:email_var,
					eregpass:pass_var,
					eregrtp:rtppass_var
				},function(data){
					var data = JSON.parse(data);
					switch(data.code)
					{
						case 200:
							$('.x_content').html('<div class="alert alert-info alert-dismissible fade in" role="alert">Thank you for registration, this site is private, please wait for acceptance.</div>');
							$('.btn-succes').remove();
						break;
						case 417:
							$('.sencillo-errors-list li').text('Illegal value, try another.');
							$('.sencillo-errors-list').show();
						break;
						case 409:
							$('.sencillo-email-group .sencillo-errors-list li').text('Email is already registered.');
							$('.sencillo-email-group .sencillo-errors-list').show();
						break;
						case 409.1:
							$('.sencillo-rtp-group .sencillo-errors-list li').text('Passwords do not match.');
							$('.sencillo-rtp-group .sencillo-errors-list').show();
						break;
						case 403:
							$('.sencillo-email-group .sencillo-errors-list li').text('Invalid email.');
							$('.sencillo-email-group .sencillo-errors-list').show();
						break;
					}
				});
			}
			else
			{
				console.log('Err - small password!');
				$('.sencillo-pass-group .sencillo-errors-list li').text('Short password.');
				$('.sencillo-pass-group .sencillo-errors-list').show();
			}
		}
		else
		{
			$('.sencillo-email-group .sencillo-errors-list li').text('Short email.');
			$('.sencillo-email-group .sencillo-errors-list').show();
		}
    });
	$(".open-button").click(function(){
		window.open(server_name+'/'+$(this).data('url'),'_self');
    });
	$('#exthdd').change(function(){
		$.post(server_name+'/ajax.slot.php',{
			atype:'switchExtHdd::action',
			action:$('#exthdd').val(),
			response:200
		},function(data){
			//checkServerStatus();
			location.reload();
			console.log(data);
		});
	});
	$('.remove-user').click(function(){
		var userid = $(this).data('user');
		var response = confirm("Remove user "+userid+"?");
		if(response)
		{
			$.post(server_name+'/ajax.slot.php',{
				atype:'removeUser::action',
				user:userid,
				response:response
			},function(data){
				//checkServerStatus();
				location.reload();
			});
		}
	});
	$('.kill-session').click(function(){
		var userid = $(this).data('user');
		var response = confirm("Kill session user "+userid+"?");
		if(response)
		{
			$.post(server_name+'/ajax.slot.php',{
				atype:'killSession::action',
				user:userid,
				response:response
			},function(data){
				//checkServerStatus();
				location.reload();
			});
		}
	});
});
function checkServerStatus()
{
	var t;
	$('#status > span').removeClass('glyphicon-ok glyphicon-warning-sign glyphicon-globe').addClass('glyphicon-globe');
	$.post(server_name+'/ajax.slot.php',{
		atype:'raspberrypi::status'
	},function(data){
		try
		{
			var data = JSON.parse(data);
		}
		catch(e)
		{
			var data = {code:404};
		}
		
		if(data.code==200)
		{
			ctrok++;
			$('#status > span').removeClass('glyphicon-ok glyphicon-warning-sign glyphicon-globe').addClass('glyphicon-ok');
		}
		else
		{
			ctrer++;
			$('#status > span').removeClass('glyphicon-ok glyphicon-warning-sign glyphicon-globe').addClass('glyphicon-warning-sign');
		}
		return data.code;
	}).fail(function() {
		ctrer++;
		$('#status > span').removeClass('glyphicon-ok glyphicon-warning-sign glyphicon-globe').addClass('glyphicon-warning-sign');
	});
	if((ctrok<10)&&(ctrer<30))
	{
		t = setTimeout(checkServerStatus, 3000);
	}
	else
	{
		ctrok=0;
		ctrer=0;
	}
}
