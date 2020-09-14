@extends('layouts.app')

@section('title', 'Indikator Proses')

@section('content')
<form id="formIndikator">

<div class="row">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="PeriodeSensus">Periode Pendataan</label>
            <div class="col-sm-4">
                <select class="form-control form-control" name="PeriodeSensus" id="PeriodeSensus">
                    <option value=""></option>
                    @foreach ($periode as $item)
                    <option value="{{ $item->Tahun }}">{{ $item->Tahun }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>


<div class="form-group row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Sarana &amp; Prasarana</th>
                        <th class="text-center">Penerimaan</th>
                        <th class="text-center">Distribusi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($indSarpras as $idx=>$row)
                    <tr>
                        <th scope="row">-</th>
                        <td>{{ $row->Value }}</td>
                        @if($row->Value == 'Juknis Manajer' or $row->Value == 'Juknis Pengolahan')
                            <td class="text-center">
                                <label class="kt-checkbox kt-checkbox--brand">
                                    <input type="checkbox" name="IndSarpras[{{ $row->Code }}][pengadaan]">
                                    <span></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <label class="kt-checkbox kt-checkbox--brand">
                                    <input type="checkbox" disabled name="IndSarpras[{{ $row->Code }}][distribusi]">
                                    <span></span>
                                </label>
                            </td>
                        @else
                        <td class="text-center">
                            <label class="kt-checkbox kt-checkbox--brand">
                                <input type="checkbox" name="IndSarpras[{{ $row->Code }}][pengadaan]">
                                <span></span>
                            </label>
                        </td>
                        <td class="text-center">
                            <label class="kt-checkbox kt-checkbox--brand">
                                <input type="checkbox" name="IndSarpras[{{ $row->Code }}][distribusi]">
                                <span></span>
                            </label>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="form-group row">
    <div class="col-sm-12">

        <div class="table-responsive">

            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Pelatihan</th>
                        <th class="text-center">Status (Sudah/Belum)</th>
                        <th class="text-center">Jml Peserta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($indPelatihan as $idx=>$row)
                    <tr>
                        <th scope="row">-</th>
                        <!-- {{ $idx+1 }} -->
                        <td>{{ $row->Value }}</td>
                        @if($row->Value == 'Pelatihan tingkat kabupaten/kota')
                            <td class="text-center">
                                <label class="kt-checkbox kt-checkbox--brand">
                                    <input type="checkbox" disabled name="IndPelatihan[{{ $row->Code }}][status_proses]">
                                    <span></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <input type="number" disabled class="form-control" name="IndPelatihan[{{ $row->Code }}][jml_peserta]">
                            </td>
                        @else
                            <td class="text-center">
                                <label class="kt-checkbox kt-checkbox--brand">
                                    <input type="checkbox" name="IndPelatihan[{{ $row->Code }}][status_proses]">
                                    <span></span>
                                </label>
                            </td>
                            <td class="text-center">
                                <input type="number" class="form-control" name="IndPelatihan[{{ $row->Code }}][jml_peserta]">
                            </td>
                        @endif
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="form-group row">
    <div class="col-sm-12">

        <div class="table-responsive">

            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Kelengkapan</th>
                        <th class="text-center">Status (Sudah/Belum)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($indKelengkapan as $idx=>$row)
                    <tr>
                        <th scope="row">-</th>
                        <td>{{ $row->Value }}</td>
                        <td class="text-center">
                            <label class="kt-checkbox kt-checkbox--brand">
                                <input type="checkbox" name="IndKelengkapan[{{ $row->Code }}][status_proses]">
                                <span></span>
                            </label>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="form-group row">
    <div class="col-sm-12">
        <button type="button" class="btn btn-primary" id="btnUpdate">Update Indikator Proses</button>
    </div>
</div>

</form>
@endsection

@section('script')
<script src="{{ url('assets/scripts/indikator/index.js') }}"></script>
@endsection
