<?php

function debug($variable){
    if (!empty($variable))
        echo "<pre>" . print_r($variable, true) . "</pre>";
}