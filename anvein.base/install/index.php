<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;
use CAgent;

class anvein_base extends CModule
{
    private $PARTNER_DIR;

    /**
     * Путь к папке сайта (web).
     *
     * @var string
     */
    protected $documentRoot;

    /**
     * Точка входа.
     */
    public function __construct()
    {
        Loc::loadMessages(__FILE__);

        $arModuleVersion = [];
        include_once __DIR__ . '/version.php';

        $this->PARTNER_DIR = 'anvein';
        $this->MODULE_ID = 'anvein.base';
        $this->MODULE_NAME = Loc::getMessage('anvein_base_name');
        $this->MODULE_DESCRIPTION = Loc::getMessage('anvein_base_description');

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->PARTNER_NAME = Loc::getMessage('anvein_base_partner_name');
        $this->PARTNER_URI = '/';

        $this->documentRoot = realpath(__DIR__ . '/../../../..');
    }

    /**
     * Точка входа при установке.
     *
     * @return bool - true, если установился
     */
    public function DoInstall()
    {
        if (!ModuleManager::isModuleInstalled($this->MODULE_ID)) {
            ModuleManager::registerModule($this->MODULE_ID);
            $this->InstallFiles();

            return true;
        }
    }

    /**
     * Точка входа при удалении.
     *
     * @return bool - true, если удалился
     */
    public function DoUninstall()
    {
        if (ModuleManager::isModuleInstalled($this->MODULE_ID)) {
            $this->UnInstallFiles();
            ModuleManager::unregisterModule($this->MODULE_ID);

            return true;
        }
    }

    /**
     * Установка файлов и компонентов.
     *
     * @return bool - true, если всё успешно установилось
     */
    public function InstallFiles()
    {
        // установка компонентов
        $path = __DIR__ . "/components/{$this->PARTNER_DIR}";
        if (is_dir($path)) {
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if ($item === '..' || $item === '.') {
                        continue;
                    }

                    CopyDirFiles("{$path}/{$item}",
                        "{$this->documentRoot}/local/components/{$this->PARTNER_DIR}/{$item}", $rewrite = true,
                        $recursive = true);
                }
                closedir($dir);
            }
        }

        // установка файлов админки
        $path = __DIR__ . '/admin';
        if (is_dir($path)) {
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if ($item === '..' || $item === '.') {
                        continue;
                    }

                    copy("{$path}/{$item}", "{$_SERVER['DOCUMENT_ROOT']}/bitrix/admin/{$item}");
                }
                closedir($dir);
            }
        }

        return true;
    }

    /**
     * Удаление файлов и компонентов.
     *
     * @return bool - true, если всё успешно удалилось
     */
    public function UnInstallFiles()
    {
        // удаление компонентов
        $path = __DIR__ . "/components/{$this->PARTNER_DIR}";
        if (is_dir($path)) {
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if ($item === '..' || $item === '.') {
                        continue;
                    }

                    $compInBitrix = "/bitrix/components/{$this->PARTNER_DIR}/{$item}";
                    if (file_exists($this->documentRoot . '/' . $compInBitrix)) {
                        DeleteDirFilesEx($compInBitrix);
                    }

                    $compInLocal = "/local/components/{$this->PARTNER_DIR}/{$item}";
                    if (file_exists($this->documentRoot . '/' . $compInLocal)) {
                        DeleteDirFilesEx($compInLocal);
                    }
                }
                closedir($dir);
            }
        }

        // удаление файлов админки
        $path = __DIR__ . '/admin';
        if (is_dir($path)) {
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if ($item === '..' || $item === '.') {
                        continue;
                    }

                    $pathFile = "{$this->documentRoot}/bitrix/admin/{$item}";
                    if (file_exists($pathFile)) {
                        unlink($pathFile);
                    }
                }
                closedir($dir);
            }
        }

        return true;
    }

    /**
     * Операции над БД при установке модуля.
     *
     * @return bool - true, если таблицы успешно созданы
     */
    public function InstallDB()
    {
        //$connection = Application::getConnection();

        return true;
    }

    /**
     * Операции над БД при удалении модуля.
     *
     * @return bool - true, если таблицы успешно удалены
     */
    public function UnInstallDB()
    {
        return true;
    }

    /**
     * Создание событий при установке.
     *
     * @return bool - true, если события успешно созданы
     */
    public function InstallEvents()
    {
        return true;
    }

    /**
     * Удаление событий модуля.
     *
     * @return bool - true, если события модуля успешно удалены
     */
    public function UnInstallEvents()
    {
        CAgent::RemoveModuleAgents($this->MODULE_ID);

        return true;
    }
}
