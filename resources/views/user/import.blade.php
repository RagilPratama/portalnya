@extends('layouts.app')

@section('title', 'Import User Kecamatan')

@section('content')
<div class="row">
    <div class="col-md-8 alert alert-outline">
        <div> Siapkan data user yang akan dibuat dalam file .csv. Contoh format .csv bisa diunduh <a href="{{ url('/files/UserTemplate.csv') }}">di sini</a>. Isi mulai baris kedua.</div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
    
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="RoleID">Role</label>
            <div class="col-sm-4">
                <select class="form-control form-control" name="RoleID" id="RoleID" >
                    @foreach ($roles as $item)
                        <option value="{{ $item->ID }}">{{ $item->RoleName }}</option>
                    @endforeach
                </select>
            </div>
        </div>
            
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="RoleID">File (.csv)</label>
            <div class="col-sm-4">
                <input type="file" class="custom-file-input" id="usersfile">
                <label class="custom-file-label" for="usersfile">Choose file</label>
            </div>
        </div>
        
        <div class="form-group row">
            <div class="col-md-6">
                <button type="button" class="btn btn-primary kt-hidden" id="btnUpload">Unggah File</button>
                <button type="button" class="btn btn-primary" id="btnProses">Proses File</button>
                <button type="button" class="btn btn-primary" id="btnCancel">Batal</button>
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12">
        <table id="table"></table>
        <div id="pager"></div>
    </div>
</div>

@endsection

@section('script')
<link href="{{ url('assets/plugins/jqGrid/themes/base/theme.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.custom.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.min.js') }}"></script>
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.custom.js') }}"></script>
<script src="{{ url('assets/scripts/user/user.import.js') }}"></script>
@endsection
