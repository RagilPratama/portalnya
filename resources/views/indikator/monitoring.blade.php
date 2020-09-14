@extends('layouts.app')

@section('title', 'Monitoring Indikator Proses')

@section('content')
<form class="kt-form kt-form--fit kt-margin-b-20 kt-hidden">
    <div class="row kt-margin-b-20">
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>Periode Pendataan:</label>
            <select class="form-control form-control" name="PeriodeSensus" id="PeriodeSensus">
                <option value=""></option>
                @foreach ($periode as $item)
                <option value="{{ $item->Tahun }}">
                    {{ $item->Tahun }}</option>
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

<ul class="nav nav-tabs" role="tablist" id="tabIndikator">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#" data-target="#tabSarpras">Sarana dan Prasarana</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#" data-target="#tabPelatihan">Pelatihan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#" data-target="#tabKelengkapan">Kelengkapan</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="tabSarpras" role="tabpanel">

        <table class="table table-striped- table-bordered table-hover table-checkable" id="tablesarpras">
            <thead>
                <!--tr>
                <th rowspan="2">Provinsi</th>
                <th rowspan="2">Kabupaten</th>
                <th rowspan="2">Kecamatan</th>
                <th colspan="2">Juknis PK lengkap (Pedoman)</th>
                <th colspan="2">Juknis Manajer</th>
                <th colspan="2">Juknis Supervisor</th>
                <th colspan="2">Buku Saku Kader</th>
                <th colspan="2">Juknis Pengolahan</th>
                <th colspan="2">PK Kit</th>
                <th colspan="2">Formulir F/I/PK</th>
                <th colspan="2">Formulir Rekapitulasi</th>
            </tr-->
                <tr>
                    <th>Provinsi</th>
                    <th>Kabupaten</th>
                    <th>Kecamatan</th>
                    <th title="Pengadaan">Pengadaan</th>
                    <th title="Distribusi">Distribusi</th>
                    <th title="Pengadaan">Pengadaan</th>
                    <th title="Distribusi">Distribusi</th>
                    <th title="Pengadaan">Pengadaan</th>
                    <th title="Distribusi">Distribusi</th>
                    <th title="Pengadaan">Pengadaan</th>
                    <th title="Distribusi">Distribusi</th>
                    <th title="Pengadaan">Pengadaan</th>
                    <th title="Distribusi">Distribusi</th>
                    <th title="Pengadaan">Pengadaan</th>
                    <th title="Distribusi">Distribusi</th>
                    <th title="Pengadaan">Pengadaan</th>
                    <th title="Distribusi">Distribusi</th>
                    <th title="Pengadaan">Pengadaan</th>
                    <th title="Distribusi">Distribusi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="tab-pane" id="tabPelatihan" role="tabpanel">
        <table class="table table-striped- table-bordered table-hover table-checkable" id="tablepelatihan">
            <thead>
                <tr>
                    <th rowspan="2">Provinsi</th>
                    <th rowspan="2">Kabupaten</th>
                    <th rowspan="2">Kecamatan</th>
                    <th colspan="2">Pelatihan Tingkat Provinsi</th>
                    <th colspan="2">Pelatihan Tingkat Kabupaten/Kota</th>
                    <th colspan="2">Orientasi</th>
                </tr>
                <tr>
                    <th title="Status">Status</th>
                    <th title="Jumlah Peserta">Jml. Pst.</th>
                    <th title="Status">Status</th>
                    <th title="Jumlah Peserta">Jml. Pst.</th>
                    <th title="Status">Status</th>
                    <th title="Jumlah Peserta">Jml. Pst.</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="tab-pane" id="tabKelengkapan" role="tabpanel">
        <table class="table table-striped- table-bordered table-hover table-checkable" id="tablekelengkapan">
            <thead>
                <tr>
                    <th>Provinsi</th>
                    <th>Kabupaten</th>
                    <th>Kecamatan</th>
                    <th>SK Pengorganisasian Lapangan</th>
                    <th>SK Posko</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>



@endsection

@section('script')
{!! bundle('datatable') !!}
<script src="{{ url('assets/scripts/indikator/monitoring.js') }}"></script>
@endsection
