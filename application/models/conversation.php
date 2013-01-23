<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 10/25/12
 * Time: 12:23 AM
 * To change this template use File | Settings | File Templates.
 */
class Conversation extends Eloquent {

    public static $table = 'conversation';

    public function users() {
        return $this->has_many_and_belongs_to('User');
    }

    public function messages() {
        return $this->has_many('Message', 'topic_id');
    }

    public function markRead($user_id=null) {
        //mark read conversation user
        DB::table('conversation_user')
            ->where('conversation_id', '=', $this->id)
            ->where('user_id', '=', $user_id)
            ->update(array(
                'read' => true
            ));
        //mark read message
        DB::table('message')
            ->where('topic_id', '=', $this->id)
            ->where('user_id', '=', $user_id)
            ->update(array(
            'read' => true
        ));
    }

    public static function listAll() {
        return Conversation::with('users')->get();
    }

    public static function send($data) {
        try {

            array_push($data['user'], $data['sender']);
            sort($data['user']);
            //dd($data);
            $convid = DB::table(static::$table)
                ->join('conversation_user', 'conversation_user.conversation_id', '=', static::$table.".id")
                ->where('conversation_user.key', '=', static::generateKey($data['user']))
                ->take(1)
                ->only(static::$table.".id")
                ;
//            dd(DB::last_query());
//            dd($convid);

            //if already have conversation with list of user
            if($convid === false) {
                $log = 'not found';
                $conversation = Conversation::create(array(
                    'subject' => Utilities\Stringutils::snippet($data['message'])
                ));

                foreach($data['user'] as $u) {
                    $add = array();
                    if($u == $data['sender']) {
                        $add['read'] = true;
                    }
                    $add['key']  = static::generateKey($data['user']);
                    $conversation->users()->attach($u, $add);
                }
                $conversation->users()->sync($data['user']);

            } else {
                $conversation = Conversation::find($convid);
                $conversation->subject = Utilities\Stringutils::snippet($data['message']);
                $conversation->save();

                DB::table('conversation_user')
                    ->where('conversation_id', '=', $conversation->id)
                    ->where('user_id', '=', $data['sender'])
                    ->update(array(
                        'read' => true,
                        'key'  => static::generateKey($data['user'])
                    ));

                //flag others pivot to unread
                DB::table('conversation_user')
                    ->where('conversation_id', '=', $conversation->id)
                    ->where('user_id', '<>', $data['sender'])
                    ->update(array(
                        'read' => false,
                    ));
                $log = $conversation;
            }

            //create message
            foreach($data['user'] as $user) {
                $m = array(
                    'user_id' => $user,
                    'message' => $data['message'],
                    'sender_id' => $data['sender']
                );
                if($user == $data['sender']) {
                    $m['read'] = true;
                }
                $message = new Message($m);
                $conversation->messages()->insert($message);
            }

            return $conversation;

        } catch (Exception $ex) {
            Log::exception($ex);
            dd($ex);
            return false;
        }
    }

    public function self() {
        $res = null;
        foreach($this->users()->pivot()->get() as $piv) {
            if($piv->user_id == Auth::user()->id) {
                //dd($piv);
                $res = $piv->to_array();
                break;
            }
        }
        return $res;
    }

    public function get_list_user() {
        $res = null;
        $i = sizeof($this->users);
        $j = 1;
        foreach($this->users as $u) {
            if($u->id != Auth::user()->id) {
                $res .= $u->name;
                if($j < $i )
                    $res .= ', ';
            }
            $j++;
        }
        return $res;
    }

    public function list_image() {
        if(sizeof($this->users) > 2) {
            return "images/userLogin.png";
        }
        $image = 'images/uploads/user/';
        foreach($this->users() as $u) {
            if($u->id != Auth::user()->id) {
                $image .= $u->picture;
                break;
            }
        }
        return $image;
    }

    public static function generateKey($users_id=array()) {
        $res = "";
        foreach($users_id as $id)
            $res .= $id . "z";
        return $res;
    }

}
