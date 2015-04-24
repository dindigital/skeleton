<?php

namespace Site\Helpers;

use DOMDocument;
use Din\Image\Picuri;

class HTMLContent
{

    private $_doc;
    private $_images;

    public function setHtml ( $html )
    {
        $this->_doc = new DOMDocument();
        if ( $html ) {
            $us_ascii = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
            @$this->_doc->loadHTML($us_ascii);
        }

    }

    public function getHtml ()
    {
        $this->findImages();

        return preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $this->_doc->saveHTML());

    }

    private function findImages ()
    {
        $this->_images = $this->_doc->getElementsByTagName('img');

        foreach ( $this->_images as $image ) {

            $src = $image->getAttribute('src');

            if ( substr($src, 0, 1) == '/' ) {
                $this->imageZoom($image);
                $this->imageResize($image);
            }
        }

    }

    private function imageZoom ( $image )
    {

        $alt = $image->getAttribute('alt');
        $title = $image->getAttribute('title');

        //

        $Zoom = $this->_doc->createElement('a');
        $Zoom->setAttribute('title', $alt);
        $Zoom->setAttribute('style', $image->getAttribute('style'));
        $Zoom->setAttribute('class', 'zoom');
        $Zoom->setAttribute('href', $image->getAttribute('src'));
        $image->parentNode->replaceChild($Zoom, $image);
        $Zoom->appendChild($image);

        if ( $title ) {
            $node_credit = $this->_doc->createElement("span");
            $node_credit->nodeValue = $title;
            $node_credit->setAttribute('class', 'credit');
            $Zoom->appendChild($node_credit);
        }

        //

        if ( $alt ) {
            $node_label = $this->_doc->createElement("span");
            $node_label->nodeValue = $alt;
            $node_label->setAttribute('style', $image->getAttribute('style'));
            $node_label->setAttribute('class', 'label');
            $Zoom->appendChild($node_label);
        }

        //

        $this->_doc->saveHTML($image);

    }

    private function imageResize ( $image )
    {

        $width = null;
        $height = null;

        preg_match('/width: (\d+)(px)?/', $image->getAttribute('style'), $matches);
        if ( count($matches) ) {
            $width = intval($matches[1]);
        }

        preg_match('/height: (\d+)(px)?/', $image->getAttribute('style'), $matches);
        if ( count($matches) ) {
            $height = intval($matches[1]);
        }

        if ( !is_null($width) && !is_null($height) ) {
            $resized = Picuri::picUri($image->getAttribute('src'), $width, $height, true, array(), 'path');
            $image->setAttribute('alt', $image->getAttribute('alt'));
            $image->setAttribute('src', $resized);
            $this->_doc->saveHTML($image);
        }

    }

}
