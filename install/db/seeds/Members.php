<?php

use Phinx\Seed\AbstractSeed;

class Members extends AbstractSeed
{
    public function run()
    {
        $data = [
            [
                'id'    => '21072721585b2001256a2ac',
                'username' => 'therecluse26',
                'password' => '$2y$10$uJ7r5iDWt/O16dg6pZcI7O0cO1dsAZTREoIBnUsCy6SR6nxZl001O',
                'email' => 'therecluse26@protonmail.com',
                'verified' => 1,
                'banned' => 1
            ]
        ];

        $members = $this->table('members');
        $members->insert($data)
              ->save();
    }
}
