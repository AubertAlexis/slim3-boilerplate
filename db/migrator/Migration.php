<?php

require __DIR__.'/../../vendor/autoload.php';

namespace Migrator;

use Illuminate\Database\Capsule\Manager as Capsule;
use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration {
    /** @var \Illuminate\Database\Capsule\Manager $capsule */
    public $capsule;
    /** @var \Illuminate\Database\Schema\Builder $capsule */
    public $schema;

    private $appSettings;

    public function init()  {
        /* Load App Settings */
        $this->appSettings = include __DIR__.'/../../src/config/settings.php';

        /* Start Eloquent */
        $this->capsule = new Capsule();
        $this->capsule->addConnection($this->appSettings['settings']['db']);
        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();

        /* Save Schema Object */
        $this->schema = $this->capsule->schema();
    }
}
