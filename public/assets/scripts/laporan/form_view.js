// "use strict";

var FormView = function() {    
    var id_frm;
    
    var grid;
    var initTable = function (param) {
        var header = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf_token,
        };
        // if (grid != null) $('#tablegridview').DataTable().clear().destroy();
        grid = $('#tablegridview').DataTable({
            responsive: true,
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: base_url+'/formulir/demografi/'+id_frm,
                type: 'GET',
                headers: header,
                data: param,
            },
            order: [1, 'asc'],
            columns: [
                { 
                    title: 'id_frm', 
                    data: 'id_frm',
                    visible: false
                }, { 
                    title: 'Nomor Anggota Keluarga', 
                    data: 'no_urutnik',
                }, { 
                    title: 'Nama Anggota Keluarga/NIK', 
                    data: 'nama_anggotakel',
                    render: function(data, type, row) {
                        return '<p>'+row.nama_anggotakel+'<br />'+row.nik+'</p>';
                    }
                }, { 
                    title: 'Jenis Kelamin (Kode)', 
                    data: 'jenis_kelamin',
                }, { 
                    title: 'Tanggal/Bulan/Tahun Lahir', 
                    data: 'tgl_lahir_id'
                }, { 
                    title: 'Status Perkawinan (Kode)', 
                    data: 'sts_kawin',
                }, { 
                    title: 'Usia kawin pertama, diisi untuk yang berstatus kawin dan cerai hidup/mati', 
                    data: 'usia_kawin',
                    render: function(data, type, row) {
                        var usia_kawin = row.sts_kawin==1 ? '' : row.usia_kawin;
                        if(usia_kawin === null) {
                            usia_kawin = '';
                        }
                        return usia_kawin;
                    }
                }, { 
                    title: 'Memiliki Akta Lahir (Kode)', 
                    data: 'status_akta',
                }, { 
                    title: 'Hubungan dengan Kepala Keluarga (Kode)', 
                    data: 'sts_hubungan',
                }, {
                    title: 'Kode Ibu Kandung (Dilihat dari Nomor Anggota Keluarga)',
                    data: 'kd_ibukandung',
                }, {
                    title: 'Agama (Kode)',
                    data: 'id_agama',
                }, {
                    title: 'Status Pekerjaan (Kode)',
                    data: 'id_pekerjaan',
                }, {
                    title: 'Pendidikan (Kode)',
                    data: 'jns_pendidikan',
                }, {
                    title: 'Kepesertaan JKN/Asuransi Kesehatan lainnya (Kode)',
                    data: 'jns_asuransi',
                }, {
                    title: 'Keberadaan Anggota Keluarga (1 tahun terakhir)',
                    data: 'jns_asuransi',
                },
            ],
        });
    }

    var initTablex = function (param) {
        $('#tableview').jqGrid("GridUnload");
        grid = $('#tableview').jqGrid({
                datatype: 'json',
                mtype: 'GET',
                url: base_url+'/formulir/demografi/'+id_frm,
                postData: param,
                // pager: '#pagerview',
                shrinkToFit: false,
                forceFit: true,
                // sortable: true,
                headertitles: true,
                // viewrecords: true,
                // rownumbers: true,
                autowidth: true,
                rowNum: 100,
                // rowList: [5, 10, 20],
                colModel: [
                        { 
                            label: 'id_frm', 
                            name: 'id_frm',
                            hidden: true
                        }, { 
                            label: 'Nomor Anggota Keluarga', 
                            name: 'no_urutnik',
                        }, { 
                            label: 'Nama Anggota Keluarga/NIK', 
                            name: 'nama_anggotakel',
                            formatter: function(val, opt, row) {
                                return '<p>'+row.nama_anggotakel+'<br />'+row.nik+'</p>';
                            }
                        }, { 
                            label: 'Jenis Kelamin (Kode)', 
                            name: 'jenis_kelamin',
                        }, { 
                            label: 'Tanggal/Bulan/Tahun Lahir', 
                            name: 'tgl_lahir_id'
                        }, { 
                            label: 'Status Perkawinan (Kode)', 
                            name: 'sts_kawin',
                        }, { 
                            label: 'Usia kawin pertama, diisi untuk yang berstatus kawin dan cerai hidup/mati', 
                            name: 'usia_kawin',
                            formatter: function(val, opt, row) {
                                var usia_kawin = row.sts_kawin==1 ? '' : row.usia_kawin;
                                if(usia_kawin === null) {
                                    usia_kawin = '';
                                }
                                return usia_kawin;
                            }
                        }, { 
                            label: 'Memiliki Akta Lahir (Kode)', 
                            name: 'status_akta',
                        }, { 
                            label: 'Hubungan dengan Kepala Keluarga (Kode)', 
                            name: 'sts_hubungan',
                        }, {
                            label: 'Kode Ibu Kandung (Dilihat dari Nomor Anggota Keluarga)',
                            name: 'kd_ibukandung',
                        }, {
                            label: 'Agama (Kode)',
                            name: 'id_agama',
                        }, {
                            label: 'Status Pekerjaan (Kode)',
                            name: 'id_pekerjaan',
                        }, {
                            label: 'Pendidikan (Kode)',
                            name: 'jns_pendidikan',
                        }, {
                            label: 'Kepesertaan JKN/Asuransi Kesehatan lainnya (Kode)',
                            name: 'jns_asuransi',
                        }, {
                            label: 'Keberadaan Anggota Keluarga (1 tahun terakhir)',
                            name: 'jns_asuransi',
                        },
                ],
            });

    }
    
	var getKB1Form = function() {
        KTApp.block('#kb1form', {message: 'Harap tunggu...'});
		var xhr = $.ajax({
				url: base_url + '/formulir/kb1form/'+id_frm,
				method: 'GET',
				dataType: 'html',
			})
			.done(function(response) {
                $('#kb1form').html(response);

			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log('jqXHR', jqXHR);
				toastr.error('getKB1Form: ' + jqXHR.statusText);
			})
			.always(function() {
                KTApp.unblock('#kb1form');
            });
	}
    
	var getPK01Form = function() {
        KTApp.block('#pk01form', {message: 'Harap tunggu...'});
		var xhr = $.ajax({
				url: base_url + '/formulir/pk01form/'+id_frm,
				method: 'GET',
				dataType: 'html',
			})
			.done(function(response) {
                $('#pk01form').html(response);

			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log('jqXHR', jqXHR);
				toastr.error('getPK01Form: ' + jqXHR.statusText);
			})
			.always(function() {
                KTApp.unblock('#pk01form');
            });
	}
    
	var getPK02Form = function() {
        KTApp.block('#pk02form', {message: 'Harap tunggu...'});
		var xhr = $.ajax({
				url: base_url + '/formulir/pk02form/'+id_frm,
				method: 'GET',
				dataType: 'html',
			})
			.done(function(response) {
                $('#pk02form').html(response);

			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				console.log('jqXHR', jqXHR);
				toastr.error('getKB1Form: ' + jqXHR.statusText);
			})
			.always(function() {
                KTApp.unblock('#pk02form');
            });
	}
    

	var btnViewHandle = function() {
		$(document).on('click', '.btnView', function(e) {
            e.preventDefault();
			id_frm = $(this).data('id_frm');
			$('#modalDetail ').modal();
		});
	}
    
    var modalShowCallback = function () {
        $('#modalDetail').on('shown.bs.modal', function (e) {
            $('.nav-tabs a[data-target="#tab_demografi"]').tab('show');
            initTable();
            getKB1Form();
            getPK01Form();
            getPK02Form();
        });
    }
    
	return {
		init: function() {
            // initTable();
            btnViewHandle();
            modalShowCallback();
            // getKB1Form();
            // getPK01Form();
            // getPK02Form();
		}
	};
}();

jQuery(document).ready(function() {
	FormView.init();
});