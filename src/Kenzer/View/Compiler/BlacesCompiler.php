<?php

declare(strict_types=1);

namespace Kenzer\View\Compiler;

use Kenzer\Interface\View\ViewContentCompiler;

class BlacesCompiler implements ViewContentCompiler
{
    public function process(string $subject): string
    {

        $content = preg_replace(
            '/\{\{\s*(.*?)\s*\}\}/',
            '<?php echo safeHtml($1) ?>',
            $subject
        );

        $content = preg_replace(
            '/\{\!\!\s*(.*?)\s*\!\!\}/',
            '<?php echo $1 ?>',
            $content
        );

        return $content;
    }
}
