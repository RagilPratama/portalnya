var WilayahIndex = function() {
    var hasApproved = $('#approved').val();
    var hasUpdate = $('#update').val();
	var kondisi;
	var id_prov;
	var id_kab; 
	var id_kec;
	var id_kel;
	var id_rw;
	var jqgridRow;
	var grid_id;

	var initTable = function() {

		$("#tableroles").jqGrid({
			datatype: 'json',
			url: base_url + '/wilayah/getDataProvinsi',
			colNames: ['ID', 'Nama Provinsi', 'Kd. Depdagri', 'Regional'],
			colModel: [{
					name: 'id_provinsi',
					index: 'id_provinsi',
					width: 20,
					editable: true,
					align: 'center',
					editoptions: {
						size: 10,
						readonly: 'readonly'
					},
				},
				{
					name: 'nama_provinsi',
					index: 'nama_provinsi',
					width: 350,
					editable: true
				},
				{
					name: 'KodeDepdagri',
					index: 'KodeDepdagri',
					width: 55,
					editable: true,
					align: 'center',
					editoptions: {
						size: 10,
						maxlength: 2
					}
				},
				{
					name: 'RegionalID',
					index: 'RegionalID',
					width: 55,
					sortable: false,
					editable: false,
					hidden: true,
					align: 'center'
				}
			],
			pager: '#pagerroles',
			shrinkToFit: pShrinkToFit,
			forceFit: pForceFit,
			sortable: true,
			viewrecords: true,
			rownumbers: true,
			autowidth: true,
			rowNum: 10,
			rowList: [5, 10, 20],
			caption: "Data Propinsi",
			editable: true,
			subGrid: true,
			//iconSet: "fontAwesome",
			subGridOptions: {
				expandOnLoad: false,
			}, // sub grid kabupaten --------------------------------------------------------- Here Kabupaten  -------------------------------
			subGridRowExpanded: function(subgrid_id, row_id) {
				subgrid_table_id = "minggus_" + subgrid_id + "_t";
				pager_id = "pager_" + subgrid_table_id;
				$("#" + subgrid_id).html("<table id='" + subgrid_table_id + "' class='scroll'></table><div id='" + pager_id + "' class='scroll'></div>");
				$("#" + subgrid_id).html();

				var key_id = '';
				var ret = $("#tableroles").getRowData(row_id); //get the selected row
				idprovinsi = ret['id_provinsi']; //get the data from selected row by column na
				nmprov = ret['nama_provinsi'];
				var idsubfungsi = row_id


				jQuery("#" + subgrid_table_id).jqGrid({
					datatype: "json",
					url: base_url + '/wilayah/getDataKabupaten/' + idprovinsi,
					emptyrecords: "No records to view",
					autowidth: true,
					rownumbers: true,
					colNames: ['ID', 'Nama Kabupaten', 'Kd.Depdagri', 'Action'],
					colModel: [{
						name: 'id_kabupaten',
						index: 'id_kabupaten',
						width: 22,
						editable: true,
						align: 'center'
					}, {
						name: 'nama_kabupaten',
						index: 'nama_kabupaten',
						width: 350,
						editable: true
					}, {
						name: 'KodeDepdagri',
						index: 'KodeDepdagri',
						width: 35,
						editable: true,
						align: 'center',
						editoptions: {
							size: 10,
							maxlength: 2
						}
					}, {
						name: '',
						index: '',
						align: 'center',
						width: 35,
						formatter: function(val, opt, row) {
							kondisi = 1;
							jqgridRow = opt;
							id_prov =idprovinsi;
							var disabled =' btn btn-outline-danger btn-elevate btn-icon btn-sm btnProv" data-id="' + row.id_kabupaten + ' ';
							var btnReset = '<button type="button" title="Pindah Provinsi" class="' + disabled + '"><i class="fa flaticon-placeholder-3"></i></button>&nbsp;';
							return '';
						}
					},  ],
					rowNum: -1,
					viewrecords: true,
					pager: pager_id,
					height: '100%',
					sortable: true,
					shrinkToFit: pShrinkToFit,
					forceFit: pForceFit,
					sortable: true,
					rowNum: 10,
					rowList: [5, 10, 20],
					caption: "Detail Kabupaten - Provinsi " + nmprov,
					subGrid: true,
					subGridOptions: {
						expandOnLoad: false,
						"plusicon": "ui-icon-triangle-1-e",
						"minusicon": "ui-icon-triangle-1-s",
						"openicon": "ui-icon-arrowreturn-1-e"
					}, // sub grid Kecamatan --------------------------------------------------------- Here  -------------------------------
					subGridRowExpanded: function(ssubgrid_id, row_id) {
						ssubgrid_table_id = "minggus_" + ssubgrid_id + "_t";
						ppager_id = "pager_" + ssubgrid_table_id;
						$("#" + ssubgrid_id).html("<table id='" + ssubgrid_table_id + "' class='scroll'></table><div id='" + ppager_id + "' class='scroll'></div>");
						$("#" + ssubgrid_id).html();

						var key_id = '';
						var ret = $("#" + subgrid_table_id).getRowData(row_id); //get the selected row
						idkabupaten = ret['id_kabupaten']; //get the data from selected row by column na
						nmkab = ret['nama_kabupaten'];
						var idsubfungsi = row_id

						//console.log(idfungsi);

						jQuery("#" + ssubgrid_table_id).jqGrid({
							datatype: "json",
							url: base_url + '/wilayah/getDataKecamatan/' + idkabupaten,
							emptyrecords: "No records to view",
							autowidth: true,
							rownumbers: true,
							colNames: ['ID', 'Nama Kecamatan', 'Kd.Depdagri', 'Action'],
							colModel: [{
								name: 'id_kecamatan',
								index: 'id_kecamatan',
								width: 30,
								editable: true,
								align: 'center'
							}, {
								name: 'nama_kecamatan',
								index: 'nama_kecamatan',
								width: 350,
								editable: true
							}, {
								name: 'KodeDepdagri',
								index: 'KodeDepdagri',
								width: 35,
								editable: true,
								align: 'center',
								editoptions: {
									size: 10,
									maxlength: 2
								}
							}, {
								name: '',
								index: '',
								align: 'center',
								width: 35,
								formatter: function(val, opt, row) {
									kondisi = 2;
									jqgridRow = opt;
									var disabled =' btn btn-outline-warning btn-elevate btn-icon btn-sm btnKab" data-id="' + row.id_kecamatan + ' ';
									var btnReset = '<button type="button" title="Pindah Kabupaten" class="' + disabled + '"><i class="fa flaticon-placeholder-3"></i></button>&nbsp;';
									return '';
								}
							} ],
							rowNum: -1,
							viewrecords: true,
							pager: ppager_id,
							height: '100%',
							sortable: true,
							shrinkToFit: pShrinkToFit,
							forceFit: pForceFit,
							sortable: true,
							viewrecords: true,
							rowNum: 5,
							rowList: [5, 10, 20],
							caption: "Detail Kecamatan - Kabupaten " + nmkab,
							subGridOptions: {
								"plusicon": "ui-icon-triangle-1-e",
								"minusicon": "ui-icon-triangle-1-s",
								"openicon": "ui-icon-arrowreturn-1-e"
							},
							subGrid: true,
							subGridOptions: {
								expandOnLoad: false
							}, // sub grid kelurahan --------------------------------------------------------- Here Kelurahan  -------------------------------
							subGridRowExpanded: function(sssubgrid_id, row_id) {
								sssubgrid_table_id = "minggus_" + sssubgrid_id + "_t";
								pppager_id = "pager_" + sssubgrid_table_id;
								$("#" + sssubgrid_id).html("<table id='" + sssubgrid_table_id + "' class='scroll'></table><div id='" + pppager_id + "' class='scroll'></div>");
								$("#" + sssubgrid_id).html();

								var key_id = '';
								var ret = $("#" + ssubgrid_table_id).getRowData(row_id); //get the selected row
								idkecamatan = ret['id_kecamatan']; //get the data from selected row by column na
								nmkec = ret['nama_kecamatan'];
								var idsubfungsi = row_id

								//console.log(idfungsi);

								jQuery("#" + sssubgrid_table_id).jqGrid({
									datatype: "json",
									url: base_url + '/wilayah/getDataKelurahan/' + idkecamatan,
									emptyrecords: "No records to view",
									autowidth: true,
									rownumbers: true,
									colNames: ['ID', 'Nama Kelurahan', 'Kd.Depdagri', 'Action'],
									colModel: [{
										name: 'id_kelurahan',
										index: 'id_kelurahan',
										width: 30,
										editable: true,
										align: 'center'
									}, {
										name: 'nama_kelurahan',
										index: 'nama_kelurahan',
										width: 350,
										editable: true
									}, {
										name: 'KodeDepdagri',
										index: 'KodeDepdagri',
										width: 35,
										editable: true,
										align: 'center',
										editoptions: {
											size: 10,
											maxlength: 4
										}
									}, {
										name: '',
										index: '',
										align: 'center',
										width: 35,
										formatter: function(val, opt, row) {
											kondisi = 3;
											jqgridRow = opt;
											var disabled =' btn btn-outline-success btn-elevate btn-icon btn-sm btnKec" data-id="' + row.id_kelurahan + ' ';
											var btnReset = '<button type="button" title="Pindah Kecamatan" class="' + disabled + '"><i class="fa flaticon-placeholder-3"></i></button>&nbsp;';
											return '';
										}
									},],
									rowNum: -1,
									viewrecords: true,
									pager: pppager_id,
									height: '100%',
									sortable: true,
									shrinkToFit: pShrinkToFit,
									forceFit: pForceFit,
									rownumbers: true,
									autowidth: true,
									rowNum: 5,
									rowList: [5, 10, 20],
									caption: "Detail Kelurahan - Kecamatan " + nmkec,
									subGrid: true,
									subGridOptions: {
										expandOnLoad: false,
										"plusicon": "ui-icon-triangle-1-e",
										"minusicon": "ui-icon-triangle-1-s",
										"openicon": "ui-icon-arrowreturn-1-e"
									}, // sub grid RW --------------------------------------------------------- Here RW -------------------------------
									subGridRowExpanded: function(ssssubgrid_id, row_id) {
										ssssubgrid_table_id = "minggus_" + ssssubgrid_id + "_t";
										ppppager_id = "pager_" + ssssubgrid_table_id;
										$("#" + ssssubgrid_id).html("<table id='" + ssssubgrid_table_id + "' class='scroll'></table><div id='" + ppppager_id + "' class='scroll'></div>");
										$("#" + ssssubgrid_id).html();

										var key_id = '';
										var ret = $("#" + sssubgrid_table_id).getRowData(row_id); //get the selected row
										idkelurahan = ret['id_kelurahan']; //get the data from selected row by column na
										nmkel = ret['nama_kelurahan']; //get the data from selected row by column na
										var idsubfungsi = row_id

										jQuery("#" + ssssubgrid_table_id).jqGrid({
											datatype: "json",
											url: base_url + '/wilayah/getDataRw/' + idkelurahan,
											emptyrecords: "No records to view",
											autowidth: true,
											rownumbers: true,
											colNames: ['idkel', 'KodeDepdagri', 'ID RW', 'Nama RW', 'Action'],
											colModel: [{
												name: 'id_kelurahan',
												index: 'id_kelurahan',
												width: 55,
												editable: true,
												hidden: true,
											}, {
												name: 'KodeDepdagri',
												index: 'KodeDepdagri',
												width: 65,
												editable: true,
												align: 'center',
												editoptions: {
													size: 10,
													maxlength: 3
												}
											},{
												name: 'id_rw',
												index: 'id_rw',
												width: 35,
												editable: true,
												align: 'center',
												editoptions: {
													size: 10,
													readonly: 'readonly'
												},
												viewable: true,
												editrules: {
													edithidden: true
												}
											}, {
												name: 'nama_rw',
												index: 'nama_rw',
												width: 350,
												editable: true
											},  {
												name: '',
												index: '',
												align: 'center',
												width: 102,
												formatter: function(val, opt, row) {
													kondisi = 4;
													jqgridRow = opt;
                                                	var btnEdit = '<button type="button" title="Edit RW" class="btn btn-outline-success btn-elevate btn-icon btn-sm btnEditRW" data-grid="' + ssssubgrid_table_id + '" data-rowid="' + opt.rowId + '"><i class="flaticon-edit"></i></button>&nbsp;';
													var disabled =' btn btn-outline-primary btn-elevate btn-icon btn-sm btnKel" data-id="' + row.id_rw + ' ';
													var btnReset = '<button type="button" title="Pindah Kelurahan" class="' + disabled + '"><i class="fa flaticon-placeholder-3"></i></button>&nbsp;';
                                                	var btnDelete = '<button type="button" title="Hapus RW" class="btn btn-outline-danger btn-elevate btn-icon btn-sm btnDelRW" data-grid="' + ssssubgrid_table_id + '"  data-rowid="' + opt.rowId + '"><i class="fa flaticon-delete"></i></button>&nbsp;';
													return btnEdit+btnReset+btnDelete;
												}
											},],
											rowNum: -1,
											viewrecords: true,
											pager: ppppager_id,
											height: '100%',
											sortable: true,
											shrinkToFit: pShrinkToFit,
											forceFit: pForceFit,
											rownumbers: true,
											rownumWidth: 70,
											autowidth: true,
											rowNum: 5,
											rowList: [5, 10, 20],
											caption: "Detail RW - Kelurahan " + nmkel,
											subGridOptions: {
												"plusicon": "ui-icon-triangle-1-e",
												"minusicon": "ui-icon-triangle-1-s",
												"openicon": "ui-icon-arrowreturn-1-e"
											},
											subGrid: true,
											subGridOptions: {
												expandOnLoad: false,
												"plusicon": "ui-icon-triangle-1-e",
												"minusicon": "ui-icon-triangle-1-s",
												"openicon": "ui-icon-arrowreturn-1-e"
											}, // sub grid RT --------------------------------------------------------- Here RT -------------------------------
											subGridRowExpanded: function(sssssubgrid_id, row_id) {
												sssssubgrid_table_id = "minggus_" + sssssubgrid_id + "_t";
												grid_id = sssssubgrid_table_id;
												pppppager_id = "pager_" + ssssubgrid_table_id;
												$("#" + sssssubgrid_id).html("<table id='" + sssssubgrid_table_id + "' class='scroll'></table><div id='" + pppppager_id + "' class='scroll'></div>");
												$("#" + sssssubgrid_id).html();

												var key_id = '';
												var ret = $("#" + ssssubgrid_table_id).getRowData(row_id); //get the selected row
												idRW = ret['id_rw']; //get the data from selected row by column na
												var idsubfungsi = row_id
												jQuery("#" + sssssubgrid_table_id).jqGrid({
													datatype: "json",
													url: base_url + '/wilayah/getDataRt/' + idRW,
													emptyrecords: "No records to view",
													autowidth: true,
													rownumbers: true,
													colNames: ['ID', 'Kode', 'Nama RT', 'Action'],
													colModel: [{
														name: 'id_rt',
														index: 'id_rt',
														width: 35,
														editable: true,
														align: 'center',
														editoptions: {
															size: 10,
															readonly: 'readonly',
															addhidden: true
														}
													}, {
														name: 'KodeRT',
														index: 'KodeRT',
														width: 35,
														editable: true,
														editoptions: {
															size: 10,
															maxlength: 3
														},
														options: {
															required: true
														},
														align: 'center'
													}, {
														name: 'nama_rt',
														index: 'nama_rt',
														width: 350,
														editable: true,
														editoptions: {
															size: 10,
															maxlength: 50
														}
													},  {
                                                        name: '',
                                                        label: 'Action',
                                                        align: 'center',
                                                        width: 112,
                                                        formatter: function (val, opt, row) {
                                                            kondisi = 5;
                                                            jqgridRow = opt;
                                                            var disabled = ' btn btn-outline-primary btn-elevate btn-icon btn-sm btnRW" data-id="' + row.id_rt + ' ';
                                                            var btnReset = '<button type="button" title="Pindah RW" class="' + disabled + '"><i class="fa flaticon-placeholder-3"></i></button>&nbsp;';
                                                            var btnEdit = '<button type="button" title="Edit RT" class="btn btn-outline-success btn-elevate btn-icon btn-sm btnEditRT" data-grid="' + sssssubgrid_table_id + '" data-rowid="' + opt.rowId + '"><i class="flaticon-edit"></i></button>&nbsp;';
                                                            var btndelete = '<button type="button" title="Delete RT" class="btn btn-outline-danger btn-elevate btn-icon btn-sm btnDeleteRT" data-grid="' + sssssubgrid_table_id + '" data-rowid="' + opt.rowId + '"><i class="flaticon-delete"></i></button>&nbsp;';
                                                            return btnEdit + btnReset + btndelete ;
                                                        }
                                                    },],
													rowNum: -1,
													viewrecords: true,
													pager: pppppager_id,
													height: '100%',
													sortable: true,
													shrinkToFit: pShrinkToFit,
													forceFit: pForceFit,
													rownumbers: true,
													rownumWidth: 70,
													autowidth: true,
													rowNum: 5,
													rowList: [5, 10, 20],
													caption: "Detail RT",
													subGridOptions: {
														"plusicon": "ui-icon-triangle-1-e",
														"minusicon": "ui-icon-triangle-1-s",
														"openicon": "ui-icon-arrowreturn-1-e"
													},
													subGrid: false,
												}); // jquery subgrid level 5 - RT
												// Tombol Add delete insert view
												var $RTGrid = $("#" + sssssubgrid_table_id);
		                                        //$('<a class="btnAddRT" data-id="' + idRW + '" title="Tambah RW">Tambah</a>').appendTo('#jqgh_' + sssssubgrid_table_id + '_rn')
		                                        if (hasUpdate && !hasApproved) {
		                                            $('<button type="button" class="btnAddRT btn-add-titlebar" data-id="' + idRW + '" title="Tambah RT">Tambah RT</button>').appendTo('#gview_' + sssssubgrid_table_id + ' > div.ui-jqgrid-titlebar')
		                                            .click(function (e) {
		                                                var id_kel = $(this).data('id');
		                                                $RTGrid.jqGrid("editGridRow", "new", {
		                                                    addCaption: "Add Data RT",
		                                                    addtext: "Add",
		                                                    closeOnEscape: true,
		                                                    closeAfterEdit: true,
		                                                    savekey: [true, 13],
		                                                    errorTextFormat: this.commonError,
		                                                    width: "500",
		                                                    reloadAfterSubmit: true,
		                                                    closeAfterAdd: true,
		                                                    bottominfo: "Fields marked with (*) are required..!",
		                                                    top: "60",
		                                                    left: "5",
		                                                    right: "5",
		                                                    beforeShowForm: function () {
		                                                        $("#tr_id_rt").hide();
		                                                    },
		                                                    onclickSubmit: function (response, postdata) {
		                                                        postdata.idRW = idRW;
		                                                        AddPostRT(postdata);
		                                                    },
		                                                });
		                                            });
		                                        }
												/*
												$("#" + sssssubgrid_table_id).jqGrid('navGrid', '#' + pppppager_id, {
													edit: true,
													edittitle: "Edit Data RT",
													width: 500,
													add: true,
													addtitle: "Add Data RT",
													width: 500,
													del: false,
													search: true,
													refresh: true,
													view: true
												}, {
													editCaption: "Edit Data RT",
													edittext: "Edit",
													closeOnEscape: true,
													closeAfterEdit: true,
													savekey: [true, 13],
													errorTextFormat: commonError,
													width: "500",
													reloadAfterSubmit: true,
													bottominfo: "Fields marked with (*) are required.!",
													top: "60",
													left: "5",
													right: "5",
													beforeShowForm: function() {
														$("#tr_id_rt").hide();
													},
													onclickSubmit: function(response, postdata) {
														//call edit button
														EditPostRT(postdata);
													}
												}, {
													addCaption: "Add Data RT",
													addtext: "Add",
													closeOnEscape: true,
													closeAfterEdit: true,
													savekey: [true, 13],
													errorTextFormat: commonError,
													width: "500",
													reloadAfterSubmit: true,
													closeAfterAdd: true,
													bottominfo: "Fields marked with (*) are required..!",
													top: "60",
													left: "5",
													right: "5",
													beforeShowForm: function() {
														$("#tr_id_rt").hide();
													},
													onclickSubmit: function(response, postdata) {
														postdata.idRW = idRW;
														AddPostRT(postdata);
													}
												}); */
											} // end subgrid lvl 5 - RT

										}); // jquery subgrid level 4 - RW
										// Tombol Add delete insert
										var $RWGrid = $("#" + ssssubgrid_table_id);
				                            //$('<a class="btnAddRW" data-id="' + idkelurahan + '" title="Tambah RW">Tambah</a>').appendTo('#jqgh_' + ssssubgrid_table_id + '_rn')				                            
				                            if (hasUpdate && !hasApproved) {
				                                $('<button type="button" class="btnAddRW btn-add-titlebar" data-id="' + idkelurahan + '" title="Tambah RW">Tambah RW</button>').appendTo('#gview_' + ssssubgrid_table_id + ' > div.ui-jqgrid-titlebar')
				                                .click(function (e) {
				                                    var id_kel = $(this).data('id');
				                                    $RWGrid.jqGrid("editGridRow", "new", {
				                                        addCaption: "Add RW",
				                                        addtext: "Add",
				                                        closeOnEscape: true,
				                                        closeAfterEdit: true,
				                                        recreateForm: true,
				                                        savekey: [true, 13],
				                                        errorTextFormat: this.commonError,
				                                        width: "500",
				                                        closeAfterAdd: true,
				                                        bottominfo: "Fields marked with (*) are required.xx",
				                                        top: "60",
				                                        left: "5",
				                                        right: "5",
				                                        beforeShowForm: function () {
				                                            $("#tr_id_rw").hide();
				                                        },
				                                        onclickSubmit: function (response, postdata) {
				                                            postdata.idkel = id_kel;
				                                            AddPostRW(postdata);
				                                        },
				                                    });
				                                });
				                            }										

										/*$("#" + ssssubgrid_table_id).jqGrid('navGrid', '#' + ppppager_id, {
											edit: true,
											edittitle: "Edit Data",
											width: 500,
											add: true,
											addtitle: "Add data",
											width: 500,
											del: false,
											search: true,
											refresh: true,
											view: true
										}, {
											editCaption: "Edit Data RW",
											edittext: "Edit",
											closeOnEscape: true,
											closeAfterEdit: true,
											savekey: [true, 13],
											errorTextFormat: commonError,
											width: "500",
											reloadAfterSubmit: true,
											bottominfo: "Fields marked with (*) are required.",
											top: "60",
											left: "5",
											right: "5",
											closeAfterEdit: true,
											beforeShowForm: function() {
												$("#tr_id_rw").hide();
												$("#tr_KodeDepdagri").hide();
											},
											onclickSubmit: function(response, postdata) {
												//call edit button
												console.log(postdata);
												EditPostRW(postdata);
											}
										}, {
											addCaption: "Add RW",
											addtext: "Add",
											closeOnEscape: true,
											closeAfterEdit: true,
											savekey: [true, 13],
											errorTextFormat: commonError,
											width: "500",
											closeAfterAdd: true,
											bottominfo: "Fields marked with (*) are required.xx",
											top: "60",
											left: "5",
											right: "5",
											closeAfterEdit: true,
											beforeShowForm: function() {
												$("#tr_id_rw").hide();
											},
											onclickSubmit: function(response, postdata) {
												console.log(postdata);
												postdata.idkel = idkelurahan;
												AddPostRW(postdata);
											},
										}); */// end button
									} // end subgrid lvl 4 - RW

								}); // jquery subgrid level 3 - Kelurahan
								// ------------------------------------ button KELURAHAN ------------------------------------

							} // end subgrid lvl 3 - Kelurahan

						}); // jquery subgrid 2 - Kecamatan
						// ------------------------------------ button Kecamatan ------------------------------------

					} // end subgrid 2 - kecamatan

				}); // jquery subgrid 1 - Kabupaten

			} // end Subgrid 1 - Kabupaten

		}); // end provinsi




		function commonError(data) {
			return "Error Occured during Operation. Please try again";
		}

		function AddPostRT(params) {
			//Here you need to define ajax call for insert records to server
			//console.log(params);
			Swal.fire({
				title: 'Apakah anda yakin, menambahkan data baru?',
				text: "Data akan ditambahkan ke basis data!",
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Simpan data!'
			}).then((result) => {
				if (result.value) {
					KTApp.blockPage({
						message: 'Harap tunggu!...'
					}); // KTApp.unblockPage();

					var header = {
						'Accept': 'application/json',
						'X-CSRF-TOKEN': csrf_token,
					};

					var xhr = $.ajax({
							url: base_url + '/wilayah/AddPostRT/',
							method: 'GET',
							dataType: 'json',
							headers: header,
							data: params,
							success: function(response) { // What to do if we succeed
								if (response.status) {
									Swal.fire(
										'Yeaah..!',
										response.message,
										'success'
									)
									//$('#modalDetail2').modal('hide');
									KTApp.unblockPage();
									jQuery("#" + params.id).trigger("reloadGrid");

								}
							},
							error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
								console.log(JSON.stringify(jqXHR));
								console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
							}
						})
						.done(function(response) {
							var message = (response.message) ? response.message : '';
							if (response.status) {
								//toastr.options.onHidden = formClear;
								toastr.success(message);
								KTApp.unblockPage();
							} else {
								message = (message !== '') ? message : 'Ada kesalahan';
								swal.fire('', message, 'error');
							}
						}).fail(function(jqXHR, textStatus, errorThrown) {
							var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
							swal.fire('', message, 'error');
						}).always(function() {
							KTApp.unblockPage();
						});

				} // end result
			}) // end then
		}

			

		function AddPostRW(params) {
			//Here you need to define ajax call for insert records to server
			//console.log(params);

			Swal.fire({
				title: 'Apakah anda yakin menambah data baru?',
				text: "Data akan disimpan ke basis data!",
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Simpan data!'
			}).then((result) => {
				if (result.value) {
					KTApp.blockPage({
						message: 'Harap tunggu!...'
					}); // KTApp.unblockPage();

					var header = {
						'Accept': 'application/json',
						'X-CSRF-TOKEN': csrf_token,
					};

					var xhr = $.ajax({
							url: base_url + '/wilayah/AddPostRW/',
							method: 'GET',
							dataType: 'json',
							headers: header,
							data: params,
							success: function(response) { // What to do if we succeed
								if (response.status) {
									Swal.fire(
										'Yeaaah!',
										response.message,
										'success'
									)
									//$('#modalDetail2').modal('hide');
									KTApp.unblockPage();
									jQuery("#" + params.id).trigger("reloadGrid");

								}
							},
							error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
								console.log(JSON.stringify(jqXHR));
								console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
							}
						})
						.done(function(response) {
							var message = (response.message) ? response.message : '';
							if (response.status) {
								//toastr.options.onHidden = formClear;
								toastr.success(message);
								KTApp.unblockPage();
							} else {
								message = (message !== '') ? message : 'Ada kesalahan';
								swal.fire('', message, 'error');
							}
						}).fail(function(jqXHR, textStatus, errorThrown) {
							var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
							swal.fire('', message, 'error');
						}).always(function() {
							KTApp.unblockPage();
						});

				} // end result
			}) // end then

		}

	} // end  init table



	var btnSaveHandler = function() {
		$('#btnSave').click(function(e) {
			var selRowId = jqgridRow; //$(this).data('id');
			var row = $('#tableroles').jqGrid('getRowData', selRowId);
			var id;
			var key;	
			var message = null;					

			//console.log(selRowId);

			if (kondisi == 1) {
				// Kabupaten pindah provinsi
				id  = $('#Provinsi').val();
				key = id_kab;	
				if ( !$('#Provinsi').val() ) {
					message = 'Please make sure the selected province field is selected!';					
				}			
				
			} else if (kondisi == 2) {
				// Kecamatan Pindah Kabupaten
				id  = $('#Kabupaten').val();
				key = id_kec;
				if ( !$('#Provinsi').val() || !$('#Kabupaten').val() ) {
					message = 'Please make sure the selected provinsi and Kabupaten field is selected !';	
				}
			} else if (kondisi == 3) {
				// Kelurahan pindah Kecamatan
				id  = $('#Kecamatan').val();
				key = id_kel;
				if ( !$('#Provinsi').val() || !$('#Kabupaten').val() || !$('#Kecamatan').val()) {
					message = 'Please make sure the selected provinsi,Kabupaten and kecamatan field is selected !';	
				}
			} else if (kondisi == 4) {
				// RW pindah Kelurahan.
				id  = $('#Kelurahan').val();
				key = id_rw.replace(/\s+/g, '');
				if ( !$('#Provinsi').val() || !$('#Kabupaten').val() || !$('#Kecamatan').val() || !$('#Kelurahan').val()) {
					message = 'Please make sure the selected provinsi,Kabupaten,kecamatan and kelurahan field is selected !';	
				}	
			} else if (kondisi == 5) {
                // RW pindah Kelurahan.
                id = $('#RW').val();
                key = id_rt.replace(/\s+/g, '');
                if (!$('#Provinsi').val() || !$('#Kabupaten').val() || !$('#Kecamatan').val() || !$('#Kelurahan').val() || !$('#RW').val()) {
                    message = 'Please make sure the selected provinsi,Kabupaten,kecamatan and kelurahan field is selected !';
                }
            }

			if ( message == null){

				Swal.fire({
					title: 'Apakah anda yakin?',
					text: "Pastikan data sudah sesuai dan benar, sebelum disimpan!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Ya, Ubah data!'
				}).then((result) => {
					if (result.value) {
						KTApp.block('#modalResetValid', {
							message: 'Harap tunggu...'
						});
												
						//console.log (selRowId.rowId);
												
						$.ajax({
							method: 'GET', // Type of response and matches what we said in the route
							url: base_url + '/wilayah/UbahWilayahParent/' + id +'/'+ key +'/'+ kondisi, // This is the url we gave in the route
							success: function(response) { // What to do if we succeed
								if (response.status) {
									Swal.fire(
										'Validate!',
										response.message,
										'success'
									)
									$('#' + grid_id).trigger("reloadGrid");
									$('#modalResetValid').modal('hide');
									//jQuery("#" +selRowId.rowId).trigger("reloadGrid");
									
								}
							},
							error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
								console.log(JSON.stringify(jqXHR));
								console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
							}
						}).always(function() {
							KTApp.unblock('#modalResetValid');
						}); // end ajax */
					} // end result
				}) // end Swall		
		   } else {
			   	Swal.fire({
					  type: 'info',
					  title: 'Oops...',
					  text: message,
					})
		   }// End check key
		}); 
	}

	// button Pindah Provinsi
	var btnProv = function() { 
			$(document).on('click', '.btnProv', function() {
				kondisi = 1;
				id_kab = $(this).attr("data-id");

				var selRowId = $(this).data('id');				
				var row = $('#tableroles').jqGrid('getRowData', selRowId);

				getprovinsi();
				getshowall();

				$('#formResetValid #Kabupaten').prop('disabled', true);
				$('#formResetValid #Kecamatan').prop('disabled', true);
				$('#formResetValid #Kelurahan').prop('disabled', true);
				$('#formResetValid #RW').prop('disabled', true);

				$('.div-kabupaten').hide();
				$('.div-kecamatan').hide();
				$('.div-kelurahan').hide();
				
				var modal = $('#modalResetValid');
				modal.find('.modal-title').text('Perubahan Provinsi');
				modal.modal();
				//$('#modalResetValid').modal();


			});
		}

	// button Pindah Kabupaten	
	var btnKab = function() {
			$(document).on('click', '.btnKab', function() {
				kondisi = 2;
				id_kec = $(this).attr("data-id");

				getprovinsi();
				getshowall();

				var selRowId = $(this).data('id');
				var row = $('#tableroles').jqGrid('getRowData', selRowId);

				$('#formResetValid #Kabupaten').prop('disabled', false);
				$('#formResetValid #Kecamatan').prop('disabled', true);
				
				$('#formResetValid #Kelurahan').prop('disabled', true);
				$('#formResetValid #RW').prop('disabled', true);

				$('.div-kecamatan').hide();
				$('.div-kelurahan').hide();

				
				var modal = $('#modalResetValid');
				modal.find('.modal-title').text('Perubahan Kabupaten');
				modal.modal();

			});
		}

	// button Pindah Kecamatan		
	var btnKec = function() {
			$(document).on('click', '.btnKec', function() {
				kondisi = 3;
				id_kel = $(this).attr("data-id");

				getprovinsi();
				getshowall();

				var selRowId = $(this).data('id');
				var row = $('#tableroles').jqGrid('getRowData', selRowId);
				
				$('#formResetValid #Kabupaten').prop('disabled', false);
				$('#formResetValid #Kecamatan').prop('disabled', false);
				$('#formResetValid #Kelurahan').prop('disabled', true);
				$('#formResetValid #RW').prop('disabled', true);
				
				$('.div-kelurahan').hide();

				var modal = $('#modalResetValid');
				modal.find('.modal-title').text('Perubahan Kecamatan');
				modal.modal();

			});
		}

	// button Pindah Kelurahan	
	var btnKel = function() {
			$(document).on('click', '.btnKel', function() {
				kondisi = 4;
				id_rw = $(this).attr("data-id");

				//console.log(id_rw);

				var selRowId = $(this).data('id');
				var row = $('#tableroles').jqGrid('getRowData', selRowId);
				getprovinsi();
				getshowall();
				
				$('#formResetValid #Kabupaten').prop('disabled', false);
				$('#formResetValid #Kecamatan').prop('disabled', false);
				$('#formResetValid #Kelurahan').prop('disabled', false);
				$('#formResetValid #RW').prop('disabled', true);
				var modal = $('#modalResetValid');
				modal.find('.modal-title').text('Perubahan Kelurahan');
				modal.modal();
			});
		}

	// button Pindah RW
    var btnRW = function () {
        $(document).on('click', '.btnRW', function () {
            kondisi = 5;
            id_rt = $(this).attr("data-id");


            var selRowId = $(this).data('id');

            console.log(selRowId);

            var row = $('#tablegrid').jqGrid('getRowData', selRowId);
            getprovinsi();
            getshowall();

            $('#formResetValid #Kabupaten').prop('disabled', false);
            $('#formResetValid #Kecamatan').prop('disabled', false);
            $('#formResetValid #Kelurahan').prop('disabled', false);
            $('#formResetValid #RW').prop('disabled', false);
            var modal = $('#modalResetValid');
            modal.find('.modal-title').text('Perubahan RW');
            modal.modal();
        });
    }

	var getprovinsi = function() {	
	    $('#Kabupaten').children().remove();	
	    $('#Kecamatan').children().remove();	
	    $('#Kelurahan').children().remove();	
	    $('#RW').children().remove();	

		var xhr = $.ajax({
				url: base_url + '/wilayah/provinsi/'+id_prov,
				method: 'GET',
				dataType: 'json',
			})
			.done(function(response) {
				var el = $('#Provinsi');
				el.children().remove();
				el.append($("<option></option>").attr("value", '').text(''));
				$.each(response, function(key, value) {					
					el.append($("<option></option>").attr("value", value.id_provinsi).text(value.nama_provinsi));
				});

			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				toastr.error('getProvinsi: ' + jqXHR.statusText);
			})
			.always(function() {});
	}

	var getKabupaten = function() {
		var provinsi = $('#Provinsi').val();
		var xhr = $.ajax({
				url: base_url + '/wilayah/kotakab/'+provinsi,
				method: 'GET',
				dataType: 'json',
			})
			.done(function(response) {
				var el = $('#Kabupaten');
				el.children().remove();
				el.append($("<option></option>").attr("value", '').text(''));
				$.each(response, function(key, value) {
					//console.log(value.id_kabupaten);
					el.append($("<option></option>").attr("value", value.id_kabupaten).text(value.nama_kabupaten));
				});

			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				toastr.error('getKabupaten: ' + jqXHR.statusText);
			})
			.always(function() {});
	}	


	var getKecamatan = function() {
		var provinsi = $('#Kabupaten').val();
		var xhr = $.ajax({
				url: base_url + '/wilayah/kecamatan/'+ provinsi,
				method: 'GET',
				dataType: 'json',
			})
			.done(function(response) {
				var el = $('#Kecamatan');
				el.children().remove();
				el.append($("<option></option>").attr("value", '').text(''));
				$.each(response, function(key, value) {
					//console.log(value.id_kabupaten);
					el.append($("<option></option>").attr("value", value.id_kecamatan).text(value.nama_kecamatan));
				});

			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				toastr.error('getKecamatan: ' + jqXHR.statusText);
			})
			.always(function() {});
	}	




	var getKelurahan = function() {
		var provinsi = $('#Kecamatan').val();
		var xhr = $.ajax({
				url: base_url + '/wilayah/kelurahan/'+provinsi,
				method: 'GET',
				dataType: 'json',
			})
			.done(function(response) {
				var el = $('#Kelurahan');
				el.children().remove();
				el.append($("<option></option>").attr("value", '').text(''));
				$.each(response, function(key, value) {
					//console.log(value.id_kabupaten);
					el.append($("<option></option>").attr("value", value.id_kelurahan).text(value.nama_kelurahan));
				});

			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				toastr.error('getKelurahan: ' + jqXHR.statusText);
			})
			.always(function() {});
	}	

	var getRW = function () {
        var provinsi = $('#Kelurahan').val();
        var xhr = $.ajax({
                url: base_url + '/wilayah/rw/' + provinsi,
                method: 'GET',
                dataType: 'json',
            })
            .done(function (response) {
                var el = $('#RW');
                el.children().remove();
                el.append($("<option></option>").attr("value", '').text(''));
                $.each(response, function (key, value) {
                    //console.log(value.id_kabupaten);
                    el.append($("<option></option>").attr("value", value.id).text(value.text));
                });

            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                toastr.error('getRW: ' + jqXHR.statusText);
            })
            .always(function () {});
    }

	var getshowall = function() {

				
		$('#formResetValid #Provinsi').prop('disabled', false);
		$('#formResetValid #Kabupaten').prop('disabled', false);
		$('#formResetValid #Kecamatan').prop('disabled', false);
		$('#formResetValid #Kelurahan').prop('disabled', false);
		$('#formResetValid #RW').prop('disabled', false);

		$('.div-provinsi').show();
		$('.div-kabupaten').show();
		$('.div-kecamatan').show();
		$('.div-kelurahan').show();
		$('.div-RW').show();

	}

	var select2Handler = function() {
		$('#Provinsi').select2({
            placeholder: '--- Pilih Provinsi ---',
        }).on('change', function(e) {
        	var el = document.getElementById('Kabupaten');
        	if (el.disabled) {
		       null;
		    } else {
		       // Enter code here
		       getKabupaten();		        
		     }
			
		});
        
        
		$('#Kabupaten').select2({
            placeholder: '--- Pilih Tingkat Kabupaten ---',
        }).on('change', function(e) {
        	var el = document.getElementById('Kecamatan');
        	if (el.disabled) {
		       null;
		    } else {
		       // Enter code here
		       getKecamatan();		        
		     }
			
		});

		$('#Kecamatan').select2({
            placeholder: '--- Pilih Tingkat Kecamatan ---',
        }).on('change', function(e) {
        	var el = document.getElementById('Kelurahan');
        	if (el.disabled) {
		       // If disabled, do this 
		       //alert("disabled");
		       null;
		    } else {
		       // Enter code here
		       getKelurahan();		        
		     }
			
		});

        $('#Kelurahan').select2({
            placeholder: '--- Pilih Tingkat Kelurahan ---',
        }).on('change', function (e) {
            var el = document.getElementById('RW');
            if (el.disabled) {
                // If disabled, do this
                //alert("disabled");
                null;
            } else {
                // Enter code here
                getRW();
            }
        });

        $('#RW').select2({
            placeholder: '--- Pilih Tingkat RW ---',
        });

		// end here ---
	}


		var btnEditRWhandler = function () {
			$(document).on('click', '.btnEditRW', function () {

	            var rowid = $(this).data('rowid');
	            var grid_rw = $(this).data('grid');

	            $('#' + grid_rw).jqGrid("editGridRow", rowid, {
	                editCaption: "Edit Data RW",
	                edittext: "Edit",
	                closeOnEscape: true,
	                closeAfterEdit: true,
	                recreateForm: true,
	                savekey: [true, 13],
	                errorTextFormat: this.commonError,
	                width: "500",
	                reloadAfterSubmit: true,
	                bottominfo: "Fields marked with (*) are required.",
	                top: "60",
	                left: "5",
	                right: "5",
	                beforeShowForm: function () {
	                    $("#tr_id_rw").hide();
	                    $("#tr_KodeDepdagri").hide();
	                },
	                onclickSubmit: function (response, postdata) {
	                    postdata.grid_id = grid_rw;
	                    //console.log(postdata);
	                    EditPostRW(postdata);
	                }
	            });
	        });
		}

		var btnEditRThandler = function () {
			$(document).on('click', '.btnEditRT', function () {

	            var rowid = $(this).data('rowid');
	            var grid_rw = $(this).data('grid');

	            $('#' + grid_rw).jqGrid("editGridRow", rowid, {
	                editCaption: "Edit Data RW",
	                edittext: "Edit",
	                closeOnEscape: true,
	                closeAfterEdit: true,
	                recreateForm: true,
	                savekey: [true, 13],
	                errorTextFormat: this.commonError,
	                width: "500",
	                reloadAfterSubmit: true,
	                bottominfo: "Fields marked with (*) are required.",
	                top: "60",
	                left: "5",
	                right: "5",
	                beforeShowForm: function () {
	                    $("#tr_id_rw").hide();
	                    $("#tr_KodeDepdagri").hide();
	                },
	                onclickSubmit: function (response, postdata) {
	                    postdata.grid_id = grid_rw;
	                    //console.log(postdata);
	                    EditPostRT(postdata);
	                }
	            });
	        });
		}

		function EditPostRW(params) {
			//Here you need to define ajax call for update records to server

			Swal.fire({
				title: 'Apakah anda yakin?',
				text: "Data akan diubah di basis data!",
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Ubah data!'
			}).then((result) => {
				if (result.value) {
					KTApp.blockPage({
						message: 'Harap tunggu!...'
					}); // KTApp.unblockPage();

					var header = {
						'Accept': 'application/json',
						'X-CSRF-TOKEN': csrf_token,
					};

					var xhr = $.ajax({
							url: base_url + '/wilayah/EditPostRW/',
							method: 'GET',
							dataType: 'json',
							headers: header,
							data: params,
							success: function(response) { // What to do if we succeed
								if (response.status) {
									Swal.fire(
										'Yeaaah!',
										response.message,
										'success'
									)
									//$('#modalDetail2').modal('hide');
									KTApp.unblockPage();
									jQuery("#" + params.id).trigger("reloadGrid");

								}
							},
							error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
								console.log(JSON.stringify(jqXHR));
								console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
							}
						})
						.done(function(response) {
							var message = (response.message) ? response.message : '';
							if (response.status) {
								//toastr.options.onHidden = formClear;
								toastr.success(message);
								KTApp.unblockPage();
							} else {
								message = (message !== '') ? message : 'Ada kesalahan';
								swal.fire('', message, 'error');
							}
						}).fail(function(jqXHR, textStatus, errorThrown) {
							var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
							swal.fire('', message, 'error');
						}).always(function() {
							KTApp.unblockPage();
						});

				} // end result
			}) // end then
		}


	function EditPostRT(params) {
			//Here you need to define ajax call for update records to server

			Swal.fire({
				title: 'Apakah Anda Yakin?',
				text: "Data akan diubah ke basis data!",
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Simpan Perubahan!'
			}).then((result) => {
				if (result.value) {
					KTApp.blockPage({
						message: 'Harap tunggu!...'
					}); // KTApp.unblockPage();

					var header = {
						'Accept': 'application/json',
						'X-CSRF-TOKEN': csrf_token,
					};

					var xhr = $.ajax({
							url: base_url + '/wilayah/EditPostRT/',
							method: 'GET',
							dataType: 'json',
							headers: header,
							data: params,
							success: function(response) { // What to do if we succeed
								if (response.status) {
									Swal.fire(
										'Yeaaah!',
										response.message,
										'success'
									)
									//$('#modalDetail2').modal('hide');
									KTApp.unblockPage();
									jQuery("#" + params.id).trigger("reloadGrid");

								}
							},
							error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
								console.log(JSON.stringify(jqXHR));
								console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
							}
						})
						.done(function(response) {
							var message = (response.message) ? response.message : '';
							if (response.status) {
								//toastr.options.onHidden = formClear;
								toastr.success(message);
								KTApp.unblockPage();
							} else {
								message = (message !== '') ? message : 'Ada kesalahan';
								swal.fire('', message, 'error');
							}
						}).fail(function(jqXHR, textStatus, errorThrown) {
							var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
							swal.fire('', message, 'error');
						}).always(function() {
							KTApp.unblockPage();
						});

				} // end result
			}) // end then
		}


		var btnDeleteRThandler = function () {
         $(document).on('click', '.btnDeleteRT', function () {
            
            var rowid = $(this).data('rowid');
            var grid_rt = $(this).data('grid');
            var rowData = jQuery('#' + grid_rt).jqGrid ('getRowData', rowid);
            
            	rowData.grid_id = grid_rt;            
            	DeletePost(rowData);                              
         	});
    	}  

		function DeletePost(params) {
        //Here you need to define ajax call for insert records to server
        //console.log(params);
        Swal.fire({
                    title: "",
                    text: "Anda yakin akan menghapus data ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus data!'
                }).then((result) => {
                    if (result.value) {
                        KTApp.blockPage({
                            message: 'Harap Tunggu!...'
                        }); // KTApp.unblockPage();

                        var header = {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf_token,
                        };

                        var xhr = $.ajax({
                                url: base_url + '/wilayah/DeleteRT/',
                                method: 'GET',
                                dataType: 'json',
                                headers: header,
                                data: params,
                            })
                            .done(function (response) {
                                var message = (response.message) ? response.message : '';
                                if (response.status) {
                                    //toastr.options.onHidden = formClear;
                                    toastr.success(message);
                                    KTApp.unblockPage();
                                    jQuery("#" + params.grid_id).trigger("reloadGrid");
                                } else {
                                    message = (message !== '') ? message : 'Ada kesalahan';
                                    swal.fire('', message, 'error');
                                }
                            }).fail(function (jqXHR, textStatus, errorThrown) {
                                var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
                                swal.fire('', message, 'error');
                            }).always(function () {
                                KTApp.unblockPage();
                            });

                    } else { // end result
                        console.log('cancel update', params);
                        $('#' + params.grid_id).trigger("reloadGrid");
                    }
                }) // end then        
    		}

    var btnDelRWhandler = function () {
        $(document).on('click', '.btnDelRW', function () {
            var rowid = $(this).data('rowid');
            var grid_rw = $(this).data('grid');

            var rowid = $(this).data('rowid');
            var grid_rt = $(this).data('grid');
            var rowData = jQuery('#' + grid_rt).jqGrid ('getRowData', rowid);
            
            rowData.grid_id = grid_rw;

            //console.log(rowData);

            DeletePostRW(rowData);
        });
    }		
    
    function DeletePostRW(params) {
        //Here you need to define ajax call for insert records to server
        //console.log(params);

        Swal.fire({
                    title: "",
                    text: "Anda yakin akan menghapus data ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus Data!'
                }).then((result) => {
                    if (result.value) {
                        KTApp.blockPage({
                            message: 'Harap tunggu!...'
                        }); // KTApp.unblockPage();

                        var header = {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf_token,
                        };

                        var xhr = $.ajax({
                                url: base_url + '/wilayah/DeleteRW/',
                                method: 'GET',
                                dataType: 'json',
                                headers: header,
                                data: params,
                            })
                            .done(function (response) {
                                var message = (response.message) ? response.message : '';
                                if (response.status) {
                                    //toastr.options.onHidden = formClear;
                                    toastr.success(message);
                                    KTApp.unblockPage();
                                    jQuery("#" + params.grid_id).trigger("reloadGrid");
                                } else {
                                    message = (message !== '') ? message : 'Ada kesalahan';
                                    swal.fire('', message, 'error');
                                }
                            }).fail(function (jqXHR, textStatus, errorThrown) {
                                var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
                                swal.fire('', message, 'error');
                            }).always(function () {
                                KTApp.unblockPage();
                            });

                    } else { // end result
                        console.log('cancel update', params);
                        $('#' + params.grid_id).trigger("reloadGrid");
                    }
                }) // end then        
    }		    	

	return {
		init: function() {
			initTable();
			getshowall();
			btnProv();
			btnKab();
			btnKec();
			btnKel();

			btnRW();
			
						
			select2Handler();
			//getprovinsi();

			btnSaveHandler();
			btnEditRWhandler();
			btnEditRThandler();
			btnDeleteRThandler();
			btnDelRWhandler();
		}
	};
}();

$(document).ready(function() {
	WilayahIndex.init();
});