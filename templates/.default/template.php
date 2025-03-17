<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php if (!empty($arResult['ERROR'])): ?>
    <div style="color: red;">
        <p><?= $arResult['ERROR'] ?></p>
    </div>
<?php endif; ?>

<?php if (isset($_GET['success']) && $_GET['success'] === 'Y'): ?>
    <div style="color: green;">
        <p>Данные успешно обновлены!</p>
    </div>
<?php endif; ?>

<?php if (!empty($arResult['USER_DATA'])): ?>
    <div>
        <p><strong>Имя:</strong> <?= $arResult['USER_DATA']['NAME'] ?></p>
        <p><strong>Фамилия:</strong> <?= $arResult['USER_DATA']['LAST_NAME'] ?></p>
        <p><strong>Дата рождения:</strong> <?= $arResult['USER_DATA']['PERSONAL_BIRTHDAY'] ?></p>
        <p><strong>Телефон:</strong> <?= $arResult['USER_DATA']['PERSONAL_PHONE'] ?></p>
    </div>

    <hr>

    <h3>Редактировать данные</h3>
    <form method="post" action="">
        <?= bitrix_sessid_post() ?>
        <div>
            <label for="NAME">Имя:</label>
            <input type="text" id="NAME" name="NAME" value="<?= $arResult['USER_DATA']['NAME'] ?>" required>
        </div>
        <div>
            <label for="LAST_NAME">Фамилия:</label>
            <input type="text" id="LAST_NAME" name="LAST_NAME" value="<?= $arResult['USER_DATA']['LAST_NAME'] ?>" required>
        </div>
        <div>
            <label for="PERSONAL_BIRTHDAY">Дата рождения:</label>
            <input type="date" id="PERSONAL_BIRTHDAY" name="PERSONAL_BIRTHDAY" value="<?= $arResult['USER_DATA']['PERSONAL_BIRTHDAY'] ?>" required>
        </div>
        <div>
            <label for="PERSONAL_PHONE">Телефон:</label>
            <input type="tel" id="PERSONAL_PHONE" name="PERSONAL_PHONE" value="<?= $arResult['USER_DATA']['PERSONAL_PHONE'] ?>" required>
            <small>Формат: 11 цифр (например, 89177486650)</small>
        </div>
        <div>
            <button type="submit">Сохранить</button>
        </div>
    </form>
<?php endif; ?>