<?php
pc_base::load_app_class('api', 'api');
class myList_course  extends api{

    public function Mylist_course(){

        $schedulings = "wangyuqi";
        include template('api', 'myList_course');
    }


}

?>