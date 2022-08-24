<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
?>
<div class="card">
    <div class="card-header">
        <?=$arResult['ITEM']['NAME']?>
    </div>
    <div class="card-body">
        <blockquote class="blockquote mb-0">
            <p class="card-text"><?=Loc::getMessage('BRAND_NAME') . ' - ' . $arResult['ITEM']['BRAND_NAME']?></p>
            <p class="card-text"><?=Loc::getMessage('MODEL_NAME') . ' - ' . $arResult['ITEM']['MODEL_NAME']?></p>
            <p class="card-text"><?=Loc::getMessage('PRICE') . ' - ' . $arResult['ITEM']['PRICE']?></p>
            <p class="card-text"><?=Loc::getMessage('YEAR') . ' - ' . $arResult['ITEM']['YEAR']?></p>
            <p><?=Loc::getMessage('OPTIONS') . ':'?></p>
            <?foreach ($arResult['ITEM']['OPTIONS'] as $option) {?>
                <footer class="blockquote-footer"><?=$option['OPTION_NAME']?></footer><br>
            <?}?>
        </blockquote>
    </div>
</div>
