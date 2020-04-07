<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'recipe/npm.php';
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

task('npm:run', function (): void {
    run('cd {{release_path}} && chmod 707 public');
    run('cd {{release_path}} && npm install');
});

// Tasks
task('build', function () {
    run('cd {{release_path}} && build');
});

task('deploy', [
    'deploy:info',
    'deploy:prepare', 
    'deploy:lock', 
    'deploy:release', 
    'deploy:update_code', 
    'deploy:shared', 
    'deploy:writable',
    'deploy:vendors', 
    'deploy:clear_paths',
    'deploy:symlink', 
    'deploy:unlock',  
    'cleanup',
    'success',
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

//before('deploy:symlink', 'artisan:migrate');

