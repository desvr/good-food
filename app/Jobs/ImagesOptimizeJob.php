<?php

namespace App\Jobs;

use App\Contracts\Shop\ImagesOptimize\ImagesOptimizeContract;
use App\Services\Shop\ImagesOptimize\TinypngService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ImagesOptimizeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public int $retryAfter = 30;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private string $file_path,
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var TinypngService $imagesOptimizeService */
        $imagesOptimizeService = app(ImagesOptimizeContract::class);

        $file = $imagesOptimizeService->getImageFromFile($this->file_path);
        $imagesOptimizeService->saveImageToPath($file, $this->file_path);

        Cache::tags(['products', 'categories'])->flush();
    }
}
