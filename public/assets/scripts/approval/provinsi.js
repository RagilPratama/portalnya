// "use strict";

var AppProvinsi = function() {
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

        $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            startDate: '-3d'
        });

    }
    
    var grid;
    var id_frm;
    
    var initTable = function () {
        var param = {
            PeriodeSensus: $('#PeriodeSensus').val(),
            Status: $('#Status').val(),
        };

        $('#tablegrid').jqGrid("GridUnload");
        grid = $('#tablegrid').jqGrid({
                datatype: 'json',
                mtype: 'GET',
                url: base_url+'/approval/provinsi/data',
                pager: '#pagergrid',
                postData: param,
                shrinkToFit: pShrinkToFit,
                forceFit: pForceFit,
                sortable: true,
                //sortname: ['nama_provinsi', 'Status_Open'],
                multiSort: true,
                sortorder: 'asc',
                viewrecords: true,
                rownumbers: true,
                autowidth: true,
                reloadAfterSubmit: true,
                rowNum: 20,
                rowList: [10, 20, 30],
                colModel: [
                        { 
                            label: 'Id', 
                            name: 'id_provinsi',
                            align: 'center',
                            width: 30,
                        },{ 
                            label: 'Nama Provinsi', 
                            name: 'nama_provinsi',
                        },{ 
                            label: 'Periode Awal', 
                            name: 'Start_Date_Open',
                            align: 'center',
                            width: 30,
                        },{ 
                            label: 'Periode Akhir', 
                            name: 'End_Date_Open',
                            align: 'center',
                            width: 30,
                        },{ 
                            label: 'Status', 
                            name: 'Status_Open',
                            align: 'center',
                            width: 30,
                            cellattr: function(rowId, val) {
                                return val ? " style='color:#0093D1;'" : "";
                            },
                            formatter: function(val) {
                                return val ? 'Open' : 'Close';
                            }
                        },{
                            label: 'Action',
                            align: 'center',
                            width: 20,
                            formatter: function(val, opt, row) {                                

                                var btnClose = '';
                                var btnOpen = '';                                    
                                
                                if (row.Status_Open != null && row.status == '1') {
                                     if (row.Status_Open) {
                                        btnClose = '<button type="button" title="Tutup Data &quot;' + row.ID_Provinsi + '&quot;" class="btn btn-outline-warning btn-elevate btn-icon btn-sm btnClose" data-id_frm="' + row.ID_Provinsi + '"><i class="fa fa-lock"></i></button>&nbsp;';   
                                     } else {
                                        btnOpen = '<button type="button" title="Buka Data &quot;' + row.ID_Provinsi + '&quot;" class="btn btn-outline-danger btn-elevate btn-icon btn-sm btnOpen" data-id_frm="' + row.ID_Provinsi + '"><i class="fa fa-lock-open"></i></button>&nbsp;';   
                                     }
                                     
                                } else {
                                    btnClose = '';   
                                }

                                return btnClose + btnOpen;
                            }
                        }
                ],
            });

    }

    var btnCloseClick = function () {
        $(document).on('click', '.btnClose', function() {
            //e.preventDefault();
            var selRowId = $(this).data('id');
            var row = $('#tablegrid').jqGrid('getRowData', selRowId); 
            id_frm = $(this).attr("data-id_frm");
            $('#idprovinsi').val(id_frm);
            
            var param = {
                            Status: $('#Status').val(),
                            PeriodeSensus: $('#PeriodeSensus').val(),
                            idprovinsi : $('#idprovinsi').val(),
                            opendate : $('#opendate').val(),
                            closedate : $('#closedate').val(),
                        };                                                

                Swal.fire({
                    title: 'Tutup periode provinsi?',
                    //text: "Data Status Provinsi akan ditutup statusnya!",
                    //type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, tutup data!',
                    cancelButtonText: "Batalkan!",
                }).then((result) => {
                    if (result.value) {                         

                        KTApp.blockPage({
                            message: 'Harap tunggu!...'
                        }); 

                         var header = {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf_token,
                        };

                        $.ajax({
                            method: 'POST', // Type of response and matches what we said in the route
                            url: base_url + '/approval/provinsi/close/targets', // This is the url we gave in the route
                            data : param,
                            headers: header,                            
                            success: function(response) { // What to do if we succeed
                                if (response.status) {
                                    Swal.fire(
                                        'Validate!',
                                        response.message,
                                        'success'
                                    )
                                    //$('#modalDetail2').modal('hide');
                                    jQuery("#tablegrid").trigger("reloadGrid");
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                console.log(JSON.stringify(jqXHR));
                                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                            }
                        }).always(function() {
                            KTApp.unblock();
                        });
                    } // end result
                }) // end then
        });
    }
    
    var btnOpenClick = function () {
       $(document).on('click', '.btnOpen', function() {
            //e.preventDefault();
          
            var selRowId = $(this).data('id');
            var row = $('#tablegrid').jqGrid('getRowData', selRowId);
            id_frm = $(this).attr("data-id_frm");
            $('#idprovinsi').val(id_frm);
            $('#modalDetail2').modal('show');
           
        });
    }

    var btnSaveClick = function () {
        $(document).on('click', '.btnSave', function() {
            //e.preventDefault();

            var selRowId = $(this).data('id');
            var row = $('#tablegrid').jqGrid('getRowData', selRowId);
        
             

            var $from = Date.parse($('#opendate').val());//$("#opendate").datepicker('getDate');
            var $to   = Date.parse($('#closedate').val());//$("#closedate").datepicker('getDate');

            if($from > $to || $from == $to) {                
                toastr.error('Periode awal tidak boleh lebih/sama dari periode akhir');
            } else {
                 $('#modalDetail2').modal('hide');   
                 var param = {
                                Status: $('#Status').val(),
                                PeriodeSensus: $('#PeriodeSensus').val(),
                                idprovinsi : $('#idprovinsi').val(),
                                opendate : $('#opendate').val(),
                                closedate : $('#closedate').val(),
                            };                        
                
                    //console.log(param);
                    Swal.fire({
                        title: 'Apakah anda yakin?',
                        text: "Data Status Provinsi akan dibuka statusnya!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Buka data!',
                        cancelButtonText: "Batalkan!",
                    }).then((result) => {
                        if (result.value) {                         

                            KTApp.blockPage({
                                message: 'Harap tunggu!...'
                            });

                             var header = {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf_token,
                            };

                            $.ajax({
                                method: 'POST', // Type of response and matches what we said in the route
                                url: base_url + '/approval/provinsi/open/targets', // This is the url we gave in the route
                                data : param,
                                headers: header,                            
                                success: function(response) { // What to do if we succeed
                                    if (response.status) {
                                        Swal.fire(
                                            'Validate!',
                                            response.message,
                                            'success'
                                        )
                                        $('#modalDetail2').modal('hide');
                                        jQuery("#tablegrid").trigger("reloadGrid");
                                        KTApp.unblockPage();
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                                    console.log(JSON.stringify(jqXHR));
                                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                                }
                            }).always(function() {
                                KTApp.unblock();
                            });
                        } // end result
                    }) // end then            
            } // end if check tanggal
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
            btnOpenClick();
            btnCloseClick();
            btnSaveClick();
		}
	};
}();

jQuery(document).ready(function() {
	AppProvinsi.init();
});