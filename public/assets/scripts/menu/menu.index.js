var MenuIndex = function () {
    
    var selectedNode = {};
    
     var demo6 = function() {
        $("#menutree").jstree({
            "core" : {
                "themes" : {
                    "responsive": false
                }, 
                // so that create works
                "check_callback" : true,
                'data' : {
                    'url' : function (node) {
                      return base_url + '/menu/datalist';
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
            "plugins" : [ "dnd", "state", "types" ]
        })
        .on('ready.jstree', function (e, data) {
            // console.log('ready', data);
        })
        .on('select_node.jstree', function (e, data) {
            selectedNode = data.node;
            console.log('select_node', data);
        });
    }
    
    var formCallBack = function(response) {
        response.text = response.name;
        console.log('formCallBack', response);
        if (typeof response.insert !== 'undefined'){
            $('#menutree').jstree().create_node(selectedNode.id, response, "last", function(data) {
              $('#menutree').jstree().set_type(selectedNode.id,'sub');
            });
            
            // selectedNode.addChildren({
				// title: data.name,
				// key: data.id,
                // route: data.route,
                // permission: data.permission,
                // sequence: data.sequence,
                // isactive: data.is_active
			// });
            // selectedNode.applyPatch({
                // folder: true,
                // expanded: true
            // });
        } else {
            // selectedNode.applyPatch({
                // title: data.name,
                // route: data.route,
                // permission: data.permission,
                // sequence: data.sequence,
                // isactive: data.is_active
            // });
        }
    }
        
    var btnToolHandler = function() {
        $('#btnCreate').click(function(){
            if (selectedNode == null) {
                toastr.error('Pilih Node terlebih dulu');
                return false;
            }
            toastr.success(selectedNode.text);
            $('#form').find("input[type=text], textarea, input[type=hidden]").val("");
            $('#parent_id').val(selectedNode.id == parseInt(selectedNode.id) ? selectedNode.id : '0');
            $('#parent_name').val(selectedNode.id == parseInt(selectedNode.id) ? selectedNode.text : 'Root');
            $('#form').removeAttr("hidden");
            $('#sequence').val(1);
            $('.perms').remove();
        });
        
    }
        
    var btnEditHandler = function() {
        $('#btnEdit').click(function(){
            if (selectedNode == null) {
                toastr.error('Pilih Node terlebih dulu');
                return false;
            }
            
            if(selectedNode.id != parseInt(selectedNode.id) || selectedNode.id=='0') {
                toastr.error('Node Root tidak dapat diedit');
                return false;
            }
            $('#form').find("input[type=text], textarea, input[type=hidden]").val("");
            $('#parent_id').val(selectedNode.parent.id == parseInt(selectedNode.parent.id) ? selectedNode.parent.id : '0');
            $('#parent_name').val(selectedNode.parent.text);
            $('#id').val(selectedNode.id);
            $('#name').val(selectedNode.text);
            $('#route').val(selectedNode.data.route);
            $('#sequence').val(selectedNode.data.sequence);
            
            $('.perms').remove();
            if (selectedNode.data.permission != null) {
                var cnt = selectedNode.data.permission.length;
                for (var i=0;i<cnt;i++){
                    var clone = $('.template.hidden').clone()
                        .removeClass('hidden').removeClass('template').addClass('perms')
                        .insertBefore('#btnAddRes');
                    $(clone).find('input[type=text]').attr('name','perm[]').val(selectedNode.data.permission[i]);
                }
            }
            
            if (selectedNode.data.is_active == 1){
                $('#active').attr("checked","");
            } else {
                $('#active').removeAttr("checked");
            }
            $('#form').removeAttr("hidden");
        });
        
    }
    
    var formSave = function () {
        $('#btnSave').click(function (e) {
            e.preventDefault();
            KTApp.blockPage({message: 'Harap tunggu...'});
            
            var parent_id = $('#parent_id').val();
            var param = {
                parent_id: $('#parent_id').val(),
                name: $('#name').val(),
                route: $('#route').val(),
                sequence: $('#sequence').val(),
                is_active: $('#is_active').val(),
            };
            
            var header = {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf_token,
            };
            
            var xhr = $.ajax({
                url: base_url+'/menu',
                method: 'POST',
                dataType: 'json',
                headers: header,
                data: param
            })
            .done(function(response){
                message = (response.message) ? response.message : '';
                if (response.status) {
                    toastr.options.onHidden = formCallBack(response.data);
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
            demo6();
            btnToolHandler();
            formSave();
        }
    };
}();

$(document).ready(function () {
    MenuIndex.init();
});