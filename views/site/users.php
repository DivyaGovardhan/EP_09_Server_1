<div class="container">
    <div class="left">
        <h1>Библиотекари</h1>

        <button class="create-button" id="create-user-btn">Ввести нового библиотекаря</button>
        <div class="list" id="users-list">
            <?php foreach ($users as $user): ?>
                <a href="#" class="button user-item"
                   data-user-id="<?= $user->id ?>"
                   data-last-name="<?= htmlspecialchars($user->last_name ?? '') ?>"
                   data-first-name="<?= htmlspecialchars($user->first_name ?? '') ?>"
                   data-patronym="<?= htmlspecialchars($user->patronym ?? '') ?>"
                   data-login="<?= htmlspecialchars($user->login) ?>"
                   data-is-admin="<?= $user->is_admin ? '1' : '0' ?>">
                    <?= htmlspecialchars($user->last_name ?? '') ?>
                    <?= htmlspecialchars($user->first_name ?? '') ?>
                    <?= htmlspecialchars($user->patronym ?? '') ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="right" id="user-form-container" style="display: none;">
        <h2>Сотрудник</h2>
        <form class="info-form" id="user-form">
            <input type="hidden" id="user-id" name="id" value="">

            <div>
                <label for="last_name">Фамилия</label>
                <input type="text" id="last_name" name="last_name" value="">
            </div>

            <div>
                <label for="first_name">Имя</label>
                <input type="text" id="first_name" name="first_name" value="">
            </div>

            <div>
                <label for="patronym">Отчество</label>
                <input type="text" id="patronym" name="patronym" value="">
            </div>

            <div>
                <label for="login">Логин</label>
                <input type="text" id="login" name="login" value="">
            </div>

            <div>
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" value="">
            </div>

            <div>
                <label for="is_admin">Администратор</label>
                <input type="checkbox" id="is_admin" name="is_admin" value="1">
            </div>

            <div class="button-container">
                <button type="submit" class="black-button button">Сохранить изменения</button>
            </div>
            <div class="button-container">
                <button type="button" id="delete-btn" class="black-button button">Удалить сотрудника</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formContainer = document.getElementById('user-form-container');
        const createBtn = document.getElementById('create-user-btn');
        const userForm = document.getElementById('user-form');
        const deleteBtn = document.getElementById('delete-btn');

        // Показать форму создания
        createBtn.addEventListener('click', function() {
            userForm.reset();
            document.getElementById('user-id').value = '';
            document.getElementById('is_admin').checked = false;
            formContainer.style.display = 'flex';
        });

        // Обработка выбора пользователя
        document.querySelectorAll('.user-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                // Заполняем форму данными пользователя
                document.getElementById('user-id').value = this.getAttribute('data-user-id');
                document.getElementById('last_name').value = this.getAttribute('data-last-name');
                document.getElementById('first_name').value = this.getAttribute('data-first-name');
                document.getElementById('patronym').value = this.getAttribute('data-patronym');
                document.getElementById('login').value = this.getAttribute('data-login');
                document.getElementById('is_admin').checked = this.getAttribute('data-is-admin') === '1';
                document.getElementById('password').value = '';

                formContainer.style.display = 'flex';
            });
        });

        // Обработка отправки формы
        userForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Собираем данные вручную, чтобы избежать проблем с FormData
            const data = {
                id: document.getElementById('user-id').value,
                last_name: document.getElementById('last_name').value,
                first_name: document.getElementById('first_name').value,
                patronym: document.getElementById('patronym').value,
                login: document.getElementById('login').value,
                password: document.getElementById('password').value,
                is_admin: document.getElementById('is_admin').checked ? 1 : 0
            };

            console.log("Отправляемые данные:", data); // Для отладки

            fetch('/save-user', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Данные успешно сохранены');
                    location.reload();
                } else {
                    alert('Ошибка сохранения: ' + (data.message || ''));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при сохранении');
            });
        });

        // Обработка удаления
        deleteBtn.addEventListener('click', function() {
            const userId = document.getElementById('user-id').value;
            if (!userId) {
                alert('Пользователь не выбран');
                return;
            }

            if (confirm('Вы уверены, что хотите удалить этого сотрудника?')) {
                fetch('/delete-user?id=' + userId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Пользователь успешно удален');
                        location.reload();
                    } else {
                        alert('Ошибка удаления: ' + (data.message || ''));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка при удалении');
                });
            }
        });
    });
</script>