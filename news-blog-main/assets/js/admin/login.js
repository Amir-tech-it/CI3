$(document).ready(function () {

  $(document).on('submit', '.form-signin', function (e) {
    e.preventDefault();
    var formObj = $(this);
    $('.all_errors').empty();
    $('.direct_access_error').hide();
    $.ajax({
      url: base_url + "admin-login",
      data: formObj.serializeArray(),
      type: "POST",
      dataType: "JSON",
      success: function (data) {
        if (data.response == true) {
          location.href = base_url + data.redirect_url;
        } else if (data.errors) {
          errors(data.errors);
          $('.password_error').html(data.password_error);
        }
      }
    });
  });


  function errors(errors = '') {
    $.each(errors, function (key, value) {
      $('.' + key + '_error').html(value);
    });
    return false;
  }

});