"use strict";

var FilterStatus = function() {
    
	var select2Handler = function() {

		$('#StatusSensus').select2({
			placeholder: '--- Pilih Status Pendataan ---',
            minimumResultsForSearch: -1,
			// allowClear: true
		})
        
		$('#Kelurahan').select2({
            placeholder: '--- Pilih Kelurahan ---',
            allowClear: true
        })
        .on('change', function() {
            getRW($(this).val());
        })
        .trigger('change');

		$('#RW').select2({
            placeholder: '--- Pilih RW ---',
            allowClear: true
        })
        .on('change', function() {
            getRT($(this).val());
        });

		$('#RT').select2({
            placeholder: '--- Pilih RT ---',
            allowClear: true
        })

		$('#Pendata').select2({
			// placeholder: '--- Pilih Pendata ---',
			// allowClear: true
		})

		$('#JenisData').select2({
			placeholder: '--- Pilih Jenis Data ---',
            minimumResultsForSearch: -1,
			// allowClear: true
		})
        .on('change', function() {
            if ($(this).val()==1) {
                $('.div-wilayah').show();
                $('.div-pendata').hide();
            } else {
                $('.div-wilayah').hide();
                $('.div-pendata').show();
            }
        }).trigger('change');

		$('#PeriodeSensus').select2({
			placeholder: '--- Pilih Periode Pendataan ---',
            minimumResultsForSearch: -1,
			// allowClear: true
		})

	}

	var getRW = function(id_kelurahan) {
		if (id_kelurahan == '') {
			var el = $('#RW');
			el.children().remove();
			el.append($("<option></option>").attr("value", '').text('--- Select Item ---'));
			el.trigger('change');
			return false;
		}
        
		KTApp.blockPage({
			message: 'Harap tunggu...'
		});

		var xhr = $.ajax({
				url: base_url + '/wilayah/rw/' + id_kelurahan,
				method: 'GET',
				dataType: 'json',
        })
        .done(function(response) {
            var el = $('#RW');
            el.children().remove();
            el.append($("<option></option>").attr("value", '').text('--- Select Item ---'));
            $.each(response, function(key, value) {
                el.append($("<option></option>").attr("value", value.id).text(value.text));
            });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('jqXHR', jqXHR);
            toastr.error('getRW: ' + jqXHR.statusText);
        })
        .always(function() {
            KTApp.unblockPage();
        });
	}

	var getRT = function(id_rw) {
		if (id_rw == '') {
			var el = $('#RT');
			el.children().remove();
			el.append($("<option></option>").attr("value", '').text('--- Select Item ---'));
			el.trigger('change');
			return false;
		}
        
		KTApp.blockPage({
			message: 'Harap tunggu...'
		});

		var xhr = $.ajax({
				url: base_url + '/wilayah/rt/' + id_rw,
				method: 'GET',
				dataType: 'json',
        })
        .done(function(response) {
            var el = $('#RT');
            el.children().remove();
            el.append($("<option></option>").attr("value", '').text('--- Select Item ---'));
            $.each(response, function(key, value) {
                el.append($("<option></option>").attr("value", value.id).text(value.text));
            });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('jqXHR', jqXHR);
            toastr.error('getRT: ' + jqXHR.statusText);
        })
        .always(function() {
            KTApp.unblockPage();
        });
	}
    
	return {
		init: function() {
            select2Handler();
		}
	};
}();

jQuery(document).ready(function() {
	FilterStatus.init();
});