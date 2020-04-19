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
                    <button  id="newProductButton"  class="btn btn-outline-primary">
                        <i class="ti-plus"></i>
                    </button>
                </div>
                <div class="table-responsive ">
                    <div class="modal fade" id="newProductModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">New Product</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="newProductForm">
                                        @csrf
                                        <div class="form-group mt-2">
                                            <label for="name">Name</label>
                                            <input name="name" type="text" class="form-control"  placeholder="Enter name's product">
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="price">Price</label>
                                            <input name="price" type="number" class="form-control"  placeholder="Enter price">
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="sale_price">Sale Price</label>
                                            <input name="sale_price" type="number" value="null" class="form-control"  placeholder="null" readonly>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="sale_price">Type</label>
                                            <select class="form-control" name="type" id="">
                                                <option value="Drink">Drink</option>
                                                <option value="Food">Food</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-2 mb-2" >
                                            <label style="cursor: pointer;" for="url">Image(only extension: PNG JPG JPEG)</label>
                                            <input  onchange="readURL(this);"  accept="image/*" name="url" class="form-control-file border" type="file" >
                                        </div>
                                        <div class="form-group mt-2 ml-2 text-center">
                                            <img class="img_edit" style="height: 150px; width: 150px" src="" alt="">
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="product_id_modal" class="modal-title">Ingredients</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class=" mt-2 mb-1 text-center pl-5 pr-5">
                    <form class="form-group" id="insert_ingredient_form">
                        <select name="material_id"class="w-20 form-control mb-2 mr-sm-2" placeholder="Enter name" id="nameIngredient">
                        </select>
                        <input name="quantity" type="number" class="form-control mb-2 mr-sm-2" placeholder="Enter quantity" id="quantityIngredient">
                        <input name="unit" autocomplete="off" list="unit" type="text" class="form-control mb-2 mr-sm-2" placeholder="Enter unit" id="unitIngredient">
                        <datalist id="unit">
                            <option value="KG">
                            <option value="GRAM">
                            <option value="ML">
                            <option value="L">
                            <option value="PACKAGES">
                            <option value="PIECES">
                            <option value="BOTTLE">
                        </datalist>
                        <br>
                        <button type="submit" class="btn btn-primary w-25">Add</button>
                    </form>
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
