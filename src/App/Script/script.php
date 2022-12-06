<?php
include('scriptdata.php');
include('scriptCategories.php');

for($i = 0; $i<1000; $i = $i+10) {
    getData($i);
}

getCategoriesDesc();