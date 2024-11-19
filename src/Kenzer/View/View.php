<?php

declare(strict_types=1);

namespace Kenzer\View;

use Kenzer\Http\Response;
use Kenzer\Interface\Data\Responsable;
use Kenzer\Interface\Http\ResponseInterface;
use Kenzer\Utility\AttributeBag;
use Kenzer\Utility\FileManager;
use Stringable;

class View implements Responsable, Stringable
{
    private ?string $layoutViewPath = null;

    private ?string $slotContent = null;

    private ?array $layoutViewParams = [];

    private array $contentBlocks = [];

    private ?string $currentBlockName = null;

    private ViewCompilerEngine $engine;

    private static ?AttributeBag $globalParams = null;

    private AttributeBag $params;

    public function __construct(
        private string $path,
        array $params = []
    ) {
        $this->layoutViewPath = null;
        $this->layoutViewParams = [];
        $this->contentBlocks = [];
        $this->currentBlockName = null;
        $this->slotContent = null;
        $this->params = new AttributeBag($params);

        $this->engine = new ViewCompilerEngine(
            $this->getViewFullPath(),
            $this->getCacheFileName()
        );

        static::$globalParams ??= new AttributeBag([]);

    }

    public static function make(string $path, array $params = [])
    {
        return new static($path, $params);
    }

    protected function getViewFullPath(): string
    {
        return FileManager::joinPaths(BASE_DIRECTORY, 'views', sprintf('%s.view.php', $this->path));
    }

    private function getCacheFileName()
    {
        return FileManager::joinPaths(STORAGE_DIRECTORY, 'cache/views', sprintf('%s.php', md5($this->getViewFullPath())));
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

    public function getBlockContent(string $name): ?string
    {
        $output = array_key_exists($name, $this->contentBlocks) ? $this->contentBlocks[$name] : null;

        return $output;
    }

    public static function putGlobal(string $key, mixed $value): void
    {
        static::$globalParams ??= new AttributeBag([]);
        static::$globalParams->set($key, $value);
    }

    public function setLayoutViewPath(string $path, array $options = [])
    {
        $this->layoutViewPath = $path;
        $this->layoutViewParams = $options;
    }

    public function getSlotContent(): string
    {
        return $this->slotContent ?? '';
    }

    private function setSlotContent(string $content)
    {
        $this->slotContent = $content;

        return $this;
    }

    public function render(): string
    {
        $this->engine->execute();

        ob_start();

        $viewData = $this->params->extend(static::$globalParams)->toArray();

        extract($viewData);

        require_once $this->getCacheFileName();

        $data = ob_get_clean();

        if ($this->layoutViewPath && $data) {
            $data = View::make($this->layoutViewPath, $this->layoutViewParams)
                ->setSlotContent($data);
        }

        return (string) $data;
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function toResponse(): ResponseInterface
    {
        return new Response($this->render());
    }
}
