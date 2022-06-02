jQuery(function ($) {
  $("#filter").submit(function () {
    var filter = $("#filter");
    console.log(" fs: " + filter.serialize());
    $.ajax({
      url: filter.attr("action"),
      data: filter.serialize(), // form data
      type: filter.attr("method"), // POST
      beforeSend: function (xhr) {
        filter.find("button").text("Processing..."); // changing the button label
      },
      success: function (data) {
        filter.find("button").text("Apply filter"); // changing the button label back
        $("#object_archive").html(data); // insert data
      },
    });
    return false;
  });
  $("#diapason").change(function () {
    var diapason_form = $("#diapason_form");
    diapason_form
      .find("#range_value")
      .text(diapason_form.find("#diapason").val());
    console.log(" dfs: " + diapason_form.serialize());
    let data1 = [];
    let data2 = [];
    data1["action"] = "my_nearest_function";
    data2 = diapason_form.serialize();
    $.ajax({
      url: diapason_form.attr("action"),
      data: diapason_form.serialize(), // form data
      type: diapason_form.attr("method"), // POST
      beforeSend: function (xhr) {
        diapason_form.find("#nearest").text("Processing..."); // changing the button label
      },
      success: function (data) {
        //diapason_form.find('button').text('Apply filter'); // changing the button label back
        $("#nearest").html(data); // insert data
      },
    });
    return false;
  });
});
