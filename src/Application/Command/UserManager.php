<?php
declare(strict_types=1);

namespace App\Application\Command;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $username
     * @param string $data
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function recordEvent(string $username, string $data)
    {
        $sql = "INSERT INTO events(username, data, is_read)
                VALUES (:username, :data, 0)";
        $this->em->getConnection()
                ->prepare($sql)
                ->execute([
                    ':username' => $username,
                    ':data' => $data
                    ]);
    }
}