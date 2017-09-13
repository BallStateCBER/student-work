<?php
namespace App\View\Helper;

use Cake\ORM\TableRegistry;
use Cake\View\Helper;

class UsersHelper extends Helper
{
    /**
     * conver cellular numbers
     *
     * @param $num
     * @return $num
     */
    public function numberConvert($num)
    {
        $num = '+1' . $num;
        $num = str_replace(['(', ')', ' '], '-', $num);
        $num = str_replace('--', '-', $num);

        return $num;
    }
}
