function upd(){ // Отправка формы
  var div = '#auth';
  $(div).ajaxSubmit( options );
}

var response = '';

var swal_default = {
  title: 'Упс...',
  text: '',
  type: 'error',
  showConfirmButton: false,
  showCancelButton: true,
  cancelButtonColor: '#e72365',
  cancelButtonText: 'Я попробую снова!'
};

var options2 = {
    type: 'POST',
    url: 'index.php',
    data: '',
    dataType: 'text',
    success : function(text) {
      response = $.trim(text);
      alert(response);
    },
    error: function(text) {
      response = $.trim(text);
      alert(response);
    }
};


var options = {
  type: 'POST',
  url: 'index.php',
  data: '',
  dataType: 'text',
  success : function(text) {
    response = $.trim(text);
    if ( response == 'fuckoff' ){
      swal_default.text = 'Неправильный логин или пароль';
      swal(swal_default).then(function (){}, function (dismiss) {
        window.location = window.location.href;
      })
    }

    else if ( response == 'sms_not_confirmed' ){
      swal_default.text = 'Ошибка авторизации';
      swal(swal_default).then(function (){}, function (dismiss) {
        window.location = window.location.href;
      })
    }

    else if( response == 'done' ) {
      swal({
        title: 'Добро пожаловать!',
        type: 'success',
        showConfirmButton: false,
        showCancelButton: false,
        timer: 1500
      }).then(function (){}, function (dismiss) {
        window.location = window.location.href;
      })
    }
  },

  error: function(text) {
      response = $.trim(text);
      alert(response);
    }
};
  
$(document).keypress(function (e) {
  if (e.which == 13) {
    upd();
  }
});

swal({
  title: '<h1>Login</h1>',
  allowOutsideClick: false,
  allowEscapeKey: false,
  showCancelButton: false,
  showConfirmButton: false,
  cancelButtonColor: '#e72365',
  width: 300,
  html:  '<form id="auth" class="login-form" method="post" action="">'
    +  '<div class="control-group">'
    +  '<input type="text" name="login" id="login-name" class="login-field" value="" placeholder="Логин" maxlength="32" pattern="^[A-Za-z0-9]*" title="Используйте только латиницу и цифры" required>'
    +  '<label class="login-field-icon fui-user" for="login-name"></label>'
    +  '</div>'
    +  '<div class="control-group">'
    +  '<input type="password" name="password" id="login-pass" class="login-field" value="" placeholder="Пароль" >'
    +  '<label class="login-field-icon fui-lock" for="login-pass"></label>'
    +  '</div>'
    +  '<input type="checkbox" id="captcha" value="true" name="im_not_a_robot">'
    +  '<div onclick="' + "upd();" + '"class="btn btn-primary btn-large btn-block">Войти</div>'
    +  '</form>'
});