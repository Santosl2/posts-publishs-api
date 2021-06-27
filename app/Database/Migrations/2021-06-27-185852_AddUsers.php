<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsers extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => true
			],
			'username' => [
				'type' => 'VARCHAR',
				'constraint' => '24',
				'null' => false,
				'unique' => true
			],
			'password' => [
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => false
			],
			'email' => [
				'type' => 'VARCHAR',
				'constraint' => '100',
				'null' => false,
				'unique' => true
			],

			'created_at datetime default current_timestamp'
		])
			->addPrimaryKey('id')
			->createTable('users');
	}

	public function down()
	{
		$this->forge->dropTable('users');
	}
}
