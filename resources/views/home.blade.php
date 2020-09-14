@extends('layouts.app')

@section('title', 'Beranda')

@section('content_home')




<div class="row">

@auth()

@if (!empty($reminder))
<!-- Notifikasi / Reminder -->
<div class="col-sm-12 col-md-6 col-lg-12">
    <div class="row" >
        <div class="col">
            <div class="alert alert-light alert-elevate fade show" role="alert">
                <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                <div class="alert-text">
                    <span class="blinking">{{$reminder}}</span>                 
                </div>
            </div>
        </div>
    </div>
</div>
<!--
<div class="alert" style="margin-left: -20px;">
<div class="alert-icon -danger"><i class="flaticon2-bell kt-font-brand"></i></div>
<div class="alert-text"><span class="blinking">
    {{$reminder}} </span>
</div>
</div>
-->
@endif
                                 
<div class="col-sm-12 col-md-6 col-lg-12">
<div class="kt-portlet">
    <div class="kt-portlet__body  kt-portlet__body--fit" id="headerPortlet">
        <div class="row row-no-padding row-col-separator-lg">

            <div class="col-md-12 col-lg-6 col-xl-3">
                <!--begin::Total Profit-->
                <div class="kt-widget24">
                    <div class="kt-widget24__details">
                        <div class="kt-widget24__info">
                            <h4 class="kt-widget24__title"> Target </h4>
                            <span class="kt-widget24__desc"> Target Keluarga </span>
                        </div>

                        <span class="kt-widget24__stats kt-font-brand" id="targetHeader"> 0 </span>
                    </div>

                    <div class="progress progress--xl">
                        <!--<div class="progress-bar kt-bg-brand" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>-->                        
                    </div>

                    <!--<div class="kt-widget24__action">
                        <span class="kt-widget24__change"> Capain </span>
                        <span class="kt-widget24__number"> 78% </span>
                    </div>-->
                </div>
                <!--end::Total Profit-->
            </div>

            <div class="col-md-12 col-lg-6 col-xl-3">
                <!--begin::New Feedbacks-->
                <div class="kt-widget24">
                    <div class="kt-widget24__details">
                        <div class="kt-widget24__info">
                            <h4 class="kt-widget24__title"> Didata </h4>
                            <span class="kt-widget24__desc"> Keluarga Didata </span>
                        </div>

                        <span class="kt-widget24__stats kt-font-warning" id="terdataHeader">  0 </span>
                    </div>

                    <div class="progress progress--sm">
                        <!--<div class="progress-bar kt-bg-warning" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>-->
                    </div>

                    <!--<div class="kt-widget24__action">
                        <span class="kt-widget24__change"> Capain </span>
                        <span class="kt-widget24__number"> 84% </span>
                    </div>-->
                </div>
                <!--end::New Feedbacks-->
            </div>

            <div class="col-md-12 col-lg-6 col-xl-3">
                <!--begin::New Orders-->
                <div class="kt-widget24">
                    <div class="kt-widget24__details">
                        <div class="kt-widget24__info">
                            <h4 class="kt-widget24__title">  Valid </h4>
                            <span class="kt-widget24__desc">  Data Valid </span>
                        </div>

                        <span class="kt-widget24__stats kt-font-success" id="validHeader"> 0 </span>
                    </div>

                    <div class="progress progress--sm">
                        <!--<div class="progress-bar kt-bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>-->
                    </div>

                    <!--<div class="kt-widget24__action">
                        <span class="kt-widget24__change"> Capain  </span>
                        <span class="kt-widget24__number"> 69%  </span>
                    </div>-->
                </div>
                <!--end::New Orders-->
            </div>

            <div class="col-md-12 col-lg-6 col-xl-3">
                <!--begin::New Users-->
                <div class="kt-widget24">
                    <div class="kt-widget24__details">
                        <div class="kt-widget24__info">
                            <h4 class="kt-widget24__title"> Anomali </h4>
                            <span class="kt-widget24__desc">  Data Not Valid, Anomali, Anulir </span>
                        </div>

                        <span class="kt-widget24__stats kt-font-danger" id="anomaliHeader"> 0 </span>
                    </div>

                    <div class="progress progress--sm">
                        <!--<div class="progress-bar kt-bg-success" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>-->
                    </div>

                    <!--<div class="kt-widget24__action">
                        <span class="kt-widget24__change"> Capain </span>
                        <span class="kt-widget24__number"> 90% </span>
                    </div>-->
                </div>
                <!--end::New Users-->
            </div>

        </div>
    </div>
</div>
</div>
    <!-- Profile -->
    
    <div class="col-sm-12 col-md-6 col-lg-4">
    <div class="kt-portlet ">
    <div class="kt-portlet__head  kt-portlet__head--noborder">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            
        </div>
    </div>
    <div class="kt-portlet__body kt-portlet__body--fit-y">
        <!--begin::Widget -->
        <div class="kt-widget kt-widget--user-profile-1">
            <div class="kt-widget__head">
                <div class="kt-widget__media">                    
                                        <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                                        <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--xl kt-badge--rounded kt-badge--bold" style="font-size: 200%;">{{ucfirst(currentUser('RoleNameID'))}}</span>
                </div>
                <div class="kt-widget__content">
                    <div class="kt-widget__section">
                        <a href="#" class="kt-widget__username">{{ currentUser('NamaLengkap') }}<i class="flaticon2-correct kt-font-success"></i>
                        </a>
                        <span class="kt-widget__subtitle">{{ currentUser('RoleName') }} </span>
                    </div>

                    <div class="kt-widget__action">
                        <button type="button" class="btn btn-info btn-sm">chat</button>&nbsp;
                        <button type="button" class="btn btn-success btn-sm">follow</button>
                    </div>
                </div>
            </div>
            <div class="kt-widget__body">
                <div class="kt-widget__content">
                    <div class="kt-widget__info">
                        <span class="kt-widget__label">Email:</span>
                        <a href="#" class="kt-widget__data">{{ currentUser('Email') }}</a>
                    </div>
                    <div class="kt-widget__info">
                        <span class="kt-widget__label">Telepon:</span>
                        <a href="#" class="kt-widget__data">{{ currentUser('NoTelepon') }}</a>
                    </div>
                    <div class="kt-widget__info">
                        <span class="kt-widget__label">Lokasi :</span>
                        <span class="kt-widget__data">{{ currentUser('TingkatWilayah') }}</span>
                    </div>
                </div>
                <div class="kt-widget__items">
                    <a href="/metronic/preview/demo1/custom/apps/user/profile-1/overview.html" class="kt-widget__item ">
                        <span class="kt-widget__section">
                            <span class="kt-widget__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"></polygon>
        <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"></path>
        <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"></path>
    </g>
</svg>                            </span>
                            <span class="kt-widget__desc">
                                Profile Overview
                            </span>
                        </span>
                    </a>
                    <a href="/metronic/preview/demo1/custom/apps/user/profile-1/personal-information.html" class="kt-widget__item kt-widget__item--active">
                        <span class="kt-widget__section">
                            <span class="kt-widget__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"></polygon>
        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
    </g>
</svg>                            </span>
                            <span class="kt-widget__desc">
                                Personal Information
                            </span>
                        </span>
                    </a>
                    <a href="/metronic/preview/demo1/custom/apps/user/profile-1/account-information.html" class="kt-widget__item ">
                        <span class="kt-widget__section">
                            <span class="kt-widget__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"></rect>
        <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3"></path>
        <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000"></path>
    </g>
</svg>                            </span>
                            <span class="kt-widget__desc">
                                Account Information
                            </span>


                        
                    </span></a>
                   
                    <div>&nbsp;</div>
                    <div>&nbsp;</div>
                    <div>&nbsp;</div>
                    <div>&nbsp;</div>
                    <div>&nbsp;</div>
                    <div>&nbsp;</div>
                    <div>&nbsp;</div>

                </div>
            </div>
        </div>
        <!--end::Widget -->
    </div>
</div>
</div>
@endauth()
    <!--------------------------------------->
    @auth()
    <div class="col-sm-12 col-md-6 col-lg-4">
        <div class="kt-portlet kt-portlet--height-fluid" id="chart1">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Data Target vs Terdata
                    </h3>
                </div>
                
            </div>
            <div class="kt-portlet__body kt-portlet__body--fluid">
                <div class="kt-widget12">
                    <div class="kt-widget12__content">
                        <div class="kt-widget12__item">                      
                            <div class="kt-widget12__info">
                                <span class="kt-widget12__desc">Target</span> 
                                <span class="kt-widget12__value" id="targetchart1"></span>
                            </div>  

                            <div class="kt-widget12__info">
                                <span class="kt-widget12__desc">Terdata</span>
                                <span class="kt-widget12__value" id="terdatachart1"></span> 
                            </div>                                       
                        </div>
                        <div class="kt-widget12__item">                            
                            <div class="kt-widget12__info">
                                <span class="kt-widget12__desc">Persentase</span> 
                                <div class="kt-widget12__progress"> 
                                    <div class="progress kt-progress--sm" id="prosens" >
                                        <div id="prosens" class="progress-bar kt-bg-brand" role="progressbar" style="width: 0%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>                               
                                    <span class="kt-widget12__stat" id="prosenchart1"> 0% </span>
                                </div>
                            </div>    
                            <div class="kt-widget12__info">
                                    <!--<span class="kt-widget12__desc">Status Kawin</span> 
                                    <span class="kt-widget12__value">60</span>-->
                            </div>                  
                        </div>
                    </div>
                    <div class="kt-widget12__chart" style="height:250px;"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <canvas id="kt_chart_order_statistics1" width="492" height="188" class="chartjs-render-monitor" style="display: block; height: 251px; width: 656px;"></canvas>
                    </div>
                </div>       
            </div>
        </div>        

    </div>
    @endauth()
    <!-- chart 2 -->

    @guest()
    <div class="col-sm-12 col-md-12 col-lg-12">
    @endguest()    
    @auth()
    <div class="col-sm-12 col-md-12 col-lg-4">
    @endauth()
        <div class="kt-portlet kt-portlet--height-fluid" id="chart2">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                       Unsur Anomali
                    </h3>
                </div>
                
            </div>
            <div class="kt-portlet__body kt-portlet__body--fluid">
                <div class="kt-widget12">
                    <div class="kt-widget12__content">
                        <div class="kt-widget12__item">                      
                            <div class="kt-widget12__info">
                                <span class="kt-widget12__desc">Valid</span> 
                                <span class="kt-widget12__value kt-font-success" id="jml_valid">0</span>
                            </div>  

                            <div class="kt-widget12__info">
                                <span class="kt-widget12__desc">Tidak Valid</span>
                                <span class="kt-widget12__value kt-font-danger" id="jml_notvalid">0</span> 
                            </div>                                       
                        </div>
                        <div class="kt-widget12__item">                            
                           <div class="kt-widget12__info">
                                <span class="kt-widget12__desc">Anomali</span> 
                                <span class="kt-widget12__value kt-font-warning" id="jml_anomali">0</span>
                            </div>  

                            <!-- <div class="kt-widget12__info">
                                <span class="kt-widget12__desc">Anulir</span>
                                <span class="kt-widget12__value kt-font-info" id="jml_anulir">0</span> 
                            </div>                   -->
                        </div>
                    </div>
                    <div class="kt-widget12__chart" style="height:250px;"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <canvas id="kt_chart_order_statistics2" width="492" height="188" class="chartjs-render-monitor" style="display: block; height: 251px; width: 656px;"></canvas>
                    </div>
                </div>       
            </div>
        </div>        

    </div>

    <!-------------->


    <!-- Chart JUMLAH DATA HARIAN Harian -->
    
    <!--<div class="col-sm-12 col-md-6 col-lg-4">    
        <div class="kt-portlet kt-portlet--height-fluid kt-callout">
            <div class="kt-widget14">
                <div class="kt-widget14__header">
                    <h3 class="kt-widget14__title"><span class="kt-badge kt-badge--unified-info kt-badge--lg kt-badge--bold"><span class="fa fa-chart-bar"></span></span> JUMLAH DATA HARIAN</h3>
                    <span class="kt-widget14__desc">&nbsp;</span>
                </div>
                <div class="kt-widget__body">
                    <canvas id="dailysumchart"></canvas>
                </div>
            </div>
        </div>            
    </div> -->
    <div class="col-sm-12 col-md-6 col-lg-6">    
        <div class="kt-portlet kt-portlet--head--noborder kt-portlet--height-fluid" id="chart3">
            <div class="kt-portlet__head kt-portlet__head--noborder">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title"> JUMLAH DATA HARIAN  </h3>
                </div>
                
            </div>
            <div class="kt-portlet__body">
                <!--begin::Widget 6-->
                <div class="kt-widget15">
                    <div class="kt-widget15__chart">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="kt_chart_sales_stats" style="height: 160px; display: block; width: 415px;" width="311" height="120" class="chartjs-render-monitor"></canvas>
                    </div>
                    <div class="kt-widget15__items kt-margin-t-40">
                        <div class="row">           
                            <div>    
                                <table id="tablegridchart3"></table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <!--<div class="kt-widget15__desc">
                                    * lorem ipsum dolor sit amet consectetuer sediat elit
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Widget 6-->
            </div>
        </div>
    </div>    
    <!-- end JUMLAH DATA HARIAN -->


    <div class="col-sm-12 col-md-12 col-lg-6">
        <div class="kt-portlet kt-portlet--height-fluid kt-callout">
            <div class="kt-widget14">
                <div class="kt-widget14__header">
                    <h3 class="kt-widget14__title"><span class="kt-badge kt-badge--unified-info kt-badge--lg kt-badge--bold"><span class="fas fa-table"></span></span> DATA TERBARU</h3>
                    <span class="kt-widget14__desc">&nbsp;</span>
                </div>
                <div class="kt-widget__body">
                    <table id="tablegrid"></table>
                </div>
            </div>
        </div>
    </div>
    
</div>

@endsection

@section('script')
<script src="{{ url('assets/scripts/home.js') }}"></script>
@endsection
