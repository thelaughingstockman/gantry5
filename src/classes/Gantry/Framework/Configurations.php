<?php

/**
 * @package   Gantry5
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2015 RocketTheme, LLC
 * @license   Dual License: MIT or GNU/GPLv2 and later
 *
 * http://opensource.org/licenses/MIT
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Gantry Framework code that extends GPL code is considered GNU/GPLv2 and later
 */

namespace Gantry\Framework;

use Gantry\Component\Config\ConfigFileFinder;
use Gantry\Component\Configuration\AbstractConfigurationCollection;
use RocketTheme\Toolbox\ResourceLocator\UniformResourceIterator;
use RocketTheme\Toolbox\ResourceLocator\UniformResourceLocator;

class Configurations extends AbstractConfigurationCollection
{
    /**
     * @param string $path
     * @return $this
     */
    public function load($path = 'gantry-config://')
    {
        /** @var UniformResourceLocator $locator */
        $locator = $this->container['locator'];

        /** @var UniformResourceIterator $iterator */
        $iterator = $locator->getIterator(
            $path,
            UniformResourceIterator::CURRENT_AS_SELF | UniformResourceIterator::KEY_AS_FILENAME |
            UniformResourceIterator::UNIX_PATHS | UniformResourceIterator::SKIP_DOTS
        );

        $files = [];
        /** @var UniformResourceIterator $info */
        foreach ($iterator as $name => $info) {
            if (!$info->isDir() || $name[0] == '.') {
                continue;
            }
            $files[$name] = ucwords(trim(preg_replace(['|_|', '|/|'], [' ', ' / '], $name)));
        }

        if (!isset($files['default'])) {
            throw new \RuntimeException('Fatal error: Theme does not have default layout');
        }

        unset($files['default']);
        unset($files['menu']);

        asort($files);

        $this->items = ['default' => 'Base Configuration'] + $files;

        return $this;
    }
}
