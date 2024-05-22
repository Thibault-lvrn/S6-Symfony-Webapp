<?php
namespace App\Tests\Entity;

use App\Entity\Pdf;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PdfTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $pdf = new Pdf();
        $title = 'Titre';
        $createdAt = new \DateTimeImmutable();
        $user = new User();

        $pdf->setTitle($title);
        $pdf->setCreatedAt($createdAt);
        $pdf->setUserId($user);

        $this->assertEquals($title, $pdf->getTitle());
        $this->assertEquals($createdAt->format('Y-m-d H:i:s'), $pdf->getCreatedAt()->format('Y-m-d H:i:s'));
        $this->assertEquals($user, $pdf->getUserId());
    }
}