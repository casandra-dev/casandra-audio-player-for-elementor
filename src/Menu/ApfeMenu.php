<?php

declare(strict_types=1);

namespace Casandra\AudioPlayerForElementor\Menu;

class ApfeMenu implements Menu
{
    private string $pageTitle;
    private string $menuTitle;
    private string $capability;
    public string $menuSlug;
    private $render;
    private string $icon;
    private int $pos;
    public $parent;
    public array $submenus;
    private bool $isSubmenu;

    public function __construct(
        string $pageTitle,
        string $menuTitle,
        string $capability,
        string $menuSlug,
        $render,
        string $icon,
        int $pos = 6,
        $parent = null
    ) {
        $this->pageTitle = $pageTitle;
        $this->menuTitle = $menuTitle;
        $this->capability = $capability;
        $this->menuSlug = $menuSlug;
        $this->render = $render;
        $this->icon = $icon;
        $this->pos = $pos;
        $this->parent = $parent;
        $this->isSubmenu = !is_null($parent);
    }

    public function add(): void
    {
        if ($this->isSubmenu) {
            add_submenu_page(
                $this->parent->menuSlug,
                $this->pageTitle,
                $this->menuTitle,
                $this->capability,
                $this->menuSlug,
                $this->render,
                $this->pos
            );
            $this->submenus[] = $this;
        } else {
            add_menu_page(
                $this->pageTitle,
                $this->menuTitle,
                $this->capability,
                $this->menuSlug,
                $this->render,
                $this->icon,
                $this->pos
            );
        }
    }
}
