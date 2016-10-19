<?php
/**
 * 用户基类
 * @author yanyt 41274611@qq.com
 */

class User implements SplSubject{
    
    private $_db = null;
    
    //注册观察者
     public $observers = array();

     //动作类型
     CONST OBSERVER_TYPE_REGISTER = 1;//注册
    
    public function __construct ()
    {
        $this->_db = DB::getInstance();
    }
     /**
      *追加观察者
      *@param SplObserver $observer 观察者
      *@param int $type 观察类型
      */
     public function attach(SplObserver $observer)
     {
         $this->observers[] = $observer;
         echo '注册观察者:'.__FILE__.__LINE__.PHP_EOL;
     }
      /**
      *去除观察者
      *@param SplObserver $observer 观察者
      *@param int $type 观察类型
      */
     public function detach(SplObserver $observer)
     {
         $idx = array_search($observer, $this->observers, true);
         if(FALSE !== $idx)
         {
             unset($this->observers[$idx]);
         }
     }
     /**
      *满足条件时通知观察者
      *@param int $type 观察类型
      */
     public function notify()
     {
         if(!empty($this->observers))
         {
             foreach($this->observers as $observer)
             {
                 $observer->update($this);
             }
         }
     }
     /**
      *添加用户
      *@param str $username 用户名
      *@param str $password 密码
      *@param str $email 邮箱
      *@return bool
      */
     public function addUser()
     {

         //执行sql

         //数据库插入成功
         $res = true;

         //调用通知观察者
         echo '通知观察者'.PHP_EOL;
         $this->notify();

         return $res;
     }
     public function transfer()
     {
         
     }
             

}
