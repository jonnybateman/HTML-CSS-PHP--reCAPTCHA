# HTML-CSS-PHP--reCAPTCHA

### Introduction

reCAPTCHA is a fraud detection service for websites and web applications that stops bots and other automated attacks while approving valid users.

This repository demonstrates a way of incorporating the Google reCAPTCHA API into a website to validate form data entered by a user. The form data is then compiled into an email and sent to the Gmail SMTP server via a PHPMailer object.

### Configuration

In order to use reCAPTCHA we irst need to register. Go to the [reCAPTCHA(https://www.google.com/recaptcha/intro/v3.html)] page and login with a Google account. When signed in, go to the 'Register a new site' box. Enter a suitable label (the website name), choose 'reCAPTCHA v3', add any required website domains and click 'Register'. Once registered you will be supplied with a site key and a secret key.

reCAPTCHA must be integrated on the client side and on the backend web server. On the client side a validation request token is obtained using the site key. Once the token is obtained it is used in conjunction with the secret key on the web server to submit the form data for validation. A response from the Google reCAPTCHA API will determine if the current user is indeed a valid user.

### Client-Side Integration

Lets take a look at the `index.php` file, it contains the form and the fields that we wish to include in our email. In the action attribute we shoud have the .php file that is responsible for 
