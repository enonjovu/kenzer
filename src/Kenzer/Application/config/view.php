<?php

declare(strict_types=1);

return [
    'directives' => [
        'if' => fn ($expression) => "<?php if($expression): ?>",
        'isset' => fn ($expression) => "<?php if(isset($expression)): ?>",
        'foreach' => fn ($expression) => "<?php foreach($expression): ?>",
        'for' => fn ($expression) => "<?php for($expression): ?>",
        'view' => fn ($expression) => "<?php echo view($expression) ?>",
        'else' => fn () => '<?php else: ?>',
        'endif' => fn () => '<?php endif; ?>',
        'endisset' => fn () => '<?php endif; ?>',
        'endforeach' => fn () => '<?php endforeach; ?>',
        'endfor' => fn () => '<?php endfor; ?>',
        'layout' => fn ($path) => sprintf('<?php $this->setLayoutViewPath(%s) ?>', $path),
        'block' => fn (string $name) => sprintf('<?php $this->startBlockContentCapture(%s) ?>', $name),
        'endblock' => fn () => '<?php $this->endBlockContentCapture() ?>',
        'blockslot' => fn ($name) => sprintf('<?= $this->getBlockContent(%s) ?>', $name),
        'slot' => fn () => '<?= $this->getSlotContent() ?>',
    ],
];
