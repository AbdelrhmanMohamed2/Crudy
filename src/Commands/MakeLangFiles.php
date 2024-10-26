<?php

namespace AbdelrhmanMohamed2\Crudy\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeLangFiles extends Command
{
    protected $signature = 'crudy:generate-lang {name}';
    protected $description = 'Generate language files for CRUD messages';

    public function handle()
    {
        $model = Str::snake($this->argument('name'));

        // Define the paths
        $enPath = resource_path("lang/en/messages.php");
        $arPath = resource_path("lang/ar/messages.php");

        // Ensure the messages.php file exists, or create it if not
        $this->ensureMessagesFileExists($enPath);
        $this->ensureMessagesFileExists($arPath);

        // Generate the content for each language
        $enContent = $this->generateContent($model, __DIR__ . '/../stubs/lang/en.stub');
        $arContent = $this->generateContent($model, __DIR__ . '/../stubs/lang/ar.stub');

        // Append the content to each messages.php file
        $this->appendToFile($enPath, $enContent, $model);
        $this->appendToFile($arPath, $arContent, $model);

        $this->info("CRUD messages added for model: $model in messages.php (en and ar).");
    }

    private function ensureMessagesFileExists($filePath)
    {
        $directory = dirname($filePath);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (!File::exists($filePath)) {
            File::put($filePath, "<?php\n\nreturn [\n];\n");
        }
    }

    private function generateContent($model, $stubPath)
    {
        $stub = file_get_contents($stubPath);
        $content = str_replace('{{ model_name }}', $model, $stub);

        // Convert the stub content to PHP array format without opening PHP tags
        return substr($content, strpos($content, "return [") + 8, -4) . "\n";
    }

    private function appendToFile($filePath, $content, $model)
    {
        $fileContent = File::get($filePath);

        // Check if model messages already exist to avoid duplicate entries
        if (strpos($fileContent, "'$model' => [") === false) {
            // Insert before the closing bracket (]);) and add a comma for array continuation
            $fileContent = substr($fileContent, 0, -3) . $content . "];";
            File::put($filePath, $fileContent);
        } else {
            $this->warn("Messages for model '$model' already exist in $filePath");
        }
    }
}
