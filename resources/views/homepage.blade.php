@extends('layouts.layout')

@section('title')
    Homepage
@endsection

@section('content')
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                       <b>{{\Illuminate\Support\Facades\Auth::user()->name}}</b>
                    </a>
                </li>
                <li>
                    <a href="#">Dashboard</a>
                </li>
                <li>
                    <a href="#">Shortcuts</a>
                </li>
                <li>
                    <a href="#">Overview</a>
                </li>
                <li>
                    <a href="#">Events</a>
                </li>
                <li>
                    <a href="#">About</a>
                </li>
                <li>
                    <a href="#">Services</a>
                </li>
                <li>
                    <a href="#">Contact</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div class="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-1">
                        <div>
                            {{--Button show sidebar--}}
                            <button class="btn btn-default menu-toggle">
                                <img src="{{asset('images/side_show.png')}}" style="width: 60px; height: 60px" alt="" >
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-11">

                        <h1>
                            Hello
                        </h1>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(".menu-toggle").click(function(e) {
            e.preventDefault();
            $(".wrapper").toggleClass("toggled");
        });
    </script>
@endsection
