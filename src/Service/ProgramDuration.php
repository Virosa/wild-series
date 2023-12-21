<?php

namespace App\Service;

use App\Entity\Program;

Class ProgramDuration
{
    public function calculate(Program $program): string
    {
        $totalDuration = 0;
        foreach ($program->getSeasons() as $season) {
            foreach ($season->getEpisodes() as $episode) {
                $totalDuration += $episode->getDuration();
            }
        }

        // Conversion en heures et minutes
        $hours = intdiv($totalDuration, 60);
        $minutes = $totalDuration % 60;
        $days = intdiv($hours, 24);
        $hours = $hours % 24;

        $result = '';
        if ($days > 0) {
            $result .= $days . ' jour' . ($days > 1 ? 's ' : ' ');
        }
        if ($hours > 0) {
            $result .= $hours . ' heure' . ($hours > 1 ? 's ' : ' ');
        }
        $result .= $minutes . ' minute' . ($minutes > 1 ? 's' : '');

        return $result;
    }
 }
    
