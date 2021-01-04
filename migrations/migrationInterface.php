<?php

interface migrationInterface
{
    public function up() : string;
    
    public function down() : string;
}