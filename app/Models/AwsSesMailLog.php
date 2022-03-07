<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwsSesMailLog extends Model
{
    use HasFactory;
    protected $table = 'aws_ses_mail_log';
    protected $casts = [
        'mail' => 'array',
        'content' => 'array',
    ];
}
