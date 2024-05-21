<!DOCTYPE html>
<html>
<body>
  <p>Hi {{ $firstname }},</p>

  <p>We have received a request to reset your password for your account at <a href="https://dashboard.mntnrecords.com">https://dashboard.mntnrecords.com</a>.</p>

  <p>Your new temporary password is: <strong>{{ $password }}</strong></p>

  <p>We strongly advise you to log into your account with this temporary password and change it to a new, unique password as soon as possible. For your security, please ensure your new password is complex, using a mix of letters, numbers, and special characters.</p>

  <p>If you did not request a password reset, please contact Raphael or our team immediately!</p>
</body>
</html>