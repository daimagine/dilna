<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 10/25/12
 * Time: 12:24 AM
 * To change this template use File | Settings | File Templates.
 */
class Message extends Eloquent {

    public static $table = 'message';

    public function user() {
        return $this->belongs_to('User');
    }

    public function sender() {
        return $this->belongs_to('User', 'sender_id');
    }

    public function conversation() {
        return $this->belongs_to('Conversation', 'topic_id');
    }

}
