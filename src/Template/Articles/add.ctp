<h1>記事の追加</h1>
<?php
    echo $this->Form->create($article);//formのactionには表示中のURLのパスが入る
    echo $this->Form->control('user_id',['type' => 'hidden', 'value' => 1]);
    echo $this->Form->control('published',['type' => 'hidden', 'value' => 1]);
    echo $this->Form->control('title');
    echo $this->Form->control('body',['rows' => 3]);
    echo $this->Form->button(__('Save Article'));
//    echo $this->Form->button('Save Article');
    echo $this->Form->end();
?>
