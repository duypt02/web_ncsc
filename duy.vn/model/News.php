<?php
require 'model/Connect.php';
require 'model/CommonFunction.php';
session_start();

class News
{
    private $id;
    private $id_user;
    private $username;
    private $title;
    private $content;

    function __construct($id = -1, $id_user = -1, $username = '', $title = '', $content = '')
    {
        $this->set_id($id);
        $this->set_username($username);
        $this->set_title($title);
        $this->set_content($content);
        $this->set_id_user($id_user);
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

    public function create_news()
    {
        $sql = "insert into news(id_user, title, content) values ('{$this->get_id_user()}', '{$this->get_title()}', '{$this->get_content()}')";
        (new Connect())->excute($sql);
    }

    public function getNews($option = 'default', $data = '', $page = 1)
    {
        $so_tin_tuc_tren_1_trang = 5;
        $skip = $so_tin_tuc_tren_1_trang * ($page - 1);
        $sql_get_num_of_pages = '';
        if ($option == 'single') {
//            $sql = "select news.*, users.username from news inner join users on users.id_user = news.id_user where news.id_user = '{$this->get_id_user()}' and news.id = '{$this->get_id()}'";
            $sql = "select news.*, users.username from news inner join users on users.id_user = news.id_user where news.id = '{$this->get_id()}'";
        }else if($option == 'get_news_by_id_user'){
            $sql_get_num_of_pages = "select count(*) from news where title like '%$data%' and id_user='{$this->get_id_user()}'";
            $sql = "select news.*, users.username from news inner join users on users.id_user = news.id_user
               where news.title like '%$data%' and news.id_user='{$this->get_id_user()}'
               limit $so_tin_tuc_tren_1_trang
              offset $skip";
        }
        else {
            $sql_get_num_of_pages = "select count(*) from news where title like '%$data%'";
            $sql = "select news.*, users.username from news inner join users on users.id_user = news.id_user 
               where news.title like '%$data%'
               limit $so_tin_tuc_tren_1_trang
              offset $skip";

        }

        $num_of_pages = (new CommonFunction())->get_num_of_pages($sql_get_num_of_pages);
        $_SESSION['page_numbers'] = $num_of_pages;
        $result = (new Connect())->select($sql);
        $arr = [];
        if (mysqli_num_rows($result) > 0) {
            foreach ($result as $each) {
                $object = new self($each['id'], $each['id_user'], $each['username'], $each['title'], $each['content']);
                $arr[] = $object;
            }
        }
        return $arr;
    }

    public function remove_news($option = 'single')
    {
        if ($option == 'all') {
            $sql = "delete from news where id_user='{$this->get_id_user()}'";
            (new Connect())->excute($sql);
        } else {
            $sql = "delete from news where id={$this->get_id()} and id_user='{$this->get_id_user()}'";
            (new Connect())->excute($sql);
        }
    }


    public function update_news()
    {
        $sql = "update news set title='{$this->get_title()}', content='{$this->get_content()}' where id_user='{$this->get_id_user()}' and id='{$this->get_id()}'";

        (new Connect())->excute($sql);
    }

    private function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


}