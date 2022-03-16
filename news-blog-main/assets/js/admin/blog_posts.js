$(document).ready(function () {

  $(document).on('submit', '#add-blog', function (e) {
    e.preventDefault();
    $('.all_errors').empty();
    var formObj = $(this);
    $.ajax({
      url: base_url + 'process-add-blog',
      data: new FormData(this),
      method: 'POST',
      dataType: 'JSON',
      contentType: false,
      processData: false,
      success: function (data) {
        if (data.response == true) {
          formObj.trigger('reset');
          swal({
            title: "Congratulations!",
            text: data.success,
            icon: "success",
            button: "OK!",
          }).then((value) => {
            location.reload();
          });
        } else {
          errors(data.errors);
          $('.image_error').html(data.image_errors);
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