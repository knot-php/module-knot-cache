<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace KnotPhp\Module\KnotCache;

use Throwable;

use KnotLib\Cache\ArrayCache;
use KnotLib\Cache\Config\FileCacheConfig;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\ComponentTypes;
use KnotLib\Kernel\EventStream\Channels;
use KnotLib\Kernel\EventStream\Events;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Kernel\Module\AbstractModule;

use KnotPhp\Module\KnotCache\Adapter\KnotCacheAdapter;

class KnotFileCacheModule extends AbstractModule
{
    /**
     * Declare dependent on components
     *
     * @return array
     */
    public static function requiredComponents() : array
    {
        return [
            ComponentTypes::EVENTSTREAM,
        ];
    }

    /**
     * Declare component type of this module
     *
     * @return string
     */
    public static function declareComponentType() : string
    {
        return ComponentTypes::CACHE;
    }

    /**
     * Install module
     *
     * @param ApplicationInterface $app
     *
     * @throws ModuleInstallationException
     */
    public function install(ApplicationInterface $app)
    {
        try{
            $cache = new ArrayCache([
                FileCacheConfig::KEY_CACHE_EXPIRE => 60,  // cache expires 1 minute
            ]);
            $app->cache(new KnotCacheAdapter($cache));

            // fire event
            $app->eventstream()->channel(Channels::SYSTEM)->push(Events::CACHE_ATTACHED, $cache);
        }
        catch(Throwable $e)
        {
            throw new ModuleInstallationException(self::class, $e->getMessage(), 0, $e);
        }
    }
}