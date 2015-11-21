#!/usr/bin/env php
<?

require(__DIR__.'/../../application.php');
PApplication::init();

class StatAggregator {
  private $db;
  
  public function __construct() {
    $this->db = PFactory::getDbo('dev_test');
  }
  
  private static function cmp($a, $b) {
    if ($a->count == $b->count) return 0;
    return ($a->count < $b->count) ? 1 : -1;
  }
  
  public function updPersonsStat() {
    $persons = array();
    $query = "SELECT * FROM `wdi_p`";
    $projectRows = $this->db->SelectSet($query);
    foreach ($projectRows as $row) {
      $statistic = json_decode($row['statistic']);
      if (count($statistic) > 0) foreach ($statistic as $record) {
        $persons[$record->name] = isset($persons[$record->name]) ? $persons[$record->name] + $record->count : $record->count;
      }
    }
      
    foreach ($persons as $name => $numCommits) {
      $query = "INSERT INTO `wdi_persons` (`name`, `num_commits`) VALUES ('".addslashes($name)."', ".(int)$numCommits.") "
        ."ON DUPLICATE KEY UPDATE `num_commits` = ".(int)$numCommits;
      $this->db->Query($query);
    }
  }
  
  public function updProjectsStat() {
    $query = "SELECT * FROM `wdi_p`";
    $projectRows = $this->db->SelectSet($query);
    foreach ($projectRows as $row) {
      $statistic = json_decode($row['statistic']);
      $totalCount = 0;
      if (count($statistic) == 0) continue;
      foreach ($statistic as $record) {
        $totalCount += $record->count;
        }
      usort($statistic, 'self::cmp');
      $statistic = array_slice($statistic, 0, 3);
      foreach ($statistic as &$record) {
        $record->count = number_format($record->count / $totalCount * 100, 0);
      }
      unset($record);
      $query = "UPDATE `wdi_p` SET `three` = '".json_encode($statistic)."' WHERE `project_id` = ".$row['project_id']." LIMIT 1";
      $this->db->Query($query);
    }
  }

}

$statAggregator = new StatAggregator();
$statAggregator->updPersonsStat();
$statAggregator->updProjectsStat();





