<?php

//加密字段
function md5X($field,$salt = '~'){
    return md5($salt.md5($field));
}
