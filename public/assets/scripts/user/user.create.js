var UserCreate = function () {
    

    var select2Handler = function () {

        $('#RoleID').select2({
            placeholder: '--- Pilih Role ---',
        }).on('change', function (e) {
            if ($(this).val() == 4) {
                $('#smartcheck').removeClass('kt-hidden');
            } else {
                $('#smartcheck').addClass('kt-hidden');
            }
            getTKWilayah();
        });

        $('#TingkatWilayahID').select2({
            placeholder: '--- Pilih Tingkat Wilayah ---',
        }).on('change', function() {
            showWil($(this).val());
        });//.trigger('change');

        $('#Provinsi').select2({
            placeholder: '--- Semua Wilayah ---',
        }).on('change', function() {
            if ($('div.divwil[data-tk="2"]').is(":visible")) {
                getAvKabupaten($(this).val());
            }
        });

        $('#Kabupaten').select2({
            placeholder: '--- Semua Wilayah ---',
        }).on('change', function() {
            if ($('div.divwil[data-tk="3"]').is(":visible")) {
                getAvKecamatan($(this).val());
            }
        });

        $('#Kecamatan').select2({
            placeholder: '--- Semua Wilayah ---',
        }).on('change', function() {
            if ($('div.divwil[data-tk="4"]').is(":visible")) {
                getAvKelurahan($(this).val());
            }
        });

        $('#Kelurahan').select2({
            placeholder: '--- Semua Wilayah ---',
        }).on('change', function() {
            if ($('div.divwil[data-tk="5"]').is(":visible")) {
                getAvRW($(this).val());
            }
        });//.trigger('change');

		$('#RW').select2({
            placeholder: '--- Semua Wilayah ---',
        }).on('change', function() {
            if ($('div.divwil[data-tk="6"]').is(":visible")) {
                getAvRT($(this).val());
            }
        });

		$('#RT').select2({
            placeholder: '--- Semua Wilayah ---',
            multiple: true,
        });
    }

    var getTKWilayah = function () {
        var roleid = $('#RoleID').val();
        var roletk = JSON.parse($('#roletk_json').val());
        for(var i = 0; i < roletk.length; i++) {
            // console.log(roletk[i]);
            if (roletk[i].ID==roleid){
                var tkwilayah = roletk[i].tkwilayah;
                var el = $('#TingkatWilayahID');
                el.children().remove();
                el.append($("<option></option>").attr("value", '').text(''));
                $.each(tkwilayah, function (key, value) {
                    el.append($("<option></option>").attr("value", value.ID).text(value.TingkatWilayah));
                });
                if (tkwilayah.length==1) {
                    el.val(tkwilayah[0].ID);
                    el.trigger('change');
                }
            }
        }
    }

    var getWilayah = function(tkwilayah,parentid)
    {
        var urlwil = '';
        if (parentid == null || parentid == '') parentid=0;
        console.log(tkwilayah);
        switch (tkwilayah)
        {
            case 1:
                urlwil = base_url + '/wilayah/avprovinsi/'+parentid;
                break;
            case 2:
                urlwil = base_url + '/wilayah/avkabupaten/'+parentid;
                break;
            case 3:
                urlwil = base_url + '/wilayah/avkecamatan/'+parentid;
                break;
        }
        // console.log(urlwil);return false;
        var xhr = $.ajax({
            url: urlwil,
            method: 'GET',
            dataType: 'json',
        })
        .done(function(response) {
            var el = $('div.divwil[data-tk="'+tkwilayah+'"]').find('select');
            el.children().remove();
            el.append($("<option></option>").attr("value", '').text(''));
            $.each(response, function(key, value) {
                el.append($("<option></option>").attr("value", value.id).text(value.text));
            });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('jqXHR', jqXHR);
            // toastr.error('getRW: ' + jqXHR.statusText);
        })
        .always(function() {
            KTApp.unblockPage();
        });
    }

    var showWil = function(tkwilayah) {
        var usertkwilayah = parseInt($('#usertkwilayah').val());
        $('div.divwil').addClass('kt-hidden');
        for (var i=usertkwilayah+1; i<=tkwilayah; i++)
        {
            $('div.divwil[data-tk="'+i+'"]').removeClass('kt-hidden');
        }

        getWilayah(usertkwilayah+1);
        // switch (tkwilayah)
        // {
        //     case "1":
        //         $('#Provinsi').parent().parent().removeClass('kt-hidden');
        //         break;

        //     case "2":
        //         $('#Kelurahan').parent().parent().removeClass('kt-hidden');
        //         break;

        //     case "3":
        //         break;

        //     case "4":
        //         break;

        //     case "5":
        //         break;

        //     case "6":
        //         break;
        // } 
        // console.log(tkwilayah)
        // $('#Kelurahan').val('').trigger('change');
        // $('#Kelurahan').attr('disabled','disabled');
        // $('#RW').attr('disabled','disabled');
        // $('#RT').attr('disabled','disabled');
        // if (tkwilayah==4){
        //     $('#Kelurahan').removeAttr('disabled');
        //     $('#RW').attr('disabled','disabled');
        //     $('#RT').attr('disabled','disabled');
        // } else if (tkwilayah==5){
        //     $('#Kelurahan').removeAttr('disabled');
        //     $('#RW').removeAttr('disabled');
        //     $('#RT').attr('disabled','disabled');
        // } else if (tkwilayah==6){
        //     $('#Kelurahan').removeAttr('disabled');
        //     $('#RW').removeAttr('disabled');
        //     $('#RT').removeAttr('disabled');
        // }
        
        // $('#Kelurahan').parent().parent().addClass('kt-hidden');
        // $('#RW').parent().parent().addClass('kt-hidden');
        // $('#RT').parent().parent().addClass('kt-hidden');
        // if (tkwilayah==4){
        //     $('#Kelurahan').parent().parent().removeClass('kt-hidden');
        //     $('#RW').parent().parent().addClass('kt-hidden');
        //     $('#RT').parent().parent().addClass('kt-hidden');
        // } else if (tkwilayah==5){
        //     $('#Kelurahan').parent().parent().removeClass('kt-hidden');
        //     $('#RW').parent().parent().removeClass('kt-hidden');
        //     $('#RT').parent().parent().addClass('kt-hidden');
        // } else if (tkwilayah==6){
        //     $('#Kelurahan').parent().parent().removeClass('kt-hidden');
        //     $('#RW').parent().parent().removeClass('kt-hidden');
        //     $('#RT').parent().parent().removeClass('kt-hidden');
        // }
        
	}

    var getAvKabupaten = function(parentid) {
		if (parentid == '') {
			var el = $('#Kabupaten');
			el.children().remove();
			el.append($("<option></option>").attr("value", '').text(''));
			el.trigger('change');
			return false;
		}
        
		KTApp.blockPage({
			message: 'Harap tunggu...'
		});

		var xhr = $.ajax({
				url: base_url + '/wilayah/avkabupaten/' + parentid,
				method: 'GET',
				dataType: 'json',
        })
        .done(function(response) {
            var el = $('#Kabupaten');
            el.children().remove();
            el.append($("<option></option>").attr("value", '').text(''));
            $.each(response, function(key, value) {
                el.append($("<option></option>").attr("value", value.id).text(value.text));
            });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('jqXHR', jqXHR);
            toastr.error('getKabupaten: ' + jqXHR.statusText);
        })
        .always(function() {
            KTApp.unblockPage();
        });
	}

    var getAvKecamatan = function(parentid) {
		if (parentid == '') {
			var el = $('#Kecamatan');
			el.children().remove();
			el.append($("<option></option>").attr("value", '').text(''));
			el.trigger('change');
			return false;
		}
        
		KTApp.blockPage({
			message: 'Harap tunggu...'
		});

		var xhr = $.ajax({
				url: base_url + '/wilayah/avkecamatan/' + parentid,
				method: 'GET',
				dataType: 'json',
        })
        .done(function(response) {
            var el = $('#Kecamatan');
            el.children().remove();
            el.append($("<option></option>").attr("value", '').text(''));
            $.each(response, function(key, value) {
                el.append($("<option></option>").attr("value", value.id).text(value.text));
            });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('jqXHR', jqXHR);
            toastr.error('getKecamatan: ' + jqXHR.statusText);
        })
        .always(function() {
            KTApp.unblockPage();
        });
	}

    var getAvKelurahan = function(parentid) {
		if (parentid == '') {
			var el = $('#Kelurahan');
			el.children().remove();
			el.append($("<option></option>").attr("value", '').text(''));
			el.trigger('change');
			return false;
		}
        
		KTApp.blockPage({
			message: 'Harap tunggu...'
		});

		var xhr = $.ajax({
				url: base_url + '/wilayah/avkelurahan/' + parentid,
				method: 'GET',
				dataType: 'json',
        })
        .done(function(response) {
            var el = $('#Kelurahan');
            el.children().remove();
            el.append($("<option></option>").attr("value", '').text(''));
            $.each(response, function(key, value) {
                el.append($("<option></option>").attr("value", value.id).text(value.text));
            });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.log('jqXHR', jqXHR);
            toastr.error('getKelurahan: ' + jqXHR.statusText);
        })
        .always(function() {
            KTApp.unblockPage();
        });
	}

    var getAvRW = function(id_kelurahan) {
		if (id_kelurahan == '') {
			var el = $('#RW');
			el.children().remove();
			el.append($("<option></option>").attr("value", '').text(''));
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
            el.append($("<option></option>").attr("value", '').text(''));
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

	var getAvRT = function(id_rw) {
		if (id_rw == '') {
			var el = $('#RT');
			el.children().remove();
			el.append($("<option></option>").attr("value", '').text(''));
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
            el.append($("<option></option>").attr("value", '').text(''));
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

    var formSave = function () {
        $('#btnSave').click(function (e) {
            e.preventDefault();
            // KTApp.blockPage({
            //     message: 'Harap tunggu...'
            // });
            var disabled = $('#formUserCreate').find('[type=button]');
            disabled.attr('disabled', 'disabled');

            var param = $('#formUserCreate').serializeObject();
            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };

            var xhr = $.ajax({
                    url: base_url + '/user/store2',
                    method: 'POST',
                    dataType: 'json',
                    headers: header,
                    data: param
                })
                .done(function (response) {
                    var message = (response.message) ? response.message : '';
                    if (response.status) {
                        // toastr.options.onHidden = formClear;
                        toastr.success(message);
                    } else {
                        message = (message !== '') ? message : 'Ada kesalahan';
                        swal.fire('', message, 'error');
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
                    swal.fire('', message, 'error');
                }).always(function () {
                    KTApp.unblockPage();
                    disabled.removeAttr('disabled');
                });
        });
    }

    return {
        init: function () {
            select2Handler();
            formSave();
        }
    };
}();

$(document).ready(function () {
    UserCreate.init();
});
