<?php
//API 文档
Routes::get('/product/api','Product@Product@api');

Routes::get('/user/product','Product@Product@sell');

Routes::get('/user/count','Product@Product@count');

Routes::get('/user/buy','Product@Product@buy');