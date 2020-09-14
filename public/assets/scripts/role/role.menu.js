var RoleMenu = function () {
    
	var select2Handler = function() {

		$('#RoleID').select2({
			placeholder: '--- Pilih Role ---',
            minimumResultsForSearch: -1,
			// allowClear: true
		})
        .on('change', function() {
            console.log($('#RoleID').val());
            initTree();
        });//.trigger('change');

	}
    
    var selectedNode = {};
    
     var initTree = function() {
         var roleid = $('#RoleID').val();
         $("#menutree").jstree('destroy');
        $("#menutree").jstree({
            "core" : {
                "themes" : {
                    "responsive": true
                }, 
                // so that create works
                "check_callback" : true,
                'data' : {
                    'url' : function (node) {
                      return base_url + '/menu/role/'+roleid;
                    },
                    'data' : function (node) {
                      return { 'parent' : node.id };
                    }
                }
            },
            "types" : {
                "default" : {
                    "icon" : "far fa-circle kt-font-info"
                },
                "sub" : {
                    "icon" : "fa fa-folder kt-font-info"
                }
            },
            "plugins" : [ "dnd", "types", "checkbox" ]
        });
    }
    
    var formSave = function () {
        $('#btnUpdate').click(function (e) {
            e.preventDefault();
            
            var roleid = $('#RoleID').val();
             if (roleid=='') {
                 return false;
             }
            var checked = $("#menutree").jstree("get_checked");
            var undetermined = $("#menutree").jstree("get_undetermined");
            $(undetermined).each(function (x, el) {
                checked.push(el);
            });
            
            KTApp.blockPage({message: 'Harap tunggu...'});
            var param = {
                checked: checked,
            };
            
            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };
            
            var xhr = $.ajax({
                url: base_url+'/role/'+roleid+'/menu',
                method: 'POST',
                dataType: 'json',
                headers: header,
                data: param
            })
            .done(function(response){
                message = (response.message) ? response.message : '';
                if (response.status) {
                    // toastr.options.onHidden = formCallBack(response.data);
                    toastr.success(message);
                } else {
                    message = (message !== '') ? message :  'Ada kesalahan';
                    swal.fire('', message, 'error');
                }
            }).fail(function(jqXHR, textStatus, errorThrown){
                console.log('jqXHR', jqXHR);
                swal.fire('', jqXHR.statusText, 'error');
            }).always(function() {
                KTApp.unblockPage();
            });
        });
    }
    
    var menuInit = function() {
        $("#menutree_").jstree({
            "core" : {
                "themes" : {
                    "responsive": false
                }, 
                // so that create works
                "check_callback" : true,
                'data': [{
                        "text": "Parent Node",
                        "children": [{
                            "text": "Initially selected",
                            "state": {
                                "selected": true
                            }
                        }, {
                            "text": "Custom Icon",
                            "icon": "fa fa-warning kt-font-danger"
                        }, {
                            "text": "Initially open",
                            "icon" : "fa fa-folder kt-font-success",
                            "state": {
                                "opened": true
                            },
                            "children": [
                                {"text": "Another node", "icon" : "fa fa-file kt-font-waring"}
                            ]
                        }, {
                            "text": "Another Custom Icon",
                            "icon": "fa fa-warning kt-font-waring"
                        }, {
                            "text": "Disabled Node",
                            "icon": "fa fa-check kt-font-success",
                            "state": {
                                "disabled": true
                            }
                        }, {
                            "text": "Sub Nodes",
                            "icon": "fa fa-folder kt-font-danger",
                            "children": [
                                {"text": "Item 1", "icon" : "fa fa-file kt-font-waring"},
                                {"text": "Item 2", "icon" : "fa fa-file kt-font-success"},
                                {"text": "Item 3", "icon" : "fa fa-file kt-font-default"},
                                {"text": "Item 4", "icon" : "fa fa-file kt-font-danger"},
                                {"text": "Item 5", "icon" : "fa fa-file kt-font-info"}
                            ]
                        }]
                    },
                    "Another Node"
                ]
            },
            "types" : {
                "default" : {
                    "icon" : "fa fa-folder kt-font-success"
                },
                "file" : {
                    "icon" : "fa fa-file  kt-font-success"
                }
            },
            "state" : { "key" : "demo2" },
            "plugins" : [ "dnd", "state", "types" ]
        });    
    }
    
    return {
        init: function () {
            // initTree();
            select2Handler();
            formSave();
        }
    };
}();

$(document).ready(function () {
    RoleMenu.init();
});