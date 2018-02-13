<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Collection\Collection;

/**
 * Article Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string $body
 * @property bool $published
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Tag[] $tags
 */
class Article extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'title' => true,
        'slug' => false,
        'body' => true,
        'published' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'tags' => true,
        'tag_string' => true,
    ];

    // ctpの$this->Form->controlから呼び出される
    protected function _getTagString()// tag_string => _getTagString()を呼び出す
    {
        //既にtag_stringが存在する場合
        if(isset($this->_properties['tag_string'])){
            return $this->_properties['tag_string'];
        }
        //記事にtagが設定されて無い場合
        if(empty($this->tags)){
            return '';
        }

        $tags = new Collection($this->tags);
        $str = $tags->reduce(function($string, $tag){
            return $string.$tag->title.', ';
        },'');

//        $this->_properties['tag_string'] = $str;
        return trim($str,', ');

    }
}
