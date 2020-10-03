<?php
/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */


namespace Commune\DingTalk\EasyDingTalk\Hyperfy;

use Commune\Blueprint\Framework\ReqContainer;
use Commune\Contracts\Cache;
use Commune\DingTalk\EasyDingTalk\Hyperfy\Providers\RequestServiceProvider;
use Commune\Framework\Spy\SpyAgency;
use EasyDingTalk;
use EasyDingTalk\Application;
use EasyDingTalk\Kernel;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\CoroutineHandler;
use Psr\Log\LoggerInterface;


/**
 * 考虑到在 Hyperf 和 Commune 中使用,
 * EasyDingTalk 应该是一个请求级应用. 毕竟底层实现不是基于 Swoole 的.
 * 因此有许多非协程客户端的功能需要修改.
 *
 * 1. 添加 hyperf 协程客户端 Client
 * 2. 注册 Cache
 * 3. 注册 Logger
 * 4. 注册 Server
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class EasyDingTalkApp extends Application
{

    /**
     * @var array
     */
    protected $providers = [
        EasyDingTalk\Auth\ServiceProvider::class,
        EasyDingTalk\Chat\ServiceProvider::class,
        EasyDingTalk\Role\ServiceProvider::class,
        EasyDingTalk\User\ServiceProvider::class,
        EasyDingTalk\Media\ServiceProvider::class,
        EasyDingTalk\H5app\ServiceProvider::class,
        EasyDingTalk\Health\ServiceProvider::class,
        EasyDingTalk\Report\ServiceProvider::class,
        EasyDingTalk\Checkin\ServiceProvider::class,
        EasyDingTalk\Contact\ServiceProvider::class,
        EasyDingTalk\Process\ServiceProvider::class,
        EasyDingTalk\Calendar\ServiceProvider::class,
        EasyDingTalk\Callback\ServiceProvider::class,
        EasyDingTalk\Microapp\ServiceProvider::class,
        EasyDingTalk\Schedule\ServiceProvider::class,
        EasyDingTalk\Blackboard\ServiceProvider::class,
        EasyDingTalk\Attendance\ServiceProvider::class,
        EasyDingTalk\Department\ServiceProvider::class,
        EasyDingTalk\Conversation\ServiceProvider::class,


        // 替换 client
        // Kernel\Providers\ClientServiceProvider::class,
        Providers\ClientServiceProvider::class,

        // 替换 logger
        // Kernel\Providers\LoggerServiceProvider::class,
        Providers\LoggerServiceProvider::class,


        Kernel\Providers\ServerServiceProvider::class,

        // 替换 request
        // Kernel\Providers\RequestServiceProvider::class,
        RequestServiceProvider::class,

        Kernel\Providers\EncryptionServiceProvider::class,
        Kernel\Providers\AccessTokenServiceProvider::class,
    ];

    public function __construct(array $config = [], array $values = [])
    {
        parent::__construct($config, $values);

        // 怀疑 pimple 是否能正确清除单例, 尝试一下检查.
        SpyAgency::incr(static::class);
    }


    public static function createByCommune(ReqContainer $container)
    {

        $cache = $container->make(Cache::class);

        $cache = function() use ($container) {
            /**
             * @var Cache $cache
             */
            $cache = $container->make(Cache::class);
            return $cache->getPSR16Cache();
        };

        $logger = function() use ($container) {
           return $container->make(LoggerInterface::class);
        };
        $client = function() use ($container) {
            return new Client([
                'handler' => HandlerStack::create(new CoroutineHandler()),
            ]);
        };

        return new static(
            $config = [],
            [
                'logger' => $logger,
                'cache' => $cache->getPSR16Cache(),
                'client' => $client,
            ]
        );
    }

    public function __destruct()
    {
        SpyAgency::decr(static::class);
    }

}