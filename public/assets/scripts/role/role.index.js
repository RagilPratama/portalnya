var RoleIndex = function () {
    var initTable = function () {
        var datatable = $('#tablegrid').KTDatatable({
			data: {
				type: 'remote',
				source: {
					read: {
						url: base_url+'/role/datapaging',
                        method: 'GET',
						map: function(raw) {
							var dataSet = raw;
							if (typeof raw.data !== 'undefined') {
								dataSet = raw.data;
							}
							return dataSet;
						},
					},
                },
                saveState: {
                    webstorage: false
                },
				serverPaging: true,
				serverFiltering: true,
				serverSorting: true,
			},
			layout: {
				scroll: false,
				footer: false,
			},
			sortable: true,
			pagination: true,
			search: {
				input: $('#searchTerm'),
			},
            toolbar:{
                items: {
                    pagination: {
                        pageSizeSelect: [10, 20]
                    }
                }
            },
			columns: [
				{
					field: 'ID',
					title: 'Role ID',
					sortable: 'asc',
					width: 50,
					type: 'number',
					selector: false,
					textAlign: 'right',
				}, {
					field: 'RoleName',
					title: 'Nama Role',
				}, {
					field: 'Actions',
					title: 'Actions',
					sortable: false,
					width: 110,
					overflow: 'visible',
					autoHide: false,
					template: function(row) {
                        var btnEdit = '<button type="button" title="Edit Role &quot;'+row.RoleName+'&quot;" class="btn btn-outline-brand btn-icon btn-sm btnEdit" data-id="'+ row.ID +'"><i class="fa fa-edit"></i></button>&nbsp;';
                            var btnMenu = '<button type="button" title="Pengaturan Menu &quot;'+row.RoleName+'&quot;" class="btn btn-outline-brand btn-icon btn-sm btnMenu" data-id="'+ row.ID +'"><i class="fa fa-list"></i></button>&nbsp;';
                            var btnDelete = '<button type="button" title="Hapus Hak Akses &quot;'+row.RoleName+'&quot;" class="btn btn-outline-brand btn-icon btn-sm btnDelete" data-id="'+ row.ID +'"><i class="fa fa-trash"></i></button>&nbsp;';
                        return btnEdit+btnMenu;
                        
					},
                }
            ],
		});
    }

    var initTablex = function () {
        $('#tableroles').jqGrid({
            datatype: 'json',
            url: base_url+'/role/datapaging',
            pager: '#pagerroles',
			shrinkToFit: pShrinkToFit,
			forceFit: pForceFit,
            sortable: true,
            viewrecords: true,
            rownumbers: true,
            autowidth: true,
            rowNum: 10,
            rowList: [5, 10, 20],
            colModel: [
                    {
						label: 'RoleID',
						name: 'ID',
					}, { 
						label: 'Nama Role', 
						name: 'RoleName',
					}, { 
						label: 'Level', 
						name: 'Level',
					}, { 
						label: 'Tanggal Dibuat', 
						name: 'CreatedDate',
					}, { 
						label: 'Action',
                        align: 'center',
						formatter: function(val, opt, row){
                            var btnEdit = '<button type="button" title="Edit Role &quot;'+row.RoleName+'&quot;" class="btn btn-outline-brand btn-icon btn-sm btnEdit" data-id="'+ row.ID +'"><i class="fa fa-edit"></i></button>&nbsp;';
                            var btnMenu = '<button type="button" title="Pengaturan Menu &quot;'+row.RoleName+'&quot;" class="btn btn-outline-brand btn-icon btn-sm btnMenu" data-id="'+ row.ID +'"><i class="fa fa-list"></i></button>&nbsp;';
                            var btnDelete = '<button type="button" title="Hapus Hak Akses &quot;'+row.RoleName+'&quot;" class="btn btn-outline-brand btn-icon btn-sm btnDelete" data-id="'+ row.ID +'"><i class="fa fa-trash"></i></button>&nbsp;';
						return btnEdit+btnMenu;
                        }
                    },
            ],
        });

    }

    var btnCreateHandler = function () {
        $('#btnCreate').click(function(){
            $('#modalRoleCreate').modal();
        });
    }
    
    var formSave = function () {
        $('#btnSave').click(function (e) {
            e.preventDefault();
            KTApp.block('#modalRoleCreate', {message: 'Harap tunggu...'});
            var param = {
                RoleName: $('#formRoleCreate #name').val(),
            };
            
            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };
            
            var xhr = $.ajax({
                url: base_url+'/role',
                method: 'POST',
                dataType: 'json',
                headers: header,
                data: param
            })
            .done(function(response){
                var message = (response.message) ? response.message : '';
                if (response.status) {
                    toastr.options.onHidden = formClear;
                    toastr.success(message);
                } else {
                    message = (message !== '') ? message :  'Ada kesalahan';
                    swal.fire('', message, 'error');
                }
            }).fail(function(jqXHR, textStatus, errorThrown){
                var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
                swal.fire('', message, 'error');
            }).always(function() {
                KTApp.unblock('#modalRoleCreate');  
            });
        });
    }
    
    var formUpdate = function () {
        $('#btnUpdate').click(function (e) {
            e.preventDefault();
            KTApp.block('#modalRoleEdit', {message: 'Harap tunggu...'});
            var id = $('#formRoleEdit #ID').val();
            var param = $('#formRoleEdit').serializeObject()
            
            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };
            
            var xhr = $.ajax({
                url: base_url+'/role/'+id,
                method: 'PUT',
                dataType: 'json',
                headers: header,
                data: param
            })
            .done(function(response){
                var message = (response.message) ? response.message : '';
                if (response.status) {
                    toastr.options.onHidden = formClear;
                    toastr.success(message);
                } else {
                    message = (message !== '') ? message :  'Ada kesalahan';
                    swal.fire('', message, 'error');
                }
            }).fail(function(jqXHR, textStatus, errorThrown){
                var message = (typeof jqXHR.responseJSON === 'undefined') ? errorThrown : jqXHR.responseJSON.message;
                swal.fire('', message, 'error');
            }).always(function() {
                KTApp.unblock('#modalRoleEdit');  
            });
        });
    }
    
    var formClear = function () {
        $('#formRoleCreate, #formRoleEdit').trigger('reset');
        $('#modalRoleCreate, #modalRoleEdit').modal('hide');
        $('#tableroles').trigger('reloadGrid');
    }
    
    var btnEditHandler = function () {
        $(document).on('click', '.btnEdit', function(){
            var selRowId = $('#tableroles').jqGrid ('getGridParam', 'selrow');
            var row = $('#tableroles').jqGrid('getRowData', selRowId);
            $('#formRoleEdit #ID').val(row.ID);
            $('#formRoleEdit #RoleName').val(row.RoleName);
            $('#formRoleEdit #Level').val(row.Level?row.Level : 0);
            $('#modalRoleEdit ').modal();
            
        });
    }
    
    return {
        init: function () {
            initTable();
            btnCreateHandler();
            formSave();
            btnEditHandler();
            formUpdate();
        }
    };
}();

$(document).ready(function () {
    RoleIndex.init();
});