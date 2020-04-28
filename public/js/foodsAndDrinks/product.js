let material_id = null;
var product_id_modal = null;
function addText(item){
    var result= ``;
    result =  `      <tr id="`+item.id+`">
                                <td>`+item.id+`</td>
                                <td>`+item.name+`</td>
                                <td>`+item.price+`</td>
                                <td>`+item.sale_price+`</td>
                                <td>`+item.promotion_id+`</td>
                                <td>
                                    <img style="width: 60px; height: 60px" src="`+location.origin+`/images/products/`+item.url+`" alt="image_product">
                                </td>
                                <td>
                                    <button name='`+item.id+`'class="ingredient ml-2 btn btn-outline-info">
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

function edit_modal(item, promotions){
    let  modal = `
                    <div class="form-group mt-2">
                        <label for="nameEdit">Name</label>
                        <input name="name" type="text" class="form-control" id="nameEdit" value="`+item.name+`" placeholder="Name">
                    </div>
                    <div class="form-group mt-2">
                        <label for="priceEdit">Price</label>
                        <input name="price" type="number" class="form-control" value="`+item.price+`" id="priceEdit">
                    </div>
                    <div class="form-group mt-2" >
                        <label for="salePriceEdit">Sale Price</label>
                        <input name="sale_price" class="form-control" type="number" id="salePriceEdit" value="`+item.sale_price+`" readonly>
                    </div>
                    <div class="form-group mt-2" >
                        <label for="promotionEdit">Promotion</label>
                         <select class="form-control" name="promotion_id" id="">
                             <option value="" selected>Not Setting</option>
                                `;

    promotions.forEach(function (promotion) {


        if (promotion.id == item.promotion_id){
            modal+= `<option value="`+promotion.id+`" selected>`+promotion.id+` : `+promotion.name+`</option>`
        }
        else {
            modal+= `<option value="`+promotion.id+`">`+promotion.id+` : `+promotion.name+`</option>`
        }
    });
          modal+=      `
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <label for="sale_price">Type</label>
                        <select class="form-control" name="type" id="">
                        `;
    if (item.type == 'Drink'){
        modal+= `  <option value="Drink" selected>Drink</option>
                   <option value="Food">Food</option>`;
    }
    else {
        modal+= `  <option value="Drink">Drink</option>
                   <option value="Food" selected>Food</option>`;
    }

            modal+= `
                        </select>
                    </div>
                    <div class="form-group mt-2 mb-2" >
                        <label style="cursor: pointer;" for="urlEdit">Image(only extension: PNG JPG JPEG)</label>
                        <input  onchange="readURL(this);"  accept="image/*" name="url" class="form-control-file border" type="file" id="urlEdit" value="`+item.url+`">
                    </div>
                    <div class="form-group mt-2 ml-2 text-center">
                        <img class="img_edit" style="height: 150px; width: 150px" src="`+location.origin +`/images/products/`+item.url+`" alt="images_product">
                    </div>
                    <div class="modal-footer mt-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                `;
    $('#form_modal').append(modal);
}


function editIngredient(ingredient, item) {
    let result = ``;
    result +=`
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Material ID</th>
                                        <th>Material Name</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>`;
    ingredient.forEach(function (material) {
        result+= `                    <tr>
                                        <td>`+material.material_id+`</td>
                                        <td>`+material.material_name+`</td>
                                        <td>`+material.quantity+`</td>
                                        <td>`+material.unit+`</td>
                                        <td>
                                            <button name="`+material.material_id+`" class="ingredient_delete btn-danger btn ">Delete</button>
                                        </td>
                                      </tr>`;
    });

    result +=     `            </tbody>
                            </table>

                       `;
    $('#modal_edit_ingredient').empty().append(result);
}
function loadList(){
    $('#listDrinks').empty();
    $('#listFoods').empty();
    $('#loadingDrinks').show();
    $('#loadingFoods').show();
    axios.get(location.origin + '/products?ajax='+true
    ).then(function (response) {
        let result = '';
        response.data.drinks.forEach(function (drink){
            result += addText(drink);
        });
        $('#listDrinks').append(result);
        $('#loadingDrinks').removeAttr("style").hide();
        result = '';
        response.data.foods.forEach(function (food){
            result += addText(food);
        });
        $('#listFoods').append(result);
        $('#loadingFoods').removeAttr("style").hide();
    });
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.img_edit')
                .attr('src', e.target.result);

        };
        console.log(input.files[0]);
        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function () {
    /*Edit Ingredient*/
    jQuery(document).on('click',".ingredient",function () {
         product_id = this.name;
         $('#modal_edit_ingredient').empty();
         $('#loading_modal_ingredient').show();
         $('#modal_ingredient').modal('show');
        $('#nameIngredient').empty();
         //-------------
        axios.get(location.origin + '/axios/products/'+product_id
        ).then(function (response) {
            $('#product_id_modal').attr('name', product_id);
            $('#loading_modal_ingredient').removeAttr("style").hide();
            response.data.ingredient_orther.forEach(function (ingredient){
              $('#nameIngredient').append('<option class="ingredient_item" name="'+ingredient.id+'" value="'+ingredient.id+'">'+ingredient.name+'</option>')
            });
            editIngredient(response.data.product.ingredients,response.data.product);
        })
    });

    /*Edit*/
    jQuery(document).on('click',".edit",function () {
        product_id_modal = this.name;
        $('#loading_modal').show();
        $('#form_modal').empty();
        $('#modal').modal('show');
        axios.get(location.origin + '/axios/products/'+ product_id_modal
        ).then(function (response) {
            $('#loading_modal').removeAttr("style").hide();
            edit_modal(response.data.product, response.data.promotions);
        })

    });
    /*Form Edit Ingredient Submit*/
    $("#insert_ingredient_form")
        .submit(function(e) {
            e.preventDefault();
        })
        .validate({
            rules: {
                material_id: {
                    required: true
                },
                quantity: {
                    required: true,
                    digits: true,
                },
                unit: {
                    required: true,
                },

            },
            messages: {
                material_id: {
                    required: 'Please choose material'
                },
                quantity: {
                    required: 'Please enter the quantity',
                    digits: 'Only accept to number',
                },
                unit: {
                    required: 'Please enter the unit',
                },
            },
            submitHandler:  function(form) {
                let product_id = $('#product_id_modal').attr('name');
                var formData = new FormData(form);
                $('#modal_edit_ingredient').removeAttr("style").hide();
                $('#loading_modal_ingredient').show();
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                };
                axios.post(location.origin +'/axios/products/updateIngredient/'+product_id, formData, config)
                    .then(function (response) {
                        $("#insert_ingredient_form").trigger("reset");
                        $('#nameIngredient').empty();
                        $('#loading_modal_ingredient').removeAttr("style").hide();
                        response.data.ingredient_orther.forEach(function (ingredient){
                            $('#nameIngredient').append('<option class="ingredient_item" name="'+ingredient.id+'" value="'+ingredient.id+'">'+ingredient.name+'</option>')
                        });
                        editIngredient(response.data.product.ingredients,response.data.product);
                        $('#modal_edit_ingredient').show();
                        toastr.success("Updated Successfully");
                    })
                    .catch(function (error) {
                        $("#insert_ingredient_form").trigger("reset");
                        $('#modal_edit_ingredient').show();
                        $('#loading_modal_ingredient').removeAttr("style").hide();
                        toastr.error("Updated Fails");
                    });
            }
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
                price: {
                    required: true,
                    digits: true,
                },
                url: {
                    required: false,
                    extension: "jpg|png|jpeg"
                }
            },
            messages: {
                name: {
                    required: "Please enter the name",
                    maxlength: "Max length is 255 characters"
                },
                price: {
                    required: "Please enter the price",
                    digits: "Input is only accepted digits",
                },
                url : {
                    required: false,
                    extension: "Extension is only accepted jpg-png-jpeg"
                }
            },
            submitHandler:  function(form) {
                var formData = new FormData(form);
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                };
                axios.post(location.origin +'/axios/products/'+product_id_modal, formData, config)
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
    /*Delete Ingredient */
    //ingredient_delete
    jQuery(document).on('click',".ingredient_delete",function () {
        let product_id = $('#product_id_modal').attr('name');;
        let material_id = this.name;
        $.confirm({
            title: 'Confirm',
            content: 'Are you sure ?',
            buttons: {
                Yes: {
                    btnClass: 'btn-success',
                    action : function () {
                        $('#modal_edit_ingredient').removeAttr("style").hide();
                        $('#loading_modal_ingredient').show();
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
                        axios.delete(location.origin + '/axios/products/deleteIngredient/'+product_id+'/'+material_id)
                            .then(function (response) {
                                $('#nameIngredient').empty();
                                $('#loading_modal_ingredient').removeAttr("style").hide();
                                response.data.ingredient_orther.forEach(function (ingredient){
                                    $('#nameIngredient').append('<option class="ingredient_item" name="'+ingredient.id+'" value="'+ingredient.id+'">'+ingredient.name+'</option>')
                                });
                                editIngredient(response.data.product.ingredients,response.data.product);
                                $('#modal_edit_ingredient').show();
                                toastr.success("Deleted Successfully");
                            })
                            .catch(function (error) {
                                $('#modal_edit_ingredient').show();
                                $('#loading_modal_ingredient').removeAttr("style").hide();
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
    /*Delete Product*/
    jQuery(document).on('click',".delete",function () {
        let product_id = this.name;
        $.confirm({
            title: 'Confirm',
            content: '<div class="text-danger">It may be effect to the ingredient\'s product </div><br> Are you sure ? <br> ',
            buttons: {
                Yes: {
                    btnClass: 'btn-success',
                    action : function () {
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
                        axios.delete(location.origin + '/axios/products/'+product_id)
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


    /*New Product*/
    $('#newProductButton').click(function () {
        $('#newProductModal').modal('show');
    });

    $('#newProductForm').submit(function(e) {
        e.preventDefault();
    }) .validate({
        rules: {
            name: {
                required: true,
                maxlength: 255
            },
            price: {
                required: true,
                digits: true,
            },
            url: {
                required: false,
                extension: "jpg|png|jpeg"
            }
        },
        messages: {
            name: {
                required: "Please enter the name",
                maxlength: "Max length is 255 characters"
            },
            price: {
                required: "Please enter the price",
                digits: "Input is only accepted digits",
            },
            url : {
                required: false,
                extension: "Extension is only accepted jpg-png-jpeg"
            }
        }, submitHandler: function (form) {
            var formData = new FormData(form);
            const config = {
                headers: {
                    'content-type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            };
            axios.post(location.origin +'/axios/products' , formData, config)
                .then(function (response) {
                    loadList();
                    $('#newProductForm').trigger("reset");
                    $('#newProductModal').modal('hide');
                    toastr.success("Created Successfully");
                })
                .catch(function (error) {
                    toastr.error("Create Fails");
                })
        }
    });
});

$(window).on('load', function () {
    loadList();
});
