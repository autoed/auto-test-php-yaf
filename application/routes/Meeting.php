<?php
//API 文档
Routes::get('/meeting/api','Meeting@Meeting@api');

Routes::get('/user/start','Meeting@Meeting@start');

Routes::get('/user/leave','Meeting@Meeting@leave');

Routes::get('/user/join','Meeting@Meeting@join');