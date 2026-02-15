<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Главная</title>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Авторизация для админов</h2>

        <?php require 'db.php'; flash(); ?>
        <form action="login.php" method="post" class="w-75 mx-auto my-5">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="login" id="login" placeholder="admin" required>
                <label for="login">Логин</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="password" placeholder="" required>
                <label for="password">Пароль</label>
            </div>
            <input type="submit" value="Войти" class="btn btn-outline-primary w-100 mt-3">
        </form>
    
        <h2 class="text-center">Поиск книг по наименованию/автору</h2>
        <div class="position-relative mt-4">
            <input type="text" class="form-control" placeholder="Название/автор книги" name="q" id="q">
            <button class="btn position-absolute top-0 end-0" type="button" id="search" onclick="searchItem()">
                <svg class="align-text-bottom" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 30 30"><path d="M 13 3 C 7.4886661 3 3 7.4886661 3 13 C 3 18.511334 7.4886661 23 13 23 C 15.396652 23 17.59741 22.148942 19.322266 20.736328 L 25.292969 26.707031 A 1.0001 1.0001 0 1 0 26.707031 25.292969 L 20.736328 19.322266 C 22.148942 17.59741 23 15.396652 23 13 C 23 7.4886661 18.511334 3 13 3 z M 13 5 C 17.430666 5 21 8.5693339 21 13 C 21 17.430666 17.430666 21 13 21 C 8.5693339 21 5 17.430666 5 13 C 5 8.5693339 8.5693339 5 13 5 z"></path></svg>
            </button>
        </div>
        <div id="searchResults" class="mt-3"></div>
    </div>
    <script>
        let input = document.getElementById('q');
        let resultContainer = document.getElementById('searchResults');
        input.addEventListener('keypress', e => {
            if (e.key === 'Enter') {
                searchItem();
            }
        });
        function searchItem() {
            let query = input.value.trim();
            if (!query) {
                resultContainer.innerHTML = '<p>Введите текст для поиска</p>';
                return;
            }

            fetch('ajax/search.php?q=' + query)
            .then(res => res.json())
            .then(data => {
                console.log(data);
                
                if (!data.length) {
                    resultContainer.innerHTML = '<p>Ничего не найдено</p>';
                    return;
                }
                let html = '<ol class="list-group list-group-numbered">';
                data.forEach(item => {
                    html += `<li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                            <div class="fw-bold">${item.title}</div>
                            Автор: ${item.author}
                            </div>
                            <span class="badge text-bg-primary rounded-pill">Читателей: ${item.readers_count}</span>
                        </li>
                    `;
                });
                html += '</ol>';
                resultContainer.innerHTML = html;
            })
            .catch(err => console.log(err));
        }
    </script>
</body>
</html>