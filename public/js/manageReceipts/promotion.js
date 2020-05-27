function addText(item){
    var result= ``;
    result =  `      <tr id="`+item.id+`">
                                <td>`+item.id+`</td>
                                <td>`+item.name+`</td>
                                <td>
                                    <textarea readonly class="form-control" rows="3">`+item.description+`</textarea>
                                </td>
                                <td>`+item.start_at+`</td>
                                <td>`+item.end_at+`</td>
                                <td>`+item.sale_percent * 100+`%</td>
                                <td>
                                    <textarea readonly class="form-control" rows="3">`+item.days+`</textarea>
                                </td>
                                <td>
                                    <button name='`+item.id+`'class="products ml-2 btn btn-outline-info">
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
                          `;
    return result;
}

function loadList(){
    $('#listPromotions').empty();
    $('#loadingPromotions').show();
    axios.get(location.origin + '/promotions?ajax='+true
    ).then(function (response) {
        let result = '';
        response.data.promotions.forEach(function (promotion){
            result += addText(promotion);
        });
        $('#listPromotions').append(result);
        $('#loadingPromotions').removeAttr("style").hide();
    });
}

function edit_modal(item){
    $('.update_promotion_checkbox').removeAttr('checked');
    if (item.days){
        console.log(item.days);
        item.days.split(",").forEach(function (day) {
             $('.update_promotion_checkbox[value="'+day.replace('"','')+'"]').prop('checked', true);
            console.log(day.replace('"',''));
        })
    }
    $('#name_edit').val(item.name);
    $('#description_edit').val(item.description);
    $('#start_at_edit').val(item.start_at);
    $('#end_at_edit').val(item.end_at);
    $('#sale_percent_edit').val(item.sale_percent*100);
    $('#form_modal').show();
    $('#loading_modal').removeAttr("style").hide();
}

function edit_products_modal(item, item_orther, promotion) {
    let result = `
                    <button name=`+promotion.id+` type="submit" class="save_product btn btn-primary w-50 mt-2 mb-3">Save</button>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col">Product Name</th>
                          <th scope="col">In Used</th>
                        </tr>
                      </thead>
                      <tbody>

`;
    item.forEach(function (product){
        result += `<tr>
                      <td>`+product.name+`</td>
                      <td>
                        <input name="`+product.id+`" type="checkbox" class="checkbox_product form-check-input"  checked>
                      </td>
                    </tr>`;
    });

    item_orther.forEach(function (product){
        result += `<tr>
                      <td>`+product.name+`</td>
                      <td>
                        <input name="`+product.id+`" type="checkbox" class="form-check-input">
                      </td>
                    </tr>`;
    });

    result+= `</table>
              <button name=`+promotion.id+`  type="submit" class="save_product btn btn-primary w-50">Save</button>
                `;
    $('#insert_product_form').append(result);

}

$(document).ready(function () {
    /*Products List*/
    jQuery(document).on('click',".products",function () {
        let promotion_id = this.name;
        $('#insert_product_form').empty();
        $('#loading_modal_product').show();
        $('#modal_products').modal('show');
        axios.get(location.origin + '/axios/promotions/showProducts/'+promotion_id
        ).then(function (response) {
            edit_products_modal(response.data.promotion.product_list, response.data.product_orther, response.data.promotion);
            $('#loading_modal_product').removeAttr("style").hide();
        });
    })

    /*Uppdate products*/

    jQuery(document).on('click',".save_product",function () {
        $('#insert_product_form').submit(function(e) {
            e.preventDefault();
        });
        let promotion_id = this.name;
        let products = [];
        $('.form-check-input:checked')
            .each(function () {
                products.push($(this).attr('name'));
            });
        const config = {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        };
        axios.post(location.origin +'/axios/promotions/updateProducts/'+promotion_id , {
            products
        }, config)
            .then(function (response) {
            $('#insert_product_form').empty();
            $('#modal_products').modal('hide');
            toastr.success("Saved Successfully");
            })
            .catch(function (error) {
                toastr.error("Saved Fails");
            })
    })
    /*Delete Table*/
    jQuery(document).on('click',".delete",function () {
        let promotion_id = this.name;
        $.confirm({
            title: 'Confirm',
            content: 'Are you sure ?',
            buttons: {
                Yes: {
                    btnClass: 'btn-success',
                    action : function () {
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
                        axios.delete(location.origin + '/axios/promotions/'+promotion_id)
                            .then(function (response) {
                                loadList();
                                toastr.success("Deleted Successfully");
                            })
                            .catch(function (error) {
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

    /*New Promotion*/
    $('#newPromotionButton').click(function () {
        $('#newPromotionModal').modal('show');
    });

    $('#newPromotionForm').submit(function(e) {
        e.preventDefault();
    }) .validate({
        rules: {
            name: {
                required: true,
                maxlength: 255
            },
            description: {
                required: true,
            },
            start_at:{
                required: true
            },
            end_at: {
                required: true
            },
            sale_percent: {
                required: true,
                min: 1,
                max: 100,
            }
        },
        messages: {
            name: {
                required: "Please enter the name",
                maxlength: "Max length is 255 characters"
            },
        }, submitHandler: function (form) {
            var formData = new FormData(form);
            const config = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            };
            let days = [];
            $('.new_promotion_checkbox:checked')
                .each(function () {
                    days.push($(this).attr('value'));
                });
            formData.append('days', days);
            axios.post(location.origin +'/axios/promotions' ,
                formData
                , config)
                .then(function (response) {
                    loadList();
                    $('#newPromotionForm').trigger("reset");
                    $('#newPromotionModal').modal('hide');
                    toastr.success("Created Successfully");
                })
                .catch(function (error) {
                    toastr.error("Create Fails");
                })
        }
    });

    /*Edit*/
    jQuery(document).on('click',".edit",function () {
        promotion_id_modal = this.name;
        $('#form_modal').removeAttr("style").hide();
        $('#loading_modal').show();
        $('#modal').modal('show');
        axios.get(location.origin + '/axios/promotions/'+ promotion_id_modal
        ).then(function (response) {
            $('#loading_modal').removeAttr("style").hide();
            edit_modal(response.data.promotion);
        })
    });

    /*Form Edit Submit*/
    $("#form_modal")
        .submit(function(e) {
            e.preventDefault();
        })
        .validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255
                },
                description: {
                    required: true,
                },
                start_at:{
                    required: true
                },
                end_at: {
                    required: true
                },
                sale_percent: {
                    required: true,
                    min: 1,
                    max: 100,
                }
            },
            messages: {
                name: {
                    required: "Please enter the name",
                    maxlength: "Max length is 255 characters"
                },
            },
            submitHandler:  function(form) {
                var formData = new FormData(form);
                const config = {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                };
                let  days = [];
                $('.update_promotion_checkbox:checked')
                    .each(function () {
                        days.push($(this).attr('value'));
                    });
                formData.append('days', days);

                axios.post(location.origin +'/axios/promotions/'+promotion_id_modal, formData, config)
                    .then(function (response) {
                        loadList();
                        $('#modal').modal('hide');
                        toastr.success("Updated Successfully");
                    })
                    .catch(function (error) {
                        $('#modal').modal('hide');
                        toastr.error("Updated Fails");
                    });
            }
        });
});

$(window).on('load', function () {
    loadList();
});
