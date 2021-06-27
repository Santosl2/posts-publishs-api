<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPosts extends Migration
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
			'content' => [
				'type' => 'TEXT',
				'null' => true
			],
			'publishedBy' => [
				'type' => 'VARCHAR',
				'constraint' => 24,
			],
			'updated_at' => [
				'type' => 'datetime',
				'null' => true,
			],
			'created_at datetime default current_timestamp'
		])
			->addPrimaryKey('id')
			->createTable('posts');
	}

	public function down()
	{
		//
	}
}
