<!--begin::modalDetail-->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i></i>Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">

                <ul class="nav nav-tabs" role="tablist" id="tabview">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#" data-target="#tab_demografi">DEMOGRAFI</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_kb1">KB1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_pk01">PK01</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_pk02">PK02</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="tab_demografi" role="tabpanel">
                        <h4>I. KEPENDUDUKAN</h4>

                        <table class="table table-striped- table-bordered table-hover table-checkable" id="tablegridview">
                        </table>
                        <table id="tableview"></table>
                        <div id="pagerview"></div>
                    </div>
                    <div class="tab-pane" id="tab_kb1" role="tabpanel">
                        <h4>II. KELUARGA BERENCANA (DITANYAKAN KEPADA WANITA PUS UMUR 15-49 TAHUN)</h4>
                        <div id="kb1form"></div>
                    </div>
                    <div class="tab-pane" id="tab_pk01" role="tabpanel">
                        <h4>III. PEMBANGUNAN KELUARGA</h4>
                        <div id="pk01form"></div>
                    </div>
                    <div class="tab-pane" id="tab_pk02" role="tabpanel">
                        <h4>III. PEMBANGUNAN KELUARGA (bag. 2)</h4>
                        <div id="pk02form"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::modalDetail-->
