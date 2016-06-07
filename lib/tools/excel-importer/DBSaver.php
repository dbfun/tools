<?php

class DBSaver extends ExcelSaver {

  private $options, $db, $customFields;
  public function __construct(stdClass $options) {
    $this->options = $options;
    $this->db = PFactory::getDbo(isset($this->options->database) ? $this->options->database : '_default');
  }

  public function setCustomFields($customFields) {
    $this->customFields = $customFields;
  }

  public function beforeSaveBegin() {
    if(isset($this->options->SQL_beforeSaveBegin) && $this->options->SQL_beforeSaveBegin) {
      $this->db->Query($this->options->SQL_beforeSaveBegin);
    }
  }

  public function afterSave() {
    if(isset($this->options->SQL_afterSave) && $this->options->SQL_afterSave) {
      $this->db->Query($this->options->SQL_afterSave);
    }
  }

  public function save($data) {
    if(isset($this->customFields) && $this->customFields) {
      eval($this->customFields);
    }

    $wrapApostrophe = function($elm) { return '`'.$elm.'`'; };
    $wrapSlaches = function($elm) { return "'".addslashes($elm)."'"; };

    $q1 = implode(', ', array_map($wrapApostrophe, array_keys($data)));
    $q2 = implode(', ', array_map($wrapSlaches, array_values($data)));

    $query = "INSERT INTO `{$this->options->table}` ($q1) VALUES ($q2)";

    $this->db->Query($query);
  }
}