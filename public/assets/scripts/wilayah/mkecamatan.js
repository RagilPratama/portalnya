    var WilayahKecamatan = function () {
    var hasApproved = $('#approved').val();
    var hasUpdate = $('#update').val();
    var kondisi;
    var id_prov;
    var id_kab;
    var id_kec;
    var id_kel;
    var id_rw;
    var id_rt;
    var jqgridRow;
    var grid_id;

    var initTable = function () {
        id_kab = 0;
        jQuery("#tablegrid").jqGrid({
            datatype: "json",
            url: base_url + '/wilayah/getDataKecamatan/' + id_kab,
            pager: '#pagergrid',
            shrinkToFit: pShrinkToFit,
            forceFit: pForceFit,
            sortable: true,
            viewrecords: true,
            rownumbers: true,
            autowidth: true,
            caption: "Detail Kecamatan - Kabupaten ",
            colModel: [{
                    name: 'KodeDepdagri',
                    label: 'Kd.Depdagri',
                    width: 35,
                    editable: true,
                    align: 'center'
                }, {
                    name: 'nama_kecamatan',
                    label: 'Nama Kecamatan',
                    width: 350,
                    editable: true
                }, {
                    name: 'id_kecamatan',
                    label: 'ID',
                    width: 35,
                    editable: true,
                    align: 'center',
                    editoptions: {
                        size: 10,
                        maxlength: 2
                    }
                }, {
                    name: '',
                    label: '',
                    align: 'center',
                    width: 35,
                    formatter: function (val, opt, row) {
                        kondisi = 2;
                        jqgridRow = opt;
                        var disabled = ' btn btn-outline-warning btn-elevate btn-icon btn-sm btnKab" data-id="' + row.id_kecamatan + ' ';
                        var btnReset = '<button type="button" title="Pindah Kabupaten" class="' + disabled + '"><i class="fa flaticon-placeholder-3"></i></button>&nbsp;';
                        return '';
                    }
                }
            ],
            subGrid: true,
            subGridOptions: {
                expandOnLoad: true
            }, // sub grid kelurahan --------------------------------------------------------- Here Kelurahan  -------------------------------
            subGridRowExpanded: function (subgrid_id, row_id) {
                var kel_grid_id = 'table_' + subgrid_id + '_kel';
                var kel_pager_id = 'pager_' + subgrid_id + '_kel';
                $('#' + subgrid_id).html('<table id="' + kel_grid_id + '"></table><div id="' + kel_pager_id + '"></div>');

                var key_id = '';
                var ret = $("#tablegrid").getRowData(row_id); //get the selected row
                idkecamatan = ret['id_kecamatan']; //get the data from selected row by column na
                nmkec = ret['nama_kecamatan'];
                var idsubfungsi = row_id
                var urls = '';
                var kelurahan = $('#Kelurahan').val();

                    if (kelurahan === '' || kelurahan === null || kelurahan === 'all' ) {                        
                        urls = base_url + '/wilayah/getDataKelurahan/' + idkecamatan;
                         //console.log('Minggus 123----------> '+ urls);
                    } else {
                        urls = base_url + '/wilayah/kelurahanbyid/' + kelurahan;
                         //console.log('Minggus 123----------> '+ urls);
                    }

                    jQuery("#" + kel_grid_id).jqGrid({
                        datatype: "json",
                        url: urls, //base_url + '/wilayah/getDataKelurahan/' + idkecamatan,
                        emptyrecords: "No records to view",
                        autowidth: true,
                        rownumbers: true,
                        colNames: ['Kd.Depdagri', 'Nama Kelurahan', 'ID', 'Action'],
                        colModel: [{
                                name: 'KodeDepdagri',
                                label: 'KodeDepdagri',
                                width: 40,
                                editable: true,
                                align: 'center'
                            }, {
                                name: 'nama_kelurahan',
                                label: 'nama_kelurahan',
                                width: 350,
                                editable: true
                            }, {
                                name: 'id_kelurahan',
                                label: 'id_kelurahan',
                                width: 35,
                                editable: true,
                                align: 'center',
                                editoptions: {
                                    size: 10,
                                    maxlength: 4
                                }
                            }, {
                                name: '',
                                label: '',
                                align: 'center',
                                width: 35,
                                formatter: function (val, opt, row) {
                                    kondisi = 3;
                                    jqgridRow = opt;
                                    var disabled = ' btn btn-outline-success btn-elevate btn-icon btn-sm btnKec" data-id="' + row.id_kelurahan + ' ';
                                    var btnReset = '<button type="button" title="Pindah Kecamatan" class="' + disabled + '"><i class="fa flaticon-placeholder-3"></i></button>&nbsp;';
                                    return '';
                                }
                            }, ],
                        rowNum: -1,
                        viewrecords: true,
                        pager: kel_pager_id,
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
                            expandOnLoad: true,
                        }, // sub grid RW --------------------------------------------------------- Here RW -------------------------------
                        subGridRowExpanded: function (subgrid_id, row_id) {
                            var rw_grid_id = 'table_' + subgrid_id + '_rw';
                            grid_id = rw_grid_id;
                            var rw_pager_id = 'pager_' + subgrid_id + '_rw';
                            $('#' + subgrid_id).html('<table id="' + rw_grid_id + '"></table><div id="' + rw_pager_id + '"></div>');

                            var key_id = '';
                            var ret = $("#" + kel_grid_id).getRowData(row_id); //get the selected row
                            idkelurahan = ret['id_kelurahan']; //get the data from selected row by column na
                            nmkel = ret['nama_kelurahan']; //get the data from selected row by column na

                            var idsubfungsi = row_id
                            var urls = '';
                            var rw = $('#RW').val();

                            if (rw == '' || rw == null || rw == 'all' ) {                                                     
                                urls = base_url + '/wilayah/getDataRw/' + idkelurahan;    
                            } else {                               
                                urls = base_url + '/wilayah/getrw/' + idkelurahan + '/' + rw; 
                            }

                                jQuery("#" + rw_grid_id).jqGrid({
                                    datatype: "json",
                                    url: urls, //base_url + '/wilayah/getDataRw/' + idkelurahan,
                                    emptyrecords: "No records to view",
                                    autowidth: true,
                                    rownumbers: true,
                                    rownumWidth: 70,
                                    colNames: ['idkel', 'KodeDepdagri', 'Nama RW', 'ID', 'Action'],
                                    colModel: [{
                                            name: 'id_kelurahan',
                                            label: 'id_kelurahan',
                                            width: 55,
                                            editable: true,
                                            hidden: true,
                                        }, {
                                            name: 'KodeDepdagri',
                                            label: 'KodeDepdagri',
                                            width: 55,
                                            editable: true,
                                            align: 'center',
                                            editoptions: {
                                                size: 10,
                                                maxlength: 4
                                            }
                                        }, {
                                            name: 'nama_rw',
                                            label: 'nama_rw',
                                            width: 350,
                                            editable: true
                                        },  {
                                            name: 'id_rw',
                                            label: 'id_rw',
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
                                            name: '',
                                            label: '',
                                            width: 70,
                                            align: 'center',
                                            sortable: false,
                                            formatter: function (val, opt, row) {
                                                kondisi = 4;

                                                //console.log('approved :' + hasApproved);
                                                //console.log('update :' + hasUpdate);

                                                var btnEdit = '<button type="button" title="Edit RW" class="btn btn-outline-success btn-elevate btn-icon btn-sm btnEditRW" data-grid="' + rw_grid_id + '" data-rowid="' + opt.rowId + '"><i class="flaticon-edit"></i></button>&nbsp;';

                                                var btnDelete = '<button type="button" title="Hapus RW" class="btn btn-outline-danger btn-elevate btn-icon btn-sm btnDelRW" data-grid="' + rw_grid_id + '"  data-rowid="' + opt.rowId + '"><i class="fa flaticon-delete"></i></button>&nbsp;';

                                                var btnMarker = '<button type="button" title="Pindah Kelurahan" class="btn btn-outline-primary btn-elevate btn-icon btn-sm btnKel" data-id="' + row.id_rw + '"><i class="fa flaticon-placeholder-3"></i></button>&nbsp;';
                                                if (hasUpdate && !hasApproved) {
                                                    return btnEdit + '' + btnMarker + btnDelete;
                                                } else {
                                                    return '';
                                                }

                                                
                                            }
                                        }, ],
                                    rowNum: -1,
                                    viewrecords: true,
                                    pager: rw_pager_id,
                                    height: '100%',
                                    sortable: true,
                                    shrinkToFit: pShrinkToFit,
                                    forceFit: pForceFit,
                                    rownumbers: true,
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
                                    subGridRowExpanded: function (subgrid_id, row_id) {
                                        var rt_grid_id = 'table_' + subgrid_id + '_rt';
                                        grid_id = rt_grid_id;
                                        var rt_pager_id = 'pager_' + subgrid_id + '_rt';
                                        $('#' + subgrid_id).html('<table id="' + rt_grid_id + '"></table><div id="' + rt_pager_id + '"></div>');

                                        var key_id = '';
                                        var ret = $("#" + rw_grid_id).getRowData(row_id); //get the selected row
                                        idRW = ret['id_rw']; //get the data from selected row by column na
                                        var idsubfungsi = row_id
                                            jQuery("#" + rt_grid_id).jqGrid({
                                                datatype: "json",
                                                url: base_url + '/wilayah/getDataRt/' + idRW,
                                                emptyrecords: "No records to view",
                                                autowidth: true,
                                                rownumbers: true,
                                                rownumWidth: 70,
                                                colModel: [{
                                                        name: 'KodeRT',
                                                        label: 'Kode RT',
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
                                                        name: 'id_rt',
                                                        label: 'ID RT',
                                                        width: 35,
                                                        editable: true,
                                                        align: 'center',
                                                        editoptions: {
                                                            size: 10,
                                                            readonly: 'readonly',
                                                            addhidden: true
                                                        }
                                                    },  {
                                                        name: 'nama_rt',
                                                        label: 'Nama RT',
                                                        width: 250,
                                                        editable: true,
                                                        editoptions: {
                                                            size: 10,
                                                            maxlength: 50
                                                        }
                                                    }, {
                                                        name: '',
                                                        label: 'Action',
                                                        align: 'center',
                                                        width: 112,
                                                        formatter: function (val, opt, row) {
                                                            kondisi = 5;
                                                            jqgridRow = opt;
                                                            var disabled = ' btn btn-outline-primary btn-elevate btn-icon btn-sm btnRW" data-id="' + row.id_rt + ' ';

                                                            
                                                            var btnEdit = '<button type="button" title="Edit RT" class="btn btn-outline-success btn-elevate btn-icon btn-sm btnEditRT" data-grid="' + rt_grid_id + '" data-rowid="' + opt.rowId + '"><i class="flaticon-edit"></i></button>&nbsp;';

                                                            var btnMarker = '<button type="button" title="Pindah RW" class=" btn btn-outline-primary btn-elevate btn-icon btn-sm btnRW" data-id="' + row.id_rt + ' "><i class="fa flaticon-placeholder-3"></i></button>&nbsp;';

                                                            var btnDelete = '<button type="button" title="Delete RT" class="btn btn-outline-danger btn-elevate btn-icon btn-sm btnDeleteRT" data-grid="' + rt_grid_id + '" data-rowid="' + opt.rowId + '"><i class="flaticon-delete"></i></button>&nbsp;';
                                                            if (hasUpdate && !hasApproved) {
                                                                return btnEdit + btnMarker + btnDelete;
                                                            } else {
                                                                return '';
                                                            }

                                                        }
                                                    }, ],
                                                rowNum: -1,
                                                viewrecords: true,
                                                pager: rt_pager_id,
                                                height: '100%',
                                                sortable: true,
                                                shrinkToFit: pShrinkToFit,
                                                forceFit: pForceFit,
                                                rownumbers: true,
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

                                        // button tambah RT
                                        var $RTGrid = $("#" + rt_grid_id);
                                        if (hasUpdate && !hasApproved) {
                                            $('<button type="button" class="btnAddRT btn-add-titlebar" data-id="' + idRW + '" title="Tambah RT">Tambah RT</button>').appendTo('#gview_' + rt_grid_id + ' > div.ui-jqgrid-titlebar')
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
                                                    // beforeSubmit: function(postdata, formid){
                                                    //     postdata.idRW = idRW;
                                                    //     $.get(base_url + '/wilayah/cekDobelRT/', postdata, function( response ) {
                                                    //         if (response.status) {
                                                    //            AddPostRT(postdata);
                                                    //         } else {
                                                    //             Swal.fire( '', response.message, 'alert' );
                                                    //         }
                                                    //     });
                                                    // },
                                                    onclickSubmit: function (response, postdata) {
                                                        postdata.idRW = idRW;
                                                        KTApp.blockPage({
                                                            message: 'Cek Kode RT Harap tunggu!...'
                                                        }); // KTApp.unblockPage();                                                        

                                                        if (postdata.KodeRT.length < 3) {
                                                            KTApp.unblockPage();                                                
                                                            Swal.fire( '', 'Kode Depdagri RT Kurang dari 3 digit', 'info' );
                                                            jQuery("#" + rt_grid_id).trigger("reloadGrid");  
                                                        } else {                                                            
                                                            $.get(base_url + '/wilayah/cekDobelRT/', postdata, function( response ) {
                                                                if (response.status) {
                                                                   KTApp.unblockPage();
                                                                   AddPostRT(postdata);
                                                                } else {
                                                                    KTApp.unblockPage();
                                                                    Swal.fire( '', response.message, 'alert' );
                                                                    jQuery("#" + postdata.id).trigger("reloadGrid");
                                                                }
                                                            }); 
                                                        }                                                        
                                                    },
                                                });
                                            });
                                        }



                                    } // end subgrid lvl 5 - RT

                                }); // jquery subgrid level 4 - RW
                            // Tombol Add delete insert
                            var $RWGrid = $("#" + rw_grid_id);
                            if (hasUpdate && !hasApproved) {
                                $('<button type="button" class="btnAddRW btn-add-titlebar" data-id="' + idkelurahan + '" title="Tambah RW">Tambah RW</button>').appendTo('#gview_' + rw_grid_id + ' > div.ui-jqgrid-titlebar')
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
                                            KTApp.blockPage({
                                                message: 'Cek Kode RW Harap tunggu!...'
                                            }); // KTApp.unblockPage();

                                            //console.log(grid_id);
                                            //console.log(postdata.KodeDepdagri.length);

                                            if (postdata.KodeDepdagri.length < 4) {
                                                KTApp.unblockPage();                                                
                                                Swal.fire( '', 'Kode Depdagri Kurang dari 4 digit', 'info' );
                                                jQuery("#" + rw_grid_id).trigger("reloadGrid");  
                                            } else {
                                                $.get(base_url + '/wilayah/cekDobelRW/', postdata, function( response ) {
                                                    if (response.status) {
                                                       KTApp.unblockPage(); 
                                                       AddPostRW(postdata);
                                                    } else {    
                                                        KTApp.unblockPage();                                                
                                                        Swal.fire( '', response.message, 'alert' );
                                                        jQuery("#" + postdata.id).trigger("reloadGrid");                                                    
                                                    }
                                                });
                                            }
                                        },
                                    });
                                });
                            }
                        } // end subgrid lvl 4 - RW

                    }); // jquery subgrid level 3 - Kelurahan
                // ------------------------------------ button KELURAHAN ------------------------------------

            } // end subgrid lvl 3 - Kelurahan

        }); // jquery subgrid 2 - Kecamatan
    }

    var commonError = function (data) {
        return "Error Occured during Operation. Please try again";
    }


    var btnEditRThandler = function () {
         $(document).on('click', '.btnEditRT', function () {
            
            var rowid = $(this).data('rowid');
            var grid_rt = $(this).data('grid');

            $('#' + grid_rt).jqGrid("editGridRow", rowid, {
                    editCaption: "Edit Data RT",
                    edittext: "Edit",
                    closeOnEscape: true,
                    closeAfterEdit: true,
                    savekey: [true, 13],
                    errorTextFormat: this.commonError,
                    width: "500",
                    reloadAfterSubmit: true,
                    bottominfo: "Fields marked with (*) are required.!",
                    top: "60",
                    left: "5",
                    right: "5",
                    beforeShowForm: function () {
                        $("#tr_id_rt").hide();
                    },
                    onclickSubmit: function (response, postdata) {
                        //call edit button
                        postdata.grid_id = grid_rt;
                         if (postdata.KodeRT.length < 3) {
                            KTApp.unblockPage();                                                
                            Swal.fire( '', 'Kode Depdagri RT Kurang dari 3 digit', 'info' );
                            jQuery("#" + rt_grid_id).trigger("reloadGrid");  
                        } else {  //console.log(postdata);                        
                            EditPostRT(postdata);
                        }                         
                    }
               });                                 
         });
    }

    function EditPostRT(params) {
            //Here you need to define ajax call for update records to server
            //console.log('RT');
            //console.log(params);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan diubah ke basis data!",
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

            //console.log(rowData);

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
                    confirmButtonText: 'Yes, Delete it!'
                }).then((result) => {
                    if (result.value) {
                        KTApp.blockPage({
                            message: 'Please Wait!...'
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
                    confirmButtonText: 'Yes, Delete it!'
                }).then((result) => {
                    if (result.value) {
                        KTApp.blockPage({
                            message: 'Please Wait!...'
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
                    //$("#tr_KodeDepdagri").hide();
                },
                onclickSubmit: function (response, postdata) {
                    postdata.grid_id = grid_rw;
                    //console.log(postdata);
                     if (postdata.KodeDepdagri.length < 4) {
                        KTApp.unblockPage();                                                
                        Swal.fire( '', 'Kode Depdagri Kurang dari 4 digit', 'info' );
                        jQuery("#" + rw_grid_id).trigger("reloadGrid");  
                    } else {
                          EditPostRW(postdata);                              
                    }                    
                }
            });
        });
    }

    function EditPostRW(params) {
        //console.log(params);

        Swal.fire({
            title: "",
            text: "Anda yakin akan menyimpan data ini?",
            icon: 'question',
            showCancelButton: true,
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
                    })
                    .done(function (response) {
                        var message = (response.message) ? response.message : '';
                        if (response.status) {
                            //toastr.options.onHidden = formClear;
                            toastr.success(message);
                            KTApp.unblockPage();
                            jQuery("#" + params.id).trigger("reloadGrid");
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


    function AddPostRT(params) {
            //Here you need to define ajax call for insert records to server
            //console.log(params);
            Swal.fire({
                title: 'Apakah anda yakin, akan menambahkan data baru ini?',
                text: "Data akan tersimpan dibasis data!",
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

                } else { jQuery("#" + params.id).trigger("reloadGrid"); }// end result
            }) // end then
        }

    function AddPostRW(params) {
        Swal.fire({
            title: 'Apakah anda yakin, akan menambahkan data baru ini?',
            text: "Data akan disimpan di basis data!",
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
                        url: base_url + '/wilayah/AddPostRW/',
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
                            jQuery("#" + params.id).trigger("reloadGrid");
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

            } else { jQuery("#" + params.id).trigger("reloadGrid"); } // end result
        }) // end then

    }

    var btnSaveHandler = function () {
        $('#btnSave').click(function (e) {
            var selRowId = jqgridRow; //$(this).data('id');
            var row = $('#tablegrid').jqGrid('getRowData', selRowId);
            var id;
            var key;
            var message = null;

            //console.log(selRowId);

            if (kondisi == 1) {
                // Kabupaten pindah provinsi
                id = $('#Provinsi').val();
                key = id_kab;
                if (!$('#Provinsi').val()) {
                    message = 'Please make sure the selected province field is selected!';
                }

            } else if (kondisi == 2) {
                // Kecamatan Pindah Kabupaten
                id = $('#Kabupaten').val();
                key = id_kec;
                if (!$('#Provinsi').val() || !$('#Kabupaten').val()) {
                    message = 'Please make sure the selected provinsi and Kabupaten field is selected !';
                }
            } else if (kondisi == 3) {
                // Kelurahan pindah Kecamatan
                id = $('#Kecamatan').val();
                key = id_kel;
                if (!$('#Provinsi').val() || !$('#Kabupaten').val() || !$('#Kecamatan').val()) {
                    message = 'Please make sure the selected provinsi,Kabupaten and kecamatan field is selected !';
                }
            } else if (kondisi == 4) {
                // RW pindah Kelurahan.
                id = $('#Kelurahan').val();
                key = id_rw.replace(/\s+/g, '');
                if (!$('#Provinsi').val() || !$('#Kabupaten').val() || !$('#Kecamatan').val() || !$('#Kelurahan').val()) {
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

            if (message == null) {

                Swal.fire({
                    title: 'Apakah anda yakin, akan merubah data ini?',
                    text: "Pastikan data sudah sesuai dan benar sebelum menyimpannya!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ubah data!'
                }).then((result) => {
                    if (result.value) {
                        KTApp.block('#modalResetValid', {
                            message: 'Wait...'
                        });

                        //console.log(kondisi+ ' - ' +  id + ' - ' + key);

                        $.ajax({
                            method: 'GET', // Type of response and matches what we said in the route
                            url: base_url + '/wilayah/UbahWilayahParent/' + id + '/' + key + '/' + kondisi, // This is the url we gave in the route
                            success: function (response) { // What to do if we succeed
                                if (response.status) {
                                    Swal.fire(
                                        'Validate!',
                                        response.message,
                                        'success');
                                    
                                    jQuery("#" + grid_id).trigger("reloadGrid");
                                    $('#modalResetValid').modal('hide');
                                    //jQuery("#" + selRowId.rowId).trigger("reloadGrid");                                    
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                                console.log(JSON.stringify(jqXHR));
                                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                            }
                        }).always(function () {
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
            } // End check key
        });
    }

    // button Pindah Provinsi
    var btnProv = function () {
        $(document).on('click', '.btnProv', function () {
            kondisi = 1;
            id_kab = $(this).attr("data-id");

            var selRowId = $(this).data('id');
            var row = $('#tablegrid').jqGrid('getRowData', selRowId);

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
    var btnKab = function () {
        $(document).on('click', '.btnKab', function () {
            kondisi = 2;
            id_kec = $(this).attr("data-id");

            getprovinsi();
            getshowall();

            var selRowId = $(this).data('id');
            var row = $('#tablegrid').jqGrid('getRowData', selRowId);

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
    var btnKec = function () {
        $(document).on('click', '.btnKec', function () {
            kondisi = 3;
            id_kel = $(this).attr("data-id");

            getprovinsi();
            getshowall();

            var selRowId = $(this).data('id');
            var row = $('#tablegrid').jqGrid('getRowData', selRowId);

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
    var btnKel = function () {
        $(document).on('click', '.btnKel', function () {
            kondisi = 4;
            id_rw = $(this).attr("data-id");

            //console.log(id_rw);

            var selRowId = $(this).data('id');
            var row = $('#tablegrid').jqGrid('getRowData', selRowId);
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

            console.log('aaaaa');

            var selRowId = $(this).data('id');
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

    var getprovinsi = function () {
        $('#Kabupaten').children().remove();
        $('#Kecamatan').children().remove();
        $('#Kelurahan').children().remove();
        $('#RW').children().remove();

        var xhr = $.ajax({
                url: base_url + '/wilayah/provinsi/' + id_prov,
                method: 'GET',
                dataType: 'json',
            })
            .done(function (response) {
                var el = $('#Provinsi');
                el.children().remove();
                el.append($("<option></option>").attr("value", '').text(''));
                $.each(response, function (key, value) {
                    el.append($("<option></option>").attr("value", value.id_provinsi).text(value.nama_provinsi));
                });

            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                toastr.error('getProvinsi: ' + jqXHR.statusText);
            })
            .always(function () {});
    }

    var getKabupaten = function () {
        var provinsi = $('#Provinsi').val();
        var xhr = $.ajax({
                url: base_url + '/wilayah/kotakab/' + provinsi,
                method: 'GET',
                dataType: 'json',
            })
            .done(function (response) {
                var el = $('#Kabupaten');
                el.children().remove();
                el.append($("<option></option>").attr("value", '').text(''));
                $.each(response, function (key, value) {
                    //console.log(value.id_kabupaten);
                    el.append($("<option></option>").attr("value", value.id_kabupaten).text(value.nama_kabupaten));
                });

            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                toastr.error('getKabupaten: ' + jqXHR.statusText);
            })
            .always(function () {});
    }

    var getKecamatan = function () {
        var provinsi = $('#Kabupaten').val();
        var xhr = $.ajax({
                url: base_url + '/wilayah/kecamatan/' + provinsi,
                method: 'GET',
                dataType: 'json',
            })
            .done(function (response) {
                var el = $('#Kecamatan');
                el.children().remove();
                el.append($("<option></option>").attr("value", '').text(''));
                $.each(response, function (key, value) {
                    //console.log(value.id_kabupaten);
                    el.append($("<option></option>").attr("value", value.id_kecamatan).text(value.nama_kecamatan));
                });

            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                toastr.error('getKecamatan: ' + jqXHR.statusText);
            })
            .always(function () {});
    }

    var getKelurahan = function () {
        var provinsi = $('#KecamatanId').val();
        var xhr = $.ajax({
                url: base_url + '/wilayah/kelurahan/' + provinsi,
                method: 'GET',
                dataType: 'json',
            })
            .done(function (response) { 
                var el = $('#Kelurahan');
                el.children().remove();
                el.append($("<option></option>").attr("value", 'all').text('-- Pilih Semua --'));
                $.each(response, function (key, value) {                    
                    el.append($("<option></option>").attr("value", value.id_kelurahan).text(value.nama_kelurahan));
                });

            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                toastr.error('getKelurahan: ' + jqXHR.statusText);
            })
            .always(function () {});
    }

    var getRW = function () {
        var provinsi = $('#Kelurahan').val();
        if (provinsi === 'all') {
            var el = $('#RW');
            el.children().remove();
        } else {
            var xhr = $.ajax({
                url: base_url + '/wilayah/rw/' + provinsi,
                method: 'GET',
                dataType: 'json',
            })
            .done(function (response) {
                var el = $('#RW');
                el.children().remove();
                el.append($("<option></option>").attr("value", 'all').text('-- Pilih Semua --'));
                $.each(response, function (key, value) {
                    //console.log('minggus ~~~~ : ' + provinsi);
                    el.append($("<option></option>").attr("value", value.id).text(value.text));
                });

            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                toastr.error('getRW---> : ' + jqXHR.statusText);
            })
            .always(function () {});
        }
    }

    var getshowall = function () {        

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

    var select2Handler = function () {
        $('#Provinsi').select2({
            placeholder: '--- Pilih Provinsi ---',
        }).on('change', function (e) {
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
        }).on('change', function (e) {
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
        }).on('change', function (e) {
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
                 var kelurahan = $('#Kelurahan').val();
                 var urls = '';
                    if (kelurahan === '' || kelurahan === null || kelurahan === 'all' ) {                        
                        urls = base_url + '/wilayah/getDataKelurahan/' + idkecamatan;                         
                    } else {
                         urls = base_url + '/wilayah/kelurahanbyid/' + kelurahan;
                    }                 
                 // jQuery("#" + kel_grid_id).jqGrid().setGridParam({url : urls}).trigger("reloadGrid");               
                 jQuery("#tablegrid").jqGrid().trigger("reloadGrid") ;
                 getRW();
            }

        });
        
       $('#RW').select2({
            placeholder: '--- Pilih Tingkat RW ---',
        }).on('change', function (e) {
            var kelurahan = $('#Kelurahan').val();
            var rw = $('#RW').val();
            //console.log('RW -------------->'+kelurahan + ' - '+ rw);   

            var urlrw = '';
            if (rw === '' || rw === null || rw === 'all' ) {                        
                //console.log('show all rw');
                urlrw + base_url + '/wilayah/getDataRw/' + kelurahan;              
            } else {
                urlrw = base_url + '/wilayah/getrw/' + kelurahan + '/' + rw;
            }                 

           
            jQuery("#tablegrid").jqGrid().trigger("reloadGrid") ;
            //jQuery("#" + kel_grid_id).jqGrid().setGridParam({url : $url}).trigger("reloadGrid");           
        });
        // end here ---
    }
    
    var formApprove = function () {
        $('#btnApprove').click(function (e) {
            e.preventDefault();
            
            KTApp.blockPage({
                message: 'Harap tunggu!...'
            });
            
            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };
            
            var xhr = $.ajax({
                url: base_url+'/approval/kecamatan/close',
                method: 'POST',
                dataType: 'json',
                headers: header
            })
            .done(function(response){
                message = (response.message) ? response.message : '';
                if (response.status) {
                    toastr.success(message);
                    $('#status').removeClass('kt-hidden');
                    $('#btnApprove').addClass('kt-hidden');
                    $('#btnDisapprove').removeClass('kt-hidden');
                } else {
                    message = (message !== '') ? message :  'Ada kesalahan';
                    swal.fire('', message, 'error');
                }
            }).fail(function(jqXHR, textStatus, errorThrown){
                swal.fire('', jqXHR.statusText, 'error');
            }).always(function() {
                KTApp.unblockPage();
            });
        });
    }
    
    var formDisApprove = function () {
        $('#btnDisapprove').click(function (e) {
            e.preventDefault();
            
            KTApp.blockPage({
                message: 'Harap tunggu!...'
            });
            
            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };
            
            var xhr = $.ajax({
                url: base_url+'/approval/kecamatan/open',
                method: 'POST',
                dataType: 'json',
                headers: header
            })
            .done(function(response){
                message = (response.message) ? response.message : '';
                if (response.status) {
                    toastr.success(message);
                    $('#status').addClass('kt-hidden');
                    $('#btnApprove').removeClass('kt-hidden');
                    $('#btnDisapprove').addClass('kt-hidden');
                } else {
                    message = (message !== '') ? message :  'Ada kesalahan';
                    swal.fire('', message, 'error');
                }
            }).fail(function(jqXHR, textStatus, errorThrown){
                swal.fire('', jqXHR.statusText, 'error');
            }).always(function() {
                KTApp.unblockPage();
            });
        });
    }

    return {
        init: function () {
            initTable();
            getshowall();
            btnProv();
            btnKab();
            btnKec();
            btnKel();
            btnRW();

            btnEditRWhandler();
            btnEditRThandler();
            btnDeleteRThandler();
            btnDelRWhandler();
            select2Handler();
            //getprovinsi();

            btnSaveHandler();
            formApprove();
            formDisApprove();

            getKelurahan();
        }
    };
}
();

$(document).ready(function () {
    WilayahKecamatan.init();
});
