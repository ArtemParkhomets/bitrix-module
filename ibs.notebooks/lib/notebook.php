<?php
namespace IBS\Notebooks;

use Bitrix\Main;
use Bitrix\Main\ORM\Data\DataManager;

class NotebookTable extends DataManager
{

    public static function getTableName()
    {
        return 'ibs_notebooks_notebook';
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
            new Main\Entity\FloatField('PRICE', array(
                'required' => true,
            )),
            new Main\Entity\IntegerField('YEAR', array(
                'required' => true,
            )),
            new Main\Entity\IntegerField('MODEL_ID'),
            new Main\Entity\ReferenceField(
                'MODEL',
                '\IBS\Notebooks\ModelTable',
                array('=this.MODEL_ID' => 'ref.ID'),
            ),
        );
    }
}
