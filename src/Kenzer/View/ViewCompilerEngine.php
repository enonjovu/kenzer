<?php

declare(strict_types=1);

namespace Kenzer\View;

use Kenzer\Application\Application;
use Kenzer\Utility\FileManager;
use Kenzer\View\Compiler\BlacesCompiler;
use Kenzer\View\Compiler\DirectiveCompiler;

class ViewCompilerEngine
{
    public function __construct(
        private string $inputFilePath,
        private string $outputFilePath,
    ) {}

    public function execute()
    {
        $content = FileManager::getFileContent($this->inputFilePath);

        $compilers = [
            new BlacesCompiler,
            new DirectiveCompiler(Application::getInstance()->get('config.view.directives')),
        ];

        $content = array_reduce($compilers, fn ($carry, $pr) => $pr->process($carry), $content);

        $content = '<?php /** @var \Kenzer\View\View $this */ ?>'
            .PHP_EOL
            .PHP_EOL
            .$content
            .PHP_EOL
            .PHP_EOL
            .sprintf('<?php //PATH:%s ?>', $this->inputFilePath)
            .PHP_EOL;

        FileManager::createFile($this->outputFilePath, $content);
    }
}
