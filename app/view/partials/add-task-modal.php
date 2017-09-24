<?php
//
//
//var_dump($value);
//
//
//?>






<div class="modal">
    <button class="modal__close" type="button" name="button" onclick="location.href = '/'">Закрыть</button>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form" action="/add-task" method="post" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>



            <?php if (array_key_exists('name', $valid) && ! $valid['name']): ?>
                <input class="form__input  form__input--error" type="text" name="name" id="name"
                       value="<?= $value['name'] ?>" placeholder="Введите название">
            <?php else: ?>
                <input class="form__input" type="text" name="name" id="name"
                       value="<?= $value['name'] ?>" placeholder="Введите название">
            <?php endif; ?>


        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select" name="project" id="project">
                <?php foreach ($projects as $project): ?>
                    <option value="<?= $project['id'] ?>"><?= $project['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения <sup>*</sup></label>

            <?php if (array_key_exists('date', $valid) && ! $valid['date']): ?>
                <input class="form__input form__input--date  form__input--error" type="text" name="date" id="date"
                       value="<?= $value['date'] ?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
            <?php else: ?>
                <input class="form__input form__input--date" type="text" name="date" id="date"
                       value="<?= $value['date'] ?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
            <?php endif; ?>
        </div>

        <div class="form__row">
            <label class="form__label">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="file" id="file" value="">
                <label class="button button--transparent" for="file">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</div>
