<?php defined('SYSPATH') or die('No direct script access.');?>

<?php
	$files = $console->files();
?>
<div class="buttons">
	<input type="submit" value="Preview" name="preview">
	<?php if ($files):?>
	<input type="submit" value="Generate" name="generate">
	<?php endif ?>
</div>
<?php if ($files):?>
<div class="feedback">
	<input type="hidden" id="answers" name="answers" value="">
	<table class="preview">
		<tr>
			<th class="file">Code File</th>
			<th class="confirm">
				<label for="check-all">Generate</label>
			</th>
		</tr>
		<?php foreach ($files as $hash=>$f):?>
		<tr class="<?=$f->status()?>">
			<td class="file">
				<a href="<?=url::site(Route::get('console/file')->uri(array('file'=>$hash, 'command'=>$command_name)))?>" rel="<?=$f->filename()?>" class="view-code"><?=$f->filename()?></a>
			</td>
			<td class="confirm">
				<label for="answers_<?=$hash?>"><?=$f->status()?></label>
				<input id="answers_<?=$hash?>" type="checkbox" name="answers[<?=$hash?>]" value="1" checked="checked">
			</td>
		</tr>
		<?php endforeach ?>
	</table>
</div>
<?php endif ?>
