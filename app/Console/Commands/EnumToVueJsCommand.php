<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EnumToVueJsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:enum-to-vue-js';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make available Enums const to vue js';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enumDirectory = app_path('Enum');
        $enumObjects = [];

        $this->info("Searching enum files in directory: $enumDirectory");

        $enumFiles = glob($enumDirectory . '/*.php');

        $this->info("Found " . count($enumFiles) . " enum files.");

        foreach ($enumFiles as $enumFile) {
            $className = basename($enumFile, '.php');
            $enumClass = "App\\Enum\\$className";

            if (class_exists($enumClass)) {
                $this->info("Loading enum class: $enumClass");

//                $reflection = new ReflectionClass($enumClass);
//                $constants = $reflection->getConstants();

                // Create an object for the enum class with its constants
                $enumObjects[$className] = $enumClass::toArray();
            } else {
                $this->info("Enum class not found: $enumClass");
            }
        }

        // Serialize enum objects to JSON
        $json = json_encode($enumObjects, JSON_PRETTY_PRINT);
        foreach ($enumObjects as $className => $enumObject) {
            $this->info("Enum Class: $className");
            $this->line('');
        }

        // Write JSON to Vue.js file
        $vueFile = resource_path('js/plugins/enums.js');
        $content = "
export const Enums = $json
export const EnumPlugin = {
    install(app) {
        app.config.globalProperties.\$enums = Enums ;
    }
};
";
        file_put_contents($vueFile, $content);

        $this->info("Enum objects saved to $vueFile");
    }
}
