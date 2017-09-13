<?php
namespace App\View\Helper;

use Cake\ORM\TableRegistry;
use Cake\View\Helper;

class UsersHelper extends Helper
{
    /**
     * conver cellular numbers
     *
     * @param string|null $num User cell number
     * @return string $num
     */
    public function numberConvert($num = null)
    {
        $num = '+1' . $num;
        $num = str_replace(['(', ')', ' '], '-', $num);
        $num = str_replace('--', '-', $num);

        return $num;
    }
}
