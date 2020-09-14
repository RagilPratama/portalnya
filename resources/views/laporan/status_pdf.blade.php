@extends('layouts.pdf')

@section('title', 'Monitoring Status Pendataan')
@section('pageformat', 'A4 landscape')

@section('content')
<div class="container">

    <table class="table table-borderless">
        <tr>
            <td width="120"><img src="{{ url('assets/media/logos/bkkbn-logo.png') }}" style="width:100%" /></td>
            <td style="vertical-align:middle">
                <span class="title1">MONITORING STATUS PENDATAAN</span>
            </td>
        </tr>
    </table>

    <table class="table table-borderless">
        <tr>
            <td width="20%">Periode Pendataan</td>
            <td>: {{ $periode ?? '' }}</td>
        </tr>
        @if(!empty($wilrow->nama_rt))
        <tr>
            <td width="20%">RT</td>
            <td>: {{ $wilrow->nama_rt ?? '' }}</td>
        </tr>
        @endif

        @if(!empty($wilrow->nama_rw))
        <tr>
            <td width="20%">RW</td>
            <td>: {{ $wilrow->nama_rw ?? '' }}</td>
        </tr>
        @endif

        @if(!empty($wilrow->nama_kelurahan))
        <tr>
            <td width="20%">Kelurahan</td>
            <td>: {{ $wilrow->nama_kelurahan ?? '' }}</td>
        </tr>
        @endif
        <tr>
            <td>Kecamatan</td>
            <td>: {{ $wilrow->nama_kecamatan ?? '' }}</td>
        </tr>
        <tr>
            <td>Kabupaten/Kota</td>
            <td>: {{ $wilrow->nama_kabupaten ?? '' }}</td>
        </tr>
        <tr>
            <td>Provinsi</td>
            <td>: {{ $wilrow->nama_provinsi ?? '' }}</td>
        </tr>
    </table>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No KK</th>
                <th>Alamat</th>
                <th>Kelurahan</th>
                <th>RW</th>
                <th>RT</th>
                <th>Pendata</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                <td>{{ $row->no_kk }}</td>
                <td>{{ $row->alamat1 }} <br> No Rumah: {{ $row->no_urutrmh }}</td>
                <td>{{ $row->nama_kelurahan }}</td>
                <td>{{ $row->nama_rw }}</td>
                <td>{{ $row->nama_rt }}</td>
                <td>{{ $row->NamaLengkap }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
