<?php

use Phinx\Seed\AbstractSeed;

class Members extends AbstractSeed
{
    public function run()
    {
        $data = [
            [
                'body'    => 'foo',
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'body'    => 'bar',
                'created' => date('Y-m-d H:i:s'),
            ]
        ];

        $members = $this->table('members');
        $members->insert($data)
              ->save();
    }
}
