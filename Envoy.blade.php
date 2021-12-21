@servers(['prod' => ['ubuntu@3.38.102.203']])

@task('deploy', ['on' => 'prod'])
cd /var/www/push
sudo git pull origin master
sudo composer install
@endtask
