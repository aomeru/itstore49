<?php

Route::get('/', 'Site\LoginController@index')->name('login');

Route::get('/home', 'Site\LoginController@index')->name('home');

Route::get('/login', 'Site\LoginController@index');

Route::post('/login', 'Site\LoginController@post')->name('post.login');

Route::get('/create-password', 'Site\LoginController@createPassword')->name('create.pass');

Route::post('/store-password', 'Site\LoginController@storePassword')->name('store.pass');

Route::get('{file}', 'Admin\PurchaseController@showFile')->name('showfile')->middleware('is-admin');


Route::group(['prefix' => 'admin', 'middleware' => 'is-admin'], function(){
	$dbcon = 'Admin\DashboardController';
	$dbrkey = 'admin.dashboard';

	Route::get('/', function(){
		return redirect()->route('admin.dashboard');
	});

	Route::get('/dashboard', $dbcon.'@index')->name($dbrkey);


	Route::get('/logout', $dbcon.'@logout')->name('admin.logout');

	Route::get('/test', 'Admin\TestController@index');
	//Route::get('/folder', 'Admin\TestController@folder');

	Route::get('/process', $dbcon.'@process')->name('process');

	Route::get('/logs', $dbcon.'@logs')->name('logs');


	Route::group(['prefix' => 'pages'], function(){
		$con = 'Admin\PagesController@';

		Route::get('/', $con.'pageIndex')->name('admin.pages');

		Route::get('/add', $con.'createPage')->name('create.page');

		Route::get('/add/load-pages', $con.'loadPages')->name('load.pages');

		Route::post('/add', $con.'storePage')->name('store.page');

		Route::get('/{id}/edit', $con.'editPage')->name('edit.page');

		Route::post('/{id}/update', $con.'updatePage')->name('update.page');

		Route::get('/{id}/delete', $con.'deletePage')->name('delete.page');
	});


	Route::group(['prefix' => 'permissions'], function(){
		$con = 'Admin\PagesController@';

		Route::get('/', $con.'permissionsIndex')->name('admin.perm');

		Route::get('/assign', $con.'assignPerm')->name('assign.perm');

		Route::post('/store', $con.'storePerm')->name('store.perm');

		Route::get('/{id}/edit', $con.'editPerm')->name('edit.perm');

		Route::post('/{id}/update', $con.'updatePerm')->name('update.perm');
	});


	Route::group(['prefix' => 'users'], function () {
		$con = 'Admin\UsersController@';
		$rkey = 'admin.users';

		Route::get('/', $con.'index')->name($rkey);

		Route::post('/add', $con.'store')->name($rkey.'.add');

		Route::post('/update', $con.'update')->name($rkey.'.update');

		Route::post('/delete', $con.'delete')->name($rkey.'.delete');

		Route::get('/view/{id}', $con.'show')->name($rkey.'.show');

		Route::post('/reset-password', $con.'resetPassword')->name($rkey.'.rpass');
		//Route::get('/{code}/reset/password', $con.'resetPassword')->name($rkey.'reset.pass');

	});


	Route::group(['prefix' => 'departments-and-units'], function () {
		$con = 'Admin\DepartmentsController@';
		$rkey = 'admin.depts';

		Route::get('/', $con.'index')->name($rkey);

		Route::post('/add', $con.'storeDept')->name($rkey.'.add');

		Route::post('/edit', $con.'updateDept')->name($rkey.'.update');

		Route::post('/delete', $con.'deleteDept')->name($rkey.'.delete');

		Route::get('/view/department/{id}', $con.'showDept')->name($rkey.'.show');

		Route::post('/add-unit', $con.'storeUnit')->name($rkey.'.add.unit');

		Route::post('/update-unit', $con.'updateUnit')->name($rkey.'.update.unit');

		Route::post('/delete-unit', $con.'deleteUnit')->name($rkey.'.delete.unit');

		Route::get('/view/unit/{id}', $con.'showUnit')->name($rkey.'.show.unit');

	});


	Route::group(['prefix' => 'items'], function () {
		$con = 'Admin\ItemsController@';
		$rkey = 'admin.items';

		Route::get('/', $con.'index')->name($rkey);

		Route::post('/add', $con.'storeItem')->name($rkey.'.add');

		Route::post('/edit', $con.'updateItem')->name($rkey.'.update');

		Route::post('/delete', $con.'deleteItem')->name($rkey.'.delete');

	});


	Route::group(['prefix' => 'inventory'], function () {
		$con = 'Admin\InventoryController@';
		$rkey = 'admin.inv';

		Route::get('/', $con.'index')->name($rkey);

		Route::post('/add', $con.'store')->name($rkey.'.add');

		Route::get('/show/{code}', $con.'show')->name($rkey.'.show');

		Route::post('/edit', $con.'update')->name($rkey.'.update');

		Route::post('/delete', $con.'delete')->name($rkey.'.delete');

		Route::post('/log/add', $con.'storeLog')->name($rkey.'.add.log');

		Route::post('/log/edit', $con.'updateLog')->name($rkey.'.update.log');

		Route::post('/log/delete', $con.'deleteLog')->name($rkey.'.delete.log');

	});


	Route::group(['prefix' => 'allocation'], function () {
		$con = 'Admin\AllocationController@';
		$rkey = 'admin.all';

		Route::get('/', $con.'index')->name($rkey);

		Route::post('/add', $con.'store')->name($rkey.'.add');

		Route::post('/edit', $con.'update')->name($rkey.'.update');

		Route::post('/delete', $con.'delete')->name($rkey.'.delete');

	});


	Route::group(['prefix' => 'tasks'], function () {
		$con = 'Admin\TaskController@';
		$rkey = 'admin.tasks';

		Route::get('/', $con.'index')->name($rkey);

		Route::post('/add', $con.'store')->name($rkey.'.add');

		Route::post('/edit', $con.'update')->name($rkey.'.update');

		Route::post('/reassign', $con.'rass')->name($rkey.'.rass');

		Route::post('/delete', $con.'delete')->name($rkey.'.delete');

		Route::get('/view/{code}', $con.'show')->name($rkey.'.show');

		Route::post('/action/add', $con.'storeComment')->name($rkey.'.add.com');

		Route::post('/action/edit', $con.'updateComment')->name($rkey.'.update.com');

		Route::post('/action/delete', $con.'deleteComment')->name($rkey.'.delete.com');

	});


	Route::group(['prefix' => 'purchase-order'], function () {
		$con = 'Admin\PurchaseController@';
		$rkey = 'admin.po';

		Route::get('/', $con.'index')->name($rkey);

		Route::get('/add', $con.'create')->name($rkey.'.add');
		
		Route::post('/add', $con.'store')->name($rkey.'.add.store');
		
		Route::get('/edit/{id}', $con.'edit')->name($rkey.'.edit');

		Route::post('/edit/{id}', $con.'update')->name($rkey.'.update');
		
		Route::post('/delete-file', $con.'deleteFile')->name($rkey.'.delete.file');

		Route::post('/delete', $con.'delete')->name($rkey.'.delete');
		
		Route::get('/view/{code}', $con.'show')->name($rkey.'.show');

		Route::post('/log/add', $con.'storeLog')->name($rkey.'.add.log');

		Route::post('/log/edit', $con.'updateLog')->name($rkey.'.update.log');

		Route::post('/log/delete', $con.'deleteLog')->name($rkey.'.delete.log');

	});


});
