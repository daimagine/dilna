<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adi
 * Date: 10/12/12
 * Time: 10:56 PM
 * To change this template use File | Settings | File Templates.
 */
class News extends Eloquent {

    public static $table = 'news';

    public static function listAll($criteria=array()) {
        return News::where('status', '=', 1)
            ->order_by('created_at', 'asc')
            ->get();
    }

    public static function recent() {
        return News::where('status', '=', 1)
            ->order_by('created_at', 'asc')
            ->take(5)
            ->get();
    }

    public static function update($id, $data = array()) {
        $news = News::where_id($id)
            ->where_status(1)
            ->first();
        $news->title = $data['title'];
        $news->resume = @$data['resume'];
        $news->file_path = @$data['file_path'];
        $news->content = @$data['content'];
        //save
        $news->save();
        return $news->id;
    }

    public static function create($data = array()) {
        $news = new News;
        $news->title = $data['title'];
        $news->resume = @$data['resume'];
        $news->file_path = @$data['file_path'];
        $news->content = @$data['content'];//save
        $news->save();
        return $news->id;
    }

    public static function remove($id) {
        $news = News::find($id);
        $news->status = 0;
        $news->save();
        return $news->id;
    }

}
