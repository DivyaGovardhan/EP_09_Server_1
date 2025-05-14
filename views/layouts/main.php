<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>library system</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
<header>
    <div class="container">
        <div class="logo">
            <img src="../../public/img/logo.png" alt="logo">
            <h1>Library system</h1>
        </div>
        <nav>
            <?php if (app()->auth::check()): ?>
                <?php if (app()->auth::user()->is_admin): ?>
                    <a href="<?= app()->route->getUrl('/users') ?>" class="button">Пользователи</a>
                <?php else: ?>
                    <a href="<?= app()->route->getUrl('/readers') ?>" class="button">Читатели</a>
                    <a href="<?= app()->route->getUrl('/books') ?>" class="button">Книги</a>
                    <a href="<?= app()->route->getUrl('/authors') ?>" class="button">Авторы</a>
                <?php endif; ?>
                <a href="<?= app()->route->getUrl('/logout') ?>" class="button">Выйти</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>
    <?= $content ?? '' ?>
</main>
</body>
</html>
