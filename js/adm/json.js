$('#form_new').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});

var response = '';
var ajax_options = {
  type: 'POST',
  url: 'index.php',
  data: '',
  dataType: 'text',
  success : function(text) {
    response = $.trim(text);
    if( response == 'done' ) {
      window.location = '/admin/' + current + '/';
    }
    else {
      alert(response);
      console.log(response);
    }
  },
  error: function(text) {
    response = $.trim(text);
    alert(response);
  }
};

  
function upd(id){ // Send form
  var div = '#form' + id;
  $(div).ajaxSubmit(ajax_options);
}


function upd_status(id){
  ajax_options.data = { upd_status : id, current : current };
  $.ajax(ajax_options);
}

function delete_by(id){
  ajax_options.data = { delete : id, current : current };
  $.ajax(ajax_options);
}

function duplicate(id){
  ajax_options.data = { duplicate : id, current : current };
  $.ajax(ajax_options);
}

function copy(id, name){
  swal({
    title: 'Создать копию "' + name + '" ?',
    type: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#e72365',
    confirmButtonText: 'ОК',
    cancelButtonText: 'Нет, я передумал',
  }).then(function () {
    swal(
    'Готово!',
    'Копия "' + name + '" создана',
    'success'
    );
    setTimeout(duplicate, 1500, id);
  }, 
  function (dismiss) {
    if (dismiss === 'cancel') {
      swal({
        title: 'Отменено',
        text: 'Ну и ладно :)',
        type: 'error',
        showCancelButton: false,
        timer: 1500
      })
    }
  })
}