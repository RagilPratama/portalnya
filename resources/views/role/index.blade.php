@extends('layouts.app')

@section('title', 'Daftar Role')

@section('content')
<div class="row kt-margin-b-25">
    <div class="col-sm-9 kt-margin-b-20-tablet-and-mobile">
        <button type="button" class="btn btn-primary" id="btnCreate">Tambah Role</button>
    </div>
    <div class="col-sm-3 kt-margin-b-20-tablet-and-mobile">
    <div class="kt-input-icon kt-input-icon--left">
        <input type="text" class="form-control" placeholder="Search..." id="searchTerm">
        <span class="kt-input-icon__icon kt-input-icon__icon--left">
        <span><i class="la la-search"></i></span>
        </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="kt-datatable" id="tablegrid"></div>
    </div>
</div>

<!--begin::modalRoleCreate-->
<div class="modal fade" id="modalRoleCreate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Hak Akses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form class="kt-form" id="formRoleCreate">
                    <div class="form-group">
                        <label for="name" class="form-control-label">Nama Hak Akses</label>
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-primary" id="btnSave">Create Role</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::modalRoleCreate-->

<!--begin::modalRoleEdit-->
<div class="modal fade" id="modalRoleEdit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Hak Akses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form class="kt-form" id="formRoleEdit">
                    <input type="hidden" name="ID" id="ID">
                    <div class="form-group">
                        <label for="RoleName" class="form-control-label">Nama Hak Akses</label>
                        <input type="text" class="form-control" name="RoleName" id="RoleName">
                    </div>
                    <div class="form-group">
                        <label for="Level" class="form-control-label">Level</label>
                        <input type="number" min="1" class="form-control" name="Level" id="Level">
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-primary" id="btnUpdate">Update Role</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::modalRoleEdit-->

@endsection

@section('script')
<script src="{{ url('assets/scripts/role/role.index.js') }}"></script>
@endsection
