<?php

/*
 * This file is part of OAuth 2.0 Laravel.
 *
 * (c) Luca Degasperi <packages@lucadegasperi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YanguangLan\OAuth2\Storage\Redis;

use Illuminate\Contracts\Container\Container as Application;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Server\Storage\AccessTokenInterface;
use League\OAuth2\Server\Storage\AuthCodeInterface;
use League\OAuth2\Server\Storage\ClientInterface;
use League\OAuth2\Server\Storage\RefreshTokenInterface;
use League\OAuth2\Server\Storage\ScopeInterface;
use League\OAuth2\Server\Storage\SessionInterface;

/**
 * This is the fluent storage service provider class.
 *
 * @author Luca Degasperi <packages@lucadegasperi.com>
 */
class OAuth2RedisStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerStorageBindings($this->app);
        $this->registerInterfaceBindings($this->app);
    }

    /**
     * Bind the storage implementations to the IoC container.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function registerStorageBindings(Application $app)
    {
        $provider = $this;

        $app->singleton(RedisAccessToken::class, function () use ($provider) {
            $storage = new RedisAccessToken($provider->app['redis']);

            return $storage;
        });

        $app->singleton(RedisAuthCode::class, function () use ($provider) {
            $storage = new RedisAuthCode($provider->app['redis']);

            return $storage;
        });

        $app->singleton(RedisClient::class, function ($app) use ($provider) {
            $storage = new RedisClient($provider->app['redis']);

            return $storage;
        });

        $app->singleton(RedisRefreshToken::class, function () use ($provider) {
            $storage = new RedisRefreshToken($provider->app['redis']);

            return $storage;
        });

        $app->singleton(RedisScope::class, function ($app) use ($provider) {
            $storage = new RedisScope($provider->app['redis']);

            return $storage;
        });

        $app->singleton(RedisSession::class, function () use ($provider) {
            $storage = new RedisSession($provider->app['redis']);

            return $storage;
        });
    }

    /**
     * Bind the interfaces to their implementations.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function registerInterfaceBindings(Application $app)
    {
        $app->bind(ClientInterface::class, RedisClient::class);
        $app->bind(ScopeInterface::class, RedisScope::class);
        $app->bind(SessionInterface::class, RedisSession::class);
        $app->bind(AuthCodeInterface::class, RedisAuthCode::class);
        $app->bind(AccessTokenInterface::class, RedisAccessToken::class);
        $app->bind(RefreshTokenInterface::class, RedisRefreshToken::class);
    }
}
