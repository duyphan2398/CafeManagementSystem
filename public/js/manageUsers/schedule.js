let output = ``;




function addText(item){

    let result= ``;
    result =  `<tr id="`+item.id+`">
                    <td>`+item.user.username+`</td>
                    <td>`+item.start_time+`</td>
                    <td>`+item.end_time+`</td>
                    <td>`+item.date+`</td>
                    <td>`+item.total_time+`</td>
                    <td>
                        <button name="`+item.id+`"  class="edit btn btn-primary mb-1" style="width: 75px">
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

axios.get(location.origin + '/axios/getAllUsersWithoutTrashed')
    .then(function (response) {
        output = ``;
        response.data.users.forEach(function (user) {
            output+= `<option value="`+user.username+`">`+user.username+`</option>`;
        });
        $("#usernameNew").empty().append(output);
    });

/*function loadListScheduleFillter() {
    let fromFillter = $("#fromFillter").val();
    let toFillter = $("#toFillter").val();
    axios.get(location.origin + '/axios/getListScheduleFillter', {
        fromFillter,
        toFillter
    }).then(function (response) {
        console.log(response);
           /!* output = ``;
            response.data.users.forEach(function (user) {
                output+= `<option value="`+user.username+`">`+user.username+`</option>`;
            });
            $("#usernameNew").empty().append(output);*!/
        });

}*/
function loadListScheduleToday(){
axios.get(location.origin + '/axios/getScheduleToday')
    .then(function (response) {
        let listSchedules = ``;
        response.data.schedules.forEach(function (schedule) {
            listSchedules+= addText(schedule);
        });
        $('#loading').removeAttr("style").hide();
        $("#listSchedule").empty().append(listSchedules);
    }).catch(function (error) {
        toastr.warning("Today's Schedule Is Empty");
        $('#loading').removeAttr("style").hide();
        $("#listSchedule").empty().append(`<tr id="---">
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                    <td>
                      ---
                    </td>
                </tr>
            `);
    });
}


function loadListScheduleFillterFirst(){
    axios.get(location.origin + '/axios/getScheduleToday')
        .then(function (response) {
            let listSchedules = ``;
            response.data.schedules.forEach(function (schedule) {
                listSchedules+= addText(schedule);
            });
            $('#loadingFillter').removeAttr("style").hide();
            $("#listScheduleFillter").empty().append(listSchedules);
        }).catch(function (error) {
        $('#loadingFillter').removeAttr("style").hide();
        $("#listScheduleFillter").empty().append(`<tr id="---">
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                    <td>---</td>
                    <td>
                      ---
                    </td>
                </tr>
            `);
    });
}
$(document).ready(function () {
    $('#loading').show();
    $('#loadingFillter').show();
    loadListScheduleToday();
    loadListScheduleFillterFirst();
    $('#newScheduleButton').click(function () {
        $('#newScheduleModal').modal('show');
    });

    $('#newScheduleForm').submit(function(e) {
        e.preventDefault();
    }) .validate({
        rules: {
            usernameNew: "required",
            startNew: "required",
            endNew: "required",
            dateNew: "required",
        },
        messages: {
            usernameNew: "Please enter username",
            startNew: "Please enter start time",
            endNew: "Please enter end time",
            dateNew: "Please enter date"
        }, submitHandler: function (form) {
            let username = $("#usernameNew").val();
            let start_time = $("#startNew").val();
            let end_time = $("#endNew").val();
            let date = $("#dateNew").val();
            let total_time = $("#totaltimeNew").val();
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            axios.post(location.origin + '/axios/schedule/new', {
                username,
                start_time,
                end_time,
                date,
                total_time
            }).then(function (response) {
                $('#newScheduleForm').trigger("reset");
                if (response.data.schedule.date == moment().format('DD-MM-YYYY')){
                    $("#listSchedule").empty()
                    $('#loading').show();
                    loadListScheduleToday();
                }
                toastr.success("Created Successfully");
            }).catch(function (error) {
                for ( key in error.response.data.errors) {
                    $("#"+key).after(`<label id="${key}-error" class="error" for="${key}">${error.response.data.errors[key]}</label>`);
                }
                toastr.error("Create Fails");
            })
        }
    });


    jQuery(document).on('click',".delete",function () {
        let schedule_id = this.name;
        $.confirm({
            title: 'Confirm',
            content: 'Are you sure ?',
            buttons: {
                Yes: {
                    btnClass: 'btn-success',
                    action : function () {
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
                        axios.delete(location.origin + '/axios/schedule/delete', {
                            params: {
                                schedule_id
                            }
                        }).then(function (response) {
                            if (response.data.date == moment().format('DD-MM-YYYY')){
                                loadListScheduleToday();
                            }
                            else {
                                //Code Here **
                            }
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

    jQuery(document).on('click',".edit",function () {
        schedule_id_modal = this.name;
        $('#loading_modal').show();
        $('#form_modal').empty();
        $('#modal').modal('show');
        axios.get(location.origin + '/axios/schedule', {
            params: {
                schedule_id_modal
            }
        }).then(function (response) {
            console.log(response);
            $('#loading_modal').removeAttr("style").hide();
            new_modal(response.data.user);
        })
    });



    $("#export").click(function () {
 /*       toFillter = $('#toFillter').val();
        fromFillter = $("#fromFillter").val();*/
         axios.post(location.origin + '/axios/schedules/export');
    });
})
