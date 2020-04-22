@extends('layouts.layout')

@section('title')
    List Of Materials
@endsection

@section('links')
    <script src="{{asset('js/warehouse/material.js')}}"></script>
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
                    <div class="search-box pull-left">
                        <form id="searchForm" method="GET" >
                            <input id="searchMaterial" type="text" name="search" placeholder="Search by name or id..." >
                            <i class="ti-search"></i>
                        </form>
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
        <div class="page-title-area">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="breadcrumbs-area clearfix m-3">
                        <h4 class="page-title pull-left">List Of Materials</h4>
                        <ul class="breadcrumbs pull-left">
                            <li><a href="{{url('/')}}">Home</a></li>
                            <li><span>Materials</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content-inner">
            <main role="main" class="mt-1"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                <div class=" mt-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Material</h1>
                    <button  id="newMaterialButton"  class="btn btn-outline-primary">
                        <i class="ti-plus"></i>
                    </button>
                </div>
                <div class="table-responsive ">
                    <div class="modal fade" id="newMaterialModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">New Material</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" id="newMaterialForm">
                                        @csrf
                                        <div class="form-group mt-2">
                                            <label for="nameNew">Name</label>
                                            <input name="nameNew" type="text" class="form-control" id="nameNew" placeholder="Name">
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="amountNew">Amount</label>
                                            <input name="amountNew" type="number" class="form-control" value="0" id="amountNew" placeholder="Amount">
                                        </div>
                                        <div class="form-group mt-2" >
                                            <label for="unitNew">Unit</label>
                                            <input name="unitNew" class="form-control" type="text" id="unitNew" list="unit" placeholder="Unit" autocomplete="off">
                                            <datalist id="unit">
                                                <option value="KG">
                                                <option value="GRAM">
                                                <option value="ML">
                                                <option value="L">
                                                <option value="PACKAGES">
                                                <option value="PIECES">
                                                <option value="BOTTLE">
                                            </datalist>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="note">Note</label>
                                            <textarea class="form-control" name="noteNew" id="noteNew"  rows="3"></textarea>
                                        </div>
                                        <div class="modal-footer mt-4">
                                            <button type="submit" class="btn btn-primary">Create</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Unit</th>
                            <th>Note</th>
                            <th>Updated_at</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody  id="listMaterials">

                        </tbody>
                    </table>
                    <div class="text-center mb-2"  id="loading" style="display: none;">
                        <img src="{{asset("images/loading.gif")}}" alt="loading..." style="margin-bottom: 70px">
                    </div>
                    <div id="seeMore" class="text-center" style="display: none; ">
                        <button id= "moreNewPosts"class="btn btn-primary w-50" style="margin-bottom: 70px">
                            See more
                            <i class="ti-arrow-circle-down"></i>
                        </button>
                    </div>
                </div>
            </main>
        </div>
    </div>


    {{--Modal--}}
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Material</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-2"  id="loading_modal" style="display: none;">
                        <img src="{{asset("images/loading.gif")}}" alt="loading..." style="margin-bottom: 190px; margin-top: 187px">
                    </div>

                    <form id="form_modal">

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
