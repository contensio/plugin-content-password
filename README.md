# Content Password

Password-protect individual posts and pages. Visitors are shown a password prompt instead of the content until they enter the correct password. Admins always bypass the gate automatically.

**Features:**
- Per-content passwords set from a dedicated admin page
- Passwords stored as bcrypt hashes — never as plain text
- Unlock state stored in the PHP session — visitor only needs to enter once per browser session
- Admins bypass the gate entirely (no password prompt)
- Works on all content types (posts, pages, custom types)
- Settings hub card in Admin > Settings

---

## Requirements

- Contensio 2.0 or later

---

## Installation

### Composer

```bash
composer require contensio/plugin-content-password
```

### Manual

Copy the plugin directory and register the service provider via the admin plugin manager.

No migrations required — passwords are stored in the core `content_meta` table.

---

## Configuration

Go to **Admin > Settings > Content Password**.

The page lists all published posts and pages. For each item you can:

- **Set a password** — type a password (min 4 characters) and click Set
- **Update a password** — type a new password and click Update; the old password is immediately invalidated
- **Remove a password** — click Remove to make the content publicly accessible again

---

## How it works

The plugin registers `ContentPasswordMiddleware` in the `web` middleware group. On every request the middleware:

1. Checks for a `slug` route parameter — exits immediately on non-slug routes
2. Looks up the matching `ContentTranslation` by slug
3. Queries `content_meta` for a `content_password` hash on that content item
4. If no hash exists, the request passes through untouched
5. If the visitor is an authenticated admin (`canAccessAdmin()`), the request passes through
6. If `cp_unlocked_{id}` is set in the session, the request passes through
7. Otherwise, a password prompt page is shown

When the visitor submits the correct password, `Hash::check()` verifies it, the session key is set, and the visitor is redirected back to the content.

---

## Hook reference

| Hook | Description |
|------|-------------|
| `contensio/admin/settings-cards` | Settings hub card linking to the password manager |

---

## Database storage

Passwords are stored in the core `content_meta` table:

| Column | Value |
|--------|-------|
| `content_id` | ID of the protected content item |
| `meta_key` | `content_password` |
| `meta_value` | bcrypt hash of the password |
