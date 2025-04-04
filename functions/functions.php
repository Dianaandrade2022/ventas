<?php

function sanitizeInput($input)
{
  return htmlspecialchars(trim($input));
}

function validateEmail($email)
{
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password)
{
  return strlen($password) > 3;
}
?>