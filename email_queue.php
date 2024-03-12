<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

use Mvc\Core\DB;
use Mvc\Core\Mail;

$emails = DB::table("email_queue")->whereNull("sent_at")->get();

if(!empty($emails)){
    $mail = new Mail();
    foreach($emails as $item){
       $id = $mail->to($item->to_email)->subject($item->subject)->message($item->message)->send($item->id);
       if($id){
            DB::table("email_queue")->where("id",$id)->limit(1)->delete();
        }
    }
}