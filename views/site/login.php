<div class="container-login">
    <?php
    if (!app()->auth::check()):
    ?>
    <h1>Вход</h1>
    <form class="login-form" method="post" action="/login">
        <label for="login">Логин</label>
        <input type="text" id="login" name="login" required>

        <label for="password">Пароль</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Войти</button>
    </form>
    <?php endif; ?>
</div>
