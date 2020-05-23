<?php $this->layout('layout') ?>

    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    <?= $category['title'];?>
                </h1>
                <h2 class="subtitle">
                    Картинки по категориям
                </h2>
            </div>
        </div>
    </section>
    <section class="section main-content">
        <div class="container">
            <div class="columns  is-multiline">
                <?php foreach($photos as $photo):?>
                    <div class="column is-one-quarter">
                        <div class="card">
                            <div class="card-image">
                                <figure class="image is-4by3">
                                    <a href="/photos/<?= $photo['id'];?>">
                                        <img src="<?= getImage($photo['image'])?>">
                                    </a>
                                </figure>
                            </div>
                            <div class="card-content">
                                <div class="media">
                                    <div class="media-left">
                                        <p class="title is-5"><a href="/category/<?= $category['id'];?>"><?= $category['title'];?></a></p>
                                    </div>
                                    <div class="media-right">
                                        <p  class="is-size-7">Размер: <?= $photo['dimensions'];?></p>
                                        <time datetime="2016-1-1" class="is-size-7">Добавлено: <?= uploadedDate($photo['date']);?></time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
            <?= paginator($paginator); ?>
        </div>
    </section>
