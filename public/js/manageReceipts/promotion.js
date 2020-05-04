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
                                <td>`+item.sale_percent * 100+`</td>
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
    /*let  modal = `
                    <div class="form-group mt-2">
                        <label for="name">Name</label>
                        <input name="name" type="text" class="form-control" value="`+item.name+`" placeholder="Name">
                    </div>
                    <div class="form-group mt-2" >
                        <label for="salePriceEdit">Description</label>
                        <textarea name="note" class="form-control" rows="3">`+item.description+`</textarea>
                    </div>
                    <div class="form-group mt-2">
                        <label for="start_at">Start At</label>
                        <input  id="start_at_edit" name="start_at"  type="text" class="form-control" value="`+item.start_at+`">
                    </div>
                    <div class="form-group mt-2">
                        <label for="end_at">End At</label>
                        <input  id="end_at_edit" name="end_at"  type="text" class="form-control" value="`+item.end_at+`">
                    </div>
                    <div class="form-group mt-2">
                        <label for="sale_percent">Sale percent</label>
                        <input   min="1" max="100" name="sale_percent" type="number" class="sale_percent form-control" value="">
                    </div>
                 <div class="modal-footer mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
                 `;*/
    $('#name_edit').val(item.name);
    $('#description_edit').val(item.description);
    $('#start_at_edit').val(item.start_at);
    $('#end_at_edit').val(item.end_at);
    $('#sale_percent_edit').val(item.sale_percent*100);
    $('#form_modal').show();
    $('#loading_modal').removeAttr("style").hide();
}

$(document).ready(function () {
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
            axios.post(location.origin +'/axios/promotions' , formData, config)
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
