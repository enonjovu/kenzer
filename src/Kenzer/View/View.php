<?php

namespace Kenzer\View;
use Kenzer\Application\Application;
use Kenzer\Http\Response;
use Kenzer\Interface\Data\Responsable;
use Kenzer\Interface\Http\ResponseInterface;
use Kenzer\Utility\FileManager;
use Stringable;

class View implements
    Stringable,
    Responsable
{
    private ViewContentCompiler $compiler;
    private ?string $layoutViewPath = null;
    private ?string $slotContent = null;
    private ?array $layoutViewParams = [];
    private array $contentBlocks = [];
    private ?string $currentBlockName = null;


    public function __construct(
        private string $path,
        private array $params = []
    ) {
        $this->compiler = Application::getInstance()
            ->get(ViewContentCompiler::class);

        $this->layoutViewPath = null;
        $this->layoutViewParams = [];
        $this->contentBlocks = [];
        $this->currentBlockName = null;
        $this->slotContent = null;
    }

    public static function make(string $path, array $params = [])
    {
        return new static($path, $params);
    }

    protected function getViewFullPath() : string
    {
        return FileManager::joinPaths(BASE_DIRECTORY, 'views', sprintf("%s.view.php", $this->path));
    }

    private function getCacheFileName()
    {
        return FileManager::joinPaths(STORAGE_DIRECTORY, 'cache/views', sprintf("%s.php", md5($this->getViewFullPath())));
    }

    public function startBlockContentCapture(string $name)
    {
        $this->currentBlockName = $name;

        ob_start();
    }

    public function endBlockContentCapture()
    {
        if (! $this->currentBlockName) {
            return;
        }

        $this->contentBlocks[$this->currentBlockName] = ob_get_clean();
        $this->currentBlockName = null;
    }

    public function getBlockContent(string $name) : ?string
    {
        $output = array_key_exists($name, $this->contentBlocks) ? $this->contentBlocks[$name] : null;

        return $output;
    }

    public function setLayoutViewPath(string $path, array $options = [])
    {
        $this->layoutViewPath = $path;
        $this->layoutViewParams = $options;
    }

    private function compileContent(string $content) : string
    {

        $content = preg_replace(
            '/\{\{\s*(.*?)\s*\}\}/',
            '<?php echo $1 ?>',
            $content
        );

        $content = $this->compiler->process($content);

        return $content;
    }

    private function cacheFile() : void
    {
        $content = FileManager::getFileContent($this->getViewFullPath());
        $content = $this->compileContent($content);

        $content = '<?php /** @var \Kenzer\View\View $this */ ?>'
            . PHP_EOL
            . PHP_EOL
            . $content
            . PHP_EOL
            . PHP_EOL
            . sprintf("<?php //PATH:%s ?>", $this->getViewFullPath())
            . PHP_EOL;

        FileManager::createFile($this->getCacheFileName(), $content);
    }

    public function getSlotContent() : string
    {
        return $this->slotContent ?? '';
    }

    private function setSlotContent(string $content)
    {
        $this->slotContent = $content;

        return $this;
    }

    public function render() : string
    {

        $this->cacheFile();

        ob_start();

        extract($this->params);

        require_once $this->getCacheFileName();

        $data = ob_get_clean();

        if ($this->layoutViewPath && $data) {
            $data = View::make($this->layoutViewPath, $this->layoutViewParams)
                ->setSlotContent($data);
        }

        return (string) $data;
    }

    function __tostring() : string
    {
        return $this->render();
    }

    function toResponse() : ResponseInterface
    {
        return new Response($this->render());
    }
}
