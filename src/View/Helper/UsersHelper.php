<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class UsersHelper extends Helper
{
    public function numberConvert($num)
    {
        $num = '+1' . $num;
        $num = str_replace(['(', ')', ' '], '-', $num);
        $num = str_replace('--', '-', $num);

        return $num;
    }
}
