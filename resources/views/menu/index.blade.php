@extends('layouts.app')

@section('title', 'Pengaturan Menu')

@section('content')
<div class="row">
    <div class="col-sm-6">
        <div id="menutree">
        </div>
    </div>
    
    <div class="col-md-6" id="divtool">
        <form id="form" class="kt-form" action="menu/store" method="POST">
            
        <div class="form-group margin-bottom-20">
            <button type="button" class="btn btn-primary" id="btnCreate">Create Menu</button>
            <button type="button" class="btn btn-warning" id="btnEdit">Edit Menu</button>
            <button type="button" class="btn btn-danger" id="btnDelete">Delete Menu</button>
        </div>
        
            <input type="text" name="id" id="id" />
            <input type="text" name="parent_id" id="parent_id" />
            <div class="form-group">
                <label class="control-label" for="parent_name">Parent Menu</label>
                <input type="text" id="parent_name" name="parent_name" class="form-control" readonly />
            </div>
            <div class="form-group">
                <label class="control-label" for="name">Menu Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter Menu Name." />
            </div>
            <div class="form-group">
                <label class="control-label" for="route">Menu URL</label>
                <input type="text" id="route" name="route" class="form-control" placeholder="Enter Menu URL. eg: /home" />
            </div>
            <div class="form-group">
                <label class="control-label" for="sequence">Sequence No.</label>
                    <input type="number" min="1" value="1" id="sequence" name="sequence" class="form-control" placeholder="Enter sequence number" />
            </div>
            <div class="form-group">
                <label class="control-label">Aktif</label>
                <div class="mt-checkbox-inline">
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" id="is_active" name="is_active" value="1">
                        <span></span>
                    </label>
                </div>
            </div>
                                                
            <h4>Permission</h4>
            <hr />
            <div class="form-group template hidden">
                <label class="control-label" for="route">Resource</label>
                <div class="col-md-9">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter Permission Resource. eg: users, users-create" />
                            <span class="input-group-btn">
                              <button type="button" class="btn btn-danger btn-flat delRes"><i class="fa fa-trash"></i></button>
                            </span>
                      </div>
                </div>
            </div>
            <button class="btn btn-flat btn-default" type="button" id="btnAddRes"><i class="fa fa-plus"></i> Permission</button>
            <hr />
            <button class="btnTool btn btn-primary" id="btnSave" type="button">Save Menu</button>
        </form>
    </div>

</div>
@endsection

@section('script')

<link href="{{ url('assets/plugins/custom/jstree/jstree.bundle.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ url('assets/plugins/custom/jstree/jstree.bundle.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/scripts/menu/menu.index.js') }}" type="text/javascript"></script>

@endsection
