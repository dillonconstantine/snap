<?php

declare(strict_types=1);

namespace DillonConstantine\Snap\Services;

use DillonConstantine\Snap\Games\Variant;
use DillonConstantine\Snap\Players\Player;

class ConfigurationService
{
    /**
     * @param DisplayService $displayService
     */
    public function __construct(
        private readonly DisplayService $displayService,
    ) {}

    /**
     * @return int
     */
    public function getVariant(): int
    {
        $variant = null;

        $iteration = 0;
        while (!is_numeric($variant) || $variant < 1 || $variant > 2) {
            if ($iteration !== 0) {
                $this->displayService->message('Please provide a valid variant.', DisplayService::COLOR_RED);
            }

            $this->displayService->message('Which variant do you want to play?');

            foreach (Variant::cases() as $variant) {
                $this->displayService->message(sprintf('%d) %s', $variant->value, $variant->getName()));
            }

            $variant = $this->readline('Please select the variant from the options above: ');

            $iteration++;
        }

        return (int)$variant;
    }

    /**
     * @return int
     */
    public function getDeckCount(): int
    {
        $deckCount = null;

        $iteration = 0;
        while (!is_numeric($deckCount) || $deckCount < 1 || $deckCount > 4) {
            if ($iteration !== 0) {
                $this->displayService->message('Please provide a valid number of decks.', DisplayService::COLOR_RED);
            }

            $deckCount = $this->readline('How many decks do you want to use (1-4)? ');

            $iteration++;
        }

        return (int)$deckCount;
    }

    /**
     * @return int
     */
    public function getRoundCount(): int
    {
        $roundCount = null;

        $iteration = 0;
        while (!is_numeric($roundCount) || $roundCount < 1 || $roundCount > 100) {
            if ($iteration !== 0) {
                $this->displayService->message('Please provide a valid number of rounds.', DisplayService::COLOR_RED);
            }

            $roundCount = $this->readline('How many rounds do you want the game to last (1-100)? ');

            $iteration++;
        }

        return (int)$roundCount;
    }

    /**
     * @return int
     */
    public function getPlayerCount(): int
    {
        $playerCount = null;

        $iteration = 0;
        while (!is_numeric($playerCount) || $playerCount < 2 || $playerCount > 4) {
            if ($iteration !== 0) {
                $this->displayService->message('Please provide a valid number of players.', DisplayService::COLOR_RED);
            }

            $playerCount = $this->readline('How many players (2-4)? ');

            $iteration++;
        }

        return (int)$playerCount;
    }

    /**
     * @param int $playerCount
     * @return array|Player[]
     */
    public function getPlayerNames(int $playerCount): array
    {
        $playersNames = [];
        for ($i = 0; $i < $playerCount; $i++) {
            $playerName = $this->readline('Please provide the name of player ' . $i + 1 . ': ');

            $playersNames[] = $playerName;
        }

        return $playersNames;
    }

    /**
     * @param string $prompt
     * @return string|false
     */
    protected function readline(string $prompt): bool|string
    {
        return readline($prompt);
    }
}
