@extends('layouts.app')

@section('title', 'Daftar User')

@section('content')

<input type="hidden" id="maproletkwilayah_json" value="{{ $maproletkwilayah_json }}" />
<form class="kt-form kt-form--fit kt-margin-b-20">
    <div class="row kt-margin-b-20">
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile">
            <label>Status Wilayah User:</label>
            <select class="form-control" name="status_wilayah" id="status_wilayah">
                <option value=""></option>
                <option value="0">Belum Ditetapkan</option>
                <option value="1">Sudah Ditetapkan</option>
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile divwil">
            <label>Kelurahan:</label>
            <select class="form-control " name="Kelurahan" id="Kelurahan" >
                <option value=""></option>
                @foreach ($kelurahan as $row)
                <option value="{{$row->id}}">{{$row->text}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile divwil">
            <label>RW:</label>
            <select class="form-control " name="RW" id="RW" >
                <option value=""></option>
            </select>
        </div>
        <div class="col-lg-3 kt-margin-b-10-tablet-and-mobile divwil">
            <label>RT:</label>
            <select class="form-control " name="RT" id="RT" >
                <option value=""></option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <button type="button" class="btn btn-primary btn-brand--icon" id="btnShow">
                <span>
                    <i class="la la-search"></i>
                    <span>Tampilkan</span>
                </span>
            </button>
            &nbsp'&nbsp;
            <button type="button" class="btn btn-primary btn-brand--icon" id="btnCreate">
                <span>
                    <i class="la la-user-plus"></i>
                    <span>Tambah User</span>
                </span>
            </button>
        </div>
    </div>
</form>

<div class="kt-separator kt-separator--border-dashed kt-separator--space-md"></div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-striped- table-bordered table-hover table-checkable" id="tablegrid">
        </table>
    </div>
</div>

<!--begin::modalRoleCreate-->
<div class="modal fade" id="modalRoleCreate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i></i>Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="kt-form" id="formUserCreate">
                
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Username</label>
                                <div class="input-group">
                                    <div class="input-group-append"><span class="input-group-text" id="basic-addon2"><i class="la la-group"></i></span></div>
                                    <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon2" name="UserName" id="UserName" />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <div class="input-group-append"><span class="input-group-text" id="basic-addon2"><i class="la la-black-tie"></i></span></div>
                                    <input type="text" class="form-control" placeholder="Nama Lengkap User" aria-describedby="basic-addon2" name="NamaLengkap" id="NamaLengkap" />
                                </div>
                            </div>
                        </div>
                    </div>
                                 
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="Email" class="form-control-label">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">@</span></div>
                                    <input type="email" class="form-control" placeholder="Email" aria-describedby="basic-addon1" name="Email" id="Email" />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="NoTelepon" class="form-control-label">No. Telepon</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone-square"></i></span></div>
                                    <input type="text" class="form-control" placeholder="NoTelepon" aria-describedby="basic-addon1" name="NoTelepon" id="NoTelepon" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group kt-hidden">
                        <label for="name" class="form-control-label">Password</label>
                        <div class="input-group">
                          <div class="input-group-append"><span class="input-group-text" id="basic-addon2"><i class="la la-key"></i></span></div>
            							<input type="password" class="form-control" placeholder="Input Password" aria-describedby="basic-addon2" name="Password" id="Password" value="{{ $parameter['Pwd'] ?? '' }} ">
            						</div>
                    </div>
                    
                                        
                    <div class="row">
                        <div class="col-sm-6"><div class="form-group">
                                <label for="RoleID">Pilih Role</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user-tag"></i></span></div>
                                    <select class="form-control" id="RoleID" name="RoleID">
                                    <option value=''></option>
                                    @foreach ($availroles as $role)
                                    <option value="{{$role->ID}}">{{$role->RoleName}}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="TingkatWilayahID">Pilih Wilayah</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-list-ol"></i></span></div>
                                    <select class="form-control" id="TingkatWilayahID" name="TingkatWilayahID"></select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row kt-hidden" id="smartcheck">
                        <label class="col-6 col-form-label">Metode Pendataan Smartphone</label>
                        <div class="col-3">
                            <span class="kt-switch kt-switch--sm kt-switch--icon">
                                <label>
                                    <input type="checkbox" name="Smartphone" id="Smartphone">
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-group">
                            <label for="NIK" class="form-control-label">NIK</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-id-card"></i></span></div>
                                <input type="text" class="form-control" placeholder="NIK" name="NIK" id="NIK" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Alamat" class="form-control-label">Alamat</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span></div>
                            <input type="text" class="form-control" placeholder="Alamat" name="Alamat" id="Alamat"/>
                        </div>
                    </div>
                        
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-primary" id="btnSave">Tambah User Baru</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::modalRoleCreate-->

<!--begin::modalResetCreate-->
<div class="modal fade" id="modalResetCreate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i></i>Reset Password User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form class="kt-form" id="formPasswordReset">
                    <input type="hidden" name="ID" id="ID">
                    <div class="form-group">
                        <label for="name" class="form-control-label">New Password</label><label style="color:red;">&nbsp; *</label>
                        <input type="password" class="form-control" name="Password" id="Password">
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-control-label">Confirmation Password</label><label style="color:red;">&nbsp; *</label>
                        <input type="password" class="form-control" name="rePassword" id="rePassword">
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-warning" id="btnResetSave">Reset Password</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::modalResetCreate-->

<!--begin::modalUserEdit-->

<div class="modal fade" id="modalUserEdit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form class="kt-form" id="formUserEdit">
                    <input type="hidden" name="ID" id="ID">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Username</label>
                                <div class="input-group">
                                    <div class="input-group-append"><span class="input-group-text" id="basic-addon2"><i class="la la-group"></i></span></div>
                                    <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon2" name="UserName" id="UserName" disabled />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <div class="input-group-append"><span class="input-group-text" id="basic-addon2"><i class="la la-black-tie"></i></span></div>
                                    <input type="text" class="form-control" placeholder="Nama Lengkap User" aria-describedby="basic-addon2" name="NamaLengkap" id="NamaLengkap"disa />
                                </div>
                            </div>
                        </div>
                    </div>
                                 
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="Email" class="form-control-label">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">@</span></div>
                                    <input type="email" class="form-control" placeholder="Email" aria-describedby="basic-addon1" name="Email" id="Email" />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="NoTelepon" class="form-control-label">No HP</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone-square"></i></span></div>
                                    <input type="text" class="form-control" placeholder="NoTelepon" aria-describedby="basic-addon1" name="NoTelepon" id="NoTelepon" />
                                </div>
                            </div>
                        </div>
                    </div>
                                        
                    <div class="row">
                        <div class="col-sm-6"><div class="form-group">
                                <label for="RoleID">Pilih Role</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user-tag"></i></span></div>
                                    <select class="form-control" id="RoleID" name="RoleID">
                                    <option value=''></option>
                                    @foreach ($availroles as $role)
                                    <option value="{{$role->ID}}">{{$role->RoleName}}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="TingkatWilayahID">Pilih Wilayah</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-list-ol"></i></span></div>
                                    <select class="form-control" id="TingkatWilayahID" name="TingkatWilayahID"></select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row kt-hidden" id="smartcheck">
                        <label class="col-6 col-form-label">Metode Pendataan Smartphone</label>
                        <div class="col-3">
                            <span class="kt-switch kt-switch--sm kt-switch--icon">
                                <label>
                                    <input type="checkbox" name="Smartphone" id="Smartphone">
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-group">
                            <label for="NIK" class="form-control-label">NIK</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-id-card"></i></span></div>
                                <input type="text" class="form-control" placeholder="NIK" name="NIK" id="NIK" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="Alamat" class="form-control-label">Alamat</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span></div>
                            <input type="text" class="form-control" placeholder="Alamat" name="Alamat" id="Alamat"/>
                        </div>
                    </div>
                    
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-primary" id="btnUpdateUser">Update User Info</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUserEditxxx" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form class="kt-form" id="formUserEdit">
                    <input type="hidden" name="ID" id="ID">
                    <div class="form-group">
                        <label for="RoleName" class="form-control-label">Username</label>
                        <input type="text" class="form-control" name="UserName" id="UserName" disabled>
                    </div>
                    <div class="form-group">
                        <label for="RoleName" class="form-control-label">Email</label>
                        <input type="text" class="form-control" name="Email" id="Email" disabled>
                    </div>
                    <div class="form-group">
                        <label for="RoleName" class="form-control-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="NamaLengkap" id="NamaLengkap">
                    </div>
                    <div class="form-group">
                        <label for="RoleName" class="form-control-label">Alamat</label>
                        <input type="text" class="form-control" name="Alamat" id="Alamat">
                    </div>
                    <div class="form-group">
                        <label for="NoTelepon" class="form-control-label">No Telp</label>
                        <input type="number" min="1" class="form-control" name="NoTelepon" id="NoTelepon">
                    </div>
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-primary" id="btnUpdateUser">Update User Info</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end::modalUserEdit-->

@include('user.wilayah')

@endsection

@section('script')
{!! bundle('jqgrid') !!}
{!! bundle('datatable') !!}
<script src="{{ url('assets/scripts/user/user.index.js') }}"></script>
<script src="{{ url('assets/scripts/user/user.wilayah.js') }}"></script>
@endsection
