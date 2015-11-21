<?php

/**
 * @package    PApplication
 *
 * @copyright
 */


abstract class PFactory {

  /**
   * Init common classes
   *
   */
   
  private static $dir, $config;
  public static function init()
  {
    self::$dir = dirname(__FILE__).'/../';
    require_once(self::getDir().'/core/mysql.php');
  }
  
  /**
	 * Get library directory.
	 *
	 */
  
  public static function getDir()
  {
    return self::$dir;
  }

  /**
	 * Get a database object.
	 *
	 */

  public static $database = array();
	public static function getDbo($dataBase = '_default')
	{
		if (!isset(self::$database[$dataBase]))
		{
      $dbConfig = self::getDbconfig();
      if ($dataBase != '_default') $dbConfig->dbname = $dataBase;
			self::$database[$dataBase] = new DataBaseMysql($dbConfig);
		}

		return self::$database[$dataBase];
	}
  
  private static $dbConfig;
  private static function getDbconfig() {
    if(isset(self::$dbConfig)) return self::$dbConfig;
    $config = file_get_contents(self::getDir().'/../config/db');
    preg_match_all('#(.*)=(.*)$#m', $config, $_mathes);
    self::$dbConfig = new stdClass();
    for($i = 0; $i < 4; $i++) {
      $key = $_mathes[1][$i];
      $val = $_mathes[2][$i];
      self::$dbConfig->$key = $val;
      }
    return self::$dbConfig;
  }
  
  /**
   * Set global option
   *
   */  
  
  private static $options = array();
  public static function setOpt($name, $value)
  {
    self::$options[$name] = $value;
  }
  
  /**
   * Get global option
   *
   */
  
  public static function getOpt($name)
  {
    return isset(self::$options[$name]) ? self::$options[$name] : null;
  } 
  
}

