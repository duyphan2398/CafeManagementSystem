let output = ``;

function addText(item){
    let result= ``;
    result =  `<tr id="`+item.id+`">
                    <td>`+item.username+`</td>
                    <td>`+item.start_time+`</td>
                    <td>`+item.end_time+`</td>
                    <td>`+item.date+`</td>
                    <td>`+item.total_time+`</td>`;
    if(item.date == moment().format('DD-MM-YYYY')){
        if (item.checkin_time){
            result+= `<td>`+item.checkin_time+`</td>`;
        }else{
            result+= `<td>
                             <button name="`+item.id+`"  class="checkin btn btn-info mb-1" >
                                Checkin
                             </button>
                             <div name="`+item.id+`" class="text-center mb-2 loadingCheckin"  style="display: none;">
                                <img src="`+location.origin+`/images/loading.gif" alt="loading..." style="margin-bottom: 70px">
                            </div>
                      </td>`;
        }
        if (item.checkout_time){
            result+= `<td>`+item.checkout_time+`</td>`;
        }else{
            result+= `<td>
                             <button name="`+item.id+`"  class="checkout btn btn-info mb-1">
                                Checkout
                             </button>
                             <div name="`+item.id+`" class="text-center mb-2 loadingCheckout"  style="display: none;" >
                                <img src="`+location.origin+`/images/loading.gif" alt="loading..." style="margin-bottom: 70px">
                            </div>
                      </td>`;
        }
    }else {
        result+= `<td>`+item.checkin_time+`</td>
                  <td>`+item.checkout_time+`</td>`;
    }

    result+=`                <td>`;
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
            $("#listScheduleFillter").empty();
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

    /*Checkin - Checkout*/
    jQuery(document).on('click',".checkin",function () {
        let schedule_id = this.name;
        $(this).removeAttr("style").hide();
        $(".loadingCheckin[name='"+schedule_id+"']").show();
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        axios.post(location.origin + '/axios/schedules/checkin/'+schedule_id)
            .then(function (response) {
                toastr.success("Have A Nice Working Day !");
                loadListScheduleToday();
            })
            .catch(function (error) {
                $(".loadingCheckin[name='"+schedule_id+"']").removeAttr("style").hide();
                $(".checkin[name='"+schedule_id+"']").show();
                toastr.error("Something Wrong ! Please refresh your browser");
            });
    });

    jQuery(document).on('click',".checkout",function () {
        let schedule_id = this.name;
        $(this).removeAttr("style").hide();
        $(".loadingCheckout[name='"+schedule_id+"']").show();
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        axios.post(location.origin + '/axios/schedules/checkout/'+schedule_id)
            .then(function (response) {
                toastr.success("Thanks For Your Help ! <br> Have A Good Day");
                loadListScheduleToday();
            })
            .catch(function (error) {
                $(".loadingCheckout[name='"+schedule_id+"']").removeAttr("style").hide();
                $(".checkout[name='"+schedule_id+"']").show();
                toastr.error("Something Wrong ! Please refresh your browser <br> Or You have not checked in yet");
            });
    });
})
