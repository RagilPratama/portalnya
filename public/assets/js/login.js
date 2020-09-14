"use strict";

// Class Definition
var KTLoginV1 = function () {
	var login = $('#kt_login');
	var otpnumber;
	var ids;

	toastr.options = {
			  "closeButton": false,
			  "debug": true,
			  "newestOnTop": true,
			  "progressBar": false,
			  "positionClass": "toast-top-right",
			  "preventDuplicates": true,
			  "onclick": null,
			  "showDuration": "300",
			  "hideDuration": "1000",
			  "timeOut": "3000",
			  "extendedTimeOut": "1000",
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut"
			};

	var showErrorMsg = function(form, type, msg) {
        var alert = $('<div class="alert alert-bold alert-solid-' + type + ' alert-dismissible" role="alert">\
			<div class="alert-text">'+msg+'</div>\
			<div class="alert-close">\
                <i class="flaticon2-cross kt-icon-sm" data-dismiss="alert"></i>\
            </div>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form);
        KTUtil.animateClass(alert[0], 'fadeIn animated');
    }

	// Private Functions
	var handleSignInFormSubmit = function () {
		$('#kt_login_signin_submit').click(function (e) {
			e.preventDefault();

			var btn = $(this);
			var form = $('#kt_login_form');

			form.validate({
				rules: {
					username: {
						required: true
					},
					password: {
						required: true
					}
				}
			});

			if (!form.valid()) {
				return;
			}

			KTApp.progress(btn[0]);

			setTimeout(function () {
				KTApp.unprogress(btn[0]);
			}, 2000);

			// ajax form submit:  http://jquery.malsup.com/form/
			form.ajaxSubmit({
				url: '',
				success: function (response, status, xhr, $form) {
					// similate 2s delay
					setTimeout(function () {
						KTApp.unprogress(btn[0]);
						showErrorMsg(form, 'danger', 'Incorrect username or password. Please try again.');
					}, 2000);
				}
			});
		});
	}


	var btnSendEmail = function () {
		$('#btnSendEmail').click(function (e) {
			e.preventDefault();
            
			var username = $('#username').val();			

			if ($('#username').val().length  != 0 ) {
				var header = {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf_token,
                     };
		
				var xhr = $.ajax({
					url: base_url + '/checkMobileNumber/'+ $('#username').val(),
					method: 'POST',
					dataType: 'json',
					headers: header,
					//data: param
				})
				.done(function(response) {
					var message = (response.message) ? response.message : '';
					if (response.status) {
						//toastr.info(message);
						//console.log(response);
						formClear();			
						$('#bannerformmodal').modal('hide');			
						$('#modalUserVerifikasiOtp').modal();
						$('#modalUserVerifikasiOtp #OTPMsg').html(response.message);
						genOTP();
					} else {
						message = (message !== '') ? message : 'Ada kesalahan';
						swal.fire('', message, 'error');
					}
				}).fail(function(jqXHR, textStatus, errorThrown) {
					var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
					let timerOn = false;
					swal.fire('', message, 'error');
				}).always(function() {
					KTApp.unblock('#modalResetCreate');
					let timerOn = false;
				});			

			} else { toastr.error('Username is Empty !!!'); }


		});
	}

	var btnVerifikasiOtp = function() {
		$('#btnVerifikasiOtp').click(function(e) {

			var header = {
		 	'Accept': 'application/json',
		 	'X-CSRF-TOKEN': csrf_token,
		 	};
			
			if ($('#modalUserVerifikasiOtp #otp').val().length  != 0) {	

				var otp = $('#modalUserVerifikasiOtp #otp').val();

				var xhr = $.ajax({
					url: base_url + '/verifikasiOTP/'+otp,
					method: 'POST',
					dataType: 'json',
					headers: header,
					//data: param
				})
				.done(function(response) {
					var message = (response.message) ? response.message : '';
					if (response.status) {
						toastr.success(message);						
						ids = response.data.ID;											
						formClear();										
						$('#formPasswordReset #ID').val(ids);
						$('#modalUserVerifikasiOtp').modal('hide');
						$('#modalResetCreate').modal();
					} else {
						message = (message !== '') ? message : 'Ada kesalahan';
						swal.fire('', message, 'error');
					}
				}).fail(function(jqXHR, textStatus, errorThrown) {
					var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;					
					swal.fire('', message, 'error');
				}).always(function() {
					KTApp.unblock('#modalResetCreate');					
				});


			} else {
				toastr.error('OTP Value is Empty !');
			}
			//let timerOn = false;
		});
	}

	function genOTP() {

		 var header = {
		 	'Accept': 'application/json',
		 	'X-CSRF-TOKEN': csrf_token,
		 };

		var xhr = $.ajax({
					url: base_url + '/generateOTP',
					method: 'POST',
					dataType: 'json',
					headers: header,
					//data: param
				})
				.done(function(response) {
					var message = (response.message) ? response.message : '';
					if (response.status) {
						toastr.info(message);
						console.log(response.data.OTP);																	
						ids = response.data.ID;						
						timer(60);
					} else {
						message = (message !== '') ? message : 'Ada kesalahan';
						swal.fire('', message, 'error');
					}
				}).fail(function(jqXHR, textStatus, errorThrown) {
					var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
					let timerOn = false;
					swal.fire('', message, 'error');
				}).always(function() {
					KTApp.unblock('#modalResetCreate');
					let timerOn = false;
				});		
	}

	var formReset = function() {
		$('#btnResetSave').click(function(e) {
			e.preventDefault();			

			KTApp.block('#modalResetCreate', {
				message: 'Harap tunggu...'
			});

			var disabled = $('#modalResetCreate').find(':input:disabled').removeAttr('disabled');

			var id = $('#formPasswordReset #ID').val();
			var param = $('#formPasswordReset').serializeObject()
			disabled.attr('disabled', 'disabled');

			var header = {
				'Accept': 'application/json',
				'X-CSRF-TOKEN': csrf_token,
			};

			var xhr = $.ajax({
					url: base_url + '/forgotPassword/' + ids,
					method: 'PUT',
					dataType: 'json',
					headers: header,
					data: param
				})
				.done(function(response) {
					var message = (response.message) ? response.message : '';
					if (response.status) {
						toastr.options.onHidden = formClear;
						toastr.success(message);
						$('#modalResetCreate').modal('hide');
					} else {
						message = (message !== '') ? message : 'Ada kesalahan';
						swal.fire('', message, 'error');
					}
				}).fail(function(jqXHR, textStatus, errorThrown) {
					var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
					swal.fire('', message, 'error');
				}).always(function() {
					KTApp.unblock('#modalResetCreate');
				});
		});
	}

	var btnSendOtp = function() {
		$('#btnResendOtp').click(function(e) {
			// Re-generate OTP 
			genOTP();
			document.getElementById("btnResendOtp").disabled = true; 
		});
	}

	let timerOn = true;

	function timer(remaining) {	  
	  	  var m = Math.floor(remaining / 60);
		  var s = remaining % 60;
		  
		  m = m < 10 ? '0' + m : m;
		  s = s < 10 ? '0' + s : s;
		  document.getElementById('timer').innerHTML = m + ':' + s;
		  remaining -= 1;
		  
		  if(remaining >= 0 && timerOn) {
		    setTimeout(function() {
		        timer(remaining);
		    }, 1000);
		    return;
		  }

		  if(!timerOn) {
		    // Do validate stuff here
		    return;
		  }
		  
		  // Do timeout stuff here		  
		  otpnumber = null;
		  document.getElementById("btnResendOtp").disabled = false;  
	}


	var formClear = function() {
			$('#bannerformmodal, #modalUserVerifikasiOtp').modal('hide');
	}
    
    var modalClearForm = function() {
		$('#bannerformmodal, #modalUserVerifikasiOtp, #modalResetCreate').on('shown.bs.modal', function(e) {
			$(this).find('input').val('');
		});
	}

	// Public Functions
	return {
		// public functions
		init: function () {
			handleSignInFormSubmit();
			btnSendEmail();
			btnVerifikasiOtp();
			btnSendOtp();
			formReset();
			modalClearForm();
		}
	};
}();

// Class Initialization
jQuery(document).ready(function () {
	KTLoginV1.init();
});
