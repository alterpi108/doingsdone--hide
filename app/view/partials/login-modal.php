<div class="modal">
    <button class="modal__close" type="button" name="button" onclick="location.href = '/'">Закрыть</button>

    <?php if ($firstLogin): ?>
        <h2 class="modal__heading">Теперь вы можете войти, используя свой email и пароль</h2>
    <?php else: ?>
        <h2 class="modal__heading">Вход на сайт</h2>
    <?php endif; ?>

    <?php if ($loginFailed): ?>
        <p class="error-massage">Вы ввели неверный email/пароль</p>
    <?php endif; ?>

    <form class="form" action="/login" method="post">
        <!-- Email -->
        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>

            <?php if (array_key_exists('email', $valid) && ! $valid['email']): ?>
                <input class="form__input  form__input--error" type="text" name="email" id="email" value="<?= htmlspecialchars($value['email'] ?? '') ?>" placeholder="Введите e-mail">
                <p class="form__message">E-mail введён некорректно</p>
            <?php else: ?>
                <input class="form__input" type="text" name="email" id="email" value="<?= htmlspecialchars($value['email'] ?? '') ?>" placeholder="Введите e-mail">
            <?php endif; ?>
        </div>

        <!-- Пароль -->
        <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>

            <?php if (array_key_exists('password', $valid) && ! $valid['password']): ?>
                <input class="form__input  form__input--error" type="password" name="password" id="password" value="" placeholder="Введите пароль">
                <p class="form__message">Пароль введён некорректно</p>
            <?php else: ?>
                <input class="form__input" type="password" name="password" id="password" value="" placeholder="Введите пароль">
            <?php endif; ?>
        </div>

        <div class="form__row">
            <label class="checkbox">
                <input class="checkbox__input visually-hidden" type="checkbox" checked>
                <span class="checkbox__text">Запомнить меня</span>
            </label>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Войти">
        </div>
    </form>
</div>
