<?php
namespace IBS\Notebooks;

use Bitrix\Main;
use Bitrix\Main\ORM\Data\DataManager;

class ModelTable extends DataManager
{

    public static function getTableName()
    {
        return 'ibs_notebooks_model';
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
            new Main\Entity\IntegerField('BRAND_ID'),
            new Main\Entity\ReferenceField(
                'BRAND',
                '\IBS\Notebooks\BrandTable',
                array('=this.BRAND_ID' => 'ref.ID'),
            ),
        );
    }
}
