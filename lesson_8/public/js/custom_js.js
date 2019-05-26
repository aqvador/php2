/**
 * @info Функция поджигает элементы меню белым цветом.
 */
$(function () {
    var location = window.location.href;
    var cur_url = '/' + location.split('/').pop();
    $('.navbar-nav li').each(function () {
        var link = $(this).find('a').attr('href');
        if (cur_url == link || cur_url == link+'/#') {
            $(this).addClass('active');
            $(this).find('em').css('color', '#0e82c5');
        }
    });
});
/**
 * @param {Параметры} data 
 * 
 * @return Возвращает всплывающее окно.
 */
function AlertSwal(data){
    var status = (data.status === true) ? 'Успех!' : 'Ошибка!';
    var type = (data.status === true) ? 'success' : 'error';
    var message = (data.message) ? data.message : 'Что-то пошло не так';
    swal({
      title: status,
      text: message,
      type: type,
      confirmButtonText: "Ок",
      showCancelButton: false,
      confirmButtonColor: '#3d75df',
      allowOutsideClick: true
    });
  }

  function basket(id, action = false) {
    window.status = true
    $.ajax({
        url: "/ajax/Basket/Addbasket",
        type: "post",
        dataType: "json",
        data: {
            id: id,
            action: action
        },
        success: function (data) {
            if (data.status !== true) {
                window.status = false;
                AlertSwal(data);
            }
        }
    });
}