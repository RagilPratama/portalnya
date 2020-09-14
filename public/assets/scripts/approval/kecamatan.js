// "use strict";

var AppKecamatan = function() {
    var select2Handler = function() {
		$('#Periode').select2({
            placeholder: '--- Pilih Periode Pendataan ---',
            minimumResultsForSearch: -1,
            // allowClear: true
        });

		$('#Status').select2({
            placeholder: '--- Pilih Status ---',
            minimumResultsForSearch: -1,
        });

    }
    
    var grid;
    
    var initTable = function () {
        var param = {
            PeriodeSensus: $('#PeriodeSensus').val(),
            Status: $('#Status').val(),
        };

        $('#tablegrid').jqGrid("GridUnload");
        grid = $('#tablegrid').jqGrid({
                datatype: 'json',
                mtype: 'GET',
                url: base_url+'/approval/kecamatan/data',
                pager: '#pagergrid',
                postData: param,
                shrinkToFit: pShrinkToFit,
                forceFit: pForceFit,
                sortable: true,
                sortname: 'nama_kabupaten',
                sortorder: 'asc',
                viewrecords: true,
                rownumbers: true,
                autowidth: true,
                rowNum: 20,
                rowList: [10, 20, 30],
                colModel: [
                        { 
                            label: 'Nama Provinsi', 
                            name: 'nama_provinsi',
                        }, { 
                            label: 'Nama Kabupaten', 
                            name: 'nama_kabupaten',
                        }, { 
                            label: 'Nama Kecamatan', 
                            name: 'nama_kecamatan',
                        }, { 
                            label: 'Status Wilayah', 
                            name: 'Status_Approve_Kec',
                            cellattr: function(rowId, val) {
                                return val ? " style='color:#0093D1;'" : "";
                            },
                            formatter: function(val) {
                                return val ? 'APPROVED' : 'NOT APPROVED';
                            }
                        }, { 
                            label: 'Status Target KK', 
                            name: 'Status_Approve_Target',
                            cellattr: function(rowId, val) {
                                return val ? " style='color:#0093D1;'" : "";
                            },
                            formatter: function(val) {
                                return val ? 'APPROVED' : 'NOT APPROVED';
                            }
                        }
                ],
            });

    }

    var showReport = function () {
        $('#btnShow').click(function (e) {
            e.preventDefault();
        
            if ($('#PeriodeSensus').val()=='') {
                swal.fire('', 'Periode Sensus belum dipilih', 'error');
                return false;
            }

            initTable();
        });
    }
    
	return {
		init: function() {
            showReport();
            select2Handler();
		}
	};
}();

jQuery(document).ready(function() {
	AppKecamatan.init();
});