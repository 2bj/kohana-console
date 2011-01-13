<?php defined('SYSPATH') or die('No direct script access.');?>

<h1>Error Generator</h1>

<p>This generator generates an error file for specified model.</p>

<div class="form gii">
	<p class="note">
		Fields with <span class="required">*</span> are required.
	</p>
	<?=form::open()?>
		<div class="row">
			<label>
				Class name
				<span class="required">*</span>
			</label>
			<?=form::input('class', $command->class, array('size'=>65)); ?>
			<div class="tooltip">
				This is the name of the model class to be generated (e.g. <code>Post</code>, <code>Comment</code>).
			</div>
			<div class="errorMessage"><?=arr::get($errors, 'class')?></div>
		</div>
		<div class="row">
			<label>
				File
			</label>
			<?=form::input('file', $command->file, array('size'=>65)); ?>
			<div class="tooltip">
				Name of the created file. If empty uses <class>_error.
			</div>
			<div class="errorMessage"><?=arr::get($errors, 'file')?></div>
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