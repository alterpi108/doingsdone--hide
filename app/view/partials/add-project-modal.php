<div class="modal">
    <button class="modal__close" type="button" name="button" onclick="location.href = '/'">Закрыть</button>

    <h2 class="modal__heading">Добавление проекта</h2>

    <form class="form" method="post" action="/add-project">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <?php if (array_key_exists('name', $valid) && ! $valid['name']): ?>
                <input class="form__input  form__input--error" type="text" name="name" id="project_name"
                       value="<?= $value['name'] ?>" placeholder="Введите название">
                <p class="form__message">Имя введено некорректно</p>
            <?php else: ?>
                <input class="form__input" type="text" name="name" id="project_name"
                       value="<?= $value['name'] ?>" placeholder="Введите название">
            <?php endif; ?>
        </div>

        <div class="form__row  form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</div>
