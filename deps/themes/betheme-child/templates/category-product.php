<?php
/**
 * Created by PhpStorm.
 * User: feader
 * Date: 2018/05/25
 * Time: 11:50
 */

$data = [
    'catId' => $cid,
    'id'    => get_the_ID(),
    'start' => 0,
    'end'   => 4,
];

$related_product = loadMoreProductByCatId($data);

?>