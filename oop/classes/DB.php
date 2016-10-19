<?php
/**
 * 
 * @author yanyt 41274611@qq.com
 */
 $ROOT_PATH_= str_replace ( 'oop/classes/DB.php', '', str_replace ( '\\', '/', __FILE__ ) ) ;
include_once ($ROOT_PATH_. 'oop/classes/Config.php');
class DB{

  private static $_instance = null;
  private  $_pdo; 
  private  $_query;
  private  $_error = false;
  private  $_results;
  private  $_count = 0;
  
  public function __construct()
  {
    try{
        $this->_pdo = new PDO ('mysql:host=' . Config::get('mysql/host'). ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password') );
        $this->_pdo->exec("SET CHARACTER SET utf8");
    }catch (PDOException $e)
    {
     die($e->getMessage());
    }
  }
  
  public static function getInstance()
  {
    if (!isset(self::$_instance))
    {
      self::$_instance = new DB();
    }
    return self::$_instance;
  }
  
  public function changeDB($dbName)
  {
      $this->_pdo->exec('use ' . $dbName);
  }
  
  public function query($sql, $params = array())
  {
    $this->_error = false;
    if($this->_query = $this->_pdo->prepare($sql))
    {
      if(count($params))
      {
        $x=1;
        foreach($params as $param)
        {
          $this->_query->bindValue($x, $param);
          $x++;
        }
      }
      if($this->_query->execute())
      {
        $this->_results = $this->_query->fetchAll(PDO::FETCH_ASSOC);
        $this->_count = $this->_query->rowCount();
      }else{
        $this->_error=true;
      }
    }
    return $this;
  }
  
  private function action($action, $table, $where = array())
  {
    if(count($where) === 3)
    {
      $operators = array('=', '>', '<', '>=', '<=');
      $field     = $where[0];
      $operator  = $where[1];
      $value     = $where[2];
      if(in_array($operator, $operators))
      {
        $sql = "{$action}  FROM {$table}  WHERE  {$field}  {$operator}  ?" ;
        if(!$this->query($sql, array($value))->error())
        {
          return $this;
        }
      }
    }
    return false;
  }
  
  public function insert($table, $fields = array())
  {
    if(count($fields))
    {
      $keys = array_keys($fields);
      $values = null;
      $x =1;
      foreach($fields as $field){
        $values .='?';
        if($x < count($fields)){
           $values .=', ';
        }
        $x++;
      }
      $sql = "INSERT INTO {$table} (`"  . implode('`,`', $keys) . "`) VALUES ({$values})";
      if(!$this->query($sql, $fields)->error())
      {
        return true;
      }
    }
    return false;
  }
  
  public function update($table, $id, $fields){
    $set = '';
    $x =1;
    foreach ($fields as $name => $value)
    {
      $set .= "{$name} = ?";
      if($x < count($fields))
      {
        $set .= ', ';
      }
      $x++;
    }
    if(is_array($id))
    {
     list($key, $val) = each($id);
     $sql = "UPDATE {$table} SET {$set} WHERE {$key} = {$val}";
    } else {
     $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
    }
    if(!$this->query($sql, $fields)->error())
    {
        return true;
    }
    return false;
  
  }
  
  public function get($table, $where)
  {
    return $this->action('SELECT * ', $table, $where );
  }
  
  public function delete($table, $where)
  {
    return $this->action("DELETE", $table, $where);
  }
  
  public function results()
  {
    return $this->_results;
  }
  
  public function first()
  {
    return $this->_results[0];
  }
  
  public function error()
  {
    return $this->_error;
  }
  
  public function count()
  {
    return $this->_count;
  }
  
  public function getLastInsertId()
  {
      return $this->_pdo->lastInsertId(); 
  }
  //事务处理
  public function transaction($querys)
  {
      if(!is_array($querys))
      {
          return false;
      }
      try{
        $this->_pdo->beginTransaction();//开启事务处理      
        foreach($querys as $sql)
        {
            $this->parseSql($sql);
        }
        $this->_pdo->commit();//交易成功就提交
    }catch(PDOException $e){
        echo $e->getMessage();
        $this->_pdo->rollback();
    }  

  }
  
  public function parseSql($sql)
  {
      if(!is_array($sql))
      {
          return false;
      }else{
          switch($sql['action'] = 'UPDATE')
          {
              case 'UPDATE':
              $this->update($sql['table'], $sql['id'], $sql['fields']);
                  break;
              case 'INSERT':
              $this->insert($sql['table'], $sql['fields']);
                  break;
              case 'DELETE':
              $this->delete($sql['table'], $sql['where']);
                  break;
          }
      }
  }
}
