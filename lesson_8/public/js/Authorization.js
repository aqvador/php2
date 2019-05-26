$('.form').find('input, textarea').on('keyup blur focus', function (e) {

  var $this = $(this),
    label = $this.prev('label');

  if (e.type === 'keyup') {
    if ($this.val() === '') {
      label.removeClass('active highlight');
    } else {
      label.addClass('active highlight');
    }
  } else if (e.type === 'blur') {
    if ($this.val() === '') {
      label.removeClass('active highlight');
    } else {
      label.removeClass('highlight');
    }
  } else if (e.type === 'focus') {

    if ($this.val() === '') {
      label.removeClass('highlight');
    } else if ($this.val() !== '') {
      label.addClass('highlight');
    }
  }

});

$('.tab a').on('click', function (e) {
  e.preventDefault();
  $(this).parent().addClass('active');
  $(this).parent().siblings().removeClass('active');

  target = $(this).attr('href');
  $('.tab-content > div').not(target).hide();
  $(target).fadeIn(600);

});

/**
 * Валидатор авторизации
 */
$(function () {
  $("#Authorization").validate({
    rules: {
      email: {
        required: true,
        email: true
      },
      pass: {
        required: true,
        minlength: 6
      }

    },
    messages: {
      email: {
        required: "Поле email обязательно для заполнения",
        email: jQuery.validator.format("Не корректный <br> Например: nama@exemple.ru")
      },
      pass: {
        required: "Поле пароль обязательно для заполнения",
        minlength: jQuery.validator.format("Длина пароля должна быть больше 5-ти символов")
      }
    },
    submitHandler: function () {
      var data = {};
      $('#Authorization').find('input, button').each(function () {
        data[this.name] = $(this).val();
      });
      $.ajax({
        url: "/ajax/Authorization/auth",
        type: "post",
        dataType: "json",
        data: data,
        success: function (data) {
          AlertSwal(data);
          if (data.status !== true) return false;
          $('input').val('');
          setTimeout(function () {
            $(location).attr('href', '/');
          }, 2000);
        }
      });
    }
  });
});

/**
 * Валидация Формы регистрации
 */
$("#Registration").validate({
  rules: {
    name: {
      required: true,
      minlength: 2
    },
    last_name: {
      required: true,
      minlength: 2
    },
    email: {
      rangelength: [6, 45],
      required: true,
      email: true,
      remote: {
        url: "/ajax/Authorization/CheckMail",
        type: "POST"
      }
    },
    phone: {
      required: true,
      rangelength: [11, 11],
      digits: true
    },
    password: {
      required: true,
      minlength: 6
    },
    confirmpassword: {
      required: true,
      minlength: 6,
      equalTo: "#password"
    }
  },

  messages: {
    name: {
      required: "Поле Имя обязательно для заполнения",
      minlength: "Минимальная длинна {0} символа",
    },
    last_name: {
      required: "Поле Фамилия обязательно для заполнения",
      minlength: "Минимальная длинна {0} символа",
    },
    email: {
      required: "Поле Email обязательно для заполнения",
      email: jQuery.validator.format("Не корректный Email"),
      remote: "Email {0} уже занят",
      rangelength: "Длинна email от {0}  до {1} символов"
    },
    phone: {
      required: "Поле Телефон обязательно для заполнения",
      rangelength: "Введите корректный номер телефона. <br> Например: 89505555668",
      digits: "В поле номер указывайте только цифры"
    },
    password: {
      required: "Поле Пароль обязательно для заполнения",
      minlength: jQuery.validator.format("Минимальная длинна пароля  {0} символов")
    },
    confirmpassword: {
      required: "Поле Пароль обязательно для заполнения",
      minlength: jQuery.validator.format("Минимальная длинна пароля  {0} символов"),
      equalTo: "Пароли не совпадают"
    }
  },
  submitHandler: function () {
    var data = {};
    $('#Registration').find('input, button').each(function () {
      data[this.name] = $(this).val();
    });
    $.ajax({
      url: "/ajax/Authorization/registr",
      type: "POST",
      dataType: "json",
      data: data,
      success: function (data) {
        AlertSwal(data);
        if(data.status !== true) return false;
        $('input').val('');
        $('.tab-group').find('li').each(function () {
        $(this).toggleClass('active');
        });
        var link = '#login';
        $('.tab-content > div').not(link).hide();
        $(link).fadeIn(600);
      }
    });
  }
});