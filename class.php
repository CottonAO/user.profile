<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;

class UserProfileComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        global $USER, $APPLICATION;

        // Подключаем модуль main
        if (!Loader::includeModule('main')) {
            ShowError('Модуль main не подключен');
            return;
        }

        // Получаем ID текущего пользователя
        $userId = $USER->GetID();
        $arResult = [];

        if ($userId > 0) {
            // Получаем данные пользователя
            $user = UserTable::getById($userId)->fetch();

            if ($user) {
                $arResult['USER_DATA'] = [
                    'NAME' => $user['NAME'],
                    'LAST_NAME' => $user['LAST_NAME'],
                    'PERSONAL_BIRTHDAY' => $user['PERSONAL_BIRTHDAY'] ? $user['PERSONAL_BIRTHDAY']->format('Y-m-d') : '',
                    'PERSONAL_PHONE' => $user['PERSONAL_PHONE'],
                ];
            } else {
                $arResult['ERROR'] = 'Пользователь не найден в БД.';
            }
        } else {
            $arResult['ERROR'] = 'Пользователь не авторизован.';
        }

        // Обработка формы
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_bitrix_sessid()) {
        $name = trim($_POST['NAME'] ?? '');
        $lastName = trim($_POST['LAST_NAME'] ?? '');
        $birthday = trim($_POST['PERSONAL_BIRTHDAY'] ?? '');
        $phone = trim($_POST['PERSONAL_PHONE'] ?? '');

        // Валидация
        $errors = [];
        if (empty($name)) {
            $errors[] = "Имя обязательно для заполнения.";
        }
        if (empty($lastName)) {
            $errors[] = "Фамилия обязательна для заполнения.";
        }
        if (empty($birthday) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday)) {
            $errors[] = "Некорректная дата рождения.";
        }
        if (empty($phone) || !preg_match('/^\d{11}$/', $phone)) {
            $errors[] = "Телефон должен состоять из 11 цифр.";
        }

        if (empty($errors)) {
            // Используем CUser для обновления данных
            $user = new CUser;

            // Преобразуем дату из формата YYYY-MM-DD в DD.MM.YYYY
            $birthdayFormatted = DateTime::createFromFormat('Y-m-d', $birthday)->format('d.m.Y');

            $userFields = [
                'NAME' => $name,
                'LAST_NAME' => $lastName,
                'PERSONAL_BIRTHDAY' => $birthdayFormatted,
                'PERSONAL_PHONE' => $phone,
            ];

            if ($user->Update($userId, $userFields)) {
                LocalRedirect($APPLICATION->GetCurPageParam("success=Y", ["success"]));
            } else {
                $arResult['ERROR'] = "Ошибка при обновлении данных: " . $user->LAST_ERROR;
            }
        } else {
            $arResult['ERROR'] = implode("<br>", $errors);
        }
    }

        // Передаем данные в шаблон
        $this->arResult = $arResult;

        // Подключаем шаблон
        $this->IncludeComponentTemplate();
    }
}