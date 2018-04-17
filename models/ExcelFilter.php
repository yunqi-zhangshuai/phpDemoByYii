<?php
/**
 * Created by PhpStorm.
 * User: zhangshuai
 * Date: 18-4-15
 * Time: 上午8:44
 */

namespace app\models;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

/**
 * Class ExcelFilter
 * @package app\models
 */
class ExcelFilter implements IReadFilter
{
    public $startRow = 1;
    public $endRow;

    public function __construct($start = 1,$end)
    {
        $this->startRow = $start;
        $this->endRow   = $end;
    }

    public function readCell($column, $row, $worksheetName = '')
  {
      if (!$this->endRow) {
          return true;
      }
      //只读取指定的行
      if ($row >= $this->startRow && $row <= $this->endRow) {
          return true;
      }

      return false;

  }
}