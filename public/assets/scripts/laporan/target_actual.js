"use strict";

var TargetActual = function() {
    
	var select2Handler = function() {
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

	var getPendata = function() {
        
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
    
    var grid;
    var initTable = function (param) {
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
            ajax: {
                url: base_url+'/laporan/targetactual/data',
                type: 'GET',
                headers: header,
                data: param,
            },
            order: [1, 'asc'],
            columns: [
                {
                    title: 'Pendata',
                    data: 'UserName',
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
                    title: 'Target', 
                    data: 'TargetKK',
                    align: 'right',
                }, { 
                    title: 'Terdata', 
                    data: 'Actual',
                    align: 'right',
                }, { 
                    title: 'Persen', 
                    data: 'Persen',
                    className: 'text-right',
                // }, { 
                //     title: 'View',
                //     data: '',
                //     className: 'text-center',
                //     render: function(data, type, row){
                //         var btnView = '<button type="button" title="View Data Detail" class="btn btn-outline-brand btn-icon btn-sm btnView" data-id_pendata="'+row.UserName+'"><i class="fa fa-file-alt"></i></button>&nbsp;';
                //     return btnView;
                //     }
                },
            ],
        });
    }

    var showReport = function () {
        $('#btnShow').click(function (e) {
            e.preventDefault();
            if ($('#PeriodeSensus').val()=='') {
                swal.fire('', 'Periode Pendataan belum dipilih', 'error');
                return false;
            }
            
            var param = {
                PeriodeSensus: $('#PeriodeSensus').val(),
                Kelurahan: $('#Kelurahan').val(),
                RW: $('#RW').val(),
                RT: $('#RT').val(),
            };
            initTable(param);
        });
    }
    
    var btnPrintHandler = function () {
        $('#btnPrint').click(function (e) {
            e.preventDefault();
            if ($('#PeriodeSensus').val()=='') {
                swal.fire('', 'Periode Pendataan belum dipilih', 'error');
                return false;
            }
            
            var param = {
                PeriodeSensus: $('#PeriodeSensus').val(),
                Kelurahan: $('#Kelurahan').val(),
                RW: $('#RW').val(),
                RT: $('#RT').val(),
                print: 1,
            };
            
            var querystr = $.param( param );
            var url = base_url + '/laporan/targetactual/data?' + querystr;
            // window.location.href = url;
            // window.open(url, '_blank');
            $('<a href="'+url+'" target="_blank">&nbsp;</a>')[0].click();

        });
    }

	var showDetail = function() {
		$(document).on('click', '.btnView', function(e) {
            e.preventDefault();
			var id_pendata = $(this).data('id_pendata');
			$('#id_pendata').val(id_pendata);
            // console.log(id_pendata);
			$('#modalDetail ').modal();
		});
	}
    
    var modalShowCallback = function () {
        $('#modalDetail').on('shown.bs.modal', function (e) {
            initTableDetail();
        });
    }
    
    var initTableDetail = function () {
        var param = {
            PeriodeSensus: $('#PeriodeSensus').val(),
            Kelurahan: $('#Kelurahan').val(),
            RW: $('#RW').val(),
            RT: $('#RT').val(),
            Pendata: $('#id_pendata').val(),
        };
        var header = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf_token,
        };
        var gridview = $('#tablegridview').DataTable({
            responsive: true,
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: base_url+'/laporan/targetactual/pendata',
                type: 'GET',
                headers: header,
                data: param,
            },
            order: [1, 'asc'],
            columns: [
                {
                    title: 'No. KK',
                    data: 'no_kk',
                }, { 
                    title: 'Alamat', 
                    data: 'alamat1',
                }, { 
                    title: 'No Urut Rumah', 
                    data: 'no_urutrmh',
                }, { 
                    title: 'No Urut Keluarga', 
                    data: 'no_urutkel',
                }, { 
                    title: 'Provinsi', 
                    data: 'nama_provinsi',
                }, { 
                    title: 'Kabupaten', 
                    data: 'nama_kabupaten',
                }, { 
                    title: 'Kecamatan', 
                    data: 'nama_kecamatan',
                }, { 
                    title: 'Kelurahan', 
                    data: 'nama_kelurahan',
                }, { 
                    title: 'RW', 
                    data: 'nama_rw',
                }, { 
                    title: 'RT', 
                    data: 'nama_rt',
                },
            ],
        });
    }
    var initTableDetailx = function () {
            var param = {
                PeriodeSensus: $('#PeriodeSensus').val(),
                Kelurahan: $('#Kelurahan').val(),
                RW: $('#RW').val(),
                RT: $('#RT').val(),
                Pendata: $('#id_pendata').val(),
            };
            $('#tableview').jqGrid('GridUnload');
            $('#tableview').jqGrid({
                datatype: 'json',
                url: base_url+'/laporan/targetactual/pendata',
                postData: param,
                pager: '#pagerview',
                shrinkToFit: false,
                forceFit: true,
                sortable: true,
                viewrecords: true,
                rownumbers: true,
                autowidth: true,
                headertitles: true,
                rowNum: 10,
                rowList: [5, 10, 20],
                colModel: [
                        {
                            label: 'No. KK',
                            name: 'no_kk',
                        }, { 
                            label: 'Alamat', 
                            name: 'alamat1',
                        }, { 
                            label: 'No Urut Rumah', 
                            name: 'no_urutrmh',
                        }, { 
                            label: 'No Urut Keluarga', 
                            name: 'no_urutkel',
                        }, { 
                            label: 'Provinsi', 
                            name: 'nama_provinsi',
                        }, { 
                            label: 'Kabupaten', 
                            name: 'nama_kabupaten',
                        }, { 
                            label: 'Kecamatan', 
                            name: 'nama_kecamatan',
                        }, { 
                            label: 'Kelurahan', 
                            name: 'nama_kelurahan',
                        }, { 
                            label: 'RW', 
                            name: 'nama_rw',
                        }, { 
                            label: 'RT', 
                            name: 'nama_rt',
                        },
                ],
            });

    }
    
	return {
		init: function() {
            select2Handler();
            // initTable();
            showReport();
            showDetail();
            modalShowCallback();
            btnPrintHandler();
		}
	};
}();

jQuery(document).ready(function() {
	TargetActual.init();
});