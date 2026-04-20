<?php

/**
 * Content Password - Contensio plugin.
 * https://contensio.com
 *
 * @copyright   Copyright (c) 2026 Iosif Gabriel Chimilevschi
 * @license     https://www.gnu.org/licenses/agpl-3.0.txt  AGPL-3.0-or-later
 */

namespace Contensio\Plugins\ContentPassword;

use Contensio\Plugins\ContentPassword\Http\Middleware\ContentPasswordMiddleware;
use Contensio\Support\Hook;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ContentPasswordServiceProvider extends ServiceProvider
{
    protected string $ns = 'contensio-content-password';

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', $this->ns);

        // Gate middleware - runs on all web requests, self-limits to slug routes
        $this->app->make(Router::class)
            ->pushMiddlewareToGroup('web', ContentPasswordMiddleware::class);

        $this->registerRoutes();

        Hook::add('contensio/admin/settings-cards', function (): string {
            return view($this->ns . '::partials.settings-hub-card')->render();
        });
    }

    private function registerRoutes(): void
    {
        if (! $this->app->routesAreCached()) {
            Route::middleware('web')
                ->group(__DIR__ . '/../routes/web.php');
        }
    }
}
