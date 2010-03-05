<?php defined('SYSPATH') or die('No direct script access.');?>
<?="<?php defined('SYSPATH') or die('No direct script access.');?>"?>

<div class="block">
	<div id="topmenuButtons"></div>
</div>

<?="<?php if (count($$models) == 0):?>"?>
	<div class="block"> 
		<p class="none">Нет объектов</p>
	</div>
<?="<?php else:?>"?>
	<?="<?=form::open('$controller_folder/action', array('id'=>'group_form'))?>"?>
		<?="<?=form::hidden('action', '', array('id'=>'action'))?>"?>
		<div class="block">
			<table class="baseTable">
				<thead>
					<th align="center">ID</th>
					<th align="center" width="10"><input id="check_all" type="checkbox"></th>
					<th width="50">Действия</th>
					<th><?=$object->tk()?></th>
				</thead>
				<tbody>
				<?="<?php foreach ($$models as $$model):?>"?>
					<tr class="<?="<?=text::alternate('odd', 'even')?>"?>">
						<td width="20" align="center"><?="<?=$$model?>->id?>"?></td>
						<td width="10" align="center">
						<input class="check" name="contents_ids[]" value="<?=$<?=$model?>->id?>" type="checkbox">
						</td>
						<td class="nowrap">
							<a href="<?=myurl::referrer('<?=$controller_folder?>/edit/'.$<?=$model?>->id)?>"><?=html::image(theme::images().'icons/edit.png', array('class'=>'icon', 'title'=>'Редактировать'))?></a>
							<a href="#" onclick="return delete_object('<?=$model?>_ids', <?=$<?=$model?>->id?>);"><?=html::image(theme::images().'icons/delete.png', array('class'=>'icon', 'title'=>'Удалить'))?></a>
						</td>
						<td><?=myhtml::anchor('<?=$controller_folder?>/edit/'.$<?=$model?>->id, $<?=$model?>->title)?></td>
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>
		</div>
	<?=form::close()?>

	<?=form::open('<?=$controller_folder?>/action', array('id'=>'single_form'))?>
		<?=form::hidden('action', '')?>
		<?=form::hidden('<?=$model?>_ids[]', '')?>
	<?=form::close()?>
	
<?php endif ?>
<div class="block">
	<div id="menuButtons"></div>
</div>

<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
	<?php if (count($<?=$models?>) > 0):?>
	create_group_menu('menuButtons', [
		new alt.ui.menuitems.DeleteMenuItem('Удалить'),
	]);
	create_group_menu('topmenuButtons', [
		new alt.ui.menuitems.DeleteMenuItem('Удалить'),
	]);
	<?php endif ?>
	
	draw_add_button ('Добавить объект', 'topmenuButtons', '<?=myurl::referrer('<?=$controller_folder?>/edit')?>');
/*]]>*/
</script>