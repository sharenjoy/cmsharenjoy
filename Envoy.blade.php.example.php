@servers(['web' => 'sharenjoy'])

@macro('deploy')
    initial
@endmacro

@task('initial', ['on' => 'web'])
    cd demo.sharenjoy.com
    mkdir public
    mkdir public/uploads
    echo 'create "public/uploads" folder successfully'
    mkdir public/uploads/thumbs
    echo 'create "public/uploads/thumbs" folder successfully'
    chmod -R 777 public/uploads
    echo 'chmod "public/uploads" folder successfully'
    ls -al public
    ls -al public/uploads
@endtask

@task('vendor', ['on' => 'web'])
    php artisan vendor/publish
    php artisan theme:create admin
@endtask

@task('update', ['on'=>'web'])
    cd demo.sharenjoy.com
    composer update sharenjoy/cmsharenjoy
    echo 'composer update done'
@endtask

@after
    @slack('sharenjoy', '', '#services', "Envoy deployed some fresh code.\n\nSite: demo.sharenjoy.com.")
@endafter

