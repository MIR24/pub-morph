<?php
namespace MIR24\Morph;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

use App\Incut;

class MorphServiceProvider extends ServiceProvider
{
    private $configName = 'pub-morph';
    private $configExtension = '.php';

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if ($this->app instanceof LumenApplication) {
            $this->app->configure($this->configName);
        } else {
            $this->publishes([
                $this->getConfigPath() => config_path($this->getConfigFullName()),
            ]);
        }
        $this->mergeConfigFrom(
           $this->getConfigPath(), $this->configName
        );

        config([
            $this->configName.'.incut.attr' => Incut::$div_attr,
        ]);
    }

    public function register(){}
        
    private function getConfigPath() {
        return __DIR__ . '/../config/' . $this->getConfigFullName();
    }
    
    private function getConfigFullName () {
        return $this->configName.$this->configExtension;
    }

}
