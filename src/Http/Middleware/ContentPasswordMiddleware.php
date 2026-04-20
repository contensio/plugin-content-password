<?php

/**
 * Content Password - Contensio plugin.
 * https://contensio.com
 *
 * @copyright   Copyright (c) 2026 Iosif Gabriel Chimilevschi
 * @license     https://www.gnu.org/licenses/agpl-3.0.txt  AGPL-3.0-or-later
 */

namespace Contensio\Plugins\ContentPassword\Http\Middleware;

use Closure;
use Contensio\Models\ContentMeta;
use Contensio\Models\ContentTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ContentPasswordMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to single post/page routes
        $slug = $request->route('slug');
        if (! $slug) {
            return $next($request);
        }

        $translation = ContentTranslation::where('slug', $slug)->first();
        if (! $translation) {
            return $next($request);
        }

        $hash = ContentMeta::where('content_id', $translation->content_id)
            ->where('meta_key', 'content_password')
            ->value('meta_value');

        if (! $hash) {
            return $next($request);
        }

        $sessionKey = 'cp_unlocked_' . $translation->content_id;

        // Already unlocked in this session
        if (session($sessionKey)) {
            return $next($request);
        }

        // Admins always bypass
        if (auth()->check() && auth()->user()->canAccessAdmin()) {
            return $next($request);
        }

        // POST - verify password
        if ($request->isMethod('POST') && $request->has('content_password')) {
            if (Hash::check($request->input('content_password'), $hash)) {
                session([$sessionKey => true]);
                return redirect()->back();
            }
            return response()->view('contensio-content-password::prompt', [
                'error' => 'Incorrect password. Please try again.',
                'slug'  => $slug,
            ], 401);
        }

        // GET - show password prompt
        return response()->view('contensio-content-password::prompt', ['error' => null, 'slug' => $slug], 200);
    }
}
