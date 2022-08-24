<?if(!check_bitrix_sessid()) return;?>
<form action="<?echo $APPLICATION->GetCurPage()?>" name="ibs.notebooks_install">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
    <input type="hidden" name="step" value="2">
    <input type="hidden" name="install" value="Y">
    <input type="hidden" name="id" value="ibs.notebooks" />
    <p>
        <input type="checkbox" name="reinstall_db" id="reinstall_db" value="Y">
        <label for="reinstall_db"><?=Bitrix\Main\Localization\Loc::getMessage("IBS_REINSTALL_DB")?></label>
    </p>
    <input type="submit" name="inst" value="<?=Bitrix\Main\Localization\Loc::getMessage("MOD_INSTALL")?>" />
</form>

