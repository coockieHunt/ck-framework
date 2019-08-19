<?php


use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class PostSeeder extends AbstractSeed
{
    public function run()
    {
        $data = [];
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 100; ++$i) {
            $date = $faker->unixTime('now');
            $data[] = [
                'name' => $faker->catchPhrase,
                'id_category' => rand(0, 10),
                'slug' => $faker->slug,
                'content' => $faker->text(3000),
                'active' => true,
                'create_at' => date('Y-m-d H:i:s', $date),
                'update_at' => date('Y-m-d H:i:s', $date)
            ];
        }
        $this->table('posts')
            ->insert($data)
            ->save();
    }
}
