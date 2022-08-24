<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
	"NAME" => Loc::getMessage("IBS_NOTEBOOK_NAME"),
	"DESCRIPTION" => Loc::getMessage("IBLOCK_CATALOG_DESCRIPTION"),
	"ICON" => "/images/catalog.gif",
	"COMPLEX" => "Y",
	"SORT" => 10,
    "PATH" => array(
        "ID" => "content",
        "CHILD" => array(
            "ID" => "ibs",
            "NAME" => Loc::getMessage("IBS_BRANCH_NAME"),
            "SORT" => 30,
        )
    )
);
