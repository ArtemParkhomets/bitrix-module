<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\UI\Extension;

Loc::loadMessages(__FILE__);
Extension::load("ui.forms");
Extension::load("ui.buttons");

class NotebookList extends CBitrixComponent
{
    public string $gridId = 'notebookGrid';
    public string $moduleId = 'ibs.notebooks';

    private function getColumnMap(): array
    {
        return [
            'brands' => [
                'ID' => 'ID',
                'NAME' => Loc::getMessage('IBS_GRID_NAME'),
            ],
            'models' => [
                'ID' => 'ID',
                'NAME' => Loc::getMessage('IBS_GRID_NAME'),
                'BRAND_NAME' => Loc::getMessage('IBS_GRID_BRAND_NAME'),
            ],
            'notebooks' => [
                'ID' => 'ID',
                'NAME' => Loc::getMessage('IBS_GRID_NAME'),
                'PRICE' => Loc::getMessage('IBS_GRID_PRICE'),
                'YEAR' => Loc::getMessage('IBS_GRID_YEAR'),
                'MODEL_NAME' => Loc::getMessage('IBS_GRID_MODEL_NAME'),
                'BRAND_NAME' => Loc::getMessage('IBS_GRID_BRAND_NAME'),
            ]
        ];
    }

    private function _checkModules(): void
    {
        if (!Loader::includeModule($this->moduleId)) {
            ShowError(Loc::getMessage('IBS_NO_MODULES'));
        }

    }

    private function _checkRights(): void
    {
        global $APPLICATION;
        if ($APPLICATION->GetGroupRight($this->moduleId) <= "D") {
            $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
        }

    }

    private function getGridOptions(): array
    {
        $grid_options = new Bitrix\Main\Grid\Options($this->gridId);
        $sort = $grid_options->GetSorting(['sort' => ['ID' => 'DESC'], 'vars' => ['by' => 'by', 'order' => 'order']]);
        $nav_params = $grid_options->GetNavParams();
        $nav = new Bitrix\Main\UI\PageNavigation($this->gridId);
        $nav->allowAllRecords(false)
            ->setPageSize($nav_params['nPageSize'])
            ->initFromUri();

        return ['sort' => $sort['sort'], 'nav_object' => $nav];
    }

    public function prepareGrid()
    {
        $this->arResult['GRID']['GRID_ID'] = $this->gridId;
        $this->arResult['GRID']['COLUMNS'] = $this->getGridColumns();
        $this->arResult['GRID']['ROWS'] = $this->getGridRows();
    }

    private function getGridColumns(): array
    {
        $columns = [];

        foreach ($this->getColumnMap()[$this->arParams['PAGE_ID']] as $colId => $colValue) {
            $columns[] = [
                'id' => $colId,
                'name' => $colValue,
                'sort' => ($colId == 'PRICE' || $colId == 'YEAR') ? $colId : false,
                'default' => true
            ];
        }

        return $columns;
    }

    private function getGridRows(): array
    {
        $rows = [];
        $data = $this->getList();
        foreach ($data as $item) {
            if(array_key_exists('NAME', $item)) {
                if($this->arParams['PAGE_ID'] == 'brands') {
                    $item['NAME'] = '<a href=' . $this->arParams["FOLDER"] . $item["ID"] . "/" . '>'. $item["NAME"] . '</a>';
                } elseif ($this->arParams['PAGE_ID'] == 'models') {
                    $item['NAME'] = '<a href=' . $this->arParams["FOLDER"] . $item["BRAND_ID"] . "/" .$item["ID"] . "/" . '>'. $item["NAME"] . '</a>';
                } else {
                    $item['NAME'] = '<a href=' . $this->arParams["FOLDER"] . "detail/" .$item["ID"] . "/" . '>' . $item["NAME"] . '</a>';
                    $item['BRAND_NAME'] = '<a href=' . $this->arParams["FOLDER"] . $item["BRAND_ID"] . "/" . '>' . $item["BRAND_NAME"] . '</a>';
                }
            }
            $row['data'] = $item;
            $rows[] = $row;
        }

        return $rows;
    }

    private function getList(): array
    {
        if($this->arParams['VARIABLES']['BRAND'] && $this->arParams['VARIABLES']['MODEL']) {
            $data = $this->getNotebooks($this->getGridOptions(), $this->arParams['VARIABLES']['BRAND'], $this->arParams['VARIABLES']['MODEL']);
        } elseif ($this->arParams['VARIABLES']['BRAND']) {
            $data = $this->getModels($this->getGridOptions(), $this->arParams['VARIABLES']['BRAND']);
        } else {
            $data = $this->getBrands($this->getGridOptions());
        }

        return $data;
    }

    private function getNotebooks($options, $brandId, $modelId): array
    {
        $data = [];
        if ($brandId && $modelId) {
            $query = \IBS\Notebooks\NotebookTable::query()
                ->setSelect([
                    'ID',
                    'NAME',
                    'PRICE',
                    'YEAR',
                    'MODEL_NAME' => 'MODEL.NAME',
                    'BRAND_NAME' => 'MODEL.BRAND.NAME',
                    'MODEL_ID',
                    'BRAND_ID' => 'MODEL.BRAND.ID'
                ])
                ->setFilter(['MODEL_ID' => $modelId])
                ->whereIn('MODEL_ID', \IBS\Notebooks\ModelTable::query()
                ->setSelect(['ID'])->setFilter(['BRAND_ID' => $brandId])
            );
            $data = $query->setOrder($options['sort'])
                ->setOffset(
                intVal($options['nav_object']->getCurrentPage()) *
                intVal($options['nav_object']->getPageSize()) -
                intVal($options['nav_object']->getPageSize())
            )->setLimit(intVal($options['nav_object']->getPageSize()))
            ->fetchAll();
            $options['nav_object']->setRecordCount($query->queryCountTotal());
            $this->arResult['GRID']['NAV'] = $options['nav_object'];
        }

        return $data;
    }

    private function getBrands($options): array
    {
        $dbQuery = \IBS\Notebooks\BrandTable::getList([
            'select' => ['ID', 'NAME'],
            'offset' => intVal($options['nav_object']->getCurrentPage()) *
                intVal($options['nav_object']->getPageSize()) -
                intVal($options['nav_object']->getPageSize()),
            'limit' => intVal($options['nav_object']->getPageSize()),
            'count_total' => true
        ]);
        $data = $dbQuery->fetchAll();
        $options['nav_object']->setRecordCount($dbQuery->getCount());
        $this->arResult['GRID']['NAV'] = $options['nav_object'];

        return $data;
    }

    private function getModels($options, $brandId): array
    {
        $filter = [];
        if($brandId) {
            $filter['BRAND_ID'] = $brandId;
        }
        $dbQuery = \IBS\Notebooks\ModelTable::getList([
            'select' => ['ID', 'NAME', 'BRAND_NAME' => 'BRAND.NAME', 'BRAND_ID'],
            'filter' => $filter,
            'offset' => intVal($options['nav_object']->getCurrentPage()) *
                intVal($options['nav_object']->getPageSize()) -
                intVal($options['nav_object']->getPageSize()),
            'limit' => intVal($options['nav_object']->getPageSize()),
            'count_total' => true
        ]);
        $data = $dbQuery->fetchAll();
        $options['nav_object']->setRecordCount($dbQuery->getCount());
        $this->arResult['GRID']['NAV'] = $options['nav_object'];

        return $data;
    }

	public function executeComponent()
	{
        $this->_checkModules();
        $this->_checkRights();
        $this->prepareGrid();
        $this->IncludeComponentTemplate();
	}
}
