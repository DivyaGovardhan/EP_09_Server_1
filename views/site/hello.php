<div class="container">
    <div class="left">
        <h1>Читатели</h1>

        <button class="create-button">Ввести нового читателя</button>
        <div class="list">
            <a href="#" class="button">Иванов Иван Иванович</a>
        </div>
    </div>

    <div class="right">
        <h2>Читатель</h2>
        <input type="number" placeholder="Номер читательского билета">
        <form class="info-form">
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
                <button class="black-button button">Сохранить изменения</button>
            </div>
            <div class="button-container">
                <button class="white-button button">Принять книгу</button>
                <button class="black-button button">Удалить читателя</button>
            </div>
        </form>
    </div>
</div>