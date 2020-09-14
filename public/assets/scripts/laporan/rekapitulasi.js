// "use strict";

var Rekapitulasi = function() {
	var a = '';
	var b = '';
	var q = '';
	var message = '';
	var id_frm;
	var globalparam;
	var list_kelurahan = [];;
	var list_rw = [];
	var list_rt = [];
	var jsonObj=[];


	var select2Handler = function() {

		
		$('#Indikator').select2({
			minimumResultsForSearch: -1,
		});

		$('#Kelurahan').select2({
				placeholder: '--- Pilih Kelurahan ---',
				allowClear: true
			})
			.on('change', function() {
								
				 if( $('#Kelurahan :selected').length > 0){
			        //build an array of selected values
			        var selectednumbers = [];
			        $('#Kelurahan :selected').each(function(i, selected) {
			            list_kelurahan[i] = $(selected).val();
			        });
			         
			        //console.log(list_kelurahan);
					getRW(list_kelurahan);
			    }	
			   
				//getRW(selectednumbers);
			}).trigger('change');

		$('#RW').select2({
				placeholder: '--- Pilih RW ---',
				allowClear: true
			})
			.on('change', function() {
				//getRT($(this).val());
				if( $('#RW :selected').length > 0){
			        //build an array of selected values
			        var selectednumbers = [];
			        $('#RW :selected').each(function(i, selected) {
			            list_rw[i] = $(selected).val();
			        });
			         
			        console.log(list_rw);
					getRT(list_rw);
			    }	
			});

		$('#RT').select2({
			placeholder: '--- Pilih RT ---',
			allowClear: true
		})
		.on('change', function() {
				//getRT($(this).val());
				if( $('#RT :selected').length > 0){
			        //build an array of selected values
			        var selectednumbers = [];
			        $('#RT :selected').each(function(i, selected) {
			            list_rt[i] = $(selected).val();
			        });
			         
			        console.log(selectednumbers);
					//getRT(selectednumbers);
			    }	
			});

		$('#Pendata').select2({
			// placeholder: '--- Pilih Pendata ---',
			// allowClear: true
		})


		$('#JenisData').select2({
				placeholder: '--- Pilih Jenis Data ---',
				minimumResultsForSearch: -1,
			})
			.on('change', function() {
				if ($(this).val() == 1) {
					$('.div-wilayah').show();
					$('.div-rw').hide();
					$('.div-rt').hide();
					$('.div-pendata').hide();
				} else if ($(this).val() == 2) {
					$('.div-rw').show();
					$('.div-wilayah').show();
					$('.div-rt').hide();
					$('.div-pendata').hide();
				} else {
					$('.div-wilayah').show();
					$('.div-rt').show();
					$('.div-rw').show();
					$('.div-pendata').hide();
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
				url: base_url + '/wilayah/rws',
				method: 'GET',
		 		data: {'data' : id_kelurahan},
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
				url: base_url + '/wilayah/rts',
				method: 'GET',
				dataType: 'json',
				data: {'data' : id_rw},
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
				toastr.error('getRT: ' + jqXHR.statusText);
			})
			.always(function() {
				KTApp.unblockPage();
			});
	}

	var grid;

	/*var initPivot = function(param) {
		"use strict";

		$('#tablemon').jqGrid("GridUnload");

		var ket1 = '';
		var ket2 = '';
		var jnsdata =$('#JenisData').val();
		if (jnsdata == '1') {
			ket1 = 'Kode Kelurahan';
			ket2 = 'Nama Kelurahan';
		} 
		else if (jnsdata == '2') {
			ket1 = 'Kode RW';
			ket2 = 'Nama RW';
		} else {
			ket1 = 'Kode RT';
			ket2 = 'Nama RT';
		}		

		var querystr = $.param(param);
		var active = false;

	     var data = [];
	    //         { kode: '1', nama: 'john dillon', city: 'london',   active: false },
	    //         { kode: '2', nama: 'marcus maxi', city: 'chicago',  active: false },
	    //         { kode: '3', nama: 'fedro james', city: 'new york', active: false },
	    //         { kode: '4', nama: 'alias hue',   city: 'georgia',  active: false },
	    //         { kode: '5', nama: 'greg finto',  city: 'st louis', active: false }
	    //     ];

	    //data = base_url + '/laporan/rekapitulasi/data?' + querystr;

	    KTApp.blockPage({
			message: 'Harap tunggu!...'
		});

	    $("#tablemon").jqGrid({
	        url: base_url + '/laporan/rekapitulasi/data?' + querystr,
			datatype: "json",
			//data : data,
	        colNames: [ket1, ket2, 'Jml KK yg Ada', 'Jml KK yg didata', 'Jml PUS Peserta KB', 'Jml PUS Bukan Peserta KB', 'Jml PUS Hamil', 'Action'],
	        colModel: [
	            { name: 'kode', sorttype: "int", width: 25  },
	            { name: 'nama', width: 80  },
	            { name: 'Target_KK', width: 50 , align: 'right', },
	            { name: 'jml_pus', width: 50 , align: 'right', },
	            { name: 'jml_pus_kb', width: 50 , align: 'right', },
	            { name: 'jml_pus_nonkb', width: 50 , align: 'right', },
	            { name: 'jml_pus_hamil', width: 50 , align: 'right', },
	            //{ name: 'city' },
	            { name: 'active', width: 25, align: 'center',
	                edittype: 'checkbox',
	                editoptions: { value: 'Yes:No', defaultValue: 'Yes' },
	                formatoptions: { disabled: false},
	                formatter: function (cellvalue, options, rowObject) {
	                    return '<input type="checkbox" id="cbPassed-' + rowObject.kode +
	                        (rowObject.active === true ? '" checked="checked" />' : '" />');
	                }
	            }
	        ],
	        cmTemplate: { width: 220 },
	        beforeSelectRow: function (rowid, e) {
	            var $self = $(this), $td = $(e.target).closest("tr.jqgrow>td"),
	                iCol = $td.length > 0 ? $td[0].cellIndex : -1,
	                cmName = iCol >= 0 ? $self.jqGrid("getGridParam", "colModel")[iCol].name : "",
	                localData = $self.jqGrid("getLocalRow", rowid);
	            if (cmName === "active" && $(e.target).is("input[type=checkbox]")) {
	                 //localData.active = $(e.target).is(":checked");
	                 active = $(e.target).is(":checked");
	            }

	            return true;
	        },
	        /*threeStateSort: true,
	        autoencode: true,
	        sortname: "id",
	        viewrecords: true,
	        sortorder: "asc",
	        shrinkToFit: false,
	        caption: 'samples'*/
	        /*forceFit: true,
			sortable: true,
			viewrecords: true,
			rownumbers: true,
			autowidth: true,
			caption: 'Data PUS',
			loadComplete: function() {
            	    //$("#tablemon").jqGrid('setGridWidth', gwdth, true); 
					KTApp.unblockPage();
			},
	    });

	} */

	var grid;
    var initPivot = function (param) {

    	var ket1 = '';
		var ket2 = '';
		var jnsdata =$('#JenisData').val();
		if (jnsdata == '1') {
			ket1 = 'Kode Kelurahan';
			ket2 = 'Nama Kelurahan';
		} 
		else if (jnsdata == '2') {
			ket1 = 'Kode RW';
			ket2 = 'Nama RW';
		} else {
			ket1 = 'Kode RT';
			ket2 = 'Nama RT';
		}		

		var querystr = $.param(param);
		var active = false;

        var header = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf_token,
        };
        if (grid != null) {
            $('#tablemon').DataTable().clear().destroy();
            $('#tablemon').empty();
        }
        grid = $('#tablemon').DataTable({
            responsive: true,
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            ajax: {
                /*url: base_url+'/laporan/targetactual/data',*/
                url: base_url + '/laporan/rekapitulasi/data?' + querystr,
                type: 'GET',
                headers: header,
                data: param,
            },
            order: [1, 'asc'],
            columns: [
                {  // 'Jml KK yg Ada', 'Jml KK yg didata', 'Jml PUS Peserta KB', 'Jml PUS Bukan Peserta KB', 'Jml PUS Hamil', 
                    title: ket1,
                    data: 'kode',
                }, { 
                    title: ket2, 
                    data: 'nama',
                }, { 
                    title: 'Jml KK yg Ada', 
                    data: 'Target_KK',
                    align: 'right',
                }, { 
                    title: 'Jml KK yg didata', 
                    data: 'jml_pus',
                    align: 'right',
                }, { 
                    title: 'Jml PUS Peserta KB', 
                    data: 'jml_pus_kb',
                    align: 'right',
                }, { 
                    title: 'Jml PUS Bukan Peserta KB', 
                    data: 'jml_pus_nonkb',
                    align: 'right',
                }, { 
                    title: 'Jml PUS Hamil', 
                    data: 'jml_pus_hamil',
                    className: 'text-right',
                }, { 
                    title: 'View',
                    data: '',
                    className: 'text-center',
                    render: function(data, type, row){
                        var btnView = '<button type="button" title="View Data Detail" class="btn btn-outline-brand btn-icon btn-sm btnView" data-id_pendata="'+row.UserName+'"><i class="fa fa-file-alt"></i></button>&nbsp;';
                    return btnView;
                    }
                }, { 
                    title: 'Pilih Cetak',
                    data: '',
                    className: 'text-center',
                    render: function(data, type, row){
                        var btnView = '<label class="kt-checkbox kt-checkbox--brand"><input type="checkbox" class="ckPrint" data-id="'+row.kode+'">&nbsp;<span></span></label>';
                    return btnView;
                    }
                },
            ],
        });
    }

	var btnShowDataHandler = function() {
		$('#btnShowData').click(function(e) {
				var myGrid = $('#tablemon'), i, rowData, names = [],
		            rowIds = myGrid.jqGrid("getDataIDs"),
		            n = rowIds.length;

				var selectedRow =  $("#tablemon").jqGrid('getGridParam', 'selrow');
				var selectedIndex = $("#tablemon").jqGrid('getInd', selectedRow);

				console.log(selectedIndex);


		        //console.log(JSON.stringify(rowIds));
		        for (i = 0; i < n; i++) {
		            rowData = myGrid.jqGrid("getLocalRow", rowIds[i]);
		            if (rowData.active) {
		                names.push(rowData.nama);
		            }
		            console.log('selected row data:'+ JSON.stringify(rowData));
		        }

				        console.log(names);
				        alert(names.join("; "));

        		});
	}

	var btnViewHandler = function() {
		$(document).on('click', '.btnShow', function() {
			var selRowId = $(this).data('id');
			var rows = $('#tablemon').jqGrid('getRowData', selRowId);

			//console.log(row);

			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Validate it!'
			}).then((result) => {
				if (result.value) {
					KTApp.block('#modalDetail2', {
						message: 'Harap tunggu...'
					});

					//var rows = $("#tablemon").jqxGrid('selectedrowindexes');
                	var selectedRecords = new Array();

					for (var m = 0; m < rows.length; m++) {
	                    var row = $("#tablemon").jqGrid('getrowdata', rows[m]);
	                    selectedRecords[selectedRecords.length] = row;
	                }

	                alert (selectedRecords);

					// $.ajax({
					// 	method: 'GET', // Type of response and matches what we said in the route
					// 	url: base_url + '/laporan/validme/' + selRowId, // This is the url we gave in the route
					// 	success: function(response) { // What to do if we succeed
					// 		if (response.status) {
					// 			Swal.fire(
					// 				'Validate!',
					// 				response.message,
					// 				'success'
					// 			)
					// 			$('#modalDetail2').modal('hide');
					// 		}
					// 	},
					// 	error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
					// 		console.log(JSON.stringify(jqXHR));
					// 		console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
					// 	}
					// }).always(function() {
					// 	KTApp.unblock('#modalDetail2');
					// });
				} // end result
			}) // end then
		});
	}

	// jQuery("#tablemon").click(function () {
 //        var s;
 //        s = jQuery("#tablemon").jqGrid('getGridParam', 'selarrrow');
 //        alert(s);
 //    });

	

	var showReport = function() {
		$('#btnShow').click(function(e) {
			e.preventDefault();

			if ($('#PeriodeSensus').val() == '') {
				swal.fire('', 'Periode Pendataan belum dipilih', 'error');
				return false;
			}

			if ($('#JenisData').val() == '') {
				swal.fire('', 'Jenis Data belum dipilih', 'error');
				return false;
			}

			var param = {
				Indikator: $('#Indikator').val(),
				PeriodeSensus: $('#PeriodeSensus').val(),
				JenisData: $('#JenisData').val(),
				Kelurahan: $('#Kelurahan').val(),
				RW: $('#RW').val(),
				RT: $('#RT').val(),
				Pendata: $('#Pendata').val(),
			};
			initPivot(param);
		});
	}

	var approve = function() {
		$('#btnApp').click(function(e) {
			e.preventDefault();

			if ($('#PeriodeSensus').val() == '') {
				swal.fire('', 'Periode Pendataan belum dipilih', 'error');
				return false;
			}

			if ($('#JenisData').val() == '') {
				swal.fire('', 'Jenis Data belum dipilih', 'error');
				return false;
			}

			var param = {
				Indikator: $('#Indikator').val(),
				PeriodeSensus: $('#PeriodeSensus').val(),
				JenisData: $('#JenisData').val(),
				Kelurahan: $('#Kelurahan').val(),
				Kecamatan: $('#id_kecamatan').val(),
				RW: $('#RW').val(),
				RT: $('#RT').val(),
				Pendata: $('#Pendata').val(),
			};
			
			globalparam = param;

			Swal.fire({
				title: 'Apakah Anda Yakin?',
				text: "Data akan di Approve (close) !",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Yakin!'
			}).then((result) => {
				if (result.value) {
					KTApp.blockPage({
		                overlayColor: "#000000",
		                type: "v2",
		                state: "success",
		                message: "Please wait..."
		            });
					$.ajax({
						method: 'GET', // Type of response and matches what we said in the route
						data : param, 
						url: base_url + '/laporan/approve/', // This is the url we gave in the route
						success: function(response) { // What to do if we succeed
							if (response.status) {
									location.reload();						 
								 KTApp.blockPage({
						                overlayColor: "#000000",
						                type: "v2",
						                state: "success",
						                message: "Please wait..."
						            }),
						            setTimeout(function() {						                
								        Swal.fire(
											'Validate!',
											response.message,
											'success'
										)	
										KTApp.unblockPage()
						            }, 2e3)
							}
						},
						error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
							console.log(JSON.stringify(jqXHR));
							console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
							KTApp.unblockPage();
						}
					}).always(function() {
						KTApp.blockPage({
			                overlayColor: "#000000",
			                type: "v2",
			                state: "success",
			                message: "Please wait..."
			            }),
			            setTimeout(function() {						                
							KTApp.unblockPage()
			            }, 2e3)
					});
				}
			});

			//console.log(globalparam);
			//initPivot(param);
		});
	}

	var showDetail = function(param) {
		globalparam = param;
		$('#modalDetail2').modal();
	}



	var modalShowCallback = function() {
		$('#modalDetail2').on('shown.bs.modal', function(e) {
			initTable(globalparam);
		});
	}

	var initTable = function(param) {
		var indikatorY = ['Pendidikan', 'Perkawinan', 'Pekerjaan'];
		var dimensionYCol = indikatorY[parseInt(param.Indikator) - 1];
		var querystr = $.param(param);

		$("#tableroles").jqGrid("GridUnload");

		$('#tableroles').jqGrid({
			datatype: 'json',
			mtype: 'GET',
			url: base_url + '/laporan/rekapitulasi/datapaging?' + querystr,
			pager: '#pagerroles',
			//postData: param,
			// pager: '#pagerview',
			shrinkToFit: false,
			forceFit: true,
			// sortable: true,
			headertitles: true,
			// viewrecords: true,
			// rownumbers: true,
			autowidth: true,
			rowNum: 100,
			colModel: [{
					label: 'id_frm',
					name: 'id_frm',
				}, {
					name: 'nik',
					label: 'NIK',
					//sortable: 'asc',
				}, {
					name: 'nama_anggotakel',
					label: 'Nama'
				}, {
					name: 'no_urutkel',
					label: 'No Urut Kel.'
				}, {
					name: 'nama_provinsi',
					label: 'Provinsi',
				}, {
					name: 'nama_kabupaten',
					label: 'Kabupaten',
				}, {
					name: 'nama_kelurahan',
					label: 'Kelurahan',
				}, {
					name: 'nama_rw',
					label: 'RW',
				}, {
					name: 'nama_rt',
					label: 'RT',
				}, {
					label: 'Status',
					align: 'center',
					width: 90,
					formatter: function(val, opt, row) {
						var tittle;
						var btnClass;
						if (row.status_sensus == '1') {
							tittle = 'Valid';
							btnClass = 'kt-badge--success';
						} else if (row.status_sensus == '2') {
							tittle = "NotValid";
							btnClass = 'kt-badge--danger';
						} else if (row.status_sensus == '3') {
							tittle = "Anomali";
							btnClass = 'kt-badge--primary';
						} else if (row.status_sensus == '4') {
							tittle = "Anulir";
							btnClass = 'kt-badge--default';
						} else {
							tittle = "Received";
							btnClass = 'kt-badge--warning';
						}

						var span = '<span class="kt-badge ' + btnClass + ' kt-badge--inline">' + tittle + '</span>';
						return span;
					}
				},
				{
					label: 'Action',
					align: 'center',
					formatter: function(val, opt, row) {
						var disabled = row.status_sensus == '2' ? ' btn btn-outline-warning btn-elevate btn-icon btn-sm btnValid" data-id="' + row.id_frm + ' ' : ' btn btn-outline-warning btn-elevate btn-icon btn-sm disabled ';
						var valid = row.status_sensus == '2' ? ' Validate data : ' + row.id_frm + ' ' : ' Record cannot be validate.! ';
						var btnEdit = '<button type="button" title="View Data &quot;' + row.id_frm + '&quot;" class="btn btn-outline-success btn-elevate btn-icon btn-sm btnView" data-id_frm="' + row.id_frm + '"><i class="fa fa-file-alt "></i></button>&nbsp;';
						var btnReset = '<button type="button" title="' + valid + '" class="' + disabled + '"><i class="fa fa-envelope-open-text"></i></button>&nbsp;';
						return btnEdit + btnReset;
					}
				}
			],  loadComplete: function() {
				    gwdthdtl = $('#pagerroles').width();
            	    $("#tableroles").jqGrid('setGridWidth', gwdthdtl, true); 
					KTApp.unblockPage();
				},
		});
	}

	var btnValidHandler = function() {
		$(document).on('click', '.btnValid', function() {
			var selRowId = $(this).data('id');
			var row = $('#tableroles').jqGrid('getRowData', selRowId);

			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Validate it!'
			}).then((result) => {
				if (result.value) {
					KTApp.block('#modalDetail2', {
						message: 'Harap tunggu...'
					});
					$.ajax({
						method: 'GET', // Type of response and matches what we said in the route
						url: base_url + '/laporan/validme/' + selRowId, // This is the url we gave in the route
						success: function(response) { // What to do if we succeed
							if (response.status) {
								Swal.fire(
									'Validate!',
									response.message,
									'success'
								)
								$('#modalDetail2').modal('hide');
							}
						},
						error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
							console.log(JSON.stringify(jqXHR));
							console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
						}
					}).always(function() {
						KTApp.unblock('#modalDetail2');
					});
				} // end result
			}) // end then
		});
	}

	
	
	var print_tk = null;
	var print_id = [];
	var checkHandler = function() {
        $(document).on('click', '.ckPrint', function () {
			var kode = $(this).data('id');
			console.log('check', kode)
            if ($(this).is(':checked')) {
				print_id.push(kode);
			} else {
				var idx  = print_id.indexOf(kode);
				if (idx > -1) {
					print_id.splice(idx, 1);
				} 
			}
		});
	}

	var printPDF = function() {
		$('#btnPrint').click(function(e) {
			e.preventDefault();
			if ($('#PeriodeSensus').val() == '') {
				swal.fire('', 'Periode Pendataan belum dipilih', 'error');
				return false;
			}
			if (print_id.length<=0) {
				swal.fire('', 'Data yang akan dicetak belum dipilih', 'error');
				return false;
			}
			// console.log(print_id);
			// return false;
			// print_id.push(666);
			var param = {
				PeriodeSensus: $('#PeriodeSensus').val(),
				JenisData: $('#JenisData').val(),
				Kelurahan: $('#Kelurahan').val(),
				Pendata: $('#Pendata').val(),
				RW: $('#RW').val(),
				RT: $('#RT').val(),
				print_id: print_id,
				print: 1,
			};
			var querystr = $.param(param);
			// console.log(querystr)
			// return false;
			var url = base_url + '/laporan/rekapitulasi/cetak?' + querystr;

			$('<a href="' + url + '" target="_blank">&nbsp;</a>')[0].click();

		});
	}


	var getJSON = function(url, successHandler, errorHandler) {
  var xhr = typeof XMLHttpRequest != 'undefined'
    ? new XMLHttpRequest()
    : new ActiveXObject('Microsoft.XMLHTTP');
  xhr.open('get', url, true);
  xhr.onreadystatechange = function() {
    var status;
    var data;
    // https://xhr.spec.whatwg.org/#dom-xmlhttprequest-readystate
    if (xhr.readyState == 4) { // `DONE`
      status = xhr.status;
      if (status == 200) {
        data = JSON.parse(xhr.responseText);
        successHandler && successHandler(data);
      } else {
        errorHandler && errorHandler(status);
      }
    }
  };
  xhr.send();
};

	return {
		init: function() {
			select2Handler();
			showReport();
			modalShowCallback();
			btnValidHandler();
			btnViewHandler();
			btnShowDataHandler();
			printPDF();
			checkHandler();
			approve();
		}
	};
}();


jQuery(document).ready(function() {
	Rekapitulasi.init();
});
