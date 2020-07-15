$(document).ready(function () {
  $("div.rooms").on("click", function (e) {
    e.preventDefault();
    const idAttr = this.id;
    roomId = idAttr.substr(6);
    // console.log(roomId);

    // Extract values from the form
    let data = {
      action: "emp_chat_goto",
      roomId: roomId,
    };

    // Make AJAX request
    jQuery.get(emp_params.ajaxurl, data, (resp) => {
      $(".container").replaceWith(resp);
    });
  });

  $(".css-checkbox").on("click", function () {
    let data;
    let checked = $(this).prop("checked");
    const idAttr = this.id;
    let roomTopicId = idAttr.substr(8);

    console.log(roomTopicId);

    if (checked == true) {
      data = {
        action: "emp_topic_completion",
        roomTopicId: roomTopicId,
        checked: 1,
      };
    } else if (checked == false) {
      data = {
        action: "emp_topic_completion",
        roomTopicId: roomTopicId,
        checked: 0,
      };
    }

    jQuery.post(emp_params.ajaxurl, data, (resp) => {
      console.log(resp);
    });
  });
});
