<?php

namespace FacturaScripts\Plugins\LoginScreen;

use FacturaScripts\Core\Html;
use FacturaScripts\Core\Template\InitClass;
use FacturaScripts\Plugins\LoginScreen\Lib\Html\CssGradientTwig;

class Init extends InitClass
{

    /**
     * @inheritDoc
     */
    public function init(): void
    {
        $this->loadExtension(new Extension\Controller\EditSettings());

        foreach (CssGradientTwig::getFunctions() as $fn) {
            Html::addFunction($fn);
        }
    }

    /**
     * @inheritDoc
     */
    public function uninstall(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function update(): void
    {
    }
}
