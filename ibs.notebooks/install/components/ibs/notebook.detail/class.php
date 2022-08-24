<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\UI\Extension;

Loc::loadMessages(__FILE__);
Extension::load('ui.bootstrap4');

class NotebookDetail extends CBitrixComponent
{
    public string $moduleId = 'ibs.notebooks';

    private function _checkModules(): bool
    {
        if (!Loader::includeModule($this->moduleId)) {
            throw new \Exception(Loc::getMessage('IBS_NO_MODULES'));
        }

        return true;
    }

    private function _checkRights(): void
    {
        global $APPLICATION;
        if ($APPLICATION->GetGroupRight($this->moduleId) <= "D") {
            $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
        }

    }

    private function getNotebook(): bool
    {
        $result = [];
        $options = [];
        $data = \IBS\Notebooks\NotebookTable::getList([
            'select' => [
                'ID',
                'NAME',
                'PRICE',
                'YEAR',
                'MODEL_NAME' => 'MODEL.NAME',
                'BRAND_NAME' => 'MODEL.BRAND.NAME',
                'MODEL_ID',
                'BRAND_ID' => 'MODEL.BRAND.ID',
                'OPTION_ID' => 'OPT_ID.OPTION_ID',
                'OPTION_NAME' => 'OPT_NAME.NAME',
            ],
            'filter' => ['ID' => $this->arParams['VARIABLES']['NOTEBOOK']],
            'runtime' => [
                new \Bitrix\Main\ORM\Fields\Relations\Reference(
                    'OPT_ID',
                    \IBS\Notebooks\NotebookOptionTable::getEntity()->getDataClass(),
                    \Bitrix\Main\ORM\Query\Join::on('this.ID', 'ref.NOTEBOOK_ID')
                ),
                new \Bitrix\Main\ORM\Fields\Relations\Reference(
                    'OPT_NAME',
                    \IBS\Notebooks\OptionTable::getEntity()->getDataClass(),
                    \Bitrix\Main\ORM\Query\Join::on('this.OPTION_ID', 'ref.ID')
                ),
            ]
        ]);
        while ($row = $data->fetch()) {
            $result = [
                'ID' => $row['ID'],
                'NAME' => $row['NAME'],
                'PRICE' =>$row['PRICE'],
                'YEAR' =>$row['YEAR'],
                'MODEL_NAME' =>$row['MODEL_NAME'],
                'BRAND_NAME' =>$row['BRAND_NAME'],
                'MODEL_ID' =>$row['MODEL_ID'],
                'BRAND_ID' =>$row['BRAND_ID'],
            ];
            $options[] = [
                'OPTION_ID' => $row['OPTION_ID'],
                'OPTION_NAME' => $row['OPTION_NAME'],
            ];
        }
        if(!empty($result)) {
            $result['OPTIONS'] = $options;
            $this->arResult['ITEM'] = $result;
        } else {
            ShowError(Loc::getMessage("IBS_ELEMENT_NOT_FOUND"));
        }

        return true;
    }

	public function executeComponent()
	{
        $this->_checkModules();
        $this->_checkRights();
        $this->getNotebook();
        $this->IncludeComponentTemplate();
	}
}
