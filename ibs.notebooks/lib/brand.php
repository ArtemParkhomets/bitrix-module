<?php
namespace IBS\Notebooks;

use Bitrix\Main;
use Bitrix\Main\ORM\Data\DataManager;

class BrandTable extends DataManager
{

    public static function getTableName()
    {
        return 'ibs_notebooks_brand';
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
