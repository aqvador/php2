$('a[href="#orders"]').on('click', function () {
  autoloadmethod('orders');
});

$('a[href="#users"]').on('click', function () {
  autoloadmethod('users');
});

$('a[href="#goods"]').on('click', function () {
  autoloadmethod('goods');
});

$('.status-order-select a').on('click', function () {
  type = $(this).attr('href').substr(1, 10);
  $('.content-orders').slideToggle(600);
  $.ajax({
    type: "POST",
    url: "ajax/Administration/GetAjaxStatusOrder",
    data: {
      who: type
    },
    dataType: "json",
    success: function (data) {
      if (data.param.status === true) {
        var content = data.data;
        setTimeout(function () {
          $('.content-orders').empty();
          $('.content-orders').append(content);
          $('.content-orders').slideToggle(600);
        }, 700);

      }

    }
  });
});


function autoloadmethod(param) {
  $.ajax({
    type: 'POST',
    url: 'ajax/Administration/GetAjaxContent',
    data: {
      action: param
    },
    dataType: 'json',
    success: function (data) {
      if (data.status == '404') {
        AlertSwal({
          status: false,
          message: 'Модуль  пока в разработке'
        });

      }
    }
  });
}
var $select = $('.wrap');
$select.on('click', '.select-status-orders', function (event) {
  if ($(event.target).is("option") || $(event.target).is("select")) {
    var $this = $(this);
    $this.attr('disabled', true);
    id = $this.parent().parent().attr('id');
    select = $this.children('option:selected').val();
    console.log(id);
    $.ajax({
      type: "POST",
      url: "/ajax/Administration/OrderEditStatus",
      data: {
        id: id,
        status: select
      },
      dataType: "json",
      success: function (data) {
        // console.log(data);
        var param = data.param;
        //Добавим блок alert
        $('.alert-message').append(data.data);
        setTimeout(function () {
          $('body #' + param.mesid).fadeToggle(1000);
          $this.attr('disabled', false);
        }, 2000);
        if (param.status === false) return;
        //Если активная страница all, то не удаляем  блок заказа со страницы
        if ($('.status-order-select .active').attr('href') != '#all') {
          setTimeout(function () {
            $('#' + param.id).fadeToggle(1000);
          }, 1500);
        }

      }
    });
  }
});

$('.wrap').on('click', '.clickshowmodal', function () {
  id = $(this).parent().parent().attr('id');
  console.log(id);
  $('.modal-content').empty();
  $.ajax({
      type: "POST",
      url: "ajax/Administration/ShowOrder",
      data: {id: id},
      dataType: "json",
      success: function (data) {
          console.log(data);

          $('.modal-content').append(data.data);
          $('.bd-example-modal-lg').modal('show');
      }
  });
});