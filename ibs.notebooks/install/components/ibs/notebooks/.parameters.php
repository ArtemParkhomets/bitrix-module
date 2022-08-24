<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Localization\Loc;

$arComponentParameters = array(
    "PARAMETERS" => array(
        "SEF_MODE" => array(
            "brands" => array(
                "NAME" => Loc::getMessage("IBS_BRAND_LIST"),
                "DEFAULT" => "",
                "VARIABLES" => array(
                ),
            ),
            "models" => array(
                "NAME" => Loc::getMessage("IBS_MODEL_LIST"),
                "DEFAULT" => "#BRAND#/",
                "VARIABLES" => array(
                    "BRAND",
                ),
            ),
            "notebooks" => array(
                "NAME" => Loc::getMessage("IBS_NOTEBOOK_LIST"),
                "DEFAULT" => "#BRAND#/#MODEL#/",
                "VARIABLES" => array(
                    "BRAND",
                    "MODEL"
                ),
            ),
            "detail" => array(
                "NAME" => Loc::getMessage("IBS_DETAIL_PAGE"),
                "DEFAULT" => "detail/#NOTEBOOK#/",
                "VARIABLES" => array(
                    "NOTEBOOK"
                ),
            ),
        ),

    ),
);
