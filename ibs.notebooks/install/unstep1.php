<form action="<?echo $APPLICATION->GetCurPage()?>">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?echo LANGUAGE_ID?>">
    <input type="hidden" name="id" value="ibs.notebooks">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <?echo CAdminMessage::ShowMessage(GetMessage("MOD_UNINST_WARN"))?>
    <p>
        <input type="checkbox" name="delete_db" id="delete_db" value="Y">
        <label for="delete_db"><?=Bitrix\Main\Localization\Loc::getMessage("IBS_DELETE_DB")?></label>
    </p>
    <input type="submit" name="inst" value="<?=Bitrix\Main\Localization\Loc::getMessage("MOD_UNINST_DEL")?>">
</form>