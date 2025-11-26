<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function (): void {
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn ($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        service('toolbar')->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            service('routes')->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }
});

// minify html output on codeigniter 4 in production environment
Events::on('post_controller_constructor', static function () {
    if (ENVIRONMENT !== 'testing' && ! url_is('assist/*') && filter_var(setting('Smartyurl.minifyHtmloutput') ?? false, FILTER_VALIDATE_BOOLEAN)) {
        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static function ($buffer) {
            $search = [
                '/\n/',      // replace end of line by a <del>space</del> nothing , if you want space make it down ' ' instead of ''
                '/\>[^\S ]+/s',    // strip whitespaces after tags, except space
                '/[^\S ]+\</s',    // strip whitespaces before tags, except space
                '/(\s)+/s',    // shorten multiple whitespace sequences
                '/<!--(.|\s)*?-->/', // remove HTML comments
            ];

            $replace = [
                '',
                '>',
                '<',
                '\\1',
                '',
            ];

            return preg_replace($search, $replace, $buffer);
        });
    }
});
