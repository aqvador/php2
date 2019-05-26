function basket(id) {
    window.status = true
    $.ajax({
        url: "/ajax/Basket/Addbasket",
        type: "post",
        dataType: "json",
        data: {
            id: id
        },
        success: function (data) {
            if (data.status !== true) {
                AlertSwal(data);
            }
        }
    });
}
/**
 * Кнопка минус в basket
 */
$('.btn-spn-down').on('click', function (e) {
    e.preventDefault();
    $(this).attr('disabled', true);
    h = $(this).parent().next('input');
    c = $(h).val();
    var id = $(h).parent().parent().parent().attr('id');
    if (c <= 1) {
        AlertSwal({
            'status': false,
            'message': 'Удалите, если Вам это не надо'
        });
        $('.btn-spn-down').attr('disabled', false);
        return;
    }
    $.ajax({
        url: "/ajax/Basket/MathematicBasket",
        type: "post",
        dataType: "json",
        data: {
            id: id,
            action: 'minus'
        },
        success: function (data) {
            if (data.status == false) {
                AlertSwal(data);
                $('.countnull').fadeToggle('show', function (e) {
                    $('.ui-view-cart').fadeToggle("show");
                });
                $('#basket span').fadeToggle('fast', function (e) {
                    $('#basket span').html('Корзина');
                    $('#basket span').fadeToggle('fast');
                });
            } else {
                var count = $('#basket').find('span').text().replace(/\D+/g, "");
                count = (count == '') ? 1 : --count;
                //эффект корзины
                $('#basket span').fadeToggle('fast', function (e) {
                    $('#basket span').html('Покупок ' + count);
                    $('#basket span').fadeToggle('fast');
                });
                //эффект стоимости товара
                $('#' + data.id + ' .summ').fadeToggle('fast', function (e) {
                    $('#' + data.id + ' .summ').html(data.pp + 'р.')
                    $('#' + data.id + ' .summ').fadeToggle('fast');
                });
                //эффект Общей стоимости товаров
                $('.totalprice').fadeToggle('fast', function (e) {
                    $('.totalprice').html(data.tp + 'р.');
                    $('.totalprice').fadeToggle('fast');
                });
            }
        }
    });
    $(h).val(--c);

    setTimeout(function () {
        $('#' + id + ' .btn-spn-down').attr('disabled', false);
    }, 500);
});
/**
 * Кнопка плюс в basket
 */
$('.btn-spn-up').on('click', function (e) {
    e.preventDefault();
    $(this).attr('disabled', true);
    h = $(this).parent().prev('input');
    id = $(h).parent().parent().parent().attr('id');
    c = $(h).val();
    if (c >= 30) {
        AlertSwal({
            'status': false,
            'message': 'У нас нет таких объемов =)'
        });
        setTimeout(function () {
            $('#' + id + ' .btn-spn-up').attr('disabled', false);
        }, 500);
        return

    };
    $.ajax({
        url: "/ajax/Basket/MathematicBasket",
        type: "post",
        dataType: "json",
        data: {
            id: id,
            action: 'plus'
        },
        success: function (data) {
            console.log(data);
            if (data.status == false) {
                AlertSwal(data);
                console.log(data);
                $('.countnull').fadeToggle('show', function (e) {
                    $('.ui-view-cart').fadeToggle("show");
                });
                $('#basket span').html('Корзина');
            } else {
                var count = $('#basket').find('span').text().replace(/\D+/g, "");
                count = (count == '') ? 1 : ++count;
                $('#basket span').fadeToggle('fast', function (e) {
                    $('#basket span').html('Покупок ' + count);
                    $('#basket span').fadeToggle('fast');
                });
                $('#' + data.id + ' .summ').fadeToggle('fast', function (e) {
                    $('#' + data.id + ' .summ').html(data.pp + 'р.')
                    $('#' + data.id + ' .summ').fadeToggle('fast');
                });
                $('.totalprice').fadeToggle('fast', function (e) {
                    $('.totalprice').html(data.tp + 'р.')
                    $('.totalprice').fadeToggle('fast');
                });
            }
        }
    });

    $(h).val(++c);
    setTimeout(function () {
        $('#' + id + ' .btn-spn-up').attr('disabled', false);
    }, 500);
});

/**
 * Удаление товара из корзины
 */
$('.btn-danger').on('click', function (e) {
    e.preventDefault();
    id = $(this).parent().parent().attr('id');
    //$('#' + id).remove();
    $.ajax({
        url: "/ajax/Basket/RemoveBasket",
        type: "post",
        dataType: "json",
        data: {
            id: id,
            action: 'remove'
        },
        success: function (data) {
            console.log(data);
            if (data.tp == false) {
                $('.ui-view-cart').toggleClass('d-block');
                $('.ui-view-cart').fadeToggle('fast', function (e) {
                    $('.countnull').fadeToggle('show');
                });
                $('#basket span').fadeToggle('fast', function (e) {
                    $('#basket span').html('Корзина');
                    $('#basket span').fadeToggle('fast');
                });
            } else {
                $('#' + data.id).fadeToggle('show', function () {
                    $('#' + data.id).remove();
                });
                $('.totalprice').fadeToggle('fast', function (e) {
                    $('.totalprice').html(data.tp + ' р.');
                    $('.totalprice').fadeToggle('fast');
                });

                $('#basket span').fadeToggle('fast', function (e) {
                    $('#basket span').html('Покупок ' + data.count);
                    $('#basket span').fadeToggle('fast');
                });
            }
            AlertSwal({
                'status': true,
                'message': 'Товар успешно удален из корзины'
            });
        }
    });
});

/**
 * Оформляем заказ
 */
$(function () {
    $("#ordersvasemodal").validate({
        rules: {
            name: {
                required: true,
                rangelength: [2,15]
            },
            phone: {
                required: true,
                minlength: 11
            },
            last_name: {
                required: true,
                rangelength: [2,15]
            }

        },
        messages: {
            name: {
                required: "Это поле обязательно для заполнения",
                rangelength: "Имя должно содержать от {0} до {1} симвволов"
            },
            phone: {
                required: "Это поле обязательно для заполнения",
                minlength: jQuery.validator.format("Длина телефона должна быть {0} симв.")
            },
            last_name: {
                required: "Это поле обязательно для заполнения",
                rangelength: "Фамилия должна содержать от {0} до {1} симвволов"
            }
        },
        submitHandler: function () {
            var data = {};
            $('#ordersvasemodal').find('input, button').each(function () {
                data[this.name] = $(this).val();
            });
            $.ajax({
                url: "/ajax/Basket/OrderBasket",
                type: "post",
                dataType: "json",
                data: data,
                success: function (data) {
                    $("#ModalCenter").modal('hide');
                    AlertSwal(data);
                    if (data.status === false && data.fatal === false) return;
                    $('.ui-view-cart').toggleClass('d-block');
                    $('.ui-view-cart').fadeToggle('fast', function (e) {
                        $('.countnull').fadeToggle('show');
                    });
                    $('#basket span').fadeToggle('fast', function (e) {
                        $('#basket span').html('Корзина');
                        $('#basket span').fadeToggle('fast');
                    });
                    if (data.fatal === true) {
                        setTimeout(function () {
                            AlertSwal({
                                'status': false,
                                'message': 'Вы проявляете не стандартные действия. Ваша корзина очищена'
                            });
                        }, 2000);
                    }
                }
            });
        }
    });
});