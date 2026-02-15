<?php  
require '../db.php';
if (!isset($_POST['tableName'])) {
    die('Не передано название таблицы');
}
$tableName = $_POST['tableName'];
if ($tableName == 'books') {
    if (!empty($_POST['title']) && !empty($_POST['author_id'])) {
        $title = $_POST['title'];
        $author_id = $_POST['author_id'];

        $stmt = $pdo->prepare("
            INSERT INTO books (title, author_id)
            VALUES (:title, :author_id)
            ON CONFLICT (title) DO NOTHING
        ");

        try {
            $stmt->execute([
                ':title' => $title,
                ':author_id' => $author_id
            ]);
            echo "Книга успешно добавлена!";
        } catch (PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
        }
    } else {
        echo 'Заполните все поля!';
    }
} elseif ($tableName == 'authors') {
    if (!empty($_POST['name'])) {
        $name = $_POST['name'];

        $stmt = $pdo->prepare("
            INSERT INTO authors (name)
            VALUES (:name)
            ON CONFLICT (name) DO NOTHING
        ");

        try {
            $stmt->execute([
                ':name' => $name
            ]);
            echo "Автор успешно добавлен!";
        } catch (PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
        }
    } else {
        echo 'Заполните все поля!';
    }
}

?>
