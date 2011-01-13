<?php defined('SYSPATH') or die('No direct script access.');?>

<h1>Model Generator</h1>

<p>This generator generates a model class for the specified database table.</p>

<div class="form gii">
	<p class="note">
		Fields with <span class="required">*</span> are required.
	</p>
	<?=form::open()?>
		<div class="row">
			<label>
				Database group
				<span class="required">*</span>
			</label>
			<?php
				$keys = array_keys(Kohana::config('database')->as_array());
				$groups = array_combine($keys, $keys);
			?>
			<?=form::select('group', $groups, $command->group, array('size'=>1, 'style'=>'width: 130px')); ?>
			<div class="tooltip">
				Name of the database group.
			</div>
			<div class="errorMessage"><?=arr::get($errors, 'group')?></div>
		</div>
		<div class="row">
			<label>
				Table
				<span class="required">*</span>
			</label>
			<?=form::input('table', $command->table, array('size'=>65)); ?>
			<div class="tooltip">
				This is the table name in database.
			</div>
			<div class="errorMessage"><?=arr::get($errors, 'table')?></div>
		</div>
		<div class="row">
			<label>
				Class name
			</label>
			<?=form::input('class', $command->class, array('size'=>65)); ?>
			<div class="tooltip">
				This is the name of the model class to be generated (e.g. <code>Post</code>, <code>Comment</code>).
			</div>
			<div class="errorMessage"><?=arr::get($errors, 'class')?></div>
		</div>
		<div class="row">
			<label>
				Driver
				<span class="required">*</span>
			</label>
			<?=form::select('driver', array_combine($command->drivers(), $command->drivers()), $command->driver, array('size'=>1, 'style'=>'width: 130px')); ?>
			<div class="tooltip">
				Orm driver for the class. Can be orm, jelly, sprig, hive.
			</div>
			<div class="errorMessage"><?=arr::get($errors, 'driver')?></div>
		</div>
		<div class="row">
			<label>
				Module
			</label>
			<?php
				$modules = Kohana::modules();
				$modules = arr::unshift($modules, '', '--');
			?>
			<?=form::select('module', $modules, $command->module, array('size'=>1, 'style'=>'width: 130px')); ?>
			<div class="tooltip">
				This is the module in which place model.
			</div>
			<div class="errorMessage"><?=arr::get($errors, 'module')?></div>
		</div>
		<?=View::factory('console/layout/files', array('command'=>$command, 'console'=>$console, 'command_name'=>$command_name))?>
	<?=form::close()?>
</div>