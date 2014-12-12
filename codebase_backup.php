#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$configFile = __DIR__ . '/config.php';
if (! file_exists($configFile)) {
    die('config.php not found.');
}

$config = include $configFile;

// 1. Load list of projects
// 2. create folder for each project
// 3. Load list of repositories for each project
// 4. clone each repository into project folder (or fetch if repository was already cloned)

$requestOptions = [
    'auth'    => new \Requests_Auth_Basic([$config['CODEBASE_USER'], $config['CODEBASE_TOKEN']]),
    'timeout' => 60,
];

$projectsResponse = \Requests::get($config['CODEBASE_BASE_URL'] . "/projects", [], $requestOptions);

if (! $projectsResponse->success) {
    echo "ERROR: Cannot fetch projects list!" . PHP_EOL;
    print_r($projectsResponse);
    die;
}

$projectsXml = new SimpleXMLElement($projectsResponse->body);

foreach ($projectsXml as $project) {

    $projectSlug = (string) $project->permalink;

    echo 'Backup project "' . (string) $project->name . '"' . PHP_EOL;

    $backupDirectory = $config['BACKUP_DIR'] . DIRECTORY_SEPARATOR . $projectSlug;

    echo '- creating/checking backup directory: ' . $backupDirectory . ' ...' . PHP_EOL;

    if (! is_dir($backupDirectory)) {
        mkdir($backupDirectory, 0770, true);
    }

    if (! is_dir($backupDirectory)) {
        echo "ERROR: cannot create backup directory: " . $backupDirectory . PHP_EOL;
        die;
    }

    // load list of repositories

    $repositoriesResponse = \Requests::get($config['CODEBASE_BASE_URL'] . "/" . $projectSlug . "/repositories", [], $requestOptions);

    if (! $repositoriesResponse->success) {
        echo "ERROR: Cannot fetch repositories list!" . PHP_EOL;
        print_r($repositoriesResponse);
        die;
    }

    $repositoriesXml = new SimpleXMLElement($repositoriesResponse->body);

    foreach ($repositoriesXml as $repository) {

        $property = "clone-url";
        $cloneUrl = (string) $repository->{$property};
        $repositorySlug = (string) $repository->permalink;

        $repositoryBackupDirectory = $backupDirectory . DIRECTORY_SEPARATOR . $repositorySlug . ".git";


        if (is_dir($repositoryBackupDirectory)) {
            echo "- updating repository " . (string) $repository->name . PHP_EOL;
            passthru("cd " . escapeshellarg($repositoryBackupDirectory) . " ; " . $config['GIT_BIN'] . " fetch");
        } else {
            echo "- cloning repository " . (string) $repository->name . PHP_EOL;
            passthru("cd " . escapeshellarg($backupDirectory) . " ; " . $config['GIT_BIN'] . " clone --mirror '{$cloneUrl}'");
        }

    }

}