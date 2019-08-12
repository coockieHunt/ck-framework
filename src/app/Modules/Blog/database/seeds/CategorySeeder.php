<?php


use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class CategorySeeder extends AbstractSeed
{
    public function run()
    {
        $data = [];
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; ++$i) {
            $date = $faker->unixTime('now');
            $data[] = [
                'name' => $faker->catchPhrase,
                'slug' => $faker->slug,
                'update_at' => date('Y-m-d H:i:s', $date),
                'create_at' => date('Y-m-d H:i:s', $date)
            ];
        }
        $this->table('category')
            ->insert($data)
            ->save();
    }
}
