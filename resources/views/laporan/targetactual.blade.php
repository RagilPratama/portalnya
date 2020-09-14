@extends('layouts.app')

@section('title', 'Monitoring Target vs Aktual')

@section('content')
<form class="kt-form kt-form--fit kt-margin-b-20">
    <div class="row kt-margin-b-20">
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>Periode Pendataan:</label>
            <select class="form-control" name="PeriodeSensus" id="PeriodeSensus"
                {{ empty($valperiode) ? '' : 'disabled' }}>
                <option value=""></option>
                @foreach ($periode as $item)
                <option value="{{ $item->Tahun }}" {{ $item->Tahun==$valperiode ? 'selected':'' }}>
                    {{ $item->Tahun }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>Wilayah:</label>
            <select class="form-control" name="Kelurahan" id="Kelurahan"
                {{ empty($valwilayah) ? '' : 'disabled' }}>
                <option value=""></option>
                @foreach ($wilayah as $item)
                <option value="{{ $item->id }}" {{ $item->id==$valwilayah ? 'selected':'' }}>{{ $item->text }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>RW:</label>
            <select class="form-control" name="RW" id="RW">
                <option value=""></option>
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>RT:</label>
            <select class="form-control" name="RT" id="RT">
                <option value=""></option>
            </select>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-primary btn-brand--icon" id="btnShow">
                <span>
                    <i class="la la-search"></i>
                    <span>Tampilkan</span>
                </span>
            </button>
            &nbsp;&nbsp;
            <button class="btn btn-primary btn-brand--icon" id="btnPrint">
                <span>
                    <i class="la la-print"></i>
                    <span>Cetak</span>
                </span>
            </button>
            &nbsp;&nbsp;
            <span class="kt-badge kt-badge--outline kt-badge--warning kt-badge--md" data-toggle="kt-popover" title="" data-content="Bila pra-tinjau cetakan tidak tampil di browser, harap periksa aplikasi pengunduh atau folder default Unduhan (Download)" data-original-title="">!</span> 
        </div>
    </div>
</form>

<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>


<table class="table table-striped- table-bordered table-hover table-checkable" id="tablegrid"></table>


<!--begin::modalDetail-->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i></i>Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_pendata" />
                <table class="table table-striped- table-bordered table-hover table-checkable table-sm" id="tablegridview"></table>

                <table id="tableview"></table>
                <div id="pagerview"></div>
            </div>
        </div>
    </div>
</div>
<!--end::modalDetail-->

@endsection

@section('script')
{!! bundle('datatable') !!}
<link href="{{ url('assets/plugins/jqGrid/themes/base/theme.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.custom.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.min.js') }}"></script>
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.custom.js') }}"></script>
<script src="{{ url('assets/scripts/laporan/target_actual.js') }}"></script>
@endsection
