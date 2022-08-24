<?php

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Application,
    Bitrix\Main\Entity\Base,
    Bitrix\Main\Loader;

Class ibs_notebooks extends CModule
{

    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__."/version.php");

        $this->MODULE_ID = "ibs.notebooks";
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage('IBS_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('IBS_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'Y';

        $this->moduleClasses = [
            'IBS\Notebooks\BrandTable',
            'IBS\Notebooks\ModelTable',
            'IBS\Notebooks\NotebookTable',
            'IBS\Notebooks\OptionTable',
            'IBS\Notebooks\ModelOptionTable',
            'IBS\Notebooks\NotebookOptionTable',
        ];

        $this->documentRoot = Application::getDocumentRoot();

    }

    function InstallFiles()
    {
        CopyDirFiles($this->documentRoot."/local/modules/$this->MODULE_ID/install/components",
            $this->documentRoot."/local/components", true, true);
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;

        $request = Application::getInstance()->getContext()->getRequest();
        if ($request['step'] < 2) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("IBS_MODULE_INSTALL_TITLE"), $this->documentRoot."/local/modules/$this->MODULE_ID/install/step1.php");

        } elseif ($request['step'] == 2) {
            $this->InstallFiles();

            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);

            if($request['reinstall_db'] == 'Y') {
                $this->InstallDB();
            }

            $APPLICATION->IncludeAdminFile(Loc::getMessage("IBS_MODULE_INSTALL_TITLE"), $this->documentRoot."/local/modules/$this->MODULE_ID/install/step2.php");
        }



    }

    function DoUninstall()
    {
        global $APPLICATION;

        $request = Application::getInstance()->getContext()->getRequest();

        if($request['step'] < 2) {
            $APPLICATION->IncludeAdminFile(GetMessage("IBS_MODULE_UNINSTALL_TITLE"),
                $this->documentRoot."/local/modules/$this->MODULE_ID/install/unstep1.php");

        } elseif($request['step'] == 2) {
            if($request['delete_db'] == 'Y') {
                $this->UnInstallDB();
            }
            \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

            $APPLICATION->IncludeAdminFile(GetMessage("IBS_MODULE_UNINSTALL_TITLE"),
                $this->documentRoot."/local/modules/$this->MODULE_ID/install/unstep2.php");
        }

    }

    function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        $this->UnInstallDB();

        $connection = Application::getConnection();

        foreach ($this->moduleClasses as $class) {
            $instance = Base::getInstance($class);
            if (!$connection->isTableExists($instance->getDBTableName())) {
                $instance->createDbTable();
            }
        }

        $brandIds = [];
        $modelIds = [];
        $notebookIds = [];
        $optionIds = [];

        $brands = ['Apple', 'ASUS', 'Lenovo'];
        foreach ($brands as $brand) {
            $result = IBS\Notebooks\BrandTable::add(['NAME' => $brand]);
            if($result->isSuccess()) {
                $brandIds[] = $result->getId();
            }
        }

        $options = ['видеокарта', 'экран', 'клавиатура', 'процессор', 'камера', 'сверхтонкий'];
        foreach ($options as $option) {
            $result = IBS\Notebooks\OptionTable::add(['NAME' => $option]);
            if($result->isSuccess()) {
                $optionIds[] = $result->getId();
            }
        }

        $models = ['x1', 'x2', 'x3', 'x4', 'x5', 'x6', 'x7', 'x8', 'x9'];
        foreach ($models as $model) {
            $tmpOptionIds = $optionIds;
            $result = IBS\Notebooks\ModelTable::add(['NAME' => $model, 'BRAND_ID' => $brandIds[array_rand($brandIds)]]);
            if($result->isSuccess()) {
                $modelIds[] = $result->getId();
                $iterations = rand(1, count($tmpOptionIds));
                for ($i = 0; $i < $iterations; $i++) {
                    $optionId = $tmpOptionIds[array_rand($tmpOptionIds)];
                    IBS\Notebooks\ModelOptionTable::add([
                        'MODEL_ID' => $result->getId(),
                        'OPTION_ID' => $optionId,
                    ]);
                    unset($tmpOptionIds[array_search($optionId, $tmpOptionIds)]);
                }
            }
        }

        for($i = 1; $i <= 20; $i++) {
            $notebooks[] = 'Ноутбук ' . $i;
        }
        foreach ($notebooks as $notebook) {
            $tmpOptionIds = $optionIds;
            $result = IBS\Notebooks\NotebookTable::add([
                'NAME' => $notebook,
                'YEAR' => rand(1980, 2022),
                'PRICE' => rand(100000, 1000000) / 100,
                'MODEL_ID' => $modelIds[array_rand($modelIds)],
            ]);
            if($result->isSuccess()) {
                $notebookIds[] = $result->getId();
                $iterations = rand(1, count($tmpOptionIds));
                for ($i = 0; $i < $iterations; $i++) {
                    $optionId = $tmpOptionIds[array_rand($tmpOptionIds)];
                    IBS\Notebooks\NotebookOptionTable::add([
                        'NOTEBOOK_ID' => $result->getId(),
                        'OPTION_ID' => $optionId,
                    ]);
                    unset($tmpOptionIds[array_search($optionId, $tmpOptionIds)]);
                }
            }
        }
    }

    function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        $connection = Application::getConnection();
        foreach ($this->moduleClasses as $class) {
            $tableName = Base::getInstance($class)->getDBTableName();
            $connection->queryExecute('drop table if exists ' . $tableName);
        }

    }

    function GetModuleRightList()
    {
        return [
            'reference_id' => ['D', 'K'],
            'reference' => [
                "[D] " . Loc::getMessage('IBS_ACCESS_DENIED'),
                "[K] " . Loc::getMessage('IBS_READ_COMPONENT'),
            ]
        ];
    }
}
