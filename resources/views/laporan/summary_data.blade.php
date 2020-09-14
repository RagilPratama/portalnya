@extends('layouts.app')

@section('title', 'Monitoring Summary Data')

@section('content')
<form class="kt-form kt-form--fit kt-margin-b-20">
    <div class="row kt-margin-b-20">
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>Periode Pendataan:</label>
            <select class="form-control form-control" name="PeriodeSensus" id="PeriodeSensus"
                {{ empty($valperiode) ? '' : 'disabled' }}>
                <option value=""></option>
                @foreach ($periode as $item)
                <option value="{{ $item->Tahun }}" {{ $item->Tahun==$valperiode ? 'selected':'' }}>{{ $item->Tahun }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>Pengelompokan Wilayah:</label>
            <select class="form-control form-control" name="Wilayah" id="Wilayah">
                <option value=""></option>
                @foreach ($tingkatwilayah as $item)
                <option value="{{ $item->ID }}">{{ $item->TingkatWilayah }}</option>
                @endforeach
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
            <span class="kt-badge kt-badge--outline kt-badge--warning kt-badge--md" data-toggle="kt-popover" title=""
                data-content="Bila pra-tinjau cetakan tidak tampil di browser, harap periksa aplikasi pengunduh atau folder default Unduhan (Download)"
                data-original-title="">!</span>
        </div>
    </div>
</form>

<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>


<div class="row">
    <div class="col-sm-12">
        <!--<table id="tablemon"></table>-->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="tablemon"></table>
        <div id="pagermon"></div>
    </div>
</div>

@endsection

@section('script')
{!! bundle('datatable') !!}
<link href="{{ url('assets/plugins/jqGrid/themes/base/theme.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.custom.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.min.js') }}"></script>
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.custom.js') }}"></script>
<script src="{{ url('assets/scripts/laporan/summary_data.js') }}"></script>
@endsection
