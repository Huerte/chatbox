<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->foreignId('receiver_id')->nullable()->change();
            
            $table->foreignId('group_id')->nullable()->after('receiver_id')->constrained('groups')->cascadeOnDelete();
            $table->string('attachment_path')->nullable()->after('message');
            $table->string('attachment_type')->nullable()->after('attachment_path');
            $table->foreignId('reply_to_id')->nullable()->after('attachment_type')->constrained('chats')->nullOnDelete();
            $table->boolean('is_forwarded')->default(false)->after('reply_to_id');
        });
    }

    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->foreignId('receiver_id')->nullable(false)->change();
            
            $table->dropConstrainedForeignId('group_id');
            $table->dropColumn('attachment_path');
            $table->dropColumn('attachment_type');
            $table->dropConstrainedForeignId('reply_to_id');
            $table->dropColumn('is_forwarded');
        });
    }
};
