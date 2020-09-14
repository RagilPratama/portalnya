"use strict";

var IndikatorProses = function() {
	
	var PeriodeHandler = function() {
		
		$('#PeriodeSensus').select2({
            placeholder: '--- Pilih Periode ---',
        }).on('change', function(e) {
			if ($(this).val()!='') {
				getDataIndikator();
			}
		});
		
		var opt = $('#PeriodeSensus option:not([value=""])');
		if (opt.length==1) {
			$('#PeriodeSensus').val(opt.first().val()).trigger('change');
		}

		function getDataIndikator() {

			var param = {
				PeriodeSensus: $('#PeriodeSensus').val()
			};

            KTApp.blockPage({
                message: 'Harap tunggu...'
			});
			
			var xhr = $.ajax({
				url: base_url + '/indikator/data',
				method: 'GET',
				dataType: 'json',
				data: param,
			})
			.done(function(response) {
				response.forEach(function(row) {
					if (row.ind_type=='IndSarpras') {
					
						$('input[name="'+row.ind_type+'['+row.ind_code+']'+'[pengadaan]"]').prop('checked', row.pengadaan);
						$('input[name="'+row.ind_type+'['+row.ind_code+']'+'[distribusi]"]').prop('checked', row.distribusi);
					
					} else if (row.ind_type=='IndPelatihan') {
					
						$('input[name="'+row.ind_type+'['+row.ind_code+']'+'[status_proses]"]').prop('checked', row.status_proses);
						$('input[name="'+row.ind_type+'['+row.ind_code+']'+'[jml_peserta]"]').val(row.status_proses ? row.jml_peserta : '');
					
					} else if (row.ind_type=='IndKelengkapan') {
					
						$('input[name="'+row.ind_type+'['+row.ind_code+']'+'[status_proses]"]').prop('checked', row.status_proses);
					
					}

				})
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log(jqXHR);
				// toastr.error('getRole: ' + jqXHR.statusText);
			})
			.always(function() {
				KTApp.unblockPage();
			});
		}
	}

	var formUpdate = function() {
		$('#btnUpdate').click(function(e) {
            e.preventDefault();
            var param = $('#formIndikator').serializeObject();

            if (param.PeriodeSensus=='') {
                swal.fire('', 'Periode Pendataan belum dipilih', 'error');
                return false;
            }

            KTApp.blockPage({
                message: 'Harap tunggu...'
            });

			var header = {
				'Accept': 'application/json',
				'X-CSRF-TOKEN': csrf_token,
			};

			var xhr = $.ajax({
					url: base_url + '/indikator/update',
					method: 'POST',
					dataType: 'json',
					headers: header,
					data: param
				})
				.done(function(response) {
					var message = (response.message) ? response.message : '';
					if (response.status) {
						toastr.success(message);
					} else {
						message = (message !== '') ? message : 'Ada kesalahan';
						swal.fire('', message, 'error');
					}
				}).fail(function(jqXHR, textStatus, errorThrown) {
					var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
					// swal.fire('', message, 'error');
				}).always(function() {
					KTApp.unblockPage();
				});
		});
    }
    
	return {
		init: function() {
            formUpdate();
            PeriodeHandler();
		}
	};
}();

jQuery(document).ready(function() {
	IndikatorProses.init();
});