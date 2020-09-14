@extends('layouts.app')

@section('title', 'Penetapan Target KK Wilayah')

@section('content')
<div class="form-group row">
    <label class="col-2 col-form-label">Kecamatan</label>
    <div class="col-2">
        <input type="text" disabled name="Wilayah" class="form-control" id="Wilayah" value="{{ currentUser('AksesWilayah')[0]->nama_kecamatan }}" />
    </div>
    <label class="col-2 col-form-label">Maks. Total Target Kecamatan</label>
    <div class="col-2">
        <input type="text" name="maxTargetKecamatan" class="form-control" id="maxTargetKecamatan" value="{{ $maxTargetKecamatan }}" />
    </div>
    <label class="col-2 col-form-label">Jumlah targrt KK yang sudah di isi</label>
    <div class="col-2">
        <input type="text" disabled name="MaxTarget" class="form-control" id="MaxTarget" value="{{ $parameter['MaxTarget']?? 0  }}" />
    </div>
</div>

<div class="form-group row {{ $approved ? '' : 'kt-hidden' }}" id="status">
    <label class="col-3 col-form-label">Status</label>
    <div class="col-3">
        <input type="text" disabled name="status" class="form-control" id="status" value="APPROVED" />
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table id="treewilayah"></table>
        <div id="pagerwilayah"></div>
    </div>
</div>
<div class="row kt-mt-20">
    <div class="col-sm-12">
        <button type="button" class="btn btn-primary {{ (checkAction('approve') && !$approved) ? '' : 'kt-hidden' }}" id="btnApprove">Approve Target</button>
        
        <button type="button" class="btn btn-primary {{ (checkAction('approve') && $approved) ? '' : 'kt-hidden' }}" id="btnDisapprove">Buka Approval</button>
        
        <button type="button" class="btn btn-primary {{ (checkAction('update')  && !$approved) ? '' : 'kt-hidden' }}" id="btnUpdateAkses">Update Target</button>
    </div>
</div>


@endsection

@section('script')
<link href="{{ url('assets/plugins/jqGrid/themes/base/theme.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.custom.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.min.js') }}"></script>
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.custom.js') }}"></script>
<script src="{{ url('assets/scripts/wilayah/wilayah.targetkk.js') }}"></script>
@endsection