<?php

return [
    'directives' => [
        'if' => fn ($expression) => "<?php if($expression): ?>",
        'for' => fn ($expression) => "<?php for($expression): ?>",
        'foreach' => fn ($expression) => "<?php foreach($expression): ?>",
        'view' => fn ($expression) => "<?php echo view($expression) ?>",
        'else' => fn () => "<?php else: ?>",
        'endif' => fn () => "<?php endif; ?>",
        'endfor' => fn () => "<?php endfor; ?>",
        'endforeach' => fn () => "<?php endforeach; ?>",
        'layout' => fn ($path) => sprintf('<?php $this->setLayoutViewPath(%s) ?>', $path),
        'block' => fn (string $name) => sprintf('<?php $this->startBlockContentCapture(%s) ?>', $name),
        'endblock' => fn () => '<?php $this->endBlockContentCapture() ?>',
        'blockslot' => fn ($name) => sprintf('<?= $this->getBlockContent(%s) ?>', $name),
        'slot' => fn () => '<?= $this->getSlotContent() ?>',
    ]
];
