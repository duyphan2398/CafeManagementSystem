let currentPage = 0;
let lastPage = 1;
let output = ``;
let material_id = null;
let output_current = ``;
/*Flag for search*/
let last_find=``;
/*--------------*/
function addText(item){
    let result= ``;
    result =  `     <tr id="`+item.id+`">
                    <td>`+item.id+`</td>
                    <td>`+item.name+`</td>
                    <td>`+item.amount+`</td>
                    <td>`+item.unit+`</td>
                    <td>`+item.updated_at+`</td>
                    <td>
                        <button  name="`+item.id+`" class="edit btn btn-primary mb-1" style="width: 75px">
                              Edit
                         </button>
                        <button name="`+item.id+`"  class="delete btn btn-danger mb-1" style="width: 75px">
                            Delete
                        </button>
                    </td>
                   `;
    return result;
}

function new_modal(insert){
    let  modal = `
                    <div class="form-group mt-2">
                        <label for="nameEdit">Name</label>
                        <input name="nameEdit" type="text" class="form-control" id="nameEdit" value="`+insert.name+`" placeholder="Name">
                    </div>
                    <div class="form-group mt-2">
                        <label for="amountEdit">Amount</label>
                        <input name="amountEdit" type="number" class="form-control" value="`+insert.amount+`" id="amountEdit" placeholder="Amount" >
                    </div>
                    <div class="form-group mt-2" >
                        <label for="unitEdit">Unit</label>
                        <input name="unitEdit" class="form-control" type="text" id="unitEdit" value="`+insert.unit+`" list="unit" placeholder="Unit" autocomplete="off" readonly>
                        <datalist id="unit">
                            <option value="KG">
                            <option value="GRAM">
                            <option value="ML">
                            <option value="L">
                            <option value="PACKAGES">
                            <option value="PIECES">
                            <option value="BOTTLE">
                        </datalist>
                    </div>
                    <div class="modal-footer mt-4">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                `;
    $('#form_modal').append(modal);

}
$(document).ready(function () {
    /*Load Page & Paginate*/
    $('#seeMore').click(function () {
        currentPage++;
        axios.get(location.origin + '/axios/materials?page=' + currentPage,
            $('#seeMore').removeAttr("style").hide(),
            $('#loading').show()
        ).then(function (response) {
            output = ``;
            lastPage = response.data.materials.last_page;
            response.data.materials.data.forEach(function (material) {
                output+= addText(material);
            });
            $('#listMaterials').append(output);
            $('#loading').removeAttr("style").hide();
            if (lastPage > currentPage){
                $('#seeMore').show();
            }
        }).catch(function (error) {
            currentPage--;
            toastr.error("Load New Fails");
            $('#loading').removeAttr("style").hide();
            if (lastPage > currentPage){
                $('#seeMore').show();
            }
        });
    });


    /*New*/
    $('#newMaterialButton').click(function () {
        $('#newMaterialModal').modal('show');
    });

    $('#newMaterialForm').submit(function(e) {
        e.preventDefault();
    }) .validate({
        rules: {
            nameNew: "required",
            unitNew: "required",
            amountNew: {
                required: true,
                number: true
            }
        },
        messages: {
            nameNew: "Please enter name",
            unitNew: "Please enter unit",
            amountNew: {
                required: "Please enter amount",
                number: "Please enter amount as number"
            },

        }, submitHandler: function (form) {
            let name = $("#nameNew").val();
            let unit = $("#unitNew").val();
            let amount = $("#amountNew").val();
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            axios.post(location.origin + '/axios/material/new', {
               name, unit, amount
            }).then(function (response) {
                $('#newMaterialModal').modal('hide');
                toastr.success("Created Successfully");
                $("#listMaterials").prepend(addText(response.data.material));
            }).catch(function (error) {
                for ( key in error.response.data.errors) {
                    $("#"+key).after(`<label id="${key}-error" class="error" for="${key}">${error.response.data.errors[key]}</label>`);
                }
                toastr.error("Create Fails");
            })
        }
    });

    /*Edit*/
    jQuery(document).on('click',".edit",function () {
        material_id = this.name;
        $('#form_modal').empty();
        $('#loading_modal').show();
        $('#modal').modal('show');
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        axios.post(location.origin + '/axios/material', {
            material_id
        }).then(function (response) {
            $('#loading_modal').removeAttr("style").hide();
            new_modal(response.data.material);
        }).catch(function (error) {
            $('#loading_modal').removeAttr("style").hide();
            toastr.error("Could Not Found Material");
            $('#modal').modal('hide');
        })

    });



    $("#form_modal")
        .submit(function(e) {
            e.preventDefault();
        })
        .validate({
            rules: {
                nameEdit: "required",
                unitEdit: "required",
                amountEdit: {
                    required: true,
                    number: true
                }
            },
            messages: {
                nameEdit: "Please enter name",
                unitEdit: "Please enter unit",
                amountEdit: {
                    required: "Please enter amount",
                    number: "Please enter amount as number"
                },

            },
            submitHandler:  function(form) {
                let  name = $("#nameEdit").val();
                let  amount = $("#amountEdit").val();
                let unit = $("#unitEdit").val();
                console.log(unit);
                console.log($("#unitEdit").val())
                axios.patch(location.origin +'/axios/material/update',{
                    material_id,
                    name,
                    unit,
                    amount
                }).then(function (response) {
                    $('#modal').modal('hide');
                    toastr.success("Updated Successfully");
                    $('#'+response.data.material.id).empty().replaceWith(addText(response.data.material));
                }).catch(function (error) {
                    $('#modal').modal('hide');
                    toastr.error("Updated Fails");
                })
            }
        });

    //Delete
    jQuery(document).on('click',".delete",function () {
        let material_id_delete = this.name;
        console.log(material_id_delete);
        $.confirm({
            title: 'Confirm',
            content: 'Are you sure ?',
            buttons: {
                Yes: {
                    btnClass: 'btn-success',
                    action : function () {
                        console.log(material_id_delete);
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
                        axios.delete(location.origin + '/axios/material/delete', {
                                params: {material_id_delete}
                        }).then(function (response) {
                            toastr.success("Deleted Successfully");
                            $("tr#" + material_id_delete + "").remove();
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


    //Search
    $('#searchForm').submit(function (event) {
        event.preventDefault();
        if ($('#searchMaterial').val() != '') {
            $('#listMaterials').empty();
            $('#seeMore').removeAttr("style").hide();
            $('#loading').show();

            let search = $('#searchMaterial').val();
            axios.get(location.origin + '/axios/material/search', {
                params: {
                    search
                }
            }).then(function (response) {
                let result = ``;
                response.data.materials.forEach(function (material) {
                    result+=addText(material);
                });
                $('#loading').removeAttr("style").hide();
                last_find = search;
                $('#listMaterials').append(result);
                toastr.success('Have '+response.data.materiala.length+' materials found');
            }).catch(function (error) {
                if (last_find){
                    search = last_find;
                    axios.get(location.origin + '/axios/material/search', {
                        params: {
                            search
                        }
                    }).then(function (response) {
                        let result = ``;
                        response.data.materials.forEach(function (material) {
                            result+=addText(material);
                        });
                        $('#loading').removeAttr("style").hide();
                        $('#listMaterials').append(result);
                        toastr.warning('Could not found');
                    }).catch(function (error) {
                        toastr.warning('Could not found');
                        toastr.error('Server Error');
                        $('#loading').removeAttr("style").hide();
                    })
                }
                else {
                    let result = ``;
                    async function loadMaterialsList() {
                        for (count = 1; count <= currentPage; count++) {
                            try {
                                $('#loading').show();
                                let asyncRequest = await axios.get(location.origin + '/axios/materials?page=' + count
                                );
                                let data = asyncRequest.data;
                                lastPage = data.materials.last_page;
                                result = ``;
                                data.materials.data.forEach(function (material) {
                                    result += addText(material);
                                });
                                $('#listMaterials').append(result);
                            }
                            catch (e) {
                                console.error(error);
                            }
                        }
                        $('#loading').removeAttr("style").hide();
                        if (lastPage > currentPage) {
                            $('#seeMore').show();
                        }
                    }
                    loadMaterialsList();
                    toastr.warning('Could not found');
                }
            })
        }
        else {
            last_find = '';
            $('#listMaterials').empty();
            $('#seeMore').removeAttr("style").hide();
            $('#loading').show();
            let result = ``;
            async function loadMaterialsList() {
                for (count = 1; count <= currentPage; count++) {
                    try {
                        $('#loading').show();
                        let asyncRequest = await axios.get(location.origin + '/axios/materials?page=' + count
                        );
                        let data = asyncRequest.data;
                        lastPage = data.materials.last_page;
                        result = ``;
                        data.materials.data.forEach(function (material) {
                            result += addText(material);
                        });
                        $('#listMaterials').append(result);

                    }
                    catch (e) {
                        console.error(error);
                    }
                }
                $('#loading').removeAttr("style").hide();
                if (lastPage > currentPage) {
                    $('#seeMore').show();
                }
            }
            loadMaterialsList();
        }
        $('#searchMaterial').val('');
    })
})

$(window).on('load', function () {
    $('#seeMore').click();
});
