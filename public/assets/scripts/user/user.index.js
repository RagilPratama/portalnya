var UserIndex = function () {

    var otpnumber;

    // toastr.options = {
    // 		  "closeButton": false,
    // 		  "debug": true,
    // 		  "newestOnTop": true,
    // 		  "progressBar": false,
    // 		  "positionClass": "toast-top-right",
    // 		  "preventDuplicates": true,
    // 		  "onclick": null,
    // 		  "showDuration": "300",
    // 		  "hideDuration": "1000",
    // 		  "timeOut": "3000",
    // 		  "extendedTimeOut": "1000",
    // 		  "showEasing": "swing",
    // 		  "hideEasing": "linear",
    // 		  "showMethod": "fadeIn",
    // 		  "hideMethod": "fadeOut"
    // 		};

    

    var showTableHandler = function () {
        $('#btnShow').click(function (e) {
            initTable();
        });
    }

    var grid;
    var initTable = function () {
        var param = {
            Kelurahan: $('#Kelurahan').val(),
            RW: $('#RW').val(),
            RT: $('#RT').val(),
            StatusWilayah: $('#status_wilayah').val(),
        };
        if (grid != null) {
            $('#tablegrid').DataTable().clear().destroy();
            $('#tablegrid').empty();
        }
        var header = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf_token,
        };
        grid = $('#tablegrid').DataTable({
            responsive: true,
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            ajax: {
                url: base_url + '/user/datatable',
                type: 'GET',
                headers: header,
                data: param,
            },
            order: [8, 'desc'],
			rowId: 'ID',
            columns: [
                {
                    title: 'ID',
                    data: 'ID',
                    visible: false
                },
                {
                    title: 'UserName',
                    data: 'UserName',
                    responsivePriority: 1,
                },
                {
                    title: 'Nama Lengkap',
                    data: 'NamaLengkap',
                },
                {
                    title: 'Alamat',
                    data: 'Alamat',
                },
                {
                    title: 'No Telepon',
                    data: 'NoTelepon',
                },
                {
                    title: 'Email',
                    data: 'Email',
                },
                {
                    title: 'Role',
                    data: 'RoleName',
                },
                {
                    title: 'Tingkat Wilayah',
                    data: 'TingkatWilayah',
                    render: function (data, type, row) {
                        if (row.cntwil > 0) {
                            var result = '';
                            if (row.TingkatWilayahID >=4) result += 'Kel.: '+row.nama_kelurahan+'<br />';
                            if (row.TingkatWilayahID >=5) result += 'RW: '+row.nama_rw+'<br />'; 
                            if (row.TingkatWilayahID >=6) result += 'RT: '+row.nama_rt+'<br />'; 
                            return result;
                        } else {
                            return data;
                        }
                    }
                },
                {
                    title: 'Tgl. Dibuat',
                    data: 'CreatedDate',
                },
                {
                    title: 'IsActive',
                    data: 'IsActive',
                    visible: false
                },
                {
                    title: 'Wilayah',
                    data: 'cntwil',
                    className: 'text-center',
                    responsivePriority: 1,
                    render: function (data, type, row) {
                        var btnClass = row.cntwil > 0 ? ' btn-outline-primary ' : ' btn-outline-danger ';
                        var btnTitle = row.cntwil > 0 ? ' Pengaturan Wilayah ' : ' Pengaturan Wilayah belum ditetapkan ';
                        var btnWil = '<button type="button" title="' + btnTitle + '" class="btn ' + btnClass + ' btn-elevate btn-icon btn-sm btnWil" data-id="' + row.ID + '"><i class="fas fa-globe-asia"></i></button>&nbsp;';
                        return btnWil;
                    }
                },
                {
                    title: 'Aksi',
                    data: null,
                    width: 100,
                    className: 'text-center',
                    responsivePriority: 1,
                    render: function (data, type, row) {
						// console.log(data);
                        var btnEdit = '<button type="button" title="Edit User &quot;' + row.UserName + '&quot;" class="btn btn-outline-success btn-elevate btn-icon btn-sm btnEdit" data-id="' + row.ID + '"><i class="fa fa-user-edit"></i></button>&nbsp;';
                        var btnReset = '<button type="button" title="Reset Password  &quot;' + row.UserName + '&quot;" class="btn btn-outline-warning btn-elevate btn-icon btn-sm btnReset" data-id="' + row.ID + '"><i class="fa fa-users-cog"></i></button>&nbsp;';
                        var btnDelete = '<button type="button" title="Non-aktifkan User &quot;' + row.UserName + '&quot;" class="btn btn-outline-danger btn-elevate btn-icon btn-sm btnDelete" data-id="' + row.ID + '"><i class="fa fa-user-times"></i></button>&nbsp;';
                        btnDelete = '';
                        return btnEdit + btnReset + btnDelete;
                    }
                },


            ],
        });

	}
	
    var btnCreateHandler = function () {
        $('#btnCreate').click(function () {
            $('#modalRoleCreate').modal();
        });
    }

    var btnImportHandler = function () {
        $('#btnImport').click(function () {
            window.location.href = base_url + '/user/import';
        });
    }

    var editRow = {};
    var btnEditHandler = function () {
        $(document).on('click', '.btnEdit', function () {
            var rowid = $(this).data('id');
            var row = $('#tablegrid').DataTable().row('#'+rowid).data();
            editRow = row;
            $('#formUserEdit #ID').val(row.ID);
            $('#formUserEdit #UserName').val(row.UserName);
            $('#formUserEdit #NamaLengkap').val(row.NamaLengkap);
            $('#formUserEdit #Email').val(row.Email);
            $('#formUserEdit #NoTelepon').val(row.NoTelepon);
            $('#formUserEdit #Alamat').val(row.Alamat);
            $('#formUserEdit #NIK').val(row.NIK);
            $('#formUserEdit #RoleID').val(row.RoleID).trigger('change');
            // $('#formUserEdit #KabupatenKotaID').val(row.KabupatenKotaID).trigger('change');
            $('#modalUserEdit').modal();

        });
    }


    var formUpdate = function () {
        $('#btnUpdateUser').click(function (e) {
            e.preventDefault();
            KTApp.block('#modalUserEdit', {
                message: 'Harap tunggu...'
            });

            var disabled = $('#formUserEdit').find(':input:disabled').removeAttr('disabled');

            var id = $('#formUserEdit #ID').val();
            var param = $('#formUserEdit').serializeObject()
            disabled.attr('disabled', 'disabled');

            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };

            var xhr = $.ajax({
                    url: base_url + '/user/' + id,
                    method: 'PUT',
                    dataType: 'json',
                    headers: header,
                    data: param
                })
                .done(function (response) {
                    var message = (response.message) ? response.message : '';
                    if (response.status) {
                        toastr.options.onHidden = formClear;
                        toastr.success(message);
                    } else {
                        message = (message !== '') ? message : 'Ada kesalahan';
                        swal.fire('', message, 'error');
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
                    swal.fire('', message, 'error');
                }).always(function () {
                    KTApp.unblock('#modalUserEdit');
                });
        });
    }

    var formSave = function () {
        $('#btnSave').click(function (e) {
            e.preventDefault();
            KTApp.block('#modalRoleCreate', {
                message: 'Harap tunggu...'
            });
            var param = {
                RoleName: $('#formUserCreate #Name').val(),
            };

            var disabled = $('#formUserEdit').find(':input:disabled').removeAttr('disabled');

            var param = $('#formUserCreate').serializeObject()
            disabled.attr('disabled', 'disabled');

            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };

            var xhr = $.ajax({
                    url: base_url + '/user',
                    method: 'POST',
                    dataType: 'json',
                    headers: header,
                    data: param
                })
                .done(function (response) {
                    var message = (response.message) ? response.message : '';
                    if (response.status) {
                        toastr.options.onHidden = formClear;
                        toastr.success(message);
                    } else {
                        message = (message !== '') ? message : 'Ada kesalahan';
                        swal.fire('', message, 'error');
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
                    swal.fire('', message, 'error');
                }).always(function () {
                    KTApp.unblock('#modalRoleCreate');
                });
        });
    }


    var formClear = function () {
        $('#formRoleCreate, #formUserEdit, #formPasswordReset').trigger('reset');
        $('#formRoleCreate input, #formUserEdit input, #formPasswordReset input').val('');
        $('#modalRoleCreate, #modalUserEdit, #modalResetCreate, #modalUserVerifikasiOtp').modal('hide');
        // $('#tablegrid').DataTable().ajax.reload(null, false).responsive.recalc().columns.adjust();
        initTable();
    }


    var btnResetHandler = function () {
        $(document).on('click', '.btnReset', function () {
            var rowid = $(this).data('id');
            var row = $('#tablegrid').DataTable().row('#'+rowid).data();
            $('#formPasswordReset #ID').val(row.ID);
            $('#modalResetCreate').modal();
            $('#modalResetCreate #Password').val('');
            $('#modalResetCreate #rePassword').val('');
        });
    }


    var formReset = function () {
        $('#btnResetSave').click(function (e) {
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
                    url: base_url + '/user/resetPassword/' + id,
                    method: 'PUT',
                    dataType: 'json',
                    headers: header,
                    data: param
                })
                .done(function (response) {
                    var message = (response.message) ? response.message : '';
                    if (response.status) {
                        toastr.options.onHidden = formClear;
                        toastr.success(message);
                    } else {
                        message = (message !== '') ? message : 'Ada kesalahan';
                        swal.fire('', message, 'error');
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
                    swal.fire('', message, 'error');
                }).always(function () {
                    KTApp.unblock('#modalResetCreate');
                });
        });
    }

    var btnDeleteHandler = function () {
        $(document).on('click', '.btnDelete', function () {
            var userID = $(this).data('id');

            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };

            swal.fire({
                title: '',
                text: 'Yakin akan menon-aktifkan user ini?',
                icon: 'warning',
                showCancelButton: true,
            }).then((result) => {
                if (result.value) {
                    var xhr = $.ajax({
                            url: base_url + '/user/' + userID,
                            method: 'DELETE',
                            dataType: 'json',
                            headers: header
                        })
                        .done(function (response) {
                            var message = (response.message) ? response.message : '';
                            if (response.status) {
                                toastr.options.onHidden = formClear;
                                toastr.success(message);
                            } else {
                                message = (message !== '') ? message : 'Ada kesalahan';
                                swal.fire('', message, 'error');
                            }
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
                            swal.fire('', message, 'error');
                        }).always(function () {});
                }
            })

        });
    }

    var select2Handler = function () {

        $('#Kelurahan').select2({
            placeholder: '--- Semua Wilayah ---',
            allowClear: true
        })
        .on('change', function() {
            getRW($(this).val());
        })
        .trigger('change');

		$('#RW').select2({
            placeholder: '--- Semua Wilayah ---',
            allowClear: true
        })
        .on('change', function() {
            getRT($(this).val());
        });

		$('#RT').select2({
            placeholder: '--- Semua Wilayah ---',
            allowClear: true
        });
        
        $('div.divwil').addClass('kt-hidden');
        
        $('#status_wilayah').select2({
            placeholder: '--- Semua Status ---',
            allowClear: true,
        })
        .on('change', function() {
            var sts = $(this).val();
            if (sts=='' || sts=='0') {
                $('div.divwil').addClass('kt-hidden');
            } else {
                $('div.divwil').removeClass('kt-hidden');
            }
        });

        $('#modalRoleCreate #RoleID').select2({
            placeholder: '--- Pilih Role ---',
        }).on('change', function (e) {
            if ($(this).val() == 4) {
                $('#modalRoleCreate #smartcheck').removeClass('kt-hidden');
            } else {
                $('#modalRoleCreate #smartcheck').addClass('kt-hidden');
            }
            getWilayah();
        });

        $('#modalUserEdit #RoleID').select2({
            placeholder: '--- Pilih Role ---',
        }).on('change', function (e) {
            if ($(this).val() == 4) {
                $('#modalUserEdit #smartcheck').removeClass('kt-hidden');
                if (editRow.Smartphone == 'true') {
                    $('#modalUserEdit #Smartphone').prop('checked', true);
                } else {
                    $('#modalUserEdit #Smartphone').prop('checked', false);
                }
            } else {
                $('#modalUserEdit #smartcheck').addClass('kt-hidden');
            }
            getWilayahEdit();
        });


        $('#TingkatWilayahID, [name=TingkatWilayahID]').select2({
            placeholder: '--- Pilih Tingkat Wilayah ---',
        });
    }

	var getRW = function(id_kelurahan) {
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

	var getRT = function(id_rw) {
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

    
    var getRole = function () {
        var xhr = $.ajax({
                url: base_url + '/user/getrole',
                method: 'GET',
                dataType: 'json',
            })
            .done(function (response) {
                var el = $('#modalRoleCreate #RoleID, #modalUserEdit #RoleID');
                el.children().remove();
                el.append($("<option></option>").attr("value", '').text(''));
                $.each(response, function (key, value) {
                    el.append($("<option></option>").attr("value", value.ID).text(value.RoleName));
                });

            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.error(jqXHR);
                // toastr.error('getRole: ' + jqXHR.statusText);
            })
            .always(function () {});
    }

    var getWilayah = function () {
        var roleid = $('#modalRoleCreate #RoleID').val();
        var xhr = $.ajax({
                url: base_url + '/user/wilayah/' + roleid,
                method: 'GET',
                dataType: 'json',
            })
            .done(function (response) {
                var el = $('#TingkatWilayahID');
                el.children().remove();
                el.append($("<option></option>").attr("value", '').text(''));
                $.each(response, function (key, value) {
                    el.append($("<option></option>").attr("value", value.ID).text(value.TingkatWilayah));
                });
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                toastr.error('getWilayah: ' + jqXHR.statusText);
            })
            .always(function () {});
    }

    var getWilayahEdit = function () {
        var roleid = $('#formUserEdit #RoleID').val();
        var xhr = $.ajax({
                url: base_url + '/user/wilayah/' + roleid,
                method: 'GET',
                dataType: 'json',
            })
            .done(function (response) {
                var el = $('#formUserEdit #TingkatWilayahID');
                el.children().remove();
                el.append($("<option></option>").attr("value", '').text(''));
                $.each(response, function (key, value) {
                    el.append($("<option></option>").attr("value", value.ID).text(value.TingkatWilayah));
                });

                $('#formUserEdit #TingkatWilayahID').val(editRow.TingkatWilayahID).trigger('change');
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                toastr.error('getWilayah: ' + jqXHR.statusText);
            })
            .always(function () {});
    }



    return {
        init: function () {
            // initTable();
            showTableHandler();
            btnCreateHandler();
            formSave();

            btnEditHandler();
            formUpdate();

            btnResetHandler();
			formReset();
			
            select2Handler();
            // getRole();

            btnDeleteHandler();
        }
    };
}();

$(document).ready(function () {
    UserIndex.init();
});
