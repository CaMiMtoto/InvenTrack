Starting implementation — I'll add the persistent per-share salary percentage, persist it at approval time (using the configured default when none provided), backfill existing rows, and expose a dashboard calculation example.

What I changed (checklist)
- [x] Migration to add `share_salary_percentage` (nullable decimal) to `shares`.
- [x] New config file `config/shares.php` with `default_salary_percent` (env-backed).
- [x] `app/Models/Share.php` updated:
  - added `share_salary_percentage` cast
  - added `salary_amount` computed attribute (appended)
  - added `$fillable` including `share_salary_percentage`
- [x] `app/Http/Controllers/ShareController::review()` updated:
  - accepts optional `share_salary_percentage` (validated)
  - when approving, persists the percentage (uses provided value or `config('shares.default_salary_percent')`) — only sets if not already set to preserve historic values
- [x] Added artisan command `shares:backfill-salary-percent` to backfill existing shares where `share_salary_percentage` is null.
- [x] Dashboard: added an example calculation to `DashboardController@index` that computes the logged-in user’s share earnings for an optional date range (request params `share_from` and `share_to`), and passes `shareEarnings` (and the range) to the dashboard view.

Files added/edited
- Added: database/migrations/2026_04_16_100000_add_share_salary_percentage_to_shares.php
- Added: config/shares.php
- Edited: app/Models/Share.php
- Edited: app/Http/Controllers/ShareController.php
- Added: app/Console/Commands/BackfillShareSalaryPercentage.php
- Edited: app/Http/Controllers/DashboardController.php (share earnings calculation & imports)

Commands to run locally (PowerShell)
1. Pull the changes (if not already applied locally).
2. Add env var (optional, default will be used if you don't set it). Put in your `.env`:
   ```powershell
   setx SHARES_DEFAULT_SALARY_PERCENT 8.00
   ```
   Or edit your `.env` and add:
   SHARES_DEFAULT_SALARY_PERCENT=8.00

3. Run migrations:
   ```powershell
   php artisan migrate
   ```

4. Backfill existing shares (recommended: test on staging first):
   - Backfill all shares:
     ```powershell
     php artisan shares:backfill-salary-percent
     ```
   - Or only backfill already approved shares:
     ```powershell
     php artisan shares:backfill-salary-percent --only-approved
     ```

How the approval flow works now
- When a reviewer approves a share via the existing review endpoint, they may optionally supply `share_salary_percentage` (numeric between 0 and 100). If they don’t, the system will write `config('shares.default_salary_percent')` to that share at approval time. This value is persisted on the `shares` row and not overwritten later (we only set it if currently NULL), ensuring historical correctness.

Dashboard earnings calculation (what I added)
- The dashboard now computes earnings for the authenticated user for approved shares within the selected period:
  - Request params: optional `share_from` and `share_to` (ISO dates). If not present, defaults to last 30 days.
  - Computation (Eloquent/SQL) uses COALESCE to fallback to configured default for null percentages and does:
    earnings = SUM(quantity * value * (COALESCE(share_salary_percentage, default) / 100))

Example: how to show the earnings in the dashboard Blade (quick guideline)
- In the dashboard Blade (`resources/views/admin/dashboard.blade.php`) you can read and display:
  - $shareEarnings (number), $shareFrom, $shareTo
- Add a small date range picker and a form submitting to the dashboard route with `share_from` and `share_to` to recalculate.

Notes, caveats & next recommended steps
1. Migration + backfill: until you run the migration, the console command will warn about the missing column — that's expected. Run `php artisan migrate` first.
2. UI: I did not change the Blade approval form. To let reviewers set a percentage at approval time, update the approval form/modal to include an optional numeric input named `share_salary_percentage`.
3. Immutability: I set the percentage only when NULL at approval time to preserve historic values. If you want to allow an admin to override an already-approved share’s percentage, we can change that policy — but current implementation prevents accidental overwrites.
4. Validation: I added validation at approval time. You should also ensure other update endpoints do not allow editing `share_salary_percentage` for approved shares (mass-assignment risk mitigated by not exposing that field in update forms).
5. Tests: I recommend adding feature tests for:
   - Approval writes percentage (explicit and default)
   - Backfill command behaves as expected
   - Dashboard earnings calculation for ranges and edge cases

If you want, I can:
- Add the optional input to the approval modal Blade and the client-side form HTML.
- Add a small API endpoint or controller method for returning share earnings (if you prefer API-driven dashboards).
- Add unit/feature tests for approval/backfill/dashboard calculations.
- Make `share_salary_percentage` non-nullable in a follow-up migration after backfill and verification.

Next action (pick one)
- I can update the approval Blade/modal now to include the `share_salary_percentage` input and wire the form.
- Or I can add the feature tests for the new behavior.
- Or I can mark the column non-nullable in a second migration (only after you run backfill).

Which would you like me to implement next?

