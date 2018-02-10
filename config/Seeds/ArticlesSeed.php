<?php
use Migrations\AbstractSeed;

/**
 * Articles seed.
 */
class ArticlesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        for($i=1001;$i <= 100000;$i++) {
            $data[] = [
                "user_id" => mt_rand(2,100),
                "title" => "title".$i,
                "slug" => "slug".$i,
                "body" => "body".$i,
                "published" => 1,
                "created" => date("Y-m-d h:i:s"),
                "modified" => date("Y-m-d h:i:s")
            ];
        }

        $table = $this->table('articles');
        $table->insert($data)->save();
    }
}
