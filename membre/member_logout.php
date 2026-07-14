<?php
session_start();
session_destroy();
header('Location: /membre/membre.php');
exit();
?>