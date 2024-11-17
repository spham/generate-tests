<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SplFileInfo;

class GenerateTests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-tests';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates tests based on models';

    /**
     * Set alias
     *
     * @return void
     */
    protected function configure()
    {
        $this->setAliases([
            'g:t',
        ]);

        parent::configure();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = File::allFiles(base_path('app/Models'));

        collect($files)->each(static function (SplFileInfo $file): void {
            $modelName = str($file->getRelativePathname())->remove('.php');

            $path = $modelName
                ->prepend(base_path('tests/Unit/'))
                ->append('Test.php')
                ->toString();

                File::ensureDirectoryExists(dirname($path));

                if (File::exists($path)) {
                    return;
                }

            $content = "<?php" . PHP_EOL . PHP_EOL . "declare(strict_types=1);" . PHP_EOL . PHP_EOL . "it('ensures " . $modelName  ." exists')->todo();";

            File::put($path, $content);
        });
    }
}
