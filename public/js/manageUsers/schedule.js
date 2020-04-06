let output = ``;

function addText(item){
    let result= ``;
    result =  `<tr id="`+item.id+`">
                    <td>`+item.username+`</td>
                    <td>`+item.start_time+`</td>
                    <td>`+item.end_time+`</td>
                    <td>`+item.date+`</td>
                    <td>`+item.total_time+`</td>
                    <td>`;
    if(item.note){
        result += `<textarea readonly class="form-control" rows="3">`+item.note+`</textarea>`;
    }
    else{
        result += `<textarea readonly class="form-control" rows="3"></textarea>`;
    };

    result +=       `</td>
                    <td>
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

function loadListScheduleFillter(from = $('#fromFillter').val(), to= $('#toFillter').val()) {
    if (from && to ){
        $("#listScheduleFillter").empty();
        $('#export').attr("disabled", true);
        $('#loadingFillter').show();
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        axios.post(location.origin + '/axios/getListScheduleFillter', {
           from,
            to
        })
            .then(function (response) {
            let resultFillter = ``;

            response.data.schedules.forEach(function (schedule) {
                resultFillter += addText(schedule);

            });
            $("#listScheduleFillter").append(resultFillter);
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
function loadListScheduleToday(){
    axios.get(location.origin + '/axios/getScheduleToday')
        .then(function (response) {
            let listSchedules = ``;
            response.data.schedules.forEach(function (schedule) {
                listSchedules+= addText(schedule);
            });
            $("#listSchedule").empty().append(listSchedules);
            $('#loading').removeAttr("style").hide();
        }).catch(function (error) {
            toastr.warning("Today's Schedule Is Empty");
            $("#listSchedule").empty().append(`<tr id="---">
                        <td>---</td>
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
            $('#loading').removeAttr("style").hide();
        });
}

$(document).ready(function () {
    $('#loading').show();
    loadListScheduleToday();
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
            let note = $("#noteNew").val();
            let total_time = $("#totaltimeNew").val();
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            axios.post(location.origin + '/axios/schedule/new', {
                username,
                start_time,
                end_time,
                date,
                total_time,
                note
            }).then(function (response) {
                $('#newScheduleForm').trigger("reset");
                if (response.data.schedule.date == moment().format('DD-MM-YYYY')){
                    $("#listSchedule").empty();
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
                                loadListScheduleFillter();
                            }
                            else {
                                loadListScheduleFillter();
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

    $("#exportScheduleFillter").submit(function (e) {
        e.preventDefault();
    })

    $("#export").click(function () {
        toFillter = $('#toFillter').val();
        fromFillter = $("#fromFillter").val();
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
         axios.post(location.origin + '/axios/schedules/export',{
             toFillter,
             fromFillter
         }).then(function (response) {
             let blob = new Blob(["\ufeff", response.data], { type: 'application/csv' });
             let link = document.createElement('a');
             link.href = window.URL.createObjectURL(blob);
             link.download = 'EmployeeSchedule('+fromFillter+'-To-'+toFillter+').csv';
             link.click();
             link.remove();

         });
    });
})
