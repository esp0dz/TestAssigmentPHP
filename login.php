<?php  
require 'db.php';

$stmt = $pdo->prepare("SELECT * FROM admins WHERE login = :login");
$stmt->execute(['login' => $_POST['login']]);
if (!$stmt->rowCount()) {
    flash('Пользователь с такими данными не зарегистрирован');
    header('Location: index.php');
    die;
}
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (password_verify($_POST['password'], $user['password'])) {
    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        $newHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = pdo()->prepare('UPDATE `admins` SET `password` = :password WHERE `login` = :login');
        $stmt->execute([
            'login' => $_POST['login'],
            'password' => $newHash,
        ]);
    }
    $_SESSION['auth'] = 'Y';
    header('Location: admin.php');
    die;
}

flash('Пароль неверен');
header('Location: index.php');