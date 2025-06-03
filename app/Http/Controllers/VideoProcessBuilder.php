<?php
declare(strict_types=1);

namespace App\YoutubeDl;

use Symfony\Component\Process\Process;
use YoutubeDl\Process\ProcessBuilderInterface;

class VideoProcessBuilder implements ProcessBuilderInterface
{
    public function build(?string $binPath, ?string $pythonPath, array $arguments = []): Process
    {
        $process = new Process([$binPath, $pythonPath, ...$arguments]);
        // Set custom timeout or customize other things..
        $process->setTimeout(60);

        return $process;
    }
}
