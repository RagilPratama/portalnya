@extends('layouts.pdf')

@section('title', 'Monitoring Target vs Aktual')
@section('pageformat', 'A4 landscape')

@section('content')
<div class="row">
    <div class="col-xs-12"><h2>Monitoring Anomali Pendata</div>
</div>
<div class="row mb">
    <div class="col-xs-3">Periode Pendataan</div>
    <div class="col-xs-8">: 2020</div>
</div>
<div class="row mb">
    <div class="col-xs-3">Wilayah</div>
    <div class="col-xs-8">: {!! $nama_wilayah !!}</div>
</div>

<div class="row">
    <div class="col-md-12">
@php
    $iduplicates = array();
    if(!collect($rows)->isEmpty()) {
        $indikator1 = collect($rows);
    }
@endphp

@if ($indikators == 5)
<table class="table table-bordered">
    <thead>
      <tr>
          <td rowspan="2">Provinsi</td>
          <th rowspan="2">Kabupaten</th>
          <th rowspan="2">KecamatanK</th>
          <th rowspan="2">Kelurahan</th>
          <th rowspan="2">RW</th>
          <th rowspan="2">RT</th>
          <th colspan="3" align="center">Jawaban</th>
      </tr>
      <tr>
          <th align="center">Ya</th>
          <th align="center">Tidak</th>
          <th align="center">Tidak Jawab</th>
      </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
        <tr>
          <td>{{ $row->nama_provinsi }}</td>
          <td>{{ $row->nama_kabupaten }}</td>
          <td>{{ $row->nama_kecamatan }}</td>
          <td>{{ $row->nama_kelurahan }}</td>
          <td>{{ $row->nama_rw }}</td>
          <td>{{ $row->nama_rt }}</td>
          @if ($row->Code == 1)
            <td align="center">{{ $row->qty }}</td>
            <td align="center">0</td>
            <td align="center">0</td>
          @elseif ($row->Code == 2)
            <td align="center">0</td>
            <td align="center">{{ $row->qty }}</td>
            <td align="center">0</td>
          @else
            <td align="center">0</td>
            <td align="center">0</td>
            <td align="center">{{ $row->qty }}</td>
          @endif
        </tr>
        @endforeach
    </tbody>
</table>
 @else
      @forelse($rows as $ros)
      @if(!in_array($ros->Code, $iduplicates))
      @php $data = $indikator1->where('Code',$ros->Code); @endphp
          <h3> {{$ros->indikator}} </h3>
          <table class="table table-bordered">
            <thead>
              <tr>
              <td rowspan="2" valign="baseline">Provinsi</td>
              <td rowspan="2">Kabupaten</td>
              <td rowspan="2">Kecamatan</td>
              <td rowspan="2">Kelurahan</td>
              <td rowspan="2">RW</td>
              <td rowspan="2">RT</td>
                  @php
                    $i = 0;
                    $duplicates = array();
                  @endphp
                  @foreach($data as $rowss1)
                  @if(!in_array($rowss1->Code, $duplicates))
                    @if($rowss1->Code != $i)
                        <td  align="center" colspan="11">{{ $rowss1->indikator }}</td>
                    @endif
                      @php $i = $rowss1->Code; @endphp

                      @php
                            $duplicates[] = $rowss1->Code;
                      @endphp
                  @endif

                  @endforeach
              </tr>
              <tr>
                @php
                  $duplicatess = array();
                @endphp
                @foreach($data as $rowssx)
                    @if(!in_array($rowssx->minggus, $duplicatess))
                        <td align="center">{{ $rowssx->indikator2 }}</td>
                        @php
                              $duplicatess[] = $rowssx->minggus;
                        @endphp
                    @endif
                @endforeach
              </tr>

                </thead>
                <tbody>
                    @php
                      $duplicates = array();
                      $x = 0;
                    @endphp
                    @foreach($data as $row)
                        @if ($row->id_rt != $x)
                           <tr>
                           @php $x = $row->id_rt; @endphp
                             <td>{{ $row->nama_provinsi }}</td>
                             <td>{{ $row->nama_kabupaten }}</td>
                             <td>{{ $row->nama_kecamatan }}</td>
                             <td>{{ $row->nama_kelurahan }}</td>
                             <td>{{ $row->nama_rw }}</td>
                             <td>{{ $row->nama_rt }}</td>
                             @if ($row->qty > 0)
                                 <td bgcolor="#bdc3c7">{{ $row->qty }}</td>
                             @else
                                 <td>{{ $row->qty }}</td>
                             @endif
                         @else
                             @if ($row->qty > 0)
                                 <td bgcolor="#bdc3c7">{{ $row->qty }}</td>
                             @else
                                 <td>{{ $row->qty }}</td>
                             @endif
                         @endif
                     @endforeach
            </tbody>
          </table>
      @endif
      @php
        $iduplicates[] = $ros->Code;
      @endphp
      @empty
          <p>No Data Found</p>
      @endforelse
 @endif



</div>
</div>
@endsection
