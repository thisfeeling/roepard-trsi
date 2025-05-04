<?php
// Cerrar sesion
session_start();
session_destroy();
header("Location: /../../trsi/index.php");
exit();
?>