<?php

use Phinx\Migration\AbstractMigration;

class RolePermissions extends AbstractMigration
{
    public function up()
    {
        $role_permissions = $this->table('{$tblprefix}role_permissions');
        $role_permissions->addColumn('role_id', 'integer', ['limit' => 11])
        ->addColumn('permission_id', 'integer', ['limit' => 11])
        ->addIndex(['permission_id', 'role_id'], ['unique' => true])
        ->addForeignKey('permission_id', '{$tblprefix}permissions', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
        ->addForeignKey('role_id', '{$tblprefix}roles', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
        ->save();
    }

    public function down()
    {
        $this->dropTable('{$tblprefix}role_permissions');
    }
}
