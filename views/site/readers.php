<div class="container">
    <div class="left">
        <h1>Читатели</h1>

        <button class="create-button">Ввести нового читателя</button>
        <div class="list">
            <?php
            foreach ($readers as $reader) {
                echo '<a href="#" class="reader-link button" data-reader-id="' . $reader->id . '"
                data-last-name="' . htmlspecialchars($reader->last_name, ENT_QUOTES, 'UTF-8') . '"
                data-first-name="' . htmlspecialchars($reader->first_name, ENT_QUOTES, 'UTF-8') . '"
                data-patronym="' . htmlspecialchars($reader->patronym, ENT_QUOTES, 'UTF-8') . '"
                data-address="' . htmlspecialchars($reader->address ?? '', ENT_QUOTES, 'UTF-8') . '"
                data-phone-number="' . htmlspecialchars($reader->phone_number ?? '', ENT_QUOTES, 'UTF-8') . '">
                ' . $reader->last_name . ' ' . $reader->first_name . ' ' . $reader->patronym . '</a>';
            }
            ?>
        </div>
    </div>
    <div class="right" style="display: none;">
        <h2>Читатель</h2>
        <input type="number" placeholder="Номер читательского билета">
        <form class="info-form" action="/readers/<?= $reader->id ?? '' ?>" method="POST">
            <div>
                <label for="last-name">Фамилия</label>
                <input type="text" id="last-name" name="last-name" value="">
            </div>

            <div>
                <label for="first-name">Имя</label>
                <input type="text" id="first-name" name="first-name" value="">
            </div>

            <div>
                <label for="patronym">Отчество</label>
                <input type="text" id="patronym" name="patronym" value="">
            </div>

            <div>
                <label for="address">Адрес</label>
                <input type="text" id="address" name="address" value="">
            </div>

            <div>
                <label for="phone-number">Телефон</label>
                <input type="number" id="phone-number" name="phone-number" value="">
            </div>

            <a href="#" class="info-link">Арендованные читателем книги</a>

            <div class="button-container">
                <button class="white-button button">Выдать книгу</button>
                <button class="black-button button" type="submit">Сохранить изменения</button>
            </div>
            <div class="button-container">
                <button class="white-button button">Принять книгу</button>
                <button class="black-button button">Удалить читателя</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const readerLinks = document.querySelectorAll('.reader-link');
        const rightDiv = document.querySelector('.right');
        const lastNameInput = document.getElementById('last-name');
        const firstNameInput = document.getElementById('first-name');
        const patronymInput = document.getElementById('patronym');
        const addressInput = document.getElementById('address');
        const phoneNumberInput = document.getElementById('phone-number');

        readerLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Предотвращаем переход по ссылке

                // Получаем данные из data-* атрибутов
                const lastName = this.dataset.lastName;
                const firstName = this.dataset.firstName;
                const patronym = this.dataset.patronym;
                const address = this.dataset.address;
                const phoneNumber = this.dataset.phoneNumber;

                // Заполняем поля формы
                lastNameInput.value = lastName;
                firstNameInput.value = firstName;
                patronymInput.value = patronym;
                addressInput.value = address;
                phoneNumberInput.value = phoneNumber;

                // Показываем блок .right
                rightDiv.style.display = 'flex';
            });
        });
    });
</script>