<?php
use Migrations\AbstractSeed;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
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
        for($i=2;$i <= 100;$i++) {
            $data[] = [
                    'id' => $i,
                    'email' => 'cakephp'. $i .'@example.com',
                    'password' => 'sekret'.$i,
                    'created' => date("Y-m-d h:i:s"),
                    'modified' => date("Y-m-d h:i:s"),
                ];
        }

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
