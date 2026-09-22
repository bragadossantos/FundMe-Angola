<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/**
 * Serverless hosts like Vercel have no shell to run `php artisan migrate`
 * after a deploy (unlike the Docker/Render entrypoint, which does this
 * automatically on boot). This gives a token-protected HTTP equivalent.
 *
 * Set DEPLOY_MIGRATE_TOKEN in the hosting platform's environment variables
 * (never commit it) and call this route with that token after each deploy
 * that changes the schema. Leaving DEPLOY_MIGRATE_TOKEN unset disables the
 * route entirely.
 */
class DeploymentController extends Controller
{
    public function migrate(Request $request)
    {
        $this->authorize($request);

        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();

        if ($request->boolean('seed')) {
            Artisan::call('db:seed', ['--force' => true]);
            $output .= "\n" . Artisan::output();
        }

        return response($output)->header('Content-Type', 'text/plain');
    }

    protected function authorize(Request $request): void
    {
        $expected = (string) config('app.deploy_token');
        $provided = (string) ($request->bearerToken() ?? $request->query('token'));

        if ($expected === '' || $provided === '' || !hash_equals($expected, $provided)) {
            abort(403, 'Token de implementação inválido, em falta, ou não configurado (DEPLOY_MIGRATE_TOKEN).');
        }
    }
}
