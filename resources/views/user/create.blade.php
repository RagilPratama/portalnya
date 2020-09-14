@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')

<input type="hidden" id="roletk_json" value="{{ $roletk_json }}" />
<input type="hidden" id="usertkwilayah" value="{{ auth()->user()->TingkatWilayahID ?? 0 }}" />
<div class="row">
    <div class="col-sm-10 offset-sm-1">
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
                    <div class="col-sm-6">
                        <div class="form-group">
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

                <div class="row kt-margin-b-20">
                    <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile divwil" data-tk="1">
                        <div class="form-group">
                            <label>Provinsi:</label>
                            <select class="input-group" name="Provinsi" id="Provinsi" >
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile divwil" data-tk="2">
                        <div class="form-group">
                            <label>Kota/Kabupaten:</label>
                            <select class="input-group" name="Kabupaten" id="Kabupaten" >
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile divwil" data-tk="3">
                        <div class="form-group">
                            <label>Kecamatan:</label>
                            <select class="input-group" name="Kecamatan" id="Kecamatan" >
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row kt-margin-b-20">
                    <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile divwil" data-tk="4">
                        <div class="form-group">
                            <label>Kelurahan:</label>
                            <select class="input-group" name="Kelurahan" id="Kelurahan" >
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile divwil" data-tk="5">
                        <div class="form-group">
                            <label>RW:</label>
                            <select class="input-group" name="RW" id="RW" >
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-4 kt-margin-b-10-tablet-and-mobile divwil" data-tk="6">
                        <div class="form-group">
                            <label>RT:</label>
                            <select class="input-group" name="RT" id="RT" >
                                <option value=""></option>
                            </select>
                        </div>
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

@endsection

@section('script')
<script src="{{ url('assets/scripts/user/user.create.js') }}"></script>
@endsection
