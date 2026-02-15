<?php
try {
    $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=postgres', 'postgres', 'admin123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'UTF8'");

    $pdo->exec("DROP DATABASE IF EXISTS library");
    $pdo->exec("CREATE DATABASE library WITH ENCODING 'UTF8' OWNER postgres LC_COLLATE = 'ru_RU' LC_CTYPE   = 'ru_RU' TEMPLATE = template0");
} catch (PDOException $e) {
    die("Ошибка создания базы: " . $e->getMessage());
}

try {
    $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=library', 'postgres', 'admin123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'UTF8'");
} catch (PDOException $e) {
    die("Ошибка подключения к library: " . $e->getMessage());
}

$pdo->exec("DROP TABLE IF EXISTS reader_logs CASCADE");
$pdo->exec("DROP TABLE IF EXISTS books CASCADE");
$pdo->exec("DROP TABLE IF EXISTS authors CASCADE");
$pdo->exec("DROP TABLE IF EXISTS admins CASCADE");

$pdo->exec("
CREATE TABLE authors (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL
);
CREATE TABLE books (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) UNIQUE NOT NULL,
    author_id INTEGER NOT NULL,
    CONSTRAINT books_author FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE
);
CREATE INDEX idx_books_author_id ON books(author_id);
CREATE TABLE reader_logs (
    id SERIAL PRIMARY KEY,
    book_id INTEGER NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT log_books FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);
CREATE INDEX idx_reader_logs_book_id ON reader_logs(book_id);
CREATE TABLE admins (
    id SERIAL PRIMARY KEY,
    login VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);
");

$pdo->exec("
INSERT INTO admins (login, password) 
VALUES ('admin', '$2y$10\$loqucup11.3DL1fgDWanoettFpFJuFFd0fY6BZyiP698ZqvA4tmuy')
ON CONFLICT (login) DO NOTHING
");

$authors = ['Александр Пушкин', 'Лев Толстой', 'Роберт Сапольски'];
$stmt = $pdo->prepare("INSERT INTO authors (name) VALUES (:name) ON CONFLICT (name) DO NOTHING");
foreach ($authors as $name) {
    $stmt->execute([':name' => $name]);
}

$books = [
    'Капитанская дочка' => 1,
    'Записки примата' => 3,
    'Война и мир' => 2,
    'Пиковая дама' => 1,
    'Детство' => 2,
    'Кто мы?' => 3
];
$stmt = $pdo->prepare("INSERT INTO books (title, author_id) VALUES (:title, :author_id) ON CONFLICT (title) DO NOTHING");
foreach ($books as $title => $author_id) {
    $stmt->execute([':title' => $title, ':author_id' => $author_id]);
}
echo "Установка завершена успешно!";
?>
