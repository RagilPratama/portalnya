@extends('layouts.pdf')

@section('title', 'Monitoring Summary Pendataan')
@section('pageformat', 'A4 landscape')

@section('content')
<div class="container">

    <table class="table table-borderless">
        <tr>
            <td width="120"><img src="{{ url('assets/media/logos/bkkbn-logo.png') }}" style="width:100%" /></td>
            <td style="vertical-align:middle">
                <span class="title1">MONITORING SUMMARY PENDATAAN</span>
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
                <th>Kelurahan</th>
                @if (in_array(request()->input('groupby'), [5,6]))
                <th>RW</th>
                @endif
                @if (in_array(request()->input('groupby'), [6]))
                <th>RT</th>
                @endif
                <th>Anomali</th>
                <th>Anulir</th>
                <th>NotValid</th>
                <th>Received</th>
                <th>Valid</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            @php
            $qty = str_replace('\'', '"', $row->qty);
            $qty = json_decode($qty, true);
            @endphp
            <tr>
                <td>{{ $row->nama_kelurahan }}</td>
                @if (in_array(request()->input('groupby'), [5,6]))
                <td>{{ $row->nama_rw }}</td>
                @endif
                @if (in_array(request()->input('groupby'), [6]))
                <td>{{ $row->nama_rt }}</td>
                @endif
                <td>{{ $qty['3'] }}</td>
                <td>{{ $qty['4'] }}</td>
                <td>{{ $qty['2'] }}</td>
                <td>{{ $qty['5'] }}</td>
                <td>{{ $qty['1'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
