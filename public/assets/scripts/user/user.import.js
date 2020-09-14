var UserImport = function () {
    var griddata;
    
    
	var select2Handler = function() {

		$('#usersfile').select2({
			placeholder: '--- Pilih Role ---',
            minimumResultsForSearch: -1,
			// allowClear: true
		})

	}
    
    var fileChanged = function() {
       $('#usersfile').on('change', function (e) {
            e.preventDefault();
            var roleID = $('#RoleID').val();
            if (roleID=='') {
                swal.fire('', 'Role sbelum dipilih', 'error');
                return false;
            }
            var filename = $(this).val();
            if (filename=='') {
                return false;
            }
            doUpload();
            
       });
           
    }
    
    var doUpload = function () {
            KTApp.blockPage({message: 'Harap tunggu...'});
            var file_data = $('#usersfile').prop('files')[0]; 
            var param = new FormData();                  
            param.append('file', file_data);
            param.append('RoleID', $('#RoleID').val());
    
            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };
            
            var xhr = $.ajax({
                url: base_url+'/user/processimport',
                method: 'POST',
                dataType: 'json',
                async: false,
                cache: false,
                contentType: false,
                processData: false, 
                headers: header,
                data: param
            })
            .done(function(response){
                griddata = response;
                initTable();
                /* message = (response.message) ? response.message : '';
                if (response.status) {
                    toastr.options.onHidden = formPasswordClear;
                    toastr.success(message);
                } else {
                    message = (message !== '') ? message :  'Ada kesalahan';
                    swal.fire('', message, 'error');
                } */
            }).fail(function(jqXHR, textStatus, errorThrown){
                swal.fire('', jqXHR.statusText, 'error');
            }).always(function() {
                KTApp.unblockPage();
            });
    }
    
    var btnUploadHandler = function () {
        $('#btnUpload').click(function (e) {
            e.preventDefault();
            doUpload();
        });
    }
    
    var btnProsesHandler = function () {
        $('#btnProses').click(function (e) {
            e.preventDefault();
            KTApp.blockPage({message: 'Harap tunggu...'});
            
            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };
            var xhr = $.ajax({
                url: base_url+'/user/processdata',
                method: 'POST',
                dataType: 'json',
                headers: header,
            })
            .done(function(response){
                
                griddata = response;
                initTable();
            }).fail(function(jqXHR, textStatus, errorThrown){
                swal.fire('', jqXHR.statusText, 'error');
            }).always(function() {
                KTApp.unblockPage();
            });
        });
    }
    
	var initTable = function() {
        $('#table').jqGrid("GridUnload");
		$('#table').jqGrid({
			datatype: 'local',
            data: griddata,
			pager: '#pager',
			shrinkToFit: pShrinkToFit,
			forceFit: pForceFit,
			sortable: true,
			viewrecords: true,
			rownumbers: true,
			autowidth: true,
			rowNum: 10,
			rowList: [5, 10, 20],
			colModel: [{
				label: 'Username',
				name: 'UserName',
                key:true, 
                hidden: false,
			}, {
				name: 'NamaLengkap',
				label: 'Nama Lengkap',
			}, {
				name: 'NIK',
				label: 'NIK',
			}, {
				name: 'Alamat',
				label: 'Alamat',
			}, {
				name: 'NoTelepon',
				label: 'No Telepon',
			}, {
				name: 'Email',
				label: 'Email',
			}, {
				name: 'NIP',
				label: 'NIP',
			}, {
				name: 'Wilayah',
				label: 'WilayahID',
			}, {
				name: 'Keterangan',
				label: 'Keterangan',
			},
            ],
		});

	}
    
    return {
        init: function () {
            btnUploadHandler();
            fileChanged();
            btnProsesHandler();
        }
    };
}();

$(document).ready(function () {
    UserImport.init();
});