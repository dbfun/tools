<?
class ExcelSchema {
  // rowStart - номер строки, с которой начинается парсинг
  // rowEndControl - столбец, по которому контроллируется конец документа
  // separator - разделитель десятичной дроби // TODO поведение при одновременном обнаружении ,.
  // fields - набор полей
  //  cell - Excel - столбец
  //  col - столбец БД
  //  type - тип (int, decimal, string)
  public function __construct($schemaPathName) {
    $schema = file_get_contents($schemaPathName);
    $this->schema = json_decode($schema);
    if(!is_object($this->schema)) throw new Exception('Invalid JSON scheme');
    }
  public function getRowStart(){
    return $this->schema->rowStart;
    }
  public function getFields() {
    return $this->schema->fields;
    }
  public function getRowEndControl() {
    return $this->schema->rowEndControl;
    }
  public function getSaver() {
    $fileName = $this->schema->saver->type.".php";
    require($fileName);
    $saver = new $this->schema->saver->type($this->schema->saver->options);
    if (isset($this->schema->customFields)) $saver->setCustomFields($this->schema->customFields);
    return $saver;
    }
    
  }




