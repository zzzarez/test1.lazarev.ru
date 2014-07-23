<?php
/**
 * @version $Id: HeatmapFromFile.class.php 35 2011-09-12 14:38:04Z edo888@gmail.com $
 * @copyright (C) 2007 - 2011 Yvan Taviaud
 * @license GNU/GPL http://www.labsmedia.com/clickheat/index.html
 *
 * ClickHeat : Classe de génération des cartes depuis un fichier de coordonnées X,Y / Maps generation class from a X,Y coords file
 *
 * Cette classe est VOLONTAIREMENT écrite pour PHP 4
 * This class is VOLUNTARILY written for PHP 4
 *
 * Utilisation : jettez un oeil au répertoire /examples/
 * Usage: have a look into /examples/ directory
 *
 * @author Yvan Taviaud - LabsMedia - www.labsmedia.com
 * @since 19/05/2007
**/

defined('_JEXEC') or die('Restricted access');

class HeatmapFromFile extends Heatmap
{
    /** @var array $files Fichiers de suivi à étudier / Logfiles to use */
    var $files = array();
    /** @var string $regular Expression régulière pour lire les enregistrements du fichier / Regular expression to read file entries */
    var $regular = '/^(\d+),(\d+)$/m';

    var $clicks;

    /**
     * Add a file to parse and check existence
     *
     * @param string $file
     */
    function addFile($file)
    {
        if (file_exists($file))
        {
            $this->files[] = $file;
        }
    }

    /**
     * Do some tasks before drawing (database connection...)
    **/
    function startDrawing()
    {
        return true;
    }

    /**
     * Find pixels coords and draw these on the current image
     *
     * @author Simon Poghosyan
     * @param integer $image Number of the image (to be used with $this->height)
     * @return boolean Success
    **/
    function drawPixels($image)
    {
        foreach ($this->clicks as $click)
        {
            $x = (int) $click[0];
            $y = (int) ($click[1]  - $image * $this->height);
            if ($x < 0 || $x >= $this->width)
            {
                continue;
            }
            /** Apply a calculus for the step, with increases the speed of rendering : step = 3, then pixel is drawn at x = 2 (center of a 3x3 square) */
            $x -= $x % $this->step - $this->startStep;
            $y -= $y % $this->step - $this->startStep;
            /** Add 1 to the current color of this pixel (color which represents the sum of clicks on this pixel) */
            $color = imagecolorat($this->image, $x, $y) + 1;
            imagesetpixel($this->image, $x, $y, $color);
            $this->maxClicks = max($this->maxClicks, $color);
            if ($image === 0)
            {
                /** Looking for the maximum height of click */
                $this->maxY = max($y, $this->maxY);
            }
        }
    }

    /**
     * Do some cleaning or ending tasks (close database, reset array...)
    **/
    function finishDrawing()
    {
        $this->files = array();
        return true;
    }
}
?>