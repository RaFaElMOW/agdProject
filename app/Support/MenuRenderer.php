<?php

namespace App\Support;

use App\Repositories\MenuRepository;

/**
 * Renders the header/footer markup from the `menus` table, keeping the exact same
 * Bootstrap classes the static template used (nav-item, dropdown, dropdown-menu, ...)
 * so switching to DB-driven menus doesn't change a single CSS selector.
 */
class MenuRenderer
{
    public static function headerNav(): string
    {
        $rows = self::loadSafely('header');
        if ($rows === []) {
            return '';
        }

        $current = basename((string) ($_SERVER['PHP_SELF'] ?? ''));
        $children = [];
        foreach ($rows as $row) {
            if ($row['parent_id'] !== null) {
                $children[$row['parent_id']][] = $row;
            }
        }

        $html = '';
        foreach ($rows as $row) {
            if ($row['parent_id'] !== null) {
                continue;
            }

            $kids = $children[$row['id']] ?? [];
            if ($kids === []) {
                $isActive = basename($row['url']) === $current;
                $html .= '<li class="nav-item' . ($isActive ? ' active' : '') . '">'
                    . '<a href="' . e($row['url']) . '" class="nav-link"' . self::targetAttr($row) . '>' . e($row['label']) . '</a></li>';
                continue;
            }

            $anyChildActive = false;
            $childrenHtml = '';
            foreach ($kids as $kid) {
                $kidActive = basename($kid['url']) === $current;
                $anyChildActive = $anyChildActive || $kidActive;
                $childrenHtml .= '<a class="dropdown-item' . ($kidActive ? ' active' : '') . '" href="' . e($kid['url']) . '"' . self::targetAttr($kid) . '>' . e($kid['label']) . '</a>';
            }

            $domId = 'menuDropdown' . $row['id'];
            $html .= '<li class="nav-item dropdown' . ($anyChildActive ? ' active' : '') . '">'
                . '<a class="nav-link dropdown-toggle" href="#" id="' . e($domId) . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . e($row['label']) . '</a>'
                . '<div class="dropdown-menu" aria-labelledby="' . e($domId) . '">' . $childrenHtml . '</div></li>';
        }

        return $html;
    }

    public static function footerLinks(): string
    {
        $rows = self::loadSafely('footer');
        $html = '';
        foreach ($rows as $row) {
            if ($row['parent_id'] !== null) {
                continue;
            }
            $html .= '<li><a href="' . e($row['url']) . '" class="py-2 d-block"' . self::targetAttr($row) . '>' . e($row['label']) . '</a></li>';
        }
        return $html;
    }

    private static function targetAttr(array $row): string
    {
        return !empty($row['target_blank']) ? ' target="_blank" rel="noopener"' : '';
    }

    private static function loadSafely(string $location): array
    {
        try {
            return (new MenuRepository())->forLocation($location);
        } catch (\Throwable) {
            return [];
        }
    }
}
