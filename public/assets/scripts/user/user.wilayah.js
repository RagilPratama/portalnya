var UserWilayah = function () {
    
    // var ddnext = false;

    var select2Handler = function () {
        $('select.ddwil').on('change', function (e) {
            var el = $(this);
            var val = el.val();
            var next = el.attr('ddnext');
            if ( next=='false') return false;
            var childtk = parseInt(el.data('tk'))+1;
            var elchild = $('select[data-tk='+childtk+']');
            if (val == '') {
                elchild.children().remove();
                elchild.append($('<option></option>').attr('value', '').text(''));
                elchild.trigger('change');            
                elchild.parent().parent().addClass('kt-hidden');

                return false;
            }
            var formdata = {
                tk: childtk,
                wid: val,
                uid: $('#pUserID').val()
            };
            getOptions(formdata);
        });

        $('select.ddwil').on('select2:select', function (e) {
            var el = $(this);
            var tk = parseInt(el.data('tk'));
            for (cnt=tk;cnt<=6;cnt++){
                userPath[cnt] = [];
            }
        });
    }

    var userPath;

    var getPathUser = function() {
        KTApp.block('#modalWilayah', {
            message: 'Harap tunggu...'
        });
        userPath=[];
        var userID =  $('#pUserID').val();
        var formdata = {};
        var xhr = $.ajax({
            url: base_url + '/akses/path/'+userID,
            method: 'GET',
            dataType: 'json',
            // headers: header,
            data: formdata
        })
        .done(function (response) {
            userPath = response;
            var firstdata = {
                uid: $('#pUserID').val()
            };
            getOptions();
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            var message = jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.statusText;
            swal.fire('', message, 'error');
        }).always(function () {
            KTApp.unblock('#modalWilayah');
        });
    }

    var getOptions = function(formdata) {
        KTApp.block('#modalWilayah', {
            message: 'Harap tunggu...'
        });
        var xhr = $.ajax({
            url: base_url + '/akses/ddwilayah',
            method: 'GET',
            dataType: 'json',
            // headers: header,
            data: formdata
        })
        .done(function (response) {
            var el = $('select[data-tk='+response.ddtk+']');
            el.parent().parent().removeClass('kt-hidden');
            el.children().remove();
            el.append($("<option></option>").attr("value", '').text(''));
            el.removeAttr('ddnext').attr('ddnext', response.ddnext);
            $.each(response.ddoptions, function(key, value) {
                var opt = $("<option></option>");
                var usedText = '';
                if (value.used) {
                    opt.attr('disabled', 'disabled');
                    usedText = ' (Terpakai)'
                }
                el.append(opt.attr("value", value.id).text(value.text+usedText));
            });

            var multiselect = false;// || (response.ddtk>=4 && response.ddnext=='false')
            if (response.ddtk>4 && response.ddnext==false) {
                el.find('option[value=""]').remove()
                multiselect = true;
            }
            el.select2({
                placeholder: '--- Pilih Wilayah ---',
                // minimumResultsForSearch: -1,
                multiple: multiselect,
                allowClear: true
            });


            /* el.select2({
                placeholder: '--- Pilih Wilayah ---',
                allowClear: true,
                // minimumResultsForSearch: -1,
            }); */
            if (response.ddoptions.length==1) {
                el.val(response.ddoptions[0].id).trigger('change');
            } else {
                if (typeof userPath[response.ddtk] !== 'undefined' && userPath[response.ddtk].length>0) {
                    if (el.find('option').length > 0) {
                        el.val(userPath[response.ddtk]).trigger('change');
                    }
                } else {
                    el.val('').trigger('change');
                }
            }

        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            var message = jqXHR.responseJSON.message ? jqXHR.responseJSON.message : jqXHR.statusText;
            swal.fire('', message, 'error');
        }).always(function () {
            KTApp.unblock('#modalWilayah');
        });
    }

    
    var formSave = function () {
        $('#btnSaveAkses').click(function (e) {
            e.preventDefault();
            var id =  $('#pUserID').val();
            var tkid =  $('#pTingkatWilayahID').val();
            var param = $('#formUserAkses').serializeObject();
            param.userid= id;
            var swaltxt = 'Yakin akan menyimpan data ini?';
            if ($('select[name="wilayahid['+tkid+']"]').val()=='') {
                swaltxt = 'Penetapan wilayah user kosong. Data wilayah yang sudah ada akan terhapus. Yakin akan melanjutkan?'
            }
            swal.fire({
                title: '',
                text: swaltxt,
                type: 'warning',
                showCancelButton: true,
            }).then((result) => {
                if (result.value) {
                    KTApp.block('#modalWilayah', {
                        message: 'Harap tunggu...'
                    });
                    var header = {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf_token,
                    };

                    var xhr = $.ajax({
                        url: base_url + '/akses/update',
                        method: 'POST',
                        dataType: 'json',
                        headers: header,
                        data: param
                    })
                    .done(function (response) {
                        var message = (response.message) ? response.message : '';
                        if (response.status) {
                            toastr.options.onHidden = function () {
                                $('#modalWilayah').modal('hide');
                            }
                            $('#tablegrid').DataTable().ajax.reload(null, false).responsive.recalc().columns.adjust();
                            toastr.success(message);
                        } else {
                            message = (message !== '') ? message : 'Ada kesalahan';
                            swal.fire('', message, 'error');
                        }
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
                        swal.fire('', message, 'error');
                    }).always(function () {
                        KTApp.unblock('#modalWilayah');
                    });
                }
            });
        });
    }

    var selectedUser;
    var btnWilHandler = function () {
        $(document).on('click', '.btnWil', function () {
            var rowid = $(this).data('id');
            selectedUser = $('#tablegrid').DataTable().row('#'+rowid).data();
            if (selectedUser.TingkatWilayahID == '' || typeof selectedUser.TingkatWilayahID === 'undefined') {
                swal.fire('', 'Tingkat wilayah user belum ditetapkan', 'error');
                return false;
            }
            $('#modalWilayah').modal({backdrop: 'static', keyboard: false});
        });
    }

    var modalWilayahCallback = function () {
        $('#modalWilayah').on('shown.bs.modal', function (e) {
            $('#formUserAkses #pUserID').val(selectedUser.ID);
            $('#formUserAkses #pTingkatWilayahID').val(selectedUser.TingkatWilayahID);
            getPathUser();
        });

        $('#modalWilayah').on('hide.bs.modal', function (e) {
            $('#formUserAkses select.ddwil option').remove();
            $('#formUserAkses .rowwil').addClass('kt-hidden');
        });
    }

    var checkMaxWilayah = function (treedata) {
        var Is1Kelurahan = $('#Is1Kelurahan').val() === '1' ? true : false;
        var result = false;
        var data = null;
        var output = {};
        var checked = treedata.filter(function (i) {
            if (i.Flag == "1") {
                return true;
            }
            return false;
        });

        // if (checked.length > 0) {
            if (checked.length > 1 && Is1Kelurahan && selectedUser.RoleID < 5) {
                data = 'User hanya diperbolehkan memiliki 1 Akses Wilayah';
            } else {
                result = true;
                data = checked;
            }
        // } else {
            // data = 'Akses Wilayah belum dipilih';
        // }
        output.result = result;
        output.data = data;
        return output;
    }

    return {
        init: function () {
            select2Handler();
            btnWilHandler();
            modalWilayahCallback();
            formSave();
        },

        getPathUser: function() {
            getPathUser();
        },

    };
}();

$(document).ready(function () {
    UserWilayah.init();
});
