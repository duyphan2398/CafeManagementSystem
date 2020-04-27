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

            },
            end_at: {

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

});

$(window).on('load', function () {
    loadList();
});
