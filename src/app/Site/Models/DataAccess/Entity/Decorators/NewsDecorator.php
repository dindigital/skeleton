<?php

namespace Site\Models\DataAccess\Entity\Decorators;

use Site\Models\DataAccess\Entity\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\News;
use Din\Filters\String\Html;
use Site\Helpers\HTMLContent;

class NewsDecorator extends AbstractDecorator
{

    public function __construct ( News $entity )
    {
        parent::__construct($entity);

    }

    public function getTitle ()
    {
        return Html::scape($this->_entity->getTitle());

    }

    public function getHead ()
    {
        return Html::scape($this->_entity->getHead());

    }

    public function getBody ()
    {

        $htmlContent = new HTMLContent;
        $htmlContent->setHtml($this->_entity->getBody());

        return $htmlContent->getHtml();

    }

    public function getDate ()
    {
        $DateTime = new \DateTime($this->_entity->getDate());
        $dia = $DateTime->format("d");
        $mes = $DateTime->format("m");
        $ano = $DateTime->format("Y");
        $data = $dia . ' de ' . $this->getMonthName($mes) . ' de ' . $ano;
        return Html::scape($data);

    }

    private function getMonthName ( $number )
    {
        switch ($number) {
            case 01:
                $number = 'janeiro';
                break;
            case 02:
                $number = 'fevereiro';
                break;
            case 03:
                $number = 'março';
                break;
            case 04:
                $number = 'abril';
                break;
            case 05:
                $number = 'maio';
                break;
            case 06:
                $number = 'junho';
                break;
            case 07:
                $number = 'julho';
                break;
            case 08:
                $number = 'agosto';
                break;
            case 09:
                $number = 'setembro';
                break;
            case 10:
                $number = 'outubro';
                break;
            case 11:
                $number = 'novembro';
                break;
            case 12:
            default:
                $number = 'dezembro';
                break;
        }
        return $number;

    }

    public function getCover ()
    {
        $pic = new \Din\Image\Picuri(parent::getCover());
        $pic->setWidth(1073);
        $pic->setHeight(457);
        //$pic->setCrop(true);
        $pic->setTypeReturn('path');
        $pic->save();

        return $pic->getImage();

    }

    public function getKeywords ()
    {
        return Html::scape($this->_entity->getKeywords());

    }

    public function getDescription ()
    {
        return Html::scape($this->_entity->getDescription());

    }

}
