<?php

class ExcelImporter {
  public function __construct() {
    require(PFactory::getDir().'/extend/phpexcel/PHPExcel/IOFactory.php');
  }

  private $schema;
  public function setSchema(ExcelSchema $schema) {
    $this->schema = $schema;
  }

  public function load($docPathName) {
    $this->loadXLS($docPathName);
    $isValidFormat = $this->isValidFormat();
    if (!$isValidFormat) die("Wrong file format $docPathName");
    return $this;
  }

  public function import() {
    $linesProcessed = $this->processWorkbook();
    echo "Lines processed: $linesProcessed".PHP_EOL;
  }

  public function line($line) {
    $fields = $this->schema->getFields();
    $rowEndControl = $this->schema->getRowEndControl();
    $data = $this->getData($line, $fields, $rowEndControl);
    echo var_dump($data);
  }

  public function pick($cell) {
    $fields = $this->schema->getFields();
    list($line, $number) = $this->explodeCellCaption($cell);

    foreach($fields as $_field) {
      if($_field->cell == $line) {
        $field = $_field;
        break;
      }
    }
    if(!isset($field)) die('No such cell in config!');

    echo var_dump($this->getCell($field, $number));
  }

  public function raw($cell) {
    $this->explodeCellCaption($cell);
    echo var_dump($this->getCellRaw($cell));
  }

  private function explodeCellCaption ($cell) {
    if (!preg_match('~^([a-z]{1,})([0-9]{1,})$~i', $cell, $m)) {
      die('Wrong cell format! Use LetterNumber, f.e. A10');
    }
    return array($m[1], $m[2]);
  }

  private $objReader, $objPHPExcel;
  private function loadXLS($fileName) {
    $inputFileType = PHPExcel_IOFactory::identify($fileName);
    $this->objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $this->objReader->setReadDataOnly(true);
    $this->objPHPExcel = $this->objReader->load($fileName);
    $this->objPHPExcel->setActiveSheetIndex(0);
    $this->aSheet = $this->objPHPExcel->getActiveSheet();
  }

  private function getCellRaw($cell) {
    $_cell = $this->aSheet->getCell($cell);
    $text = $_cell->getValue();
    if((substr($text,0,1) === '=' ) && (strlen($text) > 1)) {
      $text = $_cell->getOldCalculatedValue();
    }
    return trim($text);
  }

  private function isValidFormat() { return true;} // TODO

  private function getData($lineNumber, $fields, $rowEndControl) {
    if ($this->getCellRaw($rowEndControl.$lineNumber) == '') return false;
    $ret = array();
    foreach($fields as $field) {
      $data = $this->getCell($field, $lineNumber);
      $ret[$field->col] = $data;
    }

    return count($ret) > 0 ? $ret : false;
  }

  private function getCell($field, $lineNumber) {
    $data = $this->getCellRaw($field->cell.$lineNumber);
    switch ($field->type) {
      case 'int':
        $data = (int)(preg_replace('#[^0-9]*#', '', $data));
        break;
      case 'decimal':
        $separator = isset($field->separator) ? $field->separator : '.';
        $data = (float)str_replace($separator, '.', (preg_replace("#[^0-9,.-]*#", '', $data)));
        break;
      case 'link':
        if ($data) $data = trim(preg_replace('#^http(s)?:\/\/#i', '', $data), '/');
        break;
      case 'idate':
        $data = strtotime($data);
        break;
      case 'fio1':
        $_data = explode(' ', $data, 3);
        $data = $_data[0];
        break;
      case 'fio2':
        $_data = explode(' ', $data, 3);
        $data = $_data[1];
        break;
      case 'fio3':
        $_data = explode(' ', $data, 3);
        $data = $_data[2];
        break;
      case 'sex':
        if(preg_match('~(Елена|Ольга|Натал(ь|и)я|Ирина|Татьяна|Анна|Екатерина|Юлия|Светлана|Мария|Марина|Оксана|Анастасия|Евгения|Виктория|Надежда|Людмила|Галина|Дарья|Олеся|Лариса|Вера|Александра|Ксения|Яна|Валентина|Алла)~i', $data)) $data = $field->female;
        else $data = $field->male;
        break;
      case 'excelDate':
        if(!$data) break;
        $inPast = isset($field->inPast) && $field->inPast;
        $_date = sprintf('%u', PHPExcel_Shared_Date::ExcelToPHP($data));
        $_now = sprintf('%u', time());
        // if($inPast && $_date > $_now) echo "Warning: ".date('d.m.Y', $_date)." in future ".date('d.m.Y', $_now)." $_date, $_now ({$field->cell}, {$lineNumber})".PHP_EOL;
        $data = $_date;
        break;
      case 'custom':
        $_data = $field->custom->default;
        foreach((array)$field->custom->data as $key => $values) {
          if(in_array($data, $values)) {
            $_data = $key;
            break;
          }
        }
        $data = $_data;
        break;
      case 'string':
      default:
    }
    return $data;
  }


  private function processWorkbook() {
    $lineNumber = $this->schema->getRowStart();
    $saver = $this->schema->getSaver();
    $linesProcessed = 0;
    $fields = $this->schema->getFields();
    $rowEndControl = $this->schema->getRowEndControl();

    if (method_exists($saver, 'beforeSaveBegin')) $saver->beforeSaveBegin();

    while ($data = $this->getData($lineNumber, $fields, $rowEndControl)) {
      $lineNumber++;
      $linesProcessed++;
      $saver->save($data);
      }
    if (method_exists($saver, 'afterSave')) $saver->afterSave();
    return $linesProcessed;
  }

}