<?php
header('Content-Type: text/html; charset=utf-8');
session_start();

$dns = 'pgsql:host=localhost;port=5432;dbname=library';

try {
    $pdo = new PDO($dns, 'postgres', 'admin123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'UTF8'");
} catch (PDOException  $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

function flash(?string $message = null)
{
    if ($message) {
        $_SESSION['flash'] = $message;
    } else {
        if (!empty($_SESSION['flash'])) { ?>
          <div class="alert alert-danger mb-3">
              <?=$_SESSION['flash']?>
          </div>
        <?php }
        unset($_SESSION['flash']);
    }
}
?>