<?php

declare(strict_types=1);

namespace Casandra\AudioPlayerForElementor\MetaBox;

interface MetaBox
{
    public function registerMetaBox(string $postTypeSlug): void;
    public function renderMetaBox($post): void;
    public function saveMeta(int $postId): void;
}
