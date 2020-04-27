let table_id_modal = '';
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


    result +=                    `<td>`+item.billing_at+`</td>
                        <td>`+item.receipt_at+`</td>
                        <td>`+item.export_at+`</td>
                        <td>`+item.sale_excluded_price+`</td>
                        <td>`+item.sale_included_price+`</td>
                        <td>
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

$(document).ready(function () {
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
});

$(window).on('load', function () {
    loadList();
});
