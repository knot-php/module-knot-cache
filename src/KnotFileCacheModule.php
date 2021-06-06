<?php /** @noinspection PhpUnused */
declare(strict_types=1);

namespace knotphp\module\knotcache;

use Throwable;

use knotlib\cache\ArrayCache;
use knotlib\cache\Config\FileCacheConfig;
use knotlib\kernel\kernel\ApplicationInterface;
use knotlib\kernel\module\ComponentTypes;
use knotlib\kernel\eventstream\Channels;
use knotlib\kernel\eventstream\Events;
use knotlib\kernel\exception\ModuleInstallationException;
use knotlib\kernel\module\ModuleInterface;

use knotphp\module\knotcache\adapter\KnotCacheAdapter;

class KnotFileCacheModule implements ModuleInterface
{
    /**
     * Declare dependency on another modules
     *
     * @return array
     */
    public static function requiredModules() : array
    {
        return [];
    }

    /**
     * Declare dependent on components
     *
     * @return array
     */
    public static function requiredComponentTypes() : array
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