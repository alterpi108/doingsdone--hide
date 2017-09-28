<?= renderTemplate('partials/header', ['title' => 'Ошибка', 'overlay' => false]) ?>

<h1>Ошибка</h1>
<p><?= htmlspecialchars($message) ?></p>

</body>
</head>
