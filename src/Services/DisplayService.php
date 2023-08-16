<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Services;

class DisplayService
{
    public const COLOR_RED       = 31;
    public const COLOR_GREEN     = 32;
    public const MESSAGE_DIVIDER = '==================================================';

    /**
     * @param string $message
     * @param int|null $color
     * @return void
     */
    public function message(string $message, ?int $color = null): void
    {
        if ($color !== null) {
            $message = sprintf("\033[%dm%s\033[0m", $color, $message);
        }

        echo $message . PHP_EOL;
    }

    /**
     * @return void
     */
    public function divider(): void
    {
        $this->message(self::MESSAGE_DIVIDER);
    }
}
