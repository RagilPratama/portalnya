var UserProfile = function () {
    
    var formSave = function () {
        $('#btnSave').click(function (e) {
            e.preventDefault();
            KTApp.blockPage({message: 'Harap tunggu...'});
            
            var id = $('#ID').val();
            
            var param = {
                NIK: $('#NIK').val(),
                NamaLengkap: $('#NamaLengkap').val(),
                Alamat: $('#Alamat').val(),
                Email: $('#Email').val(),
                NIP: $('#NIP').val(),
                NoTelepon: $('#NoTelepon').val(),
            };
            
            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };
            
            var xhr = $.ajax({
                url: base_url+'/user/updateprofile/'+id,
                method: 'POST',
                dataType: 'json',
                headers: header,
                data: param
            })
            .done(function(response){
                message = (response.message) ? response.message : '';
                if (response.status) {
                    toastr.success(message);
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
    
    var formPasswordClear = function () {
        $('#oldpassword').val('');
        $('#newpassword').val('');
        $('#newpasswordconfirm').val('');
    };
    
    var formPassword = function () {
        $('#btnChangePwd').click(function (e) {
            e.preventDefault();
            KTApp.blockPage({message: 'Harap tunggu...'});
            
            var param = {
                oldpassword: $('#oldpassword').val(),
                newpassword: $('#newpassword').val(),
                newpasswordconfirm: $('#newpasswordconfirm').val(),
            };
            
            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };
            
            var xhr = $.ajax({
                url: base_url+'/user/changepassword',
                method: 'PUT',
                dataType: 'json',
                headers: header,
                data: param
            })
            .done(function(response){
                message = (response.message) ? response.message : '';
                if (response.status) {
                    toastr.options.onHidden = formPasswordClear;
                    toastr.success(message);
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
            formSave();
            formPassword();
        }
    };
}();

$(document).ready(function () {
    UserProfile.init();
});