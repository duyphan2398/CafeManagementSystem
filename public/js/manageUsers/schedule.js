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

axios.get(location.origin + '/axios/getScheduleToday')
    .then(function (response) {
        let listSchedules = ``;
        response.data.schedules.forEach(function (schedule) {
            listSchedules+= addText(schedule);
        });
        $('#loading').removeAttr("style").hide();
        $("#listSchedule").empty().append(listSchedules);
    });

$(document).ready(function () {
    $('#loading').show();

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
                console.log(response);
                $('#newScheduleForm').trigger("reset");
                if (response.data.schedule.date == moment().format('DD-MM-YYYY')){
                    $("#listSchedule").empty()
                    $('#loading').show();
                    axios.get(location.origin + '/axios/getScheduleToday')
                        .then(function (response) {
                            let listSchedules = ``;
                            response.data.schedules.forEach(function (schedule) {
                                console.log(schedule);
                                listSchedules+= addText(schedule);
                            });
                            $('#loading').removeAttr("style").hide();
                            $("#listSchedule").empty().append(listSchedules);
                        });

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
})
