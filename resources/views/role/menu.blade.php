@extends('layouts.app')

@section('title', 'Pengaturan Menu')

@section('content')
<div class="row kt-margin-bottom-20">
    <div class="col-sm-4">
        <select class="form-control form-control" name="RoleID" id="RoleID" >
            <option value=""></option>
            @foreach ($roles as $item)
                <option value="{{ $item->ID }}" >{{ $item->RoleName }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-6">
        <button type="button" class="btn btn-primary" id="btnUpdate">Simpan</button>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div id="menutree">
        </div>
    </div>
</div>
@endsection

@section('script')

<link href="{{ url('assets/plugins/custom/jstree/jstree.bundle.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ url('assets/plugins/custom/jstree/jstree.bundle.js') }}" type="text/javascript"></script>
<script src="{{ url('assets/scripts/role/role.menu.js') }}" type="text/javascript"></script>

@endsection
