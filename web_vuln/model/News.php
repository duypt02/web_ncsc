<?php
require_once 'model/Connect.php';
require_once 'model/CommonFunction.php';
session_start();

class News
{
    private $id;
    private $id_user;
    private $username;
    private $title;
    private $content;

    function __construct($id = -1, $id_user = -1, $username = '', $title = '', $content = '', $image = '')
    {
        $this->set_id($id);
        $this->set_username($username);
        $this->set_title($title);
        $this->set_content($content);
        $this->set_id_user($id_user);
        $this->set_image($image);
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($var)
    {
        $this->id = $var;
    }

    public function get_id_user()
    {
        return $this->id_user;
    }

    public function set_id_user($var)
    {
        $this->id_user = $var;
    }

    public function get_username()
    {
        return $this->username;
    }

    public function set_username($var)
    {
        $this->username = $var;
    }

    public function get_title()
    {
        return $this->title;
    }

    public function set_title($var)
    {
        $this->title = $var;
    }

    public function get_content()
    {
        return $this->content;
    }

    public function set_content($var)
    {
        $this->content = $var;
    }

    public function get_image()
    {
        return $this->image;
    }

    public function set_image($var)
    {
        $this->image = $var;
    }

    public function create_news()
    {
        $sql = "insert into news(id_user, title, content, image) values (?, ?, ?, ?)";
        $data = array($this->get_id_user(),$this->get_title(),$this->get_content(),$this->get_image());
        (new Connect())->selectPara($sql, $data);
//        (new Connect())->excute($sql);
    }

    public function getNews($option = 'default', $data = '', $page = 1)
    {
        $so_tin_tuc_tren_1_trang = 5;
        $skip = $so_tin_tuc_tren_1_trang * ($page - 1);
        $sql_get_num_of_pages = '';
        if ($option == 'single') {
            $sql = "select news.*, users.username from news inner join users on users.id_user = news.id_user 
                              where  news.id_user = ? and news.id = ?";
            $payload = array($this->get_id_user(),$this->get_id());
        } else if ($option == 'get_news_by_id_user') {
            $sql_get_num_of_pages = "select count(*) as 'SL' from news where title like ? and id_user= ?";
            $data_2 = array('%'.$data.'%', $this->get_id_user());
            $sql = "select news.*, users.username from news inner join users on users.id_user = news.id_user
               where news.title like ? and news.id_user= ? limit $so_tin_tuc_tren_1_trang offset $skip";
            $payload = array('%'.$data.'%', $this->get_id_user());
        } else {
            $sql_get_num_of_pages = "select count(*) as 'SL' from news where title like ?";
            $data_2 = array('%'.$data.'%');
            $sql = "select news.*, users.username from news inner join users on users.id_user = news.id_user 
               where news.title like ? limit $so_tin_tuc_tren_1_trang offset $skip";
            $payload = array('%'.$data.'%');
        }

        $num_of_pages = (new CommonFunction())->get_num_of_pages($sql_get_num_of_pages, $data_2);
        $_SESSION['page_numbers'] = $num_of_pages;

        $news = (new Connect())->selectPara($sql, $payload);

        if (count($news) > 0) {
            foreach ($news as $each) {
                $object = new self($each->id, $each->id_user, $each->username, $each->title, $each->content, $each->image);
                $result[] = $object;
            }
        }
        return $result;
    }

    public function remove_news($option = 'single')
    {
        if ($option == 'all') {
            $sql = "delete from news where id_user= ?";
            $data = array($this->get_id_user());
            (new Connect())->selectPara($sql, $data);
//            (new Connect())->excute($sql);
        } else {
            $sql = "delete from news where id=? and id_user=?";
            $data = array($this->get_id(), $this->get_id_user());
            (new Connect())->selectPara($sql, $data);
//            (new Connect())->excute($sql);
        }
    }


    public function update_news()
    {
        $sql = "update news set title= ?, content=?, image=? where id_user=? and id=?";
        $data = array($this->get_title(),$this->get_content(), $this->get_image(), $this->get_id_user(), $this->get_id());
        (new Connect())->selectPara($sql, $data);
    }


}