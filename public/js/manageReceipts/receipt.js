let table_id_modal = '';
/*Format Number*/
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')+ 'đ';
}

function addText(item){
    var result= ``;
    result =  `      <tr id="`+item.id+`">
                        <td>`+item.user_name+`</td>
                        <td>`+item.table_name+`</td>`
            if(item.status == 3 ) {
                result +=  `<td>Paid</td>`;
            }
            else{
                if (item.status == 1){
                    result +=  `<td>Waiting</td>`;
                }
                else {
                    result +=  `<td>Unpaid</td>`;
                }
            }


    result +=          `<td>

                            <button name='`+item.id+`' class="billing btn btn-outline-success mr-1">
                                <ti class ='ti-write'></ti>
                             </button>
                             <div name="`+item.id+`" class="text-center mb-2 loadingBilling"  style="display: none;">
                                <img src="`+location.origin+`/images/loading.gif" alt="loading..." class="mr-1" style="width: 45px; height: 45px;">
                            </div>
                            `+((item.billing_at) ? (item.billing_at) : (''))+`
                        </td>
                        <td>
                            <div name="`+item.id+`" class="text-center mb-2 loadingReceipt"  style="display: none;">
                                <img src="`+location.origin+`/images/loading.gif" alt="loading..."  class="mr-1" style="width: 45px; height: 45px;">
                            </div>
                            <button  name='`+item.id+`'class="receipt btn btn-outline-success mr-1">
                                <ti class ='ti-receipt'></ti>
                             </button>`+ ((item.receipt_at) ? (item.receipt_at) : ('')) +`
                        </td>
                        <td class="pt-3">
                            `+
                            ((item.export_at) ? (item.export_at) : ('--'))+`</td>
                        <td>`+formatNumber(item.sale_excluded_price)+`</td>
                        <td>`+ ((item.sale_included_price == item.sale_excluded_price) ? ('--') : (formatNumber(item.sale_included_price))) +`</td>
                        <td>
                            <button name="`+item.id+`"  class="manage_products btn btn-info mb-1">
                                Products
                            </button>
                            <button name="`+item.id+`"  class="delete btn btn-danger mb-1" style="width: 75px">
                                Delete
                            </button>
                        </td>
                    </tr>`;

    return result;
}

function loadList(){
    $('#listReceipts').empty();
    $('#loading').show();
    axios.get(location.origin + '/receipts?ajax='+true
    ).then(function (response) {
        let result = '';
        response.data.receipts.forEach(function (receipt){
            result += addText(receipt);
        });
        $('#listReceipts').append(result);
        $('#loading').removeAttr("style").hide();
    });
}

async function loadListReceiptFillter(from = $('#fromFillter').val(), to= $('#toFillter').val()) {
    if (from && to ){
        $("#listReceiptFillter").empty();
        $('#export').attr("disabled", true);
        $('#loadingFillter').show();
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
         axios.post(location.origin + '/axios/getListReceiptFillter', {
            from,
            to
        })
            .then(function (response) {
                let resultFillter = ``;
                response.data.receipts.forEach(function (receipt) {
                    resultFillter += addText(receipt);
                });
                $("#listReceiptFillter").empty();
                $("#listReceiptFillter").append(resultFillter);
                $('#loadingFillter').removeAttr("style").hide();
                $('#export').removeAttr('disabled');
            })
            .catch(function (error) {
                toastr.warning("Could Not Found");
                $('#loadingFillter').removeAttr("style").hide();
                $('#export').attr("disabled", true);
            });
    }
}
function edit_modal(item){
    let  modal = `
                    <div class="form-group mt-2">
                        <label for="name">Name</label>
                        <input name="name" type="text" class="form-control" value="`+item.name+`" placeholder="Name">
                    </div>
                    <div class="form-group mt-2" >
                        <label for="salePriceEdit">Note</label>
                        <textarea name="note" class="form-control" rows="3">`+item.note+`</textarea>
                    </div>
                    <div class="form-group mt-2 mb-1">
                        <label for="sale_price">Status</label>
                        <select class="form-control" name="status"> `;
    if(item.status == 'Empty'){
        modal +=  `<option value="Empty" selected>Empty</option>
                    <option value="Using">Using</option>`;
    }
    else {
        modal +=  `<option value="Empty" >Empty</option>
                    <option value="Using" selected>Using</option>`;
    }

    modal += `</select>
                </div>
                 <div class="modal-footer mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
                 `;
    $('#form_modal').append(modal);
}


function edit_products_modal(foods, drinks, receipt) {
    let result = ((receipt.status != 3) ? ('<button name='+receipt.id+'  type="submit" class="save_product btn btn-primary w-50 mb-2">Save</button>') : (''))+`
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col">Product Name</th>
                          <th scope="col">Type</th>
                          `+((receipt.status == 3) ? ('<th scope="col">Price (Old)</th>') : ('<th scope="col">Price</th>'))+`
                          `+((receipt.status == 3) ? ('<th scope="col">Sale_price (Old)</th>') : ('<th scope="col">Sale_price</th>'))
                          +`<th scope="col">Promotion Adding</th>
                          <th scope="col">Quantity</th>
                          <th scope="col">Note</th>
                          `+((receipt.status != 3) ? ('<th scope="col">Choice</th>') : (''))+`
                        </tr>
                      </thead>
                      <tbody>

`;
    result+= `
            <tr>
                <td colspan="8" style="background-color:#8b17ea; color: white; font-size: 20px">Drink</td>
            </tr>
    `;
    drinks.forEach(function (drink){
        result += `<tr>
                      <td>`+drink.name+`</td>
                      <td>`+drink.type+`</td>
                      <td>
                      `+((receipt.status == 3) ? ((drink.pivot) ? (drink.pivot.product_price) : (drink.price)) : (drink.price))+`
                      </td>
                      <td>
                      `+((receipt.status == 3)  ? ((drink.pivot) ? (((drink.pivot.product_sale_price) && (drink.pivot.product_sale_price != drink.pivot.product_price)) ? (drink.pivot.product_sale_price) : ('--')) : ('--')) : ((drink.sale_price) ? (drink.sale_price) : ('--')))+`
                      </td>
                      <td>
                      `+((drink.promotion_today) && (receipt.status != 3) ? (drink.promotion_today.id+'. '+drink.promotion_today.name) : ('--'))+`
                      </td>
                      <td>
                            <input id="quantity_`+drink.id+`" style="width: 50px" type="number" class="form-control" value="`+((drink.pivot) ? ((drink.pivot.quantity) ? (drink.pivot.quantity) : (0)) : (0))+`"`+((drink.pivot) ? ('readonly') : (''))+`>
                      </td>
                      <td>
                            <input id="note_`+drink.id+`" style="width: 100%"  type="text" class="form-control" maxlength="255" value="`+((drink.pivot) ? ((drink.pivot.note) ? (drink.pivot.note) : ('')) : (''))+`"`+((drink.pivot) ? ('readonly') : (''))+`>
                      </td>
                         `+((receipt.status != 3) ? (' <td> <input  class="checkbox_product" name="'+drink.id+'" type="checkbox" class="form-check-input"  '+(((drink.pivot) &&(drink.pivot.quantity > 0)) ? ('checked') : (''))+' > </td>')  : (''))+`
                    </tr>`;
    });
    result+= `
            <tr>
                <td colspan="8" style="background-color:#8b17ea; color: white; font-size: 20px">Food</td>
            </tr>
    `;

    foods.forEach(function (food){
        result += `<tr>
                      <td>`+food.name+`</td>
                      <td>`+food.type+`</td>
                      <td>
                      `+((receipt.status == 3) ? ((food.pivot) ? (food.pivot.product_price) : (food.price)) : (food.price))+`
                      </td>
                      <td>
                      `+((receipt.status == 3)  ? ((food.pivot) ? (((food.pivot.product_sale_price) && (food.pivot.product_sale_price != food.pivot.product_price)) ? (food.pivot.product_sale_price) : ('--')) : ('--')) : ((food.sale_price) ? (food.sale_price) : ('--')))+`
                      </td>
                      <td>
                      `+((food.promotion_today) && (receipt.status != 3) ? (food.promotion_today.id+'. '+food.promotion_today.name) : ('--'))+`
                      </td>
                      <td>
                            <input id="quantity_`+food.id+`" style="width: 50px" type="number" class="form-control" value="`+((food.pivot) ? ((food.pivot.quantity) ? (food.pivot.quantity) : (0)) : (0))+`"`+((food.pivot) ? ('readonly') : (''))+`>
                      </td>
                      <td>
                            <input id="note_`+food.id+`" style="width: 100%"  type="text" class="form-control" maxlength="255" value="`+((food.pivot) ? ((food.pivot.note) ? (food.pivot.note) : ('')) : (''))+`"`+((food.pivot) ? ('readonly') : (''))+`>
                      </td>
                          `+((receipt.status != 3) ? ('<td> <input  class="checkbox_product" name="'+food.id+'" type="checkbox" class="form-check-input"  '+(((food.pivot) &&(food.pivot.quantity > 0)) ? ('checked') : (''))+' > </td>')  : (''))+`
                    </tr>`;
    });
    result+= `</table>
                `+((receipt.status != 3) ? ('<button name='+receipt.id+'  type="submit" class="save_product btn btn-primary w-50">Save</button>') : (''));
    $('#insert_product_form').append(result);

}

$(document).ready(function () {
    /*Action edit product*/
    jQuery(document).on('click',".checkbox_product",function () {
        let product_id = this.name;
        if ($('#quantity_'+product_id).val() > 0){
            if (this.checked) {
                $('#quantity_'+product_id).prop('readonly', true);
                $('#note_'+product_id).prop('readonly', true);
            }
            else {
                $('#quantity_'+product_id).prop('readonly', false);
                $('#note_'+product_id).prop('readonly', false);
            }
        }else {
            $(this).attr('checked', false);
        }

    })
    /*Save product list*/
    jQuery(document).on('click',".save_product",function () {
        $('#insert_product_form').submit(function(e) {
            e.preventDefault();
        });
        let receipt_id = this.name;
        let products = [];
        let product = {
            id : null,
            quantity : null,
            note : null
        };
        $('.checkbox_product:checked')

            .each(function () {
                product = {
                    id : null,
                    quantity : null,
                    note : null
                };

                product.id = this.name;
                product.quantity = $('#quantity_'+product.id).val();
                product.note = $('#note_'+product.id).val();
                products.push(product);
            });

        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        axios.post(location.origin + '/axios/receipts/updateProductInReceipt/'+receipt_id,{
            products
        }).then(function (response) {
            $('#modal_products').modal('hide');
            loadListReceiptFillter();
            loadList();
            toastr.success("Update Successfully !");
            let link = document.createElement('a');
            link.href = response.data.host+response.data.url;
            link.setAttribute("download",  'Order-'+response.data.url);
            link.click();
            link.remove();
            printJS(response.data.host+response.data.url);
        }).catch(function (error) {
            $('#modal_products').modal('hide');
            toastr.error("Update Fails !");
        })
        ;

    })
    /*Show All Products*/
    jQuery(document).on('click',".manage_products",function () {
        let receipt_id = this.name;
        $('#insert_product_form').empty();
        $('#loading_modal_product').show();
        $('#modal_products').modal('show');
        axios.get(location.origin + '/axios/receipts/'+receipt_id
        ).then(function (response) {
            edit_products_modal(response.data.foods, response.data.drinks, response.data.receipt);
            $('#loading_modal_product').removeAttr("style").hide();
        })
    })
    /*Delete*/
    jQuery(document).on('click',".delete",function () {
        let receipt_id = this.name;
        $.confirm({
            title: 'Confirm',
            content: 'Are you sure ?',
            buttons: {
                Yes: {
                    btnClass: 'btn-success',
                    action : function () {
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
                        axios.delete(location.origin + '/axios/receipts/'+receipt_id).then(function (response) {
                            loadListReceiptFillter();
                            loadList();
                            toastr.success("Deleted Successfully");
                        }).catch(function (error) {
                            toastr.error("Delete Fails");
                        })
                    }
                },
                No: {
                    btnClass: 'btn-danger',
                    action :function () {
                    }
                }
            }
        });
    });

    /*Export*/
    $("#exportReceiptFillter").submit(function (e) {
        e.preventDefault();
    })
    $("#export").click(function () {
        toFillter = $('#toFillter').val();
        fromFillter = $("#fromFillter").val();
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        axios.post(location.origin + '/axios/receipts/export',{
            toFillter,
            fromFillter
        }).then(function (response) {
            let blob = new Blob(["\ufeff", response.data], { type: 'application/csv' });
            let link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'ListReceipt('+fromFillter+'-To-'+toFillter+').csv';
            link.click();
            link.remove();
        });
    });

    jQuery(document).on('click',".billing",function () {
        let receipt_id = this.name;
        $(this).removeAttr("style").hide();
        $(".loadingBilling[name='"+receipt_id+"']").css('display', 'inline-block');
        axios.get(location.origin + '/axios/receipts/billing/'+receipt_id)
            .then(function (response) {
                loadListReceiptFillter();
                loadList();
                toastr.success("Billing Successfully !");
                let link = document.createElement('a');
                link.href = response.data.host+response.data.bill;
                link.setAttribute("download",  'Bill-'+response.data.receipt.id+'.pdf');
                link.click();
                link.remove();
                printJS(response.data.host+response.data.bill);
        })
            .catch(function (error) {
                $(".loadingBilling[name='"+receipt_id+"']").removeAttr("style").hide();
                $(".billing[name='"+receipt_id+"']").show();
                if (error.response.status == 400) {
                    toastr.error('The Receipt Had been payed');
                }else{
                    toastr.error('Billing Fails');
                }
        })
    });

    jQuery(document).on('click',".receipt",function () {
        let receipt_id = this.name;
        $(this).removeAttr("style").hide();
        $(".loadingReceipt[name='"+receipt_id+"']").css('display', 'inline-block');
        axios.get(location.origin + '/axios/receipts/receipt/'+receipt_id)
            .then(function (response) {
                loadListReceiptFillter();
                loadList();
                toastr.success("Receipt Successfully !");
                let link = document.createElement('a');
                link.href = response.data.host+response.data.paid;
                link.setAttribute("download",  'Receipt-'+response.data.receipt.id+'.pdf');
                link.click();
                link.remove();
                printJS(response.data.host+response.data.paid);
            })
            .catch(function (error) {
                $(".loadingReceipt[name='"+receipt_id+"']").removeAttr("style").hide();
                $(".receipt[name='"+receipt_id+"']").show();
                if (error.response.status == 400) {
                    toastr.error('The Receipt Had not been billed');
                }else{
                    toastr.error('Receipt Fails');
                }
            })
    });
});

$(window).on('load', function () {
    loadList();
});
