@extends('layouts.pdf')

@section('title', 'Rekapitulasi Pendataan Tingkat Desa/Kelurahan')
@section('pageformat', 'A4')

@section('content')

@foreach ($aggrows as $idx=>$row)
<section class="container">

    <table class="table table-borderless">
        <tr>
            <td width="120"><img src="{{ url('assets/media/logos/bkkbn-logo.png') }}" style="width:100%" /></td>
            <td style="vertical-align:middle">
                <span class="title1">REKAPITULASI HASIL PENDATAAN KELUARGA TAHUN 2020</span>
                <span class="title2">TINGKAT DESA/KELURAHAN</span>
            </td>
        </tr>
    </table>

    <table class="table table-borderless">
        <tr>
            <td width="20%">Desa/Kelurahan</td>
            <td>: {{ $row[0]->nama_kelurahan }}</td>
        </tr>
        <tr>
            <td>Kecamatan</td>
            <td>: {{ $row[0]->nama_kecamatan }}</td>
        </tr>
        <tr>
            <td>Kabupaten/Kota</td>
            <td>: {{ $row[0]->nama_kabupaten }}</td>
        </tr>
        <tr>
            <td>Provinsi</td>
            <td>: {{ $row[0]->nama_provinsi }}</td>
        </tr>
    </table>
    
    <table class="table table-bordered">
        <tr>
            <td rowspan="2" colspan="1">NO</td>
            <td rowspan="2" colspan="1">RW</td>
            <td rowspan="1" colspan="2">JUMLAH KEPALA KELUARGA</td>
            <td rowspan="2" colspan="1">JUMLAH PUS PESERTA KB</td>
            <td rowspan="2" colspan="1">JUMLAH PUS BUKAN PESERTA KB</td>
            <td rowspan="2" colspan="1">JUMLAH PUS HAMIL</td>
        </tr>
        <tr>
            <td>YANG ADA</td>
            <td>YANG DIDATA</td>
        </tr>
        @php
        $t_Target_KK = $t_jml_pus = $t_jml_pus_kb =  $t_jml_pus_nonkb = $t_jml_pus_hamil = 0;
        @endphp
        @foreach ($row as $idx=>$item)

        <tr>
            <td>{{ $idx+1 }}</td>
            <td>{{ $item->nama_rw }}</td>
            <td>{{ $item->Target_KK }}</td>
            <td>{{ $item->jml_pus }}</td>
            <td>{{ $item->jml_pus_kb }}</td>
            <td>{{ $item->jml_pus_nonkb }}</td>
            <td>{{ $item->jml_pus_hamil }}</td>
        </tr>
        
        @php
            $t_Target_KK += $item->Target_KK;
            $t_jml_pus += $item->jml_pus;
            $t_jml_pus_kb += $item->jml_pus_kb;
            $t_jml_pus_nonkb += $item->jml_pus_nonkb;
            $t_jml_pus_hamil += $item->jml_pus_hamil;
        @endphp
        @endforeach 
        
        <tr>
            <td rowspan="1" colspan="2">JUMLAH</td>
            <td rowspan="1" colspan="1">{{ $t_Target_KK }}</td>
            <td rowspan="1" colspan="1">{{ $t_jml_pus }}</td>
            <td rowspan="1" colspan="1">{{ $t_jml_pus_kb }}</td>
            <td rowspan="1" colspan="1">{{ $t_jml_pus_nonkb }}</td>
            <td rowspan="1" colspan="1">{{ $t_jml_pus_hamil }}</td>
        </tr>
    </table>

    <table class="table table-borderless">
        <tr>
            <td width="36%">
                <b>SUDAH DIPERIKSA DAN DITERIMA<br>DENGAN BAIK OLEH :</b>
                <br>
                <b>Manajer Kecamatan, </b>
                <br>
                <br>
                <br>
                <br>
                <br>
                ( ..................................... )
            </td>
            <td width="33%">
                <br>
                <b>MENGETAHUI/MENYETUJUI :</b>
                <br>
                <b>Kepala Desa/Lurah, </b>
                <br>
                <br>
                <br>
                <br>
                <br>
                ( ..................................... )
            </td>
            <td>
            ................, ........................... 2020
                <br>
                <b>DIBUAT OLEH:</b>
                <br>
                Supervisor,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <br>
                <br>
                <br>
                <br>
                <br>
                ( ..................................... )
            </td>
        </tr>
    </table>
</section>
@endforeach

@endsection
