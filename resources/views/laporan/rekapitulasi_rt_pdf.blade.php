@extends('layouts.pdf')

@section('title', 'Rekapitulasi Pendataan Tingkat RT')
@section('pageformat', 'A4')

@section('content')

@foreach ($rows as $idx=>$row)
<section class="container">

    <table class="table table-borderless">
        <tr>
            <td width="120"><img src="{{ url('assets/media/logos/bkkbn-logo.png') }}" style="width:100%" /></td>
            <td style="vertical-align:middle">
                <span class="title1">REKAPITULASI HASIL PENDATAAN KELUARGA TAHUN 2020</span>
                <span class="title2">TINGKAT RT {{ $row->nama_rt }}</span>
            </td>
        </tr>
    </table>

    <table class="table table-borderless">
        <tr>
            <td width="20%">Dusun/RW</td>
            <td>: {{ $row ->nama_rw }}</td>
        </tr>
        <tr>
            <td>Desa/Kelurahan</td>
            <td>: {{ $row ->nama_kelurahan }}</td>
        </tr>
        <tr>
            <td>Kecamatan</td>
            <td>: {{ $row ->nama_kecamatan }}</td>
        </tr>
        <tr>
            <td>Kabupaten/Kota</td>
            <td>: {{ $row ->nama_kabupaten }}</td>
        </tr>
        <tr>
            <td>Provinsi</td>
            <td>: {{ $row ->nama_provinsi }}</td>
        </tr>
    </table>

    <hr class="solid" />
    <table class="table table-borderless">
        <tr>
            <td>1. Jumlah Kepala Keluarga Yang Ada</td>
            <td width="5%" class="text-right">{{ $row->Target_KK }}</td>
            <td>4. Jumlah PUS Bukan Peserta KB</td>
            <td width="5%" class="text-right">{{ $row->jml_pus_nonkb }}</td>
        </tr>
        <tr>
            <td>2. Jumlah Kepala Keluarga Yang Didata</td>
            <td class="text-right">{{ $row->jml_pus }}</td>
            <td>5. Jumlah PUS Hamil</td>
            <td class="text-right">{{ $row->jml_pus_hamil }}</td>
        </tr>

        <tr>
            <td>3. Jumlah PUS Peserta KB</td>
            <td class="text-right">{{ $row->jml_pus_kb }}</td>
        </tr>
    </table>
    <hr class="solid" />

    <table class="table table-borderless">
        <tr>
            <td colspan="2" class="text-center">
                ...................., .............................................. 2020
                <br>
                <br>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>MENGETAHUI/MENYETUJUI :</b>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ketua RT,
                <br>
                <br>
                <br>
                <br>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( ..................................................... )
                <br>
                <br>
            </td>
            <td>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>DIBUAT OLEH:</b>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kader Pendata,
                <br>
                <br>
                <br>
                <br>
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( ..................................................... )
                <br>
                <br>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center">
                <b>SUDAH DIPERIKSA DAN DITERIMA DENGAN BAIK OLEH :</b>
                <br>
                Supervisor
                <br>
                <br>
                <br>
                <br>
                <br>
                ( ..................................................... )
            </td>
        </tr>
    </table>
</section>
@endforeach

@endsection
