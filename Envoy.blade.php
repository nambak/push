@servers(['prod' => ['ubuntu@13.125.43.58']])

@task('deploy', ['on' => 'prod'])
cd /var/www/push
sudo git pull origin master
sudo composer install
@endtask
