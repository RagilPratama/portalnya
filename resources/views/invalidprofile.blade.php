@extends('layouts.app')

@section('title', 'Profil Tidak Lengkap')

@section('content')
<div class="alert alert-outline-danger fade show" role="alert">
            <div class="alert-icon"><i class="flaticon-exclamation-1"></i></div>
            <div class="alert-text">
                <p>Profil pengguna tidak lengkap. Segera lengkapi [{{ $field ?? '' }}] untuk dapat melanjutkan.</p>
                <a href="{{ url('/') }}" class="btn btn-outline-primary btn-sm">Beranda</a>  
                <a href="{{ url('/user/profile') }}" class="btn btn-outline-success btn-sm">Profil</a> 
            </div>
        </div>
@endsection

@section('script')
<!-- css dan js yang dibutuhkan letakkan di sini -->
@endsection
