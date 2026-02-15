<?php 
require '../db.php';

$q = $_GET['q'] ?? '';
if (!$q) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT b.id, b.title, a.name AS author, COUNT(rl.id) AS readers_count
    FROM books b
    JOIN authors a ON b.author_id = a.id
    LEFT JOIN reader_logs rl ON rl.book_id = b.id
    WHERE b.title ILIKE :q OR a.name ILIKE :q
    GROUP BY b.id, b.title, a.name
    ORDER BY readers_count DESC
    LIMIT 50
");

try {
    $stmt->execute([':q' => "%$q%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* добавляем читателей */
    if ($results) {        
        $stmt_log = $pdo->prepare("INSERT INTO reader_logs (book_id) VALUES (:book_id)");
        try {
            foreach ($results as $item) {
                $stmt_log->execute([':book_id' => $item['id']]);
            }    
        } catch (PDOException $e) {
            echo "Ошибка: " . $e->getMessage();
        }
    }

    echo json_encode($results);
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
