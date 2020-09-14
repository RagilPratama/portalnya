@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="form-group row">
    <label class="col-md-2 col-form-label">Username</label>
    <div class="col-md-4">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="la la-user"></i></span></div>
            <input class="form-control" type="text" name="ID"  id="ID" value="{{ currentUser('ID') }}" readonly>
            <input class="form-control" type="text" value="{{ currentUser('UserName') }}" readonly>
        </div>
    </div>
    <label class="col-md-2 col-form-label">NIK</label>
    <div class="col-md-4">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="la la-user"></i></span></div>
            <input class="form-control" type="text" name="NIK"  id="NIK" value="{{ currentUser('NIK') }}">
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2 col-form-label">Nama Lengkap</label>
    <div class="col-md-4">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="la la-black-tie"></i></span></div>
            <input class="form-control" type="text" name="NamaLengkap"  id="NamaLengkap" value="{{ currentUser('NamaLengkap') }}">
        </div>
    </div>
    <label class="col-md-2 col-form-label">Alamat</label>
    <div class="col-md-4">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="la la-black-tie"></i></span></div>
            <input class="form-control" type="text" name="Alamat"  id="Alamat" value="{{ currentUser('Alamat') }}">
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2 col-form-label">Email</label>
    <div class="col-md-4">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text">@</span></div>
            <input class="form-control" type="text" name="Email"  id="Email" value="{{ currentUser('Email') }}">
        </div>
    </div>
    <label class="col-md-2 col-form-label">NIP</label>
    <div class="col-md-4">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="la la-black-tie"></i></span></div>
            <input class="form-control" type="text" name="NIP"  id="NIP" value="{{ currentUser('NIP') }}">
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-2 col-form-label">No. Telepon</label>
    <div class="col-md-4">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone"></i></span></div>
            <input class="form-control" type="text" name="NoTelepon"  id="NoTelepon" value="{{ currentUser('NoTelepon') }}">
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-9 offset-md-2">
        <button type="button" class="btn btn-primary" id="btnSave">Simpan Profile</button>
    </div>
</div>


<div class="form-group row">
    <label class="col-md-12 col-form-label"><hr /><h5>Perubahan Password</h5></label>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label">Password Lama</label>
    <div class="col-md-9">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="la la-key"></i></span></div>
            <input class="form-control" name="oldpassword" id="oldpassword" type="password" value="">
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label">Password Baru</label>
    <div class="col-md-9">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="la la-key"></i></span></div>
            <input class="form-control" name="newpassword" id="newpassword" type="password" value="">
        </div>
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-form-label">Konfirmasi Password Baru</label>
    <div class="col-md-9">
        <div class="input-group">
            <div class="input-group-prepend"><span class="input-group-text"><i class="la la-key"></i></span></div>
            <input class="form-control" name="newpasswordconfirm" id="newpasswordconfirm" type="password" value="">
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-9 offset-md-3">
        <button type="button" class="btn btn-primary" id="btnChangePwd">Ubah Password</button>
    </div>
</div>

@endsection

@section('script')
<script src="{{ url('assets/scripts/user/user.profile.js') }}"></script>
@endsection
