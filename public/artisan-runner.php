<?php

/**
 * Laravel Artisan Runner v1.0
 * Terminal UI for shared hosting without SSH.
 * Protected by password + session authentication.
 *
 * @version 1.0
 * @author  @neluttu
 */

define('ARTISAN_RUNNER_VERSION', '1.0');

session_start();

// ──────────────────────────────────────────────
//  CHANGE THIS PASSWORD
// ──────────────────────────────────────────────
define('RUNNER_PASSWORD', '@Parola123');
// ──────────────────────────────────────────────

$authenticated = isset($_SESSION['artisan_runner_auth']) && $_SESSION['artisan_runner_auth'] === true;
$loginError = false;

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = [];
    session_destroy();
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Handle login POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && !isset($_POST['_ajax'])) {
    if (hash_equals(RUNNER_PASSWORD, $_POST['password'])) {
        $_SESSION['artisan_runner_auth'] = true;
        $_SESSION['artisan_runner_ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['artisan_runner_time'] = time();
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }
    $loginError = true;
}

// Session timeout (2 hours)
if ($authenticated && isset($_SESSION['artisan_runner_time'])) {
    if (time() - $_SESSION['artisan_runner_time'] > 7200) {
        $_SESSION = [];
        session_destroy();
        session_start();
        $authenticated = false;
    }
}

// ── AJAX command execution ────────────────────
if ($authenticated && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_ajax'])) {
    header('Content-Type: application/json');

    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    // command => extra arguments to pass
    $commands = [
        // Cache & Optimization
        'config:clear'          => [],
        'config:cache'          => [],
        'cache:clear'           => [],
        'cache:prune-stale-tags' => [],
        'view:clear'            => [],
        'view:cache'            => [],
        'event:cache'           => [],
        'event:clear'           => [],
        'event:list'            => [],
        'optimize'              => [],
        'optimize:clear'        => [],
        'clear-compiled'        => [],
        'package:discover'      => [],
        // Database
        'migrate'               => ['--force' => true],
        'migrate:path'          => ['--force' => true],
        'migrate:status'        => [],
        'migrate:install'       => [],
        'migrate:rollback'      => ['--force' => true],
        'migrate:fresh'         => ['--force' => true],
        'migrate:fresh --seed'  => ['--force' => true, '--seed' => true],
        'migrate:refresh'       => ['--force' => true],
        'migrate:reset'         => ['--force' => true],
        'db:seed'               => ['--force' => true],
        'db:seed:class'         => ['--force' => true],
        'db:show'               => [],
        'db:wipe'               => ['--force' => true],
        'schema:dump'           => [],
        // Routing
        'route:list'                 => [],
        'route:list --except-vendor' => ['--except-vendor' => true],
        'route:list --only-vendor'   => ['--only-vendor' => true],
        'route:clear'           => [],
        'route:cache'           => [],
        'channel:list'          => [],
        // Queue
        'queue:restart'         => [],
        'queue:clear'           => ['--force' => true],
        'queue:failed'          => [],
        'queue:flush'           => [],
        'queue:monitor'         => [],
        'queue:pause'           => [],
        'queue:resume'          => [],
        'queue:prune-batches'   => [],
        'queue:prune-failed'    => [],
        'queue:work --once'     => ['--once' => true],
        // Schedule
        'schedule:list'         => [],
        'schedule:run'          => [],
        'schedule:clear-cache'  => [],
        'schedule:interrupt'    => [],
        // Auth
        'auth:clear-resets'     => [],
        // System
        'about'                 => [],
        'env'                   => [],
        'storage:link'          => [],
        'storage:unlink'        => [],
        'down'                  => [],
        'up'                    => [],
        'key:generate'          => ['--force' => true],
        'model:prune'           => [],
        // Publish
        'lang:publish'          => [],
        'stub:publish'          => [],
        'vendor:publish'        => [],
        'config:publish'        => [],
    ];

    $cmd = trim($_POST['cmd'] ?? '');

    if (!array_key_exists($cmd, $commands)) {
        echo json_encode(['ok' => false, 'output' => "Command not allowed: {$cmd}"]);
        exit;
    }

    try {
        // For compound commands like "migrate:fresh --seed", extract the base artisan command
        $artisanCmd = explode(' ', $cmd)[0];
        $args = $commands[$cmd];

        // db:seed:class — run a specific seeder class
        if ($cmd === 'db:seed:class') {
            $seederClass = trim($_POST['class'] ?? '');
            if (!preg_match('/^[a-zA-Z0-9_\\\\]+$/', $seederClass)) {
                echo json_encode(['ok' => false, 'output' => 'Invalid seeder class name.']);
                exit;
            }
            \Illuminate\Support\Facades\Artisan::call('db:seed', [
                '--class' => $seederClass,
                '--force' => true,
            ]);
            $output = trim(\Illuminate\Support\Facades\Artisan::output());
            echo json_encode(['ok' => true, 'output' => $output ?: 'Done.']);
            exit;
        }

        // migrate:path — run a specific migration file
        if ($cmd === 'migrate:path') {
            $migrationFile = trim($_POST['path'] ?? '');
            if (!preg_match('/^[a-zA-Z0-9_\-]+\.php$/', $migrationFile)) {
                echo json_encode(['ok' => false, 'output' => 'Invalid migration filename.']);
                exit;
            }
            $fullPath = base_path('database/migrations/' . $migrationFile);
            if (!file_exists($fullPath)) {
                echo json_encode(['ok' => false, 'output' => "Migration file not found: database/migrations/{$migrationFile}"]);
                exit;
            }
            \Illuminate\Support\Facades\Artisan::call('migrate', [
                '--path' => 'database/migrations/' . $migrationFile,
                '--force' => true,
            ]);
            $output = trim(\Illuminate\Support\Facades\Artisan::output());
            echo json_encode(['ok' => true, 'output' => $output ?: 'Done.']);
            exit;
        }

        // Route list commands: return structured JSON for table rendering
        if ($artisanCmd === 'route:list') {
            $args['--json'] = true;
            \Illuminate\Support\Facades\Artisan::call($artisanCmd, $args);
            $jsonOutput = trim(\Illuminate\Support\Facades\Artisan::output());
            $routes = json_decode($jsonOutput, true);
            if (is_array($routes) && count($routes) > 0) {
                echo json_encode(['ok' => true, 'type' => 'table', 'data' => $routes, 'output' => count($routes) . ' routes']);
            } else {
                echo json_encode(['ok' => true, 'output' => 'No routes found.']);
            }
        } else {
            \Illuminate\Support\Facades\Artisan::call($artisanCmd, $args);
            $output = trim(\Illuminate\Support\Facades\Artisan::output());
            echo json_encode(['ok' => true, 'output' => $output ?: 'Done.']);
        }
    } catch (\Throwable $e) {
        echo json_encode(['ok' => false, 'output' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

// ── Login screen ──────────────────────────────
if (!$authenticated) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Artisan Runner — Login</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            * { font-family: 'JetBrains Mono', monospace; }
            .scanline { background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,0.03) 2px, rgba(0,0,0,0.03) 4px); pointer-events: none; }
            .glow { text-shadow: 0 0 8px rgba(245,48,3,0.4); }
        </style>
    </head>
    <body class="bg-black min-h-screen text-[#f53003] flex items-center justify-center p-4">

        <div class="w-full max-w-md">
            <div class="flex items-center gap-2 bg-zinc-900 border border-zinc-700 rounded-t-lg px-4 py-2.5">
                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                <span class="ml-3 text-zinc-400 text-xs tracking-wide">Laravel Artisan Runner — login</span>
            </div>

            <div class="relative bg-zinc-950 border-x border-b border-zinc-700 rounded-b-lg p-6 md:p-8">
                <div class="absolute inset-0 scanline rounded-b-lg"></div>
                <div class="relative z-10">

                    <pre class="text-[#f53003] glow text-[10px] leading-tight mb-6 hidden md:block">    _         _   _
   / \   _ __| |_(_)___  __ _ _ __
  / _ \ | '__| __| / __|/ _` | '_ \
 / ___ \| |  | |_| \__ \ (_| | | | |
/_/   \_\_|   \__|_|___/\__,_|_| |_|</pre>

                    <div class="mb-6">
                        <p class="text-zinc-500 text-xs mb-1">Connection requires authentication.</p>
                        <p class="text-zinc-400 text-xs">Enter password to continue.</p>
                    </div>

                    <?php if ($loginError): ?>
                        <div class="mb-4 flex items-center gap-2 bg-red-950/30 border border-red-900/50 rounded-md px-3 py-2">
                            <span class="text-red-500 text-sm">✗</span>
                            <span class="text-red-400 text-xs">Access denied — wrong password.</span>
                        </div>
                    <?php endif; ?>

                    <form method="POST" autocomplete="off">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-[#f53003] text-sm">❯</span>
                            <input type="password" name="password" placeholder="password" autofocus required
                                class="flex-1 bg-transparent border-b border-zinc-700 text-[#ff6b4a] text-sm py-1.5 px-1 outline-none focus:border-[#f53003] transition-colors placeholder-zinc-700">
                        </div>
                        <button type="submit"
                            class="w-full bg-[#f53003]/10 border border-[#f53003]/30 text-[#f53003] text-xs py-2.5 rounded-md hover:bg-[#f53003]/20 hover:border-[#f53003]/50 transition-all">
                            authenticate
                        </button>
                    </form>

                    <div class="mt-6 flex items-center gap-2 text-zinc-700 text-xs">
                        <span class="animate-pulse">_</span>
                    </div>
                </div>
            </div>
        </div>

    </body>
    </html>
    <?php
    exit;
}

// ── Authenticated — Bootstrap Laravel ─────────
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$baseUrl = strtok($_SERVER['REQUEST_URI'], '?');
$laravelVersion = app()->version();
$phpVersion = PHP_VERSION;
$envName = app()->environment();

$commands = [
    'cache' => [
        'label' => 'Cache & Optimization',
        'items' => [
            'config:clear'           => 'Remove the configuration cache file',
            'config:cache'           => 'Cache config for faster loading',
            'cache:clear'            => 'Flush the application cache',
            'cache:prune-stale-tags' => 'Prune stale cache tags (Redis only)',
            'view:clear'             => 'Clear all compiled view files',
            'view:cache'             => 'Compile all Blade templates',
            'event:cache'            => 'Cache events and listeners',
            'event:clear'            => 'Clear cached events and listeners',
            'event:list'             => 'List events and listeners',
            'optimize'               => 'Cache everything for production',
            'optimize:clear'         => 'Remove all cached bootstrap files',
            'clear-compiled'         => 'Remove the compiled class file',
            'package:discover'       => 'Rebuild the cached package manifest',
        ],
    ],
    'database' => [
        'label' => 'Database',
        'items' => [
            'migrate'              => 'Run pending migrations',
            'migrate:path'         => 'Run a specific migration file',
            'migrate:status'       => 'Show the status of each migration',
            'migrate:install'      => 'Create the migration repository',
            'migrate:rollback'     => 'Rollback the last batch of migrations',
            'migrate:fresh'        => 'Drop all tables and re-run migrations',
            'migrate:fresh --seed' => 'Drop all tables, re-run migrations & seed',
            'migrate:refresh'      => 'Reset and re-run all migrations',
            'migrate:reset'        => 'Rollback all database migrations',
            'db:seed'              => 'Run the database seeders',
            'db:seed:class'        => 'Run a specific seeder class',
            'db:show'              => 'Display database information',
            'db:wipe'              => 'Drop all tables, views, and types',
            'schema:dump'          => 'Dump the database schema',
        ],
    ],
    'routing' => [
        'label' => 'Routing',
        'items' => [
            'route:list'                 => 'List all registered routes',
            'route:list --except-vendor' => 'List routes (exclude vendor packages)',
            'route:list --only-vendor'   => 'List routes (only vendor packages)',
            'route:clear'                => 'Remove the route cache file',
            'route:cache'                => 'Cache routes for faster registration',
            'channel:list'               => 'List registered broadcast channels',
        ],
    ],
    'queue' => [
        'label' => 'Queue',
        'items' => [
            'queue:restart'       => 'Restart queue workers after current job',
            'queue:clear'         => 'Delete all jobs from the queue',
            'queue:failed'        => 'List all failed queue jobs',
            'queue:flush'         => 'Flush all failed queue jobs',
            'queue:monitor'       => 'Monitor the size of queues',
            'queue:pause'         => 'Pause job processing',
            'queue:resume'        => 'Resume job processing',
            'queue:prune-batches' => 'Prune stale batch entries',
            'queue:prune-failed'  => 'Prune stale failed job entries',
            'queue:work --once'   => 'Process the next job on the queue',
        ],
    ],
    'schedule' => [
        'label' => 'Schedule',
        'items' => [
            'schedule:list'        => 'List all scheduled tasks',
            'schedule:run'         => 'Run the scheduled commands',
            'schedule:clear-cache' => 'Delete cached mutex files',
            'schedule:interrupt'   => 'Interrupt the current schedule run',
        ],
    ],
    'system' => [
        'label' => 'System',
        'items' => [
            'about'            => 'Display application information',
            'env'              => 'Display the current environment',
            'storage:link'     => 'Create configured symbolic links',
            'storage:unlink'   => 'Delete existing symbolic links',
            'down'             => 'Put app into maintenance mode',
            'up'               => 'Bring app out of maintenance mode',
            'key:generate'     => 'Set the application key',
            'auth:clear-resets' => 'Flush expired password reset tokens',
            'model:prune'      => 'Prune models no longer needed',
        ],
    ],
    'publish' => [
        'label' => 'Publish',
        'items' => [
            'config:publish'   => 'Publish configuration files',
            'lang:publish'     => 'Publish language files',
            'stub:publish'     => 'Publish stubs for customization',
            'vendor:publish'   => 'Publish vendor package assets',
        ],
    ],
];

$tabKeys = array_keys($commands);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artisan Runner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'JetBrains Mono', monospace; }
        .scanline { background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(0,0,0,0.03) 2px, rgba(0,0,0,0.03) 4px); pointer-events: none; }
        .glow { text-shadow: 0 0 8px rgba(245,48,3,0.4); }
        .cmd-btn { transition: all 0.15s ease; }
        .cmd-btn:hover { background-color: rgba(245,48,3,0.12); }
        #terminal-output::-webkit-scrollbar { width: 6px; }
        #terminal-output::-webkit-scrollbar-track { background: transparent; }
        #terminal-output::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 3px; }
        #terminal-output::-webkit-scrollbar-thumb:hover { background: #52525b; }
    </style>
</head>
<body class="bg-black min-h-screen text-[#f53003] flex flex-col items-center justify-center"
      x-data="artisanRunner()"
      x-init="$nextTick(() => scrollTerminal())">

    <div class="w-[90vw] h-[90vh] flex flex-col">

        <!-- Title Bar -->
        <div class="flex items-center gap-2 bg-zinc-900 border border-zinc-700 rounded-t-lg px-4 py-2.5 shrink-0">
            <span class="w-3 h-3 rounded-full bg-red-500"></span>
            <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
            <span class="w-3 h-3 rounded-full bg-green-500"></span>
            <span class="ml-3 text-zinc-400 text-xs tracking-wide"><a href="artisan-runner.php">Laravel Artisan Runner v<?= ARTISAN_RUNNER_VERSION ?> </a> — <span class="<?= $envName === 'production' ? 'text-[#f53003] animate-pulse font-semibold' : '' ?>"><?= htmlspecialchars($envName) ?></span></span>
            <span class="ml-auto text-zinc-400 text-xs">PHP <?= $phpVersion ?> · Laravel <?= $laravelVersion ?></span>
            <a href="<?= $baseUrl ?>?action=logout"
               class="ml-4 text-zinc-400 hover:text-red-400 text-xs transition-colors">logout</a>
        </div>

        <!-- Main Body -->
        <div class="relative bg-zinc-950 border-x border-b border-zinc-700 rounded-b-lg flex-1 flex flex-col overflow-hidden">
            <div class="absolute inset-0 scanline rounded-b-lg"></div>

            <div class="relative z-10 flex flex-col flex-1 overflow-hidden">

                <!-- Top: Tabs + Commands -->
                <div class="shrink-0 border-b border-zinc-800">

                    <!-- ASCII Art Header -->
                    <div class="px-4 pt-4 pb-2 hidden md:block">
                        <pre class="text-[#f53003] glow text-[9px] leading-tight"> _                              _
| |    __ _ _ __ __ ___   _____| |
| |   / _` | '__/ _` \ \ / / _ \ |
| |__| (_| | | | (_| |\ V /  __/ |
|_____\__,_|_|  \__,_| \_/ \___|_|
    _         _   _                   ____
   / \   _ __| |_(_)___  __ _ _ __   |  _ \ _   _ _ __  _ __   ___ _ __
  / _ \ | '__| __| / __|/ _` | '_ \  | |_) | | | | '_ \| '_ \ / _ \ '__|
 / ___ \| |  | |_| \__ \ (_| | | | | |  _ <| |_| | | | | | | |  __/ |
/_/   \_\_|   \__|_|___/\__,_|_| |_| |_| \_\\__,_|_| |_|_| |_|\___|_|</pre>
                    </div>

                    <!-- Tab Bar -->
                    <div class="flex items-center gap-0 border-b border-zinc-800 px-2 pt-2">
                        <?php foreach ($commands as $key => $cat): ?>
                            <button
                                @click="activeTab = '<?= $key ?>'"
                                :class="activeTab === '<?= $key ?>'
                                    ? 'bg-zinc-950 text-[#f53003] border-t border-x border-zinc-700 -mb-px z-10'
                                    : 'bg-zinc-900/50 text-zinc-500 border-t border-x border-zinc-800/50 hover:text-zinc-300'"
                                class="px-4 py-2 text-xs rounded-t-md transition-colors">
                                <?= $cat['label'] ?>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-4">
                        <?php foreach ($commands as $key => $cat): ?>
                            <div x-show="activeTab === '<?= $key ?>'" x-cloak>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($cat['items'] as $cmd => $desc): ?>
                                        <?php if ($cmd === 'db:seed:class'): ?>
                                            <div class="flex items-center gap-2 w-full mt-1 px-3 py-2 rounded-md border border-zinc-800">
                                                <span class="text-[#f53003] text-xs shrink-0">db:seed --class=</span>
                                                <input type="text" x-model="seederClass" placeholder="BlogCategorySeeder"
                                                    :disabled="running"
                                                    class="flex-1 bg-transparent text-[#ff6b4a] text-xs outline-none placeholder-zinc-700 border-b border-zinc-700 focus:border-[#f53003] transition-colors py-0.5 disabled:opacity-40">
                                                <button @click="runSeederClass()" :disabled="running || !seederClass.trim()"
                                                    class="cmd-btn shrink-0 px-3 py-1 rounded border border-zinc-700 hover:border-[#f53003]/30 text-[#f53003] text-xs disabled:opacity-40 disabled:pointer-events-none">
                                                    run
                                                </button>
                                            </div>
                                        <?php elseif ($cmd === 'migrate:path'): ?>
                                            <div class="flex items-center gap-2 w-full mt-1 px-3 py-2 rounded-md border border-zinc-800">
                                                <span class="text-[#f53003] text-xs shrink-0">migrate --path=</span>
                                                <input type="text" x-model="migratePath" placeholder="2024_01_01_000000_create_example_table.php"
                                                    :disabled="running"
                                                    class="flex-1 bg-transparent text-[#ff6b4a] text-xs outline-none placeholder-zinc-700 border-b border-zinc-700 focus:border-[#f53003] transition-colors py-0.5 disabled:opacity-40">
                                                <button @click="runMigratePath()" :disabled="running || !migratePath.trim()"
                                                    class="cmd-btn shrink-0 px-3 py-1 rounded border border-zinc-700 hover:border-[#f53003]/30 text-[#f53003] text-xs disabled:opacity-40 disabled:pointer-events-none">
                                                    run
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <button
                                                @click="run('<?= $cmd ?>')"
                                                :disabled="running"
                                                title="<?= $desc ?>"
                                                class="cmd-btn inline-flex items-center gap-2 px-3 py-2 rounded-md border border-zinc-800 hover:border-[#f53003]/30 disabled:opacity-40 disabled:pointer-events-none group">
                                                <span class="text-[#f53003] text-xs"><?= $cmd ?></span>
                                                <span class="text-zinc-600 text-[10px] group-hover:text-zinc-400 transition-colors"><?= $desc ?></span>
                                            </button>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Bottom: Terminal (fills remaining space) -->
                <div class="flex-1 flex flex-col p-3 gap-2 min-h-0">

                    <!-- Terminal Output -->
                    <div id="terminal-output"
                         class="flex-1 bg-black/50 border border-zinc-800 rounded-md p-3 overflow-auto scroll-smooth">

                        <!-- Welcome -->
                        <div class="text-zinc-500 text-xs mb-2">
                            Welcome to Artisan Runner. Click a command or type below.
                            Type <span class="text-[#f53003]">help</span> for commands, <span class="text-[#f53003]">clear</span> to reset.
                        </div>

                        <!-- History -->
                        <template x-for="(entry, i) in history" :key="i">
                            <div class="mb-3">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="text-[#8a1c00] text-xs">$</span>
                                    <span class="text-[#ff6b4a] text-xs" x-text="'php artisan ' + entry.cmd"></span>
                                    <span x-show="entry.type === 'table'" class="text-zinc-600 text-[10px]" x-text="'(' + entry.output + ')'"></span>
                                </div>

                                <!-- Table output (route:list etc.) -->
                                <template x-if="entry.type === 'table'">
                                    <div class="overflow-x-auto ml-3.5">
                                        <table class="w-full text-xs border-collapse">
                                            <thead>
                                                <tr class="border-b border-zinc-700">
                                                    <template x-for="col in Object.keys(entry.data[0])" :key="col">
                                                        <th class="text-left text-yellow-500/70 font-medium px-2 py-1 text-[10px] uppercase tracking-wider" x-text="col"></th>
                                                    </template>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="(row, ri) in entry.data" :key="ri">
                                                    <tr class="border-b border-zinc-800/50 hover:bg-zinc-800/20">
                                                        <template x-for="col in Object.keys(entry.data[0])" :key="col">
                                                            <td class="px-2 py-0.5 text-zinc-400 truncate max-w-[300px]" x-text="row[col] ?? ''"></td>
                                                        </template>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </template>

                                <!-- Text output as full-width rows -->
                                <template x-if="entry.type !== 'table'">
                                    <div class="ml-3.5">
                                        <template x-for="(line, j) in entry.output.split('\n')" :key="j">
                                            <div class="w-full px-2 py-0.5 border-b border-zinc-800/30 hover:bg-zinc-800/20 whitespace-pre-wrap break-words"
                                                 :class="entry.ok ? 'text-zinc-400' : 'text-red-400'"
                                                 x-text="line || '\u00A0'"></div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- Running indicator -->
                        <div x-show="running" class="flex items-center gap-1.5">
                            <span class="text-[#8a1c00] text-xs">$</span>
                            <span class="text-[#ff6b4a] text-xs" x-text="'php artisan ' + currentCmd"></span>
                            <span class="text-yellow-500 text-xs animate-pulse ml-1">running...</span>
                        </div>
                    </div>

                    <!-- Input Line -->
                    <form @submit.prevent="runInput()" class="flex items-center gap-2 bg-black/50 border border-zinc-800 rounded-md px-3 py-2 shrink-0">
                        <span class="text-[#f53003] text-sm">❯</span>
                        <span class="text-zinc-500 text-xs">php artisan</span>
                        <input
                            type="text"
                            x-model="input"
                            x-ref="cmdInput"
                            :disabled="running"
                            @keydown.up.prevent="historyUp()"
                            @keydown.down.prevent="historyDown()"
                            placeholder="command..."
                            autocomplete="off"
                            class="flex-1 bg-transparent text-[#ff6b4a] text-xs outline-none placeholder-zinc-700 disabled:opacity-40">
                        <button type="submit" :disabled="running || !input.trim()"
                                class="text-zinc-500 hover:text-[#f53003] text-xs transition-colors disabled:opacity-30">
                            run
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        function artisanRunner() {
            return {
                activeTab: '<?= $tabKeys[0] ?>',
                history: [],
                cmdHistory: [],
                cmdHistoryIndex: -1,
                input: '',
                migratePath: '',
                seederClass: '',
                running: false,
                currentCmd: '',

                allowedCommands: <?= json_encode(array_merge(
                    ...array_map(fn($cat) => array_keys($cat['items']), array_values($commands))
                )) ?>,

                helpText: <?= json_encode(implode("\n", array_map(function($cat) {
                    $lines = ["\n  " . $cat['label'] . ":"];
                    foreach ($cat['items'] as $cmd => $desc) {
                        $lines[] = "    " . str_pad($cmd, 24) . $desc;
                    }
                    return implode("\n", $lines);
                }, array_values($commands)))) ?>,

                destructiveCommands: {
                    // Database
                    'migrate': 'This will run pending database migrations!',
                    'migrate:rollback': 'This will rollback the last batch of migrations!',
                    'migrate:fresh': 'This will DROP ALL TABLES and re-run migrations!',
                    'migrate:fresh --seed': 'This will DROP ALL TABLES, re-run migrations and seed!',
                    'migrate:refresh': 'This will RESET and re-run ALL migrations!',
                    'migrate:reset': 'This will ROLLBACK ALL database migrations!',
                    'db:seed': 'This will run all database seeders!',
                    'db:wipe': 'This will DROP ALL tables, views, and types!',
                    'schema:dump': 'This will dump the database schema!',
                    // Cache
                    'cache:clear': 'This will flush the entire application cache!',
                    'optimize:clear': 'This will remove all cached bootstrap files!',
                    // Queue
                    'queue:clear': 'This will DELETE ALL jobs from the queue!',
                    'queue:flush': 'This will flush all failed queue jobs!',
                    'queue:restart': 'This will restart all queue worker daemons!',
                    'queue:pause': 'This will pause job processing!',
                    // Schedule
                    'schedule:run': 'This will run all scheduled commands now!',
                    'schedule:interrupt': 'This will interrupt the current schedule run!',
                    // System
                    'down': 'This will put the application into maintenance mode!',
                    'key:generate': 'This will regenerate the app key! Encrypted data may become unreadable!',
                    'model:prune': 'This will permanently delete prunable models!',
                    'storage:unlink': 'This will delete existing symbolic links!',
                },

                async run(cmd) {
                    if (this.running || !cmd.trim()) return;

                    cmd = cmd.trim();

                    if (cmd === 'clear') {
                        this.history = [];
                        this.input = '';
                        return;
                    }

                    if (cmd === 'help') {
                        this.history.push({ cmd: 'help', ok: true, output: 'Available commands:\n' + this.helpText });
                        this.addToCmdHistory(cmd);
                        this.input = '';
                        this.$nextTick(() => this.scrollTerminal());
                        return;
                    }

                    if (!this.allowedCommands.includes(cmd)) {
                        this.history.push({ cmd, ok: false, output: `Command not allowed: ${cmd}\nType "help" to see available commands.` });
                        this.addToCmdHistory(cmd);
                        this.input = '';
                        this.$nextTick(() => this.scrollTerminal());
                        return;
                    }

                    // Confirm destructive commands
                    if (this.destructiveCommands[cmd]) {
                        if (!confirm(`⚠️ ${this.destructiveCommands[cmd]}\n\nAre you sure you want to run "${cmd}"?`)) {
                            this.history.push({ cmd, ok: false, output: 'Command cancelled by user.' });
                            this.addToCmdHistory(cmd);
                            this.input = '';
                            this.$nextTick(() => this.scrollTerminal());
                            return;
                        }
                    }

                    this.running = true;
                    this.currentCmd = cmd;
                    this.addToCmdHistory(cmd);
                    this.input = '';
                    this.$nextTick(() => this.scrollTerminal());

                    try {
                        const form = new FormData();
                        form.append('_ajax', '1');
                        form.append('cmd', cmd);

                        const res = await fetch(window.location.pathname, { method: 'POST', body: form });
                        const data = await res.json();

                        this.history.push({
                            cmd,
                            ok: data.ok,
                            output: data.output,
                            type: data.type || 'text',
                            data: data.data || null
                        });
                    } catch (e) {
                        this.history.push({ cmd, ok: false, output: 'Network error: ' + e.message });
                    }

                    this.running = false;
                    this.$nextTick(() => {
                        this.scrollTerminal();
                        this.$refs.cmdInput?.focus();
                    });
                },

                runInput() {
                    this.run(this.input);
                },

                async runSeederClass() {
                    const cls = this.seederClass.trim();
                    if (this.running || !cls) return;

                    const displayCmd = 'db:seed --class=' + cls;

                    if (!confirm(`⚠️ This will run the ${cls} seeder!\n\nAre you sure you want to run "${displayCmd}"?`)) {
                        this.history.push({ cmd: displayCmd, ok: false, output: 'Command cancelled by user.' });
                        this.$nextTick(() => this.scrollTerminal());
                        return;
                    }

                    this.running = true;
                    this.currentCmd = displayCmd;
                    this.addToCmdHistory(displayCmd);
                    this.$nextTick(() => this.scrollTerminal());

                    try {
                        const form = new FormData();
                        form.append('_ajax', '1');
                        form.append('cmd', 'db:seed:class');
                        form.append('class', cls);

                        const res = await fetch(window.location.pathname, { method: 'POST', body: form });
                        const data = await res.json();

                        this.history.push({ cmd: displayCmd, ok: data.ok, output: data.output, type: 'text' });
                    } catch (e) {
                        this.history.push({ cmd: displayCmd, ok: false, output: 'Network error: ' + e.message });
                    }

                    this.running = false;
                    this.seederClass = '';
                    this.$nextTick(() => {
                        this.scrollTerminal();
                        this.$refs.cmdInput?.focus();
                    });
                },

                async runMigratePath() {
                    const file = this.migratePath.trim();
                    if (this.running || !file) return;

                    const displayCmd = 'migrate --path=database/migrations/' + file;

                    if (!confirm(`⚠️ This will run a specific migration!\n\nAre you sure you want to run "${displayCmd}"?`)) {
                        this.history.push({ cmd: displayCmd, ok: false, output: 'Command cancelled by user.' });
                        this.$nextTick(() => this.scrollTerminal());
                        return;
                    }

                    this.running = true;
                    this.currentCmd = displayCmd;
                    this.addToCmdHistory(displayCmd);
                    this.$nextTick(() => this.scrollTerminal());

                    try {
                        const form = new FormData();
                        form.append('_ajax', '1');
                        form.append('cmd', 'migrate:path');
                        form.append('path', file);

                        const res = await fetch(window.location.pathname, { method: 'POST', body: form });
                        const data = await res.json();

                        this.history.push({ cmd: displayCmd, ok: data.ok, output: data.output, type: 'text' });
                    } catch (e) {
                        this.history.push({ cmd: displayCmd, ok: false, output: 'Network error: ' + e.message });
                    }

                    this.running = false;
                    this.migratePath = '';
                    this.$nextTick(() => {
                        this.scrollTerminal();
                        this.$refs.cmdInput?.focus();
                    });
                },

                addToCmdHistory(cmd) {
                    if (this.cmdHistory[this.cmdHistory.length - 1] !== cmd) {
                        this.cmdHistory.push(cmd);
                    }
                    this.cmdHistoryIndex = -1;
                },

                historyUp() {
                    if (this.cmdHistory.length === 0) return;
                    if (this.cmdHistoryIndex === -1) {
                        this.cmdHistoryIndex = this.cmdHistory.length - 1;
                    } else if (this.cmdHistoryIndex > 0) {
                        this.cmdHistoryIndex--;
                    }
                    this.input = this.cmdHistory[this.cmdHistoryIndex];
                },

                historyDown() {
                    if (this.cmdHistoryIndex === -1) return;
                    if (this.cmdHistoryIndex < this.cmdHistory.length - 1) {
                        this.cmdHistoryIndex++;
                        this.input = this.cmdHistory[this.cmdHistoryIndex];
                    } else {
                        this.cmdHistoryIndex = -1;
                        this.input = '';
                    }
                },

                scrollTerminal() {
                    const el = document.getElementById('terminal-output');
                    if (el) el.scrollTop = el.scrollHeight;
                },
            };
        }
    </script>
    <div class="flex items-center justify-center gap-4 py-3">
        <a href="https://x.com/neluttu" target="_blank" class="text-zinc-600 hover:text-[#f53003] transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
        <a href="https://github.com/neluttu" target="_blank" class="text-zinc-600 hover:text-[#f53003] transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
        </a>
        <a href="https://linkedin.com/in/ionel-olariu" target="_blank" class="text-zinc-600 hover:text-[#f53003] transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
        </a>
        <span class="text-zinc-700 text-[10px]">&copy; @neluttu</span>
    </div>
</body>
</html>
