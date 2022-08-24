<?php
namespace IBS\Notebooks;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;

Loc::loadMessages(__FILE__);

class OptionTable extends DataManager
{

    public static function getTableName()
    {
        return 'ibs_notebooks_option';
    }

    public static function getMap()
    {
        return array(
            new Main\Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true,
            )),
            new Main\Entity\StringField('NAME'),
            new Main\Entity\DatetimeField('TIMESTAMP_X', array(
                'default_value' => function()
                {
                    return new Main\Type\DateTime();
                },
            )),
        );
    }
}
