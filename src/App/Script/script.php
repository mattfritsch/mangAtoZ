<?php
include('scriptdata.php');
include('scriptCategories.php');

getCategoriesDesc();

for($i = 0; $i<20; $i = $i+10) {
    getData($i);
}

