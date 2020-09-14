var StatusValid = function () {

    var grid;


    var initTableValid = function (param) {
        var header = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf_token,
        };

        if (grid != null) {
            $('#tablegrid').DataTable().clear().destroy();
            $('#tablegrid').empty();
        }
        grid = $('#tablegrid').DataTable({
            responsive: true,
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            // destroy: true,
            ajax: {
                url: base_url + '/laporan/statussensus/data',
                type: 'GET',
                headers: header,
                data: param,
            },
            order: [7, 'desc'],
            columns: [
            {
                title: 'No. KK',
                data: 'no_kk',
            }, {
                title: 'Kelurahan',
                data: 'nama_kelurahan',
            }, {
                title: 'RW',
                data: 'nama_rw',
            }, {
                title: 'RT',
                data: 'nama_rt',
            }, {
                title: 'Alamat',
                data: 'alamat1',
            }, {
                title: 'No. Urut Rumah',
                data: 'no_urutrmh',
            }, {
                title: 'Pendata',
                data: 'UserName',
            }, {
                title: 'Tgl. Dibuat',
                data: 'create_date',
            }, {
                title: 'View',
                data: '',
                class: 'text-center',
                render: function (data, type, row) {
                    var btnView = '<button type="button" title="View Data Detail" class="btn btn-outline-brand btn-icon btn-sm btnView" data-id_frm="' + row.id_frm + '"><i class="fa fa-file-alt"></i></button>&nbsp;';
                    return btnView;
                }
            }, ],
        });
    }

    var initTableInvalid = function (param) {
        var header = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf_token,
        };
        if (grid != null) {
            $('#tablegrid').DataTable().clear().destroy();
            $('#tablegrid').empty();
        }

        grid = $('#tablegrid').DataTable({
            responsive: true,
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            // destroy: true,
            ajax: {
                url: base_url + '/laporan/statussensus/data',
                type: 'GET',
                headers: header,
                data: param,
            },
            order: [8, 'desc'],
            columns: [
            {
                title: 'No. KK',
                data: 'no_kk',
            }, {
                title: 'Kelurahan',
                data: 'nama_kelurahan',
            }, {
                title: 'RW',
                data: 'nama_rw',
            }, {
                title: 'RT',
                data: 'nama_rt',
            }, {
                title: 'Alamat',
                data: 'alamat1',
            }, {
                title: 'No. Urut Rumah',
                data: 'no_urutrmh',
            }, {
                title: 'Pendata',
                data: 'UserName',
            }, {
                title: 'Alasan',
                data: 'alasan_text',
            }, {
                title: 'Tgl. Dibuat',
                data: 'create_date',
            }, {
                title: 'View',
                data: '',
                class: 'text-center',
                render: function (data, type, row) {
                    var btnView = '<button type="button" title="View Data Detail" class="btn btn-outline-brand btn-icon btn-sm btnView" data-id_frm="' + row.id_frm + '"><i class="fa fa-file-alt"></i></button>&nbsp;';
                    return btnView;
                }
            },
            // {
            //     title: 'Action',
            //     data: '',
            //     class: 'text-center',
            //     render: function (data, type, row) {
            //         var btnView = '<button type="button" title="View Data Detail" class="btn btn-outline-brand btn-icon btn-sm btnAnulir text-danger" data-id="' + row.no_kk + '"><i class="fas fa-ban"></i></button>&nbsp;';
            //         return btnView;
            //     }
            // },
            ,],
        });
    }

    var showReport = function () {
        $('#btnShow').click(function (e) {
            e.preventDefault();

            if ($('#PeriodeSensus').val() == '') {
                swal.fire('', 'Periode Sensus belum dipilih', 'error');
                return false;
            }

            if ($('#JenisData').val() == '') {
                swal.fire('', 'Jenis Data belum dipilih', 'error');
                return false;
            }
            var param = {
                StatusSensus: $('#StatusSensus').val(),
                PeriodeSensus: $('#PeriodeSensus').val(),
                JenisData: $('#JenisData').val(),
                Kelurahan: $('#Kelurahan').val(),
                RW: $('#RW').val(),
                RT: $('#RT').val(),
                Pendata: $('#Pendata').val(),
            };
            if ($('#StatusSensus').val() == 1) {
                initTableValid(param);
            } else if ($('#StatusSensus').val() == 2) {
                initTableInvalid(param);
            } else if ($('#StatusSensus').val() == 3) {
                initTableInvalid(param);
            } else if ($('#StatusSensus').val() == 4) {
                initTableValid(param);
            }
        });
    }

    var anulirData = function () {
        $(document).on('click', '.btnAnulir', function () {
            var id = $(this).data('id');
            swal.fire({
                title: '',
                text: "Yakin akan menganulir data berikut?",
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    KTApp.blockPage({
                        message: 'Harap tunggu...'
                    });

                    var header = {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf_token,
                    };

                    var xhr = $.ajax({
                            url: base_url + '/laporan/statussensus/anulir/' + id,
                            method: 'PUT',
                            dataType: 'json',
                            headers: header,
                        })
                        .done(function (response) {
                            message = (response.message) ? response.message : '';
                            if (response.status) {
                                toastr.options.onHidden = function () {
                                    $('#tablegrid').DataTable().ajax.reload(null, false).responsive.recalc().columns.adjust();
                                }
                                toastr.success(message);
                            } else {
                                message = (message !== '') ? message : 'Ada kesalahan';
                                swal.fire('', message, 'error');
                            }
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            console.log('jqXHR', jqXHR);
                            swal.fire('', jqXHR.statusText, 'error');
                        }).always(function () {
                            KTApp.unblockPage();
                        });
                }
            })
        });
    }

    var btnPrintHandler = function () {
        $('#btnPrint').click(function (e) {
            e.preventDefault();
            if ($('#PeriodeSensus').val() == '') {
                swal.fire('', 'Periode Pendataan belum dipilih', 'error');
                return false;
            }

            var param = {
                StatusSensus: $('#StatusSensus').val(),
                PeriodeSensus: $('#PeriodeSensus').val(),
                JenisData: $('#JenisData').val(),
                Kelurahan: $('#Kelurahan').val(),
                RW: $('#RW').val(),
                RT: $('#RT').val(),
                Pendata: $('#Pendata').val(),
                print: 1,
            };

            var querystr = $.param(param);
            var url = base_url + '/laporan/statussensus/data?' + querystr;
            $('<a href="' + url + '" target="_blank">&nbsp;</a>')[0].click();

        });
    }

    return {
        init: function () {
            showReport();
            anulirData();
            btnPrintHandler();
        }
    };
}();

jQuery(document).ready(function () {
    StatusValid.init();
});
