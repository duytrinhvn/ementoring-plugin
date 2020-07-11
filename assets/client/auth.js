$(document).ready(function () {
  // Initialize element selectors
  const authForm = $("#login-form"),
    submitButton = $("#login-button"),
    usernameField = $("input[name='username']"),
    passwordField = $("input[name='password']"),
    nonceField = $("input[name='emp_auth']"),
    status = $("#status");

  // Form submission handler
  authForm.on("submit", (e) => {
    e.preventDefault();

    resetMessages();

    // Extract values from the form
    let data = {
      action: "emp_login_process",
      username: usernameField.val(),
      password: passwordField.val(),
      nonce: nonceField.val(),
    };

    // Check if all information in the form filled
    if (!data.username || !data.password) {
      status.html("Missing Data");
      status.addClass("text-danger");
      return;
    }

    // Update Submit button UI
    // submitButton.val("Logging in...");
    submitButton.prop("disabled", true);

    // Make AJAX request
    jQuery.post(emp_params.ajaxurl, data, (resp) => {
      // Process response object 'resp'
      resp = JSON.parse(resp);

      submitButton.prop("disabled", false);

      // Update page
      if (resp.status === false) {
        status.html(resp.message);
        status.addClass("text-danger");
      } else {
        status.html(resp.message);
        status.addClass("text-success");
        window.location.reload();
      }
    });
  });

  function resetMessages() {
    status.html("");
    status.removeClass("text-danger", "text-success");

    submitButton.prop("disabled", false);
  }
});
