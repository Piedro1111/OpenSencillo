var ctrok=0,
	ctrer=0;
$(document).ready(function(){
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