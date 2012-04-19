#!/usr/bin/env php
<?php

error_reporting(error_reporting() ^ E_DEPRECATED);
if (version_compare(PHP_VERSION, '5.3.2') >= 0) {
    error_reporting(error_reporting() ^ E_DEPRECATED);
}
date_default_timezone_set('America/Chicago');

require_once 'PEAR/PackageFileManager2.php';
PEAR::setErrorHandling(PEAR_ERROR_DIE);

/**
 * Recursively populated $GLOBALS['files']
 *
 * @param string $path The path to glob through.
 *
 * @return void
 * @uses   $GLOBALS['files']
 */
function readDirectory($path)
{
    foreach (glob($path . '/*') as $file) {
        if (!is_dir($file)) {
            $GLOBALS['files'][] = $file;
        } else {
            readDirectory($file);
        }
    }
}

$outsideDir = realpath(dirname(dirname(__FILE__)));

$version = file_get_contents($outsideDir . '/VERSION');

$api_version     = $version;
$api_state       = 'beta';

$release_version = $version;
$release_state   = 'beta';
$release_notes   = "This is an alpha release, see readme.md for examples.";

$summary     = "A PHP library for HAL generation";

$description =<<<EOF
HAL is a simple way of linking with JSON or XML.
<p/>
It provides a set of conventions for expressing hyperlinks to, and embeddedness
of, related resources - the rest of a HAL document is just plain old JSON or XML.
<p/>
HAL is a bit like HTML for machines, in that it is designed to drive many
different types of application. The difference is that HTML is intended for
presenting a graphical hypertext interface to a 'human actor', whereas HAL is
intended for presenting a machine hypertext interface to 'automated actors'.
<p/>
This document contains a formalised specification of HAL. For a friendlier, more
 pracitcal introduction to HAL you can read this article: JSON Linking with HAL
<p/>
HAL has two main components: Resources and Links.
<p/>
<ul>
<li>https://github.com/zircote/Hal</li>
<li>http://groups.google.com/group/hal-discuss</li>
<li>http://stateless.co/hal_specification.html</li>
<li>http://blog.stateless.co/post/13296666138/json-linking-with-hal</li>
<li>http://www.mnot.net/blog/2011/11/25/linking_in_json</li>
<li>https://gist.github.com/2289546</li>
</ul>
EOF;

$package = new PEAR_PackageFileManager2();

$package->setOptions(
    array(
        'filelistgenerator'       => 'file',
        'outputdirectory'         => dirname(dirname(__FILE__)),
        'simpleoutput'            => true,
        'baseinstalldir'          => '/',
        'packagedirectory'        => $outsideDir,
        'dir_roles'               => array(
            'benchmarks'          => 'doc',
            'examples'            => 'doc',
            'library'             => 'php',
            'library/Hal'         => 'php',
            'tests'               => 'test',
        ),
        'exceptions'              => array(
            'CHANGELOG'           => 'doc',
            'readme.md'           => 'doc',
            'VERSION'             => 'doc',
            'LICENSE-2.0.txt'     => 'doc',
        ),
        'ignore'                  => array(
            'build/*',
            'package.xml',
            'build.xml',
            'scripts/*',
            '.git',
            '.gitignore',
            'tests/phpunit.xml',
            'tests/build*',
            '.project',
            '.buildpath',
            '.settings',
            '*.tgz'
        )
    )
);

$package->setPackage('Hal');
$package->setSummary($summary);
$package->setDescription($description);
$package->setChannel('zircote.github.com/pear');
$package->setPackageType('php');
$package->setLicense(
    'Apache 2.0',
    'http://www.apache.org/licenses/LICENSE-2.0'
);

$package->setNotes($release_notes);
$package->setReleaseVersion($release_version);
$package->setReleaseStability($release_state);
$package->setAPIVersion($api_version);
$package->setAPIStability($api_state);
/**
 * Dependencies
 */

$maintainers = array(
    array(
        'name'  => 'Robert Allen',
        'user'  => 'zircote',
        'email' => 'zircote@gmail.com',
        'role'  => 'lead',
    )
);

foreach ($maintainers as $_m) {
    $package->addMaintainer(
        $_m['role'],
        $_m['user'],
        $_m['name'],
        $_m['email']
    );
}

$files = array(); // classes and tests
readDirectory($outsideDir . '/library');
readDirectory($outsideDir . '/tests');

$base = $outsideDir . '/';

foreach ($files as $file) {

    $file = str_replace($base, '', $file);

    $package->addReplacement(
        $file,
        'package-info',
        '@name@',
        'name'
    );

    $package->addReplacement(
        $file,
        'package-info',
        '@package_version@',
        'version'
    );
}

$files = array(); // reset global
readDirectory($outsideDir . '/library');

foreach ($files as $file) {
    $file = str_replace($base, '', $file);
    $package->addInstallAs($file, str_replace('library/', '', $file));
}


$package->setPhpDep('5.2.1');

$package->setPearInstallerDep('1.7.0');
$package->generateContents();
$package->addRelease();

if (   isset($_GET['make'])
    || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')
) {
    $package->writePackageFile();
} else {
    $package->debugPackageFile();
}