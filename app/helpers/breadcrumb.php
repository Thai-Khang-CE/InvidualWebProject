<?php

function renderBreadcrumb(array $items): void
{
    if (empty($items)) {
        return;
    }

    echo '<nav aria-label="Breadcrumb" class="breadcrumb">';
    echo '<ol class="breadcrumb__list">';

    $lastIndex = count($items) - 1;

    foreach ($items as $index => $item) {
        $label = htmlspecialchars((string) ($item['label'] ?? ''), ENT_QUOTES, 'UTF-8');
        $url = isset($item['url']) ? htmlspecialchars((string) $item['url'], ENT_QUOTES, 'UTF-8') : null;
        $isLast = $index === $lastIndex;

        echo '<li class="breadcrumb__item"' . ($isLast ? ' aria-current="page"' : '') . '>';

        if (!$isLast && $url !== null && $url !== '') {
            echo '<a class="breadcrumb__link" href="' . $url . '">' . $label . '</a>';
        } else {
            echo '<span>' . $label . '</span>';
        }

        echo '</li>';
    }

    echo '</ol>';
    echo '</nav>';
}
