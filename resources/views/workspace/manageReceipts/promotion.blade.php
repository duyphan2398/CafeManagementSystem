@extends('layouts.layout')

@section('title')
    List Of Promotions
@endsection

@section('links')
    <link rel="stylesheet" href="{{asset('css/jquery.datetimepicker.css')}}">
    <script src="{{asset('js/jquery.datetimepicker.js')}}"></script>
    <script src="{{asset('js/jquery.datetimepicker.full.min.js')}}"></script>
    <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <script src="{{asset('js/manageReceipts/promotion.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <style>
        @cannot(' update', \App\Models\Promotion::class)
            .action, .edit, .delete {
                display: none !important;
            }
        @endcannot
        @cannot('create', \App\Models\Promotion::class)
            #newPromotionButton {
            display: none !important;
            }
        @endcannot

        input.form-check-input {
            transform : scale(2);
        }
        input.checkbox_product {
            transform : scale(3);
        }
    </style>
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
                        <h4 class="page-title pull-left">List Of Promotions</h4>
                        <ul class="breadcrumbs pull-left">
                            <li><a href="{{url('/')}}">Home</a></li>
                            <li><span>Promotions</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content-inner">
            <main role="main" class="mt-1"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                <div class=" mt-2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 class="h2">Tables</h1>
                    <button  id="newPromotionButton"  class="btn btn-outline-primary">
                        <i class="ti-plus"></i>
                    </button>
                </div>
                <div class="table-responsive ">
                    <div class="modal fade" id="newPromotionModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">New Promotion</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="newPromotionForm">
                                        @csrf
                                        <div class="form-group mt-2">
                                            <label for="name">Name</label>
                                            <input name="name" type="text" class="form-control"  placeholder="">
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" name="description"rows="3"></textarea>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="start_at">Start At</label>
                                            <input  id="start_at_new" name="start_at"  type="text" class="form-control" value="">
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="end_at">End At</label>
                                            <input  id="end_at_new" name="end_at"  type="text" class="form-control" value="">
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="sale_percent">Sale percent</label>
                                            <input   min="1" max="100" name="sale_percent" type="number" class="sale_percent form-control" value="">
                                        </div>
                                        <div>
                                            <label for="sale_percent">Days Approve</label>
                                            <br>
                                            <table class="table">
                                                <tr>
                                                    <td>
                                                        <div class="ml-2 form-check form-check-inline">
                                                            <input class="new_promotion_checkbox form-check-input" type="checkbox" id="inlineCheckbox1" value="Monday">
                                                            <label class="ml-1 form-check-label" for="inlineCheckbox1">Monday(2)</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="ml-2 form-check form-check-inline">
                                                            <input class="new_promotion_checkbox form-check-input" type="checkbox" id="inlineCheckbox2" value="Tuesday">
                                                            <label class="ml-1 form-check-label" for="inlineCheckbox2">Tuesday(3)</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="ml-2 form-check form-check-inline">
                                                            <input class="new_promotion_checkbox form-check-input" type="checkbox" id="inlineCheckbox3" value="Wenesday">
                                                            <label class=" ml-1 form-check-label" for="inlineCheckbox3">Wenesday(4)</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="ml-2 form-check form-check-inline">
                                                            <input class="new_promotion_checkbox form-check-input" type="checkbox" id="inlineCheckbox4" value="Thursday">
                                                            <label class=" ml-1 form-check-label" for="inlineCheckbox4">Thursday(5)</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="ml-2 form-check form-check-inline">
                                                            <input class="new_promotion_checkbox form-check-input" type="checkbox" id="inlineCheckbox5" value="Friday">
                                                            <label class=" ml-1 form-check-label" for="inlineCheckbox5">Friday(6)</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="ml-2 form-check form-check-inline">
                                                            <input class="new_promotion_checkbox form-check-input" type="checkbox" id="inlineCheckbox6" value="Saturday">
                                                            <label class=" ml-1 form-check-label" for="inlineCheckbox6">Saturday(7)</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <td>
                                                    <div class="ml-2 form-check form-check-inline">
                                                        <input class="new_promotion_checkbox form-check-input" type="checkbox" id="inlineCheckbox7" value="Sunday">
                                                        <label class=" ml-1 form-check-label" for="inlineCheckbox7">Sunday(CN)</label>
                                                    </div>
                                                </td>
                                            </table>
                                        </div>

                                        <div class="modal-footer mt-4">
                                            <button type="submit" class="btn btn-primary">Create</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        $('#start_at_new').attr('autocomplete','off');
                        $('#start_at_new').datetimepicker({
                            timepicker : false,
                            datepicker : true,
                            format: 'd-m-Y',
                            theme: "dark",
                            onShow: function(ct){
                                this.setOptions({
                                   /* maxDate: $('#end_at_new').val() ?  $('#end_at_new').val() : false*/
                                })
                            }
                        });

                        $('#end_at_new').attr('autocomplete','off');
                        $("#end_at_new").datetimepicker({
                            timepicker : false,
                            datepicker : true,
                            format: 'd-m-Y',
                            theme: "dark",
                            onShow: function(ct){
                                this.setOptions({
                                   /* minDate: $('#start_at_new').val() ? $('#start_at_new').val() : false*/
                                })
                            }
                        });

                    </script>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Start_at</th>
                            <th>End_at</th>
                            <th>Sale Percent (%)</th>
                            <th>Days</th>
                            <th class="action">Products approve</th>
                            <th class="action">Action</th>
                        </tr>
                        </thead>
                        <tbody  id="listPromotions">
                        </tbody>
                    </table>
                    <div class="text-center mb-2"  id="loadingPromotions" style="display: none;">
                        <img src="{{asset("images/loading.gif")}}" alt="loading..." style="margin-bottom: 70px">
                    </div>
                </div>
            </main>
            {{--Modal edit product--}}
            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Promotion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-2"  id="loading_modal" style="display: none;">
                                <img src="{{asset("images/loading.gif")}}" alt="loading..." style="margin-bottom: 190px; margin-top: 187px">
                            </div>
                            <form id="form_modal">
                                <div class="form-group mt-2">
                                    <label for="name">Name</label>
                                    <input id="name_edit" name="name" type="text" class="form-control" value="" placeholder="">
                                </div>
                                <div class="form-group mt-2" >
                                    <label for="salePriceEdit">Description</label>
                                    <textarea id="description_edit" name="description" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="start_at">Start At</label>
                                    <input  id="start_at_edit" name="start_at"  type="text" class="form-control" value="">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="end_at">End At</label>
                                    <input  id="end_at_edit" name="end_at"  type="text" class="form-control" value="`+item.end_at+`">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="sale_percent">Sale percent</label>
                                    <input   id="sale_percent_edit" min="1" max="100" name="sale_percent" type="number" class="sale_percent form-control" value="">
                                </div>
                                <div>
                                    <label for="sale_percent">Days Approve</label>
                                    <br>
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <div class="ml-2 form-check form-check-inline">
                                                    <input class="update_promotion_checkbox form-check-input" type="checkbox" id="inline_Checkbox1" value="Monday">
                                                    <label class="ml-1 form-check-label" for="inline_Checkbox1">Monday(2)</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="ml-2 form-check form-check-inline">
                                                    <input class="update_promotion_checkbox form-check-input" type="checkbox" id="inline_Checkbox2" value="Tuesday">
                                                    <label class="ml-1 form-check-label" for="inline_Checkbox2">Tuesday(3)</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="ml-2 form-check form-check-inline">
                                                    <input class="update_promotion_checkbox form-check-input" type="checkbox" id="inline_Checkbox3" value="Wenesday">
                                                    <label class=" ml-1 form-check-label" for="inline_Checkbox3">Wenesday(4)</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="ml-2 form-check form-check-inline">
                                                    <input class="update_promotion_checkbox form-check-input" type="checkbox" id="inline_Checkbox4" value="Thursday">
                                                    <label class=" ml-1 form-check-label" for="inline_Checkbox4">Thursday(5)</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="ml-2 form-check form-check-inline">
                                                    <input class="update_promotion_checkbox form-check-input" type="checkbox" id="inline_Checkbox5" value="Friday">
                                                    <label class=" ml-1 form-check-label" for="inline_Checkbox5">Friday(6)</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="ml-2 form-check form-check-inline">
                                                    <input class="update_promotion_checkbox form-check-input" type="checkbox" id="inline_Checkbox6" value="Saturday">
                                                    <label class=" ml-1 form-check-label" for="inline_Checkbox6">Saturday(7)</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <td>
                                            <div class="ml-2 form-check form-check-inline">
                                                <input class="update_promotion_checkbox form-check-input" type="checkbox" id="inline_Checkbox7" value="Sunday">
                                                <label class=" ml-1 form-check-label" for="inline_Checkbox7">Sunday(CN)</label>
                                            </div>
                                        </td>
                                    </table>
                                </div>

                                <div class="modal-footer mt-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                            <script>
                                $('#start_at_edit').attr('autocomplete','off');
                                $('#start_at_edit').datetimepicker({
                                    timepicker : false,
                                    datepicker : true,
                                    format: 'd-m-Y',
                                    theme: "dark",
                                    onShow: function(ct){
                                        this.setOptions({
                                            /* maxDate: $('#end_at_new').val() ?  $('#end_at_new').val() : false*/
                                        })
                                    }
                                });

                                $('#end_at_edit').attr('autocomplete','off');
                                $("#end_at_edit").datetimepicker({
                                    timepicker : false,
                                    datepicker : true,
                                    format: 'd-m-Y',
                                    theme: "dark",
                                    onShow: function(ct){
                                        this.setOptions({
                                            /* minDate: $('#start_at_new').val() ? $('#start_at_new').val() : false*/
                                        })
                                    }
                                });

                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--Show Products Approve--}}
        <div class="modal fade" id="modal_products" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="product_id_modal" class="modal-title">Products Approve</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class=" mt-2 mb-1 text-center pl-5 pr-5">
                        <form class="form-group" id="insert_ingredient_form">
                                            dsadsadsadasdsa
                            <button type="submit" class="btn btn-primary w-25">Add</button>
                        </form>
                    </div>
                    <div class="text-center mb-2"  id="loading_modal_product" style="display: none;">
                        <img src="{{asset("images/loading.gif")}}" alt="loading..." style="margin-bottom: 190px; margin-top: 187px">
                    </div>
                </div>
            </div>
        </div>
    </div>



    {
@endsection
