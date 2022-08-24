<?php
namespace IBS\Notebooks;

use Bitrix\Main;
use Bitrix\Main\ORM\Data\DataManager;

class ModelOptionTable extends DataManager
{

    public static function getTableName()
    {
        return 'ibs_notebooks_model_options';
    }

    public static function getMap()
    {
        return array(
            new Main\Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true,
            )),
            new Main\Entity\IntegerField('MODEL_ID'),
            new Main\Entity\ReferenceField(
                'MODEL',
                '\IBS\Notebooks\ModelTable',
                array('=this.MODEL_ID' => 'ref.ID'),
            ),
            new Main\Entity\IntegerField('OPTION_ID'),
            new Main\Entity\ReferenceField(
                'OPTION',
                '\IBS\Notebooks\OptionTable',
                array('=this.OPTION_ID' => 'ref.ID'),
            ),
        );
    }
}
