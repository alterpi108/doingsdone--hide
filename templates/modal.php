<div class="modal">
    <button class="modal__close" type="button" name="button">Закрыть</button>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form" method="POST" action="index.php" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input <?php if (! $name_valid) print("form__input--error"); ?>"
                   type="text" name="name" id="name" value="<?= $name ?>" placeholder="Введите название">

            <?php if (! $name_valid) print('<span class="form__error">Имя оязательно</span>'); ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select" name="project" id="project">
                <option value="Входящие">Входящие</option>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения <sup>*</sup></label>

            <input class="form__input form__input--date <?php if (! $date_valid) print("form__input--error"); ?>"
                   type="text" name="date" id="date" value="<?= $date ?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">

            <?php if (! $date_valid) print('<span class="form__error">Введите правильную дату</span>'); ?>
        </div>

        <div class="form__row">
            <label class="form__label">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="preview" id="preview" value="">

                <label class="button button--transparent" for="preview">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</div>