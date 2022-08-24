<?php
namespace IBS\Notebooks;

use Bitrix\Main;
use Bitrix\Main\ORM\Data\DataManager;

class NotebookOptionTable extends DataManager
{

    public static function getTableName()
    {
        return 'ibs_notebooks_notebook_options';
    }

    public static function getMap()
    {
        return array(
            new Main\Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true,
            )),
            new Main\Entity\IntegerField('NOTEBOOK_ID'),
            new Main\Entity\ReferenceField(
                'NOTEBOOK',
                '\IBS\Notebooks\NotebookTable',
                array('=this.NOTEBOOK_ID' => 'ref.ID'),
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
