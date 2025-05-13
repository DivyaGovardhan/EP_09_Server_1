<div class="container">
    <div class="left">
        <h1>Книги</h1>

        <button class="create-button">Ввести новую книгу</button>
        <div class="list">
            <?php
            foreach ($books as $book) {
                echo '<a href="#" class="book-link button" data-book-id="' . $book->id . '"
                       data-name="' . htmlspecialchars($book->name, ENT_QUOTES, 'UTF-8') . '"
                       data-edition="' . htmlspecialchars($book->edition, ENT_QUOTES, 'UTF-8') . '"
                       data-edition-number="' . htmlspecialchars($book->edition_number ?? '', ENT_QUOTES, 'UTF-8') . '"
                       data-price="' . htmlspecialchars($book->price ?? '', ENT_QUOTES, 'UTF-8') . '"
                       data-annotation="' . htmlspecialchars($book->annotation ?? '', ENT_QUOTES, 'UTF-8') . '">
                        ' . htmlspecialchars($book->name, ENT_QUOTES, 'UTF-8') . '</a>';
            }
            ?>
        </div>
    </div>

    <div class="right" style="display: none;">
        <h2>Книга</h2>
        <form class="info-form">
            <div>
                <label for="name">Название</label>
                <input type="text" id="name" name="name" value="">
            </div>

            <div>
                <label for="edition">Издание</label>
                <input type="date" id="edition" name="edition" value="">
                <label for="edition-number">№</label>
                <input type="number" id="edition-number" name="edition-number" value="">
            </div>

            <div>
                <label for="price">Цена</label>
                <input type="text" id="price" name="price" value="">
            </div>

            <div>
                <label for="authors">Авторы</label>
                <div class="multiselect-container">
                    <select multiple>
                        <option>Рудольф Эрих Распе</option>
                        <option>Николай Васильевич Гоголь-Яновский</option>
                    </select>
                </div>
            </div>

            <div>
                <p>Выдана</p>
                <a href="#" class="info-link">Южаков Алексндр Сергеевич</a>
            </div>

            <label for="annotation">Аннотация</label>
            <textarea id="annotation"></textarea>


            <a href="#" class="info-link">История аренды</a>

            <div class="button-container">
                <button class="white-button button">Выдать книгу</button>
                <button class="black-button button">Сохранить изменения</button>
            </div>
            <div class="button-container">
                <button class="white-button button">Принять книгу</button>
                <button class="black-button button">Удалить книгу</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bookLinks = document.querySelectorAll('.book-link');
        const rightDiv = document.querySelector('.right');
        const nameInput = document.getElementById('name');
        const editionInput = document.getElementById('edition');
        const editionNumberInput = document.getElementById('edition-number');
        const priceInput = document.getElementById('price');
        const annotationTextarea = document.getElementById('annotation');

        bookLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();

                const name = this.dataset.name;
                const edition = this.dataset.edition;
                const editionNumber = this.dataset.editionNumber;
                const price = this.dataset.price;
                const annotation = this.dataset.annotation;

                nameInput.value = name;
                editionInput.value = edition;
                editionNumberInput.value = editionNumber;
                priceInput.value = price;
                annotationTextarea.value = annotation;

                rightDiv.style.display = 'flex';
            });
        });
    });
</script>