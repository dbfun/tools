<?php

class JsonSaver extends ExcelSaver {

  private $options, $fp, $customFields, $data;
  public function __construct(stdClass $options) {
    $this->options = $options;
    $this->fp = fopen($this->options->file, 'wb+');
    if(!is_resource($this->fp)) throw new Exception("Can not open output file: ", $this->options->file);
  }

  public function __destruct() {
    fclose($this->fp);
  }

  public function setCustomFields($customFields) {
    throw new Exception("Not applied", 1);
  }

  public function beforeSaveBegin() {}

  public function afterSave() {
    fwrite($this->fp, json_encode($this->data));
  }

  public function save($data) {
    $this->data[] = $data;
  }
}