/*
* 	This is the common helper function used throughout the project
*	Created on (15-11-2024)
*
*/

$(document).ready(function(){
	/****ajax add csrf token**/
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });
	

	//custom password validation method
	$.validator.addMethod("pwcheck", function(value) {
	   return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
		   && /[a-z]/.test(value) // has a lowercase letter
		   && /\d/.test(value) // has a digit
	}, 'Must be 8 characters long, must contain letters and numbers');

	// connect it to a css class
	jQuery.validator.addClassRules({
		pwcheck : { pwcheck : true }
	});

	/****validator default setting**/
	jQuery.validator.setDefaults({
		/*onfocusout: function (e) {
			this.element(e);
		},
		onkeyup: false,*/
		onfocusout: false,
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				validator.errorList[0].element.focus();
			}
		},
		errorElement: 'div',
		errorClass: 'invalid-feedback',
		errorPlacement: function (error, element) {
			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "div" ) );
			}else if ( element.prop( "type" ) === "radio" ) {
				error.insertAfter( element.parent( "div" ) );
			}else if ( element.hasClass("select2")) {
				error.insertAfter(element.next('.select2-container'));
			}else if (element.parent().hasClass('input-group')) {
                //error.insertAfter(element.parent());
				$(element).parents('.input-group').append(error);
			} else {
				error.insertAfter( element );
			}
		},
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				validator.errorList[0].element.focus();
			}
		},
		highlight: function (element) {
			if(jQuery(element).hasClass('select2')){
				jQuery(element).closest('.form-group').addClass('is-invalid');
			}else{
				jQuery(element).closest('.form-control').addClass('is-invalid');
			}
			//jQuery(element).closest('.form-control').addClass('is-invalid');
		},
		unhighlight: function (element) {
			if(jQuery(element).hasClass('select2')){
				jQuery(element).closest('.form-group').removeClass('is-invalid');
			}else{
				jQuery(element).closest('.form-control').removeClass('is-invalid');
			}
			//jQuery(element).closest('.form-control').removeClass('is-invalid');
		}
	});
	
})


/*
*
*	Pagination update Page no and call get data
*	params (page no)
*/

var updatePageNo = (page_no) => {
	updateURL('page', page_no);
	/*****After update query params call get data function**********/
	getData();
}

/*
*
*	Update show per page data show and call get data
*	params (obj : select box object)
*/

var updatePerPage = (obj) => {
	updateURL('perpage', $(obj).val());
	/*****After update query params call get data function**********/
	getData();
}

/*
*
*	Data Updated in list intialise date range picker
*
*/

var initaliseDateRangePicker = () => {
	$('.date-range-control').daterangepicker({
		autoUpdateInput: false,
		locale: {
			cancelLabel: 'Clear'
		}
	});

	$('.date-range-control').on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('YYYY-MM-DD') + '/' + picker.endDate.format('YYYY-MM-DD'));
	});

	$('.date-range-control').on('cancel.daterangepicker', function(ev, picker) {
		$(this).val('');
	});

}

/*
*
*	Data Updated in list intialise date picker
*
*/

var initaliseDatePicker = (element_id) => {
	if(element_id !== 'undefined'){
		$('#'+element_id).datetimepicker({ format: 'YYYY-MM-DD' });
	}
	$('.date-selector').datetimepicker({ format: 'YYYY-MM-DD' });
}

/******************Operation Function start*********************/

/*
*
*	Update Status
*
*/

var updateStatus = (obj) => {
	var Data = getAllSelectedCheckBox();
	if(Data == ''){
		toastAlert('warning', 'Please select atleast one record for this operation');
		return false;
	}
	/*******Open new Status Pop Up**********/
	confirmBeforeOperation(obj, Data, selectNewStatus);
}

/*
*
*	Select and Update New Status
*
*/

var selectNewStatus = (obj, Data) => {
	var operation_url  = $(obj).data('operation-link');
	var status_options = $(obj).data('status-options');
	Swal.fire({
		title: 'Select New Status',
		input: 'select',
		inputOptions: status_options,
		inputPlaceholder: 'Required',
		showCancelButton: true,
		inputValidator: function (value) {
		return new Promise(function (resolve, reject) {
			if (value !== '') {
				resolve();
			} else {
				resolve('You need to select a status');
			}
		});
	  }
	}).then(function (result) {
		if (result.isConfirmed) {
			showProcessing();
			$.ajax({
				url : BASE_URL + operation_url,
				type: "POST",
				data: {'items' : Data, 'new_status' : result.value},
			}).done(function (response) {
				hideProcessing();
				if(response.type == 'success'){
					toastAlert(response.type, response.msg);
					/*******Refresh Page Data*********/
					getData();
				}else{
					toastAlert(response.type, response.msg);
				}
			});
		}
	});
}

/*
*
*	Delete Record
*
*/

var deleteRecord = (obj) => {
	var Data = getAllSelectedCheckBox();
	if(Data.length === 'undefined' || Data.length == 0){
		toastAlert('warning', 'Please select atleast one record for this operation');
		return false;
	}
	confirmBeforeOperation(obj, Data, deleteSelectedRecord);
}

/*
*
*	After confirmation Delete Record
*
*/

var deleteSelectedRecord = (obj, Data) => {
	var operation_url  = $(obj).data('operation-link');
	showProcessing();
	$.ajax({
		url : BASE_URL + operation_url,
		type: "POST",
		data: {'items' : Data},
	}).done(function (response) {
		hideProcessing();
		if(response.type == 'success'){
			toastAlert(response.type, response.msg);
			/*******Refresh Page Data*********/
			getData();
		}else{
			toastAlert(response.type, response.msg);
		}
	});
}

/*
*
*	Delete Record
*
*/

var deletePermanentRecord = (obj) => {
	var Data = getAllSelectedCheckBox();
	if(Data.length === 'undefined' || Data.length == 0){
		toastAlert('warning', 'Please select atleast one record for this operation');
		return false;
	}
	confirmBeforeOperation(obj, Data, deletePermanentSelectedRecord);
}

/*
*
*	After confirmation Delete Record
*
*/

var deletePermanentSelectedRecord = (obj, Data) => {
	var operation_url  = $(obj).data('operation-link');
	showProcessing();
	$.ajax({
		url : BASE_URL + operation_url,
		type: "POST",
		data: {'items' : Data},
	}).done(function (response) {
		hideProcessing();
		if(response.type == 'success'){
			toastAlert(response.type, response.msg);
			/*******Refresh Page Data*********/
			getData();
		}else{
			toastAlert(response.type, response.msg);
		}
	});
}

/******************Operation Function end***********************/

/*
*
*	Confirm required before and opearation
*	params(obj, Data, callbackmethod)
*
*/

var confirmBeforeOperation = (obj, Data, callbackMethod) => {
	Swal.fire({
		title: 'Are you sure you need to procced?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Procced!'
	}).then((result) => {
		/* Call the function once user approve to move forward */
		if (result.isConfirmed) {
			callbackMethod(obj, Data);
		}
	})
}

/*
*
*	Confirm required before and opearation
*	params(Data, callbackmethod)
*
*/

var confirmDataOnly = (Data, callbackMethod) => {
	Swal.fire({
		title: 'Are you sure you need to procced?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Procced!'
	}).then((result) => {
		/* Call the function once user approve to move forward */
		if (result.isConfirmed) {
			callbackMethod(Data);
		}
	})
}

/*
*
*	Confirm required before and opearation
*	params(obj, Data, callbackmethod)
*
*/

var confirmFuncationalityEffected = (obj, callbackMethod) => {
	Swal.fire({
		title: 'Moving ahead will effect crm work flow. Are you sure you need to move ahead?',
		icon: 'error',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes!'
	}).then((result) => {
		/* Call the function once user approve to move forward */
		if (result.isConfirmed) {
			callbackMethod(obj);
		}
	})
}

/*
*
*	Show loader for content section
*
*
*/

var showProcessing = () => {
	var container_div = $(document).find('.crm-content-container');
		container_div.addClass('crm-loader-position');
	var loader_html = '<div class="crm-loader" style="" id="loader-icon-div">\
						<div class="content-loader-container text-center">\
							<img src="'+ CONTENT_LOADING +'" alt="loading-content">\
							<span class="ml-2 mr-3">  Loading...</span>\
						</div>\
					</div>';

	container_div.prepend(loader_html);
}

/*
*
*	Show loader for content section
*
*
*/

var hideProcessing = () => {
	var container_div = $(document).find('.crm-content-container');
	container_div.removeClass('crm-loader-position');
	$(document).find('.crm-loader').remove();
}

/*
*
*	Add Button Loader
*
*
*/
var addButtonLoader = (obj) => {
	var spinner_html = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Wait...';
	$(obj).html(spinner_html)
	$(obj).attr("disabled", true);
}

/*
*
*	Remove Button Loader
*
*
*/
var removeButtonLoader = (obj) => {
	$(obj).html('')
	$(obj).html($(obj).data('button-text'));
	$(obj).attr("disabled", false);
}

/*
*
*	Remove List Section Filter
*
*
*/

var removeFilter = () => {
	removeAllParamFromUrl();
	var div = document.getElementById('search_from_fields');
	$(div).find('input:text, input:password, input:file, select, textarea')
	.each(function() {
		//var elem_type = $(this).attr('type')
		//console.log($(this).hasClass('table-filter-select2'))
		$(this).val('')
	});
	$('.custom-select').find('option').attr('selected', '');
	$('.table-filter-select2').val(null).trigger('change');
	$('.deleted-list').addClass('d-none');
	$('.show-deleted').removeClass('d-none');
	$('.delete-item').removeClass('d-none');
	/*****After update query params call get data function**********/
	getData();
}

/*
*
*	Check Value type
*
*
*/

function isValue(value, def, is_return) {
    if ( $.type(value) == 'null'
        || $.type(value) == 'undefined'
        || $.trim(value) == ''
        || ($.type(value) == 'number' && !$.isNumeric(value))
        || ($.type(value) == 'array' && value.length == 0)
        || ($.type(value) == 'object' && $.isEmptyObject(value)) ) {
        return ($.type(def) != 'undefined') ? def : false;
    } else {
        return ($.type(is_return) == 'boolean' && is_return === true ? value : true);
    }
}
