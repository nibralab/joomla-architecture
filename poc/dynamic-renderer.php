<?php
trait DynamicRendererImplementation {
	private $handlers = array();

	public function registerContentType($type, Callable $handler) {
		$this->handlers[strtolower($type)] = $handler;
	}

	public function visit($contentType, array $arguments) {
		// Maybe needs more sanitisation
		$type = strtolower($contentType);
		if (!isset($this->handlers[$type])) {
			$type = 'default';
		}
		if (isset($this->handlers[$type])) {
			$handler = $this->handlers[$type];
			$this->output .= $handler($arguments[0]);
		} else {
			echo "\nLogWarn: Unknown content type {$contentType}, no default\n";
		}
	}
}

class Renderer {
	protected $output = '';

	use DynamicRendererImplementation;

	public function visitContent(ContentType $content) {
		$this->output .= __METHOD__ . ': ' . $content->getContents() . "\n";
	}

	public function getOutput() {
		return $this->output;
	}
}

abstract class Content {
	protected $content = 'undefined';
	public function __construct($content) {
		$this->content = $content;
	}
	abstract public function accept(Renderer $renderer);
	public function getContents() {
		return $this->content;
	}
}

class ContentType extends Content {
	public function accept(Renderer $renderer) {
		$renderer->visit('Content', array($this));
	}
}

class NewContentType extends Content {
	public function accept(Renderer $renderer) {
		$renderer->visit('NewContent', array($this));
	}
	public function asHtml(NewContentType $content) {
		return __METHOD__ . ': ' . $content->getContents() . "\n";
	}
}

class OtherContentType extends Content {
	public function accept(Renderer $renderer) {
		$renderer->visit('OtherContent', array($this));
	}
}

class UnregisteredContentType extends Content {
	public function accept(Renderer $renderer) {
		$renderer->visit('UnregisteredContent', array($this));
	}
}

$renderer = new Renderer;

$renderer->registerContentType('NewContent', array('NewContentType', 'asHtml'));
$renderer->registerContentType('OtherContent', function(OtherContentType $content) {
	return __METHOD__ . '(1): ' . $content->getContents() . "\n";
});
$renderer->registerContentType('Default', function (Content $content) {
	return __METHOD__ . '(2): ' . $content->getContents() . "\n";
});
/** @var Content[] $content */
$content = array(
	new ContentType('ContentType'),
	new NewContentType('NewContentType'),
	new OtherContentType('OtherContentType'),
	new UnregisteredContentType('UnregisteredContentType'),
);

foreach ($content as $c) {
	$c->accept($renderer);
}

echo "\nOutput:\n" . $renderer->getOutput();
