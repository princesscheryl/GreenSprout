<?php
session_start();
session_destroy();
header('Location: ../view/user_register.php');
exit();
?> 