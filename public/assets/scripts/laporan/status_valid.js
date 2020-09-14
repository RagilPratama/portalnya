"use strict";

var StatusValid = function() {
    
    var grid;
    var initTable = function () {
        grid = $('#tablemon').jqGrid({
                datatype: 'local',
                // url: base_url+'/laporan/targetactual/data',
                pager: '#pagermon',
                shrinkToFit: false,
                forceFit: true,
                sortable: true,
                viewrecords: true,
                rownumbers: true,
                autowidth: true,
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
                            label: 'No. Urut Rumah', 
                            name: 'no_urutrmh',
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
                        }, {
                            label: 'Pendata',
                            name: 'UserName',
                        }, { 
                            label: 'View',
                            align: 'center',
                            formatter: function(val, opt, row){
                                var btnView = '<button type="button" title="View Data Detail" class="btn btn-outline-brand btn-icon btn-sm btnView"><i class="fa fa-file-alt"></i></button>&nbsp;';
                            return btnView;
                            }
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
            
            if ($('#JenisData').val()=='') {
                swal.fire('', 'Jenis Data belum dipilih', 'error');
                return false;
            }
            
            var param = {
                PeriodeSensus: $('#PeriodeSensus').val(),
                JenisData: $('#JenisData').val(),
                Kelurahan: $('#Kelurahan').val(),
                RW: $('#RW').val(),
                RT: $('#RT').val(),
                Pendata: $('#Pendata').val(),
            };
            
            $(grid).jqGrid('setGridParam',{
                datatype: 'json',
                mtype: 'GET',
                url: base_url+'/laporan/statusvalid/data',
                postData: param,
            }).trigger('reloadGrid', [{page:1}]);
        });
    }

	var showDetail = function() {
		$(document).on('click', '.btnView', function() {
			// var selRowId = $('#tableroles').jqGrid('getGridParam', 'selrow');
			// var row = $('#tableroles').jqGrid('getRowData', selRowId);
			// console.log(row);
			// $('#formUserEdit #ID').val(row.ID);
			// $('#formUserEdit #UserName').val(row.UserName);
			// $('#formUserEdit #Email').val(row.Email);
			// $('#formUserEdit #NamaLengkap').val(row.NamaLengkap);
			// $('#formUserEdit #Alamat').val(row.Alamat);
			// $('#formUserEdit #NoTelepon').val(row.NoTelepon);
			//$('#formUserEdit #Level').val(row.Level ? row.Level : 0);
			$('#modalDetail ').modal();
		});
	}
    
	return {
		init: function() {
            initTable();
            showReport();
            showDetail();
		}
	};
}();

jQuery(document).ready(function() {
	StatusValid.init();
});