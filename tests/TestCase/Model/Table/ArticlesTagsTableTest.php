<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArticlesTagsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ArticlesTagsTable Test Case
 */
class ArticlesTagsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ArticlesTagsTable
     */
    public $ArticlesTags;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.articles_tags',
        'app.articles',
        'app.users',
        'app.tags'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ArticlesTags') ? [] : ['className' => ArticlesTagsTable::class];
        $this->ArticlesTags = TableRegistry::get('ArticlesTags', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ArticlesTags);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
