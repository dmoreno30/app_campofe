<?php
app()->POST('/desblindar/{id}', 'CampoFeController@desblindar');
app()->POST('/', 'CampoFeController@index');
app()->POST('/search', 'CampoFeController@add');
app()->POST('/prospectos/{idProspecto}', 'Bitrix24Controller@update');
app()->POST('/consejeros', 'CampoFeConsejerosController@index');
app()->POST('/consejeros/listar', 'CampoFeConsejerosController@list');
app()->POST('/consejeros/blindar/{idProspecto}/{cod_vendedor}', 'CampoFeConsejerosController@blindar');
