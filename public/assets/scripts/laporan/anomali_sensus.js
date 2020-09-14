// "use strict";

var AnomaliSensus = function() {
	var a = '';
	var b = '';
	var q = '';
	var message = '';
	var id_frm;
	var globalparam;
	var gwdth;
	var _Kelurahan;
	var _RW;
	var _RT;

	var color = ['#ffeb99', '#f19292', '#424874', '#cbe2b0'];

	var select2Handler = function() {

		$('#Indikator').select2({
			minimumResultsForSearch: -1,
		})

		$('#Kelurahan').select2({
				placeholder: '--- Pilih Kelurahan ---',
				allowClear: true
			})
			.on('change', function() {
				getRW($(this).val());
			}).trigger('change');;

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
					$('.div-pendata').hide();
				} else {
					$('.div-wilayah').hide();
					$('.div-pendata').show();
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
				toastr.error('getRT: ' + jqXHR.statusText);
			})
			.always(function() {
				KTApp.unblockPage();
			});
	}

	var grid;

	var initPivot = function(param) {
		$("#sidebar-menu-toggle").click();
		// $.jgrid.defaults.styleUI = 'Bootstrap4';
		// $.jgrid.defaults.iconSet = "Iconic";
		// $.jgrid.defaults.iconSet = "fontAwesome";

		$('#tablemon').jqGrid("GridUnload");

		var param = {
			Indikator: $('#Indikator').val(),
			PeriodeSensus: $('#PeriodeSensus').val(),
			JenisData: $('#JenisData').val(),
			Kelurahan: $('#Kelurahan').val(),
			RW: $('#RW').val(),
			RT: $('#RT').val(),
			Pendata: $('#Pendata').val(),
			level : 1,
		};

		var indikatorY = ['Pendidikan', 'Perkawinan', 'Pekerjaan'];
		var dimensionYCol = indikatorY[parseInt(param.Indikator) - 1];
		var querystr = $.param(param);
		gwdth = $('#pagermon').width();

		KTApp.blockPage({
			message: 'Harap tunggu!...'
		});

		$("#tablemon").jqGrid({
            url: base_url + '/laporan/anomalisensus/data?' + querystr,
            mtype: "GET",
            datatype: "json",
            //iconSet: "fontAwesome",
        	//guiStyle: "bootstrap4",
        	viewrecords: true,
            page: 1,
            colModel: [
                { label: 'Id', name: 'id_kelurahan', key: true, width: 0, hidden:true },
                { label: 'Kelurahan', name: 'nama_kelurahan', width: 50 ,  cellattr: function(rowId, val, rawObject) {
					    if (parseFloat(val) != null) {
					        return "style='padding:10px;'";						       
					    }
					} 
				},
                { label: 'Sekolah', name: 'anomali_pendidikan1', width: 35, align: 'right',
	                cellattr: function(rowId, val, rawObject) {
					    if (parseFloat(val) > 0) {
					        return " class='blue'";						       
					    }
					} 
				},
                { label: 'Berstatus Kawin', name: 'anomali_perkawinan1', width: 35, align: 'right',
	                cellattr: function(rowId, val, rawObject) {
					    if (parseFloat(val) > 0) {
					        return " class='yellow'";						       
					    }
					} 
				},
                { label: 'Berstatus Bekerja', name: 'anomali_perkerjaan1', width: 35, align: 'right',
	                cellattr: function(rowId, val, rawObject) {
					    if (parseFloat(val) > 0) {
					        return " class='gray'";						       
					    }
					} 
				},
                { label: 'Sebagai Kepala Keluarga', name: 'anomali_keluarga1', width: 35, align: 'right', 
                	cellattr: function(rowId, val, rawObject) {
					    if (parseFloat(val) > 0) {
					        return " class='red'";						       
					    }
					}
				},
                { label: 'NIK', name: 'anomali_nik1', width: 35, align: 'right',
	                cellattr: function(rowId, val, rawObject) {
					    if (parseFloat(val) > 0) {
					        return " class='green'";						       
					    }
					}
				}
            ],
			loadonce: true,             
           	pager: '#pagermon',
			//shrinkToFit: false,
			forceFit: true,
			sortable: true,
			viewrecords: true,
			rownumbers: true,
			autowidth: true,
			emptyrecords: "No records to display",
			gridComplete: LoadComplete,
			emptyDataText:'No records to display', // you can name this parameter whatever you want.
			subGridOptions: {
	            "plusicon"  : "ui-icon-triangle-1-e",
	            "minusicon" : "ui-icon-triangle-1-s",
	            "openicon"  : "ui-icon-arrowreturn-1-e"
	        },
			// rowNum: 20,
			rowList: [20, 30, 50],			
			subGrid: true, // set the subGrid property to true to show expand buttons for each row
            subGridRowExpanded: showRW, // javascript function that will take care of showing the child grid
            loadComplete: function() {
            	    //$("#tablemon").jqGrid('setGridWidth', gwdth, true); 
					KTApp.unblockPage();
			},
        });
        jQuery("#tablemon").jqGrid('setGroupHeaders', {
		  useColSpanStyle: true, 
		  groupHeaders:[			
			{startColumnName: 'anomali_pendidikan1', numberOfColumns: 1, titleText: 'Individu usia <5 tahun'},
			{startColumnName: 'anomali_perkawinan1', numberOfColumns: 3, titleText: 'individu <10 tahun'},
			{startColumnName: 'anomali_nik1', numberOfColumns: 1, titleText: 'Individu > 17 tahun'},
		  ]
		});
        jQuery("#tablemon").jqGrid('setGroupHeaders', {
		  useColSpanStyle: true, 
		  groupHeaders:[
			{startColumnName: 'anomali_pendidikan', numberOfColumns: 1, titleText: 'berstatus Sekolah'},
			{startColumnName: 'anomali_perkawinan', numberOfColumns: 1, titleText: 'berstatus kawin'},
			{startColumnName: 'anomali_perkerjaan', numberOfColumns: 1, titleText: 'berstatus bekerja'},
			{startColumnName: 'anomali_keluarga', numberOfColumns: 1, titleText: 'sebagai Kepala Keluarga'},
			{startColumnName: 'anomali_nik', numberOfColumns: 1, titleText: 'NIK 99999999999'},
		  ]
		});
		 		

		window.onerror = function(message, source, lineno, colno, error) {
			alert('Data Tidak Ditemukan');
			KTApp.unblockPage();

		}

	}

	function LoadComplete()
	{
	    if ($('#tablemon').getGridParam('records') == 0) // are there any records?
	       swal.fire('', 'Tidak Ada Data Anomali di Wilayah User', 'error');
	    
	}

	function showRW(parentRowID, parentRowKey) {

			var param = {
				Indikator: $('#Indikator').val(),
				PeriodeSensus: $('#PeriodeSensus').val(),
				JenisData: $('#JenisData').val(),
				Kelurahan: parentRowKey,
				RW: $('#RW').val(),
				RT: $('#RT').val(),
				Pendata: $('#Pendata').val(),
				level : 2,
			};		
			
			var querystr = $.param(param);
			_Kelurahan = parentRowKey;

            var childGridID = parentRowID + "_table";
            var childGridPagerID = parentRowID + "_pager";

            // send the parent row primary key to the server so that we know which grid to show
            var childGridURL = parentRowKey+".json";

            console.log(parentRowKey);

            // add a table and pager HTML elements to the parent grid row - we will render the child grid here
            $('#' + parentRowID).append('<table id=' + childGridID + '></table><div id=' + childGridPagerID + ' class=scroll></div>');

            $("#" + childGridID).jqGrid({
                url: base_url + '/laporan/anomalisensus/data?' + querystr,
                mtype: "GET",
                datatype: "json",
                page: 1,
                colModel: [
                    { label: 'Id', name: 'id_rw', key: true, width: 150, hidden:true },
	                { label: 'RW', name: 'nama_rw', width: 37,  cellattr: function(rowId, val, rawObject) {
						    if (parseFloat(val) != null) {
						        return "style='padding:10px;'";						       
						    }
						} 
					},
	                { label: '', name: 'anomali_pendidikan', width: 35, align: 'right',
		                cellattr: function(rowId, val, rawObject) {
						    if (parseFloat(val) > 0) {
						        return " class='blue'";						       
						    }
						} 
					},
	                { label: '', name: 'anomali_perkawinan', width: 35, align: 'right',
		                cellattr: function(rowId, val, rawObject) {
						    if (parseFloat(val) > 0) {
						        return " class='yellow'";						       
						    }
						} 
					},
	                { label: '', name: 'anomali_perkerjaan', width: 35, align: 'right',
		                cellattr: function(rowId, val, rawObject) {
						    if (parseFloat(val) > 0) {
						        return " class='gray'";						       
						    }
						} 
					},
	                { label: '', name: 'anomali_keluarga', width: 35, align: 'right', 
	                	cellattr: function(rowId, val, rawObject) {
						    if (parseFloat(val) > 0) {
						        return " class='red'";						       
						    }
						}
					},
	                { label: '', name: 'anomali_nik', width: 35, align: 'right',
		                cellattr: function(rowId, val, rawObject) {
						    if (parseFloat(val) > 0) {
						        return " class='green'";						       
						    }
						}
					},
                ],
				//shrinkToFit: false,
				//forceFit: true,
				sortable: true,
				viewrecords: true,
				rownumbers: true,
				autowidth: true,
				// rowNum: 20,
				rowList: [20, 30, 50],
				subGrid: true,// set the subGrid property to true to show expand buttons for each row
                subGridRowExpanded: showRT, // javascript function that will take care of showing the child grid
                pager: "#" + childGridPagerID,
                 loadComplete: function() {
            	    //$("#" + childGridID).jqGrid('setGridWidth', gwdth - 50, true); 
					KTApp.unblockPage();
				},
            });

        }


        function showRT(parentRowID, parentRowKey) {

			var param = {
				Indikator: $('#Indikator').val(),
				PeriodeSensus: $('#PeriodeSensus').val(),
				JenisData: $('#JenisData').val(),
				Kelurahan: _Kelurahan,
				RW: parentRowKey,
				RT: $('#RT').val(),
				Pendata: $('#Pendata').val(),
				level : 3,
			};		
			
			var querystr = $.param(param);
				_RW = parentRowKey;

            var childGridID = parentRowID + "_table";
            var childGridPagerID = parentRowID + "_pager";

            // send the parent row primary key to the server so that we know which grid to show
            //var childGridURL = parentRowKey+".json";

            console.log(parentRowKey);

            // add a table and pager HTML elements to the parent grid row - we will render the child grid here
            $('#' + parentRowID).append('<table id=' + childGridID + '></table><div id=' + childGridPagerID + ' class=scroll></div>');

            $("#" + childGridID).jqGrid({
                url: base_url + '/laporan/anomalisensus/data?' + querystr,
                mtype: "GET",
                datatype: "json",
                page: 1,
                colModel: [
                    { label: 'Id', name: 'id_rt', key: true, width: 10, hidden:true },                                        
	                { label: 'RT', name: 'nama_rt', width: 29, cellattr: function(rowId, val, rawObject) {
						    if (parseFloat(val) != null) {
						        return "style='padding:10px;'";						       
						    }
						} 
				 	},
	               	{ label: '', name: 'anomali_pendidikan', width: 35, align: 'right',
		                cellattr: function(rowId, val, rawObject) {
						    if (parseFloat(val) > 0) {
						        return " class='blue'";						       
						    }
						} 
					},
	                { label: '', name: 'anomali_perkawinan', width: 35, align: 'right',
		                cellattr: function(rowId, val, rawObject) {
						    if (parseFloat(val) > 0) {
						        return " class='yellow'";						       
						    }
						} 
					},
	                { label: '', name: 'anomali_perkerjaan', width: 35, align: 'right',
		                cellattr: function(rowId, val, rawObject) {
						    if (parseFloat(val) > 0) {
						        return " class='gray'";						       
						    }
						} 
					},
	                { label: '', name: 'anomali_keluarga', width: 35, align: 'right', 
	                	cellattr: function(rowId, val, rawObject) {
						    if (parseFloat(val) > 0) {
						        return " class='red'";						       
						    }
						}
					},
	                { label: '', name: 'anomali_nik', width: 35, align: 'right',
		                cellattr: function(rowId, val, rawObject) {
						    if (parseFloat(val) > 0) {
						        return " class='green'";						       
						    }
						}
					},
                ],
				//shrinkToFit: false,
				//forceFit: true,
				sortable: true,
				viewrecords: true,
				rownumbers: true,
				autowidth: true,				
				// rowNum: 20,
				rowList: [20, 30, 50],
				subGrid: false,// set the subGrid property to true to show expand buttons for each row
                //subGridRowExpanded: showRT,// set the subGrid property to true to show expand buttons for each row
                //subGridRowExpanded: showThirdLevelChildGrid, // javascript function that will take care of showing the child grid
                pager: "#" + childGridPagerID,
                 loadComplete: function() {
            	    //$("#" + childGridID).jqGrid('setGridWidth', gwdth - 100, true); 
					KTApp.unblockPage();
				},

				//onCellSelect: function(rowid, iCol, val, e) {
				onCellSelect: function (rowId, iCol, content, event) {
					
					var rowData = $(this).jqGrid("getRowData", rowId);
					var param = {
						Indikator: $('#Indikator').val(),
						PeriodeSensus: $('#PeriodeSensus').val(),
						JenisData: $('#JenisData').val(),
						Kelurahan: _Kelurahan,
						RW: _RW,
						RT: rowData.id_rt,
						Pendata: $('#Pendata').val(),
						level : 4,
						iCol  : iCol,
						limit : content,
					};	
					globalparam = param;
					console.log(rowData);

					if (content > 0) {
						console.log(param);	
						showDetail(param);	
					} else {
						swal.fire('', 'Tidak Ada Data Anomali', 'error');
					}
					
					
				},
				
            });

        }



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
			globalparam = param;

			console.log(globalparam);
			initPivot(param);
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

		KTApp.blockPage({
			message: 'Harap tunggu!...'
		});

		$('#tableroles').jqGrid({
			datatype: 'json',
			mtype: 'GET',
			url: base_url + '/laporan/anomalisensus/datapaging?' + querystr,
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
					hidden : true,
				}, {
					name: 'no_urutrmh',
					label: 'No Rumah',
					//sortable: 'asc',
				},  {
					name: 'no_urutkel',
					label: 'No Urut Kel.'
				},  {
					name: 'nama_anggotakel',
					label: 'Nama'
				}, {
					name: 'nik',
					label: 'N.I.K'
				},  {
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

						var disabled = row.status_sensus == '3' ? ' btn btn-outline-success btn-elevate btn-icon btn-sm btnValid" data-rowid="'+ opt.rowId + ' ' : ' btn btn-outline-warning btn-elevate btn-icon btn-sm disabled ';
						var valid = row.status_sensus == '3' ? ' Validate data : ' + row.id_frm + ' ' : ' Record cannot be validate.! ';
						var btnEdit = '<button type="button" title="View Data &quot;' + row.id_frm + '&quot;" class="btn btn-outline-primary btn-elevate btn-icon btn-sm btnView" data-id_frm="' + row.id_frm + '"><i class="fa fa-file-alt "></i></button>&nbsp;';
						//var btnEdit = '';
						var btnReset = '<button type="button" title="' + valid + '" class="' + disabled + '"><i class="fa flaticon2-accept"></i></button>&nbsp;';
						return btnEdit + btnReset;
					}
				}
			], loadComplete: function() {
				    gwdthdtl = $('#pagerroles').width();
            	    $("#tableroles").jqGrid('setGridWidth', gwdthdtl, true); 
					KTApp.unblockPage();
				},
		});
	}

	var btnValidHandler = function() {
		$(document).on('click', '.btnValid', function() {
			var rowid = $(this).data('rowid');
    		var rowData = jQuery('#tableroles').jqGrid ('getRowData', rowid.trim());
			Swal.fire({
				title: 'Apakah Anda Yakin?',
				text: "Data akan Diupdate, menjadi Valid !",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Yakin!'
			}).then((result) => {
				if (result.value) {
					KTApp.block('#modalDetail2', {
						message: 'Cari Data Anomali..Harap tunggu...'
					});
					$.ajax({
						method: 'GET', // Type of response and matches what we said in the route
						data:rowData,
						url: base_url + '/laporan/validmefirst/', // This is the url we gave in the route
						success: function(response) { // What to do if we succeed
							if (response.status) {
								Swal.fire({
									title: 'Apakah Anda Yakin?',
									text: response.message,
									type: 'warning',
									showCancelButton: true,
									confirmButtonColor: '#3085d6',
									cancelButtonColor: '#d33',
									confirmButtonText: 'Ya, Yakin!'
								}).then((result) => {

									KTApp.block('#modalDetail2', {
										message: 'Update Data..Harap tunggu...'
									});
									$.ajax({
										method: 'GET', // Type of response and matches what we said in the route
										data:rowData,
										url: base_url + '/laporan/validme/', // This is the url we gave in the route
										success: function(response) { // What to do if we succeed
											if (response.status) {												
												$('#tableroles').trigger( 'reloadGrid' );
												Swal.fire( '', response.message, 'success' );
												//$('#modalDetail2').modal('hide');
												KTApp.unblock('#modalDetail2');
											}
										},
										error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
											console.log(JSON.stringify(jqXHR));
											console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
										}
									}).always(function() {
										KTApp.unblock('#modalDetail2');
									});	// end ajax2 
								}) // end SWALL 2													
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

	var printPDF = function() {
		$('#btnPrint').click(function(e) {
			e.preventDefault();
			if ($('#PeriodeSensus').val() == '') {
				swal.fire('', 'Periode Pendataan belum dipilih', 'error');
				return false;
			}

			var param = {
				Indikator: $('#Indikator').val(),
				PeriodeSensus: $('#PeriodeSensus').val(),
				JenisData: $('#JenisData').val(),
				Kelurahan: $('#Kelurahan').val(),
				Pendata: $('#Pendata').val(),
				RW: $('#RW').val(),
				RT: $('#RT').val(),
				print: 1,
			};
			var querystr = $.param(globalparam); //$.param(param);
			//var url = base_url + '/laporan/cetak?' + querystr;
			var url = base_url + '/laporan/data_pdf?' + querystr;

			$('<a href="' + url + '" target="_blank">&nbsp;</a>')[0].click();

		});
	}

	return {
		init: function() {
			select2Handler();
			showReport();
			modalShowCallback();
			btnValidHandler();
			printPDF();
		}
	};
}();


jQuery(document).ready(function() {
	AnomaliSensus.init();
});
