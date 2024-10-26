<?php
namespace AbdelrhmanMohamed2\Crudy\Traits;
use Illuminate\Support\Facades\File;

trait GenerateStub
{
    public function generate(
        array $stubKeys,
        array $stubData,
        string $dirPath,
        string $fileName
    )
    {
        $serviceTemplate = file_get_contents($this->stubPath);

        $serviceContent = str_replace(
            $stubKeys,
            $stubData,
            $serviceTemplate
        );

        // Create the directory if it doesn't exist
        if (!File::exists($dirPath)) {
            File::makeDirectory($dirPath, 0755, true);
        }

        $filePath = $dirPath . '/' . $fileName;

        if (File::exists($filePath)) {
            return false;
        }

        File::put($filePath, $serviceContent);

        return true;
    }
}
