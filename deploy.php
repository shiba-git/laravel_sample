<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'laravel_sample');

// Project repository
set('repository', 'git@github.com:shiba-git/laravel_sample.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', ['vendor']);

// Writable dirs by web server 
add('writable_dirs', ['bootstrap/cache', 'storage']);
set('allow_anonymous_stats', false);

// Hosts

host('3.112.140.196')
    ->port('22')
    ->user('ec2-user')
    ->stage('production')
    ->set('branch', 'master')
    ->identityFile('/var/www/html/aws-barkey-tokyo.pem')
    ->set('deploy_path', '/{{application}}');    
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

// before('deploy:symlink', 'artisan:migrate');

