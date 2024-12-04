<?php
app()->POST('/', 'CampoFeController@index');
app()->POST('/search', 'CampoFeAPIController@add');
