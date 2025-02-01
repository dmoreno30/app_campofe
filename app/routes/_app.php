<?php
app()->POST('/desblindar/{id}/{tipoDocumento}/{documento}', 'CampoFeConsejerosController@desblindar');
app()->POST('/consejeros/blindar/{idProspecto}/{cod_vendedor}/{dsc_vendedor}', 'CampoFeConsejerosController@blindar');
app()->POST('/consejeros', 'CampoFeConsejerosController@index');
app()->POST('/consejeros/listar', 'CampoFeConsejerosController@list');
app()->POST('/', 'CampoFeController@index');
app()->POST('/search', 'CampoFeController@add');
app()->POST('/prospectos/{idProspecto}', 'Bitrix24Controller@update');
app()->POST('/citas/{idProspecto}', 'CampoFeCitasController@index');
