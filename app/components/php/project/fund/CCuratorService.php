<?php

namespace app\perree\fund;

use \app\m;
use \app\h;

class CCuratorService
{
    public function findCurators($string)
    {
        $curators = m::app()->db->query(
            "
                SELECT *
                FROM curator
                WHERE name LIKE :curatorName
            ",
            [":curatorName" => '%' . $string . '%'],
            '\app\perree\fund\MCurator'
        );
        return $curators;
    }
}