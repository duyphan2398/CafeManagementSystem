let table_id_modal = '';
function addText(item){
    var result= ``;
    result =  `      <tr id="`+item.id+`">
                                <td>`+item.id+`</td>
                                <td>`+item.name+`</td>
                                <td>
                                    <textarea readonly class="form-control" rows="3">`+item.note+`</textarea>
                                </td>
                                <td>`+item.status+`</td>

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
    $('#listTables').empty();
    $('#loadingTables').show();
    axios.get(location.origin + '/tables?ajax='+true
    ).then(function (response) {
        let result = '';
        response.data.tables.forEach(function (table){
            result += addText(table);
        });
        $('#listTables').append(result);
        $('#loadingTables').removeAttr("style").hide();
    });
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
    /*Edit*/
    jQuery(document).on('click',".edit",function () {
        table_id_modal = this.name;
        $('#loading_modal').show();
        $('#form_modal').empty();
        $('#modal').modal('show');
        axios.get(location.origin + '/axios/tables/'+ table_id_modal
        ).then(function (response) {
            $('#loading_modal').removeAttr("style").hide();
            edit_modal(response.data.table);
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
                note: {
                    required: false
                },
                status: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter the name",
                    maxlength: "Max length is 255 characters"
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
                axios.post(location.origin +'/axios/tables/'+table_id_modal, formData, config)
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

    /*New Table*/
    $('#newTableButton').click(function () {
        $('#newTableModal').modal('show');
    });

    $('#newTableForm').submit(function(e) {
        e.preventDefault();
    }) .validate({
        rules: {
            name: {
                required: true,
                maxlength: 255
            },
            status: {
                required: true,
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
                    'content-type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            };
            axios.post(location.origin +'/axios/tables' , formData, config)
                .then(function (response) {
                    loadList();
                    $('#newTableForm').trigger("reset");
                    $('#newTableModal').modal('hide');
                    toastr.success("Created Successfully");
                })
                .catch(function (error) {
                    toastr.error("Create Fails");
                })
        }
    });

    /*Delete Table*/
    jQuery(document).on('click',".delete",function () {
        let table_id = this.name;
        $.confirm({
            title: 'Confirm',
            content: 'Are you sure ?',
            buttons: {
                Yes: {
                    btnClass: 'btn-success',
                    action : function () {
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
                        axios.delete(location.origin + '/axios/tables/'+table_id)
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

});

$(window).on('load', function () {
    loadList();
});
