$(document).ready(function(){
	
})

function toastAlert(msg_type, message, msg_position='', close_time='', auto_close=true)
{
	Swal.fire({
		toast: true,
		icon: msg_type,
		title: message,
		position: 'top-right',
		showConfirmButton: false,
		timer: 10000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.addEventListener('mouseenter', Swal.stopTimer)
			toast.addEventListener('mouseleave', Swal.resumeTimer)
		}
	})
}

/*function toasterAlert(msg_type, msg_position='', message, close_time='', auto_close=true)
{
	//toastr.clear();
	//console.log(msg_position);
	close_time = close_time == '' ? '10000' : close_time; 
	msg_type = (typeof msg_type === 'undefined') ? 'info' : msg_type;
    toastr.options.positionClass = msg_position == '' ? 'toast-top-right' : msg_position;
    toastr.options.timeOut = auto_close == true ? close_time : '0';
    toastr[msg_type](message);
}*/

function bootstrapAlert(message_object, type='json')
{
	console.log('message_object', message_object)
	var message = type == 'json' ? createJsonMesageHTML(message_object) : '';
	$('#validation_msg_div').html('');
	$('#validation_msg_div').html(message);
	$('#validation_msg_div').removeClass('d-none').addClass('d-block');
}

function createJsonMesageHTML(message_object)
{
	return '<div class="alert alert-danger alert-dismissible fade show" role="alert">\
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>\
				<ul>' + createMessageListFromJson(message_object) + '</ul>\
		   </div>';
}

function createMessageListFromJson(json_message)
{
	var msg_list = '';
	$.each( json_message, (i,v) => {
		msg_list += '<li>'+ v +'</li>';
	});
	return msg_list;
}