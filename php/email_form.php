<?php
  // Import necessary PHP mailer classes for sending email.
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  // Import the site config file to access the target email address, reCaptcha
  // secret key and the smtp password for access to Gmail API.
  require("<path>/config.php");

  // If we have recieved a reCaptcha validation request token then...
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['recaptcha_response'])) {

    $recaptcha_api_url = 'https://www.google.com/recaptcha/api/siteverify';

    // Get the Google reCaptcha token that was saved to the hidden input field
    // of the form.
    $recaptcha_response = $_POST['recaptcha_response'];

    // Build the validation request and send it to Google, it returns
    // a response. Then decode JSON data of API response to an array.
    $response_data = file_get_contents($recaptcha_api_url . '?secret=' .
      $recaptcha_secret_key . '&response=' . $recaptcha_response);
    $response_data = json_decode($response_data);

    // Check if the reCaptcha API verification response is valid.
    if ($response_data->score < 0.5) {
      alert("reCAPTCHA could not validate form data! Try again.");
      die();
    }

    // Access the the form submission data through the $_POST[] array.
    // Use the filter_var() method on form submission data to remove
    // illegal characters preventing malicious code injections.
    $full_name = filter_var($_POST['full_name'],FILTER_SANITIZE_STRING);
    $sender_email = filter_var($_POST['email'],FILTER_SANITIZE_STRING);
    $phone_number = filter_var($_POST['phone_number'],FILTER_SANITIZE_NUMBER_INT);
    $subject = filter_var($_POST['subject'],FILTER_SANITIZE_SPECIAL_CHARS);
    $message = filter_var($_POST['message'],FILTER_SANITIZE_STRING);

    // Validate submitted form data.
    if (!filter_var($sender_email, FILTER_VALIDATE_EMAIL)) {
      alert("Invalid email address supplied!");
      die();
    }

    require_once "PHPMailer.php";
    require_once "SMTP.php";
    require_once "Exception.php";

    // Setup the email body.
    $email_body = "You have received a message from $full_name." . PHP_EOL .
                  "Email: $sender_email" . PHP_EOL .
                  "Phone Number: $phone_number" . PHP_EOL .
                  "Message:" . PHP_EOL .
                  "  $message";

    // Use nl2br() to insert a <br> tag before each new line. Ensures each line
    // will be displayed in the email body as a new line.
    $email_body = nl2br($email_body);

    // Create a new mailer object.
    $mail = new PHPMailer(true);

    // Apply the necessary SMTP settings to the mailer object.
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = $target_email;
    $mail->Password = $smtp_password;
    $mail->Port = 587; // 465 for SSL, use 587 for TSL
    $mail->SMTPSecure = "tls";

    // Apply the necessary email settings to the mailer object.
    $mail->isHTML(true);
    $mail->CharSet = "UTF-8";
    $mail->Encoding = "base64";
    $mail->setFrom($target_email, $full_name);
    $mail->addAddress($target_email);
    $mail->Subject = $subject;
    $mail->Body = $email_body;

    // Send email to the target email address containing the submitted form data.
    try {
      $mail->send();
      echo "<script type='text/javascript'>" .
      "alert('Message has been sent.');" .
      "window.location.href='https://cqueltech.com';" .
      "</script>";
      die();
    } catch (Exception $e) {
      echo "<script type='text/javascript'>" .
      "alert('Mailer error: " . $mail->ErrorInfo . "');" .
      "history.back();" .
      "</script>";
      die();
    }
  }

  /*
   * A function to display an alert containing the supplied message.
   */
  function alert($alert_msg) {
    echo "<script type='text/javascript'>alert('$alert_msg');</script>";
  }
?>
