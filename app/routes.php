<?php
// Routes

$app->get('/', App\Action\HomeAction::class)->setName('home');

$app->post('/', App\Action\HomeProcessAction::class);

$app->get('/about', App\Action\AboutAction::class);

$app->get('/{id}', App\Action\DetailAction::class)->setName('detail');