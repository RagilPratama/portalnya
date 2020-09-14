@extends('layouts.app')

@section('title', 'Monitoring Approval Kecamatan')

@section('content')
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="PeriodeSensus">Periode Pendataan</label>
    <div class="col-sm-4">
        <select class="form-control form-control" name="PeriodeSensus" id="PeriodeSensus" {{ $periode->count() > 1 ? '' : 'disabled' }}>
            <option value=""></option>
            @foreach ($periode as $item)
                <option value="{{ $item->Tahun }}" {{ $item->Tahun==$periode->first()->Tahun ? 'selected':'' }}>{{ $item->Tahun }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="Status">Action</label>
    <div class="col-sm-4">
        <select class="form-control form-control" name="Status" id="Status">
            <option value=""></option>
            <option value="1" selected>Status Approve</option>
            <option value="0">Status Not Approve</option>
        </select>
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-2">
        <button type="button" class="btn btn-primary" id="btnShow">Tampilkan</button>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table id="tablegrid"></table>
        <div id="pagergrid"></div>
    </div>
</div>


@endsection

@section('script')
<link href="{{ url('assets/plugins/jqGrid/themes/base/theme.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.custom.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.min.js') }}"></script>
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.custom.js') }}"></script>
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.custom.js') }}"></script>
<script src="{{ url('assets/scripts/approval/kecamatan.js') }}"></script>
@endsection