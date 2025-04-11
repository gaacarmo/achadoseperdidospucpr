<?php
require_once 'conecta_db.php';

session_start();
session_destroy();
unset($_COOKIE['usuario']);
setcookie('usuario','');

header('Location: include.php?dir=paginas&file=login');