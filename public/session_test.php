<?php
session_start();
if (!isset($_SESSION['test'])) {
    $_SESSION['test'] = 'Session OK';
}
echo 'Session: ' . $_SESSION['test'];