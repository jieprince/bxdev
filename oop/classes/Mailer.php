<?php
/**
 * 
 * @author yanyt 41274611@qq.com
 */
class Mailer implements SplObserver
 {
     /**
      *相应被观察者的变更信息
      *@param SplSubject $subject
      */
     public function update(SplSubject $subject)
     {
         $title='mail title';
         $content = 'mail content';
         $this->sendEmail($subject->email, $title, $content);
     }

     /**
      *发送邮件
      *@param str $email 邮箱地址
      *@param str $title 邮件标题
      *@param str $content 邮件内容
      */
     public function sendEmail($email, $title, $content)
     {
         //调用邮件接口，发送邮件
         echo '发送邮件'.PHP_EOL;
     }
 }

