<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <!--JQuery library-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!--Load the Google reCaptcha JavaScript API-->
    <script src="https://www.google.com/recaptcha/api.js?render=6LcswdYlAAAAADxf11hO8zWQqXKEJFyl_z40L-Tk"></script>
    <!--The button click function grabs a hidden form input and passes the token to the value of the input.-->
    <script>
        $(document).ready(function() {
          $(document).on('click', '#submit-btn', function (event) {
            event.preventDefault();
            grecaptcha.ready(function() {
              grecaptcha.execute('6LcswdYlAAAAADxf11hO8zWQqXKEJFyl_z40L-Tk', { action: 'submit' }).then(function(token) {
                $('#recaptchaResponse').val(token);
              });
            });
            // Now that we have the token submit the form. 'email_form.php' on web server is invoked. This .php validation
            // script will send the token, along with the form data entries, to Google's reCaptcha API for validation.
            $('#contact-form').submit();
          });
        });
    </script>
    <!--Website Custom Stylesheet-->
    <link rel="stylesheet" href="css/styles.css">
  </head>

  <body>
    <section class="contact" id="contact">
      <h2 class="section-heading">Contact Me</h2>
      <form method="post" id="contact-form" action="php/email_form.php">
        <div class="input-box">
          <div class="input-field form-bg-animation">
            <input type="text" placeholder="Full Name" name="full_name" required>
          </div>
          <div class="input-field form-bg-animation">
            <input type="text" placeholder="Email" name="email" required>
          </div>
        </div>
        <div class="input-box">
          <div class="input-field form-bg-animation">
            <input type="text" placeholder="Mobile Number" name="mobile_number">
          </div>
          <div class="input-field form-bg-animation">
            <input type="text" placeholder="Email Subject" name="subject" required>
          </div>
        </div>
        <div class="textarea-field form-bg-animation">
          <textarea cols="30" rows="10" placeholder="Your message" name="message" required></textarea>
        </div>
        <!-- Hidden input to store reCaptcha request token, binds the token to the form.-->
        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
        <!-- Input of type button, onClick will invoke a Google reCaptcha request returning the
             necessary token to allow us to send form data to reCaptcha API for validation.-->
        <input type="button" name="submit_btn" id="submit-btn" value="Send Message" class= "btn">
      </form>
    </section>
  </body>
</html>
