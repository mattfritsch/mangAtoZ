<?php
include('scriptdata.php');
include('scriptCategories.php');

getCategoriesDesc();

for($i = 0; $i<150; $i = $i+10) {
    getData($i);
}

