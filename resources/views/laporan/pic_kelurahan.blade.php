@extends('layouts.app')

@section('title', 'Metode Pendataan Kelurahan')

@section('content')
<div class="form-group row">
    <label class="col-3 col-form-label">Wilayah</label>
    <div class="col-3">
        <input type="text" disabled name="Wilayah" class="form-control" id="Wilayah" value="{{ $aksesWilayah->nama_kecamatan }}" />
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
<div class="table-responsive">
<table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Kelurahan</th>
                    <th>Metode Pendataan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $idx=>$row)
                <tr>
                    <td scope="row">{{ $idx+1 }}</td>
                    <td>{{$row->nama_kelurahan}}</td>
                    <td>{{$row->Smartphone ? 'Smartphone' : 'Manual' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</div>
@endsection

@section('script')
<!-- css dan js yang dibutuhkan letakkan di sini -->
@endsection
