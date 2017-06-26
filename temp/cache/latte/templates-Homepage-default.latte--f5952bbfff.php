<?php
// source: C:\xampp\htdocs\suri\app\presenters/templates/Homepage/default.latte

use Latte\Runtime as LR;

class Templatef5952bbfff extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'content' => 'html',
	];


	function main()
	{
		extract($this->params);
?>

<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['param'])) trigger_error('Variable $param overwritten in foreach on line 41');
		if (isset($this->params['value'])) trigger_error('Variable $value overwritten in foreach on line 41');
		if (isset($this->params['row'])) trigger_error('Variable $row overwritten in foreach on line 29');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>

	<h2 class="ui teal header">
		<div class="content">
			<i class="table icon"></i>
			Přehled sjednaných pojistek 
		</div>
	</h2>
	<div class="ui divider"></div>
	<a class="ui orange button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("order")) ?>"><i class="add icon"></i> Nová smlouva</a>
	<table class="ui celled  selectable table" style="text-align: center;">
		<thead>
			<tr>
				<th>č. smlovy</th>
				<th>Datum vytvoření</th>
				<th>Pojistník</th>
				<th>Datum narození pojistníka</th>
				<th>PSČ pojistíka</th>
				<th>Typ vozidla</th>
				<th>Objem válců (cm3)</th>
				<th>Výkon motoru (kW)</th>
				<th>Hmotnost vozidla (kg)</th>
				<th>Celková cena (Kč)</th>
				<th colspan="6">Výpočet</th>
			</tr>
		</thead>
		<tbody>
<?php
		$iterations = 0;
		foreach ($insurances as $row) {
?>
				<tr>
					<td><?php echo LR\Filters::escapeHtmlText($row->id) /* line 31 */ ?></td>
					<td><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $row->created, "j.n.Y H:i:s")) /* line 32 */ ?></td>
					<td><?php echo LR\Filters::escapeHtmlText($row->client->name) /* line 33 */ ?></td>
					<td><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $row->client->bday_date, "j.n.Y")) /* line 34 */ ?></td>
					<td><?php echo LR\Filters::escapeHtmlText($row->client->zip_code) /* line 35 */ ?></td>
					<td><?php echo LR\Filters::escapeHtmlText($row->car->car_types->name) /* line 36 */ ?></td>
					<td><?php echo LR\Filters::escapeHtmlText($row->car->engine_capacity) /* line 37 */ ?></td>
					<td><?php echo LR\Filters::escapeHtmlText($row->car->engine_power) /* line 38 */ ?></td>
					<td><?php echo LR\Filters::escapeHtmlText($row->car->weight) /* line 39 */ ?></td>
					<td><?php echo LR\Filters::escapeHtmlText($row->total_price) /* line 40 */ ?></td>
<?php
			$iterations = 0;
			foreach (unserialize($row->calculation) as $param => $value) {
				if ($value < 0) {
					?>							<td class="negative"><strong><?php echo LR\Filters::escapeHtmlText($param) /* line 43 */ ?>:</strong>&nbsp<i><?php
					echo LR\Filters::escapeHtmlText($value) /* line 43 */ ?></i><br></td>
<?php
				}
				elseif ($value > 1) {
					?>							<td class="positive"><strong><?php echo LR\Filters::escapeHtmlText($param) /* line 45 */ ?>:</strong>&nbsp<i><?php
					echo LR\Filters::escapeHtmlText($value) /* line 45 */ ?></i><br></td>
<?php
				}
				else {
					?>							<td class="disabled"><strong><?php echo LR\Filters::escapeHtmlText($param) /* line 47 */ ?>:</strong>&nbsp<i><?php
					echo LR\Filters::escapeHtmlText($value) /* line 47 */ ?></i><br></td>
<?php
				}
				$iterations++;
			}
?>
				</tr>
<?php
			$iterations++;
		}
?>
		</tbody>
	</table>
<?php
	}

}
