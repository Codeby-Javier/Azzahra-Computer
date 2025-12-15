<?php
/**
 * PHPExcel Compatibility Wrapper for PhpSpreadsheet
 * 
 * This file provides backward compatibility for code using PHPExcel
 * while utilizing PhpSpreadsheet (the modern, PHP 8+ compatible successor)
 * 
 * @package PHPExcel_Compat
 */

// Load Composer autoloader
require_once dirname(__FILE__) . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

/**
 * PHPExcel Class - Wrapper for PhpSpreadsheet\Spreadsheet
 */
class PHPExcel extends Spreadsheet
{
    // Inherits all methods from Spreadsheet
}

/**
 * PHPExcel_IOFactory Class - Wrapper for PhpSpreadsheet\IOFactory
 */
class PHPExcel_IOFactory
{

    /**
     * Create a new writer
     *
     * @param PHPExcel|Spreadsheet $spreadsheet
     * @param string $writerType  Excel2007 or Excel5
     * @return \PhpOffice\PhpSpreadsheet\Writer\IWriter
     */
    public static function createWriter($spreadsheet, $writerType = 'Excel2007')
    {
        $writerTypeMap = [
            'Excel2007' => 'Xlsx',
            'Excel5' => 'Xls',
            'Xlsx' => 'Xlsx',
            'Xls' => 'Xls',
            'Csv' => 'Csv',
            'Html' => 'Html',
            'PDF' => 'Mpdf',
        ];

        $mappedType = $writerTypeMap[$writerType] ?? 'Xlsx';
        return IOFactory::createWriter($spreadsheet, $mappedType);
    }

    /**
     * Load a spreadsheet file
     *
     * @param string $filename
     * @return PHPExcel
     */
    public static function load($filename)
    {
        $spreadsheet = IOFactory::load($filename);
        return $spreadsheet;
    }

    /**
     * Create a reader for a specific file type
     *
     * @param string $readerType
     * @return \PhpOffice\PhpSpreadsheet\Reader\IReader
     */
    public static function createReader($readerType)
    {
        $readerTypeMap = [
            'Excel2007' => 'Xlsx',
            'Excel5' => 'Xls',
            'Xlsx' => 'Xlsx',
            'Xls' => 'Xls',
            'Csv' => 'Csv',
        ];

        $mappedType = $readerTypeMap[$readerType] ?? 'Xlsx';
        return IOFactory::createReader($mappedType);
    }

    /**
     * Identify file type
     *
     * @param string $filename
     * @return string
     */
    public static function identify($filename)
    {
        return IOFactory::identify($filename);
    }
}

/**
 * PHPExcel_Style_Alignment Class - Constants for alignment
 */
class PHPExcel_Style_Alignment
{
    const HORIZONTAL_GENERAL = Alignment::HORIZONTAL_GENERAL;
    const HORIZONTAL_LEFT = Alignment::HORIZONTAL_LEFT;
    const HORIZONTAL_RIGHT = Alignment::HORIZONTAL_RIGHT;
    const HORIZONTAL_CENTER = Alignment::HORIZONTAL_CENTER;
    const HORIZONTAL_CENTER_CONTINUOUS = Alignment::HORIZONTAL_CENTER_CONTINUOUS;
    const HORIZONTAL_JUSTIFY = Alignment::HORIZONTAL_JUSTIFY;
    const HORIZONTAL_FILL = Alignment::HORIZONTAL_FILL;
    const HORIZONTAL_DISTRIBUTED = Alignment::HORIZONTAL_DISTRIBUTED;

    const VERTICAL_TOP = Alignment::VERTICAL_TOP;
    const VERTICAL_BOTTOM = Alignment::VERTICAL_BOTTOM;
    const VERTICAL_CENTER = Alignment::VERTICAL_CENTER;
    const VERTICAL_JUSTIFY = Alignment::VERTICAL_JUSTIFY;
    const VERTICAL_DISTRIBUTED = Alignment::VERTICAL_DISTRIBUTED;
}

/**
 * PHPExcel_Style_Border Class - Constants for borders
 */
class PHPExcel_Style_Border
{
    const BORDER_NONE = Border::BORDER_NONE;
    const BORDER_DASHDOT = Border::BORDER_DASHDOT;
    const BORDER_DASHDOTDOT = Border::BORDER_DASHDOTDOT;
    const BORDER_DASHED = Border::BORDER_DASHED;
    const BORDER_DOTTED = Border::BORDER_DOTTED;
    const BORDER_DOUBLE = Border::BORDER_DOUBLE;
    const BORDER_HAIR = Border::BORDER_HAIR;
    const BORDER_MEDIUM = Border::BORDER_MEDIUM;
    const BORDER_MEDIUMDASHDOT = Border::BORDER_MEDIUMDASHDOT;
    const BORDER_MEDIUMDASHDOTDOT = Border::BORDER_MEDIUMDASHDOTDOT;
    const BORDER_MEDIUMDASHED = Border::BORDER_MEDIUMDASHED;
    const BORDER_SLANTDASHDOT = Border::BORDER_SLANTDASHDOT;
    const BORDER_THICK = Border::BORDER_THICK;
    const BORDER_THIN = Border::BORDER_THIN;
}

/**
 * PHPExcel_Style_Fill Class - Constants for fills
 */
class PHPExcel_Style_Fill
{
    const FILL_NONE = Fill::FILL_NONE;
    const FILL_SOLID = Fill::FILL_SOLID;
    const FILL_GRADIENT_LINEAR = Fill::FILL_GRADIENT_LINEAR;
    const FILL_GRADIENT_PATH = Fill::FILL_GRADIENT_PATH;
    const FILL_PATTERN_DARKDOWN = Fill::FILL_PATTERN_DARKDOWN;
    const FILL_PATTERN_DARKGRAY = Fill::FILL_PATTERN_DARKGRAY;
    const FILL_PATTERN_DARKGRID = Fill::FILL_PATTERN_DARKGRID;
    const FILL_PATTERN_DARKHORIZONTAL = Fill::FILL_PATTERN_DARKHORIZONTAL;
    const FILL_PATTERN_DARKTRELLIS = Fill::FILL_PATTERN_DARKTRELLIS;
    const FILL_PATTERN_DARKUP = Fill::FILL_PATTERN_DARKUP;
    const FILL_PATTERN_DARKVERTICAL = Fill::FILL_PATTERN_DARKVERTICAL;
    const FILL_PATTERN_GRAY0625 = Fill::FILL_PATTERN_GRAY0625;
    const FILL_PATTERN_GRAY125 = Fill::FILL_PATTERN_GRAY125;
    const FILL_PATTERN_LIGHTDOWN = Fill::FILL_PATTERN_LIGHTDOWN;
    const FILL_PATTERN_LIGHTGRAY = Fill::FILL_PATTERN_LIGHTGRAY;
    const FILL_PATTERN_LIGHTGRID = Fill::FILL_PATTERN_LIGHTGRID;
    const FILL_PATTERN_LIGHTHORIZONTAL = Fill::FILL_PATTERN_LIGHTHORIZONTAL;
    const FILL_PATTERN_LIGHTTRELLIS = Fill::FILL_PATTERN_LIGHTTRELLIS;
    const FILL_PATTERN_LIGHTUP = Fill::FILL_PATTERN_LIGHTUP;
    const FILL_PATTERN_LIGHTVERTICAL = Fill::FILL_PATTERN_LIGHTVERTICAL;
    const FILL_PATTERN_MEDIUMGRAY = Fill::FILL_PATTERN_MEDIUMGRAY;
}

/**
 * PHPExcel_Cell_DataType Class - Data type constants
 */
class PHPExcel_Cell_DataType
{
    const TYPE_STRING = 's';
    const TYPE_STRING2 = 'str';
    const TYPE_FORMULA = 'f';
    const TYPE_NUMERIC = 'n';
    const TYPE_BOOL = 'b';
    const TYPE_NULL = 'null';
    const TYPE_INLINE = 'inlineStr';
    const TYPE_ERROR = 'e';
}
