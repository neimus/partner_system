<?php
/**
 * Created by PhpStorm.
 * User: Saburov Denis
 * Date: 26.03.18
 */

namespace app\migrations;

interface MigrationInterface
{
    public function getOptions(): ?string;

    public function beginCreateTable(): void;

    public function endCreateTable(): void;

    public function beginUp(): void;

    public function endUp(): void;

    public function beginDown(): void;

    public function endDown(): void;
}