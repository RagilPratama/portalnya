@extends('layouts.app')

@section('title', 'Maintenance Wilayah')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <table id="tableroles"></table>
        <div id="pagerroles"></div>
    </div>
</div>

<div class="form-group row {{ $approved ? '' : 'kt-hidden' }}" id="status">
    <label class="col-3 col-form-label">Status</label>
    <div class="col-3">
		<input type="text" name="approved" id="approved" value="{{ $approved }}" />
		<input type="text" name="update" id="update" value="{{ checkAction('update') }}" />
        <input type="text" disabled name="status" class="form-control" id="status" value="APPROVED" />
    </div>
</div>


<!--begin::modalResetCreate-->
<div class="modal fade" id="modalResetValid" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i></i>Pindah Wilayah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form class="kt-form" id="formResetValid">
                    <input type="hidden" name="ID" id="ID">
                    
                    <div class="form-group row div-provinsi">
		                <label class="col-sm-3 col-form-label" for="provinsi">Provinsi Baru</label>
		                <div class="col-sm-8">
		                    <select class="form-control form-control" name="Provinsi" id="Provinsi">		                        
		                    </select>
		                </div>
		            </div>

		            <div class="form-group row div-kabupaten" id="divKabupaten" name="divKabupaten">
		                <label class="col-sm-3 col-form-label" for="Kabupaten">Kabupaten Baru</label>
		                <div class="col-sm-8">
		                    <select class="form-control form-control" name="Kabupaten" id="Kabupaten">		                       
		                    </select>
		                </div>
		            </div>

		            <div class="form-group row div-kecamatan">
		                <label class="col-sm-3 col-form-label" for="Kecamatan">Kecamatan Baru</label>
		                <div class="col-sm-8">
		                    <select class="form-control form-control" name="Kecamatan" id="Kecamatan">
		                        <option value=""></option>
		                    </select>
		                </div>
		            </div>

		            <div class="form-group row div-kelurahan">
		                <label class="col-sm-3 col-form-label" for="Kelurahan">Kelurahan Baru</label>
		                <div class="col-sm-8">
		                    <select class="form-control form-control" name="Kelurahan" id="Kelurahan">
		                        <option value=""></option>
		                    </select>
		                </div>
		            </div>

		            <div class="form-group row div-RW">
		                <label class="col-sm-3 col-form-label" for="RW">RW Baru</label>
		                <div class="col-sm-8">
		                    <select class="form-control form-control" name="RW" id="RW">
		                        <option value=""></option>
		                    </select>
		                </div>
		            </div>

                    <div class="form-group text-right">
                        <button type="button" class="btn btn-warning" id="btnSave">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::modalResetCreate-->
@endsection

@section('script')
<link href="{{ url('assets/plugins/jqGrid/themes/base/theme.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.custom.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.min.js') }}"></script>
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.custom.js') }}"></script>
<script src="{{ url('assets/scripts/wilayah/wilayah.index.js') }}"></script>
@endsection
