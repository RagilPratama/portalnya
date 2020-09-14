<!--begin::modalWilayah-->
<div class="modal fade" id="modalWilayah" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Akses Wilayah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">

                <form class="kt-form kt-form--fit kt-margin-b-20" id="formUserAkses">

                    <input type="hidden" id="pUserID" value="0" class="form-control" />
                    <input type="hidden" id="pTingkatWilayahID" value="0" class="form-control" />
                    <div class="row rowwil kt-margin-b-10 kt-hidden">
                        <div class="col-lg-6 kt-margin-b-10 kt-hidden-tablet-and-mobile">
                            <label>Provinsi:</label>
                            <select class="ddwil form-control" data-next="" data-tk="1" name="wilayahid[1]"></select>
                        </div>
                    </div>
                    <div class="row rowwil kt-margin-b-10 kt-hidden">
                        <div class="col-lg-6 kt-margin-b-10 kt-hidden-tablet-and-mobile">
                            <label>Kabupaten:</label>
                            <select class="ddwil form-control" data-next="" data-tk="2" name="wilayahid[2]"></select>
                        </div>
                    </div>
                    <div class="row rowwil kt-margin-b-10 kt-hidden">
                        <div class="col-lg-6 kt-margin-b-10 kt-hidden-tablet-and-mobile">
                            <label>Kecamatan:</label>
                            <select class="ddwil form-control" data-next="" data-tk="3" name="wilayahid[3]"></select>
                        </div>
                    </div>
                    <div class="row rowwil kt-margin-b-10 kt-hidden">
                        <div class="col-lg-6 kt-margin-b-10 kt-hidden-tablet-and-mobile">
                            <label>Kelurahan:</label>
                            <select class="ddwil form-control" data-next="" data-tk="4" name="wilayahid[4]"></select>
                        </div>
                    </div>
                    <div class="row rowwil kt-margin-b-10 kt-hidden">
                        <div class="col-lg-6 kt-margin-b-10 kt-hidden-tablet-and-mobile">
                            <label>RW:</label>
                            <select class="ddwil form-control" data-next="" data-tk="5" name="wilayahid[5]"></select>
                        </div>
                    </div>
                    <div class="row rowwil kt-margin-b-10 kt-hidden">
                        <div class="col-lg-6 kt-margin-b-10 kt-hidden-tablet-and-mobile">
                            <label>RT:</label>
                            <select class="ddwil form-control" data-next="" data-tk="6" name="wilayahid[6]"></select>
                        </div>
                    </div>
                    <div class="row kt-margin-b-10">
                        <div class="col-lg-6 kt-margin-b-10 kt-hidden-tablet-and-mobile text-right">

                            <button type="button" class="btn btn-primary btn-brand--icon" id="btnSaveAkses">
                                <span>
                                    <span>Simpan Akses Wilayah</span>
                                </span>
                            </button> &nbsp;&nbsp;
                            <button type="button" class="btn btn-secondary btn-brand--icon" data-dismiss="modal">
                                <span>
                                    <span>Tutup</span>
                                </span>
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<!--end::modalWilayah-->
