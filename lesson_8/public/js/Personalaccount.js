$('.btn-primary').on('click', function () {
    id = $(this).parent().parent().attr('id');
    $('.modal-content').empty();
    $.ajax({
        type: "POST",
        url: "ajax/Personalaccount/Order",
        data: {id: id},
        dataType: "json",
        success: function (data) {
            console.log(data);

            $('.modal-content').append(data.data);
            $('.bd-example-modal-lg').modal('show');
        }
    });
});