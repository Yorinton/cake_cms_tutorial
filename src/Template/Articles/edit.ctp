<h1>記事編集</h1>
<?php
    echo $this->Form->create($article);
    echo $this->Form->control('title',['value' => $article->title]);
    echo $this->Form->control('body',['rows' => 3,'value' => $article->title]);
//    echo $this->Form->control('tags._ids',['options' => $tags]);
    echo $this->Form->control('tag_string', ['type' => 'text']);
    echo $this->Form->button(__('Edit Article'));
//    echo $this->Text->toList(h($allTags),'/');
    echo $this->Form->end();

    echo $this->Html->link('記事一覧',['action' => 'index']);

?>
