<?php
session_start();

class NewsController
{

    public function create()
    {
        $option = $_GET['option'] ?? '';
        if (($option == 'data') && ($_SERVER['REQUEST_METHOD'] == 'POST')) {
            $id_user = $_SESSION['id_user'];
            $title = $this->test_input($_POST['title'], 'create');
            $content = $this->test_input($_POST['content'], 'create');
            require 'model/News.php';
            (new News(-1, $id_user, '', $title, $content))->create_news();
            if ($_SESSION['role'] == 1) {
                header('Location:?controller=admin&action=view&id_user='.$id_user);
            } else {
                header('Location: index.php');
            }
        } else {
            require 'view/news_create.php';
        }
    }


    public function view()
    {
        $id = $_GET['id'];
        require 'model/News.php';
        $result = (new News($id))->getNews('single');
        require 'view/view_content.php';

    }

    public function edit()
    {
        $id = $_GET['id'];
        require 'model/News.php';
        $result = (new News($id, $_SESSION['id_user']))->getNews('single');
        require 'view/news_edit.php';
    }

    public function update()
    {
        $id = $_POST['id'];
        $id_user = $_SESSION['id_user'];
        $username = $_SESSION['username'];
        $title = $this->test_input($_POST['title'], 'edit', $id);
        $content = $this->test_input($_POST['content'], 'edit', $id);
        require 'model/News.php';
        (new News($id, $id_user, $username, $title, $content))->update_news();
        header('Location: index.php');
    }

    public function remove()
    {
        $id = $_GET['id'];
        $id_user = $_SESSION['id_user'];
        require 'model/News.php';
        (new News($id, $id_user))->remove_news();
        header('Location: index.php');
    }


    private function test_input($data, $action, $id = -1)
    {

        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        if ($data == '') {
            header('Location: index.php?controller=news&action=' . $action . '&error=error&id=' . $id);
            exit();
        }
        return $data;
    }


}