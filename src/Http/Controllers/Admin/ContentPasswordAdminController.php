<?php

/**
 * Content Password — Contensio plugin.
 * https://contensio.com
 *
 * @copyright   Copyright (c) 2026 Iosif Gabriel Chimilevschi
 * @license     https://www.gnu.org/licenses/agpl-3.0.txt  AGPL-3.0-or-later
 */

namespace Contensio\Plugins\ContentPassword\Http\Controllers\Admin;

use Contensio\Models\Content;
use Contensio\Models\ContentMeta;
use Contensio\Models\ContentTranslation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class ContentPasswordAdminController extends Controller
{
    private const META_KEY = 'content_password';

    /**
     * List all published posts and pages with their password status.
     */
    public function index()
    {
        $items = Content::with(['defaultTranslation', 'contentType'])
            ->where('status', Content::STATUS_PUBLISHED)
            ->orderBy('published_at', 'desc')
            ->get()
            ->map(function (Content $content) {
                $hasPassword = ContentMeta::where('content_id', $content->id)
                    ->where('meta_key', self::META_KEY)
                    ->exists();

                return [
                    'id'          => $content->id,
                    'title'       => $content->defaultTranslation?->title ?? '(Untitled)',
                    'type'        => $content->contentType?->name ?? 'Content',
                    'published'   => $content->published_at,
                    'has_password'=> $hasPassword,
                ];
            });

        return view('content-password::admin.index', compact('items'));
    }

    /**
     * Set or update the password for a content item.
     */
    public function set(Request $request, int $contentId)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:4', 'max:255'],
        ]);

        ContentMeta::updateOrCreate(
            ['content_id' => $contentId, 'meta_key' => self::META_KEY],
            ['meta_value' => Hash::make($request->input('password'))]
        );

        return back()->with('success', 'Password set.');
    }

    /**
     * Remove the password from a content item.
     */
    public function remove(int $contentId)
    {
        ContentMeta::where('content_id', $contentId)
            ->where('meta_key', self::META_KEY)
            ->delete();

        return back()->with('success', 'Password removed.');
    }
}
