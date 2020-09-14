@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="row">
    <div class="col-sm-4">
        <div class="kt-portlet kt-callout kt-callout--info kt-callout--diagonal-bg">
            <div class="kt-portlet__body">
                <div class="kt-callout__body">
                    <div class="kt-callout__content">
                        <!--begin::Widget -->
                <div class="kt-widget kt-widget--user-profile-1">
                    <div class="kt-widget__head">
                        <div class="kt-widget__content">
                            <div class="kt-widget__section">
                                <h2><a href="#" class="kt-widget__username">
                                {{ auth()->user()->NamaLengkap }}
                                </a></h2>
                                <span class="kt-widget__subtitle">
                                    {{ auth()->user()->role->RoleName }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="kt-widget__body">
                        <div class="kt-widget__content">
                            <div class="kt-widget__info">
                                <span class="kt-widget__label">Email:</span>
                                <a href="#" class="kt-widget__data">{{ auth()->user()->Email }}</a>
                            </div>
                            <div class="kt-widget__info">
                                <span class="kt-widget__label">Phone:</span>
                                <a href="#" class="kt-widget__data">{{ auth()->user()->NoTelepon }}</a>
                            </div>
                            <div class="kt-widget__info">
                                <span class="kt-widget__label">Wilayah:</span>
                                <span class="kt-widget__data">
                                    {!! auth()->user()->akses !!} 
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!--end::Widget -->
                    </div>
                </div>
            </div>
        </div>
                                        
    </div>
</div>
<!--Begin::Row-->
								<div class="row">
									<div class="col-xl-4 col-lg-4">

										<!--begin:: Widgets/Daily Sales-->
										<div class="kt-portlet kt-portlet--height-fluid">
											<div class="kt-widget14">
												<div class="kt-widget14__header kt-margin-b-30">
													<h3 class="kt-widget14__title">
														Daily Data Entry
													</h3>
													<span class="kt-widget14__desc">
														Check out each collumn for more details
													</span>
												</div>
												<div class="kt-widget14__chart" style="height:120px;"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
													<canvas id="kt_chart_daily_sales" style="display: block; width: 377px; height: 120px;" width="377" height="120" class="chartjs-render-monitor"></canvas>
												</div>
											</div>
										</div>

										<!--end:: Widgets/Daily Sales-->
									</div>
    <div class="col-xl-4 col-lg-4">

										<!--begin:: Widgets/Profit Share-->
										<div class="kt-portlet kt-portlet--height-fluid">
											<div class="kt-widget14">
												<div class="kt-widget14__header">
													<h3 class="kt-widget14__title">REKAPITULASI KARTU PENDAFTARAN FASKES KB

</h3>
													<span class="kt-widget14__desc">JUMLAH FASKES KB BERDASARKAN JENIS FASKES KB DAN STATUS KERJASAMA DENGAN BPJS KESEHATAN
 </span>
												</div>
												<div class="kt-widget14__content">
													<div class="kt-widget14__chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
														<div class="kt-widget14__stat">45</div>
														<canvas id="kt_chart_profit_share" style="height: 140px; width: 140px; display: block;" width="140" height="140" class="chartjs-render-monitor"></canvas>
													</div>
													<div class="kt-widget14__legends">
														<div class="kt-widget14__legend">
															<span class="kt-widget14__bullet kt-bg-success"></span>
															<span class="kt-widget14__stats">37% RS (ADA SK PKBRS)

</span>
														</div>
														<div class="kt-widget14__legend">
															<span class="kt-widget14__bullet kt-bg-warning"></span>
															<span class="kt-widget14__stats">47% RS (BELUM ADA SK PKBRS)

</span>
														</div>
														<div class="kt-widget14__legend">
															<span class="kt-widget14__bullet kt-bg-brand"></span>
															<span class="kt-widget14__stats">19% Others</span>
														</div>
													</div>
												</div>
											</div>
										</div>

										<!--end:: Widgets/Profit Share-->
									</div>
									<div class="col-xl-4 col-lg-4">

										<!--begin:: Widgets/Profit Share-->
										<div class="kt-portlet kt-portlet--height-fluid">
											<div class="kt-widget14">
												<div class="kt-widget14__header">
													<h3 class="kt-widget14__title">REKAPITULASI KARTU PENDAFTARAN FASKES KB

</h3>
													<span class="kt-widget14__desc">JUMLAH JEJARING FASKES KB
 </span>
												</div>
												<div class="kt-widget14__content">
													<div class="kt-widget14__chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
														<div class="kt-widget14__stat">45</div>
														<canvas id="kt_chart_profit_share2" style="height: 140px; width: 140px; display: block;" width="140" height="140" class="chartjs-render-monitor"></canvas>
													</div>
													<div class="kt-widget14__legends">
														<div class="kt-widget14__legend">
															<span class="kt-widget14__bullet kt-bg-success"></span>
															<span class="kt-widget14__stats">37% PRAKTIK DOKTER


</span>
														</div>
														<div class="kt-widget14__legend">
															<span class="kt-widget14__bullet kt-bg-warning"></span>
															<span class="kt-widget14__stats">47% PRAKTIK BIDAN MANDIRI


</span>
														</div>
														<div class="kt-widget14__legend">
															<span class="kt-widget14__bullet kt-bg-brand"></span>
															<span class="kt-widget14__stats">19% Others</span>
														</div>
													</div>
												</div>
											</div>
										</div>

										<!--end:: Widgets/Profit Share-->
									</div>
									
								</div>

								<!--End::Row-->
@endsection

@section('script')
<script src="{{ url('assets/scripts/home.js') }}"></script>
@endsection