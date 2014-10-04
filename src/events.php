<?php

/**
 * To register some events
 */

/* ====================================== */


// Event::listen('filer.createAlbum', function($model)
// {
//     $result = Filer::createFolder(0, $model->title, 'local', '', 1);
//     $model->album_id = $result['data']['id'];
//     $model->save();
// });

Event::listen('cmsharenjoy.afterAction', 'Sharenjoy\Cmsharenjoy\Events\ControllerAfterActionEvent');