<?

abstract class ExcelSaver {
  public abstract function __construct($options);
  public abstract function save($data);
  public abstract function setCustomFields($customFields);
  public function beforeSaveBegin() {}
  public function afterSave() {}
  }