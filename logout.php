<?php
require 'config.php';
require 'inc/session.php';

$_session->logout();
header('Location: index.php');