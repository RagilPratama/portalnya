@extends('layouts.app')

@section('title', 'Monitoring Pasangan Usia Subur (PUS)')

@section('content')
<form class="kt-form kt-form--fit kt-margin-b-20">
    <div class="row kt-margin-b-20">
        <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
            <label>Periode Pendataan:</label>
            <select class="form-control form-control" name="periode_sensus" id="periode_sensus">
                <option value=""></option>
                @foreach ($periode as $item)
                <option value="{{ $item->Tahun }}" >
                    {{ $item->Tahun }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
            <label>Kelompok Umur:</label>
            <select class="form-control form-control" name="kelompok_umur" id="kelompok_umur">
                <option value=""></option>
                <option value="1">10-14 Tahun</option>
                <option value="2">15-49 Tahun</option>
                <option value="2">ALL</option>
            </select>
        </div>
        <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
            <label>Status Hamil:</label>
            <select class="form-control form-control" name="status_hamil" id="status_hamil">
                <option value=""></option>
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
                <option value="3">ALL</option>
            </select>
        </div>
        <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
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
        <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
            <label>RW:</label>
            <select class="form-control" name="RW" id="RW">
                <option value=""></option>
            </select>
        </div>
        <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
            <label>RT:</label>
            <select class="form-control" name="RT" id="RT">
                <option value=""></option>
            </select>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <button type="button" class="btn btn-primary btn-brand--icon" id="btnShow">
                <span>
                    <i class="la la-search"></i>
                    <span>Tampilkan</span>
                </span>
            </button>
            &nbsp;&nbsp;
            <button type="button" class="btn btn-primary btn-brand--icon" id="btnPrint">
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



@endsection

@section('script')
{!! bundle('datatable') !!}
<script src="{{ url('assets/scripts/laporan/pus.js') }}"></script>
@endsection
