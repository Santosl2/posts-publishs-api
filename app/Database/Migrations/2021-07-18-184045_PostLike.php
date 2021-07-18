<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PostLike extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'postId' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
				'auto_increment' => false
			],
			'userId' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => true,
			],
			'created_at datetime default current_timestamp'
		])->createTable('posts_likes');
	}

	public function down()
	{
		$this->forge->dropTable('posts_likes');

	}
}
