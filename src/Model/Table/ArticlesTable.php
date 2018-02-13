<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Text;

/**
 * Articles Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\TagsTable|\Cake\ORM\Association\BelongsToMany $Tags
 *
 * @method \App\Model\Entity\Article get($primaryKey, $options = [])
 * @method \App\Model\Entity\Article newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Article[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Article|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Article patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Article[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Article findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ArticlesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)//インスタンス生成時にコンストラクタから呼び出されるメソッド
    {
        parent::initialize($config);

//        $this->setTable('articles');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        //モデルイベントのたびにcreatedやmodifiedを更新
        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsToMany('Tags', [
            'foreignKey' => 'article_id',
            'targetForeignKey' => 'tag_id',
            'joinTable' => 'articles_tags'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');//createの時は空でもOK

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')//create時は必須
            ->notEmpty('title');//空はダメ

        $validator
            ->scalar('slug')
            ->maxLength('slug', 191)
//            ->requirePresence('slug', 'create')
//            ->notEmpty('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('body')
            ->notEmpty('body');

        $validator
            ->boolean('published')
            ->allowEmpty('published');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['slug']));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    public function beforeSave($event ,$entity, $options)
    {

        if ($entity->tag_string) {
            $entity->tags = $this->_buildTags($entity->tag_string);
        }

        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            // スラグをスキーマで定義されている最大長に調整
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

    protected function _buildTags($tagString)
    {
        //入力されたタグのリストを配列にして、各要素の空白スペースを削除
//        $newTags = array_map('trim', explode(' ', $tagString));
        $newTags = array_map('trim',$this->_explodeByMultipleWords([',',' ','　','、'],$tagString));
        //空タグを削除
        $newTags = array_filter($newTags);
        //重複を削除
        $newTags = array_unique($newTags);

        $out = [];

        $query = $this->Tags->find()->where(['Tags.title IN' => $newTags]);

        //取得したレコードからtitleカラムの値だけ抽出
        foreach ($query->extract('title') as $existing) {
            $index = array_search($existing, $newTags);
            //既にタグが存在していた場合はその要素を削除
            if ($index !== false) {
                unset($newTags[$index]);
            }
        }
        // 既存のタグを追加。
        foreach ($query as $tag) {
            $out[] = $tag;
        }
        // 新しいタグを追加。
        foreach ($newTags as $tag) {
            $out[] = $this->Tags->newEntity(['title' => $tag]);
        }
        //$outの中にはTagEntityの配列が入る
        return $out;

    }

    protected function _explodeByMultipleWords($word_array,$str)
    {
        $return = array();

        //文字列を配列に入れる
        $array = array($str);

        //分割文字ごとにforeach
        foreach ($word_array as $value1){

            //文字列の配列を分割
            foreach ($array as $key => $value2) {
                $return = array_merge($return, explode($value1, $value2));

                //配列の最後になったら初期化
                if(count($array) - 1 === $key) {
                    $array = $return;
                    $return = array();
                }
            }
        }
        return $array;
    }

    public function findTagged(Query $query, array $options)
    {
        //取得するカラムを設定
        $columns = [
            'Articles.id', 'Articles.user_id', 'Articles.title',
            'Articles.body', 'Articles.published', 'Articles.created',
            'Articles.slug',
        ];

        //実行するselect文を作成($columsの値を取得する)
        $query = $query->select($columns)->distinct($columns);//重複を排除

        if(empty($options['tags'])){
            //tagsが設定されてない時はtagsが無い記事のみ取得
            $query->leftJoinWith('Tags')->where(['Tags.title is' => null]);
        }else{
            //tagsが設定されている場合はwhere in句を設定
            $query->leftJoinWith('Tags')->where(['Tags.title in' => $options['tags']]);
        }
        // group byに設定するカラムを指定してQueryを返す
        return $query->group(['Articles.id']);

    }
}
