<?= renderTemplate('partials/header', ['title' => 'Регистрация', 'overlay' => false]) ?>

<div class="page-wrapper">
    <div class="container container--with-sidebar">
        <header class="main-header">
            <a href="/">
                <img src="public/img/logo.png" width="153" height="42" alt="Логитип Дела в порядке">
            </a>
        </header>

        <div class="content">
            <section class="content__side">
                <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

                <a class="button button--transparent content__side-button" href="login">Войти</a>
            </section>

            <main class="content__main">
                <h2 class="content__main-heading">Регистрация аккаунта</h2>

                <form class="form" action="/signup" method="post">
                    <!-- E-mail -->
                    <div class="form__row">
                        <label class="form__label" for="email">E-mail <sup>*</sup></label>

                        <?php if (array_key_exists('email', $valid) && ! $valid['email']): ?>
                            <input class="form__input  form__input--error" type="text" name="email" id="email"
                                   value="<?= $value['email'] ?>" placeholder="Введите e-mail">
                            <p class="form__message">E-mail введён некорректно</p>
                        <?php else: ?>
                            <input class="form__input" type="text" name="email" id="email"
                                   value="<?= $value['email'] ?>" placeholder="Введите e-mail">
                        <?php endif; ?>
                    </div>

                    <!-- Пароль -->
                    <div class="form__row">
                        <label class="form__label" for="password">Пароль <sup>*</sup></label>

                        <?php if (array_key_exists('password', $valid) && ! $valid['password']): ?>
                            <input class="form__input  form__input--error" type="password" name="password" id="password"
                                   value="<?= $value['password'] ?>" placeholder="Введите пароль">
                            <p class="form__message">Пароль введён некорректно</p>
                        <?php else: ?>
                            <input class="form__input" type="password" name="password" id="password"
                                   value="<?= $value['password'] ?>" placeholder="Введите пароль">
                        <?php endif; ?>
                    </div>

                    <!-- Имя -->
                    <div class="form__row">
                        <label class="form__label" for="name">Имя <sup>*</sup></label>

                        <?php if (array_key_exists('name', $valid) && ! $valid['name']): ?>
                            <input class="form__input  form__input--error" type="text" name="name" id="name"
                                   value="<?= $value['name'] ?>" placeholder="Введите имя">
                            <p class="form__message">Имя введено некорректно</p>
                        <?php else: ?>
                            <input class="form__input" type="text" name="name" id="name"
                                   value="<?= $value['name'] ?>" placeholder="Введите имя">
                        <?php endif; ?>
                    </div>

                    <!-- Кнопка и сообщение об ошибке (опционально) -->
                    <div class="form__row form__row--controls">
                        <?php if ((array_key_exists('email', $valid) && ! $valid['email']) ||
                                  (array_key_exists('password', $valid) && ! $valid['password']) ||
                                  (array_key_exists('name', $valid) && ! $valid['name'])): ?>
                            <p class="error-massage">Пожалуйста, исправьте ошибки в форме</p>
                        <?php endif; ?>

                        <input class="button" type="submit" name="" value="Зарегистрироваться">
                    </div>
                </form>
            </main>
        </div>
    </div>
</div>

<?= renderTemplate('partials/footer', ['loadScript' => false]) ?>
