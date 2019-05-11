<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 24.01.2019
 * Time: 15:32
 */

namespace ActiveTableEngine\Concrete;

use ActiveTableEngine\Contracts\TableActionInterface;

class TableAction extends Navigation implements TableActionInterface
{


    /**
     * просмотр записи
     * @return bool
     */
    public function isViewRecord(): bool
    {

        if (!isset($_GET["fn"]) || !isset($_GET["id"])) {
            return false;
        }

        return $_GET["fn"] === "edit" && (int)$_GET["id"] > 0;
    }

    /**
     * Просмотр пустой формы
     * @return bool
     */
    public function isViewForm(): bool
    {
        if (!isset($_GET["fn"]) || !isset($_GET["id"])) {
            return false;
        }
        return $_GET["fn"] === "add" && (int)$_GET["id"] == 0;
    }

    /**
     * удаление записи
     * @return bool
     */
    public function isDeleteRecord(): bool
    {
        if (!isset($_GET["fn"]) || !isset($_GET["id"])) {
            return false;
        }
        return $_GET["fn"] === "del" && (int)$_GET["id"] > 0;
    }

    /**
     * Обновление записи
     * @return bool
     */
    public function isUpdateRecord(): bool
    {
        return $this->isViewRecord() && $this->isSubmitForm();
    }

    /**
     * Создание записи
     * @return bool
     */
    public function isCreateRecord(): bool
    {
        return $this->isViewForm() && $this->isSubmitForm();
    }

    /**
     * Подтвержденгие формы
     * @return bool
     */
    protected function isSubmitForm(): bool
    {
        if (!isset($_GET["submit"])) {
            return false;
        }
        return isset($_POST["submit"]);
    }

    /**
     * присутсвие ключа
     * @return int
     */
    public function getKey(): int
    {
        if (!isset($_GET["id"])) {
            return false;
        }
        return (int)$_GET["id"];
    }

    /**
     * получение базового УРЛ
     * @param $url
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        if(!isset($_SERVER["QUERY_STRING"])){
            return "";
        }
        return rtrim(str_replace($_SERVER["QUERY_STRING"], "", $_SERVER["REQUEST_URI"]), "?");
    }

}