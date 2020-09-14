@extends('layouts.app')

@section('title', 'Monitoring Rekapitulasi')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <form class="kt-form kt-form--label-left" id="formFilter">

            <div class="row kt-margin-b-20">
                 <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
                    <label class="col-sm-6 col-form-label" for="PeriodeSensus">Periode Pendataan</label>            
                    <div class="col-sm-12">   
                        <select class="form-control form-control" name="PeriodeSensus" id="PeriodeSensus" {{ empty($valperiode) ? '' : 'disabled' }}>
                            <option value=""></option>
                            @foreach ($periode as $item)
                                <option value="{{ $item->Tahun }}" {{ $item->Tahun==$valperiode ? 'selected':'' }}>{{ $item->Tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-8 kt-margin-b-10-tablet-and-mobile">
                    <label class="col-sm-6 col-form-label" for="Berdasarkan">Berdasarkan</label>  
                    <div class="col-sm-12">              
                        <select class="form-control form-control" name="JenisData" id="JenisData">
                            <option value="">Your Placeholder Text</option>
                            <option value="1" selected>Kelurahan</option>
                            <option value="2">RW</option>
                            <option value="3">RT</option>
                        </select>
                    </div>
                </div>                             
            </div>


            <div class="row kt-margin-b-20">
                @foreach ($cakupan as $itemcakupan)
                <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
                    <label class="col-sm-6 col-form-label" for="JenisData">Provinsi</label>
                    <div class="col-sm-12">
                        <input class="form-control form-control" name="provinsi" id="provinsi" value="{{ $itemcakupan->nama_provinsi }}" disabled></input>
                    </div>
                </div>
                
                <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
                    <label class="col-sm-2 col-form-label" for="kabupaten">Kabupaten</label>
                    <div class="col-sm-12">
                        <input class="form-control form-control" name="kabupaten" id="kabupaten" value="{{ $itemcakupan->nama_kabupaten }}" disabled></input>
                    </div>
                </div>

                <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile">
                    <label class="col-sm-2 col-form-label" for="kabupaten">Kecamatan</label>
                    <div class="col-sm-12">
                        <input class="form-control form-control" name="kecamatan" id="kecamatan" value="{{ $itemcakupan->nama_kecamatan }}" disabled></input>
                        <input name="id_kecamatan"  id="id_kecamatan" type="hidden" value="{{ $itemcakupan->id_kecamatan }}">
                    </div>                    
                </div>
                @endforeach   
            </div>
 
            

            <div class="row kt-margin-b-20">
                <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile div-wilayah">
                    <label class="col-sm-6 col-form-label" for="Kelurahan">Desa/Kelurahan</label>
                    <div class="col-sm-12">
                        <select class="form-control form-control" name="Kelurahan" id="Kelurahan" {{ empty($valwilayah) ? '' : 'disabled' }}>
                            <option value=""></option>
                            @foreach ($wilayah as $item)
                                <option value="{{ $item->id }}" {{ $item->id==$valwilayah ? 'selected':'' }}>{{ $item->text }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>                  

                <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile div-rw">
                    <label class="col-sm-2 col-form-label" for="RW">RW</label>
                    <div class="col-sm-12">
                        <select class="form-control form-control" name="RW" id="RW" >
                            <option value=""></option>
                        </select>
                    </div>
                </div>    

                <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile div-rt">
                    <label class="col-sm-2 col-form-label" for="RT">RT</label>
                    <div class="col-sm-12">
                        <select class="form-control form-control" name="RT" id="RT" >
                            <option value=""></option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile div-pendata">
                    <label class="col-sm-2 col-form-label" for="Pendata">Pendata</label>
                    <div class="col-sm-12">
                        <select class="form-control form-control" name="Pendata" id="Pendata" >
                            <option value="" selected>--- Pilih Semua Pendata ---</option>
                            @foreach ($userPendata as $item)
                                <option value="{{ $item->UserName }}">{{ $item->UserName.' - '.$item->NamaLengkap }}</option>
                            @endforeach
                        </select>
                    </div>                
                </div>
            </div>


            <div class="form-group row" style="margin-left: 2px;">
                <div class="col-sm-12">
                <button type="button" class="btn btn-primary" id="btnShow">Tampilkan</button>
                @if (currentUser('RoleID') == '3' && $pass == '0')
                    <button type="button" class="btn btn-danger" id="btnApp">Approve</button>
                @else
                    <button type="button" class="btn btn-danger" id="btnApp" disabled>Approve</button>
                @endif
                @if ($pass == '1')
                    <button type="button" class="btn btn-primary kt-hiddenx" id="btnPrint">Cetak</button>
                @else
                    <button type="button" class="btn btn-primary kt-hiddenx" id="btnPrint" disabled>Cetak</button>
                @endif
                <span class="kt-badge kt-badge--outline kt-badge--warning kt-badge--md" data-toggle="kt-popover" title="" data-content="Bila pra-tinjau cetakan tidak tampil di browser, harap periksa aplikasi pengunduh atau folder default Unduhan (Download)" data-original-title="">!</span>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <!--<table id="tablemon"></table>-->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="tablemon"></table>
        <div id="pagermon" style="padding: 10px;"></div>
    </div>
</div>

<!--<div>&nbsp;</div>
<div class="form-group row">
    <div class="col-sm-3">
        <button type="button" class="btn btn-primary" id="btnShowData">data</button>
    </div>
</div>-->


<!--begin::modalDetail-->
<div class="modal fade" id="modalDetail2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i></i>Detail Data Anomali Pendataan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                  <div class="col-sm-12">
                      <table id="tableroles"></table>
                      <div id="pagerroles"></div>
                  </div>
              </div>
            </div>
        </div>
    </div>
</div>
<!--end::modalDetail-->

@include('laporan.form_view')

@endsection

@section('script')
{!! bundle('datatable') !!}
<link href="{{ url('assets/plugins/jqGrid/themes/base/theme.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ url('assets/plugins/jqGrid/css/ui.jqgrid.custom.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.min.js') }}"></script>
<script src="{{ url('assets/plugins/jqGrid/jquery.jqgrid.custom.js') }}"></script>
<script src="{{ url('assets/scripts/laporan/rekapitulasi.js') }}"></script>
<script src="{{ url('assets/scripts/laporan/form_view.js') }}"></script>
@endsection
