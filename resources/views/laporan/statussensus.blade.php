@extends('layouts.app')

@section('title', 'Monitoring Status Pendataan')

@section('content')
<form class="kt-form kt-form--fit kt-margin-b-20">
    <div class="row kt-margin-b-20">
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>Periode Pendataan:</label>
            <select class="form-control form-control" name="PeriodeSensus" id="PeriodeSensus" {{ empty($valperiode) ? '' : 'disabled' }}>
                <option value=""></option>
                @foreach ($periode as $item)
                    <option value="{{ $item->Tahun }}" {{ $item->Tahun==$valperiode ? 'selected':'' }}>{{ $item->Tahun }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>Status Pendataan:</label>
            <select class="form-control form-control" name="StatusSensus" id="StatusSensus" >
                <!-- @foreach ($statussensus as $item)
                    <option value="{{ $item->Code }}">{{ $item->Value }}</option>
                @endforeach -->
                    <option value="1">Valid</option>
                    <option value="2">Not Valid</option>
                    <option value="3">All</option>
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>Berdasarkan:</label>
                    <select class="form-control form-control" name="JenisData" id="JenisData" >
                        <option value="1" selected>Wilayah</option>
                        <option value="2">Pendata</option>
                    </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>Wilayah:</label>
                    <select class="form-control form-control" name="Kelurahan" id="Kelurahan" {{ empty($valwilayah) ? '' : 'disabled' }}>
                        <option value=""></option>
                        @foreach ($wilayah as $item)
                            <option value="{{ $item->id }}" {{ $item->id==$valwilayah ? 'selected':'' }}>{{ $item->text }}</option>
                        @endforeach
                    </select>
        </div>
    </div>

    <div class="row kt-margin-b-20">
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>RW:</label>
                    <select class="form-control form-control" name="RW" id="RW" >
                        <option value=""></option>
                    </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>RT:</label>
                    <select class="form-control form-control" name="RT" id="RT" >
                        <option value=""></option>
                    </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile div-pendata">
            <label>Pendata:</label>
                    <select class="form-control form-control" name="Pendata" id="Pendata" >
                        <option value="" selected>--- Pilih Semua Pendata ---</option>
                        @foreach ($userPendata as $item)
                            <option value="{{ $item->UserName }}">{{ $item->UserName.' - '.$item->NamaLengkap }}</option>
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
            <span class="kt-badge kt-badge--outline kt-badge--warning kt-badge--md" data-toggle="kt-popover" title="" data-content="Bila pra-tinjau cetakan tidak tampil di browser, harap periksa aplikasi pengunduh atau folder default Unduhan (Download)" data-original-title="">!</span> 
        </div>
    </div>
</form>

<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>


<table class="table table-striped- table-bordered table-hover table-checkable" id="tablegrid"></table>


@include('laporan.form_view')

@endsection

@section('script')
{!! bundle('datatable') !!}
<script src="{{ url('assets/scripts/laporan/filter_status.js') }}"></script>
<script src="{{ url('assets/scripts/laporan/status_sensus.js') }}"></script>
<script src="{{ url('assets/scripts/laporan/form_view.js') }}"></script>
@endsection
