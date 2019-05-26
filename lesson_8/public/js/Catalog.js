/**
 * @discription Функция Подгружает на тсраницу товары, по клику мыши
 * 
 * @author by aqvador
 */
$('#show_more').click(function () {
    show_more();

});

/**
 * @discription Функция автоматической подгрузки контента
 * @param Определяет высоту экрана, складывает высоту документа с позицией скрола 
 * @param Сравнивает высоту документа с полученными выше значениямиями,
 * @param  Если условие срабатывет (скрол около футера на 100px) и если у футера нет класса show_more_visible
 * @param Тогда добавляем класс show_more_visible тэгу footer и выполняем функцию show_more
 * 
 * @author  by aqvador
 */
$(document).scroll(function () {
    $(document).ready(function ($) {
        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                var cont = $('footer');
                if (!cont.hasClass('show_more_visible')) {
                    cont.toggleClass('show_more_visible');
                    show_more();
                }
            }
        });
    });
});
/**
 * @discription Функция вызывается при загрузке страницы, определяет ширину экрана.
 * @param Функция Подгружает по Ajax  элементы каталога на страницу
 * 
 * @author by aqvador
 */
function show_more() {
    var category = window.location.href.split('/').pop();
    if (category == 'catalog') category = 1;
    var btn_more = $('#show_more');
    //Значение сколько покаано
    var count_show = parseInt($(btn_more).val());
    //Значение сколько надо показать
    var count_add = parseInt($(btn_more).attr('name'));
    btn_more.html('Подождите...');
    var data = {};
    data.count_show = count_show;
    data.count_add = count_add;
    data.category = category;

    $.ajax({
        url: "/ajax/Catalog/ajaxGetCatalog",
        type: "post",
        dataType: "json",
        data: data,
        success: function (data) {
            //  console.log(data);
            if (data.param.status === true) {
                $('.row').append(data.data);
                btn_more.html('Показать еще');
                btn_more.val(data.param.show);
                setTimeout(function () {
                    $('footer').toggleClass('show_more_visible');
                }, 50);
            } else {
                btn_more.html('Больше нечего показывать');
                btn_more.toggleClass('display_none');


            }
        }
    });
    // $.getScript('../js/Catalog.js');
}


/**
 * @discription Функция вызывается при загрузке страницы, определяет ширину экрана.
 * @param Функция определет  разрешение экрана
 * @param Далее  пытаетмя поределить сколько блоков влезет на страницу
 * @param  после этого подгружает столько элементов на страницу, сколько влезет умножая на три
 * 
 * @author by aqvador
 */
/*
$(document).ready(function () {
    var count;
    var width = $(window).width();
    if (width >= 2500) count = 8;
    else if (width >= 1920) count = 6;
    else if (width >= 1600) count = 5;
    else if (width >= 1280) count = 4;
    else if (width >= 952) count = 3;
    else if (width >= 550) count = 2;
    else count = 1;
    //Кнопка
    var btn_more = $('#show_more');
    // кол- во товара в строке умножим на 3, будет 3 ряда товаров
    var count_show = count * 4;
    btn_more.attr('count_show', count_show);
    btn_more.attr('count_add', count_show);
    show_more();

    setTimeout(function () {
        $('footer').toggleClass('show_more_visible');
        $('#show_more').toggleClass('display_none');
    }, 200);
});
*/
/**
 * @info Полет товара в корзину
 */
$('.content').on('click', '.cart-button', function (e) {
    e.preventDefault();
    var $this = $(this);
    url = $this.attr("href").split('/');
    var id = url[3];
    if ($this.attr('disabled') == 'disabled') return;
    $this.attr('disabled', true);
    setTimeout(function () {
        $this.attr('disabled', false);
    }, 500);
    $.ajax({
        url: "/ajax/Basket/Addbasket",
        type: "post",
        dataType: "json",
        data: {
            id: id
        },
        success: function (data) {
            var status = data.status;
            console.log(data);
            if (data.status !== true) {
                AlertSwal(data);
                return;
            } else {
                var count = $('#basket').find('span').text().replace(/\D+/g, "");
                count = (count == '') ? 1 : ++count;
                $('#basket span').html('Покупок ' + count);
                $(".product_" + id + ' a img')
                    .clone()
                    .css({
                        'position': 'absolute',
                        'z-index': '11100',
                        top: $(".product_" + id + ' a img').offset().top - 150,
                        left: $(".product_" + id + ' a img').offset().left - 150
                    })
                    .appendTo(".product_" + id)
                    .animate({
                        opacity: 0,
                        left: $("#basket").offset().left - 100,
                        top: $("#basket").offset().top - 120,
                        width: 40
                    }, 1500, function () {
                        $(this).remove();
                    });
            }
        }
    });

});

$(function () {
    var location = window.location.href;
    var cur_url = location.split('/').pop();
    if (cur_url === 'catalog') {
        $('.catalogurl li:first').addClass('active');
    }
    $('.catalogurl li').each(function () {
        var link = $(this).find('a').attr('href').split('/').pop();
        if (cur_url == link) {
            $(this).addClass('active');
        }

    });
});