<?php
declare(strict_types=1);
require __DIR__ . '/../app/bootstrap.php';
require_post();

$_SESSION = [];
session_regenerate_id(true);
flash('success', 'Você saiu da sua conta.');
redirect('/');
