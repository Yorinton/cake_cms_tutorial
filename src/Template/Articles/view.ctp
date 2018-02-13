<h1><?= h($article->title) ?></h1>
<p><?= h($article->body) ?></p>
<p><small>作成日時：<?= $article->created->format(DATE_RFC850) ?></small></p>
<p><small>最終更新日時：<?= $article->modified->format(DATE_RFC850) ?></small></p>
<p><?= $this->Html->link('Edit',['action' => 'edit',$article->slug]) ?></p>
<p><?= $this->Html->link('記事一覧',['action' => 'index']); ?></p>