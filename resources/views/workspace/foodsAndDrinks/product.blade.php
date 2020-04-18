@extends('layouts.layout')

@section('title')
    List Of Products
@endsection

@section('links')
    <script src="{{asset('js/foodsAndDrinks/product.js')}}"></script>
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
                    {{--<div class="search-box pull-left">
                        <form id="searchForm" method="GET" >
                            <input id="searchUser" type="text" name="search" placeholder="Search by name or username...">
                            <i class="ti-search"></i>
                        </form>
                    </div>--}}
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
                        <h4 class="page-title pull-left">List Of Products</h4>
                        <ul class="breadcrumbs pull-left">
                            <li><a href="{{url('/')}}">Home</a></li>
                            <li><span>Products</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content-inner">
            <main role="main" class="mt-1"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                <div class=" mt-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 class="h2">Drinks</h1>
                    <button  id="newUserButton"  class="btn btn-outline-primary">
                        <i class="ti-plus"></i>
                    </button>
                </div>
                <div class="table-responsive ">
                    <div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">New User</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" id="newUserForm">
                                        @csrf
                                        <div class="form-group mt-2">
                                            <label for="name">Name</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="Fullname">
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="username">Username</label>
                                            <input name="username" type="text" class="form-control" id="username" placeholder=Username>
                                        </div>
                                        <div class="form-group mt-2" >
                                            <label for="role">Role</label>
                                            <select class="form-control" id="role" name="role">
                                                <option  value="Employee" selected>Employee</option>
                                                <option value="Manager">Manager</option>
                                                <option value="Admin">Admin</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="password">Password</label>
                                            <input name="password" type="password" class="form-control" id="password" placeholder="Password">
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="passwordConfirm">Password Conform</label>
                                            <input name="passwordConfirm" type="password" class="form-control" id="passwordConfirm" placeholder="Password Confirm">
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
                            <th>Price</th>
                            <th>Sale_Price</th>
                            <th>Image</th>
                            <th>Ingredients</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody  id="listDrinks">
                            <tr id="`+item.id+`">
                                <td>11</td>
                                <td>fdsfsfdsfs</td>
                                <td>1111</td>
                                <td>11</td>
                                <td>
                                    <img style="width: 60px; height: 60px" src="{{asset('images/products/default_url_product.png')}}" alt="image_product">
                                </td>
                                <td>
                                    <button class="ml-2 btn btn-outline-info">
                                        <i class="ti-notepad"></i>
                                    </button>
                                </td>
                                <td>
                                    <button  name="`+item.id+`" class="edit btn btn-primary mb-1" style="width: 75px">
                                        Edit
                                    </button>
                                    <button name="`+item.id+`"  class="delete btn btn-danger mb-1" style="width: 75px">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center mb-2"  id="loadingDrinks" style="display: none;">
                        <img src="{{asset("images/loading.gif")}}" alt="loading..." style="margin-bottom: 70px">
                    </div>
                </div>
            </main>

            {{-----------------------Food-------------------------------}}
            <main role="main" class="mt-1"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                <div class=" mt-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 class="h2">Foods</h1>
                </div>
                <div class="table-responsive ">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Sale_Price</th>
                            <th>Image</th>
                            <th>Ingredients</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody  id="listFoods">

                        </tbody>
                    </table>
                    <div class="text-center mb-2"  id="loadingFoods" style="display: none;">
                        <img src="{{asset("images/loading.gif")}}" alt="loading..." style="margin-bottom: 70px">
                    </div>
                </div>
            </main>
        </div>



    {{--Modal edit product--}}
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-2"  id="loading_modal" style="display: none;">
                        <img src="{{asset("images/loading.gif")}}" alt="loading..." style="margin-bottom: 190px; margin-top: 187px">
                    </div>

                    <form id="form_modal" enctype="multipart/form-data">

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--Modal ingredient--}}
    <div class="modal fade" id="modal_ingredient" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ingredients</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="text-center mb-2"  id="loading_modal_ingredient" style="display: none;">
                    <img src="{{asset("images/loading.gif")}}" alt="loading..." style="margin-bottom: 190px; margin-top: 187px">
                </div>
                <div class="modal-body" id="modal_edit_ingredient">

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
