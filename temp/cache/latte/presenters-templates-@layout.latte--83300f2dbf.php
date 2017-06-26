<?php
// source: C:\xampp\htdocs\suri\app\presenters/templates/@layout.latte

use Latte\Runtime as LR;

class Template83300f2dbf extends Latte\Runtime\Template
{
	public $blocks = [
		'head' => 'blockHead',
		'scripts' => 'blockScripts',
	];

	public $blockTypes = [
		'head' => 'html',
		'scripts' => 'html',
	];


	function main()
	{
		extract($this->params);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">

	<title><?php
		if (isset($this->blockQueue["title"])) {
			$this->renderBlock('title', $this->params, function ($s, $type) {
				$_fi = new LR\FilterInfo($type);
				return LR\Filters::convertTo($_fi, 'html', $this->filters->filterContent('striphtml', $_fi, $s));
			});
			?> | <?php
		}
?>Nette Sandbox</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 13 */ ?>/css/semantic.css">
	<link rel="stylesheet" href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 14 */ ?>/css/main.css">
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('head', get_defined_vars());
?>
</head>

<body>
	<div class="wrap">
<?php
		$iterations = 0;
		foreach ($flashes as $flash) {
			?>		<div<?php if ($_tmp = array_filter(['flash', $flash->type])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>
			<?php echo LR\Filters::escapeHtmlText($flash->message) /* line 23 */ ?>

		</div>
<?php
			$iterations++;
		}
		$this->renderBlock('content', $this->params, 'html');
?>
		<div class="ui teal basic segment" style="text-align: center;">
			© Lukáš Pícha
		</div>
	</div>

<?php
		$this->renderBlock('scripts', get_defined_vars());
?>
</body>
</html>
<?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 22');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockHead($_args)
	{
		
	}


	function blockScripts($_args)
	{
		extract($_args);
?>
	<script src="https://nette.github.io/resources/js/netteForms.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	
	<script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 35 */ ?>/js/main.js"></script>
	<script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 36 */ ?>/js/semantic.js"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
	}

}
