<?php  
require '../db.php';
if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Не передан ID книги']);
    exit;
}
if (!isset($_POST['tableName'])) {
    echo json_encode(['success' => false, 'message' => 'Не передано название таблицы']);
    exit;
}

$id = $_POST['id'];
$tableName = $_POST['tableName'];

$stmt = $pdo->prepare("DELETE FROM {$tableName} WHERE id = :id");
try {
    $stmt->execute([':id' => $id]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>