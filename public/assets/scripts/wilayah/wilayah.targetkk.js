var TargetKK = function () {
    
    var editingID = null;
    var selectedUser = null;
    var checkedArea = [];
    var grid;
    
    var initTree = function () {
        if (grid) grid.jqGrid("GridUnload");
        grid = $('#treewilayah').jqGrid({
            datatype: 'json',
            // datastr: localdata,
            // treedatatype: "local",
            url: base_url+'/wilayah/treetarget',
            pager: '#pagerwilayah',
			shrinkToFit: pShrinkToFit,
			forceFit: pForceFit,
            autowidth: true,
            treeGrid: true,
            treeGridModel: 'adjacency',
            ExpandColumn: 'text',
            treedatatype: 'json',
            ExpandColClick: true,
            colModel: [
                {
                    label: 'ID',
                    name: 'id',
                    hidden: true,
                }, { 
                    label: 'WilayahID', 
                    name: 'WilayahID',
                    hidden: true,
                }, { 
                    label: 'Wilayah', 
                    name: 'text',
                }, { 
                    label: 'Level', 
                    name: 'level',
                    hidden: true,
                }, { 
                    label: 'Flag', 
                    name: 'Flag',
                    hidden: true,
                },  { 
                    label: 'Target KK', 
                    name: 'TargetKK',
                    editoptions: {
                        type: 'number'
                    },
                    // formatter: function(val, rowId, opt){
                    //     if (opt.level=='6') {        
                    //         return val === null || val ==='' ? '0' : parseInt(val);
                    //     } else {
                    //         return 'xxx';
                    //     }
                    // },
                    // formatoptions: {defaultValue:''}, 
                    editable: true,
                    edittype: 'text',
                },
            ],
            rowattr: function (rd) {
                if (rd.level === 6) {
                    return {"class": "table-primary"};
                }
            },
            onCellSelect: function(rowid, iCol, val, e) {
                endEdit(editingID);
                // var row = $(this).jqGrid('getRowData', rowid);
                var localrow = $(this).jqGrid('getLocalRow', rowid);
                if (localrow.level==6) {
                    editingID = rowid;
                    startEdit(editingID);
                } else {

                }
            }
        });

    }
    
    var startEdit = function (rowid) {
        grid.jqGrid(
            'editRow', 
            rowid, 
            { 
                key: true
            }
        );
    }
    
    var endEdit = function (rowid) {
        grid.jqGrid(
            'saveRow', 
            rowid
        );
    }
    
    var checkboxHandler = function () {
        $(document).on('click', '.checkPilih', function(){
            endEdit(editingID)
            var rowid = $(this).data('id');
            var row = $('#treewilayah').jqGrid('getRowData', rowid);
            if ($(this).is(':checked')) {
                if ((selectedUser.TingkatWilayahID==5 || selectedUser.TingkatWilayahID==6 )&& (row.TargetKK=='' || typeof row.TargetKK === 'undefined')) {
                    $('#treewilayah').jqGrid('setRowData', rowid, {TargetKK:0});
                }
                $('#treewilayah').jqGrid('setRowData', rowid, {Flag:1});
                editingID = rowid;
                startEdit(editingID);
            } else {
                if (row.TargetKK==0) {
                    $('#treewilayah').jqGrid('setRowData', rowid, {TargetKK:''});
                }
                $('#treewilayah').jqGrid('setRowData', rowid, {Flag:0});
            }
        });
    }
    
    var checkTargetKKVal = function(treedata) {
        var MaxTarget = $('#MaxTarget').val();
        var MinTarget = 0;
        var result = false;
        var data = null;
        var maxExceed = false;
        var output = {};
        var checked = treedata.filter(function (i){
            if (i.level == 6 && i.TargetKK > 0) {
                if (parseInt(i.TargetKK) < parseInt(MinTarget) || parseInt(i.TargetKK) > parseInt(MaxTarget)) {
                    maxExceed = true;
                }
                return true;
            } else {
                return false;
            }
        });
        if (checked.length > 0) {
            if (maxExceed) {
                data = 'Target KK tidak boleh melebihi batas: '+MaxTarget;
            } else {
                result = true;
                data = checked;
            }
        } else {
            data = 'Target KK Wilayah belum diisi';
        }
        output.result = result;
        output.data = data;
        return output;
    }
    
    var checkAllTargetKKVal = function(treedata) {
        var MaxTarget = $('#MaxTarget').val();
        var MinTarget = 0;
        var result = false;
        var data = null;
        var maxExceed = false;
        var output = {};
        var checked = treedata.filter(function (i){
            if (i.level == 6 && parseInt(i.TargetKK) >= 0) {
                if (parseInt(i.TargetKK) < parseInt(MinTarget) || parseInt(i.TargetKK) > parseInt(MaxTarget)) {
                    maxExceed = true;
                }
                return true;
            } else {
                return false;
            }
        });
        if (checked.length > 0) {
            if (maxExceed) {
                data = 'Target KK tidak boleh melebihi batas: '+MaxTarget;
            } else {
                result = true;
                data = checked;
            }
        } else {
            data = 'Target KK Wilayah belum diisi';
        }
        output.result = result;
        output.data = data;
        return output;
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
                url: base_url+'/approval/kecamatan/close/target',
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
                url: base_url+'/approval/kecamatan/open/target',
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
    
    var formSave = function () {
        $('#btnUpdateAkses').click(function (e) {
            e.preventDefault();
            endEdit(editingID);
            
            KTApp.blockPage({
                message: 'Harap tunggu!...'
            });
            var treedata = $('#treewilayah').jqGrid('getRowData');
            var aksesdata = [];
            aksesdata = checkAllTargetKKVal(treedata);
            if (!aksesdata.result) {
                swal.fire('', aksesdata.data, 'error');
                KTApp.unblockPage();
                return false;
            }
            
            var formdata = {
                tree: JSON.stringify(aksesdata.data)
            };
            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };
            
            var xhr = $.ajax({
                url: base_url+'/wilayah/updatetarget',
                method: 'POST',
                dataType: 'json',
                headers: header,
                data: formdata
            })
            .done(function(response){
                message = (response.message) ? response.message : '';
                if (response.status) {
                    toastr.success(message);
                } else {
                    message = (message !== '') ? message :  'Ada kesalahan';
                    swal.fire({
                        title: '',
                        text: message,
                        type: 'error'
                    })
                    .then(result => {
                        if (response.data=='approved') window.location.reload(true);
                    });
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
            initTree();
            checkboxHandler();
            formSave();
            formApprove();
            formDisApprove();
        },
        
    };
}();

$(document).ready(function () {
    TargetKK.init();
});