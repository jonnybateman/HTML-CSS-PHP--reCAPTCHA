# HTML-CSS-PHP--reCAPTCHA

### Introduction

reCAPTCHA is a fraud detection service for websites and web applications that stops bots and other automated attacks while approving valid users.

This repository demonstrates a way of incorporating the Google reCAPTCHA API into a website to validate form data entered by a user. The form data is then compiled into an email and sent to the Gmail SMTP server via a PHPMailer object.

### Configuration

In order to use reCAPTCHA we irst need to register. Go to the [reCAPTCHA(https://www.google.com/recaptcha/intro/v3.html)] page and login with a Google account. When signed in, go to the 'Register a new site' box. Enter a suitable label (the website name), choose 'reCAPTCHA v3', add any required website domains and click 'Register'. Once registered you will be supplied with a site key and a secret key.

reCAPTCHA must be integrated on the client side and on the backend web server. On the client side a validation request token is obtained using the site key. Once the token is obtained it is used in conjunction with the secret key on the web server to submit the form data for validation. A response from the Google reCAPTCHA API will determine if the current user is indeed a valid user.

### Client-Side Integration

Lets take a look at the `index.php` file, it contains the form and the fields that we wish to include in our email. In the action attribute we should have the PHP file that is responsible for sending the validation request to Google, forming the email and submitting it to the Gmail SMTP server.

It is important that the submit button to start the process does not actually submit the form at this point. Submitting the form is done once we have obtained the reCAPTCHA validation request token.

    <button type="button" id="send-btn" class="btn">Send Message</button>
            
Note: the `type` atrribute is set to 'button' and not 'submit'. When the button is pressed an onClick event is initiated calling a JavaScript function. Ensure the following code snippet is included in your web page.
 
    <head>
      ...
      <script src="https://www.google.com/recaptcha/api.js?render=reCAPTCHA_site_key"></script>
      ...
    </head>
    <body>
      ...
      <form...>
      ...
      </form>
      <script>
        let sendBtn = document.getElementById("send-btn");

        // Retrieve a validation request token from the Google reCAPTCHA API and store it in the
        // hidden input element 'recaptchaResponse'. Once retrieved we can then submit the form.
        function getRecaptchaToken() {
          grecaptcha.ready(function() {
            grecaptcha.execute('6LcswdYlAAAAADxf11hO8zWQqXKEJFyl_z40L-Tk', { action: 'submit' }).then(function(token) {
              document.getElementById("recaptchaResponse").value = token;
              document.getElementById("contact-form").submit();
            });
          });
        }

        sendBtn.addEventListener("click", getRecaptchaToken, false);
      </script>
      ...
    </body>

   Replace 'reCAPTCHA_site_key' with your own site key.
   
When the token is obtained it is bound to a hidden `<input>` field of the form where it can be accessed later by the PHP validation script. Now that we have the token the final action of the onClick event is to submit the form.

### Backend Server Integration

When the form is submitted the form `action` attribute is invoked executing the validation PHP script. It builds a validation request which is posted to the reCAPTCHA Verify API. The `$recaptcha_response` is the token that we retrieved earlier. The response from the Verify API is formulated as a JSON file where we decode it to a series of key value pairs and store in an array.

    $recaptcha_api_url = 'https://www.google.com/recaptcha/api/siteverify';
    // Get the Google reCaptcha token that was saved to the hidden input field
    // of the form.
    $recaptcha_response = $_POST['recaptcha_response'];
    // Build and submit request.
    $response_data = file_get_contents($recaptcha_api_url . '?secret=' .
      $recaptcha_secret_key . '&response=' . $recaptcha_response);
    // Decode the JSON response to the request.
    $response_data = json_decode($response_data);
    
   Replace 'reCAPTCHA_secret_key' with your own secret key.
    
The value we care about from the response is the score. The score can be between 0.0 and 1.0, the higher the score the more likely it is that a legitimate user submitted the form. A low score means there is a good chance it was a bot or automated attack that initiated the form submision. If you find that the score is high enough then you can proceeed to compose the email and post it to the Gmail SMTP server.

A complete working example can be seen in this repository's code files.
