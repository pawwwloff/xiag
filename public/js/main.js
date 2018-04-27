$(document).on('submit', 'form', function (e) {
  e.preventDefault();
  var form = $(this);
  var url = form.attr('action');
  $.ajax({
		type: "POST",
		url: url,
		data: form.serializeArray(),
		success: function (data) {
			 $('#result').html(data);
		}
  });
})