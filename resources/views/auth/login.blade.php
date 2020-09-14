<!DOCTYPE html>

<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 8
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">

<!-- begin::Head -->

<head>
    <base href="">
    <meta charset="utf-8" />
    <title>BKKBN PK2021</title>
    <meta name="description" content="Login page example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

    <!--end::Fonts -->

    <!--begin::Page Custom Styles(used by this page) -->
    <link href="{{ url('assets/css/login.css') }}" rel="stylesheet" type="text/css" />

    <!--end::Page Custom Styles -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="{{ url('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />

    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->

    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{ url('favicon.ico') }}" />

    <script>
            var base_url = '{{ url("/") }}';
            var csrf_token = '{{ csrf_token() }}';
        </script>
</head>

<!-- end::Head -->

<!-- begin::Body -->

<body class="kt-page--loading-enabled kt-page--loading kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header--minimize-topbar kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-page--loading">

    <!-- begin::Page loader -->

    <!-- end::Page Loader -->

    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--ver kt-grid--root kt-page">
        <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v1" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">

                <!--begin::Aside-->
                <div class="kt-grid__item kt-grid__item--order-tablet-and-mobile-2 kt-grid kt-grid--hor kt-login__aside" style="background-image: url(assets/media/bg/bg-1.jpg);">
                    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver">
                        <div class="kt-grid__item kt-grid__item--middle">
                            <h3 class="kt-login__title">Selamat Datang <br /> Di Portal PK2021 BKKBN</h3>
                        </div>
                    </div>
                    <div class="kt-grid__item">
                        <div class="kt-login__info">
                            <div class="kt-login__copyright">
                                &copy 2019 BKKBN
                            </div>
                        </div>
                    </div>
                </div>

                <!--begin::Aside-->

                <!--begin::Content-->
                <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">

                    <!--begin::Head-->
                    <!-- <div class="kt-login__head">
							<span class="kt-login__signup-label">Don't have an account yet?</span>&nbsp;&nbsp;
							<a href="#" class="kt-link kt-login__signup-link">Sign Up!</a>
						</div> -->

                    <!--end::Head-->

                    <!--begin::Body-->
                    <div class="kt-login__body">
                        <!--begin::Signin-->
                        <div class="kt-login__form">
                            <div class="kt-login__title">
                        
                    <div class="kt-grid__item">
                        <a href="#" class="kt-login__logo">
                            <img src="assets/media/logos/logo-4.png">
                        </a>
                    </div>
                                <h3>Sign In</h3>
                            </div>

                            @if ($errors->any())
                            <div class="alert alert-outline-danger fade show" role="alert">
                                <div class="alert-text">{!! $errors->first() !!}</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                            @endif
                            <!--begin::Form-->
                            <form class="kt-form" action="{{ url('login') }}" method="POST" novalidate="novalidate" id="kt_login_form">
                                @csrf
                                <div class="form-group">
                                    <input class="form-control" type="text" placeholder="Username / Email" name="UserName" id="UserName" value="{{ old('UserName') }}" autocomplete="off" required autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="password" placeholder="Password" name="Password" id="Password" required autocomplete="off" autocomplete="current-password">
                                </div>
                                <!--begin::Action-->
                                <div class="kt-login__actions">
                                    <a href="#" data-toggle="modal" data-target="#bannerformmodal">Lupa Password ?</a>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										</a>
                                    <button class="btn btn-primary btn-elevate kt-login__btn-primary">Sign In</button>
                                    <a href="{{ url('/') }} " class="kt-link kt-login__link-forgot">
											Cancel
										</a>
                                </div>

                                <!--end::Action-->
                            </form>

                            <!--end::Form-->
                        </div>

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
							                <form class="kt-form" id="formPasswordReset" action="{{ url('/forgotPassword') }}" method="POST">
                                            <input name="_method" type="hidden" value="PUT">

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
							    <div class="modal fade bannerformmodal" tabindex="-1" role="dialog" aria-labelledby="bannerformmodal" aria-hidden="true" id="bannerformmodal">
							        <div class="modal-dialog modal-lg" role="document">
							            <div class="modal-content">
							                <div class="modal-header">
							                    <h5 class="modal-title">Forgot Password</h5>
							                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							                    </button>
							                </div>
							                <div class="modal-body">
							                    
							                        <label for="OTP" class="form-control-label">Username/Email</label>
							                        <input type="text" class="form-control" name="username" id="username">
							                        <div>&nbsp;</div>
							                        <div class="form-group text-right">
							                            <button type="button" class="btn btn-success" id="btnSendEmail">Send</button>
							                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							                        </div>
							                </div>
							            </div>
							        </div>
							    </div>
							    <!--end::modalUserEdit-->


							    <!--begin::modalUserEdit-->
								<!--<div class="modal fade" id="modalUserVerifikasiOtp" tabindex="-1" role="dialog" aria-hidden="true">-->
									<div class="modal fade modalUserVerifikasiOtp" tabindex="-1" role="dialog" aria-labelledby="modalUserVerifikasiOtp" aria-hidden="true" id="modalUserVerifikasiOtp">
								    <div class="modal-dialog modal-lg" role="document">
								        <div class="modal-content">
								            <div class="modal-header">
								                <h5 class="modal-title">Verifikasi OTP</h5>
								                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								                </button>
								            </div>
								            <div class="modal-body">
								                    <input type="hidden" name="ID" id="ID">
								                    <div class="form-group">
								                        <label for="OTP" class="form-control-label">OTP</label>
								                        <input type="text" class="form-control" name="otp" id="otp">
								                    </div>                    
								                    <div class="form-group">
								                        <div><span id="OTPMsg"></span> Time Left = <span id="timer"></span></div>
								                    </div>
								                    <div class="form-group text-right">
								                        <button type="button" class="btn btn-danger" id="btnResendOtp" disabled>Resend OTP</button>
								                        <button type="button" class="btn btn-warning" id="btnVerifikasiOtp">Verifikasi OTP</button>
								                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
								                    </div>
								            </div>
								        </div>
								    </div>
								</div>
								<!--end::modalUserEdit-->
                        <!--end::Signin-->
                    </div>

                    <!--end::Body-->
                </div>

                <!--end::Content-->
            </div>
        </div>
    </div>

    

    <!-- end:: Page -->

    <!-- begin::Global Config(global config for global JS sciprts) -->
    <script>
        var KTAppOptions = {
            "colors": {
                "state": {
                    "brand": "#374afb",
                    "light": "#ffffff",
                    "dark": "#282a3c",
                    "primary": "#5867dd",
                    "success": "#34bfa3",
                    "info": "#36a3f7",
                    "warning": "#ffb822",
                    "danger": "#fd3995"
                },
                "base": {
                    "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                    "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
                }
            }
        };
    </script>

    <!-- end::Global Config -->


    <!--begin::Global Theme Bundle(used by all pages) -->
    <script src="{{ url('assets/plugins/global/plugins.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/scripts.bundle.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/js/custom.js') }}" type="text/javascript"></script>    

    <!--end::Global Theme Bundle -->

    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ url('assets/js/login.js') }}" type="text/javascript"></script>


    <!--end::Page Scripts -->
</body>

<!-- end::Body -->

</html>