<?php
// source: C:\xampp\htdocs\suri\app\presenters/templates/Homepage/order.latte

use Latte\Runtime as LR;

class Templatee69e2cdcfc extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'scripts' => 'blockScripts',
	];

	public $blockTypes = [
		'content' => 'html',
		'scripts' => 'html',
	];


	function main()
	{
		extract($this->params);
?>

<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars());
?>


<?php
		$this->renderBlock('scripts', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['error'])) trigger_error('Variable $error overwritten in foreach on line 12');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>
	<h2 class="ui teal header">
		<div class="content">
			<i class="add icon"></i> Nová pojistka
			<div class="sub header">
				<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("default")) ?>">Přehled pojistek</a>
			</div>
		</div>
	</h2>
	<?php
		/* line 11 */
		echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin($form = $_form = $this->global->formsStack[] = $this->global->uiControl["newInsuranceForm"], ['class' => "ui form"]);
?>

<?php
		$iterations = 0;
		foreach ($form->errors as $error) {
?>		<div class="ui red message">
			<?php echo LR\Filters::escapeHtmlText($error) /* line 13 */ ?>

		</div>
<?php
			$iterations++;
		}
?>
		<h4 class="ui dividing header">
			<i class="user icon"></i> Informace o pojistínkovi
		</h4>
		<div class="fields">
			<div class="four wide field">
				<?php if ($_label = end($this->global->formsStack)["name"]->getLabel()) echo $_label ?>

				<?php echo end($this->global->formsStack)["name"]->getControl() /* line 21 */ ?>

			</div>
			<div class="four wide field">
				<?php if ($_label = end($this->global->formsStack)["mail"]->getLabel()) echo $_label ?>

				<?php echo end($this->global->formsStack)["mail"]->getControl() /* line 25 */ ?>

			</div>
			<div class="four wide field">
				<?php if ($_label = end($this->global->formsStack)["bday_date"]->getLabel()) echo $_label ?>

				<?php echo end($this->global->formsStack)["bday_date"]->getControl()->addAttributes(['id' => "bday_date"]) /* line 29 */ ?>

			</div>
			<div class="four wide field">
				<?php if ($_label = end($this->global->formsStack)["zip_code"]->getLabel()) echo $_label ?>

				<?php echo end($this->global->formsStack)["zip_code"]->getControl() /* line 33 */ ?>

			</div>
		</div>

		<h4 class="ui dividing header">
			<i class="car icon"></i> Údaje o autě
		</h4>

		<div class="fields">
			<div class="four wide field">
				<?php if ($_label = end($this->global->formsStack)["car_types"]->getLabel()) echo $_label ?>

				<?php echo end($this->global->formsStack)["car_types"]->getControl()->addAttributes(['class' => "ui dropdown"]) /* line 44 */ ?>

			</div>
			<div class="four wide field">
				<?php if ($_label = end($this->global->formsStack)["engine_capacity"]->getLabel()) echo $_label ?>

				<?php echo end($this->global->formsStack)["engine_capacity"]->getControl() /* line 48 */ ?>

			</div>
			<div class="four wide field">
				<?php if ($_label = end($this->global->formsStack)["engine_power"]->getLabel()) echo $_label ?>

				<?php echo end($this->global->formsStack)["engine_power"]->getControl() /* line 52 */ ?>

			</div>
			<div class="four wide field">
				<?php if ($_label = end($this->global->formsStack)["weight"]->getLabel()) echo $_label ?>

				<?php echo end($this->global->formsStack)["weight"]->getControl() /* line 56 */ ?>

			</div>
		</div>

		<h4 class="ui dividing header">
			<i class="checkmark box icon"></i> Dokončení
		</h4>
		<div class="field" style="text-align: center;">
			<?php echo end($this->global->formsStack)["send"]->getControl()->addAttributes(['class' => "ui teal button"]) /* line 64 */ ?>

		</div>
		
	<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack));
?>

<?php
	}


	function blockScripts($_args)
	{
		extract($_args);
		$this->renderBlockParent('scripts', get_defined_vars());
?>
	<script type="text/javascript">
		$(function() {
            $("#bday_date").datepicker({
                dateFormat: "dd.mm.yy",
               	dayNamesMin: [ "Ne", "Po", "Út", "St", "Čt", "Pá", "So" ],
               	firstDay: 1,
               	monthNames: [ "Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen", "Září", "Říjen", "Listopad", "Prosinec" ]
            });
        });


		/*$("#carTypeSelect").change(function() {
			var carType = $(this).val();
			if(carType == 1 || carType == 2)
			{
				$("#engineCapInput").show();
				$("#enginePowerInput").show();
				$("#engineWeightInput").show();
			}
			else
			{
				$("#engineCapInput").hide();
				$("#enginePowerInput").hide();
				$("#engineWeightInput").show();
			}
		});*/

	</script>
<?php
	}

}
