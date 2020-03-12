let currentPage = 0;
let lastPage = 1;
let output = ``;
let user_id_modal = null;
let array_username = [];

axios.get(location.origin + '/axios/getAllUsers')
    .then(function (response) {
        response.data.users.forEach(function (user) {
            array_username.push(user.username);
        })
});
function addText(insert){
    outputAddText = `
                    <tr id="`+insert.id+`">
                        <td>`+insert.id+`</td>
                        <td>`+insert.name+`</td>
                        <td>`+insert.username+`</td>
                        <td>`+insert.role+`</td>
                        <td>`+insert.created_at+`</td>
                        <td>`;
    if(insert.deleted_at == null){
        outputAddText +=
            `<button name="`+insert.id+`" id="actived`+insert.id+`" class="btn btn-success state" style="display: block">Actived</button>
             <button name="`+insert.id+`"  id="blocked`+insert.id+`"class="btn btn-warning state text-white" style="display: none">Blocked</button>`;
    }
    else {
        outputAddText +=
            `<button name="`+insert.id+`" id="actived`+insert.id+`" class="btn btn-success state" style="display: none">Actived</button>
             <button name="`+insert.id+`" id="blocked`+insert.id+`"class="btn btn-warning state text-white" style="display: block">Blocked</button>`;;
    }

    outputAddText +=` </td>
                          <td>
                            <button  name="`+insert.id+`" class="edit btn btn-primary mb-1" style="width: 75px">
                                     Edit
                             </button>
                             <button name="`+insert.id+`"  class="delete btn btn-danger mb-1" style="width: 75px">
                                     Delete
                             </button>
                          </td>
                     </tr> `;
    return outputAddText;
}



function new_modal(insert){
    let  modal = `
                        <input type="hidden" name="_token" value="`+token.content+`">
                        <div class="form-group">
                            <label for="nameModal" class="col-form-label">Name:</label>
                            <input value="`+ insert.name +`" type="text" name="nameModal" class="form-control" id="nameModal">
                        </div>
                        <div class="form-group">
                            <label  for="usernameModal" class="col-form-label">Username:</label>
                            <input  value="`+ insert.username +`" name="usernameModal" class="form-control" type="text" id="usernameModal" readonly>
                        </div>
                        <div class="form-group">
                            <label for="roleModal">Role:</label>
                            <select class="form-control" id="roleModal" name="roleModal">`;

                        switch (insert.role) {
                            case "Admin":
                                modal += `
                                      <option selected>Admin</option>
                                      <option>Manager</option>
                                      <option>Employee</option>`;
                                break;
                            case "Manager":
                                modal += `
                                      <option>Admin</option>
                                      <option selected>Manager</option>
                                      <option>Employee</option>`;
                                break;
                            case "Employee":
                                modal += `
                                      <option>Admin</option>
                                      <option>Manager</option>
                                      <option selected>Employee</option>`;
                                break;
                        }



                     modal +=`
                            </select>
                          </div>
                        <div class="form-group">
                            <label for="passwordModal" class="col-form-label">Password:</label>
                            <input name="passwordModal" class="form-control" type="password" id="passwordModal">
                        </div>
                        <div class="form-group">
                            <label for="passwordConfirmModal" class="col-form-label">Password Confirm:</label>
                            <input name="passwordConfirmModal" class="form-control" type="password" id="passwordConfirmModal">
                        </div>
                        <div class="modal-footer">
                            <button  id="updateUser" type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                `;
    $('#form_modal').append(modal);

}
$(document).ready(function () {
    $('#seeMore').click(function () {
        $('#seeMore').removeAttr("style").hide();
        $('#loading').show();
        currentPage++;
        axios.get(location.origin + '/axios/users?page=' + currentPage,
            $('#seeMore').removeAttr("style").hide(),
            $('#loading').show()
        ).then(function (response) {
            output = ``;
            lastPage = response.data.users.last_page;
            response.data.users.data.forEach(function (user) {
                output+= addText(user);
            });
            $('#loading').removeAttr("style").hide();
            $('#listUser').append(output);
            if (lastPage > currentPage){
                $('#seeMore').show();
            }
        });
    });


    jQuery(document).on('click',".state",function () {
        let user_id = this.name;
        if (this.id == 'actived'+user_id){
            $('#actived'+user_id).removeAttr("style").hide();
            $('#blocked'+user_id).show();
        }
        else{
            $('#blocked'+user_id).removeAttr("style").hide();
            $('#actived'+user_id).show();
        }
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        axios.delete(location.origin +'/axios/user/delete',{
            params:{
                user_id
            }
        })
    });

    jQuery(document).on('click',".delete",function () {
        let user_id = this.name;
        $.confirm({
            title: 'Confirm',
            content: 'Are you sure ?',
            buttons: {
                Yes: {
                    btnClass: 'btn-success',
                    action : function () {
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
                        axios.delete(location.origin + '/axios/user/forceDelete', {
                            params: {
                                user_id
                            }
                        }).then(function (response) {
                            toastr.success("Deleted Successfully");
                            $("tr#" + user_id + "").remove();
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


    jQuery(document).on('click',".edit",function () {
        user_id_modal = this.name;
        $('#loading_modal').show();
        $('#form_modal').empty();
        $('#modal').modal('show');
        axios.get(location.origin + '/axios/user', {
            params: {
                user_id_modal
            }
        }).then(function (response) {
            $('#loading_modal').removeAttr("style").hide();
            new_modal(response.data.user);
        })

    });



    $("#form_modal")
        .submit(function(e) {
            e.preventDefault();
        })
        .validate({
        rules: {
            nameModal: "required",
            passwordModal: {
                required: false,
                minlength : 5
            },
            passwordConfirmModal : {
                required: false,
                minlength : 5,
                equalTo : "#passwordModal"
            }
        },
        messages: {
            nameModal: {
                required: "Please enter the name"
            },
            passwordModal: {
                minlength : "At least 5 characters"
            },
            passwordConfirmModal : {
                minlength : "At least 5 characters",
                equalTo : "Must same the password"
            }
        },
        submitHandler:  function(form) {
            let  name = $("#nameModal").val();
            let role =  $("#roleModal").val();
            let  password =  $("#passwordModal").val();
            let passwordConfirm = $("#passwordConfirmModal").val();
            axios.patch(location.origin +'/axios/user/update',{
                user_id_modal,
                name,
                role,
                password,
                passwordConfirm

            }).then(function (response) {
                $('#modal').modal('hide');
                toastr.success("Updated Successfully");
                $('#'+response.data.user.id).empty().replaceWith(addText(response.data.user));
            }).catch(function (error) {
                $('#modal').modal('hide');
                toastr.error("Updated Fails");
            })
        }
    });


    $('#newUserForm').submit(function(e) {
        e.preventDefault();
    }) .validate({
        rules: {
            name: "required",
            username: "required",
            role: "required",
            password: {
                required: true,
                minlength: 5
            },
            passwordConfirm: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            }
        },
        messages: {
            name: {
                required: "Please enter the name"
            },
            username: {
              required: "Please enter the username",

            },
            passwordModal: {
                required: "Please enter the password",
                minlength: "At least 5 characters"
            },
            passwordConfirmModal: {
                required: "Please enter the password confirm",
                minlength: "At least 5 characters",
                equalTo: "Must same the password"
            }
        },
        submitHandler: function (form) {
            let name = $("#name").val();
            let username = $("#username").val();
            let role = $("#role").val();
            let password = $("#password").val();
            let passwordConfirm = $("#passwordConfirm").val();
            axios.post(location.origin + '/axios/user/new', {
                name,
                username,
                role,
                password,
                passwordConfirm
            }).then(function (response) {
                $('#newUserModal').modal('hide');
                toastr.success("Created Successfully");
                $("#listUser").prepend(addText(response.data.user));
            }).catch(function (error) {
                for ( key in error.response.data.errors) {
                    $("#"+key).after(`<label id="${key}-error" class="error" for="${key}">${error.response.data.errors[key]}</label>`);
                }
                toastr.error("Updated Fails");
            })
        }
    });

    $('#newUserButton').click(function () {
        $('#newUserModal').modal('show');
    });

    $('#searchUser').keyup( function() {
        console.log($(this).val());
    })
});


$(window).on('load', function () {
    $('#seeMore').click();
});

