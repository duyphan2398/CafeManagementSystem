@extends('layouts.layout')

@section('title')
    Statistics
@endsection

@section('links')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css">
    <script src="{{asset('js/warehouse/statistic.js')}}"></script>

@endsection

@section('content')
    <div class="main-content">
        <!-- header area start -->
        <div class="header-area">
            <div class="row align-items-center">
                <!-- nav and search button -->
                <div class="col-md-6 col-sm-8 clearfix">
                    <div class="nav-btn pull-left">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-4 clearfix">
                    <ul class="notification-area pull-right">
                        <li id="full-view"><i class="ti-fullscreen"></i></li>
                        <li id="full-view-exit"><i class="ti-zoom-out"></i></li>
                    </ul>
                </div>
            </div>
        </div>
        {{--Content Chart--}}
    <!-- page title area end -->
        <div class="main-content-inner">
            <!-- line chart start -->
            <div class="row">
                <div class="col-lg-6 mt-5">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="text-center mb-2"  id="loadingDiagram1" style="display: block;">
                                <img src="{{asset("images/loading.gif")}}" alt="loading..." style="height: 70px">
                            </div>
                            <canvas id="amlinechart1" style="display: none"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5">
                    <div class="card ">
                        <div class="card-body">
                            <div class="text-center mb-2"  id="loadingDiagram2" style="display: inline-block;">
                                <img src="{{asset("images/loading.gif")}}" alt="loading..." style="height: 70px;">
                            </div>
                            <canvas id="amlinechart2" style="display: none"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <div id="amlinechart3"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <div id="amlinechart4"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <div id="amlinechart5"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <div id="verview-shart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- line chart end -->
        </div>


    </div>
@endsection
