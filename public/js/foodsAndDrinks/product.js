let material_id = null;
let product_id_modal = null;
function addText(item){
    var result= ``;
    result =  `      <tr id="`+item.id+`">
                                <td>`+item.id+`</td>
                                <td>`+item.name+`</td>
                                <td>`+item.price+`</td>
                                <td>`+item.sale_price+`</td>
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

function edit_modal(item){
    let  modal = `
                    <div class="form-group mt-2">
                        <label for="nameEdit">Name</label>
                        <input name="nameEdit" type="text" class="form-control" id="nameEdit" value="`+item.name+`" placeholder="Name">
                    </div>
                    <div class="form-group mt-2">
                        <label for="priceEdit">Price</label>
                        <input name="priceEdit" type="number" class="form-control" value="`+item.price+`" id="priceEdit">
                    </div>
                    <div class="form-group mt-2" >
                        <label for="salePriceEdit">Sale Price</label>
                        <input name="salePriceEdit" class="form-control" type="number" id="salePriceEdit" value="`+item.sale_price+`">
                    </div>
                    <div class="form-group mt-2 mb-2" >
                        <label style="cursor: pointer;" for="urlEdit">Image(only extension: PNG JPG JPEG)</label>
                        <input  onchange="readURL(this);"  accept="image/*" name="urlEdit" class="form-control-file border" type="file" id="urlEdit" value="`+item.url+`">
                    </div>
                    <div class="form-group mt-2 ml-2 text-center">
                        <img class="img_edit" style="height: 150px; width: 150px" src="`+location.origin +`/images/products/`+item.url+`" alt="images_product">
                    </div>
                    <div class="modal-footer mt-4">
                        <button type="submit" class="btn btn-primary">Create</button>
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
                                    </tr>
                                </thead>
                                <tbody>`;
    ingredient.forEach(function (material) {
        result+= `                    <tr>
                                        <td>`+material.material_id+`</td>
                                        <td>`+material.material_name+`</td>
                                        <td>`+material.quantity+`</td>
                                        <td>`+material.unit+`</td>
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
    /*See Ingredient*/
    jQuery(document).on('click',".ingredient",function () {
         product_id = this.name;
         $('#modal_edit_ingredient').empty();
         $('#loading_modal_ingredient').show();
         $('#modal_ingredient').modal('show');
         //-------------
        axios.get(location.origin + '/axios/products/'+product_id
        ).then(function (response) {
            $('#loading_modal_ingredient').removeAttr("style").hide();
            editIngredient(response.data.product.ingredients,response.data.product);
        })
    });

    /*Edit*/
    jQuery(document).on('click',".edit",function () {
        product_id_modal = this.name;
        $('#loading_modal').show();
        $('#form_modal').empty();
        $('#modal').modal('show');
        axios.get(location.origin + '/axios/products/'+product_id_modal
        ).then(function (response) {
            $('#loading_modal').removeAttr("style").hide();
            edit_modal(response.data.product);
        })

    });

    /*Form Edit Submit*/
    $("#form_modal")
        .submit(function(e) {
            e.preventDefault();
        })
        .validate({
            rules: {
                nameEdit: {
                    required: true,
                    maxlength: 255
                },
                priceEdit: {
                    required: true,
                    digits: true,
                },
                salePriceEdit: {
                    required: false,
                    digits: true,
                },
                urlEdit : {
                    required: false,
                    extension: "jpg|png|jpeg"
                }
            },
            messages: {
                nameEdit: {
                    required: "Please enter the name",
                    maxlength: "Max length is 255 characters"
                },
                priceEdit: {
                    required: "Please enter the price",
                    digits: "Input is only accepted digits",
                },
                salePriceEdit: {
                    digits: "Input is only accepted digits",
                },
                urlEdit : {
                    required: false,
                    extension: "Extension is only accepted jpg-png-jpeg"
                }
            },
            submitHandler:  function(form) {
               /* let  name = $("#nameEdit").val();
                let role =  $("#priceEdit").val();
                let  password =  $("#passwordModal").val();*/
                //let passwordConfirm = $("#passwordConfirmModal").val();
                var formData = new FormData(form);
                axios.patch(location.origin +'/axios/products/'+product_id_modal,{
                    /*product_id_modal,
                    name,
                    role,
                    password,
                    passwordConfirm*/
                    formData
                }).then(function (response) {
                    console.log(response);
                    $('#modal').modal('hide');
                    toastr.success("Updated Successfully");
                    $('#'+response.data.user.id).empty().replaceWith(addText(response.data.user, response.data.auth_id));
                }).catch(function (error) {
                    $('#modal').modal('hide');
                    toastr.error("Updated Fails");
                })
            }
        });

});

$(window).on('load', function () {
    loadList();
});
