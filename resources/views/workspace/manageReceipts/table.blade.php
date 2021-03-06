@extends('layouts.layout')

@section('title')
    List Of Tables
@endsection

@section('links')
    <script src="https://js.pusher.com/6.0/pusher.min.js"></script>
    <script src="{{asset('js/manageReceipts/table.js')}}"></script>
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
        <div class="page-title-area">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <div class="breadcrumbs-area clearfix m-3">
                        <h4 class="page-title pull-left">List Of Tables</h4>
                        <ul class="breadcrumbs pull-left">
                            <li><a href="{{url('/')}}">Home</a></li>
                            <li><span>Tables</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content-inner">
            <main role="main" class="mt-1"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                <div class=" mt-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 class="h2">Tables</h1>
                    <button  id="newTableButton"  class="btn btn-outline-primary">
                        <i class="ti-plus"></i>
                    </button>
                </div>
                <div class="table-responsive ">
                    <div class="modal fade" id="newTableModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">New Table</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="newTableForm">
                                        @csrf
                                        <div class="form-group mt-2">
                                            <label for="name">Name</label>
                                            <input name="name" type="text" class="form-control"  placeholder="Enter name's table">
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="note">Note</label>
                                            <textarea class="form-control" name="note"rows="3"></textarea>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="status">Status</label>
                                            <input readonly name="status"  type="text" class="form-control" value="Empty">
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
                            <th>Note</th>
                            <th>Status</th>
                            <th>User Using</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody  id="listTables">
                        <tr id="`+item.id+`">
                            <td>11</td>
                            <td>fdsfsfdsfs</td>
                            <td>1111</td>
                            <td>
                                <textarea name="" id="" cols="30" rows="10"></textarea>
                            </td>
                            <td>
                                <select>
                                    <option>User_id</option>
                                    <option>Empty</option>
                                </select>
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
                    <div class="text-center mb-2"  id="loadingTables" style="display: none;">
                        <img src="{{asset("images/loading.gif")}}" alt="loading..." style="margin-bottom: 70px">
                    </div>
                </div>
            </main>
        {{--Modal edit product--}}
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Table</h5>
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
    </div>
    </div>
@endsection
