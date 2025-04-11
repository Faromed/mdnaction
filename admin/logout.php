<?php
require 'inc/auth.php';
logout();
header('Location: index.php');
exit;
?>