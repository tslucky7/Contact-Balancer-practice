<?php
declare(strict_types=1);

namespace App\Utils;

use Symfony\Component\Uid\Ulid;
class IdGenerator {
  public static function ulid(): string {
    return Ulid::generate();
  }
}
