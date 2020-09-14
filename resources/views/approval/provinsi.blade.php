@extends('layouts.app')

@section('title', 'Monitoring Approval Provinsi')

@section('content')
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="PeriodeSensus">Periode Pendataan</label>
    <div class="col-sm-4">
        <select class="form-control form-control" name="PeriodeSensus" id="PeriodeSensus" {{ $periode->count() > 1 ? '' : 'disabled' }}>
            <option value=""></option>
            @foreach ($periode as $item)
                <option value="{{ $item->Tahun }}" {{ $item->Tahun==$periode->first()->Tahun ? 'selected':'' }}>{{ $item->Tahun }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label" for="Status">Action</label>
    <div class="col-sm-4">
        <select class="form-control form-control" name="Status" id="Status">
            <option value=""></option>
            <option value="1" selected>Status Open</option>
            <option value="0">Status Close</option>
        </select>
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-2">
        <button type="button" class="btn btn-primary" id="btnShow">Tampilkan</button>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table id="tablegrid"></table>
        <div id="pagergrid"></div>
    </div>
</div>

<!--begin::modalDetail-->
<div class="modal fade" id="modalDetail2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetail">Buka Periode Provinsi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
            <form>                    
                <input type="hidden" class="form-control datepicker" readonly="" placeholder="Select date" id="idprovinsi" name="idprovinsi">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2 col-sm-12">Periode </label>
                    <div class="col-lg-9 col-md-9 col-sm-12">
                        <div class="input-group date">
                            <input type="text" class="form-control datepicker" readonly="" placeholder="Select date" id="opendate" name="opendate">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                    <i class="la la-calendar-check-o"></i>
                                    </span>
                                </div>

                            <label class="col-form-label col-lg-1 col-sm-12">s/d</label>

                            <input type="text" class="form-control datepicker" readonly="" placeholder="Select date" id="closedate" name="closedate">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                    <i class="la la-calendar-check-o"></i>
                                    </span>
                                </div>    
                        </div>
                    </div>
                </div>            
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary btnSave" id="btnSave" name="btnSave">Simpan</button>
            </div>
        </div>
    </div>
</div>
<!--end::modalDetail-->


@endsection

@section('script')
<link href="{{ url('assets/plugins/jqGrid/themes/base/theme.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.custom.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.min.js') }}"></script>
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.custom.js') }}"></script>
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.custom.js') }}"></script>
<script src="{{ url('assets/scripts/approval/provinsi.js') }}"></script>
<script src="{{ url('assets/js/bootstrap-datepicker.js') }}"></script>
@endsection