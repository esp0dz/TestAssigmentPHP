<?php  
require '../db.php';
if (!isset($_POST['tableName'])) {
    die('Не передано название таблицы');
}
if (!isset($_POST['id'])) {
    echo 'Не передан ID книги';
    exit;
}

$tableName = $_POST['tableName'];
$id = $_POST['id'];

if ($tableName == 'books') {
    if (!empty($_POST['title']) && !empty($_POST['author_id'])) {
        $title = $_POST['title'];
        $author_id = $_POST['author_id'];

        $stmt = $pdo->prepare("
            UPDATE books
            SET title = :title,
                author_id = :author_id
            WHERE id = :id
        ");

        try {
            $stmt->execute([
                ':title' => $title,
                ':author_id' => $author_id,
                ':id' => $id
            ]);
            echo json_encode(['success' => true]);
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
            UPDATE authors
            SET name = :name
            WHERE id = :id
        ");

        try {
            $stmt->execute([
                ':name' => $name,
                ':id' => $id
            ]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
        }
    } else {
        echo 'Заполните все поля!';
    }
}
?>