<?php

class CommonFunction{
    public function get_num_of_pages($sql, $data)
    {
        
        if ($sql != '') {
            require_once 'model/Connect.php';
            $num_of_pages = 5;
            $result = (new Connect())->selectPara($sql, $data);

            if(count($result) > 0) {
//                $each = mysqli_fetch_array($result);
                $num_of_rows = $result[0]->SL;
                $so_rows_tren_1_trang = 5;
                $num_of_pages = ceil($num_of_rows / $so_rows_tren_1_trang);
            }
            return $num_of_pages;
        }
        return 1;
    }


}